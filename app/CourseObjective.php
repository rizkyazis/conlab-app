<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseObjective extends Model
{
    protected $table = 'course_objective';

    protected $fillable = ['course_id', 'title'];

    public function course(){
        return $this->belongsTo('App\Courses');
    }
}
