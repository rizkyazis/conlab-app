<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $table = 'points';

    protected $fillable = [
        'account_id',
        'lesson_id',
        'quiz_id',
        'point'
    ];

    public function account(){
        return $this->belongsTo('App\Account','account_id');
    }

    public function lesson(){
        return $this->belongsTo('App\Codes','lesson_id');
    }

    public function quiz(){
        return $this->belongsTo('App\QuizSession','quiz_id');
    }
}
