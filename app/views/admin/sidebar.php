<?php

$siteUrl       = \SoftnCMS\rute\Router::getSiteURL();
$urlAdmin      = $siteUrl . 'admin/';
$urlPost       = $urlAdmin . 'post/';
$urlPostCreate = $urlPost . 'create/';
$urlCategory   = $urlAdmin . 'category/';
$urlTerm       = $urlAdmin . 'term/';
$urlComment    = $urlAdmin . 'comment/';
$urlUser       = $urlAdmin . 'user/';
$urlUserCreate = $urlUser . 'create/';
$urlOption     = $urlAdmin . 'option/';
$urlMenu       = $urlAdmin . 'menu/';
$urlSidebar    = $urlAdmin . 'sidebar/'
?>
<aside>
    <ul class="menu-content">
        <li>
            <a href="<?php echo $urlAdmin; ?>"><i class="fa fa-tachometer"></i> Información</a>
        </li>
        <li>
            <a data-toggle="collapse" href="#post"><span class="glyphicon glyphicon-bullhorn"></span> Entradas <span class="pull-right glyphicon glyphicon-chevron-down"></span></a>
            <ul id="post" class="submenu-content collapse">
                <li>
                    <a href="<?php echo $urlPost; ?>"><span class="glyphicon glyphicon-bullhorn"></span> Entradas</a>
                </li>
                <li>
                    <a href="<?php echo $urlPostCreate; ?>"><span class="glyphicon glyphicon-pencil"></span> Nueva entrada</a>
                </li>
                <li>
                    <a href="<?php echo $urlCategory; ?>"><span class="glyphicon glyphicon-bookmark"></span> Categorías</a>
                </li>
                <li>
                    <a href="<?php echo $urlTerm; ?>"><span class="glyphicon glyphicon-tags"></span> Etiquetas</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="<?php echo $urlMenu; ?>"><span class="fa fa-bars"></span> Menus</a>
        </li>
        <li>
            <a href="<?php echo $urlSidebar; ?>"><span class="glyphicon glyphicon-object-align-right"></span> Barras laterales</a>
        </li>
        <li>
            <a href="<?php echo $urlComment; ?>"><span class="glyphicon glyphicon-comment"></span> Comentarios</a>
        </li>
        <li>
            <a data-toggle="collapse" href="#user"><span class="glyphicon glyphicon-user"></span> Usuarios <span class="pull-right glyphicon glyphicon-chevron-down"></span>
            </a>
            <ul id="user" class="submenu-content collapse">
                <li>
                    <a href="<?php echo $urlUser; ?>"><span class="glyphicon glyphicon-user"></span> Usuarios</a>
                </li>
                <li>
                    <a href="<?php echo $urlUserCreate; ?>"><span class="glyphicon glyphicon-pencil"></span> Nuevo usuario</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="<?php echo $urlOption; ?>"><span class="glyphicon glyphicon-cog"></span> Configuración</a>
        </li>
    </ul>
</aside>
