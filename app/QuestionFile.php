<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionFile extends Model
{
    protected $table = 'question_files';

    protected $fillable = [
        'question_id',
        'type',
        'url'
    ];

    public function question(){
        return $this->belongsTo('App\Question','question_id');
    }

}
