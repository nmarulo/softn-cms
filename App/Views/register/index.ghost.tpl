{{ extends('layouts.login') }}

#set[content]
<div class="register-box">
    <div class="register-logo">
        <a href="#"><b>SoftN</b> CMS</a>
    </div>
    <div class="register-box-body">
        <p class="login-box-msg">Formulario de registro</p>
        <form id="form-register" method="post">
            <div class="form-group has-feedback">
                <label class="sr-only" for="input-user-login">Nombre de usuario</label>
                <input id="input-user-login" type="text" class="form-control" name="userLogin" placeholder="Nombre de usuario">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <label class="sr-only" for="input-user-email">Correo electrónico</label>
                <input id="input-user-email" type="email" class="form-control" name="userEmail" placeholder="Correo electrónico">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <label class="sr-only" for="input-user-password">Contraseña</label>
                <input id="input-user-password" type="password" class="form-control" name="userPassword" placeholder="Contraseña">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <label class="sr-only" for="input-user-password-re">Repetir contraseña</label>
                <input id="input-user-password-re" type="password" class="form-control" name="userPasswordRe" placeholder="Repetir contraseña">
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input id="input-accept-terms" type="checkbox"> Acepto los
                            <a id="link-terms" href="#">términos</a>
                        </label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <button id="button-register" type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>
                </div>
            </div>
        </form>
        <a id="link-login" href="{{ url('/login') }}" class="text-center">Ya estoy registrado</a>
    </div>
</div>
#end
