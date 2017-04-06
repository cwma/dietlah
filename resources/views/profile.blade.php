@extends('template')

@section('title', 'Viewing '. $user['username'] .' profile - DietLah!')

@section('page-content')
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
<div class="container">
    <div class="row">
        <br>
        <div class="container profile-container">
            <div class="center-align">
                <div class="img-container">
                    <img class="responsive-img materialboxed center-align" style="display:inline" data-src="{{$user['profile_pic']}}">
                </div>
            </div>
            <div class="center-align">
                <h4>{{$user['username']}}</h4>
                <p>{!! nl2br(e($user['bio'])) !!}</p>
                @if(Auth::check() && Auth::id() != $user['id'])
                    <div class="right">
                        <a href="{{route('message.read', ['id'=>$user->id])}}" class="light-green-text">Send Message
                        <i class="material-icons light-green-text left" style="vertical-align:middle">mail</i></a>
                    </div>
                @endif
                @if(Auth::check() && Auth::id() == $user['id'])
                    <div class="left">
                        <a href="/editprofile"  class="light-green-text">Edit Profile
                        <i class="material-icons light-green-text left" style="vertical-align:middle">create</i>
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <div class="divider"></div>
    <div class="row">
        <div class="cards-container" id="grid" data-columns>
        </div>
    </div>
</div>
<div id="postmodal" class="modal modal-fixed-header modal-fixed-footer post-modal">
    <div class="progress post-progress light-green lighten-4"><div class="indeterminate light-green"></div></div>
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
                        <button class="btn waves-effect waves-ligh light-green lighten-1 right" type="submit" name="action" id='edit-comment-submit'>
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

@section('bottom-anchor')
<div class="container">
    <div class="row">
        <div id="marker"></div>

        <div class="end-of-page center" hidden>
            <hr>
            <p> You've reached the end of the page </p>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.0/jquery.form.min.js" integrity="sha384-E4RHdVZeKSwHURtFU54q6xQyOpwAhqHxy2xl9NLW9TQIqdNrNh60QVClBRBkjeB8" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/typeahead.bundle.min.js"></script>
<script type="text/javascript" src="js/linkify.min.js"></script>
<script type="text/javascript" src="js/linkify-html.min.js"></script>
<script type="text/javascript" src="js/materialize-tags.min.js"></script>
<script type="text/javascript" src="js/profile.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkiUSHEYhC-Eq_KjyTib-zmz7QBbkyk4M"></script>
<script id="card_template" type="text/x-handlebars-template">
@{{~#each posts~}}
<div class="card grid-item hoverable" hidden>
    <div class="article-header">
        <div class="article-user left">
            <div class="chip white">
                <img data-src="@{{{profile_pic}}}" alt="Contact Person">
                <a href="/profile/@{{user_id}}">@{{{username}}}</a>
            </div>
        </div>
        <div class="article-tag right">
        @{{#if tag}}
            <div class="chip light-green lighten-3">
                <a href="view/new/all?tags[]=@{{tagid}}">@{{{tag}}}</a>
            </div>
        @{{else}}
            <span style="margin-right: 10px; line-height:2">No Tags</span>
        @{{/if}}
        </div>
    </div>
    @{{#if (containsImage image)}}
        <a href="/post/@{{{id}}}" onclick="return false;">
            <div class="card-image" data-postid="@{{{id}}}">
                <img data-src="@{{{image}}}">
            </div>
        </a>
    @{{/if}}
    <a href="/post/@{{{id}}}" onclick="return false;" style="color: black">
        <div class="card-content" data-postid="@{{{id}}}">
            <span class="card-title truncate">@{{{title}}}</span>
            <p>@{{{linkify summary}}}</p>
            <br>
            <p class="light-green-text">@{{time}}</p>
        </div>
    </a>
    <div class="card-action">
        <div class="col s3 center card-icon-container">
            <a class="modal-trigger light-green-text card-icon center" href="#postmodal" data-postid="@{{{id}}}">More</a>
        </div>
        <div class="col s3 center card-icon-container">
            <a class="tooltipped light-green-text card-icon center" data-position="bottom" data-delay="50" data-tooltip="Comments">
                <i style="vertical-align:middle" class="material-icons light-green-text">comment</i><span>@{{{comments}}}</span>
            </a>
        </div>
        @if (Auth::check())
        <div class="col s3 center card-icon-container">
            <a class="tooltipped light-green-text post-like card-icon center" data-position="bottom" data-delay="50" data-tooltip="Like this post!" href="#"
                liked="@{{#if liked}}yes@{{else}}no@{{/if}}" post-id="@{{{id}}}">
                <i style="vertical-align:middle" class="material-icons light-green-text">@{{#if liked}}star@{{else}}star_border@{{/if}}</i><span>@{{{likes}}}</span>
            </a>
        </div>
        <div class="col s3 center card-icon-container">
            <a class="tooltipped light-green-text post-fav card-icon center" data-position="bottom" data-delay="50" data-tooltip="Add to Favourites!" href="#"
                favourited="@{{#if favourited}}yes@{{else}}no@{{/if}}" post-id="@{{{id}}}">
                <i style="vertical-align:middle" class="material-icons light-green-text">@{{#if favourited}}bookmark@{{else}}bookmark_border@{{/if}}</i>
            </a>
        </div>
        @else
        <div class="col s3 center card-icon-container">
            <a class="tooltipped light-green-text card-icon center" data-position="bottom" data-delay="50" data-tooltip="Like this post!" href="/login">
                <i style="vertical-align:middle" class="material-icons light-green-text">star_border</i><span>@{{{likes}}}</span>
            </a>
        </div>
        <div class="col s3 center card-icon-container">
            <a class="tooltipped light-green-text card-icon center" data-position="bottom" data-delay="50" data-tooltip="Add to Favourites!" href="/login">
                <i style="vertical-align:middle" class="material-icons light-green-text">bookmark_border</i>
            </a>
        </div>
        @endif
    </div>
</div>
@{{/each}}
</script>
<script id="post_template" type="text/x-handlebars-template">
<div id="postWrapper" hidden>
    <div class="progress post-progress light-green lighten-4"><div class="indeterminate light-green"></div></div>
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
                        <a href="#post-comments" class="tooltipped light-green-text post" data-position="bottom" data-delay="50" data-tooltip="Comments">
                            <i class="material-icons light-green-text" style="vertical-align:middle">comment</i>
                            <span class="light-green-text">(@{{comments_count}})</span>
                        </a>
                    </li>
                    @if (Auth::check())
                    <li class="tab">
                        <a href="#post-tags" class="tooltipped light-green-text" data-position="bottom" data-delay="50" data-tooltip="Suggest tags!">
                            <i class="material-icons light-green-text" style="vertical-align:middle">label</i>
                            <span class="light-green-text">(@{{tags_count}})</span>
                        </a>
                    </li>
                    @else
                    <li class="tab">
                        <a href="/login" target="_self" class="tooltipped light-green-text" data-position="bottom" data-delay="50" data-tooltip="Suggest tags!">
                            <i class="material-icons light-green-text" style="vertical-align:middle">label</i>
                            <span class="light-green-text">(@{{tags_count}})</span>
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
                    @{{#if (containsImage image)}}
                        <div class="center-align">
                            <div class="img-container">
                                <img class="post-modal-image responsive-img materialboxed center-align" style="display:inline" data-src="@{{image}}">
                            </div>
                        </div>
                    @{{/if}}
                    <div class="article-header">
                        <div class="article-user left">
                            <div class="chip white">
                                <img data-src="@{{profile_pic}}" alt="Contact Person">
                                <a href="/profile/@{{user_id}}">@{{{username}}}</a>
                            </div>
                        </div>
                        <div class="article-tag right">
                            <p class="light-green-text" style="line-height: 5px">@{{created_at}}</p>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>@{{title}}</h5>
                        <p>@{{{linkify text}}}</p>
                    </div>
                    <div class="divider"></div>
                    <div class="divider"></div>
                    @{{#if (containsLoc loc)}}
                    <div id="map"></div>
                    @{{/if}}
                    <div class="section">
                        @{{#each tags}}
                            @{{#if (top5 @index)}}
                                <div class="chip light-green lighten-3">
                                    <a href="view/new/all?tags[]=@{{@key}}">@{{this}}</a>
                                </div>
                            @{{/if}}
                        @{{~/each}}
                        <ul class="collapsible" data-collapsible="accordion">
                            <li>
                                <div class="collapsible-header">
                                    <i class="material-icons">keyboard_arrow_down</i>All Tags</span>
                                </div>
                                <div class="collapsible-body"><span>
                                    @{{#each tags}}
                                        <div class="chip light-green lighten-3">
                                            <a href="view/new/all?tags[]=@{{@key}}">@{{this}}</a>
                                        </div>
                                    @{{~/each}}
                                </span></div>
                            </li>
                        </ul>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                    @if(Auth::check())
                        @{{#if (canEdit user_id current_user_id)}}
                            <div class="left">
                                <a href="/update/@{{id}}"  class="light-green-text">Edit Post
                                <i class="material-icons light-green-text left" style="vertical-align:middle">create</i>
                            </div>
                        @{{/if}}
                            <div class="right">
                                <a href="#report-post-modal"  class="light-green-text" data-postid="@{{id}}">Report this post
                                <i class="material-icons light-green-text left" style="vertical-align:middle">flag</i>
                            </div>
                        </a>
                    @endif
                    </div>
                    <div class="row" style="margin-left: 5px"><br>
                    <div><a href="https://twitter.com/share" class="twitter-share-button" data-url="@{{root}}/post/@{{id}}"
                    data-text="@{{title}}" data-show-count="false">Tweet</a></div>
                    <div class="fb-like" data-width="350" data-href="@{{root}}/post/@{{id}}" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
                    </div>
                </div>

                <div id="post-comments" class="col s12">
                    <div class="commentsWrapper">
                        <div class="row">
                            @if (Auth::check())
                            <form id="comment-form" method="post" action="/rest/createcomment" novalidate="novalidate">
                                <div class="input-field col s12">
                                    <textarea name="comment" id="comment" class="materialize-textarea"></textarea>
                                    <label for="comment" id="input-validate-label">add a comment</label>
                                </div>
                                <input name="post_id" id="post_id" type="text" value="@{{id}}" hidden>
                                <div>
                                    <button class="btn waves-effect waves-ligh light-green lighten-1" type="submit" name="action" id="create-comment-submit">
                                        <i class="material-icons right">send</i>Post Comment
                                    </button>
                                </div>
                            </form>
                            @endif
                        </div>
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
                                <input name="post_id" id="suggest-tags-post-id" type="text" value="@{{id}}" hidden>
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
                        <div class="section">
                            <h5>Tags suggested by other users</h5>
                            @{{#each tags}}
                            <div class="chip light-green lighten-3" tag="@{{this}}">
                                @{{this}}
                                <i id="add-tag" class="material-icons add-tag">add</i>
                            </div>
                            @{{~/each}}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="row vertical-align">
            @if (Auth::check())
            <div class="col s3 center">
                <a class="tooltipped light-green-text full-post-like" data-position="top" data-delay="50" data-tooltip="Like this post!" href="#"
                    liked="@{{#if liked}}yes@{{else}}no@{{/if}}" post-id="@{{{id}}}">
                    <i style="vertical-align:middle" class="material-icons light-green-text">@{{#if liked}}star@{{else}}star_border@{{/if}}</i><span>@{{likes_count}}</span>
                </a>
            </div>
            <div class="col s3 center">
                <a class="tooltipped light-green-text full-post-fav" data-position="top" data-delay="50" data-tooltip="Add to Favourites!" href="#"
                    favourited="@{{#if favourited}}yes@{{else}}no@{{/if}}" post-id="@{{{id}}}">
                    <i style="vertical-align:middle" class="material-icons light-green-text">@{{#if favourited}}bookmark@{{else}}bookmark_border@{{/if}}</i>
                </a>
            </div>
            @else
            <div class="col s3 center">
                <a class="tooltipped light-green-text" data-position="top" data-delay="50" data-tooltip="Like this post!" href="/login">
                    <i style="vertical-align:middle" class="material-icons light-green-text">star_border</i><span>@{{likes_count}}</span>
                </a>
            </div>
            <div class="col s3 center">
                <a class="tooltipped light-green-text" data-position="top" data-delay="50" data-tooltip="Add to Favourites!" href="/login">
                    <i style="vertical-align:middle" class="material-icons light-green-text">bookmark_border</i>
                </a>
            </div>
            @endif

            <div class="col s3 center">
                <a class="tooltipped light-green-text" data-position="top" data-delay="50" data-tooltip="open post in new window"
                    target="_blank" href="/post/@{{id}}">
                <i class="material-icons light-green-text" style="vertical-align:middle">open_in_new</i>
                </a>
            </div>
            <div class="col s3 center">
                <a href="#!" class="light-green-text modal-tab-icon" id="modal-close">
                <i class="material-icons light-green-text" style="vertical-align:middle">close</i>
                </a>
            </div>
        </div>
    </div>
</div>
</script>
<script id="comments_template" type="text/x-handlebars-template">
    @{{#each comments}}
        <li class="collection-item collection-item-comments avatar hide-on-small-only">
            <img data-src="@{{{profile_pic}}}" alt="" class="circle">
            <span class="title"><a href="/profile/@{{user_id}}">@{{username}}</a></span>
            <p id="comment-text">@{{{linkify text}}}</p>
            <p id="actual" hidden>@{{{raw_text}}}</p>
            <p class="light-green-text">@{{time}}
                @if(Auth::check())
                    @{{#if (canEdit user_id ../current_user_id)}}
                    <a href="#edit-comment-modal"  class="tooltipped light-green-text right edit-comment"
                        data-position="bottom" data-delay="50" data-tooltip="Edit your comment" comment-id="@{{id}}">
                        <i class="material-icons light-green-text left" style="vertical-align:middle">create</i>
                    </a>
                    @{{/if}}
                @endif
                <a href="#report-comment-modal"  class="tooltipped light-green-text right report-comment-desktop"
                    data-position="bottom" data-delay="50" data-tooltip="Report this comment" comment-id="@{{id}}">
                    <i class="material-icons light-green-text left" style="vertical-align:middle">flag</i>
                </a>
            </p>
        </li>
        <li class="collection-item hide-on-med-and-up">
            <span class="title"><a href="/profile/@{{user_id}}">@{{username}}</a></span>
            <p id="comment-text">@{{{linkify text}}}</p>
            <p id="actual" hidden>@{{{raw_text}}}</p>
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
@stop
