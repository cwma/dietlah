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
    })
}

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

function renderCards(cardJson, cardTemplate) {
    var div = document.createElement('div');
    div.innerHTML = cardTemplate(cardJson);
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
}

function initializePostModal() {
    postTemplate = compilePostTemplate();
    $('.modal').modal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .5, // Opacity of modal background
        inDuration: 300, // Transition in duration
        outDuration: 200, // Transition out duration
        startingTop: '4%', // Starting top style attribute
        endingTop: '10%', // Ending top style attribute
        ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
            showNavLoadingBar();
            postid = $(trigger).attr('data-postid');
            $.ajax({
                url: "http://dietlah.cwma.me/rest/post/" + postid,
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

function initializeSubmitComment() {
   $('#commentForm').on('submit', function(e) {
        e.preventDefault(); 
        var comment = $('#commentForm #comment').val();
        var postId = $('#commentForm #postId').val();
        console.log(comment);
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

function loadHomeJavascriptElements() {
    $(window).lazyLoadXT();
    $('#marker').lazyLoadXT({visibleOnly: false, checkDuplicates: false});
    $('.tooltipped').tooltip({delay: 50});
    initializePostModal();
}

function loadPostJavascriptElements(modal) {
    $(modal).find('#post-content img').lazyLoadXT();
    $('ul.tabs').tabs({
        onShow: function(tab) {
            $(tab).find('img').lazyLoadXT();
        }
    });
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

function initializeInfiniteScroll() {
    var grid = document.querySelector('#grid');
    var marker = $('#marker');

    var cardTemplate = compileCardTemplate();

    $('#marker').on('lazyshow', function () {

        showNavLoadingBar();

        $.ajax({
            url: dietlah.restUrl + String(dietlah.page),
            dataType: "json"
        }).done(function (response) {
            renderCards(response, cardTemplate);
            loadHomeJavascriptElements();
            dietlah.page += 1;
            if (!response["hasMore"]) {
                disableInfiniteScroll();
            }
            hideNavLoadingBar();
        }).fail(function(jqXHR, textStatus) {
            paginationFailure(jqXHR, textStatus);
            hideNavLoadingBar();
        });
    }).lazyLoadXT({visibleOnly: false});
}

function setupAjax() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

$(document).ready(function(){
    setupAjax();
    $.lazyLoadXT.scrollContainer = '.modal-content';
    registerDateTimeHelper();
    initializeInfiniteScroll();
});