<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Get list of news articles
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $articles = Article::query()->get();

        return response()->json($articles);
    }

    /**
     * Get a news article by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $article = Article::find($id);
        if(!$article) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($article);
    }
}