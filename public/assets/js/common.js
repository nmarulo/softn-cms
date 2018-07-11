$(function () {
    $('#btn-navbar-menu-toggle').click(function () {
        navbarToggle();
    });
});

function navbarToggle() {
    $('#navbar-collapse-fixed-top').toggleClass('toggle');
    $('.main-container').toggleClass('toggle');
}

function makeRequest(method, route, dataToSend, callback, parseJSON) {
    $.ajax({
        method: method,
        url: formatterURL(route),
        data: dataToSend
    }).done(function (data, textStatus, jqXHR) {
        callbackRequest(data, callback, parseJSON);
    }).fail(function (jqXHR, textStatus, errorThrown) {
        //TODO: registrar error.
        console.log(jqXHR.statusText + '[' + jqXHR.status + '] ' + jqXHR.responseText);
    });
}

function formatterURL(route) {
    var url = globalURL;
    
    if (url.substr(url.length - 1, 1) != '/') {
        url += '/';
    }
    
    if (route.substr(0, 1) == '/') {
        route = route.substring(1);
    }
    
    return url + route;
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

function viewUpdate(html, dataIdUpdate) {
    if (!Array.isArray(dataIdUpdate)) {
        return;
    }
    
    var currentDocument = $(document);
    var documentHTML = $('<div />').append($.parseHTML(html));
    
    dataIdUpdate.forEach(function (value) {
        var findHTML = documentHTML.find(value).html();
        
        if ($(findHTML).hasClass(messagesClassDiv)) {
            addMessagesContent(findHTML);
        } else {
            currentDocument.find(value).html(findHTML);
        }
    });
}

function createRepresentationDataToSendRequest(name, value, currentDataToSend) {
    if (currentDataToSend == null || !(currentDataToSend instanceof Array)) {
        currentDataToSend = [];
    }
    
    return currentDataToSend.concat({'name': name, 'value': value});
}

function getDataIdUpdateElement(element) {
    var data = element.data('update');
    
    if (data === undefined) {
        return [];
    }
    
    return data.split(" ");
}
