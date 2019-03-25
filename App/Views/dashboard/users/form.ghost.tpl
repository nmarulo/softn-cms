{{ extends('layouts.master') }}

#set[content]
<div class="panel panel-default">
    <div class="panel-body">
        <h1>
            #if(is_null($user->id))
                Crear
            #else
                Actualizar
            #endif
            usuario
        </h1>
        <form method="post">
            <div class="form-group">
                <label class="control-label" for="user-name">Nombre</label>
                <input class="form-control" type="text" name="user_name" id="user-name" value="{{$user->userName}}"/>
            </div>
            <div class="form-group">
                <label class="control-label" for="user-login">Login</label>
                <input class="form-control" type="text" name="user_login" id="user-login" value="{{$user->userLogin}}"/>
            </div>
            <div class="form-group">
                <label class="control-label" for="user-email">Correo electrónico</label>
                <input class="form-control" type="text" name="user_email" id="user-email" value="{{$user->userEmail}}"/>
            </div>
            <div class="form-group">
                <label class="control-label" for="user-password">Contraseña</label>
                <input class="form-control" type="password" name="user_password" id="user-password"/>
            </div>
            <button class="btn btn-success" type="submit">
                #if(is_null($user->id))
                    Crear
                #else
                    Actualizar
                #endif
            </button>
        </form>
    </div>
</div>
#end
