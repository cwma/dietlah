@extends('template')

@section('title', 'DietLah!')

@section('page-content')
<div class="container">
    <div class="row">
        <div class="cards-container" id="grid" data-columns>


        </div>
    </div>
</div>
<div id="postmodal" class="modal modal-fixed-footer">
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
    <div class="card-image">
        <img data-src="@{{{this.cardPic}}}">
    </div>
    <div class="card-content">
        <span class="card-title">@{{{this.title}}}</span>
        <p>@{{{this.summary}}}</p>
        <br>
        <p class="modal-trigger light-green-text">@{{timeSince this.postTime.date}}</p>
    </div>
    <div class="card-action">
        <a class="modal-trigger light-green-text" href="#postmodal" style="vertical-align:top" data-postid="@{{{this.id}}}">More</a>
        <a class="tooltipped light-green-text" data-position="bottom" data-delay="50" data-tooltip="Comments" href="#">
            <i class="material-icons light-green-text">chat</i><span style="vertical-align:top">@{{{this.comments}}}</span>
        </a>
        <a class="tooltipped light-green-text" data-position="bottom" data-delay="50" data-tooltip="Like this post!" href="#">
            <i class="material-icons light-green-text" onclick="Materialize.toast('Liked!', 4000)">@{{#if this.userLiked}}star@{{else}}star_border@{{/if}}</i><span style="vertical-align:top">@{{{this.likes}}}</span>
        </a>
        <a class="tooltipped light-green-text" data-position="bottom" data-delay="50" data-tooltip="Add to Favourites!" href="#">
            <i class="material-icons light-green-text" onclick="Materialize.toast('Added to Favourites!', 4000)">@{{#if this.userFavourited}}bookmark@{{else}}bookmark_border@{{/if}}</i>
        </a>
    </div>
</div>
@{{/each}}
</script>
<script id="post_template" type="text/x-handlebars-template">
<div id="postWrapper" hidden>
    <div class="modal-content">
        <h4>@{{{this.title}}}</h4>
        <p>@{{{this.fulltext}}}</p>
    </div>
    <div class="modal-footer">
        <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
    </div>
</div>
</script>
@stop