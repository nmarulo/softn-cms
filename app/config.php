<?php

/**
 * Base de configuración de la aplicación.
 * Contiene las siguentes configuraciones:
 * - Datos de conexion para la base de datos.
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

/** Determina si los errores deberían ser impresos en pantalla. */
define('APP_DEBUG', \TRUE);

// Claves de autentificacion
define('LOGGED_KEY', '5GLhueRQNTmlo8nY6XusgNN1JDSnQWRKsGiQTkjU0QuD9IVf9SdtmPrwDxc6irBs');
define('COOKIE_KEY', 'bydiGj9QAG0Nh9RpzGjmPKtTTiUI6PI3iivDP9nEyoTQMtaqnmN7GxR8xXYci5dw');
//define('SALTED_KEY',    '8s6IyXziV8T2Xlz1TpySqGjFM0PZGJyWmHt8vf2Dmde2DYwsykRtOEJbFM6bN3rz');
define('COOKIE_EXPIRE', strtotime('+30 days'));
