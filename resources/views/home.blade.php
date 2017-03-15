@extends('template')

@section('title', 'DietLah!')

@section('page-content')
<div class="container">
    <div class="row">
        <div class="cards-container" id="grid" data-columns>


        </div>
    </div>
</div>
@stop

@section('bottom-anchor')
<div class="container">
    <div class="row">
        <div id="marker" last-id="{{$lastId}}" rest-url="{{$restUrl}}" page="{{$page}}"></div>

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
        <img class="materialboxed" data-src="@{{{this.cardPic}}}" data-caption="Salad!">
    </div>
    <div class="card-content">
        <span class="card-title">@{{{this.title}}}</span>
        <p>@{{{this.summary}}}</p>
    </div>
    <div class="card-action">
        <a class="modal-trigger" href="#modal2">Read More</a>
        <a class="btn btn-flat btn-floating waves-effect waves-green tooltipped light-green lighten-3" data-position="bottom" data-delay="50" data-tooltip="Add to Favourites!" href="#"><i class="material-icons" onclick="Materialize.toast('Added to favourites!', 4000)">star</i></a>
    </div>
</div>
@{{/each}}
</script>
@stop