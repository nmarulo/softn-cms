<?php

/**
 * Declaración de datos constantes.
 */
/** Versión actual de la aplicación. */
\define('VERSION', '0.3');

/** Nombre para identificar el panel de administración. EJ: http://localhost/admin/ */
\define('ADMIN', 'admin');

/** Nombre de la variable que contiene los datos de la URL. */
\define('URL_GET', 'url');

/** Espacio de nombre de la aplicación. */
\define('APP_NAMESPACE', 'SoftnCMS' . \DIRECTORY_SEPARATOR);

/** Espacio de nombre de los controladores. */
\define('NAMESPACE_CONTROLLERS', APP_NAMESPACE . 'controllers' . \DIRECTORY_SEPARATOR);

/** Ruta de los modulos de controladores. */
\define('CONTROLLERS', \ABSPATH . 'controllers' . \DIRECTORY_SEPARATOR);

/** Ruta de los modulos de modelos. */
\define('MODELS', \ABSPATH . 'models' . \DIRECTORY_SEPARATOR);

/** Ruta de los modulos de vista. */
\define('VIEWS', \ABSPATH . 'views' . \DIRECTORY_SEPARATOR);

/** Direccion temprar de la aplicación. */
\define('LOCALHOST', 'http://localhost/ProyectosWeb/htdocsxampp/SoftN-CMS' . \DIRECTORY_SEPARATOR);
