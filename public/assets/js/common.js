$(function () {
    $('#btn-navbar-menu-toggle').click(function () {
        navbarToggle();
    });
});

function navbarToggle() {
    $('#navbar-collapse-fixed-top').toggleClass('toggle');
    $('.main-container').toggleClass('toggle');
}

function makeRequest(method, route, data, callback, parseJSON) {
    $.ajax({
        method: method,
        url: globalURL + route,
        data: data
    }).done(function (data, textStatus, jqXHR) {
        console.log('OK');
    }).fail(function (jqXHR, textStatus, errorThrown) {
        //TODO: registrar error.
        console.log(jqXHR.statusText + '[' + jqXHR.status + '] ' + jqXHR.responseText);
    });
}
