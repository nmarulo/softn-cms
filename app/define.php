<?php

/**
 * Modulo: Constantes de la aplicación.
 */
/** Versión actual de la aplicación. */
\define('VERSION', '0.4-alfa');

/** Nombre del indice para el identificador del usuario en $_SESSION */
define('SESSION_USER', 'usernameID');

/** Nombre del indice para el identificador del usuario en $_COOKIE */
define('COOKIE_USER_REMEMBER', 'userRememberMe');

/** Nombre del indice usado en "Router::getDATA()"  */
define('SITE_URL', 'siteUrl');

/** Nombre de la variable que contiene los datos de la URL. */
\define('URL_GET', 'url');

/** Espacio de nombre de la aplicación. */
\define('APP_NAMESPACE', 'SoftnCMS\\');

/** Espacio de nombre de los controladores. */
\define('NAMESPACE_CONTROLLERS', \APP_NAMESPACE . 'controllers\\');

/** Espacio de nombre de los controladores del panel de administración. */
\define('NAMESPACE_CONTROLLERS_ADMIN', \NAMESPACE_CONTROLLERS . 'admin\\');

/** Espacio de nombre de los controladores del tema de la aplicación. */
\define('NAMESPACE_CONTROLLERS_THEME', \NAMESPACE_CONTROLLERS . 'themes\\');

/** Ruta de los modulos de controladores. */
\define('CONTROLLERS', \ABSPATH . 'controllers' . \DIRECTORY_SEPARATOR);

/** Ruta de los modulos de controladores de configuración. */
\define('CONTROLLERS_CONFIG', \CONTROLLERS . 'config' . \DIRECTORY_SEPARATOR);

/** Ruta de los modulos de controladores del panel de administración. */
\define('CONTROLLERS_ADMIN', \CONTROLLERS . 'admin' . \DIRECTORY_SEPARATOR);

/** Ruta de los modulos de controladores del tema de la aplicación. */
\define('CONTROLLERS_THEME', \CONTROLLERS . 'themes' . \DIRECTORY_SEPARATOR);

/** Ruta de los modulos de modelos. */
\define('MODELS', \ABSPATH . 'models' . \DIRECTORY_SEPARATOR);

/** Ruta de los modulos de vista. */
\define('VIEWS', \ABSPATH . 'views' . \DIRECTORY_SEPARATOR);

/** Ruta de los modulos de vista. */
\define('VIEWS_ADMIN', \VIEWS . 'admin' . \DIRECTORY_SEPARATOR);

/** Ruta de los temas de la aplicación. */
\define('THEMES', \ABSPATH . 'themes' . \DIRECTORY_SEPARATOR);

/** Directorio de los temas de la aplicación. */
\define('APP_THEMES', 'app/themes/');
