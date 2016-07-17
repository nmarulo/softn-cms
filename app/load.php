<?php
/**
 * Fichero de dirección de la aplicación y carga de ficheros.
 * Fichero que direcciona al usuario hacia el panel de administración
 * o hacia la plantilla y carga los ficheros necesarios.
 */

require \ABSPATH . 'define.php';
require ABSPATH . 'vendor/autoload.php';

new \SoftnCMS\controllers\Router();
