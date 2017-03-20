<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< HEAD:app/Http/Controllers/HomePageController.php
use JavaScript;
use Faker;

class HomePageController extends Controller {

    public function index($page = 1) {
        JavaScript::put([
            "page" => 1,
            "restUrl" => "/rest/postfeed/",
        ]);
        return view('homepage');
=======
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
>>>>>>> 625e7def3d60b57b55a1165e53822d0d2f5c73fc:app/Http/Controllers/HomeController.php
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	$request->session()->put('username', Auth::user()->username);
        return view('home');
    }
}
