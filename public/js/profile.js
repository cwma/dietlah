/* handlebars helper functions */

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

function registerTopTagsView() {
    Handlebars.registerHelper("top5", function(index) {
        if (index < 5) {
            return true;
        } else { 
            return false;
        }
    });
}

function registerContainsImage() {
    Handlebars.registerHelper("containsImage", function(image) {
        if (image != "" && image != null) {
            return true;
        } else { 
            return false;
        }
    });
}

function registerMapHelper() {
    Handlebars.registerHelper("containsLoc", function(loc) {
        if (loc != null && loc != "") {
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
    $('.nav-progress').show();
}

function hideNavLoadingBar() {
    $('.nav-progress').hide();
}

function showPostLoadingBar() {
    $('.post-progress').show();
}

function hidePostLoadingBar() {
    $('.post-progress').hide();
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
    $(modal).html('<div class="progress post-progress light-green lighten-4"><div class="indeterminate light-green"></div></div>');
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
            showPostLoadingBar();
            postid = $(trigger).attr('data-postid');
            $.ajax({
                url: "/rest/post/" + postid,
                dataType: "json"
            }).done(function (response) {
                renderPost(modal, response, postTemplate);
                loadPostJavascriptElements(modal, response);
                hidePostLoadingBar();
                if($(trigger).attr('comments') == "yes"){
                    $('ul.tabs').tabs('select_tab', 'post-comments');
                }
            }).fail(function(jqXHR, textStatus) {
                paginationFailure(jqXHR, textStatus);
                hidePostLoadingBar();
            });
        },
        complete: function(modal) { 
            clearPost(modal);
            $('#comments-marker').off();
            history.pushState({modal:"open"}, "modal", "/profile/" + dietlah.profileid);

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
            $("#report-post-submit").prop("disabled", false);
            history.pushState({inner:"#report-post-modal"}, "modal", "post/"+dietlah.currentPostModalId);
            dietlah.reportModalOpen = true;
        },
        complete: function(modal) { 
            $("#reported_id_post").val("");
            history.pushState({modal:"#post-modal"}, "modal", "post/"+dietlah.currentPostModalId);
            dietlah.reportModalOpen = false;
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
            $("#report-comment-submit").prop("disabled", false);
            history.pushState({inner:"#report-post-modal"}, "modal", "post/"+dietlah.currentPostModalId);
            dietlah.reportModalOpen = true;
        },
        complete: function(modal) { 
            $("#reported_id_comment").val("");
            history.pushState({modal:"#post-modal"}, "modal", "post/"+dietlah.currentPostModalId);
            dietlah.reportModalOpen = false;
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
            $("#delete-comment-id").val($(trigger).attr('comment-id'));
            $('#delete-comment-confirm').attr('checked', false);
            $("#edit-comment-submit").prop("disabled", false);
            $("#delete-comment-submit").prop("disabled", false);
            history.pushState({inner:"#report-post-modal"}, "modal", "post/"+dietlah.currentPostModalId);
            dietlah.reportModalOpen = true;
        },
        complete: function(modal) { 
            $("#edit-comment-id").val("");
            $('#edit-comment').val("");
            $('#edit-comment').trigger('autoresize');
            $("#delete-comment-id").val("");
            reinitializeCommentsScroll();
            history.pushState({modal:"#post-modal"}, "modal", "post/"+dietlah.currentPostModalId);
            dietlah.reportModalOpen = false;
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
            showPostLoadingBar();
            $("#report-post-submit").prop("disabled", true);
            $(form).ajaxSubmit({
                error: function(e){
                    hidePostLoadingBar();
                    $("#report-post-submit").prop("disabled", false);
                    Materialize.toast("There was an error attempting to submit this report: " + e.statusText, 4000);
                },
                success: function (data, textStatus, jqXHR, form){
                    hidePostLoadingBar();
                    $("#report-post-submit").prop("disabled", false);
                    if(data['status'] == 'success') {
                        Materialize.toast("Your report has been submitted.", 4000);
                        $(form).resetForm();
                        $(form).find('#report_comment').trigger('autoresize');
                        $('#report-post-modal').modal('close');
                    } else {
                        Materialize.toast("We were not able to send the report", 10000);
                        reasons = data['reason'];
                        for(i=0; i<reasons.length; i++) {
                            Materialize.toast(reasons[i], 10000);
                        }
                    }
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
            showPostLoadingBar();
            $("#report-comment-submit").prop("disabled", true);
            $(form).ajaxSubmit({
                error: function(e) {
                    hidePostLoadingBar();
                    $("#report-comment-submit").prop("disabled", false);
                    Materialize.toast("There was an error attempting to submit this report: " + e.statusText, 4000);
                },
                success: function (data, textStatus, jqXHR, form){
                    hidePostLoadingBar();
                    $("#report-comment-submit").prop("disabled", false);
                    if(data['status'] == 'success') {
                        Materialize.toast("Your report has been submitted.", 4000);
                        $(form).resetForm();
                        $(form).find('#report_comment').trigger('autoresize');
                        $('#report-comment-modal').modal('close');
                    } else {
                        Materialize.toast("We were not able to update the comment", 10000);
                        reasons = data['reason'];
                        for(i=0; i<reasons.length; i++) {
                            Materialize.toast(reasons[i], 10000);
                        }
                    }
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
            showPostLoadingBar();
            $("#create-comment-submit").prop("disabled", true);
            $(form).ajaxSubmit({
                error: function(e){
                    hidePostLoadingBar();
                    $("#create-comment-submit").prop("disabled", false);
                    Materialize.toast("There was an error attempting to submit a comment " + e.statusText, 4000);
                },
                success: function (data, textStatus, jqXHR, form){
                    hidePostLoadingBar();
                    $("#create-comment-submit").prop("disabled", false);
                    if(data['status'] == 'success') {
                        Materialize.toast("your comment has been created!", 4000);
                        $(form).resetForm();
                        $(form).find('#comment').trigger('autoresize');
                        reinitializeCommentsScroll();
                    } else {
                        Materialize.toast("We were not able to create the comment", 10000);
                        reasons = data['reason'];
                        for(i=0; i<reasons.length; i++) {
                            Materialize.toast(reasons[i], 10000);
                        }
                    }
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
            showPostLoadingBar();
            $("#edit-comment-submit").prop("disabled", true);
            $(form).ajaxSubmit({
                error: function(e){
                    hidePostLoadingBar();
                    $("#edit-comment-submit").prop("disabled", false);
                    Materialize.toast("There was an error attempting to update this comment: " + e.statusText, 4000);
                },
                success: function (data, textStatus, jqXHR, form){
                    hidePostLoadingBar();
                    $("#edit-comment-submit").prop("disabled", false);
                    if(data['status'] == 'success') {
                        Materialize.toast("your comment has been updated!", 4000);
                        $(form).resetForm();
                        $(form).find('#edit_comment').trigger('autoresize');
                        $('#edit-comment-modal').modal('close');
                        reinitializeCommentsScroll();
                    } else {
                        Materialize.toast("We were not able to update the comment", 10000);
                        reasons = data['reason'];
                        for(i=0; i<reasons.length; i++) {
                            Materialize.toast(reasons[i], 10000);
                        }
                    }
                }
            });
        }
    }); 
}


function handleDeleteCommentSubmit() {
    $('#delete-comment-form').validate({
        rules: {
            comment_id: "required",
            confirm: "required",
        }, 
        messages: {
            confirm: 'you must check this box to delete this comment'
        },
        submitHandler: function(form) {
            showPostLoadingBar();
            $("#delete-comment-submit").prop("disabled", true);
            $(form).ajaxSubmit({
                error: function(e){
                    hidePostLoadingBar();
                    $("#delete-comment-submit").prop("disabled", false);
                    Materialize.toast("There was an error attempting to delete this comment: " + e.statusText, 4000);
                },
                success: function (data, textStatus, jqXHR, form){
                    hidePostLoadingBar();
                    $("#delete-comment-submit").prop("disabled", false);
                    if(data['status'] == "success") {
                        Materialize.toast(data["response"], 4000);
                        $('#edit-comment-modal').modal('close');
                    } else {
                        Materialize.toast(data["reason"], 4000);
                    }
                }
            });
        }
    }); 
}

function handleSuggestTagsSubmit() {
    $('#suggest-tags').submit(function(){
        showPostLoadingBar();
        $(this).ajaxSubmit({
            data : {
                tags: $('#suggested-tags').materialtags('items')
            },
            error: function(e){
                hidePostLoadingBar();
                Materialize.toast("There was an error attempting to suggest tags: " + e.statusText, 4000);
            },
            success: function (data, textStatus, jqXHR, form){
                hidePostLoadingBar();
                if(data['status'] == "success") {
                    Materialize.toast(data["response"], 4000);
                } else {
                    Materialize.toast(data["reason"], 4000);
                }
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
    dietlah.loc = response['loc'];
    console.log(response['loc']);
    initMaps();
    dietlah.postModalOpen = true;
    dietlah.currentPostModalId = response['id'];
    history.pushState({modal:"open"}, "modal", "/post/" + dietlah.currentPostModalId);
    handleLikeClickEvent('.full-post-like');
    handleFavouriteClickEvent('.full-post-fav');
    $(modal).find('#post-content img').lazyLoadXT();
    $('ul.tabs').tabs({
        onShow: function(tab) {
            $(tab).find('img').lazyLoadXT();
            $.event.trigger("resize"); // shitty hack, to trigger the detection of marker when reloading page
            if($(tab).attr('id') == "post-content") {
                initMaps();
            }
        }
    });
   $('#modal-close').on('click', function(e) {;
        e.preventDefault(); 
        $('#postmodal').modal('close');
    })
    $('.materialboxed').materialbox();
    $('.tooltipped').tooltip({delay: 50});
    $('.collapsible').collapsible();
    initializeCommentsScroll();
    initializeSubmitComment();
    initializeTagChips(response['user_tags']);
    handleSuggestTagsSubmit();
    FB.XFBML.parse();
    twttr.widgets.load();
}

function loadCommentsJavascriptElements(){
    $(".comments-list").find('img').lazyLoadXT();
    $('#comments-marker').lazyLoadXT({visibleOnly: false, checkDuplicates: false});
    $('.tooltipped').tooltip({delay: 50});
}

function disableInfiniteScroll() {
    $('#marker').off();
    $('.end-of-page').fadeIn();
    dietlah.pageScrollDisabled = true;
}

function disableCommentsScroll() {
    $('#comments-marker').off();
    $('.end-of-comments').fadeIn();
    dietlah.commentScrollDisabled = true;
}

function paginationFailure(jqXHR, textStatus) {
    console.log(textStatus);
    console.log(jqXHR);
}

function ajaxLoadPageFeed() {
    if (dietlah.nextPage != null) {
        showNavLoadingBar();
        $.ajax({
            url: dietlah.nextPage,
            dataType: "json"
        }).done(function (response) {
            renderCards(grid, response);
            loadHomeJavascriptElements();
            dietlah.page += 1;
            dietlah.nextPage = response["next"];
            hideNavLoadingBar();
        }).fail(function(jqXHR, textStatus) {
            paginationFailure(jqXHR, textStatus);
            hideNavLoadingBar();
        });
    } else {
        disableInfiniteScroll();
    }
}

function ajaxLoadPageFeedSearch(search) {
    if (dietlah.nextPage != null) {
        showNavLoadingBar();
        $.ajax({
            url: dietlah.nextPage,
            dataType: "json",
            data: {
                search: search
            }
        }).done(function (response) {
            renderCards(grid, response);
            loadHomeJavascriptElements();
            dietlah.page += 1;
            dietlah.nextPage = response["next"];
            hideNavLoadingBar();
        }).fail(function(jqXHR, textStatus) {
            paginationFailure(jqXHR, textStatus);
            hideNavLoadingBar();
        });
    } else {
        disableInfiniteScroll();
    }
}

function ajaxLoadComments(postid) {
    if(dietlah.nextComments != null) {
        showPostLoadingBar();
        $.ajax({
            url: dietlah.nextComments,
            dataType: "json",
        }).done(function (response) {
            hidePostLoadingBar();
            renderComments(response);
            loadCommentsJavascriptElements();
            dietlah.nextComments = response["next"]
        }).fail(function(jqXHR, textStatus) {
            hidePostLoadingBar();
            paginationFailure(jqXHR, textStatus);
        });
    } else {
        disableCommentsScroll();
    }
}

function initializeCommentsScroll() {
    dietlah.commentScrollDisabled = false;
    dietlah.nextComments = "/rest/comments/" + dietlah.currentPostModalId
    $('#comments-marker').on('lazyshow', function () {
        ajaxLoadComments(dietlah.currentPostModalId);
    }).lazyLoadXT({visibleOnly: false});
}

function initializeInfiniteScroll() {
    dietlah.pageScrollDisabled = false;
    dietlah.nextPage = "/rest/profile/" + dietlah.profileid;
    var grid = document.querySelector('#grid');
    $('#marker').on('lazyshow', function () {
        ajaxLoadPageFeed();
    }).lazyLoadXT({visibleOnly: false});
}

function reinitializeCommentsScroll() {
    if(dietlah.commentScrollDisabled){
        $('#comments-marker').lazyLoadXT({visibleOnly: false, checkDuplicates: false});
    }
    $('#comments-marker').off();
    $('.end-of-comments').hide();
    $('.comments-list').html("")
    initializeCommentsScroll();
    $.event.trigger("resize"); // shitty hack, to trigger the detection of marker when reloading page
}

function overrideBackButtonForModal(){
    $(window).on('popstate', function() {
        if(dietlah.postModalOpen) {
            if(dietlah.reportModalOpen) {
                $("#report-post-modal").modal('close');
                $("#report-comment-modal").modal('close');
                $("#edit-comment-modal").modal('close');
            } else {
                $("#postmodal").modal('close');
            }
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
        if(event.item.length < 3 || event.item.length > 20) {
            event.cancel = true;
        }
    });
    for(i in userTags) {
        $('#suggested-tags').materialtags('add', userTags[i]);
    }
}

function registerHandleBarsHelpers() {
    registerLinkifyHelper();
    registerTopTagsView();
    registerContainsImage();
    registerCanEditHelper();
    registerMapHelper();
}

function initMaps() {

    var map;
    var marker;
    var infowindow;
    var messagewindow;

    if (dietlah.loc != null && dietlah.loc != "") {
        try {
            pos = {lat: parseFloat(dietlah.loc.split(",")[0]), lng:parseFloat(dietlah.loc.split(",")[1])};
            map = new google.maps.Map(document.getElementById('map'), {
                center: pos,
                zoom: 16
            });
            var marker = new google.maps.Marker({
              position: pos,
              map: map
            });
        } catch (err) {
            console.log(err);
        }
    } 
}

$(document).ready(function(){
    registerHandleBarsHelpers();
    setupValidationErrorFormatting();
    overrideBackButtonForModal();
    initializeHomeModals();
    handleReportPostSubmit();
    handleReportCommentSubmit();
    handleEditCommentSubmit();
    handleDeleteCommentSubmit();
    dietlah.cardTemplate = compileCardTemplate();
    dietlah.commentsTemplate = compileCommentsTemplate();
    $.lazyLoadXT.scrollContainer = '.modal-content';
    initializeInfiniteScroll();
});