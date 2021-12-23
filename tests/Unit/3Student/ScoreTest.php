<?php

namespace Tests\Unit\Student;

use App\Accounts;
use App\Codes;
use App\Contributors;
use App\Courses;
use App\CourseSections;
use App\Enroll;
use App\Quiz;
use App\QuizSession;
use App\SectionLessons;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ScoreTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function enroll_course_and_access_lesson_success()
    {
        $email = 'student@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $dashboard = $this->get("/score");
        $dashboard->assertStatus(200);
        $dashboard->assertViewHas('courses');
        $dashboard->assertViewHas('codes');
        $dashboard->assertViewHas('quizzes');
        $dashboard->assertViewHas('rankings');

    }

    /** @test */
    public function enroll_course_and_access_lesson_fail()
    {
        $dashboard = $this->get("/score");
        $dashboard->assertStatus(302);

    }
    /** @test */
    public function get_certificate_success()
    {
        $enroll = Enroll::where('is_finished',1)->first();

        $account = Accounts::find($enroll->account_id);

        $user = [
            'email'=>$account->user->email,
            'password'=>'conlab123'
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $dashboard = $this->get("/course/$enroll->course_id/certificate");
        $dashboard->assertStatus(200);
        $dashboard->assertViewHas('name');
        $dashboard->assertViewHas('course');
        $dashboard->assertViewHas('date');

    }

    /** @test */
    public function get_certificate_fail()
    {
        $enroll = Enroll::where('is_finished',0)->first();

        $account = Accounts::find($enroll->account_id);

        $user = [
            'email'=>$account->user->email,
            'password'=>'conlab123'
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $dashboard = $this->get("/course/$enroll->course_id/certificate");
        $dashboard->assertStatus(302);

    }

}
