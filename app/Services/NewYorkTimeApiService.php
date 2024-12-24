<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Class NewYorkTimeApiService.
 */
class NewYorkTimeApiService
{
    protected $newsapi;
    protected $apiKey;

    public function __construct($category = null)
    {
        $this->apiKey = env('NEW_YORK_TIME_API_KEY', 'uFxhnK7PeAI2JpJLdjrGmvsrc3NEMWA6');
        $this->newsapi = Http::get("api.nytimes.com/svc/search/v2/articlesearch.json", [
            'api-key' => $this->apiKey,
            'fq' => $category,
            'page-size'  => 100,
            'page'       => 1
        ]);
    }

    public function getContent()
    {
        if ($this->newsapi->successful()) {
            return $this->newsapi->json()['response']['docs'];
        } else {
            \Log::error('API Request failed', [
                'status' => $this->newsapi->status(),
                'response' => $this->newsapi->body(),
            ]);
            abort($this->newsapi->status(), 'API request failed');
        }
    }
}
