<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@index')->name('index');
Route::get('/language', 'HomeController@index')->name('language');

//User Route
Route::post('/getstarted','CourseController@get_started')->name('get.started');
Route::get('/courses', 'CourseController@index')->name('courses');
Route::get('/courses/search', 'CourseController@search')->name('course.search');
Route::get('/courses/category','CourseController@category')->name('category');
Route::get('/courses/category/{category}','CourseController@category_course')->name('courses.category');
Route::get('/course/{id}/details', 'CourseController@detail')->name('course');


Route::get('/point','PointController@index')->middleware('auth')->name('point');

Route::prefix('profile')->middleware('auth')->group(function () {
    Route::get('/', 'ProfileController@index')->name('profile.account');
    Route::post('/', 'ProfileController@update')->name('profile.account.update');
    Route::post('/password', 'ProfileController@password_update')->name('profile.password.update');
});

Route::middleware('profile.check')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('course')->group(function () {
            Route::get('/{id}/enroll','CourseController@enroll')->name('enroll.course');
            Route::get('/{id}/section/{section_id}/lesson/{lesson_id}', 'CourseController@lesson')->name('lesson');
            Route::post('/{id}/section/{section_id}/lesson/{lesson_id}/store', 'CourseController@code_store')->name('lesson.code.store');

            //quiz
            Route::get('/{id}/section/{section_id}/quiz/{quiz_id}', 'QuizController@index')->name('quiz');
            //question
            Route::get('/{id}/section/{section_id}/quiz/{quiz_id}/question', 'QuizController@question')->name('quiz.question');
            Route::post('/{id}/section/{section_id}/quiz/{quiz_id}/question/answer', 'QuizController@questionAnswer')->name('quiz.question.answer');
            Route::post('/{id}/section/{section_id}/quiz/{quiz_id}/submit', 'QuizController@submit')->name('quiz.submit');
            //certificate
            Route::get('/{id}/certificate', 'CourseController@certificate')->name('course.certificate');

        });

        Route::prefix('score')->group(function () {
            Route::get('/', 'ScoreController@index')->name('score.index');
        });
    });


//Admin, Teacher, Reviewer Route
    Route::prefix('dashboard')->middleware(['auth', 'role:teacher,reviewer,admin'])->group(function () {
        Route::get('/', 'Dashboard\DashboardController@index')->name('dashboard');

        Route::middleware(['auth', 'role:admin'])->group(function () {
            Route::prefix('user')->group(function () {
                Route::get('/', 'Dashboard\UserController@index')->name('user.index');
                Route::get('/search','Dashboard\UserController@user_search')->name('user.search');
                Route::put('/{id}/roles', 'Dashboard\UserController@roles')->name('user.role');
            });

            Route::prefix('category')->group(function () {
                Route::get('/', 'Dashboard\CategoryController@index')->name('category.index');
                Route::get('/search', 'Dashboard\CategoryController@category_search')->name('category.search');
                Route::post('/create', 'Dashboard\CategoryController@create')->name('category.create');
            });
        });


        Route::prefix('course')->middleware(['auth', 'role:teacher,admin'])->group(function () {
            Route::get('/', 'Dashboard\CourseController@index')->name('dashboard.course.index');
            Route::get('/search','Dashboard\CourseController@course_search')->name('dashboard.course.search');

            /**
             * General Info start here
             */
            Route::get('/new', 'Dashboard\CourseController@index_create')->name('dashboard.course.new');
            Route::post('/new', 'Dashboard\CourseController@general_store')->name('dashboard.course.new.store');

            /**
             * Detailed Info start here
             */
            Route::get('/{id}','Dashboard\CourseController@course')->name('dashboard.course.detailed');
            Route::post('/{id}','Dashboard\CourseController@course_update')->name('dashboard.course.update');

            Route::get('/{id}/detailed', 'Dashboard\CourseController@detailed')->name('dashboard.course.detailed.new');
            // Objective
            Route::post('/{id}/detailed/objective', 'Dashboard\CourseController@objective_store')->name('dashboard.course.objective.store');
            Route::get('/{id}/detailed/objective/{obj_id}/delete', 'Dashboard\CourseController@objective_destroy')->name('dashboard.course.objective.destroy');
            // Contributor
            Route::post('/{id}/detailed/contributor', 'Dashboard\CourseController@contributor_store')->name('dashboard.course.contributor.store');
            Route::get('/{id}/detailed/contributor/{cont_id}/delete', 'Dashboard\CourseController@contributor_destroy')->name('dashboard.course.contributor.destroy');

            /**
             * Lesson Info start here
             */
            Route::get('{id}/info', 'Dashboard\CourseController@info')->name('dashboard.course.new.info');
            // Section
            Route::post('{id}/info/section', 'Dashboard\CourseController@section_store')->name('dashboard.course.section.store');
            Route::get('{id}/info/section/{section_id}/delete', 'Dashboard\CourseController@section_destroy')->name('dashboard.course.section.destroy');

            // Lesson
            Route::post('{id}/info/lesson', 'Dashboard\CourseController@lesson_store')->name('dashboard.course.lesson.store');
            Route::get('{id}/info/lesson/{lesson_id}/delete', 'Dashboard\CourseController@lesson_destroy')->name('dashboard.course.lesson.destroy');
        });

        Route::prefix('quiz')->group(function () {
            Route::get('/', 'Dashboard\QuizController@index')->name('dashboard.quiz.index');
            Route::get('/search','Dashboard\QuizController@course_search')->name('dashboard.quiz.search');
            Route::get('/{id}/section', 'Dashboard\QuizController@section')->name('dashboard.quiz.section');
            Route::get('/section/{section_id}/detail', 'Dashboard\QuizController@detail')->name('dashboard.quiz.detail');
            Route::post('/section/{section_id}/detail/store', 'Dashboard\QuizController@store')->name('dashboard.quiz.store');
            //question
            Route::get('/section/{section_id}/question', 'Dashboard\QuizController@question')->name('dashboard.quiz.question');
            Route::post('/preview','Dashboard\QuizController@preview_question')->name('preview.question');
            Route::post('/section/{section_id}/question/store', 'Dashboard\QuizController@questionStore')->name('dashboard.quiz.question.store');
            Route::post('/section/question/{question_id}/delete', 'Dashboard\QuizController@questionDelete')->name('dashboard.quiz.question.delete');
        });

        Route::prefix('review')->group(function () {
            Route::get('/', 'Dashboard\ReviewController@index')->name('review');
            Route::get('/search','Dashboard\ReviewController@course_search')->name('course.review.search');
            Route::get('/{id}/lessons', 'Dashboard\ReviewController@lessons')->name('review.lessons');
            Route::get('/{id}/lessons/{lesson_id}/participants', 'Dashboard\ReviewController@participants')->name('review.participants');
            Route::get('/{id}/lessons/{lesson_id}/participants/search', 'Dashboard\ReviewController@participants_search')->name('review.participants_search');
            Route::get('/{id}/lessons/{lesson_id}/participant/{account_id}/code', 'Dashboard\ReviewController@code')->name('review.code');
            Route::post('/{id}/lessons/{lesson_id}/participant/{account_id}/code/update', 'Dashboard\ReviewController@update')->name('review.update');
        });

    });
});

Auth::routes();
