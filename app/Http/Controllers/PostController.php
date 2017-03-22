<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Javascript;
use Faker;
use App\Post;
class PostController extends Controller {
	public function newpost() {
		return view('newpost');
	}

    public function post($postId) {
        $post = Post::findOrFail($postId);
        return view('post', ['post'=> $post]);
    }

	public function createPost(Request $request) {
//		$validator = Validator::make($request->all(), [
//      		'title' => 'required|min:3|max:100',
//      		'text' => 'required',
//    	]);
//    	if ($validator->fails()) {
//      	return redirect('createpost') // redisplay the form
//             ->withErrors($validator) // to see the error messages
//             ->withInput(); // the previously entered input remains
//    	}

    	$post = new Post;
    	$post->user_id = Auth::user()->id;
    	$post->title = $request->title;
    	$post->text = $request->text;
        $post->likes_count = 0;
        $post->favourites_count = 0;
    	$post->save();

    	return redirect('/');
	}

	public function deletePost(Request $request) {
        Post::destroy($request->post_id);

        $response = ["delete" => "successful"];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }
}