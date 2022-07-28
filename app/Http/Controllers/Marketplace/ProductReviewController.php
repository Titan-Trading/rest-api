<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    /**
     * Get list of product reviews
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = ProductReview::query();

        // search by product
        if($request->has('product_id') && $request->product_id) {
            $query->whereProductId($request->product_id);
        }
        // show only reviews created by current user
        $query->whereReviewerId($request->user()->id);

        $reviews = $query->get();

        return response()->json($reviews);
    }

    /**
     * Create a review
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => ['required', 'exists,products,id'],
            'featured_image_id' => ['exists:images,id'],
            'title' => ['required'],
            'text' => ['required'],
            'rating' => ['decimal']
        ], [
            'product_id_required' => 'Product id is required',
            'product_id_exists' => 'Product must exist',
            'featured_image_id_exists' => 'Featured image must exist',
            'title_required' => 'Title is required',
            'text_required' => 'Text is a required',
            'rating_decimal' => 'Rating must be a decimal value'
        ]);

        $review = new ProductReview();
        $review->reviewer_id = $request->user()->id;
        $review->product_id = $request->product_id;
        $review->featured_image_id = $request->featured_image_id ? $request->featured_image_id : null;
        $review->rating = $request->rating ? $request->rating : 0.00;
        $review->is_featured = false;
        $review->status = 'pending';
        $review->review_name = $request->user()->name;
        $review->title = $request->title;
        $review->text = $request->text;
        $review->save();

        return response()->json($review, 201);
    }

    /**
     * Update a review by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $review = ProductReview::find($id);
        if(!$review) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if the review was created by the current user
        if($review->reviewer_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to make changes to the review'
            ], 403);
        }

        $this->validate($request, [
            'product_id' => ['required', 'exists,products,id'],
            'featured_image_id' => ['exists:images,id'],
            'title' => ['required'],
            'text' => ['required'],
            'rating' => ['decimal']
        ], [
            'product_id_required' => 'Product id is required',
            'product_id_exists' => 'Product must exist',
            'featured_image_id_exists' => 'Featured image must exist',
            'title_required' => 'Title is required',
            'text_required' => 'Text is a required',
            'rating_decimal' => 'Rating must be a decimal value'
        ]);

        $review->featured_image_id = $request->featured_image_id ? $request->featured_image_id : $review->featured_image_id;
        $review->rating = $request->rating ? $request->rating : $review->rating;
        $review->is_featured = $review->is_featured;
        $review->status = 'pending';
        $review->review_name = $request->user()->name;
        $review->title = $request->title;
        $review->text = $request->text;
        $review->save();

        return response()->json($review);
    }

    /**
     * Delete a review by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $review = ProductReview::find($id);
        if(!$review) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if the review was created by the current user
        if($review->reviewer_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to make changes to the review'
            ], 403);
        }

        $review->delete();

        return response()->json($review);
    }
}