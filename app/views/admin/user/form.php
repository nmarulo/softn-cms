<?php
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\UsersManager;

$title    = ViewController::getViewData('title');
$user     = ViewController::getViewData('user');
$method   = ViewController::getViewData('method');
$isUpdate = $method == UsersManager::FORM_UPDATE;
?>
<div class="page-container" data-menu-collapse-id="user">
    <div>
        <h1><?php echo $title; ?></h1>
    </div>
    <div>
        <form class="form-horizontal" role="form" method="post">
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo __('Usuario'); ?></label>
                <div class="col-sm-10">
                    <input class="form-control" name="<?php echo UsersManager::USER_LOGIN; ?>" value="<?php echo $user->getUserLogin(); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo __('Nombre'); ?></label>
                <div class="col-sm-10">
                    <input class="form-control" name="<?php echo UsersManager::USER_NAME; ?>" value="<?php echo $user->getUserName(); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo __('Correo electrónico'); ?></label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="<?php echo UsersManager::USER_EMAIL; ?>" value="<?php echo $user->getUserEmail(); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo __('Contraseña'); ?></label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="<?php echo UsersManager::USER_PASSWORD; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo __('Repetir contraseña'); ?></label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="<?php echo UsersManager::USER_PASSWORD_REWRITE; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Tipo de usuario</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="<?php echo UsersManager::USER_ROL; ?>" value="<?php echo $user->getUserRol(); ?>" disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo __('Sitio web'); ?></label>
                <div class="col-sm-10">
                    <input type="url" class="form-control" name="<?php echo UsersManager::USER_URL; ?>" value="<?php echo $user->getUserUrl(); ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <?php if ($isUpdate) { ?>
                        <button class="btn btn-primary" name="<?php echo UsersManager::FORM_UPDATE; ?>" value="<?php echo UsersManager::FORM_UPDATE; ?>"><?php echo __('Actualizar'); ?></button>
                    <?php } else { ?>
                        <button class="btn btn-primary" name="<?php echo UsersManager::FORM_CREATE; ?>" value="<?php echo UsersManager::FORM_CREATE; ?>"><?php echo __('Agregar'); ?></button>
                    <?php } ?>
                </div>
            </div>
            <input type="hidden" name="<?php echo UsersManager::ID; ?>" value="<?php echo $user->getId(); ?>"/>
            <?php \SoftnCMS\util\Token::formField(); ?>
        </form>
    </div>
</div>
