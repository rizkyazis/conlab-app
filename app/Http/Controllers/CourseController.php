<?php

namespace App\Http\Controllers;

use App\Accounts;
use App\Codes;
use App\Contributors;
use App\CourseObjective;
use App\Courses;
use App\CourseSections;
use App\CourseTag;
use App\Enroll;
use App\Point;
use App\QuizSession;
use App\SectionLessons;
use App\Quiz;
use App\Tag;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class CourseController extends Controller
{
    public function get_started(Request $request)
    {
        $user = User::where('email', $request->email)->get();
        if ($user->count() != 0) {
            toast('This Email is Registered, please login to continue', 'info');
            return redirect()->route('login')->with('email', $request->email);
        }
        toast('Fill all the field to continue', 'info');
        return redirect()->route('register')->with('email', $request->email);
    }

    public function category()
    {
        $categories = Tag::all();
        return view('category')
            ->with('categories', $categories);
    }

    public function category_course($category)
    {
        $tag = Tag::where('name', $category)->first();
        $course = DB::table('courses')
            ->join('course_tag', 'courses.id', '=', 'course_tag.course_id')
            ->join('tag', 'course_tag.tag_id', '=', 'tag.id')
            ->where('course_tag.tag_id', $tag->id)->get();
        return view('course.index')
            ->with('courses', $course);
    }

    public function index(Request $request)
    {
        $course = Courses::with('tags');

        $course->when($request->lang, function ($query) use($request){
            $query->whereHas('tags', function ($query) use($request){
                $query->where('name', $request->lang);
            });
        });

        $course->when($request->skill, function($query) use($request){
            $query->where('difficulty', $request->skill);
        });

        $course->when($request->search, function($query) use($request){
            $query->where('title', 'like' ,'%' . $request->search . '%');
        });


        return view('course.index')
            ->with('courses', $course->get());
    }

    public function detail($id)
    {
        try {
            $course = Courses::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            toast('Course not found!', 'error');
            return redirect()->back();
        }

        if (Auth::check()) {
            $account = Accounts::where('user_id', auth()->user()->id)->first();
            $points = Point::where('account_id', $account->id)->with('lesson','quiz')->get();
            $enroll = Enroll::where([
                'course_id'=>$id,
                'account_id'=>$account->id
            ])->get();

            if(count($enroll)!=0){
                $enroll = 'yes';
            }else{
                $enroll = 'no';
            }
        } else {
            $points = [];
            $enroll = 'no';
        }

        $course_objective = CourseObjective::where('course_id', $id);
        $contributors = Contributors::where('course_id', $id);
        $sections = CourseSections::where('course_id', $id);

        return view('course.detail')
            ->with('course', $course)
            ->with('objectives', $course_objective->get())
            ->with('contributors', $contributors->get())
            ->with('sections', $sections->get())
            ->with('points', $points)
            ->with('enroll',$enroll);

    }

    public function enroll($id)
    {
        $course = Courses::find($id);
        $section = CourseSections::where('course_id', $course->id)->first();
        $lesson = SectionLessons::where('section_id', $section->id)->first();
        return redirect()->route('lesson', ['id' => $id, 'section_id' => $section->id, 'lesson_id' => $lesson->id]);
    }

    public function lesson($id, $section_id, $lesson_id)
    {
        try {
            $course = Courses::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            toast('Course not found!', 'error');
            return redirect()->back();
        }


        try {
            $section = CourseSections::findOrFail($section_id);
        } catch (ModelNotFoundException $ex) {
            toast('Lesson not found!', 'error');
            return redirect()->back();
        }

        try {
            $lesson = SectionLessons::findOrFail($lesson_id);
        } catch (ModelNotFoundException $ex) {
            toast('Lesson not found!', 'error');
            return redirect()->back();
        }

        $account = Accounts::where('user_id', auth()->user()->id)->first();
        $enroll = Enroll::where([
            ['account_id', $account->id],
            ['course_id', $id]
        ]);

        if ($enroll->count() === 0) {
            Enroll::create([
                'account_id' => $account->id,
                'course_id' => $id
            ]);
            toast('Enroll success!', 'success');
        }

        if ($lesson->is_coding === 0) {
            $point = 5;
        } else {
            $point = 0;
        }

        $lessonPoint = Point::where([
            'account_id' => $account->id,
            'lesson_id' => $lesson->id
        ]);

        if ($lessonPoint->count() === 0) {
            Point::create([
                'account_id' => $account->id,
                'lesson_id' => $lesson->id,
                'point' => $point
            ]);
        }

        $sections = CourseSections::where('course_id', $id);

        $code = Codes::where([
            ['lesson_id', $lesson_id],
            ['account_id', $account->id]
        ]);

        if ($code->count() > 0) {
            $code = $code->first();
        } else {
            $code = [];
        }

        if ($enroll->count() != 0) {
            $points = Point::where('account_id', $account->id)->with('lesson','quiz')->get();
        }else{
            $points = [];
        }

        return view('course.lessons')
            ->with('course', $course)
            ->with('sections', $sections->get())
            ->with('lesson', $lesson)
            ->with('code', $code)
            ->with('points',$points);
    }

    public function code_store($id, $section_id, $lesson_id, Request $request)
    {
        try {
            $course = Courses::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            toast('Course not found!', 'error');
            return redirect()->back();
        }

        try {
            $section = CourseSections::findOrFail($section_id);
        } catch (ModelNotFoundException $ex) {
            toast('Lesson not found!', 'error');
            return redirect()->back();
        }

        try {
            $lesson = SectionLessons::findOrFail($lesson_id);
        } catch (ModelNotFoundException $ex) {
            toast('Lesson not found!', 'error');
            return redirect()->back();
        }

        if (empty($request->code)) {
            alert('Code Error', 'Code cannot be empty!', 'error');
            return redirect()->back()->withInput($request->all());
        }

        $account = Accounts::where('user_id', auth()->user()->id)->first();

        $code = Codes::where([
            ['lesson_id', $lesson_id],
            ['account_id', $account->id]
        ]);

        if ($code->count() > 0) {
            $code->update([
                'lesson_id' => $lesson_id,
                'account_id' => $account->id,
                'raw_code' => $request->code
            ]);
        } else {
            Codes::create([
                'lesson_id' => $lesson_id,
                'account_id' => $account->id,
                'raw_code' => $request->code
            ]);
        }

        alert('Your code', 'Your code is submitted', 'success');
        return redirect()->back();
    }

    public function certificate($id)
    {
        try {
            $course = Courses::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            toast('Course not found!', 'error');
            return redirect()->back();
        }

        $this->checkStatusCourse($id);

        $account = Accounts::where('user_id', auth()->id())->first();

        $enroll = Enroll::where([
            'course_id' => $course->id,
            'account_id' => $account->id
        ])->first();


        if ($enroll->is_finished === 0) {
            toast('Course assignment is not finished yet', 'error');
            return redirect()->back();
        }

        $date = date("d F Y", strtotime($course->updated_at));


        return view('certificate')
            ->with('name', $account->fullname)
            ->with('course', $course->title)
            ->with('date', $date);

    }

    public function checkStatusCourse($id)
    {
        $course = Courses::find($id);


        $account = Accounts::where('user_id', auth()->user()->id)->first();
        $status = false;

        $enroll = Enroll::where([
            'course_id' => $id,
            'account_id' => $account->id
        ]);

        if ($enroll->count() != 0) {
            foreach ($course->sections as $section) {
                foreach ($section->lessons as $lesson) {
                    if ($lesson->is_coding === 1) {
                        $takenLesson = Codes::where([
                            'lesson_id' => $lesson->id,
                            'account_id' => $account->id
                        ]);
                        if ($takenLesson->count() != 0) {
                            if ($takenLesson->first()->is_reviewed === 1) {
                                $status = true;
                            } else {
                                $status = false;
                                break;
                            }
                        } else {
                            $status = false;
                            break;
                        }
                    }
                }
                foreach ($section->quiz as $quiz) {
                    $takenQuiz = QuizSession::where([
                        'quiz_id' => $quiz->id,
                        'account_id' => $account->id
                    ]);
                    if ($takenQuiz->count() != 0) {
                        if ($takenQuiz->first()->status === 'taken') {
                            $status = true;
                        } else {
                            $status = false;
                            break;
                        }
                    } else {
                        $status = false;
                        break;
                    }
                }
            }
            if ($status === true) {
                $enroll->first()->update([
                    'is_finished' => true
                ]);
            }
        }
    }
}
