function showNavLoadingBar() {
    $('.nav-progress').show();
}

function hideNavLoadingBar() {
    $('.nav-progress').hide();
}

function handleFormSubmit() {
    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param * 1024 * 1024)
    }, 'File size must be less than {0} megabytes');

    $('#update-profile').validate({
        rules: {
            bio: "required",
            image: {
              extension: "jpeg|jpg|png",
              filesize: 5
            }
        },
        messages: {
            image: {
                extension: "Please provide a valid image file: jpeg, jpg, png.",
                filesize: "Image file size must be smaller than 5 megabytes."
            }
        },
        submitHandler: function(form) {
            showNavLoadingBar();
            $(form).find(':submit').attr('disabled',true);
            $(form).ajaxSubmit({
                error: function(e){
                    hideNavLoadingBar();
                    Materialize.toast("There was an error editing this profile: " + e.statusText, 4000);
                    $(form).find(':submit').attr('disabled', false);
                },
                success: function (data, textStatus, jqXHR, form){
                    hideNavLoadingBar();
                    if(data['status'] == 'successful') {
                        Materialize.toast("your profile has been updated!", 4000);
                        window.location.href = "/myprofile";  
                    } else {
                        Materialize.toast("We were not able to update your profile", 10000);
                        reasons = data['reason'];
                        for(i=0; i<reasons.length; i++) {
                            Materialize.toast(reasons[i], 10000);
                        }
                        $(form).find(':submit').attr('disabled', false);
                    }
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
            if($(element).attr("name") == "image") {
                $('#image-label').attr("data-error", error.contents().text());
                $('#image-name').removeClass("valid").addClass("invalid");
            }
        }
    });
}

function initializeImagePreview() {
    if($('#image-preview').attr('src') != '') {
        $('#image-preview-container').show();
    }

    $('#image').on('change', function () {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#image-preview').attr('src', e.target.result);
            $('#image-preview-container').show();
        };
        reader.readAsDataURL(this.files[0]);
    });
}

function initializeDeleteImageBtn() {
    $('#image-preview-close').click(function(event) {
        $('#image-preview').attr('src', '');
        $('#image-preview-container').hide();
        $('#image').val('');
        $('#image-name').val('');
        $('#should_delete_image').val(true);
    });
}

$(document).ready(function(){
    showNavLoadingBar();
    $('#update-profile').find(':submit').attr('enabled','enabled');
    setupValidationErrorFormatting();
    handleFormSubmit();
    initializeImagePreview();
    initializeDeleteImageBtn();
    hideNavLoadingBar();
});