var elementDataTable;
var dataColumn;
var glyphiconTH;
var sortAsc;
var sortDesc;

$(function () {
    sortAsc = 'asc';
    sortDesc = 'desc';
    elementDataTable = $('.container-data-table');
    dataColumn = 'data-column';
    glyphiconTH = '<span class="glyphicon glyphicon-triangle-bottom"></span>';
    
    $(document).on('click', '.pagination-container .pagination li > a', function (event) {
        event.preventDefault();
        var element = $(this);
        
        if (element.parent().hasClass('disabled') || element.parent().hasClass('active')) {
            return false;
        }
        
        initPagination(element);
    });
    
    $(document).find(elementDataTable).on('click', 'table th[' + dataColumn + ']', function () {
        var elementTH = $(this);
        var sort = elementTH.data('sort');
        
        //Cuando es igual a desc lo quita
        if (elementTH.hasClass('active') && sort === sortAsc) {
            //ordenar desc/asc y cambiar glyphicon
            var spanGlyphicon = elementTH.find('span.glyphicon');
            dataListSpanGlyphicon(spanGlyphicon, sort);
            elementTH.data('sort', sortDesc);
        } else if (sort === sortDesc) {
            //Se eliminan todos los datos de la columna
            elementTH.removeAttr('data-sort');
            elementTH.data('sort', '');//$.removeData() no esta funcionando, por eso lo dejo vació.
            elementTH.removeClass('active');
            elementTH.find('span[class*=glyphicon-triangle-]').remove();
        } else {
            //ordenar asc
            elementTH.addClass('active');
            elementTH.attr('data-sort', sortAsc);
            elementTH.data('sort', sortAsc);//Se agrega también, ya que al obtener su valor es nulo si no existe
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
