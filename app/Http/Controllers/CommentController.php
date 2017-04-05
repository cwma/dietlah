<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Post;
use App\Comment;
class CommentController extends Controller {

	public function createComment (Request $request){
		if (Auth::check()){

			$validator = Validator::make($request->all(), [
                'comment' => 'required|max:1000',
            ]);

            if ($validator->fails()) {
                $response = ["status" => "failed", "reason" => $validator->errors()->all()];
                return response(json_encode($response)) ->header('Content-Type', 'application/json');
            }

			$comment = new Comment;
			$comment->comment = $request->comment;
			$comment->user_id = Auth::user()->id;
			$comment->post_id = $request->post_id;
			$comment->save();

			$comments_count = Comment::where("post_id", $request->post_id)->count();
            $post = Post::findOrFail($request->post_id);
            $post->comments_count = $comments_count;
            $post->save();

	        $response = ["status" => "success", "response" => "comment created!"];
	        return response(json_encode($response)) ->header('Content-Type', 'application/json');
		}
        $response = ["status" => "failed", "reason" => ["unauthorized"]];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
	}

	public function deleteComment (Request $request){

		if (Auth::check()){
			$comment = Comment::findOrFail($request->comment_id);

			if(Auth::id() == $comment->user_id) {

				$post_id = $comment->post->id;

				Comment::destroy($request->comment_id);

				$comments_count = Comment::where("post_id", $post_id)->count();
	            $post = Post::findOrFail($post_id);
	            $post->comments_count = $comments_count;
	            $post->save();

		        $response = ["status" => "success", "response" => "comment deleted"];
		        return response(json_encode($response)) ->header('Content-Type', 'application/json');
		    } else {
		        $response = ["status" => "failed", "reason" => "unauthorized"];
		        return response(json_encode($response)) ->header('Content-Type', 'application/json');
		    }
		}
        $response = ["status" => "failed", "reason" => "unauthorized"];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
	}

	public function restComments(Request $request, $postid){
		$comments = Comment::with('user')->where('post_id', $postid)->orderBy('created_at', 'desc')->paginate(10);
		$results = [];
		foreach ($comments as $comment) {
			$item = ["text"=>nl2br(e($comment->comment)), "time"=>$comment->created_at->diffForHumans(), "id"=>$comment->id,
						 "user_id"=>$comment->user_id, "profile_pic"=>$comment->user->profile_pic,
						 "username"=>$comment->user->username, "raw_text"=>$comment->comment];
			array_push($results, $item);
		}
		$response = ["comments" => $results, "next"=>$comments->nextPageUrl()];

		if(Auth::check()) {
			$response['current_user_id'] = Auth::user()->id;
		}

		return response(json_encode($response)) ->header('Content-Type', 'application/json');
	}


	public function updateComment (Request $request){

		if (Auth::check()){
			$comment = Comment::findOrFail($request->comment_id);

			if(Auth::id() == $comment->user_id) {

				$validator = Validator::make($request->all(), [
	                'comment' => 'required|max:1000',
	            ]);

	            if ($validator->fails()) {
	                $response = ["status" => "failed", "reason" => $validator->errors()->all()];
	                return response(json_encode($response)) ->header('Content-Type', 'application/json');
	            }

				$comment->comment = $request->comment;
				$comment->save();

		        $response = ["status" => "success", "response" => "comment updated!"];
		        return response(json_encode($response)) ->header('Content-Type', 'application/json');
		    } else {
		        $response = ["status" => "failed", "reason" => ["unauthorized"]];
		        return response(json_encode($response)) ->header('Content-Type', 'application/json');
		    }
		}
        $response = ["status" => "failed", "reason" => ["unauthorized"]];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
	}
}