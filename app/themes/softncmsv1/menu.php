<?php

use SoftnCMS\models\template\MenuTemplate;
use SoftnCMS\controllers\ViewController;

$siteTitle     = ViewController::getViewData('siteTitle');
$siteUrl       = ViewController::getViewData('siteUrl');
$userSession   = ViewController::getViewData('userSession');
$menuList      = ViewController::getViewData('menuList');
$urlAdmin      = $siteUrl . 'admin';
$urlLogin      = $siteUrl . 'login';
$urlLogout     = "$urlLogin/logout";
$urlUserUpdate = '';

if (!empty($userSession)) {
    $urlUserUpdate = "$urlAdmin/user/update/" . $userSession->getId();
}
?>

<nav class="navbar navbar-white">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo $siteUrl; ?>">
                <?php echo $siteTitle; ?>
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php array_walk($menuList, function(MenuTemplate $menuTemplate) {
                    $subMenuList = $menuTemplate->getSubMenuList();
                    $menu        = $menuTemplate->getMenu();
                    $menuTitle   = $menu->getMenuTitle();
    
                    if (empty($subMenuList)) {
                        echo '<li><a href="' . $menu->getMenuUrl() . '">' . $menuTitle . '</a></li>';
                    } else { ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <?php echo $menuTitle; ?><span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php array_walk($subMenuList, function(MenuTemplate $menuTemplate) {
                                    $menu = $menuTemplate->getMenu();
                                    echo '<li><a href="' . $menu->getMenuUrl() . '">' . $menu->getMenuTitle() . '</a></li>';
    
                                }); ?>
                            </ul>
                        </li>
                    <?php }
                }); ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if (!empty($userSession)) { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $userSession->getUserName(); ?>
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $urlAdmin; ?>">Administración</a></li>
                            <li><a href="<?php echo $urlUserUpdate; ?>">Perfil</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo $urlLogout; ?>">Cerrar sesión</a></li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li><a href="<?php echo $urlLogin; ?>">Acceder</a></li>
                <?php } ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
