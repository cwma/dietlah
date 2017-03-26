/* handlebars helper functions */

function timeSince(date) {
    if (typeof date !== 'object') {
        date = new Date(date);
    }

    var seconds = Math.floor((new Date() - date) / 1000);
    var intervalType;

    var interval = Math.floor(seconds / 31536000);
    if (interval >= 1) {
        intervalType = 'year';
    } else {
        interval = Math.floor(seconds / 2592000);
        if (interval >= 1) {
            intervalType = 'month';
        } else {
            interval = Math.floor(seconds / 86400);
            if (interval >= 1) {
                intervalType = 'day';
            } else {
                interval = Math.floor(seconds / 3600);
                if (interval >= 1) {
                    intervalType = "hour";
                } else {
                    interval = Math.floor(seconds / 60);
                    if (interval >= 1) {
                        intervalType = "minute";
                    } else {
                        interval = seconds;
                        intervalType = "second";
                    }
                }
            }
        }
    }

    if (interval > 1 || interval === 0) {
        intervalType += 's';
    }

    return interval + ' ' + intervalType + " ago.";
};

function registerDateTimeHelper() {
    Handlebars.registerHelper("timeSince", function(dateTime) {
        return timeSince(dateTime);
    });
}

function registerLinkifyHelper() {
    Handlebars.registerHelper("linkify", function(post) {
        return linkifyHtml(post);
    });
}

function registerCanEditHelper() {
    Handlebars.registerHelper("canEdit", function(userid, commentuserid) {
        return userid == commentuserid;
    });
}

/* page rendering functions */

function compileCommentsTemplate() {
    var source = $("#comments_template").html();
    return Handlebars.compile(source);
}

function showNavLoadingBar() {
    $('.nav-progress').show();
}

function hideNavLoadingBar() {
    $('.nav-progress').hide();
}

function renderComments(commentsJson){
    $('.comments-list').append(dietlah.commentsTemplate(commentsJson));
    $("#commentsWrapper").fadeIn();
}

/* initialize modals on home page */

function initializePostModals() {
    $('.report-post-modal').modal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .5, // Opacity of modal background
        inDuration: 300, // Transition in duration
        outDuration: 200, // Transition out duration
        startingTop: '5%', // Starting top style attribute
        endingTop: '10%', // Ending top style attribute
        ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
            $("#reported_id_post").val($(trigger).attr('data-postid'));
        },
        complete: function(modal) { 
            $("#reported_id_post").val("");
        } // Callback for Modal close
    });
    $('.report-comment-modal').modal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .5, // Opacity of modal background
        inDuration: 300, // Transition in duration
        outDuration: 200, // Transition out duration
        startingTop: '5%', // Starting top style attribute
        endingTop: '10%', // Ending top style attribute
        ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
            $("#reported_id_comment").val($(trigger).attr('comment-id'));
        },
        complete: function(modal) { 
            $("#reported_id_comment").val("");
        } // Callback for Modal close
    });
    $('.edit-comment-modal').modal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .5, // Opacity of modal background
        inDuration: 300, // Transition in duration
        outDuration: 200, // Transition out duration
        startingTop: '5%', // Starting top style attribute
        endingTop: '10%', // Ending top style attribute
        ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
            $("#edit-comment-id").val($(trigger).attr('comment-id'));
            $('#edit-comment').val($(trigger).parent().prev().html());
            $('#edit-comment').trigger('autoresize');
        },
        complete: function(modal) { 
            $("#edit-comment-id").val("");
            $('#edit-comment').val("");
            $('#edit-comment').trigger('autoresize');
        }
    });
    $('.report-tag-modal').modal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .5, // Opacity of modal background
        inDuration: 300, // Transition in duration
        outDuration: 200, // Transition out duration
        startingTop: '5%', // Starting top style attribute
        endingTop: '10%', // Ending top style attribute
        ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.

        },
        complete: function(modal) { 
        } // Callback for Modal close
    });
}

/* on click events for likes/favs */

function handleLikeClickEvent(elementId) {
    $(elementId).on('click', function(e) {
        var likeCaller = this;
        e.preventDefault(); 
        var liked = $(likeCaller).attr("liked");
        var postId = $(likeCaller).attr("post-id");
        $.ajax({
            type: "POST",
            url: "/rest/like",
            data: {
                liked:liked,
                postId:postId
            },
            success: function( msg ) {
                Materialize.toast(msg["response"], 4000);
                if(liked == "yes") {
                    $(likeCaller).attr("liked", "no");
                    $(likeCaller).children("i").html("star_outline");
                    $(likeCaller).children("span").html(msg["likes"]);
                } else {
                    $(likeCaller).attr("liked", "yes");
                    $(likeCaller).children("i").html("star");
                    $(likeCaller).children("span").html(msg["likes"]);
                }
            }
        });
    })
}

function handleFavouriteClickEvent(elementId) {
    $(elementId).on('click', function(e) {
        var favCaller = this;
        e.preventDefault(); 
        var favourited = $(favCaller).attr("favourited");
        var postId = $(favCaller).attr("post-id");
        $.ajax({
            type: "POST",
            url: "/rest/favourite",
            data: {
                favourited:favourited,
                postId:postId
            },
            success: function( msg ) {
                Materialize.toast(msg["response"], 4000);
                if(favourited == "yes") {
                    $(favCaller).attr("favourited", "no");
                    $(favCaller).children("i").html("bookmark_outline");
                } else {
                    $(favCaller).attr("favourited", "yes");
                    $(favCaller).children("i").html("bookmark");
                }
            }
        });
    })
}

/* ajax form submits */

function handleReportPostSubmit() {
    $('#report-post-form').validate({
        rules: {
            report_comment: "required",
            reported_id: "required",
            report_type: "required"
        },
        submitHandler: function(form) {
            showNavLoadingBar();
            $(form).ajaxSubmit({
                error: function(e){
                    hideNavLoadingBar();
                    Materialize.toast("There was an error attempting to submit this report: " + e.statusText, 4000);
                },
                success: function (data, textStatus, jqXHR, form){
                    hideNavLoadingBar();
                    Materialize.toast("Your report has been submitted.", 4000);
                    $(form).resetForm();
                    $(form).find('#report_comment').trigger('autoresize');
                    console.log(data);
                }
            });
        }
    }); 
}

function handleReportCommentSubmit() {
    $('#report-comment-form').validate({
        rules: {
            report_comment: "required",
            reported_id: "required",
            report_type: "required"
        },
        submitHandler: function(form) {
            showNavLoadingBar();
            $(form).ajaxSubmit({
                error: function(e) {
                    hideNavLoadingBar();
                    Materialize.toast("There was an error attempting to submit this report: " + e.statusText, 4000);
                },
                success: function (data, textStatus, jqXHR, form){
                    hideNavLoadingBar();
                    Materialize.toast("Your report has been submitted.", 4000);
                    $(form).resetForm();
                    $(form).find('#report_comment').trigger('autoresize');
                    console.log(data);
                }
            });
        }
    }); 
}

function initializeSubmitComment() {
    $('#comment-form').validate({
        rules: {
            comment: "required",
            post_id: "required",
        },
        submitHandler: function(form) {
            showNavLoadingBar();
            $(form).ajaxSubmit({
                error: function(e){
                    hideNavLoadingBar();
                    Materialize.toast("There was an error attempting to submit a comment " + e.statusText, 4000);
                },
                success: function (data, textStatus, jqXHR, form){
                    hideNavLoadingBar();
                    Materialize.toast(data["response"], 4000);
                    $(form).resetForm();
                    $(form).find('#comment').trigger('autoresize');
                    reinitializeCommentsScroll($(form).find('#post_id').val());
                    console.log(data);
                }
            });
        }
    }); 
}

function handleEditCommentSubmit() {
    $('#edit-comment-form').validate({
        rules: {
            comment_id: "required",
            comment: "required",
        },
        submitHandler: function(form) {
            showNavLoadingBar();
            $(form).ajaxSubmit({
                error: function(e){
                    hideNavLoadingBar();
                    Materialize.toast("There was an error attempting to update this reportt: " + e.statusText, 4000);
                },
                success: function (data, textStatus, jqXHR, form){
                    hideNavLoadingBar();
                    Materialize.toast("Your comment has been updated.", 4000);
                    $(form).resetForm();
                    $(form).find('#edit_comment').trigger('autoresize');
                    console.log(data);
                }
            });
        }
    }); 
}

function handleSuggestTagsSubmit() {
    $('#suggest-tags').submit(function(){
        showNavLoadingBar();
        $(this).ajaxSubmit({
            data : {
                tags: $('#suggested-tags').materialtags('items')
            },
            error: function(e){
                hideNavLoadingBar();
                Materialize.toast("There was an error attempting to suggest tags: " + e.statusText, 4000);
            },
            success: function (data, textStatus, jqXHR, form){
                hideNavLoadingBar();
                console.log(data);
                Materialize.toast("your suggested tags have been saved", 4000);
            }
        });
        return false;
    });
}

/* load scripts on home page */

function loadPostJavascriptElements() {
    /* called whenever a post modal is opened */
    dietlah.postModalOpen = true;
    handleLikeClickEvent('.full-post-like');
    handleFavouriteClickEvent('.full-post-fav');
    $(window).lazyLoadXT();
    $('ul.tabs').tabs({
        onShow: function(tab) {
            $(tab).find('img').lazyLoadXT();
            $.event.trigger("resize"); // shitty hack, to trigger the detection of marker when reloading page
        }
    });
    $('.materialboxed').materialbox();
    $('.tooltipped').tooltip({delay: 50});
    $('.collapsible').collapsible();
    initializeCommentsScroll(dietlah.postId);
    initializeSubmitComment();
    initializeTagChips(dietlah.userTags);
    handleSuggestTagsSubmit();
}

function loadCommentsJavascriptElements(){
    $(".comments-list").find('img').lazyLoadXT();
    $('#comments-marker').lazyLoadXT({visibleOnly: false, checkDuplicates: false});
    $('.tooltipped').tooltip({delay: 50});
}

function disableInfiniteScroll() {
    $('#marker').off();
    $('.end-of-page').fadeIn();
}

function disableCommentsScroll() {
    $('#comments-marker').off();
    $('.end-of-comments').fadeIn();
}

function paginationFailure(jqXHR, textStatus) {
    console.log(textStatus);
    console.log(jqXHR);
}

function ajaxLoadComments(postid) {
    showNavLoadingBar();
    $.ajax({
        url: dietlah.nextComments,
        dataType: "json",
    }).done(function (response) {
        hideNavLoadingBar();
        renderComments(response);
        loadCommentsJavascriptElements();
        dietlah.nextComments = response["next"]
        if (response["next"] == null) {
            disableCommentsScroll();
            dietlah.commentsEnd = true;
        }
    }).fail(function(jqXHR, textStatus) {
        paginationFailure(jqXHR, textStatus);
    });
}

function initializeCommentsScroll(postid) {
    dietlah.commentsEnd = false;
    dietlah.nextComments = "/rest/comments/" + postid
    $('#comments-marker').on('lazyshow', function () {
        ajaxLoadComments(postid);
    }).lazyLoadXT({visibleOnly: false});
}

function reinitializeCommentsScroll(postId) {
    if(dietlah.commentsEnd){
        $('#comments-marker').lazyLoadXT({visibleOnly: false, checkDuplicates: false});
    }
    $('#comments-marker').off();
    $('.end-of-comments').hide();
    $('.comments-list').html("")
    initializeCommentsScroll(postId);
    $.event.trigger("resize"); // shitty hack, to trigger the detection of marker when reloading page
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
    var tags = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: dietlah.tags
    });
    $('#suggested-tags').materialtags({
        typeaheadjs: [{
            highlight: true,
        },{
            source: tags.ttAdapter()
        }],
        freeInput: true,
        maxChars: 20,
        trimValue: true,
    });
    $('.add-tag').on('click', function(e) {
        $('#suggested-tags').materialtags('add', $(this).parent().attr("tag"));
    })
    $('#suggested-tags').on('beforeItemAdd', function(event) {
        if(event.item.length < 3) {
            event.cancel = true;
        }
    });
    for(i in userTags) {
        $('#suggested-tags').materialtags('add', userTags[i]);
    }
}

function registerHandleBarsHelpers() {
    registerDateTimeHelper();
    registerLinkifyHelper();
    registerCanEditHelper();
}

$(document).ready(function(){
    console.log(dietlah.postId)
    loadPostJavascriptElements();
    registerHandleBarsHelpers();
    initializePostModals();
    setupValidationErrorFormatting();
    handleReportPostSubmit();
    handleReportCommentSubmit();
    handleEditCommentSubmit();
    dietlah.commentsTemplate = compileCommentsTemplate();
    hideNavLoadingBar();
});