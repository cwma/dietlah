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
			return redirect()->action('PostController@post', ['postId' => $request->postId]);
		}
		return redirect('/');
		
	}
	public function deleteComment (Request $request){
		Comment::destroy($request->commentId);
		return redirect()->action('PostController@post', ['postId' => $request->postId]);
	}
	public function comment (Request $request){
		$comment = Comment::findOrFail($request->commentId);
		return view('comment', ['comment' => $comment]);
	}
	public function updateComment (Request $request){
		$comment = Comment::findOrFail($request->commentId);
		$comment->comment = $request->comment;
		$comment->save();
		return redirect()->action('PostController@post', ['postId' => $request->postId]);
	}
}