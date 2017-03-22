<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post_Tag extends Model
{
    public function tag() {
	return $this->belongsTo('App\Tag');
    }

    public function post() {
	return $this->belongsTo('App\Post');
    }
}
