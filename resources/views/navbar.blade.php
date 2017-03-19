<div class="navbar-fixed">
    <ul id="listsdropdown" class="dropdown-content">
        @foreach ($lists as $list) 
            <li><a href="/tag/{{$list}}/popular">{{$list}}</a></li>
        @endforeach
        <li><a href="/manage">Manage Lists</a></li>
    </ul>
    <ul id="tagsdropdown" class="dropdown-content">
        @foreach ($tags as $tag) 
            <li><a href="/tag/{{$tag}}/popular">{{$tag}}</a></li>
        @endforeach
        <li><a href="/tags">See all tags ({{$tagCount}})</a></li>
    </ul>
    <ul id="accountdropdown" class="dropdown-content">
        <li><a href="#">Login</a></li>
        <li><a href="#">Register</a></li>
    </ul>
    <nav class="light-green lighten-1">
        <div class="nav-wrapper">
            <ul id="nav-mobile" class="left hide-on-med-and-down">
                <li><a href="/"><b>DietLah!</b><i class="material-icons left">cloud</i></a></li>
                <li class="active"><a href="/">All</a></li>
                <li class=""><a class="dropdown-button" href="#!" data-activates="tagsdropdown">Tags<i class="material-icons left">arrow_drop_down</i></a></li>
                <li class=""><a class="dropdown-button" href="#!" data-activates="listsdropdown">My Lists<i class="material-icons left">arrow_drop_down</i></a></li>
            </ul>
            <a href="#" data-activates="mobile" class="button-collapse"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li><form>
                    <div class="input-field">
                        <input id="search nav-search" type="search" placeholder="search posts">
                        <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                        <i class="material-icons">close</i>
                    </div>
                </form></li>
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
        <div class="nav-wrapper light-green lighten-2 hide-on-med-and-down">
            <ul id="nav-mobile" class="left">
                <li style="padding-left: 10px">Viewing All Posts</li>
                <li style="padding-left: 8px; padding-right: 8px">|</li>
                <li class="active"><a href="#">New</a></li>
                <li><a href="#">Popular</a></li>
                <li><a href="#">Most Comments</a></li>
                <li><a href="#">Relevance</a></li>
            </ul>
        </div>
    </nav>
    <div class="progress nav-progress light-green lighten-4">
        <div class="indeterminate light-green"></div>
    </div>
</div>
<div class="navbar-mobile">
    <ul id="mobile" class="side-nav">
        <li><a href="#">Mobile navigation WIP</a></li>
        <!-- WIP -->
    </ul>
</div>