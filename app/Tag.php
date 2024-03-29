<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['tag_name'];

    public function posts() {
        return $this->belongsToMany('App\Post', 'post_tags');
    }

    public function post_tags() {
	return $this->hasMany('App\PostTag');
    }
}
