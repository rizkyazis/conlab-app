<?php

namespace Tests\Unit\Reviewer;

use App\Codes;
use App\Contributors;
use App\Courses;
use App\CourseSections;
use App\SectionLessons;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ReviewTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function access_dashboard_dashboard_review_success()
    {
        $email = ['admin@conlab.com', 'teacher@conlab.com', 'reviewer@conlab.com'];
        $password = 'conlab123';

        for ($i = 0; $i < 3; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();
            $login->assertRedirect('/dashboard');

            $dashboard = $this->get('/dashboard/review');
            $dashboard->assertStatus(200);
            $dashboard->assertViewHas('courses');

            $this->post('/logout');
        }
    }

    /** @test */
    public function access_dashboard_quiz_fail()
    {
        $email = 'student@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $dashboard = $this->get('/dashboard/review');
        $dashboard->assertStatus(302);
    }

    /** @test */
    public function access_dashboard_review_lesson_success()
    {
        $email = ['admin@conlab.com', 'teacher@conlab.com', 'reviewer@conlab.com'];
        $password = 'conlab123';
        $course = Courses::all();
        $course = $course->last();

        for ($i = 0; $i < 3; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();
            $login->assertRedirect('/dashboard');

            $dashboard = $this->get("/dashboard/review/$course->id/lessons");
            $dashboard->assertStatus(200);
            $dashboard->assertViewHas('lessons');

            $this->post('/logout');
        }
    }

    /** @test */
    public function access_dashboard_quiz_lesson_fail()
    {
        $email = 'student@conlab.com';
        $password = 'conlab123';
        $course = Courses::all();
        $course = $course->last();

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $dashboard = $this->get("/dashboard/review/$course->id/lessons");
        $dashboard->assertStatus(302);
    }

    /** @test */
    public function access_dashboard_review_lesson_participant_success()
    {
        $email = ['admin@conlab.com', 'teacher@conlab.com', 'reviewer@conlab.com'];
        $password = 'conlab123';
        $lesson = SectionLessons::where('is_coding', 1)->first();
        $section = CourseSections::find($lesson->section->id);

        for ($i = 0; $i < 3; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();
            $login->assertRedirect('/dashboard');

            $dashboard = $this->get("/dashboard/review/$section->course_id/lessons/$lesson->id/participants");
            $dashboard->assertStatus(200);
            $dashboard->assertViewHas('accounts');

            $this->post('/logout');
        }
    }

    /** @test */
    public function access_dashboard_review_lesson_participant_fail()
    {
        $email = 'student@conlab.com';
        $password = 'conlab123';
        $lesson = SectionLessons::where('is_coding', 1)->first();
        $section = CourseSections::find($lesson->section->id);

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $dashboard = $this->get("/dashboard/review/$section->course_id/lessons/$lesson->id/participants");
        $dashboard->assertStatus(302);
    }

    /** @test */
    public function access_dashboard_review_lesson_participant_code_success()
    {
        $email = ['admin@conlab.com', 'teacher@conlab.com', 'reviewer@conlab.com'];
        $password = 'conlab123';
        $code = Codes::all();
        $code = $code->first();
        $lesson = SectionLessons::find($code->lesson_id);
        $section = CourseSections::find($lesson->section->id);

        for ($i = 0; $i < 3; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();
            $login->assertRedirect('/dashboard');

            $dashboard = $this->get("/dashboard/review/$section->course_id/lessons/$code->lesson_id/participant/$code->account_id/code");
            $dashboard->assertStatus(200);
            $dashboard->assertViewHas('code');
            $dashboard->assertViewHas('account');
            $dashboard->assertViewHas('course');

            $this->post('/logout');
        }
    }

    /** @test */
    public function access_dashboard_review_lesson_participant_code_fail()
    {
        $email = 'student@conlab.com';
        $password = 'conlab123';
        $code = Codes::all();
        $code = $code->first();
        $lesson = SectionLessons::find($code->lesson_id);
        $section = CourseSections::find($lesson->section->id);

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $dashboard = $this->get("/dashboard/review/$section->course_id/lessons/$code->lesson_id/participant/$code->account_id/code");
        $dashboard->assertStatus(302);
    }

    /** @test */
    public function update_dashboard_review_lesson_participant_code_success()
    {
        $email = 'reviewer@conlab.com';
        $password = 'conlab123';
        $code = Codes::all();
        $code = $code->first();
        $lesson = SectionLessons::find($code->lesson_id);
        $section = CourseSections::find($lesson->section->id);
        $reviewer = User::where(['email' => $email, 'role' => 'reviewer'])->first();
        $contributor = Contributors::where(['accounts_id'=>$reviewer->account->id,'course_id'=>$section->course_id])->first();

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $feedback = $this->faker->sentence;
        $score = rand(0, 100);

        $review = [
            'code'=>$code->raw_code,
            'score' => $score,
            'feedback' => $feedback,
        ];

        $respon = $this->post("/dashboard/review/$section->course_id/lessons/$code->lesson_id/participant/$code->account_id/code/update", $review);
        $respon->assertSessionHasNoErrors();

        $this->assertDatabaseHas('codes', [
            'lesson_id' => $lesson->id,
            'account_id' => $code->account_id,
            'contributor_id' => $contributor->id,
            'feedback' => $feedback,
            'score' => $score,
            'is_reviewed' => 1
        ]);

    }

    /** @test */
    public function update_dashboard_review_lesson_participant_code_fail()
    {
        $email = 'reviewer@conlab.com';
        $password = 'conlab123';
        $code = Codes::all();
        $code = $code->first();
        $lesson = SectionLessons::find($code->lesson_id);
        $section = CourseSections::find($lesson->section->id);
        $reviewer = User::where(['email' => $email, 'role' => 'reviewer'])->first();
        $contributor = Contributors::where(['accounts_id'=>$reviewer->account->id,'course_id'=>$section->course_id])->first();

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $feedback = $this->faker->sentence;
        $score = 120;

        $review = [
            'code'=>$code->raw_code,
            'score' => $score,
            'feedback' => $feedback,
        ];

        $respon = $this->post("/dashboard/review/$section->course_id/lessons/$code->lesson_id/participant/$code->account_id/code/update", $review);
        $respon->assertSessionHasErrors();

        $this->assertDatabaseMissing('codes', [
            'lesson_id' => $lesson->id,
            'account_id' => $code->account_id,
            'contributor_id' => $contributor->id,
            'feedback' => $feedback,
            'score' => $score,
            'is_reviewed' => 1
        ]);

    }
}
