{{ extends('layouts.login') }}

#set[content]
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>SoftN</b> CMS</a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">Iniciar sesión</p>
        <form id="form-login" method="post">
            <div class="form-group has-feedback">
                <label class="sr-only" for="input-user-login">Usuario</label>
                <input id="input-user-login" type="text" class="form-control" name="userLogin" placeholder="Usuario">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <label class="sr-only" for="input-user-password">Contraseña</label>
                <input id="input-user-password" type="password" class="form-control" name="userPassword" placeholder="Contraseña">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input id="input-remember-me" type="checkbox"> Recuérdame
                        </label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <button id="button-login" type="submit" class="btn btn-primary btn-block btn-flat">Iniciar</button>
                </div>
            </div>
        </form>
        <a id="link-password-recovery" href="#">Recuperar contraseña</a>
        <br>
        <a id="link-register" href="{{ url('/register') }}" class="text-center">Registrarse</a>
    </div>
</div>
#end
