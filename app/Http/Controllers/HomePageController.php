<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use JavaScript;
use Faker;

use App\Post;
use App\Like;
use App\Favourite;
use App\Comment;
use App\Tag;
use App\PostTag;

class HomePageController extends Controller {

    public function index(Request $request, $page = 1) {
        $tags = Tag::all()->pluck("tag_name");

        JavaScript::put([
            "tags" => $tags
        ]);
	$request->session()->put('username', 'temp');
        return view('homepage');
    }



    # RESTFUL end points

    public function restPostFeed($order, $range, Request $request) {
        $auth = Auth::check();
        if($auth) {
            $userid = Auth::user()->id;
        }

        # TODO: range, relevance
        if($order == "new") {
            $posts = Post::with('tags')->orderBy('created_at', 'desc')->paginate(12);
        } else if ($order == "popular") {
            $posts = Post::with('tags')->orderBy('likes_count', 'desc')->paginate(12);
        } else if ($order == "favourites") {
            $posts = Post::with('tags','favourites')->whereHas('favourites', function($query) use ($userid) {
                $query->where("user_id", $userid);
            })->orderBy('created_at', 'desc')->paginate(12);
        } else if ($order == "comments") {
            $posts = Post::with('tags')->orderBy('comments_count', 'desc')->paginate(12);
        } else if ($order == "relevance") {
            $posts = Post::with('tags')->orderBy('created_at', 'desc')->paginate(12);
        } else if ($order == "myposts") {
            $posts = Post::with('tags')->where('user_id', $userid)->orderBy('created_at', 'desc')->paginate(12);
        } else {
            $posts = Post::with('tags')->orderBy('created_at', 'desc')->paginate(12);
        }

        $auth = Auth::check();
        if($auth) {
            $userid = Auth::user()->id;
        }

        $results = [];
        foreach ($posts as $post) {
            $item = ["title"=>$post->title, "summary"=>$post->summary, "time"=>$post->created_at, "id"=>$post->id,
                         "user_id"=>$post->user_id, "profile_pic"=>$post->user->profile_pic,
                         "username"=>$post->user->username, "likes"=>$post->likes_count, 
                         "comments"=>$post->comments_count, "image"=>$post->image];

            if ($auth) {
                $likers = $post->likes->pluck('id', 'user_id')->all();
                if(array_key_exists($userid, $likers)) {
                    $item['liked'] = true;
                } else {
                    $item['liked'] = false;
                }
                $favs = $post->favourites->pluck('id', 'user_id')->all();
                if(array_key_exists($userid, $favs)) {
                    $item['favourited'] = true;
                } else {
                    $item['favourited'] = false;
                }
            }

            // tag, sort and get top tag later
            $tags = $post->tags->pluck('tag_name')->all();
            if(sizeof($tags) > 0) {
                $item["tag"] = $tags[0];
            }

            array_push($results, $item);
        }
        $response = ["posts" => $results, "next"=>$posts->nextPageUrl()];

        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

    public function restPost($postId) {
        $post = Post::with('User')->with('tags')->with('likes')->with('favourites')->with('post_tags')->findOrFail($postId);
        $result = ["id" => $post->id, "image"=>$post->image, "title"=>$post->title, "summary"=>$post->summary, "text"=>nl2br(e($post->text)),
                   "location"=>$post->location, "likes_count"=>$post->likes_count, "comments_count"=>$post->comments_count, "user_id"=>$post->user_id,
                   "created_at"=>$post->created_at, "username"=>$post->user->username, "profile_pic"=>$post->user->profile_pic];

        // handle tags
        // TODO: sort by tag count some how
        $result["tags"] = $post->tags->pluck('tag_name');
        $result["tags_count"] = $post->tags->count();


        // handle user liked and favourite
        if(Auth::check()) {
            $userid = Auth::user()->id;
            $likers = $post->likes->pluck('id', 'user_id')->all();
            if(array_key_exists($userid, $likers)) {
                $result['liked'] = true;
            } else {
                $result['liked'] = false;
            }
            $favs = $post->favourites->pluck('id', 'user_id')->all();
            if(array_key_exists($userid, $favs)) {
                $result['favourited'] = true;
            } else {
                $result['favourited'] = false;
            }

            // tags, sort later
            $userTags = PostTag::where('user_id', $userid)->where('post_id', $postId)->with('tag')->get()->pluck('tag.tag_name');
            $result['user_tags'] = $userTags;

        }

        return response(json_encode($result)) ->header('Content-Type', 'application/json');
    }
}
