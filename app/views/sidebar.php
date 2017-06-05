<?php
use SoftnCMS\models\managers\OptionsManager;

$optionsManager = new OptionsManager();
$siteUrl        = $optionsManager->searchByName(OPTION_SITE_URL)
                                 ->getOptionValue();
$urlAdmin       = $siteUrl . 'admin/';
$urlPost        = $urlAdmin . 'post/';
$urlPostCreate  = $urlPost . 'create/';
$urlCategory    = $urlAdmin . 'category/';
$urlTerm        = $urlAdmin . 'term/';
$urlComment     = $urlAdmin . 'comment/';
$urlUser        = $urlAdmin . 'user/';
$urlUserCreate  = $urlUser . 'create/';
$urlOption      = $urlAdmin . 'option/';
?>

<aside class="sn-menu-content">
    <ul class="sn-menu">
        <li>
            <a href="<?php echo $urlAdmin; ?>"><i class="fa fa-tachometer" aria-hidden="true"></i> Información</a>
        </li>
        <li>
            <a data-toggle="collapse" href="#post"><span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span> Entradas <span class="pull-right glyphicon glyphicon-chevron-down"></span></a>
            <ul id="post" class="sn-submenu collapse">
                <li>
                    <a href="<?php echo $urlPost; ?>"><span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span> Entradas</a>
                </li>
                <li>
                    <a href="<?php echo $urlPostCreate; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Nueva entrada</a>
                </li>
                <li>
                    <a href="<?php echo $urlCategory; ?>"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span> Categorías</a>
                </li>
                <li>
                    <a href="<?php echo $urlTerm; ?>"><span class="glyphicon glyphicon-tags" aria-hidden="true"></span> Etiquetas</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="<?php echo $urlComment; ?>"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> Comentarios</a>
        </li>
        <li>
            <a data-toggle="collapse" href="#user"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Usuarios <span class="pull-right glyphicon glyphicon-chevron-down"></span>
            </a>
            <ul id="user" class="sn-submenu collapse">
                <li>
                    <a href="<?php echo $urlUser; ?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Usuarios</a>
                </li>
                <li>
                    <a href="<?php echo $urlUserCreate; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Nuevo usuario</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="<?php echo $urlOption; ?>"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Configuración</a>
        </li>
    </ul>
</aside>
