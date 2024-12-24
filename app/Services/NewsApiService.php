<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

/**
 * Class NewsApiService.
 */

class NewsApiService
{
    protected $newsapi;
    protected $apiKey;

    public function __construct($category = null)
    {
        $this->apiKey = env('NEWS_API_KEY', 'b8e3cf2d1a3b4e688ca51a696691e2da');

        $this->newsapi = Http::get("https://newsapi.org/v2/top-headlines", [
            'apiKey' => $this->apiKey,
            'category' => $category,
            'page-size'  => 100,
            'page'       => 1
        ]);
    }

    public function getContent()
    {
        if ($this->newsapi->successful()) {
            return $this->newsapi->json()['articles'];
        } else {
            abort($this->newsapi->status(), 'API request failed');
        }
    }
}

