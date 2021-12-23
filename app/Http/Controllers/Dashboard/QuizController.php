<?php

namespace App\Http\Controllers\dashboard;

use App\CourseSections;
use App\Http\Controllers\Controller;
use App\Courses;
use App\Quiz;
use App\Question;
use App\Answer;
use App\QuestionFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function index()
    {
        $courses = Courses::all();

        return view('dashboard.quiz.index')
            ->with('courses', $courses);
    }

    public function course_search(Request $request){
        $courses = Courses::where('title', 'like', '%' . $request->query('name') . '%');

        if ($courses->count() === 0) {
            toast('Course not found', 'error');
            return redirect()->back();
        }

        return view('dashboard.quiz.index')
            ->with('courses',$courses->get());
    }

    public function section($id)
    {
        $sections = CourseSections::where('course_id', $id)->get();

        return view('dashboard.quiz.section')
            ->with('sections', $sections);
    }

    public function detail($section_id)
    {
        $quiz = Quiz::where('section_id', $section_id)->get();

        if (count($quiz) == 0) {
            $quiz = Quiz::create([
                'section_id' => $section_id
            ]);
        } else {
            $quiz = $quiz->first();
        }

        return view('dashboard.quiz.detail')
            ->with('quiz', $quiz);
    }

    public function store($section_id, Request $request)
    {
        $rules = [
            'title' => 'required|max:150',
            'description' => 'required|max:240'
        ];

        $this->validate($request, $rules);

        $quiz = Quiz::where('section_id', $section_id)->first();

        if($quiz->title == $request->title && $quiz->description == $request->description){
            return redirect(route('dashboard.quiz.question', $section_id));
        }

        $quiz->update([
            'title' => $request->title,
            'description' => $request->description
        ]);

        toast('Quiz updated!', 'success');
        return redirect(route('dashboard.quiz.question', $section_id));
    }

    public function question($section_id)
    {
        $quiz = Quiz::where('section_id', $section_id)->first();

        $questions = Question::where('quiz_id', $quiz->id)->with('answer')->get();


        return view('dashboard.quiz.question')
            ->with('quiz', $quiz)
            ->with('questions', $questions);
    }

    public function questionStore($section_id, Request $request)
    {
        $rules = [
            'question' => 'required|max:150',
            'answer' => 'required|max:150',
            'point' => 'required'
        ];
        $type = '';
        $file = false;

        if ($request->file != null) {
            $rules['file'] = 'required|max:5120|mimes:jpg,png,jpeg';
            $type = 'image';
            $validator = Validator::make($request->all(),$rules);
            if ($validator->fails()) {
                $rules['file'] = 'required|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:15360';
                $type = 'video';
            }
        }


        $this->validate($request, $rules);

        $quiz = Quiz::where('section_id', $section_id)->first();

        if ($type != '') {
            $file = true;
        }

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'question' => $request->question,
            'file_exist' => $file
        ]);

        if ($type === 'video' || $type === 'image') {
            $uploadFile = $request->file('file');
            $storage = Storage::put('public/course/quiz/question', $uploadFile);
            $questionFile = QuestionFile::create([
                'question_id' => $question->id,
                'type' => $type,
                'url' => $storage
            ]);
        }

        for ($i = 0; $i < count($request->answer); $i++) {
            $answer = Answer::create([
                'question_id' => $question->id,
                'answer' => $request->answer[$i],
                'point' => $request->point[$i]
            ]);
        }

        toast('Question Added', 'success');
        return redirect()->back();
    }

    public function preview_question(Request $request){
        try {
            $question = Question::findOrFail($request->id);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['error'=>"Question doesn't exists"]);
        }
            $question = Question::where('id',$request->id)->with('file')->with('answer')->first();
        return response()->json($question);
    }

    public function questionDelete($question_id){
        if(empty($question_id)){
            toast('Something went wrong!', 'error');
            return redirect()->back();
        }
        try{
            $question = Question::findOrFail($question_id);
        }catch(ModelNotFoundException $ex){
            toast('Objective not found!', 'error');
            return redirect()->back();
        }

        if($question->file_exist == 1) {
            $file = QuestionFile::where('question_id', $question_id)->first();
            Storage::delete($file->url);
        }

        $question->delete();
        toast('Question deleted', 'success');
        return redirect()->back();
    }
}
