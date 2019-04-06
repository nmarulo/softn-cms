{{ extends('layouts.login') }}

#set[content]
<div id="login-container" class="center-block">
    <div class="panel panel-default">
        <div class="panel-body">
            <img class="center-block" src="{{ asset('images/softn.png') }}">
            <hr/>
            <form method="post">
                <div class="form-group">
                    <label class="control-label" for="user-login">Usuario:</label>
                    <input class="form-control" type="text" name="userLogin" id="user-login" autofocus>
                </div>
                <div class="form-group">
                    <label class="control-label" for="user-password">Contraseña:</label>
                    <input class="form-control" type="password" name="userPassword" id="user-password">
                </div>
                <button class="btn btn-success" type="submit">Acceder</button>
            </form>
        </div>
    </div>
</div>
#end
