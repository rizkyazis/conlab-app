<?php

namespace App\Http\Controllers;

use App\Courses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index(){
        $course = DB::table('courses')
            ->join('course_tag', 'courses.id', '=', 'course_tag.course_id')
            ->join('tag', 'course_tag.tag_id', '=', 'tag.id')->get();
        return view('index')
            ->with('courses', $course);
    }
}
