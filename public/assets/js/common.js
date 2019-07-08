$(function () {
    callAjax();
    iCheckInputs();
});

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

function makeGetRequest(dataToSend, callback, parseJson) {
    makeRequest('GET', getRoute(), dataToSend, callback, parseJson);
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
        var findHTML;
        //Si contiene ':', buscara en el elemento de la posición 0 (contiene el identificador), todos los elementos de la posición 1
        //EJ: #my-id:.my-class1,.my-class2,.my-class3,#my-id2,table
        if (value.search(':') === -1) {
            findHTML = documentHTML.find(value).html();
            
            if ($(findHTML).hasClass(messagesClassDiv)) {
                addMessagesContent(findHTML);
            } else {
                currentDocument.find(value).html(findHTML);
            }
        } else {
            var valueSplit = value.split(':');
            var valueID = valueSplit[0];
            var currentDocumentFind = currentDocument.find(valueID);
            findHTML = documentHTML.find(valueID);
            
            valueSplit[1].split(',').forEach(function (find) {
                currentDocumentFind.find(find).html(findHTML.find(find).html())
            });
        }
    });
}

function createRepresentationDataToSendRequest(name, value, currentDataToSend) {
    return checkArray(currentDataToSend).concat({'name': name, 'value': value});
}

function elementDataAttr(element, key, value) {
    element.attr('data-' + key, value);
    element.data(key, value);//Se agrega también, ya que al obtener su valor es nulo si no existe
}

function elementDataAttrRemove(element, key) {
    element.removeAttr('data-' + key);
    element.data(key, '');//$.removeData() no esta funcionando, por eso lo dejo vació.
}

function getDataIdUpdateElement(element) {
    var data = element.data('update');
    
    if (data === undefined) {
        return [];
    }
    
    return data.split(" ");
}

function getContainerTableData(element) {
    return element.closest('.container-table-data');
}

function checkArray(array) {
    if (array === undefined || !(array instanceof Array)) {
        return [];
    }
    
    return array;
}

function callAjax() {
    $(document).on('click', '[data-call-ajax]', function () {
        var element = $(this);
        var callAjaxUrl = element.data('call-ajax');
        var method = element.data('call-method');
        var elementUpdate = getDataIdUpdateElement(element);
        var execute = element.data('execute');
        
        makeRequest(method, callAjaxUrl, '', function (data) {
            viewUpdate(data, elementUpdate);
    
            if (execute !== undefined && execute != null) {
                eval(execute)()
            }
        });
    });
}

function iCheckInputs() {
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"], input[type="radio"]').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    })
}
