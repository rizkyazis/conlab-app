<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tag';

    protected $fillable = ['name','image'];

    public function course_tags(){
        return $this->hasMany('App\CourseTag');
    }

    public function courses(){
        return $this->hasManyThrough(Courses::class, CourseTag::class, "tag_id", "id");
    }
}
