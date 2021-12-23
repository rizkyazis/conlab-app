<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';

    protected $fillable = [
        'quiz_id',
        'question',
        'file_exist'
    ];

    public function quiz(){
        return $this->belongsTo('App\Quiz','quiz_id');
    }

    public function file(){
        return $this->hasOne('App\QuestionFile');
    }

    public function answer(){
        return $this->hasMany('App\Answer');
    }
}
