<?php

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\UsersManager;

$siteUrl     = ViewController::getViewData('siteUrl');
$urlRegister = ViewController::getViewData('urlRegister');
?>
<div id="login" class="page-container center-block">
    <div class="panel panel-default clearfix">
        <div class="panel-body">
            <div id="logo-SoftN">
                <img class="center-block" src="<?php echo $siteUrl; ?>app/resources/img/softn.png" alt="CMS - SoftN"/>
            </div>
            <hr/>
            <form method="post">
                <div class="form-group">
                    <label class="control-label"><?php echo __('Usuario'); ?>:</label>
                    <input class="form-control" name="<?php echo UsersManager::USER_LOGIN; ?>" autofocus>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo __('Contraseña'); ?>:</label>
                    <input type="password" class="form-control" name="<?php echo UsersManager::USER_PASSWORD; ?>">
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="<?php echo UsersManager::USER_REMEMBER_ME; ?>">
                            <?php echo __('Mantener la sesión iniciada'); ?>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" name="<?php echo Constants::FORM_SUBMIT; ?>" value="<?php echo Constants::FORM_SUBMIT; ?>"><?php echo __('Conectar'); ?></button>
                </div>
                <?php \SoftnCMS\util\Token::formField(); ?>
            </form>
            <hr/>
            <a href="<?php echo $urlRegister; ?>" class="btn btn-success btn-block"><?php echo __('Registro'); ?></a>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <a href="<?php echo $siteUrl; ?>"><span class="glyphicon glyphicon-arrow-left"></span> <?php echo __('Volver a la pagina principal'); ?>.</a>
        </div>
    </div>
</div>
