<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseSections extends Model
{
    protected $table = 'course_sections';

    protected $fillable = ['course_id', 'title'];

    public function courses(){
        return $this->belongsTo('App\Courses','course_id');
    }

    public function quiz(){
        return $this->hasMany('App\Quiz','section_id');
    }

    public function lessons(){
        return $this->hasMany('App\SectionLessons', 'section_id');
    }
}
