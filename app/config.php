<?php

/**
 * Modulo: configuración de la aplicación.
 * Contiene las siguientes configuraciones:
 * - Datos de conexión para la base de datos.
 * - Claves.
 * - Tiempo de vencimiento de las cookies.
 */
/** Nombre de la base de datos */
define('DB_NAME', 'softn_cms');

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

// Claves SALTED
define('LOGGED_KEY', 'mpk9wqPbH5Whs70UVHT1FBU7N0U8UDgz4IMmUYP0vCesrnmcPs1afABSNYUf4liN');
define('COOKIE_KEY', 'aUCsFocHG1UwNw5m65yccrFvaOgfCUHTDYPpOHXgfjXrwxboKzr5wa60Uw07xmuR');
define('SALTED_KEY', '8s6IyXziV8T2Xlz1TpySqGjFM0PZGJyWmHt8vf2Dmde2DYwsykRtOEJbFM6bN3rz');
define('TOKEN_KEY', 'gpGxsPteaomtXPILXkn2JCFMoVtXxY52eYo06DIidbUUclji6kLryRCMD9HkWVnw');
define('COOKIE_EXPIRE', strtotime('+30 days'));//strtotime('+30 days')
