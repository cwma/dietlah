<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view)
    {
        // Hardcoded test data for now
        $tags = ["keto (20)", "salad (30)", "food (15)", "dining (5)"];
        $tagCount = 10;
        $lists = ["keto diet", "frugal", "fav tags"];
        $view->with('tags', $tags)->with('tagCount', $tagCount)->with("lists", $lists);
    }
}