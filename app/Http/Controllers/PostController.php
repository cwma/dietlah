<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Javascript;
use Faker;
use App\Post;
class PostController extends Controller {
	public function newpost(Request $request){
		return view('newpost');
	}
	public function createPost(Request $request){
		$validator = Validator::make($request->all(), [ 
      		'title' => 'required|min:3|max:100',
      		'text' => 'required',
    	]);
    	if ($validator->fails()) {
      	return redirect('createpost') // redisplay the form
             ->withErrors($validator) // to see the error messages
             ->withInput(); // the previously entered input remains
    	}

    	$post = new Post;
    	$post->title = $request->title;
    	$post->text = $request->text;
    	$post->save();
    	return redirect('/');
	}
	public function post($postId, Request $request){
		$post = Post::findOrFail($postId);
		return view('post', ['post'=> $post]);
	}
}