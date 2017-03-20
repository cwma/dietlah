<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {

    public function index(Request $request, $page = 1) {
        JavaScript::put([
            "page" => 1,
            "restUrl" => "/rest/postfeed/",
        ]);
	$request->session()->put('username', 'temp');
        return view('home');
    }
}
