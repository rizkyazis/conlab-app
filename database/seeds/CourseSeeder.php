<?php

use App\Answer;
use App\Contributors;
use App\CourseObjective;
use App\Courses;
use App\CourseSections;
use App\CourseTag;
use App\Question;
use App\Quiz;
use App\SectionLessons;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $title = ['HTML dan CSS Dasar', 'Pemrograman Python untuk Pemula'];
        $description = [
            'Mengenal dan belajar dasar-dasar HTML dan CSS untuk fondasi awal belajar web development',
            'Panduan langkah demi langkah untuk belajar pemrograman Python'
        ];
        $about = [
            'Dalam kelas online ini kamu akan belajar dasar-dasar HTML dan CSS. HTML dan CSS merupakan pengetahuan umum yang harus dimiliki oleh setiap orang yang menggeluti bidang web programming. Dalam kelas ini, kamu akan belajar mulai dari bagaimana konsep dasar dan cara kerja dari HTML dan CSS serta bagaimana penggunaannya dalam membangun sebuah tampilan website.',
            'Dalam kelas online ini kamu akan belajar langkah demi langkah dasar-dasar Bahasa Pemrograman Python. Materi belajar telah disusun sedemikian rupa agar kamu mudah dalam memahaminya.'
        ];
        $difficulty = 'beginner';
        $image = ['public/images/seed/htmlcourse.png', 'public/images/seed/pythoncourse.jpg'];
        $copied_image = ['public/storage/course/cover/htmlcourse.png', 'public/storage/course/cover/pythoncourse.jpg'];
        $image_url = ['public/course/cover/htmlcourse.png', 'public/course/cover/pythoncourse.jpg'];
        $tag = ['1', '2'];
        $objectives = [
            [
                'Dapat mengenal HTML dan CSS',
                'Dapat mengetahui dasar-dasar pemrograman',
                'Dapat menginstal software yang dibutuhkan dalam belajar pemrograman',
                'Dapat menjelaskan dasar-dasar pemrograman'
            ],
            [
                'Dapat menegenal Bahasa Pemprogramman Python',
                'Dapat mengenal syntax dasar Python',
                'Dapat menjelaskan dasar-dasar pemrograman'
            ]
        ];

        $contributors = [2, 3];
        $contributors_role = ['teacher', 'reviewer'];

        $sections = [
            [
                'Pengenalan HTML dan CSS',
                'Dasar HTML dan CSS',
            ],
            [
                'Pengenalan Python',
                'Syntax dasar Python',
            ]
        ];

        $lesson_title = [
            [
                ['Apa itu HTML?', 'Apa itu CSS'],
                ['Heading', 'Input Tag']
            ],
            [
                ['Apa itu Python?', 'Syntax dasar Python'],
                ['Conditional', 'Looping']
            ],
        ];
        $lesson_is_coding = [
            [
                [0, 0],
                [1, 1]
            ],
            [
                [0, 1],
                [1, 1]
            ]
        ];

        $lesson_description = [
            [
                ['Penjelasan HTML','Penjelasan Css'],
                ['Penjelasan Heading dan Studi kasus Heading','Penjelasan Input Tag dan Studi kasus Input Tag']
            ],
            [
                ['Penjelasan Python','Penjelasan Syntax dasar Python dan Studi Kasus'],
                ['Penjelasan Conditional dan Studi Kasus Conditional','Penjelasan Loopign dan Studi kasus Looping']
            ]
        ];

        $quiz_title = [
            [
                'Quiz HTML dan CSS',
                'Quiz Tag HTML dan CSS',
            ],
            [
                'Quiz Intro Python',
                'Quiz Syntax dasar Python',
            ]
        ];

        $quiz_description = [
            [
                'Deskripsi Quiz HTML dan CSS',
                'Deskripsi Quiz Tag HTML dan CSS',
            ],
            [
                'Deskripsi Quiz Intro Python',
                'Deskripsi Quiz Syntax dasar Python',
            ]
        ];

        $question = ['soal 1','soal 2','soal 3','soal 4','soal 5'];
        $answer = ['answer true','answer false','answer half true','answer false'];
        $answer_point = [4,0,2,0];

        for ($i = 0; $i < 2; $i++) {
            copy($image[$i], $copied_image[$i]);
            $course = Courses::create([
                'title' => $title[$i],
                'description' => $description[$i],
                'about' => $about[$i],
                'difficulty' => $difficulty,
                'img' => $image_url[$i]
            ]);

            CourseTag::create([
                'course_id' => $course->id,
                'tag_id' => $tag[$i]
            ]);

            foreach ($objectives[$i] as $objective) {
                CourseObjective::create([
                    'course_id' => $course->id,
                    'title' => $objective
                ]);
            }

            for ($n = 0; $n < 2; $n++) {
                Contributors::create([
                    'course_id' => $course->id,
                    'accounts_id' => $contributors[$n],
                    'as' => $contributors_role[$n]
                ]);
            }
            $count = 0;
            foreach ($sections[$i] as $section) {
                $new_section = CourseSections::create([
                    'course_id' => $course->id,
                    'title' => $section
                ]);
                for ($o = 0;$o<2;$o++){
                    SectionLessons::create([
                        'title'=>$lesson_title[$i][$count][$o],
                        'section_id'=>$new_section->id,
                        'is_coding'=>$lesson_is_coding[$i][$count][$o],
                        'description'=>$lesson_description[$i][$count][$o],
                        'is_video'=>0
                    ]);
                }

                $quiz_new = Quiz::create([
                    'section_id'=>$new_section->id,
                    'title'=>$quiz_title[$i][$count],
                    'description'=>$quiz_description[$i][$count]
                ]);

                for ($o = 0;$o<5;$o++) {
                    $question_new = Question::create([
                        'quiz_id'=>$quiz_new->id,
                        'question'=>$question[$o],
                        'file_exist'=>0
                    ]);
                    for ($n = 0;$n<4;$n++){
                        $answer_new = Answer::create([
                            'question_id'=>$question_new->id,
                            'answer'=>$answer[$n],
                            'point'=>$answer_point[$n],
                        ]);
                    }
                }

                $count++;
            }
        }
    }
}
