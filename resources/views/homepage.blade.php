@extends('template')

@section('title', 'DietLah!')

@section('page-content')
<div class="container">
    <div class="row">
        <div class="cards-container" id="grid" data-columns>
        </div>
    </div>
</div>
<div id="postmodal" class="modal modal-fixed-header modal-fixed-footer post-modal">
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
                <div class="row right">
                    <button class="btn waves-effect waves-ligh light-green lighten-1" type="submit" name="action" id="submitBtn">
                        <i class="material-icons right">send</i>Send Report
                    </button>
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
                <div class="row right">
                    <button class="btn waves-effect waves-ligh light-green lighten-1" type="submit" name="action" id="submitBtn">
                        <i class="material-icons right">send</i>Send Report
                    </button>
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
                <div class="row right">
                    <button class="btn waves-effect waves-ligh light-green lighten-1" type="submit" name="action">
                        <i class="material-icons right">send</i>Update Comment
                    </button>
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
<script type="text/javascript" src="js/home.js"></script>
<script id="card_template" type="text/x-handlebars-template">
@{{~#each posts~}}
<div class="card grid-item hoverable" hidden>
    <div class="article-header">
        <div class="article-user left">
            <div class="chip white">
                <img data-src="@{{{this.profilePic}}}" alt="Contact Person">
                @{{{this.username}}}
            </div>
        </div>
        <div class="article-tag right">
            <div class="chip light-green lighten-3">
                @{{{this.tag}}}
            </div>
        </div>
    </div>
    <div class="card-image" data-postid="@{{{this.postId}}}">
        <img data-src="@{{{this.cardPic}}}">
    </div>
    <div class="card-content" data-postid="@{{{this.postId}}}">
        <span class="card-title truncate">@{{{this.title}}}</span>
        <p>@{{{linkify this.summary}}}</p>
        <br>
        <p class="light-green-text">@{{timeSince this.postTime.date}}</p>
    </div>
    <div class="card-action">
        <div class="col s3 center">
            <a class="modal-trigger light-green-text" href="#postmodal" data-postid="@{{{this.postId}}}">More</a>
        </div>
        <div class="col s3 center">
            <a class="tooltipped light-green-text" data-position="bottom" data-delay="50" data-tooltip="Comments" href="#">
                <i style="vertical-align:middle" class="material-icons light-green-text">comment</i><span>@{{{this.comments}}}</span>
            </a>
        </div>
        <div class="col s3 center">
            <a class="tooltipped light-green-text post-like" data-position="bottom" data-delay="50" data-tooltip="Like this post!" href="#"
                liked="@{{#if this.userLiked}}true@{{else}}false@{{/if}}" post-id="@{{{this.postId}}}">
                <i style="vertical-align:middle" class="material-icons light-green-text">@{{#if this.userLiked}}star@{{else}}star_border@{{/if}}</i><span>@{{{this.likes}}}</span>
            </a>
        </div>
        <div class="col s3 center">
            <a class="tooltipped light-green-text post-fav" data-position="bottom" data-delay="50" data-tooltip="Add to Favourites!" href="#"
                favourited="@{{#if this.userFavourited}}true@{{else}}false@{{/if}}" post-id="@{{{this.postId}}}">
                <i style="vertical-align:middle" class="material-icons light-green-text">@{{#if this.userFavourited}}bookmark@{{else}}bookmark_border@{{/if}}</i>
            </a>
        </div>
    </div>
</div>
@{{/each}}
</script>
<script id="post_template" type="text/x-handlebars-template">
<div id="postWrapper" hidden>
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
                            <span class="light-green-text">(@{{{this.commentCount}}})</span> 
                        </a>
                    </li>
                    <li class="tab">
                        <a href="#post-tags" class="tooltipped light-green-text" data-position="bottom" data-delay="50" data-tooltip="Suggest tags!">
                            <i class="material-icons light-green-text" style="vertical-align:middle">label</i>
                            <span class="light-green-text">(@{{{this.tagCount}}})</span> 
                        </a>
                    </li>
                </ul>
                <div class="progress post-progress light-green lighten-4">
                    <div class="indeterminate light-green"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-content">
        <div class="container">
            <div class="row">
                <div id="post-content" class="col s12">
                    <div class="center-align">
                        <div class="img-container">
                            <img class="post-modal-image responsive-img materialboxed center-align" style="display:inline" data-src="@{{{this.postPic}}}">
                        </div>
                    </div>
                    <div class="article-header">
                        <div class="article-user left">
                            <div class="chip white">
                                <img data-src="@{{{this.authorPic}}}" alt="Contact Person">
                                @{{{this.username}}}
                            </div>
                        </div>
                        <div class="article-tag right">
                            <p class="light-green-text" style="line-height: 5px">@{{timeSince this.postTime.date}}</p>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>@{{{this.title}}}</h5>
                        <p>@{{{linkify this.fulltext}}}</p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <ul class="collapsible" data-collapsible="accordion">
                            <li>
                                <div class="collapsible-header active">
                                    <i class="material-icons">keyboard_arrow_down</i>Top Tags</span>
                                </div>
                                <div class="collapsible-body"><span>
                                    @{{#each this.tags}}
                                        @{{#if (top5 @index)}}
                                            <div class="chip light-green lighten-3">
                                                @{{{this.tag}}}
                                            </div>
                                        @{{/if}}
                                    @{{~/each}}
                                </span></div>
                            </li>
                            <li>
                                <div class="collapsible-header">
                                    <i class="material-icons">keyboard_arrow_down</i>All Tags</span>
                                </div>
                                <div class="collapsible-body"><span>
                                    @{{#each this.tags}}
                                        <div class="chip light-green lighten-3">
                                            @{{{this.tag}}}
                                        </div>
                                    @{{~/each}}
                                </span></div>
                            </li>
                        </ul>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                            <div class="left">
                                <a href="/update/@{{this.postId}}"  class="light-green-text">Edit Post
                                <i class="material-icons light-green-text left" style="vertical-align:middle">create</i>
                            </div>
                            <div class="right">
                                <a href="#report-post-modal"  class="light-green-text" data-postid="@{{this.postId}}">Report this post
                                <i class="material-icons light-green-text left" style="vertical-align:middle">flag</i>
                            </div>
                        </a>
                    </div>
                </div>

                <div id="post-comments" class="col s12">
                    <div class="commentsWrapper">
                        <div class="row">
                            <form id="comment-form" method="post" action="/rest/createcomment" novalidate="novalidate">
                                <div class="input-field col s12">
                                    <textarea name="comment" id="comment" class="materialize-textarea"></textarea>
                                    <label for="comment" id="input-validate-label">add a comment</label>
                                </div>
                                <input name="post_id" id="post_id" type="text" value="@{{this.postId}}" hidden>
                                <div>
                                    <button class="btn waves-effect waves-ligh light-green lighten-1" type="submit" name="action">
                                        <i class="material-icons right">send</i>Post Comment
                                    </button>
                                </div>
                            </form>
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
                
                <div id="post-tags" class="col s12">
                    <div class="container">
                        <h5> Add your own tags to this post </h4>
                        <div class="section">
                            <form id="suggest-tags" method="post" action="/rest/addtag" novalidate="novalidate">
                                <div class="input-field col s12">
                                    <input name id="suggested-tags" class="materialize-textarea" data-role="materialtags"></input>
                                    <label id="input-validate-label" for="suggested-tags">Your suggested tags for this post (min 3 chars, max 20 per tag)</label>
                                </div>
                                <input name="post_id" id="suggest-tags-post-id" type="text" value="@{{{this.postId}}}" hidden>
                                <div class="row">
                                    <div class="right">
                                        <button class="btn waves-effect waves-ligh light-green lighten-1" type="submit" name="action">
                                            <i class="material-icons right">send</i>Save Tags
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br>
                        <div class="divider"></div>
                        <div class="section">
                            <h5>Tags suggested by other users</h4>
                            @{{#each this.tags}}
                            <div class="chip light-green lighten-3" tag="@{{{this.tag}}}">
                                @{{{this.tag}}}
                                <i id="add-tag" class="material-icons add-tag">add</i>
                            </div>
                            @{{~/each}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="row vertical-align">
            <div class="col s3 center">
                <a class="tooltipped light-green-text full-post-like" data-position="bottom" data-delay="50" data-tooltip="Like this post!" href="#"
                    liked="@{{#if this.userLiked}}true@{{else}}false@{{/if}}" post-id="@{{{this.postId}}}">
                    <i style="vertical-align:middle" class="material-icons light-green-text">@{{#if this.userLiked}}star@{{else}}star_border@{{/if}}</i><span>@{{{this.likes}}}</span>
                </a>
            </div>
            <div class="col s3 center">
            <a class="tooltipped light-green-text full-post-fav" data-position="bottom" data-delay="50" data-tooltip="Add to Favourites!" href="#"
                favourited="@{{#if this.userFavourited}}true@{{else}}false@{{/if}}" post-id="@{{{this.postId}}}">
                <i style="vertical-align:middle" class="material-icons light-green-text">@{{#if this.userFavourited}}bookmark@{{else}}bookmark_border@{{/if}}</i>
            </a>
            </div>
            <div class="col s3 center">
                <a class="tooltipped light-green-text" data-position="bottom" data-delay="50" data-tooltip="open post in new window" 
                    target="_blank" href="/post/@{{{this.postId}}}/@{{{this.title}}}">
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
            <img data-src="@{{{profilePic}}}" alt="" class="circle">
            <span class="title">@{{username}}</span>
            <p id="comment-text">@{{commentText}}</p>
            <p class="light-green-text">@{{timeSince commentTime.date}}
                <a href="#edit-comment-modal"  class="tooltipped light-green-text right edit-comment" 
                    data-position="bottom" data-delay="50" data-tooltip="Edit your comment" comment-id="@{{id}}">
                    <i class="material-icons light-green-text left" style="vertical-align:middle">create</i>
                </a>
                <a href="#report-comment-modal"  class="tooltipped light-green-text right report-comment-desktop" 
                    data-position="bottom" data-delay="50" data-tooltip="Report this comment" comment-id="@{{id}}">
                    <i class="material-icons light-green-text left" style="vertical-align:middle">flag</i>
                </a>
            </p>
        </li>
        <li class="collection-item hide-on-med-and-up">
            <span class="title">@{{username}}</span>
            <p id="comment-text">@{{commentText}}</p>
            <p class="light-green-text">@{{timeSince commentTime.date}}
                <a href="#edit-comment-modal"  class="tooltipped light-green-text right edit-comment" 
                    data-position="bottom" data-delay="50" data-tooltip="Edit your comment" comment-id="@{{id}}">
                    <i class="material-icons light-green-text left" style="vertical-align:middle">create</i>
                </a>
                <a href="#report-comment-modal"  class="tooltipped light-green-text right report-comment" 
                    data-position="bottom" data-delay="50" data-tooltip="Report this comment" comment-id="@{{id}}">
                    <i class="material-icons light-green-text left" style="vertical-align:middle">flag</i>
                </a>
            </p>
        </li>
        @{{~/each}}
</script>
@stop
