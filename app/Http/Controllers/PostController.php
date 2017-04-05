<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Post;
use App\Like;
use App\Favourite;
use App\Comment;
use App\Tag;
use App\PostTag;
use JavaScript;
use Storage;
use Image;



class PostController extends Controller {

    // Original PHP code by Chirp Internet: www.chirp.com.au
    // Please acknowledge use of this code by including this header.

    public function myTruncate($string, $limit, $break=".", $pad="...")
    {
      // return with no change if string is shorter than $limit
      if(strlen($string) <= $limit) return $string;

      // is $break present between $limit and the end of the string?
      if(false !== ($breakpoint = strpos($string, $break, $limit))) {
        if($breakpoint < strlen($string) - 1) {
          $string = substr($string, 0, $breakpoint) . $pad;
        }
      }

      return $string;
    }

    public function post($postId) {
        $post = Post::with('User')->with('tags')->with('likes')->with('favourites')->findOrFail($postId);
        $result = ["id" => $post->id,"title"=>$post->title, "summary"=>$post->summary, "text"=>nl2br(e($post->text)),
            "location"=>$post->location, "likes_count"=>$post->likes_count, "comments_count"=>$post->comments_count, "user_id"=>$post->user_id,
            "created_at"=>$post->created_at->diffForHumans(), "username"=>$post->user->username, "profile_pic"=>$post->user->profile_pic];

        // handle tags
        // TODO: sort by tag count some how
        $result["tags"] = $post->tags->pluck('tag_name');
        $result["tags_count"] = $post->tags->count();


        // handle image
        if($post->image != "") {
            $result['image'] = Storage::url($post->image);
        } else {
            $result['image'] = "";
        }

        // for user tags and auto complete
        if(Auth::check()) {
            $userid = Auth::user()->id;
            $userTags = PostTag::where('user_id', $userid)->where('post_id', $postId)->with('tag')->get()->pluck('tag.tag_name');
            $tags = Tag::has('post_tags')->get()->pluck("tag_name");
            JavaScript::put([
                "tags" => $tags,
                "userTags" => $userTags,
            ]);
        }

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
        }

        // for comments infinite scroll
        JavaScript::put([
            "postId" => $postId,
            "loc" => $post->location
        ]);

        return view('post', ['post'=> $result]);
    }

	public function newpost() {
        if (!Auth::check()) {
            return redirect()->route('login');
        } 

        // for autocomplete of tags
        // make sure you run composer install
        // this facade just helps put the variables into the javascript namespace "dietlah"
        // can access tags by calling dietlah.tags in browser
        JavaScript::put([
            "tags" => Tag::has('post_tags')->get()->pluck("tag_name")
        ]);
		return view('newpost');
	}

	public function editpost($postId) {
        $post = Post::with('User')->findOrFail($postId);

        if (!Auth::check()) {
            return redirect()->route('login');
        } else if (Auth::id() != $post->user_id && !Auth::user()->is_admin) {
            abort(403, 'you are not authorized to edit this post.');
        }

        $result = ["id"=>$postId, "title"=>$post->title, "summary"=>$post->summary, "text"=>$post->text, "location"=>$post->location];

        if($post->image != "") {
            $result['image'] = Storage::url($post->image);
        } else {
            $result['image'] = "";
        }

        // get only tags for this post added by user
        $user_tags = PostTag::with('tag')
            ->where(['user_id' => Auth::id(), 'post_id' => $postId])->get()
            ->pluck('tag')->pluck('tag_name');

        // for autocomplete of tags & to prepopulate tags
        JavaScript::put([
            "user_tags" => $user_tags,
            "tags" => Tag::has('post_tags')->get()->pluck("tag_name"),
            "loc" => $post->location
        ]);

        return view('editpost', ['post' => $result]);

    }

	public function createPost(Request $request) {
        if (!Auth::check()) {
            // only logged in user can create post
            $response = ["status" => "failed", "reason" => "you need to be logged in."];
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'text' => 'required|max:10000',
            'image' => 'max:8192',
            'location' => 'max:50'
        ]);

        if ($validator->fails()) {
            $response = ["status" => "failed", "reason" => $validator->errors()->all()];
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }

        if(sizeOf($request->tags) > 0) {
            foreach ($request->tags as $tag) {

                if(strlen($tag) > 20 || strlen($tag) < 3) {
                    $response = ["status" => "failed", "reason" => ["tags cannot be less than 3 characters or more than 20 characters each"]];
                    return response(json_encode($response)) ->header('Content-Type', 'application/json');
                }
            }
        }

        $returnid;
        DB::transaction(function () use (&$request, &$returnid) {

            $user_id = Auth::id();

        	$post = new Post;
        	$post->title = $request->title;
        	$post->text = $request->text;
        	$post->location = $request->location;
            $post->summary = self::myTruncate($request->text, 80);
            $post->likes_count = 0;
            $post->comments_count = 0;
            $post->user_id = $user_id;

            // store image
            try {
                if($request->hasFile('image')) {
                    $path = $request->file('image')->store('public/images/postimages');
                    $image = Image::make(storage_path().'/app/'.$path);

                    if ($image->height() > 1080) {
                        $image->resize(null, 1080, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                    if ($image->width() > 1920) {
                        $image->resize(1920, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                    $image->save(storage_path().'/app/'.$path);

                    $post->image = $path;
                }
            } catch (\Intervention\Image\Exception\NotReadableException $e) {
                 $response = ["status" => "failed", "reason" => ["The image file you provided seems to be corrupted. Please try another file"]];
                 return response(json_encode($response)) ->header('Content-Type', 'application/json');
            }

        	$post->save();
        	$post_id = $post->id;

        	// add tags if there are tags
            $tags = $request->has('tags') ? $request->tags : array();
            foreach ($tags as $tagname) {
                $tag = Tag::firstOrCreate(["tag_name" => $tagname]);
                $post_tag = new PostTag;
                $post_tag->user_id = $user_id;
                $post_tag->post_id = $post_id;
                $post_tag->tag_id = $tag->id;
                $post_tag->save();
            }

            $returnid = $post->id;
        });

        $response = ["status" => "successful", "post_id" => $returnid];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
	}

	public function updatePost(Request $request) {
        $post = Post::findOrFail($request->post_id);
        if (!Auth::check() || Auth::id() != $post->user_id) {
            // user can only delete his own post
            if(!Auth::user()->is_admin) {
                $response = ["status" => "failed", "reason" => ["unauthorized"]];
                return response(json_encode($response)) ->header('Content-Type', 'application/json');
            }
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'text' => 'required|max:10000',
            'image' => 'max:8192',
            'location' => 'max:50'
        ]);

        if ($validator->fails()) {
            $response = ["status" => "failed", "reason" => $validator->errors()->all()];
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }

        if(sizeOf($request->tags) > 0) {
            foreach ($request->tags as $tag) {

                if(strlen($tag) > 20 || strlen($tag) < 3) {
                    $response = ["status" => "failed", "reason" => ["tags cannot be less than 3 characters or more than 20 characters each"]];
                    return response(json_encode($response)) ->header('Content-Type', 'application/json');
                }
            }
        }

        $returnid;
        DB::transaction(function () use (&$request, &$returnid) {
            $post_id = $request->post_id;
            $post = Post::findOrFail($post_id);

            $post->title = $request->title;
            $post->text = $request->text;
        	$post->location = $request->location;
            $post->summary = self::myTruncate($request->text, 80);

            $old_image = $post->image;
            // store image
            if($request->hasFile('image')) {

                $path = $request->file('image')->store('public/images/postimages');
                $image = Image::make(storage_path().'/app/'.$path);

                if ($image->height() > 1080) {
                    $image->resize(null, 1080, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
                if ($image->width() > 1920) {
                    $image->resize(1920, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
                $image->save(storage_path().'/app/'.$path);

                $post->image = $path;
            } elseif ($request->should_delete_image) {
                $post->image = null;
            }

            $post->save();
            $this->updateTags(Auth::id(), $post_id, $request->tags);

            if($old_image != null && $old_image != $post->image){
                Storage::delete($old_image);
            }

            $returnid = $post_id;
        });


        $response = ["status" => "successful", "post_id" => $returnid];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

	public function deletePost(Request $request) {
        $post = Post::findOrFail($request->post_id);
        if (!Auth::check() || Auth::id() != $post->user_id) {
            // user can only delete his own post
            if(!Auth::user()->is_admin) {
                $response = ["status" => "failed", "reason" => "unauthorized"];
                return response(json_encode($response)) ->header('Content-Type', 'application/json');
            }
        }

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

    public function favouritePost(Request $request) {
        if(Auth::check()) {
            $postid = $request->postId;
            $userid = Auth::user()->id;
            if ($request->favourited === "no") {
                $fav_post = Favourite::firstOrNew(["user_id" => $userid, "post_id" => $postid]);
                if(!$fav_post->exists) {
                    $fav_post->save();
                }

                $response = ["status" => "success", "response" => "You added this post to favourites!"];
            } else {
                $fav_post = Favourite::where("user_id", $userid)->where("post_id", $postid)->delete();

                $response = ["status" => "success", "response" => "You removed this post from favourites!"];
            }
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }
        $response = ["status" => "failed", "reason" => "unauthorized"];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

    public function updatePostTags(Request $request) {
        if(Auth::check()) {
            $user_id = Auth::user()->id;
            $post_id = $request->post_id;
            $tags = $request->tags;

            if(sizeOf($tags) > 0) {
                foreach ($tags as $tag) {

                    if(strlen($tag) > 20 || strlen($tag) < 3) {
                        $response = ["status" => "failed", "reason" => ["tags cannot be less than 3 characters or more than 20 characters each"]];
                        return response(json_encode($response)) ->header('Content-Type', 'application/json');
                    }
                }
            }

            $this->updateTags($user_id, $post_id, $tags);

            $response = ["status" => "success", "response" => "tags saved!"];
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }

        $response = ["status" => "failed", "reason" => "unauthorized"];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

    public function removeTag(Request $request) {
        if (!Auth::check() || !Auth::user()->is_admin) {
            // if user is not admin, disallow delete of tags
            $response = ["status" => "failed", "reason" => "unauthorized"];
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }

        Tag::where("tag_name", $request->tag_name)->delete();
    }

    // update the user's tags for a post
    private function updateTags($user_id, $post_id, $tags) {

        // clear existing tags from user
        PostTag::where("user_id", $user_id)->where("post_id", $post_id)->delete();

        // if user deleted all existing tags, return because we don't need to add tags
        if (empty($tags)) {
            return;
        }

        // else repopulate tags from user
        foreach ($tags as $tag) {

            $tag = Tag::firstOrCreate(["tag_name" => $tag]);
            $post_tag = new PostTag;
            $post_tag->user_id = $user_id;
            $post_tag->post_id = $post_id;
            $post_tag->tag_id = $tag->id;
            $post_tag->save();
        }
    }
}