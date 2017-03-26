<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ["tag_name"];

    public function posts() {
        return $this->belongsToMany('App\Post', 'post_tags');
    }

    public function post_tags() {
	return $this->hasMany('App\PostTag');
    }
}
