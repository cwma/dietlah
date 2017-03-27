<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public function user() {
	    return $this->belongsTo('App\User');
    }

    public function comments() {
	    return $this->hasMany('App\Comment');
    }

    public function tags() {
	    return $this->belongsToMany('App\Tag', 'post_tags');
    }

    public function post_tags() {
	    return $this->hasMany('App\PostTag');
    }

    public function likes() {
        return $this->hasMany('App\Like');
    }

    public function favourites() {
        return $this->hasMany('App\Favourite');
    }
}
