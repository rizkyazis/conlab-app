<?php

namespace App\Http\Controllers\API;

use App\Accounts;
use App\Courses;
use App\CourseSections;
use App\Http\Controllers\Controller;
use App\SectionLessons;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScoreController extends Controller
{
    public function getLanguages(){
        $tags = Tag::all(['id', 'name']);

        return response()->json([
            'status' => true,
            'tags' => $tags,
        ]);
    }

    public function getCourses(Request $request){
        $rules = [
            'tag_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $account = Accounts::where('user_id', auth()->id())->first();

        $courses = Courses::whereHas('enrolls', function($query) use($account){
            $query->where('account_id', $account->id);
        })->select('id', 'title')->whereHas('course_tags', function($query) use($request){
            $query->where('tag_id', $request->tag_id);
        })->get();

        return response()->json([
            'status' => true,
            'courses' => $courses
        ]);
    }

    public function getSections(Request $request){
        $rules = [
            'course_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $account = Accounts::where('user_id', auth()->id())->first();

        $section = CourseSections::whereHas('courses.enrolls', function($query) use($account){
            $query->where('enroll.account_id', $account->id);
        })->select('id', 'title')->where('course_id', $request->course_id)->get();

        return response()->json([
            'status' => true,
            'sections' => $section
        ]);
    }

    public function getLessons(Request $request){
        $rules = [
            'section_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $account = Accounts::where('user_id', auth()->id())->first();

        $lesson = SectionLessons::with(['codes'=>function($query) use($account) {
            $query->where('account_id',$account->id);
        }])->whereHas('section.courses.enrolls', function($query) use($account){
            $query->where('enroll.account_id', $account->id);
        })->where('section_id', $request->section_id)
            ->get();



        return response()->json([
            'status' => true,
            'lessons' => $lesson
        ]);
    }
}
