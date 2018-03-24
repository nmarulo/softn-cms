{{ extends('layouts.master') }}

#set[content]
<div class="panel panel-default">
    <div class="panel-body">
        <h1>
            #if(empty($user->id))
                Crear
            #else
                Actualizar
            #endif
            usuario
        </h1>
        <form method="post">
            <div class="form-group">
                <label class="control-label" for="user_name">Nombre</label>
                <input class="form-control" type="text" name="user_name" id="user_name" value="{{$user->user_name}}"/>
            </div>
            <div class="form-group">
                <label class="control-label" for="user_login">Login</label>
                <input class="form-control" type="text" name="user_login" id="user_login" value="{{$user->user_login}}"/>
            </div>
            <div class="form-group">
                <label class="control-label" for="user_email">Correo electronico</label>
                <input class="form-control" type="text" name="user_email" id="user_email" value="{{$user->user_email}}"/>
            </div>
            <div class="form-group">
                <label class="control-label" for="user_password">Contraseña</label>
                <input class="form-control" type="password" name="user_password" id="user_password" value="{{$user->user_password}}"/>
            </div>
            <button class="btn btn-success" type="submit">
                #if(empty($user->id))
                    Crear
                #else
                    Actualizar
                #endif
            </button>
        </form>
    </div>
</div>
#end
