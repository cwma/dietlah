@extends('template')

@section('title', 'New Post!')

@section('page-content')
<div class="container create-post-container">
    <h4> Create new post </h4>
    <div class="row">

        <form id="create-post" name="create-post" method="post" action="/rest/createpost" novalidate="novalidate">

            <div class="row">
                <div class="col s12 center-align">
                    <div id="image-preview-container">
                        <img class="responsive-img" id="image-preview" src=""/>
                        <span id="image-preview-close">&times;</span>
                        <input type="hidden" name="should_delete_image" id="should_delete_image">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                  <input name="title" id="title" type="text">
                  <label id="input-validate-label" for="title">Title</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <textarea name="text" id="text" class="materialize-textarea"></textarea>
                    <label id="input-validate-label" for="text">Description:</label>
                </div>
            </div>

            <div class="row">
                <div class="file-field input-field col s12">
                    <div class="btn light-green lighten-1">
                        <span>Image</span>
                        <input type="file" name="image" id="image">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" id="image-name">
                        <label id="image-label" for="image-name" style="padding-left:125px">You can add an image to your post (optional)</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                    <div id="map"></div>
                    <label id="input-validate-label" for="map">Place a marker to save a location (optional)</label>
                    <br>
                    <button class="btn waves-effect waves-ligh light-green lighten-1 left" type="button" name="action" id="deleteLocation">
                        Delete Location
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <textarea id="tags" class="materialize-textarea" data-role="materialtags"></textarea>
                    <label id="input-validate-label" for="tags">Your tags for this post (min 3 chars, max 20 per tag), press enter to set a tag.</label>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                    <button class="btn waves-effect waves-ligh light-green lighten-1 right" type="submit" name="action" id="submitBtn">
                        <i class="material-icons right">send</i>Create Post
                    </button>
                </div>
            </div>
        
        </form>
    </div>

</div>
@stop

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js" integrity="sha384-tIwI8+qJdZBtYYCKwRkjxBGQVZS3gGozr3CtI+5JF/oL1JmPEHzCEnIKbDbLTCer" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
<script type="text/javascript" src="js/typeahead.bundle.min.js"></script>
<script type="text/javascript" src="js/materialize-tags.min.js"></script>
<script type="text/javascript" src="js/createpost.1.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkiUSHEYhC-Eq_KjyTib-zmz7QBbkyk4M"></script>
@stop