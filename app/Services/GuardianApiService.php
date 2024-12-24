<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Class GuardianApiService.
 */
class GuardianApiService
{
    protected $newsapi;
    protected $apiKey;

    public function __construct($category = null)
    {
        $this->apiKey = env('GUARDIAN_API_KEY', 'a9229c59-de77-4bd2-b5b6-6ba7614e1c9d');
        $tag = $category ? "{$category}/{$category}" : 'business/business';
        $this->newsapi = Http::get("https://content.guardianapis.com/search", [
            'api-key' => $this->apiKey,
            'tag' => $tag,
            'show-fields' => 'trailText,thumbnail,headline,shortUrl,bodyText',
            'page-size'  => 100,
            'page'       => 1
        ]);
    }

    public function getContent()
    {
        if ($this->newsapi->successful()) {
            return $this->newsapi->json()['response']['results'];
        } else {
            abort($this->newsapi->status(), 'API request failed');
        }
    }

}
