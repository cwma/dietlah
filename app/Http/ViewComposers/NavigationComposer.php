<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Tag;

class NavigationComposer
{
    public function compose(View $view)
    {
        $tags = Tag::all();
        $view->with('tags', $tags);
    }
}