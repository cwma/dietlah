function showNavLoadingBar() {
    $('.nav-progress').show();
}

function hideNavLoadingBar() {
    $('.nav-progress').hide();
}

function handleFormSubmit() {
    $('#create-post').validate({
        rules: {
            title: "required",
            text: "required",
        },
        submitHandler: function(form) {
            showNavLoadingBar();
            $(form).find(':submit').attr('disabled','disabled');
            $(form).ajaxSubmit({
                data : {
                    tags: $('#tags').materialtags('items')
                },
                error: function(e){
                    hideNavLoadingBar();
                    Materialize.toast("There was an error creating this post: " + e.statusText, 4000);
                    $(form).find(':submit').attr('enabled','enabled');
                },
                success: function (data, textStatus, jqXHR, form){
                    hideNavLoadingBar();
                    console.log(data);
                    Materialize.toast("your post has been created!", 4000);
                    //redirect after
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
}

$(document).ready(function(){
    $('#create-post').find(':submit').attr('enabled','enabled');
    setupValidationErrorFormatting();
    handleFormSubmit();
    initializeTagChips();
    hideNavLoadingBar();
});