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
        callbackRequest(data, callback, parseJSON);
    }).fail(function (jqXHR, textStatus, errorThrown) {
        //TODO: registrar error.
        console.log(jqXHR.statusText + '[' + jqXHR.status + '] ' + jqXHR.responseText);
    });
}

function callbackRequest(data, callback, parseJSON) {
    if (callback !== undefined && callback != null) {
        var parseData = data;
        
        if (parseJSON) {
            parseData = JSON.parse(data);
        }
        
        callback(parseData);
    }
}

function getRoute() {
    return document.URL.replace(globalURL, '');
}

function viewUpdate(idBlocks, html) {
    var currentDocument = $(document);
    var documentHTML = $('<div />').append($.parseHTML(html));
    
    idBlocks.split(" ").forEach(function (value) {
        var idBlock = '#' + value;
        var findHTML = documentHTML.find(idBlock).html();
        
        if ($(findHTML).hasClass(messagesClassDiv)) {
            addMessagesContent(findHTML);
        } else {
            currentDocument.find(idBlock).html(findHTML);
        }
    });
}
