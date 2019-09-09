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
            <div class="col-md-4 col-md-push-8">
                #if(!is_null($user->id))
                <div class="form-group">
                    <div class="col-md-12">
                        <img src="{{$user->userUrlImage}}" class="img-responsive center-block"/>
                    </div>
                </div>
                #endif
            </div>
            <div class="col-md-8 col-md-pull-4">
                <div class="form-group">
                    <label for="input-user-name" class="control-label col-md-4">
                        Nombre
                    </label>
                    <div class="col-md-8">
                        <input id="input-user-name" type="text" class="form-control" value="{{$user->userName}}" name="userName" placeholder="Nombre">
                    </div>
                </div>
                <div class="form-group">
                    <label for="input-user-login" class="control-label col-md-4">
                        Usuario
                    </label>
                    <div class="col-md-8">
                        <input id="input-user-login" type="text" class="form-control" value="{{$user->userLogin}}" name="userLogin" placeholder="Usuario">
                    </div>
                </div>
                <div class="form-group">
                    <label for="input-user-email" class="control-label col-md-4">
                        Email
                    </label>
                    <div class="col-md-8">
                        <input id="input-user-email" type="email" class="form-control" value="{{$user->userEmail}}" name="userEmail" placeholder="Correo electrónico">
                    </div>
                </div>
                #if(is_null($user->id))
                <div class="form-group">
                    <label for="input-user-password" class="control-label col-md-4">
                        Contraseña
                    </label>
                    <div class="col-md-8">
                        <input id="input-user-password" type="password" class="form-control" name="userPassword" placeholder="Contraseña">
                    </div>
                </div>
                <div class="form-group">
                    <label for="input-user-password-re" class="control-label col-md-4">
                        Repetir contraseña
                    </label>
                    <div class="col-md-8">
                        <input id="input-user-password-re" type="password" class="form-control" name="userPasswordRe" placeholder="Repetir contraseña">
                    </div>
                </div>
                #endif
                <div class="form-group">
                    <label for="select-user-profiles" class="control-label col-md-4">
                        Perfil
                    </label>
                    <div class="col-md-8">
                        <select id="select-user-profiles" class="form-control select2-default" name="profileId">
                            #foreach($profilesView as $profile)
                            <option {{$profile->selected ? 'selected' : ''}}
                                    value="{{$profile->id}}">{{$profile->profileName}}</option>
                            #endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-4">
                        <div class="row">
                            #if(!is_null($user->id))
                            <div class="col-lg-6">
                                <button id="button-user-password" type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-user-password">
                                    Modificar contraseña
                                </button>
                            </div>
                            <br class="hidden-lg"/>
                            #endif
                            <div class="col-lg-6">
                                <button id="button-user-new-update" type="submit" class="btn btn-success btn-block">
                                    #if(is_null($user->id))
                                        Crear
                                    #else
                                        Actualizar
                                    #endif
                                </button>
                            </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
#if(!is_null($user->id))
{{ component('user.modalpassword') }}
#endif
#end
