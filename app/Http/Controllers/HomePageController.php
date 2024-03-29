<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use JavaScript;
use Faker;
use Storage;

use App\Post;
use App\Like;
use App\Favourite;
use App\Comment;
use App\Tag;
use App\PostTag;

class HomePageController extends Controller {

    public function index(Request $request, $page = 1) {
        $tags = Tag::has('post_tags')->get()->pluck("tag_name");

        JavaScript::put([
            "tags" => $tags,
            "filter"=>["order"=>"new", "range"=>"all", "tags"=>[]]
        ]);

        return view('homepage');
    }

    public function indexFiltered($sort, $range, Request $request, $page = 1) {
        $tags = Tag::has('post_tags')->get()->pluck("tag_name");

        JavaScript::put([
            "tags" => $tags,
            "filter"=>["search"=>false, "order"=>$sort, "range"=>$range, "tags"=>$request->tags]
        ]);

        return view('homepage');
    }

    public function indexSearch(Request $request) {
        $tags = Tag::has('post_tags')->get()->pluck("tag_name");

        JavaScript::put([
            "tags" => $tags,
            "filter"=>["search"=>"true", "params"=>$request->params]
        ]);

        return view('homepage');
    }

    private function findTopTag($tags) {
        $top = 0;
        $top_tag = "";
        $top_id = "";
        foreach ($tags as $name => $vals) {
            $count = sizeOf($vals);
            if($count > $top) {
                $top = $count;
                $top_tag = $name;
                $top_id = $vals[0]->pivot->tag_id;
            }
        }
        return ["id"=>$top_id, "name"=>$top_tag];
    }

    private function pageQueryHandler($order, $range, $request) {
        $auth = Auth::check();
        if($auth) {
            $userid = Auth::user()->id;
        }

        $posts = Post::with('tags');

        // TODO: better pagination

        // handle tag filtering
        $tag_filters = $request->tags;
        if(sizeof($tag_filters)>0){
            foreach($tag_filters as $tag) {
                $posts = $posts->whereHas('tags', function ($query) use ($tag) {
                    $query->where("tag_id", $tag);
                });
            }
        }

        // handle range
        if($range == "today"){
            $posts = $posts->where('created_at', '>=', Carbon::now()->subDay());
        } else if ($range == "week") {
            $posts = $posts->where('created_at', '>=', Carbon::now()->subWeek());
        } else if ($range == "month") {
            $posts = $posts->where('created_at', '>=', Carbon::now()->subMonth());
        } 

        // handle $order
        if($order == "new") {
            $posts = $posts->orderBy('created_at', 'desc');
        } else if ($order == "popular") {
            $posts = $posts->orderBy('likes_count', 'desc');
        } else if ($order == "comments") {
            $posts = $posts->orderBy('comments_count', 'desc');
        } else if ($order == "relevance") {
            $posts = $posts->orderBy('created_at', 'desc'); // scrap relevance no time
        } else if ($order == "myposts") {
            $posts = $posts->where('user_id', $userid)->orderBy('created_at', 'desc');
        } else if ($order == "favourites") {
            $posts = $posts->whereHas('favourites', function($query) use ($userid) {
                $query->where("user_id", $userid);
            })->orderBy('created_at', 'desc');
        } else {
            $posts = Post::with('tags')->orderBy('created_at', 'desc');
        }

        return $posts->paginate(12);
    }

    public function getRecommendations($post){
        $userlikes = $post->likes->pluck('user_id');

        if (sizeof($userlikes)>0) {

            $likes = Like::where('post_id', '!=', $post->id);

            $likes = $likes->where(function($query) use ($userlikes) {
                foreach($userlikes as $user) {
                    $query->orWhere('user_id', $user);
                }
            });

            $recs = $likes->groupBy('post_id')->select(DB::raw('post_id, count(post_id) as aggregate'))->orderBy('aggregate', 'desc')->orderBy('post_id', 'desc')->take(3)->get();

            if(sizeof($recs) > 0) {

                $recposts = Post::query();
                foreach ($recs as $rec) {
                    $recposts = $recposts->orWhere('id', $rec->post_id);
                }

                return $recposts->get()->pluck('title', 'id');
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    # RESTFUL end points

    public function restPostFeed($order, $range, Request $request) {
        $auth = Auth::check();
        if($auth) {
            $userid = Auth::user()->id;
        }

        $posts = self::pageQueryHandler($order, $range, $request);

        $results = [];
        foreach ($posts as $post) {
            $item = ["title"=>$post->title, "summary"=>nl2br(e($post->summary)), "time"=>$post->created_at->diffForHumans(), "id"=>$post->id,
                         "user_id"=>$post->user_id, "profile_pic"=>$post->user->profile_pic,
                         "username"=>$post->user->username, "likes"=>$post->likes_count, 
                         "comments"=>$post->comments_count];

            if($post->image != "") {
                $item['image'] = Storage::url($post->image);
            } else {
                $item['image'] = "";
            }

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

            // the slow way for now... TODO: Optimize!
            $tags = $post->tags->groupby('tag_name')->all();
            if(sizeof($tags) > 0) {
                $result = self::findTopTag($tags);
                $item['tagid'] = $result['id'];
                $item['tag'] = $result['name'];
            }

            array_push($results, $item);
        }
        $response = ["posts" => $results, "next"=>$posts->nextPageUrl()];

        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

    public function restSearch(Request $request) {
        $auth = Auth::check();
        if($auth) {
            $userid = Auth::user()->id;
        }

        if($request->search != "") {
            $posts = Post::search($request->search)->paginate(12);
        } else {
            $posts = Post::with('tags')->orderBy('created_at', 'desc')->paginate(12);
        }
        $results = [];
        foreach ($posts as $post) {
            $item = ["title"=>$post->title, "summary"=>nl2br(e($post->summary)), "time"=>$post->created_at->diffForHumans(), "id"=>$post->id,
                         "user_id"=>$post->user_id, "profile_pic"=>$post->user->profile_pic,
                         "username"=>$post->user->username, "likes"=>$post->likes_count, 
                         "comments"=>$post->comments_count];

            if($post->image != "") {
                $item['image'] = Storage::url($post->image);
            } else {
                $item['image'] = "";
            }

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

            // the slow way for now... TODO: Optimize!
            $tags = $post->tags->groupby('tag_name')->all();
            if(sizeof($tags) > 0) {
                $result = self::findTopTag($tags);
                $item['tagid'] = $result['id'];
                $item['tag'] = $result['name'];
            }

            array_push($results, $item);
        }
        $response = ["posts" => $results, "next"=>$posts->nextPageUrl()];

        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

    public function restPost($postId) {
        $post = Post::with('User')->with('tags')->with('likes')->with('favourites')->findOrFail($postId);
        $result = ["id" => $post->id, "title"=>$post->title, "summary"=>$post->summary, "text"=>nl2br(e($post->text)),
                   "loc"=>$post->location, "likes_count"=>$post->likes_count, "comments_count"=>$post->comments_count, "user_id"=>$post->user_id,
                   "created_at"=>$post->created_at->diffForHumans(), "username"=>$post->user->username, "profile_pic"=>$post->user->profile_pic];

        if($post->image != "") {
            $result['image'] = Storage::url($post->image);
        } else {
            $result['image'] = "";
        }

        // handle tags
        // TODO: sort by tag count some how
        $result['tags'] = collect(DB::select('SELECT tag_name, count(post_tags.tag_id) as aggregate, tag_id from post_tags, tags
                                where post_tags.tag_id = tags.id and post_id = ? group by post_tags.tag_id 
                                ORDER BY aggregate DESC, tags.tag_name ASC', [$postId]));
        $result["tags_count"] = sizeOf($result["tags"]);


        $result['root'] = url('/');

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

            $result['current_user_id'] = Auth::user()->id;

            $userTags = PostTag::where('user_id', $userid)->where('post_id', $postId)->with('tag')->get()->pluck('tag.tag_name');
            $result['user_tags'] = $userTags;
        }

        $result['recs'] = self::getRecommendations($post);

        return response(json_encode($result)) ->header('Content-Type', 'application/json');
    }
}
