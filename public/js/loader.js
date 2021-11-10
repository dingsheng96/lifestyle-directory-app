function showLoading() {
    $('.loading').show();
}

function hideLoading() {
    $('.loading').hide();
}

$(document).on('submit','form', function() {

    if($(this).hasClass('no-load') || !$(this).hasClass('loading')) {
        $(this).find(":submit").attr("disabled", "disabled");
        return;
    }

    showLoading();
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    beforeSend: function(xhr) {
        showLoading();
    },
});

$(document).ajaxComplete(function() {
    hideLoading();
});