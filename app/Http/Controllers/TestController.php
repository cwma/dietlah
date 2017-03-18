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
        $response = ["test" => "Received comment [".$request->comment."] for post id ".$request->postId.". successfully"];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }
}
