{{ extends('layouts.master') }}

#set[header]
<h1>
    Usuarios
    <small>Gestión de usuarios</small>
</h1>
#end

#set[breadcrumb]
<li>
    <a href="{{ url('/dashboard/users') }}">
        <i class="fa fa-users"></i>
        Usuarios
    </a>
</li>
<li class="active">Gestión</li>
#end

#set[content]
<div class="box">
    <div class="box-body">
        <form class="form-horizontal form-control-label-left row" role="form">
            <div class="col-sm-8">
                <div class="form-group">
                    <label for="user-name" class="control-label col-md-2">
                        Nombre
                    </label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="user-name" value="{{$user->userName}}" name="userName" placeholder="Nombre">
                    </div>
                </div>
                <div class="form-group">
                    <label for="user-login" class="control-label col-md-2">
                        Usuario
                    </label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="user-login" value="{{$user->userLogin}}" name="userLogin" placeholder="Usuario">
                    </div>
                </div>
                <div class="form-group">
                    <label for="user-email" class="control-label col-md-2">
                        Email
                    </label>
                    <div class="col-md-10">
                        <input type="email" class="form-control" id="user-email" value="{{$user->userEmail}}" name="userEmail" placeholder="Email">
                    </div>
                </div>
                #if(is_null($user->id))
                <div class="form-group">
                    <label for="user-password" class="control-label col-md-2">
                        Contraseña
                    </label>
                    <div class="col-md-10">
                        <input type="password" class="form-control" id="user-password"  name="userPassword" placeholder="Contraseña">
                    </div>
                </div>
                <div class="form-group">
                    <label for="user-re-password" class="control-label col-md-2">
                        Repetir contraseña
                    </label>
                    <div class="col-md-10">
                        <input type="password" class="form-control" id="user-re-password" name="userRePassword" placeholder="Repetir contraseña">
                    </div>
                </div>
                #else
                <div class="form-group">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-user-password">
                            Modificar contraseña
                        </button>
                    </div>
                </div>
                #endif
            </div>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-success btn-block">
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
{{ include('dashboard.users.modalpassword') }}
#end
