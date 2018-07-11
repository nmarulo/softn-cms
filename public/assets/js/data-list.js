var glyphiconTH;
var sortAsc;
var sortDesc;

$(function () {
    sortAsc = 'asc';
    sortDesc = 'desc';
    glyphiconTH = '<span class="glyphicon glyphicon-triangle-bottom"></span>';
    
    $(document).on('click', '.pagination-container .pagination li > a', function (event) {
        event.preventDefault();
        var element = $(this);
        
        if (element.parent().hasClass('disabled') || element.parent().hasClass('active')) {
            return false;
        }
        
        initPagination(element);
    });
    
    $(document).on('click', 'table th[data-column]', function () {
        var elementTH = $(this);
        var sort = elementTH.data('sort');
        var columnName = elementTH.data('column');
        
        //Cuando es igual a desc lo quita
        if (elementTH.hasClass('active') && sort === sortAsc) {
            //ordenar desc/asc y cambiar glyphicon
            dataListSpanGlyphicon(elementTH.find('span.glyphicon'), sort);
            elementDataAttr(elementTH, 'sort', sortDesc);
        } else if (sort === sortDesc) {
            //Se eliminan todos los datos de la columna
            elementDataAttrRemove(elementTH, 'sort', '');
            elementTH.removeClass('active');
            elementTH.find('span[class*=glyphicon-triangle-]').remove();
        } else {
            //ordenar asc
            elementTH.addClass('active');
            elementDataAttr(elementTH, 'sort', sortAsc);
            elementTH.prepend(glyphiconTH);
        }
        
        
    });
});

function dataListSpanGlyphicon(spanGlyphicon, sort) {
    if (sort === sortAsc) {
        spanGlyphicon.removeClass('glyphicon-triangle-bottom');
        spanGlyphicon.addClass('glyphicon-triangle-top');
    } else if (sort === sortDesc) {
        spanGlyphicon.removeClass('glyphicon-triangle-top');
        spanGlyphicon.addClass('glyphicon-triangle-bottom');
    }
}

function initPagination(element) {
    var page = element.data('page');
    
    if (page == null) {
        return false;
    }
    
    //TODO: eliminar route de la paginación?
    var route = element.data('route');
    var elementParent = element.closest('.pagination-container').parent('div[data-update]');
    
    makeRequest('GET', getRoute(), createDataToSendPagination(page), function (dataHTML) {
        viewUpdate(dataHTML, getDataIdUpdateElement(elementParent));
    });
}

function getActivePageNumber(element) {
    return element.find('.pagination-container:eq(0) .pagination li.active > a').text();
}

function createDataToSendPagination(page, currentDataToSend) {
    return createRepresentationDataToSendRequest('page', page, currentDataToSend);
}
