<?php

namespace Tests\Unit\Teacher;

use App\Contributors;
use App\CourseObjective;
use App\Courses;
use App\CourseSections;
use App\Question;
use App\Quiz;
use App\SectionLessons;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use function Symfony\Component\Translation\t;

class CourseManageTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function access_dashboard_course_success()
    {
        $email = ['admin@conlab.com', 'teacher@conlab.com'];
        $password = 'conlab123';

        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();
            $login->assertRedirect('/dashboard');

            $dashboard = $this->get('/dashboard/course');
            $dashboard->assertStatus(200);
            $dashboard->assertViewHas('courses');

            $this->post('/logout');
        }
    }

    /** @test */
    public function access_dashboard_course_fail()
    {
        $email = ['student@conlab.com', 'reviewer@conlab.com'];
        $password = 'conlab123';

        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();

            $dashboard = $this->get('/dashboard/course');
            $dashboard->assertStatus(302);
        }
    }

    /** @test */
    public function access_dashboard_course_create_success()
    {
        $email = ['admin@conlab.com', 'teacher@conlab.com'];
        $password = 'conlab123';

        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();
            $login->assertRedirect('/dashboard');

            $dashboard = $this->get('/dashboard/course/new');
            $dashboard->assertStatus(200);
            $dashboard->assertViewHas('tags');

            $this->post('/logout');
        }
    }

    /** @test */
    public function access_dashboard_course_create_fail()
    {
        $email = ['student@conlab.com', 'reviewer@conlab.com'];
        $password = 'conlab123';

        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();

            $dashboard = $this->get('/dashboard/course/new');
            $dashboard->assertStatus(302);
        }
    }

    /** @test */
    public function create_course_success()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        Storage::fake('course');
        $title = $this->faker->streetName;

        $file = UploadedFile::fake()->image('logo.jpg');

        $title = $this->faker->name;
        $description = $this->faker->name;
        $about = $this->faker->text;

        $course = [
            'title' => $title,
            'description' => $description,
            'difficulty' => 'beginner',
            'about' => $about,
            'category' => 1,
            'img' => $file
        ];

        $dashboard = $this->post('/dashboard/course/new', $course);
        $dashboard->assertSessionHasNoErrors();

        $this->assertFileExists('public/storage/course/cover/' . $file->hashName());
        $this->assertDatabaseHas('courses', ['title' => $title,
            'description' => $description,
            'difficulty' => 'beginner',
            'about' => $about,
            'img' => 'public/course/cover/' . $file->hashName()]);
    }

    /** @test */
    public function create_category_fail()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        Storage::fake('course');
        $title = $this->faker->streetName;

        $file = UploadedFile::fake()->image('logo.mp4');

        $title = $this->faker->name;
        $description = $this->faker->name;
        $about = $this->faker->text;

        $course = [
            'title' => $title,
            'description' => $description,
            'difficulty' => 'beginner',
            'about' => $about,
            'category' => 30,
            'img' => $file
        ];

        $dashboard = $this->post('/dashboard/course/new', $course);
        $dashboard->assertSessionHasErrors();
    }

    /** @test */
    public function access_dashboard_course_detail_success()
    {
        $email = ['admin@conlab.com', 'teacher@conlab.com'];
        $password = 'conlab123';

        $course = Courses::all();
        $course = $course->last();

        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();
            $login->assertRedirect('/dashboard');

            $dashboard = $this->get("/dashboard/course/$course->id/detailed");
            $dashboard->assertStatus(200);
            $dashboard->assertViewHas('objectives');
            $dashboard->assertViewHas('contributors');
            $dashboard->assertViewHas('users');

            $this->post('/logout');
        }
    }

    /** @test */
    public function access_dashboard_course_detail_fail()
    {
        $email = ['student@conlab.com', 'reviewer@conlab.com'];
        $password = 'conlab123';

        $course = Courses::all();
        $course = $course->last();

        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();

            $dashboard = $this->get("/dashboard/course/$course->id/detailed");
            $dashboard->assertStatus(302);
        }
    }

    /** @test */
    public function add_new_course_objective_success()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $course = Courses::all();
        $course = $course->last();

        $objective = $this->faker->name . ' objective';

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $respon = $this->post("/dashboard/course/$course->id/detailed/objective", ['objective' => $objective]);
        $respon->assertSessionHasNoErrors();

        $this->assertDatabaseHas('course_objective', ['course_id' => $course->id, 'title' => $objective]);
    }

    /** @test */
    public function add_new_course_objective_fail()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $course = Courses::all();
        $course = $course->last();
        $id = $course->id + 1;

        $objective = $this->faker->name . ' objective';

        $objective_count_before = CourseObjective::count();

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $this->post("/dashboard/course/$id/detailed/objective", ['objective' => $objective]);

        $objective_count_after = CourseObjective::count();

        $this->assertEquals($objective_count_after, $objective_count_before);
    }

    /** @test */
    public function delete_course_objective_success()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $course = Courses::all();
        $course = $course->last();
        $objective = CourseObjective::where('course_id', $course->id)->get();
        $objective = $objective->last();
        $objective_count_before = CourseObjective::count();
        $this->get("/dashboard/course/$course->id/detailed/objective/$objective->id/delete");
        $objective_count_after = CourseObjective::count();

        $this->assertEquals($objective_count_before - 1, $objective_count_after);
    }

    /** @test */
    public function delete_course_objective_fail()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $objective_count_before = CourseObjective::count();
        //id course dan id objective tidak ada di db
        $this->get("/dashboard/course/100/detailed/objective/100/delete");
        $objective_count_after = CourseObjective::count();

        $this->assertEquals($objective_count_before, $objective_count_after);
    }

    /** @test */
    public function add_new_course_contributor_success()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $course = Courses::all();
        $course = $course->last();

        $objective = $this->faker->name . ' objective';

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $user = User::where('role', 'reviewer')->get();
        $user = $user->first();
        $contributor_count_before = Contributors::count();
        $contributor = ['contributor' => $user->id, 'as' => 'reviewer'];

        $respon = $this->post("/dashboard/course/$course->id/detailed/contributor", $contributor);
        $respon->assertSessionHasNoErrors();

        $contributor_count_after = Contributors::count();

        $this->assertEquals($contributor_count_after, $contributor_count_before + 1);
        $this->assertDatabaseHas('contributors', ['course_id' => $course->id, 'accounts_id' => $user->id, 'as' => 'reviewer']);
    }

    /** @test */
    public function delete_course_contributor_success()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $course = Courses::all();
        $course = $course->last();

        $contributor = Contributors::all();
        $contributor = $contributor->last();

        $contributor_before = Contributors::count();

        $this->get("/dashboard/course/$course->id/detailed/contributor/$contributor->id/delete");

        $contributor_after = Contributors::count();

        $this->assertEquals($contributor_before, $contributor_after + 1);

    }

    /** @test */
    public function delete_course_contributor_fail()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $course = Courses::all();
        $course = $course->last();


        $contributor_before = Contributors::count();

        //course dan contributor tidak ada dalam db
        $this->get("/dashboard/course/1000/detailed/contributor/1000/delete");

        $contributor_after = Contributors::count();

        $this->assertEquals($contributor_before, $contributor_after);
    }


    /** @test */
    public function add_new_course_contributor_fail()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $course = Courses::all();
        $course = $course->last();

        $objective = $this->faker->name . ' objective';

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $user = User::where('role', 'reviewer')->get();
        $user = $user->first();
        $contributor_count_before = Contributors::count();
        //user id tidak ada di db
        $contributor = ['contributor' => '1000', 'as' => 'reviewer'];

        $respon = $this->post("/dashboard/course/$course->id/detailed/contributor", $contributor);
        $contributor_count_after = Contributors::count();
        $this->assertEquals($contributor_count_after, $contributor_count_before);
    }

    /** @test */
    public function access_dashboard_course_section_lesson_success()
    {
        $email = ['admin@conlab.com', 'teacher@conlab.com'];
        $password = 'conlab123';

        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();
            $login->assertRedirect('/dashboard');

            $course = Courses::all();
            $course = $course->last();

            $dashboard = $this->get("/dashboard/course/$course->id/info");
            $dashboard->assertStatus(200);
            $dashboard->assertViewHas('sections');
            $dashboard->assertViewHas('lessons');

            $this->post('/logout');
        }
    }

    /** @test */
    public function access_dashboard_course_section_lesson_fail()
    {
        $email = ['student@conlab.com', 'reviewer@conlab.com'];
        $password = 'conlab123';

        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();

            $course = Courses::all();
            $course = $course->last();

            $dashboard = $this->get("/dashboard/course/$course->id/info");
            $dashboard->assertStatus(302);
        }
    }

    /** @test */
    public function add_new_dashboard_course_section_success()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $course = Courses::all();
        $course = $course->last();
        $title = $this->faker->sentence;

        $respon = $this->post("/dashboard/course/$course->id/info/section", ['section' => $title]);
        $respon->assertSessionHasNoErrors();

        $this->assertDatabaseHas('course_sections', ['course_id' => $course->id, 'title' => $title]);
    }

    /** @test */
    public function add_new_dashboard_course_section_fail()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $section_before = CourseSections::count();

        $course = Courses::all();
        $course = $course->last();
        $title = $this->faker->text;

        $section_after = CourseSections::count();

        //course tidak ada di db dan section terlalu panjang
        $respon = $this->post("/dashboard/course/1000/info/section", ['section' => $title]);

        $this->assertEquals($section_before, $section_after);

    }

    /** @test */
    public function add_new_dashboard_course_lesson_success()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $course = Courses::all();
        $course = $course->last();

        $section = CourseSections::all();
        $section = $section->last();

        Storage::fake('lesson');

        $file = UploadedFile::fake()->image('lesson.mp4');

        $title = $this->faker->streetName;
        $description = $this->faker->text;

        $lesson = [
            'title' => $title,
            'section_id' => $section->id,
            'description' => $description,
            'is_coding' => 'yes',
            'is_video' => 'yes',
            'video-int' => $file
        ];

        $respon = $this->post("/dashboard/course/$course->id/info/lesson", $lesson);
        $respon->assertSessionHasNoErrors();

        $this->assertFileExists('public/storage/course/video/' . $file->hashName());

        $this->assertDatabaseHas('section_lessons', [
            'title' => $title,
            'section_id' => $section->id,
            'description' => $description,
            'is_coding' => 1,
            'is_video' => 1
        ]);

        $lesson_new = SectionLessons::all();
        $lesson_new = $lesson_new->last();

        $this->assertDatabaseHas('lesson_video', [
            'lesson_id' => $lesson_new->id,
            'title' => $title,
            'url' => 'public/course/video/' . $file->hashName()
        ]);
    }

    /** @test */
    public function add_new_dashboard_course_lesson_fail()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $course = Courses::all();
        $course = $course->last();

        $section = CourseSections::all();
        $section = $section->last();

        Storage::fake('lesson');

        $file = UploadedFile::fake()->image('lesson.jpg');

        $title = $this->faker->text;
        $description = $this->faker->text;

        $lesson = [
            'title' => $title,
            'section_id' => $section->id,
            'description' => $description,
            'is_coding' => 'yes',
            'is_video' => 'yes',
            'video-int' => $file
        ];

        $lesson_before = SectionLessons::count();

        //file seharusnya video diganti image dan title melebihi kapasitas
        $respon = $this->post("/dashboard/course/$course->id/info/lesson", $lesson);

        $lesson_after = SectionLessons::count();

        $this->assertEquals($lesson_before, $lesson_after);
    }

    /** @test */
    public function access_dashboard_dashboard_quiz_success()
    {
        $email = ['admin@conlab.com', 'teacher@conlab.com'];
        $password = 'conlab123';

        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();
            $login->assertRedirect('/dashboard');

            $dashboard = $this->get('/dashboard/quiz');
            $dashboard->assertStatus(200);
            $dashboard->assertViewHas('courses');

            $this->post('/logout');
        }
    }

    /** @test */
    public function access_dashboard_quiz_fail()
    {
        $email = ['student@conlab.com', 'reviewer@conlab.com'];
        $password = 'conlab123';

        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();

            $dashboard = $this->get('/dashboard/quiz');
            $dashboard->assertStatus(302);
        }
    }

    /** @test */
    public function access_dashboard_quiz_section_success()
    {
        $email = ['admin@conlab.com', 'teacher@conlab.com'];
        $password = 'conlab123';
        $course = Courses::all();
        $course = $course->last();

        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();
            $login->assertRedirect('/dashboard');

            $dashboard = $this->get("/dashboard/quiz/$course->id/section");
            $dashboard->assertStatus(200);
            $dashboard->assertViewHas('sections');

            $this->post('/logout');
        }
    }

    /** @test */
    public function access_dashboard_quiz_section_fail()
    {
        $email = ['student@conlab.com', 'reviewer@conlab.com'];
        $password = 'conlab123';
        $course = Courses::all();
        $course = $course->last();

        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();

            $dashboard = $this->get("/dashboard/quiz/$course->id/section");
            $dashboard->assertStatus(302);
        }
    }

    /** @test */
    public function access_dashboard_quiz_section_detail_success()
    {
        $email = ['admin@conlab.com', 'teacher@conlab.com'];
        $password = 'conlab123';
        $section = CourseSections::all();
        $section = $section->last();

        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();
            $login->assertRedirect('/dashboard');

            $dashboard = $this->get("/dashboard/quiz/section/$section->id/detail");
            $dashboard->assertStatus(200);
            $dashboard->assertViewHas('quiz');

            $this->post('/logout');
        }
    }

    /** @test */
    public function access_dashboard_quiz_section_detail_fail()
    {
        $email = ['student@conlab.com', 'reviewer@conlab.com'];
        $password = 'conlab123';
        $section = CourseSections::all();
        $section = $section->last();
        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();

            $dashboard = $this->get("/dashboard/quiz/section/$section->id/detail");
            $dashboard->assertStatus(302);
        }
    }

    /** @test */
    public function change_dashboard_quiz_section_detail_success()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $section = CourseSections::all();
        $section = $section->last();

        $title = $this->faker->streetName;
        $description = $this->faker->text;

        $quiz = [
            'title' => $title,
            'description' => $description
        ];

        $respon = $this->post("/dashboard/quiz/section/$section->id/detail/store", $quiz);
        $respon->assertSessionHasNoErrors();

        $this->assertDatabaseHas('quizzes', [
            'section_id' => $section->id,
            'title' => $title,
            'description' => $description
        ]);
    }

    /** @test */
    public function change_dashboard_quiz_section_detail_fail()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $section = CourseSections::all();
        $section = $section->last();

        $title = $this->faker->text(500);
        $description = $this->faker->text;

        $quiz = [
            'title' => $title,
            'description' => $description
        ];

        $respon = $this->post("/dashboard/quiz/section/$section->id/detail/store", $quiz);
        $respon->assertSessionHasErrors();

        $this->assertDatabaseMissing('quizzes', [
            'section_id' => $section->id,
            'title' => $title,
            'description' => $description
        ]);
    }


    /** @test */
    public function access_dashboard_quiz_section_question_success()
    {
        $email = ['admin@conlab.com', 'teacher@conlab.com'];
        $password = 'conlab123';
        $section = CourseSections::all();
        $section = $section->last();

        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();
            $login->assertRedirect('/dashboard');

            $dashboard = $this->get("/dashboard/quiz/section/$section->id/question");
            $dashboard->assertStatus(200);
            $dashboard->assertViewHas('quiz');
            $dashboard->assertViewHas('questions');

            $this->post('/logout');
        }
    }

    /** @test */
    public function access_dashboard_quiz_section_question_fail()
    {
        $email = ['student@conlab.com', 'reviewer@conlab.com'];
        $password = 'conlab123';
        $section = CourseSections::all();
        $section = $section->last();
        for ($i = 0; $i < 2; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();

            $dashboard = $this->get("/dashboard/quiz/section/$section->id/question");
            $dashboard->assertStatus(302);
        }
    }

    /** @test */
    public function add_new_dashboard_quiz_section_question_success()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $section = CourseSections::all();
        $section = $section->last();
        $quiz = Quiz::where('section_id', $section->id)->first();

        $question = $this->faker->sentence;
        $option = [$this->faker->sentence, $this->faker->sentence, $this->faker->sentence];
        $point = [0, 2, 3];

        Storage::fake('question');

        $file = UploadedFile::fake()->image('question.jpg');

        $question = [
            'question' => $question,
            'answer' => [$option[0], $option[1], $option[2]],
            'point' => [$point[0], $point[1], $point[2]],
            'file' => $file
        ];

        $respon = $this->post("/dashboard/quiz/section/$section->id/question/store", $question);
        $respon->assertSessionHasNoErrors();

        $question_new = Question::all();
        $question_new = $question_new->last();

        $this->assertFileExists('public/storage/course/quiz/question/' . $file->hashName());
        $this->assertDatabaseHas('questions', [
            'quiz_id' => $quiz->id,
            'question' => $question,
            'file_exist' => 1
        ]);
        $this->assertDatabaseHas('question_files', [
            'question_id' => $question_new->id,
            'type' => 'image',
            'url' => 'public/course/quiz/question/' . $file->hashName()
        ]);
        for ($i = 0; $i < 3; $i++) {
            $this->assertDatabaseHas('answers', [
                'question_id' => $question_new->id,
                'answer' => $option[$i],
                'point' => $point[$i]
            ]);
        }
    }

    /** @test */
    public function add_new_dashboard_quiz_section_question_failed()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $section = CourseSections::all();
        $section = $section->last();
        $quiz = Quiz::where('section_id', $section->id)->first();

        $question = $this->faker->sentence;
        $point = [0, 2, 3];

        Storage::fake('question');

        $file = UploadedFile::fake()->image('question.mp4');

        $question = [
            'question' => $question,
            'answer' => '',
            'point' => [$point[0], $point[1], $point[2]],
            'file' => $file
        ];

        $question_before = Question::count();

        $respon = $this->post("/dashboard/quiz/section/$section->id/question/store", $question);
        $respon->assertSessionHasErrors();

        $question_after = Question::count();
        $this->assertEquals($question_before, $question_after);
    }

    /** @test */
    public function delete_dashboard_quiz_section_question_success()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $question = Question::all();
        $question = $question->last();

        $question_before = Question::count();

        $respon = $this->post("dashboard/quiz/section/question/$question->id/delete");
        $respon->assertSessionHasNoErrors();

        $question_after = Question::count();

        $this->assertDatabaseMissing('questions', [
            'quiz_id' => $question->quiz_id,
            'question' => $question->question,
            'file_exist' => $question->file_exist
        ]);
        $this->assertDatabaseMissing('answers', ['question_id' => $question->id]);
        $this->assertDatabaseMissing('question_files', ['question_id' => $question->id]);
        $this->assertEquals($question_before, $question_after + 1);
    }

    /** @test */
    public function delete_dashboard_quiz_section_question_fail()
    {
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $question_before = Question::count();

        //question tidak ada di db
        $respon = $this->post("dashboard/quiz/section/question/1000/delete");

        $question_after = Question::count();

        $this->assertEquals($question_before, $question_after);
    }

    /** @test  */
    public function delete_dashboard_course_lesson_success(){
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $lesson = SectionLessons::all();
        $lesson = $lesson->last();

        $course = Courses::all();
        $course = $course->last();

        $respon = $this->get("/dashboard/course/$course->id/info/lesson/$lesson->id/delete");
        $respon->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('section_lessons',[
           'section_id'=>$lesson->section_id,
           'title'=>$lesson->title,
           'description'=>$lesson->description,
           'is_coding'=>$lesson->is_coding,
           'is_video'=>$lesson->is_video,
            'deleted_at'=>null
        ]);
    }

    /** @test  */
    public function delete_dashboard_course_lesson_fail(){
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $course = Courses::all();
        $course = $course->last();

        //lesson tidak ada di db
        $respon = $this->get("/dashboard/course/$course->id/info/lesson/1000/delete");
        $respon->assertSessionHasErrors();;

    }

    /** @test  */
    public function delete_dashboard_course_section_success(){
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $section = CourseSections::all();
        $section = $section->last();

        $course = Courses::all();
        $course = $course->last();

        $section_before = CourseSections::count();

        $respon = $this->get("/dashboard/course/$course->id/info/section/$section->id/delete");
        $respon->assertSessionHasNoErrors();

        $section_after = CourseSections::count();

        $this->assertDatabaseMissing('course_sections',[
            'course_id'=>$section->course_id,
            'title'=>$section->title,
        ]);
        $this->assertEquals($section_before,$section_after+1);
    }

    /** @test  */
    public function delete_dashboard_course_section_fail(){
        $email = 'teacher@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $course = Courses::all();
        $course = $course->last();

        $section_before = CourseSections::count();

        $respon = $this->get("/dashboard/course/$course->id/info/section/1000/delete");
        $respon->assertSessionHasNoErrors();

        $section_after = CourseSections::count();
        $this->assertEquals($section_before,$section_after);

    }
}
