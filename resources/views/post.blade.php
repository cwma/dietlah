@extends('template')

@section('title', $post['title'].' - DietLah!')

@section('meta')
    <meta property="og:url"           content="{{url('/').'/post/'.$post['id']}}" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="DietLah! - {{$post['title']}}" />
    <meta property="og:description"   content="{{$post['summary']}}" />
@if($post['image'] != "")
    <meta property="og:image"         content="{{url('/').$post['image']}}" />
@endif
@stop

@section('page-content')
<div id="fb-root"></div>
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
<div class="container standalone-post-container">
    <div class="row">
        <div id="postWrapper">
            <div class="modal-header">
                <div class="row">
                    <div class="col s12">
                        <ul class="tabs tabs-fixed-width">
                            <li class="tab">
                                <a href="#post-content">
                                    <span class="light-green-text">Details</span> 
                                </a>
                            </li>
                            <li class="tab">
                                <a href="#post-comments" class="tooltipped light-green-text" data-position="bottom" data-delay="50" data-tooltip="Comments">
                                    <i class="material-icons light-green-text" style="vertical-align:middle">comment</i>
                                    <span class="light-green-text" id="post-comments-count">({{$post['comments_count']}})</span> 
                                </a>
                            </li>
                            @if(Auth::check())
                            <li class="tab">
                                <a href="#post-tags" class="tooltipped light-green-text" data-position="bottom" data-delay="50" data-tooltip="Suggest tags!">
                                    <i class="material-icons light-green-text" style="vertical-align:middle">label</i>
                                    <span class="light-green-text tags-count">({{$post['tags_count']}})</span> 
                                </a>
                            </li>
                            @else
                            <li class="tab">
                                <a href="/login" target="_self" class="tooltipped light-green-text" data-position="bottom" data-delay="50" data-tooltip="Suggest tags!">
                                    <i class="material-icons light-green-text" style="vertical-align:middle">label</i>
                                    <span class="light-green-text">({{$post['tags_count']}})</span> 
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-content">
                <div class="container">
                    <div class="row">
                        <div id="post-content" class="col s12">
                            @if ($post['image'] != "")
                                <div class="center-align">
                                    <div class="img-container">
                                        <img class="post-modal-image responsive-img materialboxed center-align" style="display:inline" data-src="{{$post['image']}}">
                                    </div>
                                </div>
                            @endif
                            <div class="article-header">
                                <div class="article-user left">
                                    <div class="chip white">
                                        <a href="/profile/{{$post['user_id']}}"><img data-src="{{$post['profile_pic']}}" alt="Contact Person"></a>
                                        <a href="/profile/{{$post['user_id']}}">{{$post['username']}}</a>
                                    </div>
                                </div>
                                <div class="article-tag right">
                                    <p class="light-green-text" style="line-height: 5px">{{$post['created_at']}}</p>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="section">
                                <h5>{{$post['title']}}</h5>
                                <p id="post-content">{!! $post['text'] !!}</p>
                            </div>
                            <div class="divider"></div>
                            @if ($post['location'])
                            <div id="map"></div>
                            @endif
                            <div class="divider"></div>

                            <div class="section tag-section">
                                @foreach ($post['tags'] as $id => $tag)
                                        @if ($loop->index == 5)
                                            @break
                                        @endif
                                        <div class="chip light-green lighten-3">
                                            <a href="view/new/all?tags[]={{$id}}">{{$tag}}</a>
                                        </div>
                                @endforeach
                                <ul class="collapsible" data-collapsible="accordion">
                                    <li>
                                        <div class="collapsible-header">
                                            <span><i class="material-icons">keyboard_arrow_down</i>All Tags</span>
                                        </div>
                                        <div class="collapsible-body"><span>
                                            @foreach ($post['tags'] as $tag)
                                                    <div class="chip light-green lighten-3">
                                                        <a class="truncate" href="view/new/all?tags[]={{$id}}">{{$tag}}</a>
                                                    </div>
                                            @endforeach
                                        </span></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="divider"></div>
                            @if (sizeof($recs) > 0)
                                <div class="section">
                                    <span> Users who liked this post also liked: </span><br>
                                    @foreach ($recs as $id => $title)
                                        <a href="/post/{{$id}}">{{$title}}</a><br>
                                    @endforeach
                                </div>
                                <div class="divider"></div>
                            @endif
                            <div class="section">
                                @if(Auth::check() && Auth::id() == $post['user_id'])
                                    <div class="left">
                                        <a href="/update/{{$post['id']}}"  class="light-green-text">Edit Post
                                        <i class="material-icons light-green-text left" style="vertical-align:middle">create</i>
                                    </div>
                                @elseif(Auth::check() && Auth::user()->is_admin)
                                    <div class="left">
                                        <a href="/update/{{$post['id']}}"  class="light-green-text">Admin Edit
                                        <i class="material-icons light-green-text left" style="vertical-align:middle">report_problem</i>
                                    </div>
                                @endif
                                @if(Auth::check())
                                    <div class="right">
                                        <a href="#report-post-modal"  class="light-green-text" data-postid="{{$post['id']}}">Report this post
                                        <i class="material-icons light-green-text left" style="vertical-align:middle">flag</i>
                                    </div>
                                </a>
                                @endif
                            </div>
                        </div>

                        <div id="post-comments" class="col s12">
                            <div class="commentsWrapper">
                                @if(Auth::check())
                                <div class="row">
                                    <form id="comment-form" method="post" action="/rest/createcomment" novalidate="novalidate">
                                        <div class="input-field col s12">
                                            <textarea name="comment" id="comment" class="materialize-textarea"></textarea>
                                            <label for="comment" id="input-validate-label">add a comment</label>
                                        </div>
                                        <input name="post_id" id="post_id" type="text" value="{{$post['id']}}" hidden>
                                        <div>
                                            <button class="btn waves-effect waves-ligh light-green lighten-1" type="submit" name="action"  id="create-comment-submit">
                                                <i class="material-icons right">send</i>Post Comment
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                @endif
                                <br>
                                <div class="divider"></div>
                                <div class="row">
                                    <ul class="collection comments-list">
                                    </ul>
                                </div>
                                <div id="comments-marker"></div>
                                <div class="end-of-comments center" hidden>
                                    <hr>
                                    <p> end of comments </p>
                                </div>
                            </div>
                        </div>
                        @if(Auth::check())
                        <div id="post-tags" class="col s12">
                            <div class="container">
                                <h5> Add your own tags to this post </h5>
                                <div class="section">
                                    <form id="suggest-tags" method="post" action="/rest/addtag" novalidate="novalidate">
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <textarea name id="suggested-tags" class="materialize-textarea" data-role="materialtags"></textarea>
                                                <label id="input-validate-label" for="suggested-tags">Your suggested tags for this post (min 3 chars, max 20 per tag), press enter to set a tag.</label>
                                            </div>
                                        </div>
                                        <input name="post_id" id="suggest-tags-post-id" type="text" value="{{$post['id']}}" hidden>
                                        <div class="row">
                                            <div class="col s12">
                                                <button class="btn waves-effect waves-ligh light-green lighten-1 right" type="submit" name="action">
                                                    <i class="material-icons right">send</i>Save Tags
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <br>
                                <div class="divider"></div>
                                <div class="section tag-section-others">
                                    <h5>Tags suggested by other users</h5>
                                    @foreach ($post['tags'] as $tag)
                                            <div class="chip light-green lighten-3" tag="{{$tag}}">
                                                {{$tag}}
                                                <i id="add-tag" class="material-icons add-tag">add</i>
                                            </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @if(Auth::check())
                <div class="row vertical-align">
                    <div class="col s6 center">
                        <a class="tooltipped light-green-text full-post-like" data-position="bottom" data-delay="50" data-tooltip="Like this post!" href="#"
                            liked="{{$post['liked'] ? 'yes' : 'no'}}" post-id="{{$post['id']}}">
                            <i style="vertical-align:middle" class="material-icons light-green-text">@if ($post['liked']) star @else star_border @endif</i><span>{{$post['likes_count']}}</span>
                        </a>
                    </div>
                    <div class="col s6 center">
                        <a class="tooltipped light-green-text full-post-fav" data-position="bottom" data-delay="50" data-tooltip="Add to Favourites!" href="#"
                            favourited="{{$post['favourited'] ?  'yes' : 'no'}}" post-id="{{$post['id']}}">
                            <i style="vertical-align:middle" class="material-icons light-green-text">@if ($post['favourited']) bookmark @else bookmark_border @endif</i>
                        </a>
                    </div>
                </div>
                @else
                <div class="row vertical-align">
                    <div class="col s6 center">
                        <a class="tooltipped light-green-text" data-position="bottom" data-delay="50" data-tooltip="Like this post!" href="/login">
                            <i style="vertical-align:middle" class="material-icons light-green-text">star_border</i><span>{{$post['likes_count']}}</span>
                        </a>
                    </div>
                    <div class="col s6 center">
                        <a class="tooltipped light-green-text" data-position="bottom" data-delay="50" data-tooltip="Add to Favourites!" href="/login">
                            <i style="vertical-align:middle" class="material-icons light-green-text">bookmark_border</i>
                        </a>
                    </div>
                </div>
                @endif
                    <div class="row" style="margin-left: 15px"><br>
                    <div><a href="https://twitter.com/share" class="twitter-share-button" data-url="{{url('/').'/post/'.$post['id']}}"
                    data-text="{{$post['title']}}" data-show-count="false">Tweet</a></div>
                    <div class="fb-like" data-width="350" data-href="{{url('/').'/post/'.$post['id']}}" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
                    </div>
            </div>
        </div>
    </div>
</div>

<div id="report-post-modal" class="modal report-modal report-post-modal">
    <div class="modal-content">
        <div class="container">
            <form id="report-post-form" method="post" action="/rest/report" novalidate="novalidate">
                <div class="row">
                    <div class="input-field col s12">
                        <textarea name="report_comment" id="report_comment" class="materialize-textarea"></textarea>
                        <label id="input-validate-label" for="report_comment">Please write a short explanation on why you are reporting this post</label>
                    </div>
                </div>
                <input name="reported_id" id="reported_id_post" type="text" value="" hidden>
                <input name="report_type" type="text" value="post" hidden>
                <div class="row">
                    <div class="col s12">
                        <button class="btn waves-effect waves-ligh light-green lighten-1 right" type="submit" name="action" id="report-post-submit">
                            <i class="material-icons right">send</i>Send Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="report-comment-modal" class="modal report-modal report-comment-modal">
    <div class="modal-content">
        <div class="container">
            <form id="report-comment-form" method="post" action="/rest/report" novalidate="novalidate">
                <div class="row">
                    <div class="input-field col s12">
                        <textarea name="report_comment" id="report_comment" class="materialize-textarea"></textarea>
                        <label id="input-validate-label" for="report_comment">Please write a short explanation on why you are reporting this comment</label>
                    </div>
                </div>
                <input name="reported_id" id="reported_id_comment" type="text" value="" hidden>
                <input name="report_type" type="text" value="comment" hidden>
                <div class="row">
                    <div class="col s12">
                        <button class="btn waves-effect waves-ligh light-green lighten-1 right" type="submit" name="action" id="report-comment-submit">
                            <i class="material-icons right">send</i>Send Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-comment-modal" class="modal report-modal edit-comment-modal">
    <div class="modal-content">
        <div class="container">
            <form id="edit-comment-form" method="post" action="/rest/updatecomment" novalidate="novalidate">
                <div class="input-field col s12">
                    <textarea name="comment" id="edit-comment" class="materialize-textarea"></textarea>
                    <label id="input-validate-label" for="comment">your updated comment here</label>
                </div>
                <input name="comment_id" id="edit-comment-id" type="text" value="" hidden>
                <div class="row">
                    <div class="col s12">
                        <button class="btn waves-effect waves-ligh light-green lighten-1 right" type="submit" name="action" id="edit-comment-submit">
                            <i class="material-icons right">send</i>Update Comment
                        </button>
                    </div>
                </div>
            </form>
            <div class="divider"></div>
            <form id="delete-comment-form" method="post" action="/rest/deletecomment" novalidate="novalidate">
                <div class="input-field col s12">
                    <input type="checkbox" class="filled-in" id="delete-comment-confirm" checked="checked" name="confirm" />
                    <label for="delete-comment-confirm">check to confirm delete</label>
                </div>
                <input name="comment_id" id="delete-comment-id" type="text" value="" hidden>
                <div class="row">
                    <div class="col s12">
                        <button class="btn waves-effect waves-ligh light-green lighten-1 right" type="submit" name="action" id="delete-comment-submit">
                            <i class="material-icons right">delete</i>Delete comment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="report-tag-modal" class="modal report-modal report-tag-modal">
    <div class="modal-content">
        <h4>Report This Tag</h4>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js" integrity="sha384-tIwI8+qJdZBtYYCKwRkjxBGQVZS3gGozr3CtI+5JF/oL1JmPEHzCEnIKbDbLTCer" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/typeahead.bundle.min.js"></script>
<script type="text/javascript" src="js/linkify.min.js"></script>
<script type="text/javascript" src="js/linkify-jquery.min.js"></script>
<script type="text/javascript" src="js/materialize-tags.min.js"></script>
<script type="text/javascript" src="js/post.6.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkiUSHEYhC-Eq_KjyTib-zmz7QBbkyk4M"></script>
<script>
    $(window).bind("load", function() {
       $.getScript('js/social.js', function() {});
    });
</script>
<script id="comments_template" type="text/x-handlebars-template">
    @{{#each comments}}
        <li class="collection-item collection-item-comments avatar hide-on-small-only">
            <a href="/profile/@{{user_id}}"><img data-src="@{{profile_pic}}" alt="" class="circle"></a>
            <span class="title"><a href="/profile/@{{user_id}}">@{{username}}</a></span>
            <p id="comment-text" class="comment-text">@{{{text}}}</p>
            <p id="actual" hidden text="@{{raw_text}}"></p>
            <p class="light-green-text">@{{time}}
                @if(Auth::check())
                    @{{#if (canEdit user_id ../current_user_id)}}
                    <a href="#edit-comment-modal"  class="tooltipped light-green-text right edit-comment" 
                        data-position="bottom" data-delay="50" data-tooltip="Edit your comment" comment-id="@{{id}}">
                        <i class="material-icons light-green-text left" style="vertical-align:middle">create</i>
                    </a>
                    @{{/if}}
                @endif
                @if(Auth::check() && Auth::user()->is_admin)
                    <a href="#edit-comment-modal"  class="tooltipped light-green-text right edit-comment" 
                        data-position="bottom" data-delay="50" data-tooltip="ADMIN" comment-id="@{{id}}">
                        <i class="material-icons light-red-text left" style="vertical-align:middle">report_problem</i>
                    </a>
                @endif
                <a href="#report-comment-modal"  class="tooltipped light-green-text right report-comment-desktop" 
                    data-position="bottom" data-delay="50" data-tooltip="Report this comment" comment-id="@{{id}}">
                    <i class="material-icons light-green-text left" style="vertical-align:middle">flag</i>
                </a>
            </p>
        </li>
        <li class="collection-item hide-on-med-and-up">
            <span class="title"><a href="/profile/@{{user_id}}">@{{username}}</a></span>
            <p id="comment-text" class="comment-text">@{{text}}</p>
            <p id="actual" hidden text="@{{raw_text}}">
            <p class="light-green-text">@{{time}}
                @if(Auth::check())
                    @{{#if (canEdit user_id ../current_user_id)}}
                    <a href="#edit-comment-modal"  class="tooltipped light-green-text right edit-comment" 
                        data-position="bottom" data-delay="50" data-tooltip="Edit your comment" comment-id="@{{id}}">
                        <i class="material-icons light-green-text left" style="vertical-align:middle">create</i>
                    </a>
                    @{{/if}}
                <a href="#report-comment-modal"  class="tooltipped light-green-text right report-comment" 
                    data-position="bottom" data-delay="50" data-tooltip="Report this comment" comment-id="@{{id}}">
                    <i class="material-icons light-green-text left" style="vertical-align:middle">flag</i>
                </a>
                @endif
            </p>
        </li>
    @{{~/each}}
</script>
<script id="tags-template" type="text/x-handlebars-template">
    @{{#each this}}
        @{{#if (top5 @index)}}
            <div class="chip light-green lighten-3">
                <a href="view/new/all?tags[]=@{{tag_id}}">@{{tag_name}}</a>
            </div>
        @{{/if}}
    @{{~/each}}
    <ul class="collapsible" data-collapsible="accordion">
        <li>
            <div class="collapsible-header">
                <i class="material-icons">keyboard_arrow_down</i>All Tags</span>
            </div>
            <div class="collapsible-body"><span>
                @{{#each this}}
                    <div class="chip light-green lighten-3">
                        <a href="view/new/all?tags[]=@{{tag_id}}">@{{tag_name}}</a>
                    </div>
                @{{~/each}}
            </span></div>
        </li>
    </ul>
</script>
<script id="tags-others-template" type="text/x-handlebars-template">
    <h5>Tags suggested by other users</h5>
    @{{~#each this}}
    <div class="chip light-green lighten-3" tag="@{{tag_name}}">
        @{{tag_name}}
        <i id="add-tag" class="material-icons add-tag">add</i>
    </div>
    @{{~/each}}
</script>
@stop
