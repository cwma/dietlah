<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('user_id', 'post_id');
}
