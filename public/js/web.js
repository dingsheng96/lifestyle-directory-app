$(document).on('focus', '.phone_format', function () {
    var value = $(this).val();
    if(value == "" || value == null || value.substring(0, 4) != "+60") {
        $(this).val("+60");
        return;
    }
});

$(document).bind('scroll',function(e){
    $('section').each(function(){
        if ($(this).offset().top < $(window).scrollTop() + 1
        && $(this).offset().top + $(this).height() > $(window).scrollTop() + 1) {
            window.history.replaceState(null, null, "#" + $(this).attr('id'));
        }
    });
});

$(document).ready(function () {

    if(excludeHash() > -1) {
        return;
    }

    var hash = window.location.hash.substr(1);
    if(hash == null || hash == "") {
        window.history.replaceState(null, null, "#home");
        return;
    }

    if((hash != null || hash != "") && hash != 'home') {
        scrollToDiv(hash);
        return;
    }
});

$(document).on('click', '.navbar-nav .nav-link', function (e) {

    if(excludeHash() > -1) {
        return;
    }

    e.preventDefault();

    var href = $(this).attr('href');
    var index = href.indexOf('#');

    if(index != -1) {
        var div = href.substring(index + 1);
        scrollToDiv(div);
    }
});

function scrollToDiv(id) {
    $('html,body').animate({
        scrollTop: $("#" + id).offset().top
    }, 500);
}

function excludeHash() {
    var exclusions = ['register', 'privacy-policy', 'term-condition'];
    var prefix = (window.location.pathname).split("/");

    return $.inArray(prefix.slice(-1)[0], exclusions);
}