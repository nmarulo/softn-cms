$(function () {
    $(document).on('click', '.pagination-container .pagination li > a', function (event) {
        event.preventDefault();
        var element = $(this);
        
        if (element.parent().hasClass('disabled') || element.parent().hasClass('active')) {
            return false;
        }
        
        initPagination(element);
    })
});

function initPagination(element) {
    var page = element.data('page');
    //TODO: eliminar route de la paginación?
    var route = element.data('route');
    var parentDataUpdate = $('.pagination-container').parent('div[data-update]');
    
    if (parentDataUpdate.length > 0) {
        element.data('update', element.data('update') + ' ' + parentDataUpdate.data('update'));
    }
    
    if (page == null) {
        return false;
    }
    
    setCurrentElementTriggeringAction(element);
    
    makeRequest('GET', getRoute(), createDataToSendPagination(page), function (dataHTML) {
        viewUpdate(dataHTML);
    });
}

function getActivePageNumber() {
    return $(document).find('.pagination-container .pagination li.active > a').text();
}

function createDataToSendPagination(page, currentDataToSend) {
    if (page == null) {
        page = getActivePageNumber();
    }
    
    return createRepresentationDataToSendRequest('page', page, currentDataToSend);
}
