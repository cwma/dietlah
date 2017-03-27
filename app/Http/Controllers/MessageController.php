<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nahid\Talk\Facades\Talk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Post;
use App\Like;
use App\Favourite;
use App\Comment;
use App\Tag;
use App\PostTag;
use App\User;
use App\Message;
use App\Conversation;
use JavaScript;

class MessageController extends Controller {

    public function displayContacts(){
        if (false/*!Auth::check()*/){ // disabled for testing
            $response = ["status" => "unsuccessful", "error" => "user not logged in"];
            // change to login screen...
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }
        else {
            $users = User::all();
            return view('newmessage')->with('users', $users);
        }
    }

    public function displayChat(){
        if (false/*!Auth::check()*/){ // disabled for testing
            $response = ["status" => "unsuccessful", "error" => "user not logged in"];
            // change to login screen...
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }
        else {
            //get conversation between users
            $uid = 1;
            $conversations = Talk::getMessagesByUserId($uid);
            $user = '';
            $messages = [];
            if(!$conversations) {
                $user = User::find($uid);
            } else {
                $user = $conversations->withUser;
                $messages = $conversations->messages;
            }

            return view('messages', compact('messages', $messages));
        }
    }


}
