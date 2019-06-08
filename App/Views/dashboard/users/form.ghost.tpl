{{ extends('layouts.master') }}

#set[header]
<h1>
    Usuarios
    <small>Gestión de usuarios</small>
</h1>
#end

#set[breadcrumb]
<li>
    <a id="link-breadcrumb-users" href="{{ url('/dashboard/users') }}">
        <i class="fa fa-users"></i>
        Usuarios
    </a>
</li>
<li class="active">Gestión</li>
#end

#set[content]
<div class="box">
    <div class="box-body">
        <form id="form-users" class="form-horizontal form-control-label-left row" method="post">
            <div class="col-sm-8">
                <div class="form-group">
                    <label for="input-user-name" class="control-label col-md-2">
                        Nombre
                    </label>
                    <div class="col-md-10">
                        <input id="input-user-name" type="text" class="form-control" value="{{$user->userName}}" name="userName" placeholder="Nombre">
                    </div>
                </div>
                <div class="form-group">
                    <label for="input-user-login" class="control-label col-md-2">
                        Usuario
                    </label>
                    <div class="col-md-10">
                        <input id="input-user-login" type="text" class="form-control" value="{{$user->userLogin}}" name="userLogin" placeholder="Usuario">
                    </div>
                </div>
                <div class="form-group">
                    <label for="input-user-email" class="control-label col-md-2">
                        Email
                    </label>
                    <div class="col-md-10">
                        <input id="input-user-email" type="email" class="form-control" value="{{$user->userEmail}}" name="userEmail" placeholder="Correo electrónico">
                    </div>
                </div>
                #if(is_null($user->id))
                <div class="form-group">
                    <label for="input-user-password" class="control-label col-md-2">
                        Contraseña
                    </label>
                    <div class="col-md-10">
                        <input id="input-user-password" type="password" class="form-control" name="userPassword" placeholder="Contraseña">
                    </div>
                </div>
                <div class="form-group">
                    <label for="input-user-password-re" class="control-label col-md-2">
                        Repetir contraseña
                    </label>
                    <div class="col-md-10">
                        <input id="input-user-password-re" type="password" class="form-control" name="userPasswordRe" placeholder="Repetir contraseña">
                    </div>
                </div>
                #else
                <div class="form-group">
                    <div class="col-md-12">
                        <button id="button-user-password" type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-user-password">
                            Modificar contraseña
                        </button>
                    </div>
                </div>
                #endif
            </div>
            <div class="col-sm-4">
                <button id="button-user-new-update" type="submit" class="btn btn-success btn-block">
                    #if(is_null($user->id))
                        Crear
                    #else
                        Actualizar
                    #endif
                </button>
            </div>
        </form>
    </div>
</div>
#if(!is_null($user->id))
{{ component('user.modalpassword') }}
#endif
#end
