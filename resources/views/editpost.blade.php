@extends('template')

@section('title', 'Edit Post')

@section('page-content')
    <div class="container create-post-container">
        <h4> Edit post </h4>
        <div class="row">

            <form id="update-post" name="update-post" method="post" action="/rest/updatepost" novalidate="novalidate">

                <input type="hidden" name="post_id" id="post_id" value="{{$post['id']}}">

                <div class="row">
                    <div class="col s12">
                        @if($post['image'] == '')
                            <img class="responsive-img" id="image-preview" src=""/>
                        @else
                            <img class="responsive-img" id="image-preview" src="storage/images/postimages/{{$post['image']}}"/>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <input name="title" id="title" type="text" value="{{$post['title']}}">
                        <label id="input-validate-label" for="title">Title</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <textarea name="text" id="text" class="materialize-textarea">{{$post['text']}}</textarea>
                        <label id="input-validate-label" for="text">Description:</label>
                    </div>
                </div>

                <div class="row">
                    <div class="file-field input-field col s2">
                        <button class="btn waves-effect waves-ligh light-green lighten-1 left" type="button" name="action" id="deleteImageBtn">
                            Delete
                        </button>
                        <input type="hidden" name="should_delete_image" id="should_delete_image">
                    </div>
                    <div class="file-field input-field col s10">
                        <div class="btn light-green lighten-1">
                            <span>Image</span>
                            <input type="file" name="image" id="image">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" id="image-name">
                            <label for="image-name" style="margin-left:125px">You can add an image to your post (optional)</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field  col s12">
                        <input id="location" type="text" class="validate" disabled value="Work in progress">
                        <label id="input-validate-label" for="title">Location</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="tags" class="materialize-textarea" data-role="materialtags"></textarea>
                        <label id="input-validate-label" for="tags">Your tags for this post (min 3 chars, max 20 per tag)</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <button class="btn waves-effect waves-ligh light-green lighten-1 left" type="button" name="action" id="deletePostBtn">
                            <i class="material-icons right">delete</i>Delete Post
                        </button>
                        <button class="btn waves-effect waves-ligh light-green lighten-1 right" type="submit" name="action" id="submitBtn">
                            <i class="material-icons right">send</i>Edit Post
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.0/jquery.form.min.js" integrity="sha384-E4RHdVZeKSwHURtFU54q6xQyOpwAhqHxy2xl9NLW9TQIqdNrNh60QVClBRBkjeB8" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
    <script type="text/javascript" src="js/typeahead.bundle.min.js"></script>
    <script type="text/javascript" src="js/materialize-tags.min.js"></script>
    <script type="text/javascript" src="js/editpost.js"></script>
@stop