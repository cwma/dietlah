<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Post;
use App\Like;
use App\Favourite;
use App\Comment;
use App\Tag;
use App\PostTag;
use JavaScript;

class PostController extends Controller {
	public function newpost() {
        if (!Auth::check()) {
            $response = ["status" => "unsuccessful", "error" => "user not logged in"];
            // change to login screen...
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }

        // for autocomplete of tags
        // make sure you run composer install
        // this facade just helps put the variables into the javascript namespace "dietlah"
        // can access tags by calling dietlah.tags in browser
        JavaScript::put([
            "tags" => Tag::all()->pluck('tag_name')
        ]);
		return view('newpost');
	}

    public function post($postId) {
        $post = Post::with('User')->with('tags')->with('likes')->with('favourites')->with('post_tags')->findOrFail($postId);
        $result = ["id" => $post->id, "image"=>$post->image, "title"=>$post->title, "summary"=>$post->summary, "text"=>$post->text,
                   "location"=>$post->location, "likes_count"=>$post->likes_count, "comments_count"=>$post->comments_count, "user_id"=>$post->user_id,
                   "created_at"=>$post->created_at, "username"=>$post->user->username, "profile_pic"=>$post->user->profile_pic];

        // handle tags
        // TODO: sort by tag count some how
        $result["tags"] = $post->tags->pluck('tag_name');
        $result["tags_count"] = $post->tags->count();

        // for user tags and auto complete
        if(Auth::check()) {
            $userid = Auth::user()->id;
            $userTags = PostTag::where('user_id', $userid)->where('post_id', $postId)->with('tag')->get()->pluck('tag.tag_name');
            $tags = Tag::all()->pluck("tag_name");
            JavaScript::put([
                "tags" => $tags,
                "userTags" => $userTags,
                "postId" => $postId
            ]);
        } 

        // handle user liked and favourite
        if(Auth::check()) {
            $userid = Auth::user()->id;
            $likers = $post->likes->pluck('id', 'user_id')->all();
            if(array_key_exists("41", $likers)) {
                $result['liked'] = true;
            } else {
                $result['liked'] = false;
            }
            $favs = $post->favourites->pluck('id', 'user_id');
            if(array_key_exists($userid, $favs)) {
                $result['favourited'] = true;
            } else {
                $result['favourited'] = false;
            }
        }

        // for comments infinite scroll
        JavaScript::put([
            "postId" => $postId
        ]);

        return view('post', ['post'=> $result]);
    }

	public function createPost(Request $request) {
//		$validator = Validator::make($request->all(), [
//      		'title' => 'required|min:3|max:100',
//      		'text' => 'required',
//    	]);
//    	if ($validator->fails()) {
//      	return redirect('createpost') // redisplay the form
//             ->withErrors($validator) // to see the error messages
//             ->withInput(); // the previously entered input remains
//    	}

        $user_id = Auth::id();

        // TODO yy check where the images shld be stored
    	$post = new Post;
//    	$post->image = $request->file('portrait')->store('public/images/postimages');
    	$post->title = $request->title;
    	$post->text = $request->text;
//    	$post->location = $request->location;
        $post->summary = 'To be updated';
        $post->likes_count = 0;
        $post->comments_count = 0;
        $post->user_id = $user_id;
    	$post->save();
    	$post_id = $post->id;

        $tags = $request->tags;
        // TODO yy check how the tags are passed
        //  assuming tagnames are passed as array
        foreach ($tags as $tagname) {
            $tag = Tag::firstOrCreate(["tag_name" => $tagname]);
            $post_tag = new PostTag;
            $post_tag->user_id = $user_id;
            $post_tag->post_id = $post_id;
            $post_tag->tag_id = $tag->id;
            $post_tag->save();
        }

        $response = ["status" => "successful", "post_id" => $post->id];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
	}

	public function updatePost(Request $request) {
        // TODO yy add image and location when UI ready
        $post_id = $request->post_id;
        $post = Post::findOrFail($post_id);
        // TODO yy delete the old image?
//    	$post->image = $request->file('portrait')->store('public/images/postimages');
        $post->title = $request->title;
        $post->text = $request->text;
//    	$post->location = $request->location;
        $post->save();

        $response = ["status" => "successful", "post_id" => $post_id];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

	public function deletePost(Request $request) {
        $post = Post::findOrFail($request->post_id);
        $comments = $post->comments;
        foreach ($comments as $comment){
            Comment::destroy($comment->id);
        }
        Post::destroy($request->post_id);

        $response = ["status" => "successful"];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

    public function likePost(Request $request) {
        if(Auth::check()) {
            $postid = $request->postId;
            $userid = Auth::user()->id;
            if ($request->liked === "no") {
                $like_post = Like::firstOrNew(["user_id" => $userid, "post_id" => $postid]);
                if(!$like_post->exists) {
                    $like_post->save();
                }
                $likes = Like::where("post_id", $postid)->count();
                $post = Post::findOrFail($postid);
                $post->likes_count = $likes;
                $post->save();

                $response = ["status" => "success", "response" => "You liked this post!", "likes" => $likes];
            } else {
                $like_post = Like::where("user_id", $userid)->where("post_id", $postid)->delete();
                $likes = Like::where("post_id", $postid)->count();
                $post = Post::findOrFail($postid);
                $post->likes_count = $likes;
                $post->save();
                $response = ["status" => "success", "response" => "You unliked this post!", "likes" => $likes];
            }
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }
        $response = ["status" => "failed", "reason" => "unauthorized"];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

    public function updatePostTags(Request $request) {
        $user_id = Auth::user()->id;
        $post_id = $request->post_id;
        $tags = $request->tags;

        // assuming tags are passed as array
        // what about if the user removes tags?
        // assuming array tags is
        // eg. [[tag_name => tagA, action => remove], [tag_name => tagB, action => add]]
        foreach ($tags as $tagAction) {
            $tag_name = $tagAction["tag_name"];
            if ($tagAction["action"] === "remove") {
                $tag_id = Tag::where("tag_name", $tag_name)->firstOrFail()->id;
                $post_tag = PostTag::where(["user_id" => $user_id, "post_id" => $post_id, "tag_id" => $tag_id]);
                $post_tag->delete();
            } else if ($tagAction["action"] === "add"){
                $tag = Tag::firstOrCreate(["tag_name" => $tag_name]);
                $post_tag = new PostTag;
                $post_tag->user_id = $user_id;
                $post_tag->post_id = $post_id;
                $post_tag->tag_id = $tag->id;
                $post_tag->save();
            }
        }
    }
}