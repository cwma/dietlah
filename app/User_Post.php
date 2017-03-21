<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_Post extends Model
{
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function posts() {
        return $this->hasMany('App\Post');
    }
}
