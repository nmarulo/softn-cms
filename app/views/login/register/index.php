<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\UsersManager;

$urlLogin = ViewController::getViewData('urlLogin');
$siteUrl  = ViewController::getViewData('siteUrl')
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
                    <label class="control-label"><?php echo __('Repetir contraseña'); ?>:</label>
                    <input type="password" class="form-control" name="<?php echo UsersManager::USER_PASSWORD_REWRITE; ?>">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo __('Correo electrónico'); ?>:</label>
                    <input type="email" class="form-control" name="<?php echo UsersManager::USER_EMAIL; ?>">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" name="<?php echo UsersManager::FORM_SUBMIT; ?>" value="<?php echo UsersManager::FORM_SUBMIT; ?>"><?php echo __('Registrar'); ?></button>
                </div>
                <?php \SoftnCMS\util\Token::formField(); ?>
            </form>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <a href="<?php echo $urlLogin; ?>" class="btn btn-success btn-block"><?php echo __('Conectar'); ?></a>
        </div>
    </div>
</div>

