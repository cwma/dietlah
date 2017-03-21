<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function post() {
        return $this->belongsTo('App\Post', 'post_comments');
    }
}
