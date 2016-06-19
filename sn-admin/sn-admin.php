<?php
/**
 * Fichero comun del panel de administración.
 */

/** Indica que el usuario esta en el panel de administracion */
define('ADM_PANEL', true);

require dirname(dirname(__FILE__)) . '/sn-load.php';

/*
 * $formSignin solo existe cuando estoy en la pagina login.php,
 * cuando no existe siempre se comprueba si el usuario a iniciado sesión.
 */
if (!isset($formSignin)) {
    SN_Users::checkLogin();
}

$dataTable['option']['rol'] = [
    'admin' => [
        'title' => 'Administrador',
        'rol' => 3,
    ],
    'author' => [
        'title' => 'Autor',
        'rol' => 2,
    ],
    'editor' => [
        'title' => 'Editor',
        'rol' => 1,
    ],
    'user' => [
        'title' => 'Suscriptor',
        'rol' => 0,
    ],
];

/** Guarda la dirección de la carpeta SN-ADMIN/CONTENT */
define('ADM_CONT', ADM . 'content/');

$dataTable['menu']['select'] = SN_Menus::dataList('fetchObject');
$dataTable['menu']['dataList'] = SN_Menus::dataList();
$dataTable['post']['dataList'] = SN_Posts::dataList('fetchAll', 'post');
$dataTable['page']['dataList'] = SN_Posts::dataList('fetchAll', 'page');
$dataTable['comment']['dataList'] = SN_Comments::dataList();
$dataTable['category']['dataList'] = SN_Categories::dataList();
$dataTable['term']['dataList'] = SN_Terms::dataList();
$dataTable['user']['dataList'] = SN_Users::dataList();
$dataTable['sidebar']['dataList'] = SN_Sidebars::dataList();
$dataTable['option']['dataList'] = SN_Options::dataList();
//Numero de filas a mostrar de post, categorias, etiquetas, etc...
$dataTable['option']['numberRows'] = SN_Options::get_instance('optionPaged')->getOption_value();
$dataTable['option']['menu'] = SN_Options::get_instance('optionMenu')->getOption_value();

require ABSPATH . ADM . 'functions.php';