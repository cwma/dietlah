<div class="navbar-fixed">
    <ul id="accountdropdown" class="dropdown-content">
        @if (Auth::guest())
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
        @else
            <li><a href="{{ route('profile.my') }}">My Profile</a></li>
            <li><a href="{{ route('message.read') }}">Messages</a></li>
            @if (Auth::user()->is_admin)
            <li><a href="{{ route('admin') }}">Admin</a></li>
            @endif
            @if (!Auth::user()->verified)
            <li><a href="{{ route('verify.resendpage') }}">Verify Email</a></li>
            @endif
            <li>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
		    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        @endif
    </ul>
    <nav class="light-green lighten-1">
        <div class="nav-wrapper">
            <ul class="left hide-on-med-and-down">
                <li><a href="/"><img class="logo" src="logo.png"><span class="logo-text">DietLah!</span></a></li>
                @if (Route::currentRouteNamed('home.default') || Route::currentRouteNamed('home.sorted') || Route::currentRouteNamed('home.search'))
                <li class="input-field" style="padding-left:10px;width:120px">
                    <select id="post-order-select" class="post-filter">
                        <option value="new" selected>New</option>
                        <option value="popular">Popular</option>
                        @if(Auth::check())
                        <option value="favourites">Favourites</option>
                        <option value="myposts">My Posts</option>
                        @endif
                        <option value="comments">Comments</option>
                    </select>
                </li>
                <li>&nbsp;</li>
                <li class="input-field" style="padding-left:10px;width:120px">
                    <select id="post-range-select" class="post-filter">
                        <option value="all" selected>All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This week</option>
                        <option value="month">This month</option>
                    </select>
                </li>
                <li>&nbsp;</li>
                <li class="input-field tag-field-size" style="padding-left:10px;">
                    <select multiple id="post-tag-select" class="post-filter tag-filter">
                        <option value="" disabled selected>All Tags</option>
                        @foreach ($tags as $tag) 
                            <option value="{{$tag->id}}">{{$tag->tag_name}}</option>
                        @endforeach
                    </select>
                </li>
                <li>
                    <a class="clear-tags tooltipped" href="#" data-position="bottom" data-delay="50" data-tooltip="Clear tags">
                        <i class="material-icons">close</i>
                    </a>
                </li>
                <li>
                    <a class="refresh tooltipped" href="#" data-position="bottom" data-delay="50" data-tooltip="Refresh">
                        <i class="material-icons">autorenew</i>
                    </a>
                </li>
                @endif
            </ul>
            <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
            @if (Route::currentRouteNamed('home.default') || Route::currentRouteNamed('home.sorted') || Route::currentRouteNamed('home.search'))
            <ul class="hide-on-large-only">
                <li>
                    <a class="refresh">
                    <i class="material-icons">autorenew</i></a>
                </li>
            </a>
            </ul>
            @endif
            <ul class="right hide-on-med-and-down">
                @if (Route::currentRouteNamed('home.default') || Route::currentRouteNamed('home.sorted') || Route::currentRouteNamed('home.search'))
                <li class="input-field search-field-size">
                    <input id="nav-search" type="search" placeholder="search posts" name="nav-search">
                    <label class="label-icon" for="nav-search"><i class="material-icons">search</i></label>
                </li>
                @endif
                <li class="{{Route::currentRouteNamed('about') ? 'active' : '' }}">
                    <a class="tooltipped" href="/about" data-position="bottom" data-delay="50" data-tooltip="About DietLah!">
                        <i class="material-icons">info</i>
                    </a>
                </li>
                <li class="{{Route::currentRouteNamed('post.create') ? 'active' : '' }}">
                    <a class="tooltipped" href="/createpost" data-position="bottom" data-delay="50" data-tooltip="Create new post">
                        <i class="material-icons">create</i>
                    </a>
                </li>
                <li>
                    <a class="dropdown-button" {{Auth::check() ? 'href=/profile/'.$user['id'] : ''}} data-activates="accountdropdown">
                        @if(Auth::check())
                            <img src="{{$user['profile_pic']}}" class="circle nav-image">
                        @else
                            <i class="material-icons">account_box</i>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="progress nav-progress light-green lighten-4" style="display:none">
        <div class="indeterminate light-green"></div>
    </div>
</div>
<div class="navbar-mobile">
    <ul id="slide-out" class="side-nav collapsible collapsible-accordion" style="overflow-x:hidden">
        @if(Auth::check())
        <li>
            <div class="userView">
                <div class="background" style="background-color: #9ccc65">
                </div>
                <a href="{{ route('profile.my') }}"><img src="{{$user['profile_pic']}}" class="circle"></a>
                <a href="{{ route('profile.my') }}"><span class="white-text name">{{$user['username']}}</span></a>
            </div>
        </li>
        @else 
        <li>
            <div class="userView">
                <div class="background" style="background-color: #9ccc65">
                </div>
                <a href="/"><img class="logo" src="logo.png"><span class="logo-text" style="color:white;">DietLah!</span></a>
            </div>
        </li>
        @endif
        @if (Route::currentRouteNamed('home.default') || Route::currentRouteNamed('home.sorted') || Route::currentRouteNamed('home.search'))
        <li class="no-padding">
            <a class="collapsible-header active"><b>Home</b><i class="material-icons left">home</i><i class="material-icons right">keyboard_arrow_down</i></a>
            <div class="collapsible-body" style="padding:0px!important">
                <ul>
                    <li class="input-field z-depth-3 input-dropdown" style="margin-left:10px;width:280px">
                        <select id="post-order-select-mobile" class="post-filter">
                            <option value="new" selected>New</option>
                            <option value="popular">Popular</option>
                            @if(Auth::check())
                            <option value="favourites">Favourites</option>
                            <option value="myposts">My Posts</option>
                            @endif
                            <option value="comments">Comments</option>
                            <option value="relevance">Relevance</option>
                        </select>
                    </li>
                    <li class="input-field z-depth-3 input-dropdown" style="margin-left:10px;width:280px">
                        <select id="post-range-select-mobile" class="post-filter">
                            <option value="all" selected>All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This week</option>
                            <option value="month">This month</option>
                        </select>
                    </li>
                    <li class="input-field z-depth-3 input-dropdown" style="margin-left:10px;width:280px">
                        <select multiple id="post-tag-select-mobile" class="post-filter tag-filter">
                            <option value="" disabled selected>All Tags</option>
                                @foreach ($tags as $tag) 
                                    <option value="{{$tag->id}}">{{$tag->tag_name}}</option>
                                @endforeach
                        </select>
                    </li>
                    <li>
                        <a class="clear-tags" href="#">Clear Tags
                            <i class="material-icons">close</i>
                        </a>
                    </li>
                    <li class="input-field z-depth-3" style="margin-left:10px;width:280px">
                        <input id="nav-search-mobile" type="search" placeholder="search posts" name="nav-search">
                        <label class="label-icon" for="nav-search"><i class="material-icons">search</i></label>
                    </li>
                    <li><div class="divider"></div></li>
                </ul>
            </div>
        </li>
        @else
        <li class="{{Route::currentRouteNamed('home.default') ? 'active' : '' }}"><a href="/"><b>Home</b><i class="material-icons left">home</i></a></li>
        @endif
        <li class="{{Route::currentRouteNamed('about') ? 'active' : '' }}"><a href="/about"><b>About DietLah!</b><i class="material-icons left">info</i></a></li>
        <li class="{{Route::currentRouteNamed('post.create') ? 'active' : '' }}"><a href="/createpost"><b>Create Post</b><i class="material-icons left">create</i></a></li>

        <li class="no-padding">
            <a class="collapsible-header">Account
                <i class="material-icons">account_box</i><i class="material-icons right">keyboard_arrow_down</i>
            </a>
            <div class="collapsible-body" style="padding:0px!important">
                <ul>
                 @if (Auth::guest())
                    <li class="{{Route::currentRouteNamed('login') ? 'active' : '' }}" style="padding-left:43px"><a href="{{ route('login') }}">Login</a></li>
                    <li class="{{Route::currentRouteNamed('register') ? 'active' : '' }}" style="padding-left:43px"><a href="{{ route('register') }}">Register</a></li>
                @else
                    @if (Auth::user()->is_admin)
                    <li style="padding-left:43px"><a href="{{ route('admin') }}">Admin</a></li>
                    @endif
                    @if (!Auth::user()->verified)
                    <li><a href="{{ route('verify.resendpage') }}">Verify Email</a></li>
                    @endif
                    <li class="{{Route::currentRouteNamed('profile.my') ? 'active' : '' }}" style="padding-left:43px"><a href="{{ route('profile.my') }}">My Profile</a></li>
                    <li class="{{Route::currentRouteNamed('message.read') ? 'active' : '' }}" style="padding-left:43px"><a href="{{ route('message.read') }}">Messages</a></li>
                    <li style="padding-left:43px">
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                        Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                @endif           
                </ul>
            </div>
        </li>

    </ul>
</div>
