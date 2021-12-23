<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    protected $table = 'courses';

    protected $fillable = [
        'title', 'description', 'about', 'difficulty', 'img'
    ];

    public function contributors(){
        return $this->hasMany('App\Contributors');
    }

    public function course_tags(){
        return $this->hasOne('App\CourseTag','course_id');
    }

    public function tags(){
        return $this->hasOneThrough(Tag::class, CourseTag::class, 'course_id', 'id','id','tag_id');
    }

    public function course_objectives(){
        return $this->hasMany('App\CourseObjective');
    }

    public function sections(){
        return $this->hasMany('App\CourseSections','course_id');
    }

    public function enrolls(){
        return $this->hasMany('App\Enroll', 'course_id', 'id');
    }

    public function accounts(){
        return $this->hasManyThrough(Accounts::class, Enroll::class, 'course_id', 'id');
    }
}
