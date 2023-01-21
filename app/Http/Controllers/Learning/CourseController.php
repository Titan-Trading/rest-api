<?php

namespace App\Http\Controllers\Learning;

use App\Http\Controllers\Controller;
use App\Models\Learning\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Get list of courses
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $courses = Course::all();
        
        return response()->json($courses);
    }
}