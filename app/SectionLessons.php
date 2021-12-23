<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SectionLessons extends Model{
    use SoftDeletes;

    protected $table = 'section_lessons';

    protected $fillable = ['section_id', 'title', 'description', 'is_coding', 'is_video'];

    public function video(){
        return $this->hasOne('App\LessonVideo', 'lesson_id');
    }

    public function codes(){
        return $this->hasMany('App\Codes','lesson_id');
    }

    public function section(){
        return $this->belongsTo('App\CourseSections','section_id');
    }
}
