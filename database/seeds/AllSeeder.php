<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\User;
use App\Post;
use App\Comment;
use App\Like;
use App\Favourite;
use App\Tag;
use App\PostTag;
use App\Report;
use App\TagReport;

class AllSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	self::generateUsers();
	self::generatePosts();
	self::generateComments();
	self::generateLikes();
	self::generateFavourites();
	self::generateTags();
	self::generatePostTags();
	self::generateReports();
	self::generateTagReports();
    }

    private function generateUsers() {
	$limit = 100;
	$faker = Faker\Factory::create();
	for ($i = 0; $i < $limit; $i++) {
	    $user = new User;
	    $user->username = $faker->userName;
	    $user->email = $faker->unique->freeEmail;
	    $user->password = Hash::make('test');
	    $user->profile_pic = $faker->imageUrl($width = 400, $height = 400, 'people');
	    $user->bio = $faker->sentence($nbWords = 6, $variableNbWords = true);
	    $user->save();
	}
    }

    private function generatePosts() {
	$limit = 100;
	$faker = Faker\Factory::create();
	for ($i = 0; $i < $limit; $i++) {
	    $post = new Post;
	    $post->image = $faker->imageUrl($width = 400, $height = 400, 'food');
	    $post->title = $faker->sentence($nbWords = 6, $variableNbWords = true);
	    $post->summary = $faker->text($maxNbChars = 100);
	    $post->text = $faker->text($maxNbChars = 1000);
	    $post->likes_count = $limit;
	    $post->comments_count = $limit;
	    $post->user_id = $faker->numberBetween($min = 1, $max = $limit);
	    $post->save();
	}
    }

    private function generateComments() {
	$limit = 100;
	$faker = Faker\Factory::create();
	for ($i = 1; $i <= $limit; $i++) {
	    for ($j = 0; $j <= $limit; $j++) {
	    	$comment = new Comment;
	    	$comment->comment = $faker->sentence($nbWords = 6, $variableNbWords = true);
	        $comment->user_id = $faker->numberBetween($min = 1, $max = $limit);
		$comment->post_id = $i;
	    	$comment->save();
	    }
	}
    }

    private function generateLikes() {
	$limit = 100;
	$faker = Faker\Factory::create();
	for ($i = 1; $i <= $limit; $i++) {
	    for ($j = 0; $j <= $limit; $j++) {
	    	$like = new Like;
	        $like->user_id = $faker->numberBetween($min = 1, $max = $limit);
		$like->post_id = $i;
	    	$like->save();
	    }
	}
    }

    private function generateFavourites() {
	$limit = 100;
	$faker = Faker\Factory::create();
	for ($i = 1; $i <= $limit; $i++) {
	    for ($j = 0; $j <= $limit; $j++) {
	    	$favourite = new Favourite;
	        $favourite->user_id = $faker->numberBetween($min = 1, $max = $limit);
		$favourite->post_id = $i;
	    	$favourite->save();
	    }
	}
    }

    private function generateTags() {
	$limit = 100;
	$faker = Faker\Factory::create();
	for ($i = 0; $i < $limit; $i++) {
	    $tag = new Tag;
	    $tag->tag_name = $faker->unique->word;
	    $tag->save();
	}
    }

    private function generatePostTags() {
	$limit = 100;
	$faker = Faker\Factory::create();
	for ($i = 1; $i <= $limit; $i++) {
	    for ($j = 1; $j <= $limit; $j++) {
	    	$post_tag = new PostTag;
		$post_tag->user_id = $faker->numberBetween($min = 1, $max = $limit);
		$post_tag->post_id = $i;
		$post_tag->tag_id = $j;
		$post_tag->save();
	    }
	}
    }

    private function generateReports() {
	$limit = 100;
	$faker = Faker\Factory::create();
	$type = ['user', 'post', 'comment', 'tag'];
	for ($i = 0; $i < $limit; $i++) {
	    $report = new Report;
	    $report->reported_id = $faker->numberBetween($min = 1, $max = $limit);
	    $report->report_type = $faker->randomElement($type);
	    $report->report_comment = $faker->sentence($nbWords = 6, $variableNbWords = true);
	    $report->status = true;
	    $report->user_id = $faker->numberBetween($min = 1, $max = $limit);
	    $report->save();
	}
    }

    private function generateTagReports() {
	$limit = 100;
	$faker = Faker\Factory::create();
	for ($i = 0; $i < $limit; $i++) {
	    $tag_report = new TagReport;
	    $tag_report->tag_id = $faker->numberBetween($min = 1, $max = $limit);
	    $tag_report->post_id = $faker->numberBetween($min = 1, $max = $limit);
	    $tag_report->report_comment = $faker->sentence($nbWords = 6, $variableNbWords = true);
	    $tag_report->status = true;
	    $tag_report->user_id = $faker->numberBetween($min = 1, $max = $limit);
	    $tag_report->save();
	}
    }
}
