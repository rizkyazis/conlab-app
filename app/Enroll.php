<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enroll extends Model
{
    protected $table = 'enroll';

    protected $fillable = ['account_id', 'course_id', 'is_finished'];

    public function course(){
        return $this->belongsTo('App\Courses');
    }

    public function account(){
        return $this->belongsTo('App\Accounts');
    }

}
