<?php
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\UsersManager;

$idUser            = LoginManager::getSession();
$optionsManager    = new OptionsManager();
$userManager       = new UsersManager();
$siteUrl           = $optionsManager->getSiteUrl();
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
                <a class="navbar-brand" href="<?php echo $urlAdmin; ?>"><?php echo $siteTitle; ?></a>
            </div>
            <div class="collapse navbar-collapse" id="navbarHeader">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Publicar
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $urlPostCreate; ?>">Entrada</a></li>
                            <li><a href="#">Pagina</a></li>
                            <li><a href="<?php echo $urlCategoryCreate; ?>">Categoría</a></li>
                            <li><a href="<?php echo $urlTermCreate; ?>">Etiqueta</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="glyphicon glyphicon-user"></span>
                            Hola <?php echo $nameUser; ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo $urlUpdateUser; ?>">Perfil</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo $urlLogout; ?>" role="link">Cerrar sesión</a></li>
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
