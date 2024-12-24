<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleIndexRequest;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class ArticleController extends Controller
{
    /**
     * Article Index Request
     *
     * This endpoint allows you to get articles.
     *
     * @param ArticleIndexRequest $request
     * @return JsonResponse
     */
    public function index(ArticleIndexRequest $request): JsonResponse
    {
        $query = Article::query();

        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('content', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }


        if ($request->has('start_date') && $request->has('end_date')
            && !isEmpty($request->input('start_date')) && !isEmpty($request->input('end_date'))
        ) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('publishedAt', [$startDate, $endDate]);
        }

        if ($request->has('category_id') && !is_null($request->input('category_id'))) {
            $categoryId = $request->input('category_id');
            $query->where('category_id', $categoryId);
        }

        if ($request->has('source_id') && !is_null($request->input('source_id'))) {
            $sourceId = $request->input('source_id');
            $query->where('source_id', $sourceId);
        }


        $perPage = $request->input('per_page', 15);
        $page = $request->input('page', 1);

        $query->with('category:name,id');
        $data = $query->paginate($perPage, ['*'], 'page', $page);

        return $this->successResponse(__('Successfully'), $data);
    }


    /**
     * Show Article by id
     * @param Article $id
     * @return JsonResponse
     */
    public function show(Article $article): JsonResponse
    {
        $article = Article::with('category')->find($article->id);
        $relatedArticles = Article::where('category_id', $article->category_id)
            ->orderBy('publishedAt', 'desc')
            ->limit(6)
            ->get();

        if (!$article) {
            return $this->successResponse(__('Article not found'), 404);
        }
        return $this->successResponse(__('Successfully'),
            [
                'article' => $article,
                'relatedArticles' => $relatedArticles
            ]);
    }

    /**
     *
     * @return JsonResponse
     */
    public function recentArticles(Request $request): JsonResponse
    {
        $recentArticle = Article::
            orderBy('publishedAt', 'desc')
            ->limit(4)->get();

        return $this->successResponse(__('Successfully'), $recentArticle);

    }

}
