<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contributors extends Model
{
    protected $table = 'contributors';

    protected $fillable = [
        'course_id', 'accounts_id', 'as'
    ];

    public function codes(){
        return $this->hasMany('App\Codes');
    }

    public function courses(){
        return $this->belongsToMany('App\Courses');
    }

    public function accounts(){
        return $this->belongsTo('App\Accounts');
    }
}
