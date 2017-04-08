<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Tag;

class NavigationComposer
{
    public function compose(View $view)
    {
        $tags = DB::Select('SELECT tags.id, tag_name from tags 
        					INNER JOIN post_tags ON tags.id = post_tags.tag_id group by post_tags.tag_id 
        					order by count(post_tags.tag_id) desc, tag_name asc', []);
        $view->with('tags', $tags)->with('user', Auth::user());
    }
}