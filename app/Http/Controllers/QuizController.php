<?php

namespace App\Http\Controllers;

use App\Codes;
use App\Enroll;
use App\Point;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Courses;
use App\CourseSections;
use App\Accounts;
use App\Quiz;
use App\QuizSession;
use App\QuizSessionAnswer;
use App\Question;
use App\QuestionFile;
use App\Answer;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class QuizController extends Controller
{
    public function index($id, $section_id, $quiz_id)
    {
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
                $quiz = Quiz::findOrFail($quiz_id);
            } catch (ModelNotFoundException $ex) {
                toast('Lesson not found!', 'error');
                return redirect()->back();
            }

            $sections = CourseSections::where('course_id', $id);
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

            $session = QuizSession::where([
                ['quiz_id', $quiz_id],
                ['account_id', $account->id]
            ]);

            if ($session->count() === 0) {
                $session = [];
            } else {
                $session = $session->first();
            }

            return view('course.quiz.index')
                ->with('sections', $sections->get())
                ->with('quiz', $quiz)
                ->with('session', $session);
        }
    }

    public function question($id, $section_id, $quiz_id)
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
            $quiz = Quiz::findOrFail($quiz_id);
        } catch (ModelNotFoundException $ex) {
            toast('Quiz not found!', 'error');
            return redirect()->back();
        }

        $sections = CourseSections::where('course_id', $id);
        $account = Accounts::where('user_id', auth()->user()->id)->first();

        $session = QuizSession::where([
            'quiz_id' => $quiz_id,
            'account_id' => $account->id
        ]);

        if ($session->count() === 0) {
            $session = QuizSession::create([
                'quiz_id' => $quiz_id,
                'account_id' => $account->id,
                'status' => 'progress'
            ]);
        }else{
            $session = $session->with('answer')->first();
        }

        if ($session->status == 'taken') {
            toast('Quiz Already Taken', 'warning');
            return redirect()->back();
        }

        $quizPoint = Point::where([
            'account_id'=>$account->id,
            'quiz_id'=>$quiz_id
        ]);

        if($quizPoint->count()===0){
            Point::create([
                'account_id'=>$account->id,
                'quiz_id'=>$quiz_id,
                'point'=>0
            ]);
        }

        $question = Question::where('quiz_id', $quiz_id)->with('file')->with('answer')->paginate(1);

        return view('course.quiz.question')
            ->with('sections', $sections->get())
            ->with('quiz', $quiz)
            ->with('session', $session)
            ->with('questions', $question);

    }

    public function questionAnswer($id, $section_id, $quiz_id,Request $request){
        $rules = [
            'session_id'=>'required',
            'question_id'=>'required',
            'answer_id'=>'required'
        ];


        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $course = Courses::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['error'=>'Course not found!']);
        }

        try {
            $section = CourseSections::findOrFail($section_id);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['error'=>'Section not found!']);
        }

        try {
            $quiz = Quiz::findOrFail($quiz_id);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['error'=>'Course not found!']);
        }

        try {
            $session = QuizSession::findOrFail($request->session_id);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['error'=>'Session not found!']);
        }

        $answer = QuizSessionAnswer::where([
           'session_id'=>$request->session_id,
           'question_id'=>$request->question_id,
        ]);

        if($answer->count() === 0){
            $answer = QuizSessionAnswer::create([
                'session_id'=>$request->session_id,
                'question_id'=>$request->question_id,
                'answer_id'=>$request->answer_id
            ]);
        }

        $answer->update([
            'answer_id'=>$request->answer_id
        ]);

        return response()->json(['success'=>'Question Answered']);
    }

    public function submit($id, $section_id, $quiz_id,Request $request){
        try {
            $course = Courses::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            toast('Course not Found','error');
            return redirect()->back();
        }

        try {
            $section = CourseSections::findOrFail($section_id);
        } catch (ModelNotFoundException $ex) {
            toast('Section not Found','error');
            return redirect()->back();
        }

        try {
            $quiz = Quiz::findOrFail($quiz_id);
        } catch (ModelNotFoundException $ex) {
            toast('Quiz not Found','error');
            return redirect()->back();
        }

        try {
            $session = QuizSession::findOrFail($request->session_id);
        } catch (ModelNotFoundException $ex) {
            toast('Session not Found','error');
            return redirect()->back();
        }

        if (empty($request->session_id)) {
            alert('Code Error', 'Session cannot be empty!', 'error');
            return redirect()->back()->withErrors('Session cant be empty');
        }

        $questions = Question::where('quiz_id',$quiz_id)->get();

        $QuestionScore = 0;
        $score = 0;

        foreach ($questions as $question){
            $answer = Answer::where('question_id',$question->id)->max('point');
            $QuestionScore = $QuestionScore + $answer;

            $sessionAnswer = QuizSessionAnswer::where([
                'session_id'=>$request->session_id,
                'question_id'=>$question->id
            ])->with('answer');


            if($sessionAnswer->count() != 0){
                $sessionAnswer = $sessionAnswer->first();
                $score = $score + $sessionAnswer->answer->point;
            }

        }

        $session->update([
           'status'=>'taken',
            'score'=>($score/$QuestionScore)*100
        ]);

        $account = Accounts::where('user_id',auth()->user()->id)->first();

        Point::where([
            'account_id'=>$account->id,
            'quiz_id'=>$quiz_id
        ])->update([
           'point'=> (($score/$QuestionScore)*15)
         ]);

        $this->checkStatusCourse($id);

        toast('Quiz Submitted','success');
        return redirect(route('quiz',['id'=>$id,'section_id'=>$section_id,'quiz_id'=>$quiz_id]));

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
