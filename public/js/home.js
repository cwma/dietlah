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

/* page rendering functions */

function compileCardTemplate() {
    var source = $("#card_template").html();
    return Handlebars.compile(source);
}

function compilePostTemplate() {
    var source = $("#post_template").html();
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

function clearPost(modal) {
    $(modal).html("");
    dietlah.postModalOpen = false;
}

/* javascript/ajax handling */

function initializePostModal() {
    postTemplate = compilePostTemplate();
    $('.modal').modal({
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
                loadPostJavascriptElements(modal);
                hideNavLoadingBar();
            }).fail(function(jqXHR, textStatus) {
                paginationFailure(jqXHR, textStatus);
                hideNavLoadingBar();
            });
        },
        complete: function(modal) { 
            clearPost(modal);
        } // Callback for Modal close
    });
}

function initializeCardClickModalOpen() {
    $('.card-image, .card-content').on('click', function() {
        $("a[data-postid="+$(this).attr("data-postid")+"]").click();
    })
}

function initializeSubmitComment() {
   $('#commentForm').on('submit', function(e) {
        e.preventDefault(); 
        var comment = $('#commentForm #comment').val();
        var postId = $('#commentForm #postId').val();
        $.ajax({
            type: "POST",
            url: "/rest/createcomment",
            data: {
                comment:comment,
                postId:postId
            },
            success: function( msg ) {
                Materialize.toast(msg["test"], 4000);
            }
        });
    });
}

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

function loadHomeJavascriptElements() {
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
    initializePostModal();
}

function loadPostJavascriptElements(modal) {
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
    initializeSubmitComment();
}

function disableInfiniteScroll() {
    $('#marker').off();
    $('.end-of-page').fadeIn();
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

function initializeInfiniteScroll(order, range, tags) {
    dietlah.pageEnd = false;
    var grid = document.querySelector('#grid');
    $('#marker').on('lazyshow', function () {
        ajaxLoadPageFeed(order, range, tags);
    }).lazyLoadXT({visibleOnly: false});
}

function setupAjax() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

function reinitializeInfiniteScroll() {
    dietlah.page = 1;
    if(dietlah.pageEnd){
        $('#marker').lazyLoadXT({visibleOnly: false, checkDuplicates: false});
    }
    // var order = $("#post-order-select").val();
    // var range = $("#post-range-select").val();
    // var tags = $("#post-tag-select").val();
    var order = $("#post-order-select option:selected").text();
    var range = $("#post-range-select option:selected").text();
    var tags = $("#post-tag-select option:selected").text();
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

$(document).ready(function(){
    registerDateTimeHelper();
    registerLinkifyHelper();
    overrideBackButtonForModal();
    setupAjax();
    dietlah.cardTemplate = compileCardTemplate();
    $.lazyLoadXT.scrollContainer = '.modal-content';
    initializeInfiniteScroll("new", "all", []);
    setupPostsFiltering();
});