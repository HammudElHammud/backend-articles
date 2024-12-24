<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    /**
     * Category Index Request
     *
     * This endpoint allows you to get Categories.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request){
        $data = Category::select('name', 'slug', 'id')->withCount('articles')->get();

         return $this->successResponse(__('successfully'), $data);
    }
}
