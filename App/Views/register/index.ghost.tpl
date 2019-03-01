{{ extends('layouts.login') }}

#set[content]
<div id="login-container" class="center-block">
    <div class="panel panel-default">
        <div class="panel-body">
            <img class="center-block" src="{{ asset('images/softn.png') }}">
            <hr/>
            <form method="post">
                <div class="form-group">
                    <label for="user-login" class="control-label">Usuario:</label>
                    <input class="form-control" type="text" name="user_login" id="user-login" autofocus>
                </div>
                <div class="form-group">
                    <label for="user-email" class="control-label">Correo electrónico:</label>
                    <input class="form-control" type="email" name="user_email" id="user-email">
                </div>
                <div class="form-group">
                    <label for="user-password" class="control-label">Contraseña:</label>
                    <input class="form-control" type="password" name="user_password" id="user-password">
                </div>
                <div class="form-group">
                    <label for="user-password-re" class="control-label">Repetir contraseña:</label>
                    <input class="form-control" type="password" name="user_password_re" id="user-password-re">
                </div>
                <button class="btn btn-success" type="submit">Registrar</button>
            </form>
        </div>
    </div>
</div>
#end
