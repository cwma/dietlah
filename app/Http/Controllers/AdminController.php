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

use App\Report;
use App\Post;
use App\Comment;

class AdminController extends Controller {

    public function index() {
        if(Auth::check() && Auth::user()->is_admin){
        	$reports = Report::orderBy("created_at", "desc")->paginate(5);

        	$posts = [];

        	foreach ($reports as $report) {
        		if ($report->report_type == "comment") {
        			$comment = Comment::find($report->reported_id);
        			if ($comment) {
        				$posts[$report->reported_id] = $comment;
        			} else {
        				$posts[$report->reported_id] = false;
        			}
        		} else {
        			$post = Post::find($report->reported_id);
        			if ($post) {
        				$posts[$report->reported_id] = $post;
        			} else {
        				$posts[$report->reported_id] = false;
        			}
        		}
        	}

        	return view('admin', ["reports"=>$reports, "posts"=>$posts]);
        } else {
        	abort(401);
        }
    }

}