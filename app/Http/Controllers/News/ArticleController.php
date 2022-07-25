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
     * Create a news article
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'source_id' => ['required', 'exists:sources,id'],
            'feed_id' => ['required', 'exists:feeds,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'author_id' => ['required', 'exists:authors,id'],
            'title' => ['required', 'unique:articles,title'],
            'url' => 'required',
            'excerpt' => 'required',
            'content_html' => 'required',
            'published_at' => 'required'
        ], [
            'source_id_required' => 'Source id is required',
            'source_id_exists' => 'Source must exist',
            'feed_id_required' => 'Feed id is required',
            'feed_id_exists' => 'Feed must exist',
            'category_id_required' => 'Category id is required',
            'category_id_exists' => 'Category must exist',
            'author_id_required' => 'Author id is required',
            'author_id_exists' => 'Author must exist',
            'title_required' => 'Title is required',
            'title_unique' => 'Title must be unique',
            'url_required' => 'Url is required',
            'excerpt_required' => 'Excerpt is required',
            'content_html_required' => 'Content html is required',
            'published_at' => 'Published at is required'
        ]);

        $article = new Article();
        $article->source_id = $request->source_id;
        $article->feed_id = $request->feed_id;
        $article->category_id = $request->category_id;
        $article->author_id = $request->author_id;
        $article->title = $request->title;
        $article->url = $request->url;
        $article->excerpt = $request->excerpt;
        $article->content_html = $request->content_html;
        $article->content_text = $request->content_text ? $request->content_text : null;
        $article->published_at = $request->published_at;
        $article->save();

        return response()->json($article, 201);
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

    /**
     * Update a news article by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $article = Article::find($id);
        if(!$article) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $titleRules = ['required'];
        if($article->title != $request->title)
        {
            $titleRules[] = 'unique:articles,title';
        }

        $this->validate($request, [
            'source_id' => ['required', 'exists:sources,id'],
            'feed_id' => ['required', 'exists:feeds,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'author_id' => ['required', 'exists:authors,id'],
            'title' => $titleRules,
            'url' => 'required',
            'excerpt' => 'required',
            'content_html' => 'required',
            'published_at' => 'required'
        ], [
            'source_id_required' => 'Source id is required',
            'source_id_exists' => 'Source must exist',
            'feed_id_required' => 'Feed id is required',
            'feed_id_exists' => 'Feed must exist',
            'category_id_required' => 'Category id is required',
            'category_id_exists' => 'Category must exist',
            'author_id_required' => 'Author id is required',
            'author_id_exists' => 'Author must exist',
            'title_required' => 'Title is required',
            'title_unique' => 'Title must be unique',
            'url_required' => 'Url is required',
            'excerpt_required' => 'Excerpt is required',
            'content_html_required' => 'Content html is required',
            'published_at' => 'Published at is required'
        ]);

        $article->source_id = $request->source_id;
        $article->feed_id = $request->feed_id;
        $article->category_id = $request->category_id;
        $article->author_id = $request->author_id;
        $article->title = $request->title;
        $article->url = $request->url;
        $article->excerpt = $request->excerpt;
        $article->content_html = $request->content_html;
        $article->content_text = $request->content_text ? $request->content_text : null;
        $article->published_at = $request->published_at;
        $article->save();

        return response()->json($article);
    }

    /**
     * Delete a news article by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $article = Article::find($id);
        if(!$article) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $article->delete();

        return response()->json($article);
    }
}