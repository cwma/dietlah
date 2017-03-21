<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post_Comment extends Model
{
    public function post() {
        return $this->belongsTo('App\Post');
    }

    public function comments() {
        return $this->hasMany('App\Comment');
    }
}
