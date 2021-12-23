<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizSession extends Model
{
    protected $table = 'quiz_sessions';

    protected $fillable = [
        'quiz_id',
        'account_id',
        'status',
        'score'
    ];

    public function quiz(){
        return $this->belongsTo('App\Quiz','quiz_id');
    }

    public function account(){
        return $this->belongsTo('App\Account','account_id');
    }

    public function answer(){
        return $this->hasMany('App\QuizSessionAnswer','session_id');
    }
}
