<?php

namespace App\Http\Controllers\Dashboard;

use App\Accounts;
use App\Contributors;
use App\CourseObjective;
use App\Courses;
use App\CourseSections;
use App\CourseTag;
use App\Http\Controllers\Controller;
use App\LessonVideo;
use App\SectionLessons;
use App\Tag;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index(){
        $courses = Courses::all();

        return view('dashboard.course.index')
            ->with('courses',$courses);
    }

    public function course_search(Request $request){
        $courses = Courses::where('title', 'like', '%' . $request->query('name') . '%');

        if ($courses->count() === 0) {
            toast('Course not found', 'error');
            return redirect()->back();
        }

        return view('dashboard.course.index')
            ->with('courses',$courses->get());
    }

    public function index_create(){
        $tag = Tag::all();

        return view('dashboard.course.create')
            ->with('tags', $tag);
    }


    public function detailed($id, Request $request){
        try{
            $course = Courses::findOrFail($id);
        }catch(ModelNotFoundException $ex){
            toast('You can\'t do that!', 'error');
            return redirect()->back()->withInput($request->all());
        }

        $courseObj = CourseObjective::where('course_id', $id);
        $contributor = Contributors::where('course_id', $id);
        $users = User::where('role', 'teacher')->orWhere('role', 'reviewer');

        return view('dashboard.course.detailed')
            ->with('objectives', $courseObj->get())
            ->with('contributors', $contributor->get())
            ->with('users', $users->get());
    }


    public function info($id){
        try{
            $course = Courses::findOrFail($id);
        }catch(ModelNotFoundException $ex){
            toast('You can\'t do that!', 'error');
            return redirect()->back();
        }

        $section_ids = array();

        $sections = CourseSections::all()->where('course_id', $id);
        foreach($sections as $section){
            $data_section = ['section_id', '=', $section->id];
            array_push($data_section, 'or');
            array_push($section_ids, $data_section);
        }
        $this->array_flatten($section_ids);
        if($section_ids == []){
            $lesson = SectionLessons::where('id',0);
        }else{
            $lesson = SectionLessons::where($section_ids);
        }
        return view('dashboard.course.info')
            ->with('sections', $sections)
            ->with('lessons', $lesson->get());
    }


    public function general_store(Request $request){
        $rules = [
            'title' => 'required|max:150',
            'description' => 'required|max:240',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'img' => 'required|max:5120|mimes:jpg,png,jpeg',
            'category' => 'required',
            'about' => 'required'
        ];
        $this->validate($request, $rules);

        $img = $request->file('img');
        $storage = Storage::put('public/course/cover', $img);

        $course = Courses::create([
            'title' => $request->title,
            'description' => $request->description,
            'difficulty' => $request->difficulty,
            'img' => $storage,
            'about' => $request->about,
        ]);

        $course_tag = CourseTag::create([
            'tag_id' => $request->category,
            'course_id' => $course->id
        ]);

        return redirect()->route('dashboard.course.detailed.new', $course->id);
    }

    public function course($id){
        $tag = Tag::all();
        try{
            $course = Courses::findOrFail($id);
        }catch(ModelNotFoundException $ex){
            toast("Course doesn't exist", 'error');
            return redirect()->back();
        }
        $course = Courses::find($id);

        return view('dashboard.course.course')
            ->with('course',$course)
            ->with('tags', $tag);
    }

    public function course_update($id, Request $request){
        try{
            $course = Courses::findOrFail($id);
        }catch(ModelNotFoundException $ex){
            toast("Course doesn't exist", 'error');
            return redirect()->back();
        }
        $rules = [
            'title' => 'required|max:150',
            'description' => 'required|max:240',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'img' => 'max:5120|mimes:jpg,png,jpeg',
            'category' => 'required',
            'about' => 'required'
        ];
        $this->validate($request, $rules);

        if($request->img != null){
            $img = $request->file('img');
            $storage = Storage::put('public/course/cover', $img);
        }else{
            $img = $course->img;
        }

        $course->update([
            'title' => $request->title,
            'description' => $request->description,
            'difficulty' => $request->difficulty,
            'img' => $img,
            'about' => $request->about,
        ]);

        $course_tag = CourseTag::find($course->course_tags->id);

        $course_tag->update([
            'tag_id' => $request->category,
            'course_id' => $course->id
        ]);

        return redirect()->route('dashboard.course.detailed.new', $course->id);
    }

    public function objective_store($id, Request $request){
        try{
            $course = Courses::findOrFail($id);
        }catch(ModelNotFoundException $ex){
            toast("Course doesn't exist", 'error');
            return redirect()->back()->with('error',"Course doesn't exist");
        }

        $rules = [
            'objective' => 'required|max:150'
        ];

        $this->validate($request, $rules);

        $objective = CourseObjective::create([
            'course_id' => $id,
            'title' => $request->objective
        ]);
        toast('Objective Added', 'success');
        return redirect()->back();
    }


    public function objective_destroy($id, $obj_id){
        if(empty($id) || empty($obj_id)){
            toast('Something went wrong!', 'error');
            return redirect()->back();
        }
        try{
            $objective = CourseObjective::findOrFail($obj_id);
        }catch(ModelNotFoundException $ex){
            toast('Objective not found!', 'error');
            return redirect()->back();
        }

        CourseObjective::where([
            ['id', $obj_id],
            ['course_id', $id]
        ])->delete();
        toast('Objective deleted', 'success');
        return redirect()->back();
    }


    public function contributor_store($id, Request $request){
        $rules = [
            'contributor' => 'required',
            'as' => 'required|in:teacher,reviewer'
        ];

        $this->validate($request, $rules);

        $account = Accounts::find($request->contributor);
        if($account->user->role == 'reviewer' && $request->as == 'teacher'){
            toast('Reviewer cannot be assign as a teacher', 'error');
            return redirect()->back();
        }

        $contributor = Contributors::where([
            ['accounts_id', $request->contributor],
            ['course_id', $id]
        ]);

        if($contributor->count() > 0){
            toast('Contributor already exists', 'error');
            return redirect()->back();
        }

        $contributor = Contributors::create([
            'course_id' => $id,
            'accounts_id' => $request->contributor,
            'as' => $request->as
        ]);
        toast('Contributor added', 'success');
        return redirect()->back();
    }


    public function contributor_destroy($id, $cont_id){
        if(empty($id) || empty($cont_id)){
            toast('Something went wrong!', 'error');
            return redirect()->back();
        }
        try{
            $contributor = Contributors::findOrFail($cont_id);
        }catch(ModelNotFoundException $ex){
            toast('Objective not found!', 'error');
            return redirect()->back();
        }

        Contributors::where([
            ['id', $cont_id],
            ['course_id', $id]
        ])->delete();
        toast('Contributor deleted', 'success');
        return redirect()->back();
    }


    public function section_store($id, Request $request){
        $rules = [
            'section' => 'required|max:150'
        ];

        $this->validate($request, $rules);

        $section = CourseSections::create([
            'course_id' => $id,
            'title' => $request->section
        ]);

        toast('Section added', 'success');
        return redirect()->back();
    }


    public function section_destroy($id, $section_id){
        if(empty($id) || empty($section_id)){
            toast('Something went wrong!', 'error');
            return redirect()->back();
        }

        try{
            $section = CourseSections::findOrFail($section_id);
        }catch (ModelNotFoundException $ex){
            toast('Section not found!', 'error');
            return redirect()->back();
        }


        CourseSections::where([
            ['id', $section_id],
            ['course_id', $id]
        ])->delete();

        toast(  'Section deleted', 'success');
        return redirect()->back();
    }


    public function lesson_store($id, Request $request){
        $rules = [
            'title' => 'required|max:150',
            'section_id' => 'required',
            'is_coding' => 'required|in:yes,no',
            'is_video' => 'required|in:yes,no',
            'video-int' => 'mimes:mp4,mkv,flv,web|max:153600',
            'description' => 'required'
        ];

        if(empty($request->description)){
            alert('Description Error', 'Lesson Description cannot be empty!', 'error');
            return redirect()->back()->withInput($request->all());
        }

        $this->validate($request, $rules);


        $is_coding = $request->is_coding === 'yes';
        $is_video = $request->is_video === 'yes';

        $lesson = SectionLessons::create([
            'section_id' => $request->section_id,
            'title' => $request->title,
            'description' => $request->description,
            'is_coding' => $is_coding,
            'is_video' => $is_video
        ]);

        if($is_video){
            $video = LessonVideo::create([
                'lesson_id' => $lesson->id,
                'title' => $request->title,
                'url' => $this->video_store($lesson->id, $request)
            ]);
        }

        toast('Lesson added', 'success');
        return redirect()->back();
    }

    public function lesson_destroy($id, $lesson_id){
        if(empty($id) || empty($lesson_id)){
            toast('Something went wrong!', 'error');
            return redirect()->back();
        }


        try{
            $lesson = SectionLessons::findOrFail($lesson_id);
        }catch (ModelNotFoundException $ex){
            toast('Lesson not found!', 'error');
            return redirect()->back()->withErrors('lesson not found');
        }

        $lesson->delete();

        toast(  'Lesson deleted', 'success');
        return redirect()->back();
    }

    private function video_store($lesson_id, Request $request){

        $storage = Storage::put('public/course/video', $request->file('video-int'));

        return $request->hasFile('video-int') ? $storage : $request->get('video-ext');
    }

    private function array_flatten($array)
    {
        if (!is_array($array)) {
            return false;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->array_flatten($value));
            } else {
                $result = array_merge($result, array($key => $value));
            }
        }
        return $result;
    }
}
