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
                    Materialize.toast("There was an error editing this post: " + e.statusText, 4000);
                    $(form).find(':submit').attr('enabled','enabled');
                },
                success: function (data, textStatus, jqXHR, form){
                    hideNavLoadingBar();
                    console.log(data);
                    Materialize.toast("your post has been edited!", 4000);
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

    // add tags user previously tagged
    dietlah.user_tags.forEach(function(tag){
        $('#tags').materialtags('add', tag);
    });
}

function initializeDeleteBtn() {
    $('#deleteBtn').click(function(event){
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
                    console.log(data);
                    Materialize.toast("your post has been deleted!", 4000);
                    //redirect after
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    hideNavLoadingBar();
                    Materialize.toast("There was an error deleting this post: " + textStatus, 4000);
                }
            });
        }
    });
}


$(document).ready(function(){
    $('#create-post').find(':submit').attr('enabled','enabled');
    setupValidationErrorFormatting();
    handleFormSubmit();
    initializeTagChips();
    initializeDeleteBtn();
    hideNavLoadingBar();
});