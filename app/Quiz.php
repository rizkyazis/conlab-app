<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizzes';

    protected $fillable = [
        'section_id',
        'title',
        'description'
    ];

    public function section(){
        return $this->belongsTo('App\CourseLesson','section_id');
    }

    public function question(){
        return $this->haveMany('App\Question','section_id');
    }

    public function session(){
        return $this->haveMany('App\QuizSession','section_id');
    }
}
