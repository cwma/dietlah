<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{

    use Searchable;


    public function user() {
	    return $this->belongsTo('App\User');
    }

    public function comments() {
	    return $this->hasMany('App\Comment');
    }

    public function tags() {
	    return $this->belongsToMany('App\Tag', 'post_tags');
    }

    public function likes() {
        return $this->hasMany('App\Like');
    }

    public function favourites() {
        return $this->hasMany('App\Favourite');
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();
        return $array;
    }
}
