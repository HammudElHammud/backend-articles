<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Source;
use App\Services\GuardianApiService;
use App\Services\NewsApiService;
use App\Services\NewYorkTimeApiService;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $sources = Source::all();
        $openNewsSource = $sources->firstWhere('name', 'OpenNews');
        $guardianSource = $sources->firstWhere('name', 'The Guardian');
        $newYorkTimesSource = $sources->firstWhere('name', 'New York Times');

        foreach ($categories as $category){
            $NewsApiService = new NewsApiService($category->name);
            $GuardianApiService = new GuardianApiService($category->slug);
            $NewYorkTimeApiService = new NewYorkTimeApiService($category->name);
            $NewYorkTimeApiServiceArticles = $NewYorkTimeApiService->getContent();
            $GuardianApiServiceArticles = $GuardianApiService->getContent();
            $articles = $NewsApiService->getContent();
            foreach ($articles as $articleData) {
                $publishedAt = Carbon::parse($articleData['publishedAt'])->format('Y-m-d H:i:s');

                Article::create([
                    'author' => $articleData['author'] ?? 'Unknown',
                    'title' => $articleData['title'],
                    'url' => $articleData['url'],
                    'urlToImage' => $articleData['urlToImage'],
                    'publishedAt' => $publishedAt,
                    'content' => $articleData['content'],
                    'description' => $articleData['description'],
                    'user_id' => null,
                    'source_id' => $openNewsSource->id ?? null,
                    'category_id' => $category->id
                ]);
            }

            foreach ($GuardianApiServiceArticles as $articleData) {
                $publishedAt = Carbon::parse($articleData['webPublicationDate'])->format('Y-m-d H:i:s');
                Article::create([
                    'author' => $articleData['fields']['headline'] ?? 'No author',
                    'title' => $articleData['fields']['headline'] ?? 'No title',
                    'url' => $articleData['fields']['shortUrl'] ?? 'No URL',
                    'urlToImage' => $articleData['fields']['thumbnail'] ?? null,
                    'publishedAt' =>  $publishedAt,
                    'content' => $articleData['fields']['bodyText'] ?? 'No content',
                    'description' => $articleData['fields']['trailText'] ?? 'No description',
                    'user_id' => null,
                    'source_id' => $guardianSource->id ?? null,
                    'category_id' => $category->id,
                ]);
            }

            foreach ($NewYorkTimeApiServiceArticles as $articleData) {
                $publishedAt = Carbon::parse($articleData['pub_date'])->format('Y-m-d H:i:s');

                Article::create([
                    'author' => $articleData['byline']['original'] ?? 'Unknown',
                    'title' => $articleData['headline']['main'],
                    'url' => $articleData['web_url'],
                    'urlToImage' => !empty($articleData['multimedia']) ?  $articleData['multimedia'][0]['url'] : "",
                    'publishedAt' => $publishedAt,
                    'content' => $articleData['abstract'],
                    'description' => $articleData['lead_paragraph'],
                    'user_id' => null,
                    'source_id' => $newYorkTimesSource->id ?? null,
                    'category_id' => $category->id
                ]);
            }
        }
    }
}
