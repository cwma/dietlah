<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Post;
class PostController extends Controller {
	public function newpost() {
        if (!Auth::check()) {
            $response = ["status" => "unsuccessful", "error" => "user not logged in"];
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }

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

        // TODO yy add image and location when UI ready
    	$post = new Post;
//    	$post->image = $request->file('portrait')->store('public/images/postimages');
    	$post->title = $request->title;
    	$post->text = $request->text;
//    	$post->location = $request->location;
        $post->summary = 'To be update';
        $post->likes_count = 0;
        $post->favourites_count = 0;
        $post->user_id = Auth::user()->id;
    	$post->save();

        $response = ["status" => "successful", "post_id" => $post->id];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
	}

	public function updatePost(Request $request) {
        // TODO yy add image and location when UI ready
        $post_id = $request->post_id;
        $post = Post::findOrFail($post_id);
        // TODO yy delete the old image?
//    	$post->image = $request->file('portrait')->store('public/images/postimages');
        $post->title = $request->title;
        $post->text = $request->text;
//    	$post->location = $request->location;
        $post->save();

        $response = ["status" => "successful", "post_id" => $post_id];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

	public function deletePost(Request $request) {
        Post::destroy($request->post_id);

        $response = ["status" => "successful"];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }
}