<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Javascript;
use Faker;
use App\Post;

class TestController extends Controller {

    public function test($page = 1) {
        $response = ["test" => "successful!"];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

    public function testPost($id) {
        $post = Post::with('User')->with('tags')->with('likes')->with('favourites')->with('post_tags')->findOrFail($id);
        $result = ["id" => $post->id, "image"=>$post->image, "title"=>$post->title, "summary"=>$post->summary, "text"=>$post->text,
                   "location"=>$post->location, "likes_count"=>$post->likes_count, "comments_count"=>$post->comments_count, "user_id"=>$post->user_id,
                   "created_at"=>$post->created_at, "username"=>$post->user->username, "profile_pic"=>$post->user->profile_pic];

        // handle tags
        // TODO: sort by tag count some how
        $result["tags"] = $post->tags->pluck('tag_name');
        $result["tags_count"] = $post->tags->count();

        // handle user liked and favourite
        if(Auth::check()) {
            $userid = Auth::user()->id;
            $likers = $post->likes->pluck('id', 'user_id');
            if(array_key_exists($userid, $likers)) {
                $result['liked'] = true;
            } else {
                $result['liked'] = false;
            }
            $favs = $post->favourites->pluck('id', 'user_id');
            if(array_key_exists($userid, $favs)) {
                $result['favourited'] = true;
            } else {
                $result['favourited'] = false;
            }
        } 

        return response(json_encode($post)) ->header('Content-Type', 'application/json');
    }

    public function testCreateComment(Request $request) {
        $response = ["test" => "Received comment [".$request->comment."] for post id ".$request->post_id.". successfully"];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

    public function testLike(Request $request) {
    	if ($request->liked === "false") {
    		$response = ["test" => "Received Like for postId ".$request->postId.".", "likes" => 50];
    	} else {
    		$response = ["test" => "Received unlike for postId ".$request->postId.".", "likes" => 49];
    	}
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

    public function testFavourite(Request $request) {
    	if ($request->favourited === "false") {
    		$response = ["test" => "Received Favourite for postId ".$request->postId."."];
    	} else {
    		$response = ["test" => "Received unFavourite for postId ".$request->postId."."];
    	}
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }
}
