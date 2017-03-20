<div class="navbar-fixed">
    <ul id="accountdropdown" class="dropdown-content">
        <li><a href="#">Login</a></li>
        <li><a href="#">Register</a></li>
    </ul>
    <nav class="light-green lighten-1">
        <div class="nav-wrapper">
            <ul id="nav-mobile" class="left hide-on-med-and-down">
                <li><a href="/"><b>DietLah!</b><i class="material-icons left">cloud</i></a></li>
                @if (Route::currentRouteNamed('home.default'))
                <li class="input-field" style="padding-left:10px;width:120px">
                    <select id="post-order-select" class="post-filter">
                        <option value="1" selected>New</option>
                        <option value="2">Popular</option>
                        <option value="3">Favourites</option>
                        <option value="4">Comments</option>
                        <option value="5">Relevance</option>
                    </select>
                </li>
                <li>&nbsp;</li>
                <li class="input-field" style="padding-left:10px;width:120px">
                    <select id="post-range-select" class="post-filter">
                        <option value="1" selected>All Time</option>
                        <option value="2">Today</option>
                        <option value="3">This week</option>
                        <option value="4">This month</option>
                    </select>
                </li>
                <li>&nbsp;</li>
                <li class="input-field" style="padding-left:10px;width:200px">
                    <select multiple id="post-tag-select" class="post-filter">
                        <option value="" disabled selected>All Tags</option>
                        @foreach ($tags as $tag) 
                            <option value="{{$tag['id']}}">{{$tag['name']}} ({{$tag['count']}})</option>
                        @endforeach
                    </select>
                </li>
                @endif
            </ul>
            <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li class="input-field">
                    <input id="search nav-search" type="search" placeholder="search posts">
                    <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                    <i class="material-icons">close</i>
                </li>
                <li>
                    <a class="tooltipped" href="#" data-position="bottom" data-delay="50" data-tooltip="About DietLah!">
                        <i class="material-icons">info</i>
                    </a>
                </li>
                <li>
                    <a class="tooltipped" href="/createpost" data-position="bottom" data-delay="50" data-tooltip="Create new post">
                        <i class="material-icons">create</i>
                    </a>
                </li>
                <li class="">
                    <a class="dropdown-button" href="#!" data-activates="accountdropdown">
                        <i class="material-icons">account_box</i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="progress nav-progress light-green lighten-4">
        <div class="indeterminate light-green"></div>
    </div>
</div>
<div class="navbar-mobile">
    <ul id="slide-out" class="side-nav">
        <li class="{{Route::currentRouteNamed('home.default') ? 'active' : '' }}"><a href="/"><b>Home</b><i class="material-icons left">home</i></a></li>
        @if (Route::currentRouteNamed('home.default'))
        <li class="input-field z-depth-3 input-dropdown" style="margin-left:10px;width:280px">
            <select id="post-order-select-mobile" class="post-filter">
                <option value="1" selected>New</option>
                <option value="2">Popular</option>
                <option value="3">Favourites</option>
                <option value="4">Comments</option>
                <option value="5">Relevance</option>
            </select>
        </li>
        <li class="input-field z-depth-3 input-dropdown" style="margin-left:10px;width:280px">
            <select id="post-range-select-mobile" class="post-filter">
                <option value="1" selected>All Time</option>
                <option value="2">Today</option>
                <option value="3">This week</option>
                <option value="4">This month</option>
            </select>
        </li>
        <li class="input-field z-depth-3 input-dropdown" style="margin-left:10px;width:280px">
            <select multiple id="post-tag-select-mobile" class="post-filter">
                <option value="" disabled selected>All Tags</option>
                @foreach ($tags as $tag) 
                    <option value="{{$tag['id']}}">{{$tag['name']}} ({{$tag['count']}})</option>
                @endforeach
            </select>
        </li>
        <li class="input-field z-depth-3" style="margin-left:10px;width:280px">
            <input id="search nav-search" type="search" placeholder="search posts">
            <label class="label-icon" for="search"><i class="material-icons">search</i></label>
            <i class="material-icons" style="line-height: 2">close</i>
        </li>
        @endif
        <li><a href="/"><b>About DietLah!</b><i class="material-icons left">info</i></a></li>
        <li><a href="/createpost"><b>Create Post</b><i class="material-icons left">create</i></a></li>
        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
                <li>
                    <a class="collapsible-header">Account<i class="material-icons">account_box</i></a>
                    <div class="collapsible-body" style="padding:0px!important">
                        <ul>
                            <li style="padding-left:43px"><a href="#">Login</a></li>
                            <li style="padding-left:43px"><a href="#">Register</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>

    </ul>
</div>