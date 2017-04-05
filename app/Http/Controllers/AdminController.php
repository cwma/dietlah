<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use JavaScript;
use Faker;
use Storage;

use App\Post;
use App\Like;
use App\Favourite;
use App\Comment;
use App\Tag;
use App\User;
use App\PostTag;

class AdminController extends Controller {

    public function index() {
        if(Auth::check() && Auth::user()->is_admin){
        	return view('admin');
        } else {
        	abort(401);
        }
    }

}