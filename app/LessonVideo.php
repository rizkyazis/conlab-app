<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonVideo extends Model
{
    protected $table = 'lesson_video';

    protected $fillable = ['lesson_id', 'title', 'url'];

    public function lesson(){
        return $this->belongsTo('App\SectionLessons');
    }
}
