var glyphiconTH;
var sortAsc;
var sortDesc;

$(function () {
    sortAsc = 'asc';
    sortDesc = 'desc';
    glyphiconTH = '<span class="glyphicon glyphicon-triangle-bottom"></span>';
    
    initTableDataPagination();
    initTableDataSortColumn();
    initTableDataHideColumns();
    initTableDataForm('form-search-table-data');
    initTableDataForm('form-filter-table-data');
});

function initTableDataForm(classForm) {
    $(document).on('submit', 'form.' + classForm, function (event) {
        event.preventDefault();
        tableDataRequest($(this));
    });
}

function initTableDataHideColumns() {
    $(document).on('change', '.container-hide-columns input[type=checkbox]', function () {
        var element = $(this);
        var columnName = element.attr('name');
        var tableData = getContainerTableData(element).find('table');
        var show = !element.is(':checked');
        var posColumn = tableData.find('thead > tr > th[data-column=' + columnName + ']').index();
        
        tableShowHideColumns(tableData, ++posColumn, show);
    });
}

function tableShowHideColumns(table, pos, show) {
    var columns = table.find('tr > td:nth-child(' + pos + '), tr > th:nth-child(' + pos + ')');
    
    if (show) {
        columns.removeClass('hidden');
    } else {
        columns.addClass('hidden');
    }
}

function initTableDataPagination() {
    $(document).on('click', '.container-pagination .pagination li > a', function (event) {
        event.preventDefault();
        var elementA = $(this);
        var elementParent = elementA.parent();
        var dataPage = elementA.data('page');
        
        if (elementParent.hasClass('disabled') || elementParent.hasClass('active') || dataPage === undefined) {
            return;
        }
        
        if (elementA.data('type') === 'arrow') {
            elementParent = elementA.closest('.pagination')
                .find('a[data-type=page][data-page=' + dataPage + ']')
                .parent();
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

function createDataToSendForm(elementForm, classForm, currentDataToSend) {
    var element = elementForm;
    var dataToSend = checkArray(currentDataToSend);
    
    if (!elementForm.hasClass(classForm)) {
        element = getContainerTableData(elementForm).find('form.' + classForm);
    }
    
    var input = element.find('input');
    
    if (input.length === 0 || input.val().length === 0) {
        return dataToSend;
    }
    
    return dataToSend.concat(element.serializeArray());
}

function getActivePageNumber(elementTrigger) {
    var containerPagination = elementTrigger.closest('.container-pagination');
    
    if (containerPagination.length === 0) {
        containerPagination = getContainerTableData(elementTrigger).find('.container-pagination:eq(0)');
    }
    
    return containerPagination.find('.pagination li.active > a').text();
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
    var dataToSend = createDataToSendPagination(getActivePageNumber(elementTrigger));
    //Obtener todas las columnas y su correspondiente orden
    dataToSend = createDataToSendSortColumn(containerTableData, dataToSend);
    dataToSend = createDataToSendForm(elementTrigger, 'form-search-table-data', dataToSend);
    dataToSend = createDataToSendForm(elementTrigger, 'form-filter-table-data', dataToSend);
    
    //realizar una petición para actualizar los datos de la tabla
    makeGetRequest(dataToSend, function (dataHTML) {
        viewUpdate(dataHTML, getDataIdUpdateElement(containerTableData));
        updateTableBodyHideColumns();
    });
}

function updateTableBodyHideColumns() {
    var table = $(document).find('.container-table-data table > thead > tr > th.hidden').closest('table');
    
    table.find('thead > tr > th.hidden').each(function () {
        table.find('tbody > tr > td:nth-child(' + ($(this).index() + 1) + ')').addClass('hidden');
    });
}
