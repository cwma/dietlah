$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $('#talkSendMessage').on('submit', function(e) {
        e.preventDefault();
        var url, request, tag, data;
        tag = $(this);
        url = __baseUrl + '/ajax/message/send';
        data = tag.serialize();

        request = $.ajax({
            method: "post",
            url: url,
            data: data
        });

        request.done(function (response) {
            if (response.status == 'success') {
                $('#talkMessages').append(response.html);
                tag[0].reset();
            } else {
                reasons = response['reason'];
                for(i=0; i<reasons.length; i++) {
                    Materialize.toast(reasons[i], 10000);
                }
            }
        });

    });


    $('body').on('click', '.talkDeleteMessage', function (e) {
        e.preventDefault();
        var tag, url, id, request;

        tag = $(this);
        id = tag.data('message-id');
        url = __baseUrl + '/ajax/message/delete/' + id;

        if(!confirm('Do you want to delete this message?')) {
            return false;
        }

        request = $.ajax({
            method: "post",
            url: url,
            data: {"_method": "DELETE"}
        });

        request.done(function(response) {
           if (response.status == 'success') {
                $('#message-' + id).hide(500, function () {
                    $(this).remove();
                });
           }
        });
    })

    var usernames = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch : {
            url : '/rest/userlist.json',
            ttl: 1,
        }
    });

    $('#username').typeahead({
      hint: true,
      highlight: true,
      minLength: 1
    },
    {
      name: 'usernames',
      source: usernames.ttAdapter()
    });

    $.validator.setDefaults({
        errorClass: 'invalid',
        errorPlacement: function (error, element) {
            $(element).next("label").attr("data-error", error.contents().text());
        }
    });

    $('#newconvo').validate({
        rules: {
            username: {
                required: true
            }
        },
        submitHandler: function(form) {
            $('.nav-progress').show();
            $("#newconvobutton").prop("disabled", true);
            $(form).ajaxSubmit({
                error: function(e){
                    $('.nav-progress').hide();
                    $("#newconvobutton").prop("disabled", false);
                    Materialize.toast("There was an error attempting to find this user " + e.statusText, 4000);
                },
                success: function (data, textStatus, jqXHR, form){
                    $('.nav-progress').hide();
                    $("#newconvobutton").prop("disabled", false);
                    if(data['status'] == 'success') {
                        Materialize.toast("Starting conversation", 4000);
                        window.location.href = "/message/" + data['userid'];  
                    } else {
                        Materialize.toast("Error attempting to start new conversation: ", 10000);
                        reasons = data['reason'];
                        for(i=0; i<reasons.length; i++) {
                            Materialize.toast(reasons[i], 10000);
                        }
                    }
                }
            });
        }
    }); 

    $('.chat-history').scrollTop($('.chat-history')[0].scrollHeight);
});
