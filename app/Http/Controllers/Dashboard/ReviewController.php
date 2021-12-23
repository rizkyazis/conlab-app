<?php

namespace App\Http\Controllers\Dashboard;

use App\Accounts;
use App\Codes;
use App\Contributors;
use App\Courses;
use App\CourseSections;
use App\Enroll;
use App\Http\Controllers\Controller;
use App\Point;
use App\QuizSession;
use App\SectionLessons;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index()
    {
        $account = Accounts::where('user_id', auth()->user()->id);
        $contributors = Contributors::where('accounts_id', $account->first()->id);

        $course_ids = array();
        foreach ($contributors->get() as $contributor) {
            $data_course = ['id', '=', $contributor->course_id];
            array_push($data_course, 'or');
            array_push($course_ids, $data_course);
        }

        $this->array_flatten($course_ids);

        $courses = Courses::where($course_ids);
        return view('dashboard.review.index')
            ->with('courses', $courses->get());
    }

    public function course_search(Request $request)
    {
        $account = Accounts::where('user_id', auth()->user()->id);
        $contributors = Contributors::where('accounts_id', $account->first()->id);

        $course_ids = array();
        foreach ($contributors->get() as $contributor) {
            $data_course = ['id', '=', $contributor->course_id];
            array_push($data_course, 'or');
            array_push($course_ids, $data_course);
        }

        $this->array_flatten($course_ids);

        $courses = Courses::where($course_ids)->where('title', 'like', '%' . $request->query('name') . '%');

        if ($courses->count() === 0) {
            toast('Course not found', 'error');
            return redirect()->back();
        }

        return view('dashboard.review.index')
            ->with('courses', $courses->get());
    }

    public function lessons($id)
    {
        try {
            $course = Courses::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            toast("Course doesn't exist", 'error');
            return redirect()->back()->with('error', "Course doesn't exist");
        }
        $sections = CourseSections::where('course_id', $id);

        $section_ids = array();

        foreach ($sections->get() as $section) {
            $data_section = ['section_id', '=', $section->id];
            array_push($data_section, 'or');
            array_push($section_ids, $data_section);
        }

        $this->array_flatten($section_ids);

        $lessons = SectionLessons::where($section_ids)->where('is_coding', true);
        return view('dashboard.review.lessons')
            ->with('lessons', $lessons->get());
    }

    // TODO: function lessons and participants will be bug, because there is no course owner check
    public function participants($id, $lesson_id)
    {
        try {
            $course = Courses::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            toast("Course doesn't exist", 'error');
            return redirect()->back()->with('error', "Course doesn't exist");
        }
        try {
            $lesson = SectionLessons::findOrFail($lesson_id);
        } catch (ModelNotFoundException $ex) {
            toast("Course doesn't exist", 'error');
            return redirect()->back()->with('error', "Course doesn't exist");
        }
        $codes = Codes::where('lesson_id', $lesson_id);

        $account_ids = array();

        foreach ($codes->get() as $code) {
            $data_account = ['id', '=', $code->account_id];
            array_push($data_account, 'or');
            array_push($account_ids, $data_account);
        }

        $this->array_flatten($account_ids);

        if (empty($account_ids)) {
            return view('dashboard.review.participants');
        }

        $accounts = Accounts::where($account_ids);

        return view('dashboard.review.participants')
            ->with('accounts', $accounts->get());
    }

    public function participants_search($id, $lesson_id, Request $request)
    {
        try {
            $course = Courses::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            toast("Course doesn't exist", 'error');
            return redirect()->back()->with('error', "Course doesn't exist");
        }
        try {
            $lesson = SectionLessons::findOrFail($lesson_id);
        } catch (ModelNotFoundException $ex) {
            toast("Course doesn't exist", 'error');
            return redirect()->back()->with('error', "Course doesn't exist");
        }
        $accounts = Accounts::where('fullname', 'like', '%' . $request->query('name') . '%');

        if ($accounts->count() === 0) {
            toast('Participant not found', 'error');
            return redirect()->back();
        }

        $codes = Codes::where([
            ['lesson_id', $lesson_id],
            ['account_id', $accounts->first()->id]
        ]);

        if ($codes->count() === 0) {
            toast('Participant did not have codes', 'error');
            return redirect()->back();
        }

        return view('dashboard.review.participants')
            ->with('accounts', $accounts->get());
    }

    public function code($id, $lesson_id, $account_id)
    {
        try {
            $course = Courses::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            toast("Course doesn't exist", 'error');
            return redirect()->back()->with('error', "Course doesn't exist");
        }
        try {
            $lesson = SectionLessons::findOrFail($lesson_id);
        } catch (ModelNotFoundException $ex) {
            toast("Course doesn't exist", 'error');
            return redirect()->back()->with('error', "Course doesn't exist");
        }

        try {
            $account = Accounts::findOrFail($account_id);
        } catch (ModelNotFoundException $ex) {
            toast("Course doesn't exist", 'error');
            return redirect()->back()->with('error', "Course doesn't exist");
        }

        $course = Courses::find($id);

        $codes = Codes::where([
            ['lesson_id', $lesson_id],
            ['account_id', $account_id]
        ]);

        $account = Accounts::where('id', $codes->first()->account_id);

        return view('dashboard.review.code')
            ->with('code', $codes->first())
            ->with('account', $account->first())
            ->with('course', $course);
    }

    public function update($id, $lesson_id, $account_id, Request $request)
    {
        try {
            $course = Courses::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            toast("Course doesn't exist", 'error');
            return redirect()->back()->with('error', "Course doesn't exist");
        }

        try {
            $lesson = SectionLessons::findOrFail($lesson_id);
        } catch (ModelNotFoundException $ex) {
            toast("Course doesn't exist", 'error');
            return redirect()->back()->with('error', "Course doesn't exist");
        }


        try {
            $account = Accounts::findOrFail($account_id);
        } catch (ModelNotFoundException $ex) {
            toast("Course doesn't exist", 'error');
            return redirect()->back()->with('error', "Course doesn't exist");
        }

        if (empty($request->code)) {
            alert('Code Error', 'Code cannot be empty!', 'error');
            return redirect()->back()->withInput($request->all());
        }
        $rules = [
          'score'=>'required|lte:100',
          'feedback'=>'required|max:250'
        ];

        $this->validate($request, $rules);

        $account = Accounts::where('user_id', auth()->user()->id);
        $contributors = Contributors::where('accounts_id', $account->first()->id);

        $codes = Codes::where([
            ['lesson_id', $lesson_id],
            ['account_id', $account_id]
        ])->update([
            'contributor_id' => $contributors->first()->id,
            'feedback' => $request->feedback,
            'raw_code' => $request->code,
            'score' => $request->score,
            'is_reviewed' => true
        ]);

        $point = ($request->score / 100) * 15;

        Point::where([
            'account_id' => $account_id,
            'lesson_id' => $lesson_id
        ])->update([
            'point' => intval($point)
        ]);

        $this->checkStatusCourse($id);

        toast('Code has ben reviewed!', 'success');
        return redirect()->back();
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
