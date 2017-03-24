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

function registerTopTagsView() {
    Handlebars.registerHelper("top5", function(index) {
        if (index < 5) {
            return true;
        } else { 
            return false;
        }
    });
}

/* page rendering functions */

function compileCardTemplate() {
    var source = $("#card_template").html();
    return Handlebars.compile(source);
}

function compilePostTemplate() {
    var source = $("#post_template").html();
    return Handlebars.compile(source);
}

function compileCommentsTemplate() {
    var source = $("#comments_template").html();
    return Handlebars.compile(source);
}

function showNavLoadingBar() {
    $('.progress').show();
}

function hideNavLoadingBar() {
    $('.progress').hide();
}

function renderCards(grid, cardJson) {
    var div = document.createElement('div');
    div.innerHTML = dietlah.cardTemplate(cardJson);
    var elements = div.childNodes;
    salvattore.appendElements(grid, elements);
    $('.card').fadeIn();
}

function renderPost(modal, postJson, postTemplate) {
    $(modal).html(postTemplate(postJson));
    $("#postWrapper").fadeIn();
}

function renderComments(commentsJson){
    $('.comments-list').append(dietlah.commentsTemplate(commentsJson));
    $("#commentsWrapper").fadeIn();
}

function clearPost(modal) {
    $(modal).html("");
    dietlah.postModalOpen = false;
}

/* initialize modals on home page */

function initializeHomeModals() {
    postTemplate = compilePostTemplate();
    $('.post-modal').modal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .5, // Opacity of modal background
        inDuration: 300, // Transition in duration
        outDuration: 200, // Transition out duration
        startingTop: '0%', // Starting top style attribute
        endingTop: '5%', // Ending top style attribute
        ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
            showNavLoadingBar();
            postid = $(trigger).attr('data-postid');
            $.ajax({
                url: "/rest/post/" + postid,
                dataType: "json"
            }).done(function (response) {
                renderPost(modal, response, postTemplate);
                loadPostJavascriptElements(modal, response);
                hideNavLoadingBar();
            }).fail(function(jqXHR, textStatus) {
                paginationFailure(jqXHR, textStatus);
                hideNavLoadingBar();
            });
        },
        complete: function(modal) { 
            clearPost(modal);
            $('#comments-marker').off();
        } // Callback for Modal close
    });
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

function initializeCardClickModalOpen() {
    $('.card-image, .card-content').on('click', function() {
        $("a[data-postid="+$(this).attr("data-postid")+"]").click();
    })
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
                Materialize.toast(msg["test"], 4000);
                if(liked == "true") {
                    $(likeCaller).attr("liked", false);
                    $(likeCaller).children("i").html("star_outline");
                    $(likeCaller).children("span").html(msg["likes"]);
                } else {
                    $(likeCaller).attr("liked", true);
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
                Materialize.toast(msg["test"], 4000);
                if(favourited == "true") {
                    $(favCaller).attr("favourited", false);
                    $(favCaller).children("i").html("bookmark_outline");
                } else {
                    $(favCaller).attr("favourited", true);
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
            $(form).ajaxSubmit({
                error: function(e){
                    Materialize.toast("There was an error attempting to submit this report: " + e.statusText, 4000);
                },
                success: function (data, textStatus, jqXHR, form){
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
            $(form).ajaxSubmit({
                error: function(e){
                    Materialize.toast("There was an error attempting to submit this report: " + e.statusText, 4000);
                },
                success: function (data, textStatus, jqXHR, form){
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
            $(form).ajaxSubmit({
                error: function(e){
                    Materialize.toast("There was an error attempting to submit a comment " + e.statusText, 4000);
                },
                success: function (data, textStatus, jqXHR, form){
                    Materialize.toast(data["test"], 4000);
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
            $(form).ajaxSubmit({
                error: function(e){
                    Materialize.toast("There was an error attempting to update this reportt: " + e.statusText, 4000);
                },
                success: function (data, textStatus, jqXHR, form){
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
        $(this).ajaxSubmit({
            data : {
                tags: $('#suggested-tags').materialtags('items')
            },
            error: function(e){
                Materialize.toast("There was an error attempting to suggest tags: " + e.statusText, 4000);
            },
            success: function (data, textStatus, jqXHR, form){
                console.log(data);
                Materialize.toast("your suggested tags have been saved", 4000);
            }
        });
        return false;
    });
}

/* load scripts on home page */

function loadHomeJavascriptElements() {
    /* called after every ajax paginate */
    handleLikeClickEvent('.post-like');
    handleFavouriteClickEvent('.post-fav');
    initializeCardClickModalOpen();
    $(window).lazyLoadXT();
    $('#marker').lazyLoadXT({visibleOnly: false, checkDuplicates: false});
    $('.tooltipped').tooltip({delay: 50});
    $('#post-fav').on('click', function(e) {
        e.preventDefault(); 
        $('#postmodal').modal('close');
    })
}

function loadPostJavascriptElements(modal, response) {
    /* called whenever a post modal is opened */
    dietlah.postModalOpen = true;
    history.pushState({modal:"open"}, "modal", "#modal");
    handleLikeClickEvent('.full-post-like');
    handleFavouriteClickEvent('.full-post-fav');
    $(modal).find('#post-content img').lazyLoadXT();
    $('ul.tabs').tabs({
        onShow: function(tab) {
            $(tab).find('img').lazyLoadXT();
        }
    });
   $('#modal-close').on('click', function(e) {;
        e.preventDefault(); 
        $('#postmodal').modal('close');
    })
    $('.materialboxed').materialbox();
    $('.tooltipped').tooltip({delay: 50});
    $('.collapsible').collapsible();
    initializeCommentsScroll(response['postId']);
    initializeSubmitComment();
    initializeTagChips(response['userTags']);
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

function ajaxLoadPageFeed(order, range, tags) {
    showNavLoadingBar();
    $.ajax({
        url: dietlah.restUrl + order + "/" + range + "/" + "placeholder" + "/" + String(dietlah.page),
        dataType: "json",
        data: {
            tags: tags
        }
    }).done(function (response) {
        renderCards(grid, response);
        loadHomeJavascriptElements();
        dietlah.page += 1;
        if (!response["hasMore"]) {
            disableInfiniteScroll();
            dietlah.pageEnd = true;
        }
        hideNavLoadingBar();
    }).fail(function(jqXHR, textStatus) {
        paginationFailure(jqXHR, textStatus);
        hideNavLoadingBar();
    });
}

function ajaxLoadComments(postid) {
    //showPostModalLoadingBar();
    start = new Date().getTime();
    $.ajax({
        url: "/rest/comments/" + postid + "/" + start + "/" + dietlah.commentsPage,
        dataType: "json",
    }).done(function (response) {
        renderComments(response);
        loadCommentsJavascriptElements();
        dietlah.commentsPage += 1;
        if (!response["hasMore"]) {
            disableCommentsScroll();
            dietlah.commentsEnd = true;
        }
    }).fail(function(jqXHR, textStatus) {
        paginationFailure(jqXHR, textStatus);
    });
}

function initializeCommentsScroll(postId) {
    dietlah.commentsEnd = false;
    dietlah.commentsPage = 1;
    $('#comments-marker').on('lazyshow', function () {
        ajaxLoadComments(postId);
    }).lazyLoadXT({visibleOnly: false});
}

function initializeInfiniteScroll(order, range, tags) {
    dietlah.pageEnd = false;
    var grid = document.querySelector('#grid');
    $('#marker').on('lazyshow', function () {
        ajaxLoadPageFeed(order, range, tags);
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

function reinitializeInfiniteScroll() {
    dietlah.page = 1;
    if(dietlah.pageEnd){
        $('#marker').lazyLoadXT({visibleOnly: false, checkDuplicates: false});
    }
    var order = $("#post-order-select").val();
    var range = $("#post-range-select").val();
    var tags = $("#post-tag-select").val();
    $('#marker').off();
    $('.end-of-page').hide();
    $('.cards-container').children().html("")
    initializeInfiniteScroll(order, range, tags);
    $.event.trigger("resize"); // shitty hack, to trigger the detection of marker when reloading page
}

function setupPostsFiltering() {
    $('#post-order-select, #post-order-select-mobile').on('change', function(e) {
        $('#post-order-select, #post-order-select-mobile').val(this.value);
        $('#post-order-select, #post-order-select-mobile').material_select();
        reinitializeInfiniteScroll();
    });
    $('#post-range-select, #post-range-select-mobile').on('change', function(e) {
        $('#post-range-select, #post-range-select-mobile').val(this.value);
        $('#post-range-select, #post-range-select-mobile').material_select();
        reinitializeInfiniteScroll();
    });
    $('#post-tag-select, #post-tag-select-mobile').on('change', function(e) {
        $('#post-tag-select, #post-tag-select-mobile').val($(this).val());
        if($(this).attr("id") == "post-tag-select") {
            $('#post-tag-select-mobile').material_select();
        } else {
            $('#post-tag-select').material_select();
        }
        reinitializeInfiniteScroll();
    });
}

function overrideBackButtonForModal(){
    $(window).on('popstate', function() {
        if(dietlah.postModalOpen) {
            $('#postmodal').modal('close');
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
    registerTopTagsView();
}

$(document).ready(function(){
    registerHandleBarsHelpers();
    setupValidationErrorFormatting();
    overrideBackButtonForModal();
    initializeHomeModals();
    handleReportPostSubmit();
    handleReportCommentSubmit();
    handleEditCommentSubmit();
    dietlah.cardTemplate = compileCardTemplate();
    dietlah.commentsTemplate = compileCommentsTemplate();
    $.lazyLoadXT.scrollContainer = '.modal-content';
    initializeInfiniteScroll("new", "all", []);
    setupPostsFiltering();
});