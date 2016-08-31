SoftN CMS
===================

SoftN CMS es un sistema de gestión de contenido con el que puedes crear sitios web dinámicos e interactivos, con una interfaces amigable e intuitiva que se adapta a cualquier tipo de resolución. Desarrollado en PHP como lenguaje de programación y MySQL como gestor de base de datos.

> **Versión 0.3 Alfa - Fenix** Este proyecto está todavía en fase de desarrollo.

> **Versión 0.2.1 Beta** https://github.com/nmarulo/softn-cms/tree/v0.2.1-Final

> **branch:develop** Ultimos cambios realizados. https://github.com/nmarulo/softn-cms/tree/develop

----------

Steaming https://www.livecoding.tv/marulo/

----------

Más información: http://www.softn.red/softn-cms.html
----------

Recursos utilizados
-------------

- JQuery: http://jquery.com/
- Bootstrap: http://getbootstrap.com/
- TinyMCE: https://www.tinymce.com/
- XAMPP: https://www.apachefriends.org/
- nuevos recursos pendientes de agregar...

----------

Funcionalidades
-------------

#### Panel de administración

- Publicar contenido o artículos.
- Publicar paginas. (No disponible)
- Crear categorías.
- Crear etiquetas.
- Crear sidebars o barra lateral. (No disponible)
- Crear menús. (No disponible)
- Gestionar comentarios.
- Crear usuarios.
- Inicio de sesión.
- Registro de usuario
- Configuraciones generales.
- Listado de información de artículos, paginas, categorías, usuarios, etc.

#### Tema o plantilla web

- Lista de artículos publicados.
- Publicar comentarios. (No disponible)

----------

Instalación
-------------------

- Instalación manual: 
En el fichero **"app/config.php"** configurar los datos de conexión a la base de datos:
```
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
```

Abrir el script SQL **"softn_cms.sql"** buscar y reemplazar la siguiente ruta **"http://localhost/ProyectosWeb/htdocsxampp/SoftN-CMS/"** con la ruta tu localhost.

Por ultimo **ejecutar el script "softn_cms.sql"**, por ejemplo, usando PHPMyAdmin.

Acceder usando el usuario **"admin"** y contraseña **"admin"** o crear un usuario.

----------

Requisitos
-------------

SoftN CMS fue probado en XAMP v3.2.2 (PHP v5.6.21, Apache v2.4.12, MySQL v5.6.25)

- PHP 5.6.21 o superior
- MySQL 5.6.25 o superior
- PDO PHP Extension
- Apache con mod_rewrite
- Permisos de escritura en el directorio de la aplicación.

----------

Ficheros
--------------------

- **/** - Composer, tablas SQL, datos demo SQL.
- **app/** - Configuración, declaración de constantes, autoload.
- **app/controllers/** - Controladores de la aplicación, Router, Request.
- **app/defaults/** - Datos base de la aplicación. (Sin implementar)
- **app/install/** - Pagina de instalación de la aplicación. (No disponible)
- **app/models/** - Modelos de la aplicación, MySQL, plantilla de modelos.
- **app/themes/** - Plantillas o temas de la aplicación.
- **app/vendor/** - Librerias de la aplicación, autoload (composer).
- **app/views/** - Vistas de la aplicación, script JS, CSS.

----------

Imagenes
--------------------

#### Panel de administración [img](http://i392.photobucket.com/albums/pp4/nmarulo/2_zpszycfoycl.png "Panel de administración")
![Panel de administración](http://i392.photobucket.com/albums/pp4/nmarulo/2_zpszycfoycl.png "Panel de administración")
#### Lista de publicaciones [img](http://i392.photobucket.com/albums/pp4/nmarulo/3_zpspjzjw5y4.png "Lista de publicaciones")
![Lista de publicaciones](http://i392.photobucket.com/albums/pp4/nmarulo/3_zpspjzjw5y4.png "Lista de publicaciones")

----------

Licencia (MIT https://opensource.org/licenses/MIT)
--------------------


Copyright (c) 2016 Nicolás Marulanda P.

Por la presente se autoriza, de forma gratuita, a cualquier persona que haya obtenido una copia de este software y archivos de documentación asociados (el "Software"), a utilizar el Software sin restricción, incluyendo sin limitación los derechos de usar, copiar, modificar, fusionar, publicar, distribuir, sublicenciar, y/o vender copias de este Software, y permitir lo mismo a las personas a las que se les proporcione el Software, de acuerdo con las siguientes condiciones:

El aviso de copyright anterior y este aviso de permiso tendrán que ser incluidos en todas las copias o partes sustanciales del Software.

EL SOFTWARE SE ENTREGA "TAL CUAL", SIN GARANTÍA DE NINGÚN TIPO, YA SEA EXPRESA O IMPLÍCITA, INCLUYENDO, A MODO ENUNCIATIVO, CUALQUIER GARANTÍA DE COMERCIABILIDAD, IDONEIDAD PARA UN FIN PARTICULAR Y NO INFRACCIÓN. EN NINGÚN CASO LOS AUTORES O TITULARES DEL COPYRIGHT INCLUIDOS EN ESTE AVISO SERÁN RESPONSABLES DE NINGUNA RECLAMACIÓN, DAÑOS U OTRAS RESPONSABILIDADES, YA SEA EN UN LITIGIO, AGRAVIO O DE OTRO MODO, RESULTANTES DE O EN CONEXION CON EL SOFTWARE, SU USO U OTRO TIPO DE ACCIONES EN EL SOFTWARE.