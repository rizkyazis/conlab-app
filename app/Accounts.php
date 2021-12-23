<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accounts extends Model
{
    protected $table = 'accounts';

    protected $fillable = ['user_id', 'fullname','birth_place','birth_date','contact', 'address','photo'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function enrolls(){
        return $this->hasMany('App\Enroll');
    }

    public function contributor(){
        return $this->hasOne('App\Contributors');
    }

    public function codes(){
        return $this->hasMany('App\Codes');
    }

    public function session(){
        return $this->haveMany('App\QuizSession','section_id');
    }
}
