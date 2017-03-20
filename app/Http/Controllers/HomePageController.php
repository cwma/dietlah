<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JavaScript;
use Faker;

class HomePageController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request, $page = 1) {
        JavaScript::put([
            "page" => 1,
            "restUrl" => "/rest/postfeed/",
        ]);
        return view('homepage');
    }



    # RESTFUL end points

    public function restPostFeed($order, $range, $datetime, $page, Request $request) {
        $faker = Faker\Factory::create();
        $faker->seed($page);

        $posts = [];

        $metadata = "params: ".$order." ".$range." ".$datetime." ".$page." ".json_encode($request->tags);
        for($i = ($page * 12) - 11 ; $i < ($page * 12) + 1; $i++) {
            array_push($posts, ["title" => $i.". ".$faker->sentence($nbWords = 3, $variableNbWords = true), "username" => $faker->name,
                                "summary" => $faker->sentence($nbWords = 30, $variableNbWords = true)."<br><br>".$metadata, 
                                "tag" => $faker->word,
                                "profilePic" => $faker->imageUrl($width = 200, $height = 200, 'cats', true, 'Faker'),
                                "cardPic" => $faker->imageUrl($width = 800, $height = 600, 'cats', true, 'Faker'),
                                "postTime" => $faker->dateTime($max = 'now', $timezone = "GMT+8"),
                                "likes" => $faker->numberBetween($min = 0, $max = 99),
                                "comments" => $faker->numberBetween($min = 0, $max = 99),
                                "userLiked" => $faker->boolean($chanceOfGettingTrue = 50),
                                "userFavourited" => $faker->boolean($chanceOfGettingTrue = 50),
                                "postId" => $i, "tagCount" => $faker->numberBetween($min = 1, $max = 9)
                                ]);
        }

        $response = ["posts" => $posts, "lastId" => "0"];
        if ($page > 3) {
            $response["hasMore"] = false;
        } else {
            $response["hasMore"] = true;
        }

        return response(json_encode($response)) ->header('Content-Type', 'application/json');
    }

    public function restPost($postId) {
        $faker = Faker\Factory::create();
        $faker->seed($postId);

        $tagCount = $faker->numberBetween($min = 1, $max = 9);
        $tags = [];
        for($i = 0; $i < $tagCount; $i++) {
            array_push($tags, ["tag" => $faker->word, "votes" => $faker->numberBetween($min = 1, $max = 20),
                                "userVoted" => $faker->boolean($chanceOfGettingTrue = 50)]);
        }


        $commentCount = $faker->numberBetween($min = 0, $max = 20);
        $comments = [];
        for($i = 0; $i < $commentCount; $i++) {
            array_push($comments, ["username" => $faker->name, "profilePic" => $faker->imageUrl($width = 200, $height = 200, 'cats', true, 'Faker'),
                                   "commentText" => $faker->sentence($nbWords = 30, $variableNbWords = true),
                                   "commentTime" => $faker->dateTime($max='now', $timezone="GMT+8")]);
        }



        $post = ["title" => $postId.". ".$faker->sentence($nbWords = 7, $variableNbWords = true), "username" => $faker->name,
                "fulltext" => $faker->paragraphs($nb = 5, $asText = true), "tag" => $faker->word,
                "authorPic" => $faker->imageUrl($width = 200, $height = 200, 'cats', true, 'Faker'),
                "postPic" => $faker->imageUrl($width = 800, $height = 600, 'cats', true, 'Faker'),
                "postTime" => $faker->dateTime($max = 'now', $timezone = "GMT+8"),
                "likes" => $faker->numberBetween($min = 0, $max = 99),
                "commentCount" => $commentCount,
                "comments" => $comments,
                "userLiked" => $faker->boolean($chanceOfGettingTrue = 50),
                "userFavourited" => $faker->boolean($chanceOfGettingTrue = 50),
                "postId" => $postId, "tags" => $tags, "tagCount" => $tagCount
                ];
        return response(json_encode($post)) ->header('Content-Type', 'application/json');
    }
}
