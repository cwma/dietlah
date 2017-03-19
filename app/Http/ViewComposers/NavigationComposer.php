<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view)
    {
        // Hardcoded test data for now
        $tags = [["id"=> 1, "name"=>"keto", "count"=>30], ["id"=> 2, "name"=>"dining", "count"=>20], ["id"=> 3, "name"=>"low carb", "count"=>15], ["id"=> 4, "name"=>"salad", "count"=>10]];
        $view->with('tags', $tags);
    }
}