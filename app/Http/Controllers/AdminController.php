<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use JavaScript;
use Faker;
use Storage;

use App\Report;

class AdminController extends Controller {

    public function index() {
        if(Auth::check() && Auth::user()->is_admin){
        	$reports = Report::orderBy("created_at", "desc")->get();
        	return view('admin', ["reports"=>$reports]);
        } else {
        	abort(401);
        }
    }

}