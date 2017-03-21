<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public function user() {
	    return $this->belongsTo('App\User', 'user_posts');
    }

    public function comments() {
		return $this->hasMany('App\Comment', 'post_comments');
	}

    public function tags() {
	    return $this->belongsToMany('App\Tag', 'post_tags');
    }
}
