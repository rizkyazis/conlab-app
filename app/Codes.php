<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Codes extends Model
{
    protected $table = 'codes';

    protected $fillable = [
        'lesson_id', 'account_id', 'contributor_id',
        'raw_code', 'output', 'feedback', 'score',
        'is_reviewed'
    ];

    public function account(){
        return $this->belongsTo('App\Accounts');
    }

    public function contributor(){
        return $this->belongsTo('App\Contributors');
    }

    public function lesson(){
        return $this->belongsTo('App\SectionLessons');
    }
}
