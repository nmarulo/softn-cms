{{ extends('layouts.login') }}

#set[content]
<div class="register-box">
    <div class="register-logo">
        <a href="#"><b>SoftN</b> CMS</a>
    </div>
    <div class="register-box-body">
        <p class="login-box-msg">Formulario de registro</p>
        <form method="post">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="userLogin" placeholder="Nombre de usuario">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="email" class="form-control" name="userEmail" placeholder="Correo electrónico">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="userPassword" placeholder="Contraseña">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="userPasswordRe" placeholder="Repetir contraseña">
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> Acepto los
                            <a href="#">términos</a>
                        </label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>
                </div>
            </div>
        </form>
        <a href="{{ url('/login') }}" class="text-center">Ya estoy registrado</a>
    </div>
</div>
#end
