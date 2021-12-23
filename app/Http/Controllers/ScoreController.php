<?php

namespace App\Http\Controllers;

use App\Accounts;
use App\Codes;
use App\Courses;
use App\CourseSections;
use App\Point;
use App\QuizSession;
use App\SectionLessons;
use App\User;
use Illuminate\Http\Request;
use App\Enroll;

class ScoreController extends Controller
{
    public function index(){

        $account = Accounts::where('user_id', auth()->user()->id)->first();
        $courses = Enroll::where('account_id',$account->id)->with('course')->get();
        $sections = CourseSections::all();
        $codes = Codes::where('account_id',$account->id)->get();
        $quizzes = QuizSession::where('account_id',$account->id)->get();

        $users = User::where('role','student')->get();

        $points = Point::all();

        $accountPoint = array();

        foreach ($users as $user){
            $account = Accounts::where('user_id',$user->id)->first();
            $totalPoint = 0;
            foreach ($points as $point){
                if ($account->id === $point->account_id){
                    $totalPoint = $totalPoint + $point->point;
                }
            }
            if ($account->fullname != ''){
                array_push($accountPoint,[
                    'id'=>$account->id,
                    'user_id'=>$account->user_id,
                    'name'=>$account->fullname,
                    'point'=>$totalPoint
                ]);
            }
        }

        $rankings = (object) collect($accountPoint)->sortByDesc('point');

        $enrolls = Enroll::where('account_id',$account->id)->get();

        foreach ($enrolls as $enroll){
            $this->checkStatusCourse($enroll->course_id);
        }

        return view('score.index')
            ->with('courses',$courses)
            ->with('sections',$sections)
            ->with('codes',$codes)
            ->with('quizzes',$quizzes)
            ->with('rankings', $rankings);
    }

    public function checkStatusCourse($id){
        $course = Courses::find($id);

        $account = Accounts::where('user_id',auth()->user()->id)->first();
        $status = false;

        $enroll = Enroll::where([
            'course_id'=>$id,
            'account_id'=>$account->id
        ]);


        if ($enroll->count() != 0){
            foreach ($course->sections as $section){
                foreach ($section->lessons as $lesson){
                    if($lesson->is_coding === 1){
                        $takenLesson = Codes::where([
                            'lesson_id'=>$lesson->id,
                            'account_id'=>$account->id
                        ]);
                        if ($takenLesson->count() != 0){
                            if($takenLesson->first()->is_reviewed === 1){
                                $status = true;
                            }else{
                                $status = false;
                                break;
                            }
                        }else{
                            $status = false;
                            break;
                        }
                    }
                }
                foreach ($section->quiz as $quiz){
                    $takenQuiz = QuizSession::where([
                        'quiz_id'=>$quiz->id,
                        'account_id'=>$account->id
                    ]);
                    if ($takenQuiz->count() != 0){
                        if($takenQuiz->first()->status === 'taken'){
                            $status = true;
                        }else{
                            $status = false;
                            break;
                        }
                    }else{
                        $status = false;
                        break;
                    }
                }
            }
            if($status === true){
                $enroll->first()->update([
                    'is_finished'=>true
                ]);
            }
        }
    }
}
