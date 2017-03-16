function compileCardTemplate() {
    var source = $("#card_template").html();
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

function loadJavascriptElements() {
    $(window).lazyLoadXT();
    $('#marker').lazyLoadXT({visibleOnly: false, checkDuplicates: false});
    $('.modal').modal();
    $('.tooltipped').tooltip({delay: 50});
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
            loadJavascriptElements();
            dietlah.page += 1;
            if (!response["hasMore"]) {
                disableInfiniteScroll();
            }
            hideNavLoadingBar();
        }).fail(function(jqXHR, textStatus) {
            paginationFailure(jqXHR, textStatus);
        });
    }).lazyLoadXT({visibleOnly: false});
}


$(document).ready(function(){
    initializeInfiniteScroll();
});