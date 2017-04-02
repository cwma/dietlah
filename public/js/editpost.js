function showNavLoadingBar() {
    $('.nav-progress').show();
}

function hideNavLoadingBar() {
    $('.nav-progress').hide();
}

function handleFormSubmit() {
    $('#update-post').validate({
        rules: {
            title: "required",
            text: "required",
        },
        submitHandler: function(form) {
            showNavLoadingBar();
            $(form).find(':submit').attr('disabled','disabled');
            $(form).ajaxSubmit({
                data : {
                    tags: $('#tags').materialtags('items'),
                    location: dietlah.loc
                },
                error: function(e){
                    hideNavLoadingBar();
                    Materialize.toast("There was an error editing this post: " + e.statusText, 4000);
                    $(form).find(':submit').attr('enabled','enabled');
                },
                success: function (data, textStatus, jqXHR, form){
                    hideNavLoadingBar();
                    Materialize.toast("your post has been edited!", 4000);
                    window.location.href = "/post/" + data['post_id'];
                }
            });
        }
    });
}

function setupValidationErrorFormatting() {
    $.validator.setDefaults({
        errorClass: 'invalid',
        errorPlacement: function (error, element) {
            $(element).next("label").attr("data-error", error.contents().text());
        }
    });
}

function initializeImagePreview() {
    $('#image').on('change', function () {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#image-preview').attr('src', e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
    });
}

function initializeDeleteImageBtn() {
    $('#deleteImageBtn').click(function(event) {
        $('#image-preview').attr('src', '');
        $('#image').val('');
        $('#should_delete_image').val(true);
    });
}

function initializeTagChips(userTags) {
    console.log("setup");
    var tags = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: dietlah.tags
    });
    $('#tags').materialtags({
        typeaheadjs: [{
            highlight: true,
        },{
            source: tags.ttAdapter()
        }],
        freeInput: true,
        maxChars: 20,
        trimValue: true,
        CapitalizeFirstLetterOnly: true
    });
    $('#tags').on('beforeItemAdd', function(event) {
        if(event.item.length < 3) {
            event.cancel = true;
        }
    });

    // add tags user previously tagged
    dietlah.user_tags.forEach(function(tag){
        $('#tags').materialtags('add', tag);
    });
}

function initializeDeletePostBtn() {
    $('#deletePostBtn').click(function(event){
        event.preventDefault();
        if(confirm("Are you sure you want to delete this student?")){
            showNavLoadingBar();
            $.ajax({
                'url' : '/deletepost',
                'type': 'POST',
                'dataType': 'json',
                'data': {post_id: $('#post_id').val()},
                'success': function(data,  textStatus, jqXHR) {
                    hideNavLoadingBar();
                    Materialize.toast("your post has been deleted!", 4000);
                    window.location.href = "/";
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    hideNavLoadingBar();
                    Materialize.toast("There was an error deleting this post: " + textStatus, 4000);
                }
            });
        }
    });
}


function initMaps() {
    $('#deleteLocation').on('click', function(e){
        marker.setMap(null);
        marker = null;
        dietlah.loc = "";
    })

    var map;
    var marker;
    var infowindow;
    var messagewindow;

    if (dietlah.loc != null && dietlah.loc != "") {
        pos = {lat: parseFloat(dietlah.loc.split(",")[0]), lng:parseFloat(dietlah.loc.split(",")[1])};
        map = new google.maps.Map(document.getElementById('map'), {
            center: pos,
            zoom: 16
        });
        var marker = new google.maps.Marker({
          position: pos,
          map: map
        });
    } else {
        var singapore = {lat: 1.3521, lng: 103.8198};
        map = new google.maps.Map(document.getElementById('map'), {
            center: singapore,
            zoom: 12
        });
    }

    google.maps.event.addListener(map, 'click', function(event) {
        if (marker) {
            marker.setPosition(event.latLng);
        } else {
            marker = new google.maps.Marker({
                position: event.latLng,
                map: map
            });
        }
        dietlah.loc = marker.getPosition().lat() + "," + marker.getPosition().lng();
    });
}


$(document).ready(function(){
    initMaps();
    $('#create-post').find(':submit').attr('enabled','enabled');
    setupValidationErrorFormatting();
    handleFormSubmit();
    initializeImagePreview();
    initializeDeleteImageBtn();
    initializeTagChips();
    initializeDeletePostBtn();
    hideNavLoadingBar();
});