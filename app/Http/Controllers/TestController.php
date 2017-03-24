<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Javascript;
use Faker;

class TestController extends Controller {

    public function test($page = 1) {
        $response = ["test" => "successful!"];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
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
