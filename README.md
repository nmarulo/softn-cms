SoftN CMS
===================

SoftN CMS es un sistema de gestión de contenido con el que puedes crear sitios web dinámicos e interactivos, con una interfaces amigable e intuitiva que se adapta a cualquier tipo de resolución. Desarrollado en PHP como lenguaje de programación y MySQL como gestor de base de datos.

*SoftN CMS no utiliza ningún framework de PHP y/o JavaScript.*

> **Versión 0.2.1 Beta** Este proyecto está todavía en fase de desarrollo.

Steaming https://www.livecoding.tv/marulo/

----------

Recursos utilizados
-------------

 - JQuery: http://jquery.com/
 - Bootstrap: http://getbootstrap.com/
 - TinyMCE: https://www.tinymce.com/
 - XAMPP: https://www.apachefriends.org/

----------

Funcionalidades
-------------

#### Panel de administración

- Publicar contenido o artículos.
- Publicar paginas.
- Crear categorías.
- Crear etiquetas.
- Crear sidebars o barra lateral.
- Crear menús.
- Gestionar comentarios.
- Crear usuarios.
- Roles de usuarios.
- Inicio de sesión.
- Registro de usuario
- Configuraciones generales.
- Listado de información de artículos, paginas, categorías, usuarios, etc.

#### Tema o plantilla web

- Lista de artículos publicados.
- Artículos por categoría
- Artículos por etiqueta.
- Artículos por autor.
- Menú de navegación.
- Publicar comentarios.

----------

Instalación
-------------------

#### Instalación manual

Crear el fichero "/config.php" a partir de "/sn-config-sample.php" y completar los
siguientes campos:

```
define('DB_NAME', 'base de datos');
define('DB_USER', 'usuario de la base de datos');
define('DB_NAME', 'contraseña de la base de datos');
define('DB_HOST', 'localhost');
define('LOGGED_KEY', 'codigo de 64 caracteres');
define('COOKIE_KEY', 'codigo de 64 caracteres');
```

Ejecutar el script SQL "/eer-softn-cms.sql" desde PHPMyAdmin (Contiene las tablas de la base de datos y otras sentencias), **antes de ejecutar el script** buscar y modificar la ruta “http://localhost/”. Este script es solo para uso local.

De forma opcional, ejecutar el script SQL "/sn-admin/includes/demo.sql". (Agrega datos de prueba. Solo funciona via web, es decir, a través de
http://localhost/install.php)

Para acceder al panel de administración podemos crear un usuario desde "http://localhost/login.php?action=register" o desde PHPMyAdmin. Todo los usuarios registrados a través del enlace anterior se les asigna el rol "0" que es el más básico, **si quieres que su rol sea de administrador** en la tabla "sn_users" cambia el valor de la columna "user_rol" a "3". Una vez que tengas creado un usuario administrador ya puedes acceder a todas las funciones del panel de administración.

#### Instalación vía web

Acceder a http://localhost/install.php y seguir las instrucciones. Si todo a salido bien, aparecerá el mensaje "La instalación se completo correctamente." ademas del usuario y contraseña con el que podrás acceder al panel de administración.

----------

Requisitos
-------------

SoftN CMS fue probado en XAMP v3.2.1 (PHP v5.5.27, Apache v2.4.12, MySQL v5.6.25)

- XAMPP
- PHP 5.5 o superior
- MySQL 5.6 o superior
- PDO PHP Extension
- Apache con mod_rewrite
- Permisos de escritura en el directorio de la aplicación.

----------

Ficheros
--------------------

- **/sn-admin** Controladores y vistas del panel de administración.
- **/sn-content** Vista de la plantilla web.
- **/sn-includes** Modelos de la base de datos y fichero usados en
toda la aplicación.

----------

Imagenes
--------------------

#### Panel de administración [img](http://i392.photobucket.com/albums/pp4/nmarulo/1_zps9deo9sju.png "Panel de administración")
![Panel de administración](http://i392.photobucket.com/albums/pp4/nmarulo/1_zps9deo9sju.png "Panel de administración")
#### Lista de publicaciones [img](http://i392.photobucket.com/albums/pp4/nmarulo/2_zpsrwawlzym.png "Lista de publicaciones")
![Lista de publicaciones](http://i392.photobucket.com/albums/pp4/nmarulo/2_zpsrwawlzym.png "Lista de publicaciones")
#### Pagina de menus [img](http://i392.photobucket.com/albums/pp4/nmarulo/4_zps9rolmc21.png "Pagina de menus")
![Pagina de menus](http://i392.photobucket.com/albums/pp4/nmarulo/4_zps9rolmc21.png "Pagina de menus")
#### Pagina de instalación [img](http://i392.photobucket.com/albums/pp4/nmarulo/9_zpsqjcnqysi.png "Pagina de instalación")
![Pagina de instalación](http://i392.photobucket.com/albums/pp4/nmarulo/9_zpsqjcnqysi.png "Pagina de instalación")

----------

Licencia (MIT https://opensource.org/licenses/MIT)
--------------------


Copyright (c) 2016 Nicolás Marulanda P.

Por la presente se autoriza, de forma gratuita, a cualquier persona que haya obtenido una copia de este software y archivos de documentación asociados (el "Software"), a utilizar el Software sin restricción, incluyendo sin limitación los derechos de usar, copiar, modificar, fusionar, publicar, distribuir, sublicenciar, y/o vender copias de este Software, y permitir lo mismo a las personas a las que se les proporcione el Software, de acuerdo con las siguientes condiciones:

El aviso de copyright anterior y este aviso de permiso tendrán que ser incluidos en todas las copias o partes sustanciales del Software.

EL SOFTWARE SE ENTREGA "TAL CUAL", SIN GARANTÍA DE NINGÚN TIPO, YA SEA EXPRESA O IMPLÍCITA, INCLUYENDO, A MODO ENUNCIATIVO, CUALQUIER GARANTÍA DE COMERCIABILIDAD, IDONEIDAD PARA UN FIN PARTICULAR Y NO INFRACCIÓN. EN NINGÚN CASO LOS AUTORES O TITULARES DEL COPYRIGHT INCLUIDOS EN ESTE AVISO SERÁN RESPONSABLES DE NINGUNA RECLAMACIÓN, DAÑOS U OTRAS RESPONSABILIDADES, YA SEA EN UN LITIGIO, AGRAVIO O DE OTRO MODO, RESULTANTES DE O EN CONEXION CON EL SOFTWARE, SU USO U OTRO TIPO DE ACCIONES EN EL SOFTWARE.