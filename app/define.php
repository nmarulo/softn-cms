<?php

/**
 * Modulo: Constantes de la aplicación.
 */
/** Versión actual de la aplicación. */
define('VERSION', '0.4-alfa');

/** Nombre del indice para el identificador del usuario en $_SESSION */
define('SESSION_USER', 'usernameID');
define('SESSION_MESSAGES', 'sessionMessages');

/** Nombre del indice para el identificador del usuario en $_COOKIE */
define('COOKIE_USER_REMEMBER', 'userRememberMe');

/** Nombre del indice usado en "Router::getDATA()"  */
define('SITE_URL', 'siteUrl');

/** Nombre del indice usado en "Router::getDATA()"  */
define('SITE_TITLE', 'siteTitle');

/** Nombre de la variable que contiene los datos de la URL. */
define('URL_GET', 'url');

/** Espacio de nombre de la aplicación. */
define('APP_NAMESPACE', 'SoftnCMS\\');

/** Espacio de nombre de los controladores. */
define('NAMESPACE_CONTROLLERS', APP_NAMESPACE . 'controllers\\');

/** Espacio de nombre de los modelos. */
define('NAMESPACE_MODELS', APP_NAMESPACE . 'models\\');

/** Espacio de nombre de los gestores de las tablas. */
define('NAMESPACE_MANAGERS', NAMESPACE_MODELS . 'managers\\');

define('NAMESPACES_LICENSES', NAMESPACE_MODELS . 'licenses\\');

/** Ruta de los modulos de controladores. */
define('CONTROLLERS', ABSPATH . 'controllers' . DIRECTORY_SEPARATOR);

/** Ruta de los modulos de modelos. */
define('MODELS', ABSPATH . 'models' . DIRECTORY_SEPARATOR);

/** Ruta de los modulos de modelos. */
define('MANAGERS', MODELS . 'managers' . DIRECTORY_SEPARATOR);

/** Ruta de los modulos de vista. */
define('VIEWS', ABSPATH . 'views' . DIRECTORY_SEPARATOR);

/** Ruta de los temas de la aplicación. */
define('THEMES', ABSPATH . 'themes' . DIRECTORY_SEPARATOR);

define('LANGUAGES', ABSPATH . 'util' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR);

/** Directorio de los temas de la aplicación. */
define('APP_THEMES', 'app' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR);

define('REPLACE_SQL_SITE_URL', '#{SITE_URL}');
define('REPLACE_SQL_PREFIX', '#{PREFIX}');
/** Nombre del parámetro enviado por url para establecer el idioma de la web. */
define('PARAM_LANGUAGE', 'lan');

define('OPTION_TITLE', 'optionTitle');
define('OPTION_DESCRIPTION', 'optionDescription');
define('OPTION_PAGED', 'optionPaged');
define('OPTION_SITE_URL', 'optionSiteUrl');
define('OPTION_THEME', 'optionTheme');
define('OPTION_EMAIL_ADMIN', 'optionEmailAdmin');
define('OPTION_MENU', 'optionMenu');
define('OPTION_LANGUAGE', 'optionLanguage');

define('LICENSE_DEFAULT_CODE', 0);
define('LICENSE_READ_CODE', 1);
define('LICENSE_READ_INSERT_CODE', 2);
define('LICENSE_READ_UPDATE_CODE', 3);
define('LICENSE_READ_DELETE_CODE', 4);
define('LICENSE_READ_INSERT_UPDATE_CODE', 5);
define('LICENSE_READ_INSERT_DELETE_CODE', 6);
define('LICENSE_READ_UPDATE_DELETE_CODE', 7);
define('LICENSE_READ_INSERT_UPDATE_DELETE_CODE', 8);
