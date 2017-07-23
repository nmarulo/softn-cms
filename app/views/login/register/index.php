<?php
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\UsersManager;

$urlLogin = ViewController::getViewData('urlLogin');
$siteUrl  = ViewController::getViewData('siteUrl');
?>
<div id="login" class="page-container center-block">
    <div class="panel panel-default clearfix">
        <div class="panel-body">
            <div id="logo-SoftN">
                <img class="center-block" src="<?php echo $siteUrl; ?>app/resources/img/softn.png" alt="CMS - SoftN"/>
            </div>
            <hr/>
            <form role="form" method="post">
                <div class="form-group">
                    <label class="control-label">Usuario:</label>
                    <input type="text" class="form-control" name="<?php echo UsersManager::USER_LOGIN; ?>" placeholder="Usuario" autofocus="autofocus">
                </div>
                <div class="form-group">
                    <label class="control-label">Contraseña:</label>
                    <input type="password" class="form-control" name="<?php echo UsersManager::USER_PASSWORD; ?>" placeholder="Contraseña">
                </div>
                <div class="form-group">
                    <label class="control-label">Repetir Contraseña:</label>
                    <input type="password" class="form-control" name="<?php echo UsersManager::USER_PASSWORD_REWRITE; ?>" placeholder="Contraseña">
                </div>
                <div class="form-group">
                    <label class="control-label">Correo electrónico:</label>
                    <input type="email" class="form-control" name="<?php echo UsersManager::USER_EMAIL; ?>" placeholder="Correo electrónico">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="<?php echo UsersManager::FORM_SUBMIT; ?>" value="<?php echo UsersManager::FORM_SUBMIT; ?>">Registrar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <a href="<?php echo $urlLogin; ?>" class="btn btn-success btn-block">Conectar</a>
        </div>
    </div>
</div>
