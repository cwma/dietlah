@extends('template')

@section('title', 'DietLah!')

@section('page-content')
<div class="container">
    <div class="row">
        <div class="cards-container" id="grid" data-columns>
        </div>
    </div>
</div>
<div id="postmodal" class="modal modal-fixed-header modal-fixed-footer">
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
<script type="text/javascript" src="js/linkify.min.js"></script>
<script type="text/javascript" src="js/linkify-html.min.js"></script>
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
        <span class="card-title">@{{{this.title}}}</span>
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
                        <a href="#post-edit">
                            <span class="light-green-text">Edit</span> 
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
                    <li class="tab">
                        <a href="#post-report"  class="tooltipped light-green-text" data-position="bottom" data-delay="50" data-tooltip="Report this post">
                            <i class="material-icons light-green-text" style="vertical-align:middle">flag</i>
                        </a>
                    </li>
                </a>
                </ul>
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
                        @{{#each this.tags}}
                            <div class="chip light-green lighten-3">
                                @{{{this.tag}}} (@{{this.votes}})
                            </div>
                        @{{~/each}}
                    </div>
                </div>
                
                <div id="post-edit" class="col s12">

                </div>
                
                <div id="post-comments" class="col s12">
                    <div class="row">
                        <form id="commentForm">
                            <div class="input-field col s12">
                                <textarea id="comment" class="materialize-textarea"></textarea>
                                <label for="comment">add a comment</label>
                            </div>
                            <input id="postId" type="text" value="@{{{this.postId}}}"hidden>
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
                        <ul class="collection">
                            @{{#each this.comments}}
                            <li class="collection-item avatar">
                                <img data-src="@{{{this.profilePic}}}" alt="" class="circle">
                                <span class="title">@{{this.username}}</span>
                                <p>@{{this.commentText}}</p>
                                <p class="light-green-text">@{{timeSince this.commentTime.date}}</p>
                            </li>
                            @{{~/each}}
                        </ul>
                    </div>
                </div>
                
                <div id="post-tags" class="col s12">

                </div>
                <div id="post-report" class="col s12">

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
@stop