<?php

namespace Tests\Unit\Student;

use App\Accounts;
use App\Answer;
use App\Courses;
use App\CourseSections;
use App\Point;
use App\Question;
use App\Quiz;
use App\QuizSession;
use App\SectionLessons;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Browser\PointReviewCertificateTest;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use WithFaker;
    /** @test */
    public function access_course_list(){
        $dashboard = $this->get('/courses');
        $dashboard->assertStatus(200);
        $dashboard->assertViewHas('courses');
    }

    /** @test */
    public function access_course_detail_success(){
        $course = Courses::first();
        $dashboard = $this->get("/course/$course->id/details");
        $dashboard->assertStatus(200);
        $dashboard->assertViewHas('course');
        $dashboard->assertViewHas('objectives');
        $dashboard->assertViewHas('contributors');
        $dashboard->assertViewHas('sections');
        $dashboard->assertViewHas('points');
    }

    /** @test  */
    public function access_course_detail_fail(){
        //course tidak ada di db
        $dashboard = $this->get("/course/1000/details");
        $dashboard->assertStatus(302);
    }

    /** @test  */
    public function enroll_course_and_access_lesson_success(){
        $email = 'student@conlab.com';
        $password = 'conlab123';

        $lesson = SectionLessons::first();
        $section = CourseSections::find($lesson->section_id);

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $dashboard = $this->get("/course/$section->course_id/section/$section->id/lesson/$lesson->id");
        $dashboard->assertStatus(200);
        $dashboard->assertViewHas('course');
        $dashboard->assertViewHas('sections');
        $dashboard->assertViewHas('lesson');
        $dashboard->assertViewHas('code');

    }

    /** @test  */
    public function enroll_course_and_access_lesson_fail(){
        $email = 'student@conlab.com';
        $password = 'conlab123';


        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        //course/section/lesson tidak ada di db
        $dashboard = $this->get("/course/1000/section/1000/lesson/1000");
        $dashboard->assertStatus(302);

    }

    /** @test  */
    public function store_course_lesson_code_success(){
        $email = 'student@conlab.com';
        $password = 'conlab123';

        $user = User::where('email',$email)->first();
        $account = Accounts::where('user_id',$user->id)->first();

        $lesson = SectionLessons::where('is_coding',1)->first();
        $section = CourseSections::find($lesson->section_id);

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $code = $this->faker->domainWord;
        $respon = $this->post("/course/$section->course_id/section/$section->id/lesson/$lesson->id/store",['code'=>$code]);
        $respon->assertSessionHasNoErrors();

        $this->assertDatabaseHas('codes',[
            'lesson_id'=>$lesson->id,
            'account_id'=>$account->id,
            'raw_code'=>$code
        ]);
    }
    /** @test  */
    public function store_course_lesson_code_fail(){
        $email = 'student@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $code = $this->faker->domainWord;
        $respon = $this->post("/course/1000/section/1000/lesson/1000/store",['code'=>$code]);
        $respon->assertStatus(302);
    }

    /** @test  */
    public function access_course_quiz_success(){
        $email = 'student@conlab.com';
        $password = 'conlab123';

        $quiz = Quiz::first();
        $section = CourseSections::find($quiz->section_id);

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $dashboard = $this->get("/course/$section->course_id/section/$section->id/quiz/$quiz->id");
        $dashboard->assertStatus(200);
        $dashboard->assertViewHas('sections');
        $dashboard->assertViewHas('quiz');
        $dashboard->assertViewHas('session');
    }

    /** @test  */
    public function access_course_quiz_fail(){
        $email = 'student@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $dashboard = $this->get("/course/1000/section/1000/quiz/1000");
        $dashboard->assertStatus(302);
    }

    /** @test  */
    public function start_and_access_course_quiz_success(){
        $email = 'student@conlab.com';
        $password = 'conlab123';

        $quiz = Quiz::first();
        $section = CourseSections::find($quiz->section_id);

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $dashboard = $this->get("/course/$section->course_id/section/$section->id/quiz/$quiz->id/question");
        $dashboard->assertStatus(200);
        $dashboard->assertViewHas('sections');
        $dashboard->assertViewHas('quiz');
        $dashboard->assertViewHas('session');
        $dashboard->assertViewHas('questions');
    }

    /** @test  */
    public function start_and_access_course_quiz_fail(){
        $email = 'student@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $dashboard = $this->get("/course/1000/section/1000/quiz/1000/question");
        $dashboard->assertStatus(302);
    }

    /** @test  */
    public function answer_question_quiz_success(){
        $email = 'student@conlab.com';
        $password = 'conlab123';

        $user = User::where('email',$email)->first();
        $account = Accounts::where('user_id',$user->id)->first();

        $quiz = Quiz::first();
        $section = CourseSections::find($quiz->section_id);

        $question = Question::where('quiz_id',$quiz->id)->first();
        $answer = Answer::where('question_id',$question->id)->first();

        $quiz_session = QuizSession::where(['quiz_id'=>$quiz->id,'account_id'=>$account->id])->first();

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $answer = [
          'session_id'=>$quiz_session->id,
          'question_id'=>$question->id,
          'answer_id'=>$answer->id
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $respon = $this->post("/course/$section->course_id/section/$section->id/quiz/$quiz->id/question/answer",$answer);
        $respon->assertJsonStructure(['success']);
    }

    /** @test  */
    public function answer_question_quiz_fail(){
        $email = 'student@conlab.com';
        $password = 'conlab123';

        $user = User::where('email',$email)->first();
        $account = Accounts::where('user_id',$user->id)->first();

        $quiz = Quiz::first();
        $section = CourseSections::find($quiz->section_id);

        $question = Question::where('quiz_id',$quiz->id)->first();
        $answer = Answer::where('question_id',$question->id)->first();

        $quiz_session = QuizSession::where(['quiz_id'=>$quiz->id,'account_id'=>$account->id])->first();

        $user = [
            'email' => $email,
            'password' => $password
        ];

        //session tidak include (harusnya include karena rulenya required)
        $answer = [
            'question_id'=>$question->id,
            'answer_id'=>$answer->id
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $respon = $this->post("/course/$section->course_id/section/$section->id/quiz/$quiz->id/question/answer",$answer);
        $respon->assertStatus(400);
    }

    /** @test  */
    public function submit_course_quiz_session_success(){
        $email = 'student@conlab.com';
        $password = 'conlab123';

        $user = User::where('email',$email)->first();
        $account = Accounts::where('user_id',$user->id)->first();

        $quiz = Quiz::first();
        $section = CourseSections::find($quiz->section_id);

        $question = Question::where('quiz_id',$quiz->id)->first();
        $answer = Answer::where('question_id',$question->id)->first();

        $quiz_session = QuizSession::where(['quiz_id'=>$quiz->id,'account_id'=>$account->id])->first();

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $respon = $this->post("/course/$section->course_id/section/$section->id/quiz/$quiz->id/submit",['session_id'=>$quiz_session->id]);
        $respon->assertSessionHasNoErrors();

        $this->assertDatabaseHas('quiz_sessions',['id'=>$quiz_session->id,'status'=>'taken']);
        $this->assertDatabaseHas('points',['quiz_id'=>$section->quiz_id,'account_id'=>$account->id]);

        $point = Point::where(['quiz_id'=>$section->quiz_id,'account_id'=>$account->id])->delete();
        $quiz_session_reset = QuizSession::find($quiz_session->id);
        $quiz_session_reset->update([
           'status'=>'progress',
            'score'=>null
        ]);
    }

    /** @test  */
    public function submit_course_quiz_session_fail(){
        $email = 'student@conlab.com';
        $password = 'conlab123';

        $user = User::where('email',$email)->first();
        $account = Accounts::where('user_id',$user->id)->first();

        $quiz = Quiz::first();
        $section = CourseSections::find($quiz->section_id);

        $question = Question::where('quiz_id',$quiz->id)->first();
        $answer = Answer::where('question_id',$question->id)->first();

        $quiz_session = QuizSession::where(['quiz_id'=>$quiz->id,'account_id'=>$account->id])->first();

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $respon = $this->post("/course/$section->course_id/section/$section->id/quiz/$quiz->id/submit",['session_id'=>null]);
        $respon->assertStatus(302);

        $this->assertDatabaseMissing('quiz_sessions',['quiz_id'=>$quiz->id,'account_id'=>$account->id,'status'=>'taken']);

    }
}
