<?php

/**
 * Configuraciones.
 * Define contantes, define variables globales e incluye los ficheros necesarios
 * para el funcionamiento de la aplicación.
 * @package SoftN-CMS
 */
/** Guarda la dirección de la carpeta INCLUDES. */
define('INC', 'sn-includes' . DIRECTORY_SEPARATOR);
/** Guarda la dirección de la carpeta SN-ADMIN. */
define('ADM', 'sn-admin' . DIRECTORY_SEPARATOR);
/** Guarda la dirección de la carpeta SN-CONTENT. */
define('CONT', 'sn-content'  . DIRECTORY_SEPARATOR);
/** Guarda la dirección de la carpeta THEMES. */
define('THEMES', CONT . 'themes'  . DIRECTORY_SEPARATOR);
/** Versión del actual del CMS. */
define('VERSION', '0.1-beta');

require ABSPATH . INC . 'messages.php';
require ABSPATH . INC . 'sn-db.php';
require ABSPATH . INC . 'sn-categories.php';
require ABSPATH . INC . 'sn-comments.php';
require ABSPATH . INC . 'sn-menus.php';
require ABSPATH . INC . 'sn-sidebars.php';
require ABSPATH . INC . 'sn-options.php';
require ABSPATH . INC . 'sn-posts-categories.php';
require ABSPATH . INC . 'sn-posts-terms.php';
require ABSPATH . INC . 'sn-posts.php';
require ABSPATH . INC . 'sn-terms.php';
require ABSPATH . INC . 'sn-users.php';
session_start();

//Intancia la conexión a la base de datos.
$sndb = new SN_DB();

$siteUrl = SN_Options::get_instance('optionSiteUrl');
/*
 * Es muy importante saber la dirección web, por tanto, si no existe
 * no se podra continuar
 */
if ($siteUrl) {
    $dataTable['option']['siteUrl'] = $siteUrl->getOption_value();
    $dataTable['option']['theme'] = SN_Options::get_instance('optionTheme')->getOption_value();
    $dataTable['option']['siteTitle'] = SN_Options::get_instance('optionTitle')->getOption_value();

    if (filter_input(INPUT_GET, 'action') == 'logout') {
        SN_Users::logout();
    } else if (!defined('ADM_PANEL') || (defined('ADM_PANEL') && !ADM_PANEL)) {
        /*
         * Si no estoy en el panel de administración incluyo el fichero que
         * carga las plantilla.
         */
        require ABSPATH . 'sn-template.php';
    }
}