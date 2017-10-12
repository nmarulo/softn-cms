<?php

/**
 * Modulo: configuración de la aplicación.
 * Contiene las siguientes configuraciones:
 * - Datos de conexión para la base de datos.
 * - Claves.
 * - Tiempo de vencimiento de las cookies.
 */
/** Nombre de la base de datos */
define('DB_NAME', 'softn_cms_test');

/** Usuario de la base de datos */
define('DB_USER', 'root');

/** Contraseña de la base de datos */
define('DB_PASSWORD', 'root');

/** Host */
define('DB_HOST', 'localhost');

/** Codificación de caracteres de la base de datos */
define('DB_CHARSET', 'utf8');

/** Prefijo para las tablas de la base de datos. EJ: sn_posts; */
define('DB_PREFIX', 'sn_');

/** Tipo de base de datos. */
define('DB_TYPE', 'mysql');

define('DEBUG', FALSE);

define('DEFAULT_LANGUAGE', 'es');

define('LOGGER', TRUE);

define('FULL_LOGGER', TRUE);

// Claves SALTED
define('LOGGED_KEY', 'LOGGED_KEY');
define('COOKIE_KEY', 'COOKIE_KEY');
define('SALTED_KEY', 'SALTED_KEY');
define('TOKEN_KEY', 'TOKEN_KEY');
define('COOKIE_EXPIRE', strtotime('+30 days'));//strtotime('+30 days')
