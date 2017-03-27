<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Tag;

class NavigationComposer
{
    public function compose(View $view)
    {
        $tags = Tag::orderBy("tag_name")->get();
        $view->with('tags', $tags);
    }
}