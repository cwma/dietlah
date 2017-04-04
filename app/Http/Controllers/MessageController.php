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
Use View;

class MessageController extends Controller {

    protected $authUser;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Talk::setAuthUserId(Auth::user()->id); return $next($request);
        });
        
        if (Auth::check())
        {
            $this->middleware('auth');
            Talk::setAuthUserId(Auth::user()->id);

            View::composer('partials.peoplelist', function($view) {
                $threads = Talk::threads();
                $view->with(compact('threads'));
            });
        }
        else
        {
            $response = ["status" => "unsuccessful", "error" => "user not logged in"];
            // change to login screen...
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }
    }

    public function displayContacts(){
        if (!Auth::check()){ // disabled for testing
            $response = ["status" => "unsuccessful", "error" => "user not logged in"];
            // change to login screen...
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }
        else {
            $users = User::all();
            return view('newmessage', compact('users'));
        }
    }

    public function chatHistory($id = null)
    {
        if($id != null) {
            $conversations = Talk::getMessagesByUserId($id);
            $user = '';
            $messages = [];
            if(!$conversations) {
                $user = User::find($id);
            } else {
                $user = $conversations->withUser;
                $messages = $conversations->messages;
            }

            Talk::setAuthUserId(Auth::user()->id);

            View::composer('partials.peoplelist', function($view) {
                $threads = Talk::threads();
                $view->with(compact('threads'));
            });

            return view('messages.conversations', compact('messages', 'user', 'threads'));
        } else {
            Talk::setAuthUserId(Auth::user()->id);

            View::composer('partials.peoplelist', function($view) {
                $threads = Talk::threads();
                $view->with(compact('threads'));
            }); 
            $messages =[];
            return view('messages.conversations', compact('messages', 'threads'));
        }
    }

    public function ajaxSendMessage(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'message-data'=>'required',
                '_id'=>'required'
            ];

            $this->validate($request, $rules);

            $body = $request->input('message-data');
            $userId = $request->input('_id');

            if ($message = Talk::sendMessageByUserId($userId, $body)) {
                $html = view('ajax.newMessageHtml', compact('message'))->render();
                return response()->json(['status'=>'success', 'html'=>$html], 200);
            }
        }
    }

    public function ajaxDeleteMessage(Request $request, $id)
    {
        if ($request->ajax()) {
            if(Talk::deleteMessage($id)) {
                return response()->json(['status'=>'success'], 200);
            }

            return response()->json(['status'=>'errors', 'msg'=>'something went wrong'], 401);
        }
    }

    public function tests()
    {
        dd(Talk::channel());
    }
}
