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

use App\Post;
use App\Like;
use App\Favourite;
use App\Comment;
use App\Tag;
use App\User;
use App\PostTag;

use Image;

use \Identicon\Identicon;

class ProfileController extends Controller {

    private function findTopTag($tags) {
        $top = 0;
        $top_tag = "";
        foreach ($tags as $name => $vals) {
            $count = sizeOf($vals);
            if($count > $top) {
                $top = $count;
                $top_tag = $name;
            }
        }
        return $top_tag;
    }

    public function myProfile() {
    	if(Auth::check()) {
	    	$user = Auth::user();
	    	$tags = Tag::has('post_tags')->get()->pluck("tag_name");

	        JavaScript::put([
	            "tags" => $tags,
	            "profileid" => $user->id
	        ]);

	    	return view('profile', ['user'=> $user]);
	    } else {
	    	return redirect()->route('login');
	    }
    }

	public function viewProfile($profileid, Request $request){
		$user = User::findOrFail($profileid);

        $tags = Tag::has('post_tags')->get()->pluck("tag_name");

        JavaScript::put([
            "tags" => $tags,
            "profileid" => $user->id
        ]);

		return view('profile', ['user'=> $user]);
	}

	public function editProfile(){
    	if(Auth::check()) {
	    	$user = Auth::user();

	    	return view('editprofile', ['user'=> $user]);
	    } else {
	    	return redirect()->route('login');
	    }
	}

	public function updateProfile(Request $request){
    	if(!Auth::check()) {
	    	$response = ["status"=>"failed", "reason"=>["unauthorized"]];
	    	return response(json_encode($response)) ->header('Content-Type', 'application/json');
    	}

		$validator = Validator::make($request->all(), [
            'bio' => 'required|max:5000',
            'image' => 'max:8192'
        ]);

        if ($validator->fails()) {
            $response = ["status" => "failed", "reason" => $validator->errors()->all()];
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }

    	$path;

    	try {

    		DB::transaction(function () use (&$request, &$path) {
		    	$user = Auth::user();

		        if($request->hasFile('image')) {
		            $path = $request->file('image')->store('public/images/profile');
		            $image = Image::make(storage_path().'/app/'.$path);

		            if ($image->height() > 640) {
			            $image->resize(null, 640, function ($constraint) {
						    $constraint->aspectRatio();
						});
					}
					if ($image->width() > 640) {
						$image->resize(640, null, function ($constraint) {
						    $constraint->aspectRatio();
						});
					}
		            $image->save(storage_path().'/app/'.$path);

		            $user->profile_pic = Storage::url($path);
		        } elseif ($request->should_delete_image) {
		        	$identicon = new Identicon();
		            $user->profile_pic = $identicon->getImageDataUri($user->username);
		        }

		    	$user->bio = $request->bio;
		    	$user->save();
		    });


        } catch (\Intervention\Image\Exception\NotReadableException $e) {
            if($path != null) {
                Storage::delete($path);
            }
            $response = ["status" => "failed", "reason" => ["The image file you provided seems to be corrupted. Please try another file"]];
            return response(json_encode($response)) ->header('Content-Type', 'application/json');
        }

        $response = ["status" => "successful"];
        return response(json_encode($response)) ->header('Content-Type', 'application/json');

    }

	public function restProfile($profileid, Request $request) {

		$auth = Auth::check();
        if($auth) {
            $userid = Auth::user()->id;
        }

        $posts = Post::with('tags')->where('user_id', $profileid)->orderBy('created_at', 'desc')->paginate(12);

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
                $item['tag'] = self::findTopTag($tags);
            }

            array_push($results, $item);
        }
        $response = ["posts" => $results, "next"=>$posts->nextPageUrl()];

        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

}