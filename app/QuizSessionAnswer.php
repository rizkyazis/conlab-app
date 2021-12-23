<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizSessionAnswer extends Model
{
    protected $table = 'quiz_session_answers';

    protected $fillable = [
        'session_id',
        'question_id',
        'answer_id'
    ];

    public function question(){
        return $this->belongsTo('App\Question','question_id');
    }

    public function session(){
        return $this->belongsTo('App\QuizSession','session_id');
    }

    public function answer(){
        return $this->belongsTo('App\Answer','answer_id');
    }
}
