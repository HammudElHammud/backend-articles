<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\SaveFavoriteRequest;
use App\Models\Favorite;
use App\Models\NewsFeed;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleFavoriteController extends Controller
{
    /**
     * getUserFavorites Index Request
     *
     * This endpoint allows you to get Favorite articles.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserFavorites(Request $request ): JsonResponse
    {
        $user = Auth::user();

        $data = Favorite::with('article')
        ->where('user_id', $user->id)
            ->get();

        return $this->successResponse('Successfully' , $data);
    }

    /**
     * saveFavorite Index Request
     *
     * This endpoint allows you to save Favorite articles.
     *
     * @param SaveFavoriteRequest $request
     * @return JsonResponse
     */

    public function saveFavorite(SaveFavoriteRequest $request): JsonResponse
    {
        $user = Auth::user();

        $data = $request->validated();

        DB::beginTransaction();

        try {
            $responseData = [];


            if (isset($data['article_id'])) {
                $favorite = Favorite::create([
                    'user_id' => $user->id,
                    'article_id' => $data['article_id'],
                ]);
                $responseData['favorite'] = $favorite;
            }

            if (isset($data['category_id']) || isset($data['source_id'])) {
                $newsFeed = NewsFeed::create([
                    'user_id' => $user->id,
                    'category_id' => $data['category_id'] ?? null,
                    'source_id' => $data['source_id'] ?? null,
                ]);
                $responseData['newsFeed'] = $newsFeed;
            }

            DB::commit();

            return $this->successResponse(__('Success'), $responseData);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error saving favorite or news feed: ' . $e->getMessage());

            return $this->errorResponse(__('An error occurred'), $e->getMessage());
        }
    }



}
