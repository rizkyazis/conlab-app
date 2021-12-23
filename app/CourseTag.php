<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseTag extends Model
{
    protected $table = 'course_tag';
    protected $fillable = ['course_id', 'tag_id'];

    public function tag(){
        return $this->belongsTo('App\Tag','tag_id');
    }

    public function courses(){
        return $this->belongsTo('App\Courses','course_id');
    }
}
