<?php
/**
 * Carga de archivos de configuración.
 * Comprueba si existe el archivo sn-config.php, si no existe redirecciona
 * a la pagina de instalación.
 * @package SoftN-CMS
 */

/** Ruta absotula del proyecto. */
define('ABSPATH',   dirname( __FILE__ )  . DIRECTORY_SEPARATOR);

if(file_exists(ABSPATH . 'sn-config.php')){
    require ABSPATH . 'sn-config.php';    
    require ABSPATH . 'sn-settings.php';
}  else {
    header('Location: install.php');
}