<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JavaScript;
use Faker;

class HomeController extends Controller {

    public function index($page = 1) {
        JavaScript::put([
            "page" => 1,
            "restUrl" => "/rest/home/popular/0/",
        ]);


        return view('home');
    }



    # RESTFUL end points

    public function restHome($sorttype, $datetime, $page) {
        $faker = Faker\Factory::create();
        $faker->seed($page);

        $posts = [];

        for($i = ($page * 12) - 11 ; $i < ($page * 12) + 1; $i++) {
            array_push($posts, ["title" => $i.". ".$faker->sentence($nbWords = 3, $variableNbWords = true), "username" => $faker->name,
                                "summary" => $faker->sentence($nbWords = 30, $variableNbWords = true), "tag" => $faker->word,
                                "profilePic" => $faker->imageUrl($width = 200, $height = 200, 'cats', true, 'Faker'),
                                "cardPic" => $faker->imageUrl($width = 800, $height = 600, 'cats', true, 'Faker'),
                                "postTime" => $faker->dateTime($max = 'now', $timezone = "GMT+8"),
                                "likes" => $faker->numberBetween($min = 0, $max = 99),
                                "comments" => $faker->numberBetween($min = 0, $max = 99),
                                "userLiked" => $faker->boolean($chanceOfGettingTrue = 50),
                                "userFavourited" => $faker->boolean($chanceOfGettingTrue = 50),
                                "id" => $i,
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

        $commentCount = $faker->numberBetween($min = 0, $max = 20);
        $comments = [];
        for($i = 0; $i < $commentCount; $i++) {
            array_push($comments, ["username" => $faker->name, "profilePic" => $faker->imageUrl($width = 200, $height = 200, 'cats', true, 'Faker'),
                                   "commentText" => $faker->sentence($nbWords = 30, $variableNbWords = true)]);
        }

        $post = ["title" => $postId.". ".$faker->sentence($nbWords = 3, $variableNbWords = true), "username" => $faker->name,
                "fulltext" => $faker->sentence($nbWords = 30, $variableNbWords = true), "tag" => $faker->word,
                "authorPic" => $faker->imageUrl($width = 200, $height = 200, 'cats', true, 'Faker'),
                "postPic" => $faker->imageUrl($width = 800, $height = 600, 'cats', true, 'Faker'),
                "postTime" => $faker->dateTime($max = 'now', $timezone = "GMT+8"),
                "likes" => $faker->numberBetween($min = 0, $max = 99),
                "commentCount" => $commentCount,
                "comments" => $comments,
                "userLiked" => $faker->boolean($chanceOfGettingTrue = 50),
                "userFavourited" => $faker->boolean($chanceOfGettingTrue = 50),
                "id" => $postId
                ];
        return response(json_encode($post)) ->header('Content-Type', 'application/json');
    }
}
