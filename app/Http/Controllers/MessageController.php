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

        $this->middleware('isVerified');
        
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
        if($id != null && $id != Auth::id()) {

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

            $validator = Validator::make($request->all(), [
                'message-data'=>'required|max:1000',
                '_id'=>'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status'=>'failed', 'reason'=>$validator->errors()->all()], 200);
            }

            $target = User::find($request->_id);
            if(!$target) {
                return response()->json(['status'=>'failed', 'reason'=>["Message not sent. No such user"]], 200);
            }

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

    public function users() {
        $users = User::all()->pluck('username');
        return response()->json($users, 200);
    }

    public function getuserid(Request $req) {
        $user = User::where("username", $req->username)->get();

        $validator = Validator::make($req->all(), [
            'username' => 'required|min:3|max:20',
        ]);

        if ($validator->fails()) {
            $response = ["status" => "failed", "reason" => $validator->errors()->all()];
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }

        if($req->username == Auth::user()->username) {
            return response()->json(["status"=>"failed", "reason" =>["you cant start a conversation with yourself!"]], 200); 
        }

        if(sizeOf($user) == 1) {
            return response()->json(["status"=>"success", "userid" => $user[0]->id], 200);
        } else {
            return response()->json(["status"=>"failed", "reason" =>["no such user"]], 200); 
        }
    }
}
