<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JavaScript;

class MessageController extends Controller {

    public function displayContacts(){
        if (false/*!Auth::check()*/){ // disabled for testing
            $response = ["status" => "unsuccessful", "error" => "user not logged in"];
            // change to login screen...
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }
        else {
            return view('newmessage');
        }
    }


}
