<?php

namespace App\Http\Controllers\Source;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SourceController extends Controller
{


    /**
     * Source Index Request
     *
     * This endpoint allows you to get Source.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $sources = Source::select('name', 'id')->withCount('articles')->get();

        return $this->successResponse(__('Successfully') , $sources);
    }
}
