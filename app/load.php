<?php

/**
 * Fichero de dirección de la aplicación y carga de ficheros.
 * Fichero que direcciona al usuario hacia el panel de administración
 * o hacia la plantilla y carga los ficheros necesarios.
 */
require $config;
require \ABSPATH . 'define.php';
require \ABSPATH . 'vendor/autoload.php';

if (\APP_DEBUG === \TRUE) {
    \ini_set('display_errors', \TRUE);
}
\session_start();
/**
 * Guarda la direción del sitio web, para ser usada en los controladores.
 */
$urlSite = '';
\SoftnCMS\controllers\Router::load();
