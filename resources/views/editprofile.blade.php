@extends('template')

@section('title', 'Edit Profile')

@section('page-content')
    <div class="container create-post-container">
        <h4> Editing Profile </h4>
        <div class="row">

            <form id="update-profile" name="update-profile" method="post" action="/rest/updateprofile" novalidate="novalidate">

                <div class="row">
                    <div class="col s12 center-align">
                        <div id="image-preview-container">
                            <img class="responsive-img" id="image-preview" src="{{$user['profile_pic']}}"/>
                            <span id="image-preview-close">&times;</span>
                            <input type="hidden" name="should_delete_image" id="should_delete_image">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <textarea name="bio" id="bio" class="materialize-textarea">{{$user['bio']}}</textarea>
                        <label id="input-validate-label" for="bio">Bio:</label>
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
                            <label id="image-label" for="image-name" style="padding-left:125px">Add a profile image (optional)</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <button class="btn waves-effect waves-ligh light-green lighten-1 right" type="submit" name="action" id="submitBtn">
                            <i class="material-icons right">send</i>Edit Profile
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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
    <script type="text/javascript" src="js/editprofile.js"></script>
@stop