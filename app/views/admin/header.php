<?php

use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\UsersManager;

$idUser            = LoginManager::getSession();
$userManager       = new UsersManager();
$optionsManager    = new OptionsManager();
$siteUrl           = \SoftnCMS\rute\Router::getSiteURL();
$urlAdmin          = $siteUrl . 'admin/';
$urlPostCreate     = $urlAdmin . 'post/create';
$urlCategoryCreate = $urlAdmin . 'category/create';
$urlTermCreate     = $urlAdmin . 'term/create';
$siteTitle         = $optionsManager->searchByName(OPTION_TITLE)
                                    ->getOptionValue();
$urlLogout         = $siteUrl . 'login/logout';
$nameUser          = $userManager->searchById($idUser)
                                 ->getUserName();
$urlUpdateUser     = $urlAdmin . 'user/update/' . $idUser;
?>
<header>
    <nav class="navbar navbar-custom">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarHeader">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo $siteUrl; ?>"><?php echo $siteTitle; ?></a>
            </div>
            <div class="collapse navbar-collapse" id="navbarHeader">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <?php echo __('Publicar'); ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $urlPostCreate; ?>"><?php echo __('Entrada'); ?></a></li>
                            <li><a href="#"><?php echo __('Pagina'); ?></a></li>
                            <li><a href="<?php echo $urlCategoryCreate; ?>"><?php echo __('Categoría'); ?></a></li>
                            <li><a href="<?php echo $urlTermCreate; ?>"><?php echo __('Etiqueta'); ?></a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <span class="glyphicon glyphicon-user"></span>
                            <?php echo __('Hola %1$s', $nameUser); ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo $urlUpdateUser; ?>"><?php echo __('Perfil'); ?></a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo $urlLogout; ?>" role="link"><?php echo __('Cerrar sesión'); ?></a></li>
                        </ul>
                    </li>
                </ul>
                <div id="search_admin" class="navbar-form navbar-right">
                    <input type="text" class="form-control" placeholder="Buscar..." name="searchAdmin">
                </div>
            </div>
        </div>
    </nav>
</header>
