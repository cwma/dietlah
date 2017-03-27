<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    protected $fillable = ['user_id', 'post_id', 'tag_id'];

    public function tag() {
	return $this->belongsTo('App\Tag');
    }

    public function post() {
	return $this->belongsTo('App\Post');
    }
}
