<?php

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\InstallManager;

$host    = ViewController::getViewData('host');
$siteUrl = ViewController::getViewData('siteUrl');
$charset = ViewController::getViewData('charset');
$prefix  = ViewController::getViewData('prefix');
?>
<div id="install" class="panel panel-default center-block clearfix">
    <div class="panel-body">
        <div id="logo-SoftN">
            <img class="center-block" src="<?php echo $siteUrl; ?>app/resources/img/softn.png" alt="CMS - SoftN"/>
        </div>
        <h2><?php echo __('Proceso de instalación'); ?></h2>
        <hr/>
        <form class="form-horizontal" method="post">
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo __('Dirección web'); ?>:</label>
                <div class="col-sm-9">
                    <input class="form-control" name="<?php echo InstallManager::INSTALL_SITE_URL; ?>" placeholder="http://localhost/" value="<?php echo $siteUrl; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo __('Base de datos'); ?>:</label>
                <div class="col-sm-9">
                    <input class="form-control" name="<?php echo InstallManager::INSTALL_DB_NAME; ?>" placeholder="softn_cms">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo __('Usuario BD'); ?>:</label>
                <div class="col-sm-9">
                    <input class="form-control" name="<?php echo InstallManager::INSTALL_DB_USER; ?>" placeholder="root">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo __('Contraseña BD'); ?>:</label>
                <div class="col-sm-9">
                    <input class="form-control" name="<?php echo InstallManager::INSTALL_DB_PASSWORD; ?>" placeholder="root">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo __('Host'); ?>:</label>
                <div class="col-sm-9">
                    <input class="form-control" name="<?php echo InstallManager::INSTALL_HOST; ?>" placeholder="localhost" value="<?php echo $host; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo __('Prefijo'); ?>:</label>
                <div class="col-sm-9">
                    <input class="form-control" name="<?php echo InstallManager::INSTALL_PREFIX; ?>" placeholder="sn_" value="<?php echo $prefix; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo __('Charset'); ?>:</label>
                <div class="col-sm-9">
                    <input class="form-control" name="<?php echo InstallManager::INSTALL_CHARSET; ?>" placeholder="utf8" value="<?php echo $charset; ?>">
                </div>
            </div>
            <button class="btn btn-primary" name="<?php echo Constants::FORM_SUBMIT; ?>" value="<?php echo Constants::FORM_SUBMIT; ?>"><?php echo __('Siguiente'); ?></button>
            <?php \SoftnCMS\util\Token::formField(); ?>
        </form>
    </div>
</div>
