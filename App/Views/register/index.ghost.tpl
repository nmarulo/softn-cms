{{ extends('layouts.login') }}

#set[content]
<div id="login-container" class="center-block">
    <div class="panel panel-default">
        <div class="panel-body">
            <img class="center-block" src="{{ asset('images/softn.png') }}">
            <hr/>
            <form>
                <div class="form-group">
                    <label class="control-label">Usuario:</label>
                    <input class="form-control" type="text" name="">
                </div>
                <div class="form-group">
                    <label class="control-label">Correo electrónico:</label>
                    <input class="form-control" type="password" name="">
                </div>
                <div class="form-group">
                    <label class="control-label">Contraseña:</label>
                    <input class="form-control" type="password" name="">
                </div>
                <div class="form-group">
                    <label class="control-label">Repetir contraseña:</label>
                    <input class="form-control" type="password" name="">
                </div>
                <button class="btn btn-success" type="submit">Registrar</button>
            </form>
        </div>
    </div>
</div>
#end
