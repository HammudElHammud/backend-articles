<?php

use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Article\ArticleFavoriteController;
use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Auth\SettingUserController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\NewsFeed\NewsFeedController;
use App\Http\Controllers\Source\SourceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::name('auth.')->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::post('login', [AuthenticatedController::class, 'store'])->name('login');
        Route::apiResource('article', ArticleController::class)->only(['index', 'show']);
        Route::get('recent/article', [ArticleController::class, 'recentArticles']);
        Route::get('category', [CategoryController::class, 'index'])->name('categories');
        Route::get('source', [SourceController::class, 'index'])->name('source');
        Route::post('register', [RegisterUserController::class, 'store'])->name('register');
    });

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthenticatedController::class, 'destroy'])->name('logout');
        Route::post('update-password', [SettingUserController::class, 'update'])->name('setting');
        Route::post('save-favorite', [ArticleFavoriteController::class, 'saveFavorite']);
        Route::get('user-favorites', [ArticleFavoriteController::class, 'getUserFavorites']);
        Route::post('/save-news-feed', [NewsFeedController::class, 'saveNewsFeed']);
        Route::get('/user-news-feed', [NewsFeedController::class, 'getUserNewsFeed']);


    });

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
