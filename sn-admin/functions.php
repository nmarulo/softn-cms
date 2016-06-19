<?php

/**
 * Funciones generales del panel de administración.
 */

/**
 * Metodo que reemplaza los empacio por el simbolo "_" de una cadena de texto.
 * @param string $str
 * @return string
 */
function str_filter($str){
    return str_replace(' ', '_', $str);
}

/**
 * Metodo que retorna un lista con los nombres de las carpetas del directorio
 * "sn-content/themes/"
 * @param string $select Nombre del directorio seleccionado.
 * @return string Lista de opciones para el formulario "select"
 */
function getThemes($select) {
    $dirThemes = array_diff(scandir(ABSPATH . 'sn-content/themes/'), ['..', '.', 'index.php']);
    $out = '';
    foreach ($dirThemes as $dir) {
        if ($select == $dir) {
            $out .= "<option selected>$dir</option>";
        } else {
            $out .= "<option>$dir</option>";
        }
    }
    return $out;
}

/**
 * Metodo que obtiene el numero del rol o un array asociativo con el
 * titulo y el numero del rol.
 * @global array $dataTable
 * @param string $rol Nombre del rol a buscar.
 * @param boolean $isOnlyRol Si es false retornara un array
 * @return int|array
 */
function getRol($rol, $isOnlyRol = true) {
    global $dataTable;
    $out = $dataTable['option']['rol'][$rol];
    return $isOnlyRol ? $out['rol'] : $out;
}

/**
 * Metodo que obtiene el titulo del rol.
 * @global array $dataTable
 * @param string $rol
 * @return string
 */
function getRolName($rol) {
    global $dataTable;
    $out = array_column($dataTable['option']['rol'], 'title', 'rol');
    return $out[$rol];
}

/**
 * Metodo que imprime los estilos usados en el panel de administación.
 */
function getStyles() {
    $out = '<link href="' . siteUrl() . 'sn-includes/css/bootstrap.css" rel="stylesheet">';
    $out .= '<link href="' . siteUrl() . 'sn-admin/css/style.css" rel="stylesheet">';
    echo $out;
}

/**
 * Metodo que imprime los scripts usados en el panel de administración.
 */
function getScripts() {
    $out = '<script src="' . siteUrl() . 'sn-includes/js/jquery-1.12.0.js" type="text/javascript"></script>';
    $out .= '<script src="' . siteUrl() . 'sn-includes/js/bootstrap.min.js"></script>';
    $out .= '<script src="' . siteUrl() . 'sn-includes/js/tinymce/tinymce.min.js"></script>';
    $out .= '<script src="' . siteUrl() . 'sn-includes/js/script.js" type="text/javascript"></script>';
    echo $out;
}

/**
 * Retorna la fecha actual.
 * @param string $format Formato de la fecha.
 * @return string
 */
function getDate_now($format = 'Y-m-d H:i:s') {
    return date($format);
}

/**
 * Muestra u obtiene el titulo de la pagina.
 * @global array $dataTable
 * @param bool $echo Si es true, imprime el titulo del sitio.
 * @return mixed
 */
function siteTitle($echo = false) {
    global $dataTable;
    if ($echo) {
        echo $dataTable['option']['siteTitle'];
    } else {
        return $dataTable['option']['siteTitle'];
    }
}

/**
 * Muestra u obtiene la direccion principal de la pagina.
 * @global type $dataTable
 * @param boolean $echo Si es true, imprime la dirección del sitio.
 * @return mixed
 */
function siteUrl($echo = false) {
    global $dataTable;

    if ($echo) {
        echo $dataTable['option']['siteUrl'];
    } else {
        return $dataTable['option']['siteUrl'];
    }
}

/**
 * Metodo que incluye el encabezado del panel de administración.
 * @param string $page Extención del encabezado, en caso de 
 * existir diferentes encabezados.<br/>
 * EJ: header-{$page}.php => header-login.php
 * @param string $title Titulo de la pagina.
 */
function get_header($page = '', $title = '') {
    $file = 'header';
    $file .= empty($page) ? '.php' : "-$page.php";
    require ABSPATH . ADM_CONT . $file;
}

/*
 * Metodo que incluye el controlador del menu del panel de administración.
 */

function get_sidebar() {
    require ABSPATH . ADM . 'admin-menu.php';
}

/**
 * Metodo de incluye el pie de pagina del panel de administración.
 * @param string $page Extención del pie de pagina, en caso de 
 * existir diferentes pies de pagina.<br/>
 * EJ: footer-{$page}.php => footer-login.php
 */
function get_footer($page = '') {
    $file = 'footer';
    $file .= empty($page) ? '.php' : "-$page.php";
    require ABSPATH . ADM_CONT . 'footer.php';
}

/**
 * Metodo que imprime los creditos y la version en el panel de administración.
 */
function get_credits() {
    ?>
    <div id="footer" class="clearfix">
        <hr class=""/>
        <p class="pull-left">SoftN CMS</p>
        <p class="pull-right">versión <?php echo VERSION; ?></p>
    </div>
    <?php
}

/**
 * Metodo que calcula el indice inicial y final de la pagina. <b>NOTA: Esta función
 * va junto con pagedNav()</b>
 * @param array $arg Lista de opciones.
 * <ul>
 *      <li>$numberRows Numero de filas a mostrar.</li>
 *      <li>$pagedNow Pagina actual.</li>
 *      <li>$countData Todal de filas o datos.</li>
 * </ul>
 * @return array 
 * <ul>
 *      <li>$countPages Total de paginas a mostrar.</li>
 *      <li>$pagedNow</li>
 *      <li>$countData</li>
 *      <li>$beginRow Inidice inicial de los datos a mostrar.</li>
 *      <li>$endRow Inidice final.</li>
 * </ul>
 */
function pagedNavArg($arg) {
    //Datos de retorno por defecto para los casos donde "COUNTDATA" es 0.
    $out = [
        'countPages' => 0,
        'beginRow' => 0,
        'endRow' => 0,
    ];
    //Compruebo que hay datos.
    if ($arg['countData']) {
        global $dataTable;
        $default = [
            'numberRows' => $dataTable['option']['numberRows'], //Numero de filas a mostrar
            'pagedNow' => 1, //Pagina actual
            'countData' => 1, //Total de filas o datos en la base de datos.
        ];

        $default = array_merge($default, $arg);

        /*
         * Compruebo que el numero de datos a mostrar por pagina
         * no sea mayor al total de filas.
         */
        $numberRows = $default['numberRows'] > $default['countData'] ? $default['countData'] : $default['numberRows'];
        //Total de paginas posibles.
        $countPages = ceil($default['countData'] / $numberRows);
        /*
         * Pagina actual solicitada, compruebo que sea valida, 
         * de lo contrario asigno la pagina 1.
         */
        $page = empty($default['pagedNow']) || intval($default['pagedNow']) <= 0 ? 1 : $default['pagedNow'];
        /*
         * Se realiza un segunda comprobación, si es mayor al total 
         * de paginas, obtengo la ultima pagina.
         */
        $page = $page > $countPages ? $countPages : $page;
        // Obtengo la posicion del inicio de la fila
        $beginRow = ($numberRows * $page) - $numberRows;
        //Compruebo si quedan menos filas de las que se pueden mostrar
        $endRow = $beginRow + $numberRows > $default['countData'] ? $default['countData'] : $beginRow + $numberRows;

        $out = [
            'countPages' => $countPages, //Total de paginas posibles.
            'pagedNow' => $page, //Pagina actual
            'countData' => $default['countData'], //Total de filas o datos en la base de datos.
            'beginRow' => $beginRow, //Posicion de inicio de los datos ha mostrar
            'endRow' => $endRow, //Posicion final
        ];
    }

    return $out;
}

/**
 * Metodo que muestra un campo para ir directemente a una pagina y la lista de paginación.
 * @param array $arg
 * <ul>
 *      <li>$countPages Total de paginas a mostrar.</li>
 *      <li>$getPost Datos a mantener.</li>
 *      <li>$pagedNow Pagina actual.</li>
 * </ul>
 */
function pagedNav($arg) {
    $echo = '';
    $paged = '';
    $page = 0;
    /*
     * Representa el numero de paginas que mostrara
     * hacia el lado derecho e izquierdo de la pagina seleccionada.
     */
    $maxShowPage = 3;
    $default = [
        'countPages' => 1,
        'getPost' => '',
        'pagedNow' => 0,
    ];

    //Debe haber más de 1 pagina para mostrar la paginación.
    if ($arg['countPages'] > 1) {
        $default = array_merge($default, $arg);

        $default['getPost'] = empty($default['getPost']) ? '' : '&' . $default['getPost'];

        //En caso de que se ingrese una pagina invalida.
        if ($default['pagedNow'] > $default['countPages']) {
            $default['pagedNow'] = $default['countPages'];
        } elseif (empty($default['pagedNow'])) {
            $default['pagedNow'] = 1;
        }

        $echo .= '<div class="row pagination-content"><div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">Pagina</span>
                    <input id="goToPage" title="Presione Enter para continuar." class="form-control" type="number" name="pagination_num" min="1" value="' . $default['pagedNow'] . '">
                    <input type="hidden" name="search" value="' . $default['getPost'] . '" id="goToPageHide">
                </div></div>
                <nav class="col-md-8"><nav><ul class="pagination clearfix">';
        // Signo flecha izquierda.
        if ($default['pagedNow'] == 1) {
            $echo .= '<li class="disabled"><a href="#"><span>&laquo;</span></a></li>';
        } else {
            $page = ($default['pagedNow'] - 1);
            $paged = 'paged=' . $page . $default['getPost'];
            $echo .= '<li><a data-paged="' . $paged . '" href="#"><span>&laquo;</span></a></li>';
        }

        /*
         * Para evitar los casos donde el total de paginas es demaciado grande
         * se emplea un maximo de paginas a mostrar ($maxShowPage).
         */

        $auxBeginNumPage = $default['pagedNow'] - $maxShowPage;
        //Compruebo si estoy a una distancia valida de la pagina 1.
        $beginNumPage = $auxBeginNumPage > 0 ? $auxBeginNumPage : 1;

        $auxEndNumPage = $default['pagedNow'] + $maxShowPage;
        //Compruebo si estoy a una distancia valida de la ultima pagina.
        $endNumPage = $auxEndNumPage < $default['countPages'] ? $auxEndNumPage : $default['countPages'];

        /*
         * Para mostrar siempre el mismo numero de paginas,
         * si $auxBeginNumPage es menor o igual a 0, obtengo 
         * su valor positivo para sumarlo al valor de la posicion
         * de la ultima pagina.
         */
        if ($auxBeginNumPage <= 0) {
            $endNumPage += abs($auxBeginNumPage) + 1;
        } elseif ($auxEndNumPage >= $default['countPages']) {
            /*
             * Para seguir mostrando el mismo numero de paginas
             * las paginas que sobran (de la operación siguiente) se las 
             * resto a la posición de la pagina de inicio.
             */
            $beginNumPage -= $auxEndNumPage - $default['countPages'];
            //Compruebo que la pagina de inicio sea una posicion valida.
            $beginNumPage = $beginNumPage >= 1 ? $beginNumPage : 1;
        }

        //Obtengo las paginas.
        for ($i = $beginNumPage; $i <= $default['countPages'] && $i <= $endNumPage; ++$i) {
            //Guardo la información que se obtendra por JS.
            $paged = 'paged=' . $i . $default['getPost'];
            //Si $i es menor o igual a 9 le concateno el 0.
            $istr = $i <= 9 ? '0' . $i : $i;

            //Si $i es igual a la pagina actual, le asigna la clase "ACTIVE" al "LI".
            if ($default['pagedNow'] == $i) {
                $echo .= '<li class="active"><a data-paged="' . $paged . '" href="#">' . $istr . '</a></li>';
            } else {
                $echo .= '<li><a data-paged="' . $paged . '" href="#">' . $istr . '</a></li>';
            }
        }

        // Signo flecha izquierda
        if ($default['pagedNow'] == $default['countPages']) {
            $echo .= '<li class="disabled"><a href="#"><span>&raquo;</span></a></li>';
        } else {
            $page = ($default['pagedNow'] + 1);
            $paged = 'paged=' . $page . $default['getPost'];
            $echo .= '<li><a data-paged="' . $paged . '" href="#"><span>&raquo;</span></a></li>';
        }
        $echo .= '</ul></nav></div>';
        echo $echo;
    }
}

/**
 * Metodo que redirecciona a la pagina indicada.
 * @param string $page Nombre de la pagina.
 * @param string $path Ruta. Por defecto busca en la pagina principal.
 */
function redirect($page = 'index', $path = '') {
    header('Location: ' . siteUrl() . $path . "$page.php");
    exit();
}
