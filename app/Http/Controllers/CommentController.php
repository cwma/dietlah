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
			$comment = new Comment;
			$comment->comment = $request->comment;
			$comment->user_id = Auth::user()->id;
			$comment->post_id = $request->postId;
			$comment->save();
		}
		return redirect()->action('PostController@post', ['postId' => $request->postId]);
	}
	public function deleteComment ($postId, Request $request){
		Comment::destroy($request->commentId);
		return redirect()->action('PostController@post', ['postId' => $postId]);
	}
}