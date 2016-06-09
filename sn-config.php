<?php

/**
 * Base de configuración de la aplicación.
 * Contiene las siguentes configuraciones:
 * * Datos de conexion para la base de datos.
 * * Claves.
 * * Tiempo de vencimiento de las cookies.
 * @package SoftN-CMS
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
define('DB_PREFIX', 'saassn_');

// Claves de autentificacion
define('LOGGED_KEY', '1afbT6iFNdqrH7lR4UhMSaWxAZzYaZRp5e2JkkOgkmqP6WAO49v1fAUP3k03vZw2');
define('COOKIE_KEY', '2FGECVceKTiBn8G7XLADtnJtp2jvIn43QtdXOUtz2N3KwIFOILyCBxqwD8vkN8uq');
//define('SALTED_KEY',    '8s6IyXziV8T2Xlz1TpySqGjFM0PZGJyWmHt8vf2Dmde2DYwsykRtOEJbFM6bN3rz');
define('COOKIE_EXPIRE', strtotime('+30 days'));
