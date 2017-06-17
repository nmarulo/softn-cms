<?php
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
                    <div class="checkbox">
                        <label>
                            <input class="" type="checkbox" name="<?php echo UsersManager::USER_REMEMBER_ME; ?>"> Mantener la sesión iniciada
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="<?php echo UsersManager::FORM_SUBMIT; ?>" value="<?php echo UsersManager::FORM_SUBMIT; ?>">Conectar</button>
                </div>
            </form>
            <hr/>
            <a href="<?php echo $urlRegister; ?>" class="btn btn-success btn-block">Registro</a>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <a href="<?php echo $siteUrl; ?>">Volver a la pagina principal.</a>
        </div>
    </div>
</div>
