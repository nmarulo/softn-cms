<?php

use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\util\HTML;

?>
<div id="login" class="page-container center-block">
    <div class="panel panel-default clearfix">
        <div class="panel-body">
            <div id="logo-SoftN">
                <?php HTML::image('softn.png', 'CMS - SoftN', 'CMS - SoftN', ['class' => 'center-block']); ?>
            </div>
            <hr/>
            <form role="form" method="post">
                <div class="form-group">
                    <label class="control-label">Usuario:</label>
                    <?php HTML::input(UsersManager::USER_LOGIN, '', 'text', [
                        'class'       => 'form-control',
                        'placeholder' => 'Usuario',
                        'autofocus'   => TRUE,
                    ]); ?>
                </div>
                <div class="form-group">
                    <label class="control-label">Contraseña:</label>
                    <?php HTML::input(UsersManager::USER_PASSWORD, '', 'password', [
                        'class'       => 'form-control',
                        'placeholder' => 'Contraseña',
                    ]); ?>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <?php HTML::input(UsersManager::USER_REMEMBER_ME, '', 'checkbox'); ?>
                            Mantener la sesión iniciada
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <?php HTML::button(UsersManager::FORM_SUBMIT, UsersManager::FORM_SUBMIT, 'Conectar', 'submit', ['class' => 'btn btn-primary']) ?>
                </div>
            </form>
            <hr/>
            <?php HTML::link('register', 'login', '', 'Registro', ['class' => 'btn btn-success btn-block']); ?>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <?php HTML::link('', '', '', '<span class="glyphicon glyphicon-arrow-left"></span> Volver a la pagina principal'); ?>
        </div>
    </div>
</div>
