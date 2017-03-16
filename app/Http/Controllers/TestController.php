<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Javascript;
use Faker;

class TestController extends Controller {

    public function index($page = 1) {
        $response = ["test": "successful!"];
        return response(json_encode($response)) ->header('Content-Type', 'application/json'););
    }
}
