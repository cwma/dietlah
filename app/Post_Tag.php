<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post_Tag extends Model
{
    public function posts() {
        return $this->belongsToMany('App\Post');
    }

    public function tags() {
        return $this->belongsToMany('App\Tag');
    }
}
