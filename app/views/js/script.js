$(document).ready(function () {
    checkInit();
//    eventsOn();
});

/**
 * Metodo con todos los eventos ON.
 */
function eventsOn() {
    $(document).on('click', 'ul.pagination a', function (e) {
        var li = $(this).closest('li');
        if (!li.hasClass('disabled') &&
                !li.hasClass('active')) {
            //#dataSearch Si existe estoy en la pagina de busqueda.
            if ($(this).closest('#dataSearch').length) {
                reloadDataSearch($(this).data('paged'));
            } else {
                reloadData($(this), $(this).data('paged'));
            }
        }
        e.preventDefault();
    });

    $(document).on('click', 'button.btnAction', function () {
        if (!$(this).hasClass('disabled')) {
            var paged = $(document).find('#goToPage');
            var formData = getFormData($('#formGroup'));
            if (paged.length) {
                var paged = 'paged=' + paged.val();
            } else {
                paged = '';
            }
            formData = '&' + formData;
            reloadData($(this), $(this).data('action') + formData + paged);
        }
    });

    $('.sn-menu').on('hide.bs.collapse', function (e) {
        changeGlyphicon(e.target, 'down', 'up');
    });

    $('.sn-menu').on('show.bs.collapse', function (e) {
        changeGlyphicon(e.target, 'up', 'down');
    });

    //#goToPage Es el identificador del campo input de paginación.
    $(document).on('keyup', '#goToPage', function (e) {
        if (e.keyCode == 13) {
            var dataSearch = $(this).closest('#dataSearch');
            //Compruenba si estoy en la pagina de busqueda.
            if (dataSearch.length) {
                var data = 'paged=' + $(this).val() + '&search=';
                data = data + dataSearch.find('input[name="search"]').val();
                reloadDataSearch(data);
            } else {
                reloadData($(this), 'paged=' + $(this).val());
            }
        }
    });

    //#search_admin Es el identificador del campo input de busqueda.
    $('#search_admin').on('keyup', 'input', function (e) {
        if (e.keyCode == 13) {
            reloadDataSearch('search=' + $(this).val());
        }
    });
}

/**
 * Metodo de configuraciones iniciales.
 */
function checkInit() {
    var collapse = $(document).find('.sn-content').data('collapse');
    if (collapse != undefined) {
        $(collapse).addClass('in');
        changeGlyphicon(collapse, 'up', 'down');
    }
    //Comprueba si hay errores en la estructura de bootstrap
    //javascript:(function(){var s=document.createElement("script");s.onload=function(){bootlint.showLintReportForCurrentDocument([]);};s.src="https://maxcdn.bootstrapcdn.com/bootlint/latest/bootlint.min.js";document.body.appendChild(s)})();

    //Configuración para la libreria TINYMCE.
    tinymce.init({
        selector: 'textarea#textContent',
        height: 500,
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons',
    });
}

/**
 * Metodo que cambia la clase "glyphicon-chevron-up" por "glyphicon-chevron-down" y viceversa.
 * Solo cambia la palabra final, por ejemplo, cambia "up" por "down" de "glyphicon-chevron-up"
 * @param string collapse Identificador del elemento.
 * @param string add 
 * @param string remove
 */
function changeGlyphicon(collapse, add, remove) {
    var span = $(collapse).closest('li').children(':first').children(':last');
    span.removeClass('glyphicon-chevron-' + remove);
    span.addClass('glyphicon-chevron-' + add);
}

/**
 * Metodo que obtiene los datos de los formularios (input, textarea y select) en la pagina
 * @param object element
 * @returns String Retorna los datos en una cadena de texto, ejemplo: "action=edit&id=2".
 */
function getFormData(element) {
    var data = findFormData(element, 'input');
    data = data + findFormData(element, 'textarea');
    data = data + findFormData(element, 'select');
    return data;
}

/**
 * Metodo que buscar todos los campos del formulario segun el tipo
 * pasado por parametro.
 * @param object element
 * @param string find Tipo de campo a buscar
 * @returns String Retorna los datos en una cadena de texto, ejemplo: "action=edit&id=2".
 */
function findFormData(element, find) {
    var data = '';
    //nota:comprobar el comportamiento con un select multiple.
    element.find(find).each(function () {
        data = data + $(this).attr('name') + '=' + $(this).val() + '&';
    });

    return data;
}

/**
 * Metodo que recarga los datos en la pagina de busqueda.
 * @param string data
 */
function reloadDataSearch(data) {
    $.ajax({
        type: 'POST',
        url: 'search.php',
        data: data,
        success: function (data) {
            $(document).find('#snwrap').html(data);
        }
    });
}

/**
 * Metodo que recarga los datos en la pagina.
 * @param object element 
 * @param string sendData Datos a enviar.
 */
function reloadData(element, sendData) {
    var page = element.closest('.sn-content').attr('id');
    $.ajax({
        type: 'POST',
        url: page + '.php',
        data: 'isReloadData=1&' + sendData,
        success: function (data) {
            $("#reloadData").html(data);
        }
    });
}