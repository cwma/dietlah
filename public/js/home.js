function onPageLoad() {

    // mobile menu
    $('.button-collapse').sideNav({
        menuWidth: 300, // Default is 300
        edge: 'right', // Choose the horizontal origin
        closeOnClick: true, // Closes side-nav on <a> clicks, useful for Angular/Meteor
        draggable: true // Choose whether you can drag to open on touch screens
    });

    // navbar dropdown
    $(".dropdown-button").dropdown({
        hover: true
    });
}


$(document).ready(function(){

    onPageLoad();

    var grid = document.querySelector('#grid');
    var marker = $('#marker');

    var source = $("#card_template").html();
    var template = Handlebars.compile(source);

    $('#marker').on('lazyshow', function () {

        $('.progress').show();

        $.ajax({
            url: dietlah.restUrl + String(dietlah.page),
            dataType: "json"
        }).done(function (response) {
            var div = document.createElement('div');
            div.innerHTML = template(response);
            var elements = div.childNodes;
            salvattore.appendElements(grid, elements);
            $(window).lazyLoadXT();
            $('#marker').lazyLoadXT({visibleOnly: false, checkDuplicates: false});
            $('.modal').modal();
            $('.tooltipped').tooltip({delay: 50});
            $('.card').fadeIn();
            dietlah.page += 1;
            if (!response["hasMore"]) {
                $('#marker').off();
                $('.end-of-page').fadeIn();
            }
            $('.progress').hide();
        }).fail(function(jqXHR, textStatus) {
        	console.log(textStatus);
        	console.log(jqXHR);
        	// handle ajax failure here
        });
    }).lazyLoadXT({visibleOnly: false});
});