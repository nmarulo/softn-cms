var glyphiconTH;
var sortAsc;
var sortDesc;

$(function () {
    sortAsc = 'asc';
    sortDesc = 'desc';
    glyphiconTH = '<span class="glyphicon glyphicon-triangle-bottom"></span>';
    
    initTableDataPagination();
    initTableDataSortColumn();
});

function initTableDataPagination() {
    $(document).on('click', '.container-pagination .pagination li > a', function (event) {
        event.preventDefault();
        var elementA = $(this);
        var elementParent = elementA.parent();
        
        if (elementParent.hasClass('disabled') || elementParent.hasClass('active') || elementA.data('page') === undefined) {
            return;
        }
        
        elementA.closest('.pagination').find('li').removeClass('active');
        elementParent.addClass('active');//Le agrego la clase, para que "tableDataRequest" busque correctamente.
        
        tableDataRequest(elementA);
    });
}

function initTableDataSortColumn() {
    $(document).on('click', 'table th[data-column]', function () {
        var elementTH = $(this);
        var sort = elementTH.data('sort');
        
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
        
        tableDataRequest(elementTH);
    });
}

function getActivePageNumber(element) {
    return element.find('.container-pagination:eq(0) .pagination li.active > a').text();
}

function createDataToSendPagination(page, currentDataToSend) {
    if (page === undefined || page.length === 0) {
        return checkArray(currentDataToSend);
    }
    
    return createRepresentationDataToSendRequest('page', page, currentDataToSend);
}

function createDataToSendSortColumn(containerTableData, currentDataToSend) {
    var value = getSortColumns(containerTableData);
    
    if (value.length === 0) {
        return checkArray(currentDataToSend);
    }
    
    return createRepresentationDataToSendRequest('sortColumn', JSON.stringify(value), currentDataToSend);
}

function getSortColumns(containerTableData) {
    var sortColumns = [];
    var elementTH = containerTableData.find('table thead > tr > th[data-column][data-sort]');
    
    elementTH.each(function () {
        sortColumns = sortColumns.concat({'column': $(this).data('column'), 'sort': $(this).data('sort')});
    });
    
    return sortColumns;
}

function dataListSpanGlyphicon(spanGlyphicon, sort) {
    if (sort === sortAsc) {
        spanGlyphicon.removeClass('glyphicon-triangle-bottom');
        spanGlyphicon.addClass('glyphicon-triangle-top');
    } else if (sort === sortDesc) {
        spanGlyphicon.removeClass('glyphicon-triangle-top');
        spanGlyphicon.addClass('glyphicon-triangle-bottom');
    }
}

function tableDataRequest(elementTrigger) {
    var containerTableData = getContainerTableData(elementTrigger);
    var dataToSend = createDataToSendPagination(getActivePageNumber(containerTableData));
    //Obtener todas las columnas y su correspondiente orden
    dataToSend = createDataToSendSortColumn(containerTableData, dataToSend);
    console.table(dataToSend);
    
    //realizar una petición enviando la pagina activa y las columnas que serán ordenadas
    makeGetRequest(dataToSend, function (dataHTML) {
        viewUpdate(dataHTML, getDataIdUpdateElement(containerTableData));
    });
}