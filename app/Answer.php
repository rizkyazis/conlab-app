<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'answers';

    protected $fillable = [
        'question_id',
        'answer',
        'point'
    ];

    public function question(){
        return $this->belongsTo('App\Question','question_id');
    }
}
