<?php
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\UsersManager;

$optionsManager = new OptionsManager();
$siteTitle      = $optionsManager->searchByName(OPTION_TITLE)
                                 ->getOptionValue();
$siteUrl        = $optionsManager->getSiteUrl();
$urlAdmin       = $siteUrl . 'admin';
$isLogin        = LoginManager::isLogin();
$urlLogin       = $siteUrl . 'login';
$urlLogout      = "$urlLogin/logout";
$urlUserUpdate  = '';

if ($isLogin) {
    $usersManager  = new UsersManager();
    $user          = $usersManager->searchById(LoginManager::getSession());
    $urlUserUpdate = "$urlAdmin/user/update/" . $user->getId();
}
?>
<header>
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
                    <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
                    <li><a href="#">Link</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="navbar-form navbar-left">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Buscar...">
                    </div>
                    <button type="submit" class="btn btn-default">Buscar</button>
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <?php if ($isLogin) { ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $user->getUserName(); ?>
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
</header>
