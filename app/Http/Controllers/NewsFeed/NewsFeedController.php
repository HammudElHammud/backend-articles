<?php

namespace App\Http\Controllers\NewsFeed;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsFeed\SaveNewsFeedRequest;
use App\Models\Article;
use App\Models\NewsFeed;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NewsFeedController extends Controller
{

    /**
     * getUserNewsFeed Index Request
     *
     * This endpoint allows you to get news feed.
     *
     * @param SaveNewsFeedRequest $request
     * @return JsonResponse
     */
    public function getUserNewsFeed(Request $request): JsonResponse
    {

        $user = Auth::user();

        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);

        $newsFeeds = NewsFeed::where('user_id', $user->id)
            ->get();
        $data = [];

        if ($newsFeeds->isEmpty()) {

            return $this->successResponse(__('There no results'), $data);
        }
        $query = Article::query();
        foreach ($newsFeeds as $newsFeed) {
            $query->orWhere(function ($query) use ($newsFeed) {
                $query->where('category_id', $newsFeed->category_id)
                    ->orWhere('source_id', $newsFeed->source_id);

                if ($newsFeed->author) {
                    $query->where('author', $newsFeed->author);
                }
            });
        }
        $data = $query->paginate($perPage, ['*'], 'page', $page);


        return $this->successResponse(__('Successfully'), $data);
    }


    /**
     * saveNewsFeed Index Request
     *
     * This endpoint allows you to save news feed.
     *
     * @param SaveNewsFeedRequest $request
     * @return JsonResponse
     */
    public function saveNewsFeed(SaveNewsFeedRequest $request): JsonResponse
    {
        $user = Auth::user();
        $data = $request->validated();
        $addedData = [];

        DB::beginTransaction();

        try {
            if (!empty($data['categories']) && !empty($data['sources'])) {
                foreach ($data['categories'] as $categoryId) {
                    foreach ($data['sources'] as $sourceId) {
                        $exists = NewsFeed::where('user_id', $user->id)
                            ->where('category_id', $categoryId)
                            ->where('source_id', $sourceId)
                            ->exists();

                        if (!$exists) {
                            $newsFeed = NewsFeed::create([
                                'author' => $data['author'],
                                'user_id' => $user->id,
                                'category_id' => $categoryId,
                                'source_id' => $sourceId,
                            ]);
                            $addedData[] = $newsFeed;
                        }
                    }
                }
            } elseif (!empty($data['categories'])) {
                foreach ($data['categories'] as $categoryId) {
                    $exists = NewsFeed::where('user_id', $user->id)
                        ->where('category_id', $categoryId)
                        ->whereNull('source_id')
                        ->exists();

                    if (!$exists) {
                        $newsFeed = NewsFeed::create([
                            'author' => $data['author'],
                            'user_id' => $user->id,
                            'category_id' => $categoryId,
                            'source_id' => null,
                        ]);
                        $addedData[] = $newsFeed;
                    }
                }
            } elseif (!empty($data['sources'])) {
                foreach ($data['sources'] as $sourceId) {
                    $exists = NewsFeed::where('user_id', $user->id)
                        ->whereNull('category_id')
                        ->where('source_id', $sourceId)
                        ->exists();

                    if (!$exists) {
                        $newsFeed = NewsFeed::create([
                            'author' => $data['author'],
                            'user_id' => $user->id,
                            'category_id' => null,
                            'source_id' => $sourceId,
                        ]);
                        $addedData[] = $newsFeed;
                    }
                }
            } else {
                $exists = NewsFeed::where('user_id', $user->id)
                    ->whereNull('category_id')
                    ->whereNull('source_id')
                    ->exists();

                if (!$exists) {
                    $newsFeed = NewsFeed::create([
                        'author' => $data['author'],
                        'user_id' => $user->id,
                        'category_id' => null,
                        'source_id' => null,
                    ]);
                    $addedData[] = $newsFeed;
                }
            }

            DB::commit();
            return $this->successResponse(__('Successfully', $addedData));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(__('An error occurred'), $e->getMessage());
        }
    }
}
