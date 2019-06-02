{{ extends('layouts.master') }}

#set[header]
<h1>
    Usuarios
    <small>Gestión de usuarios</small>
</h1>
#end

#set[breadcrumb]
<li class="active">Usuarios</li>
#end

#set[content]
<div id="container-user-table" class="box container-table-data" data-update="#container-user-table:.container-pagination,tbody #messages">
    <div class="box-header">
        <div class="col-sm-6">
            <a href="{{ url('/dashboard/users/form') }}" class="btn btn-primary">
                <span class="fa fa-user-plus"></span>
                <small>Agregar usuario</small>
            </a>
        </div>
        <div class="col-sm-6">
            {{ component('pagination') }}
        </div>
    </div>
    <div class="box-body table-responsive">
        <table id="example2" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th></th>
                    <th data-column="user_name">Nombre</th>
                    <th data-column="user_login">Usuario</th>
                    <th data-column="user_email">Email</th>
                    <th data-column="user_registered">Registro</th>
                </tr>
            </thead>
            <tbody>
            #foreach($users as $user)
                <tr>
                    <td class="td-btn-actions">
                        <div class="btn-group">
                            <a href="{{ url('/dashboard/users/form/') }}{{$user->id}}" class="btn btn-primary btn-xs">
                                <span class="fa fa-user-edit"></span>
                            </a>
                            <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-delete" data-delete-action="/dashboard/users/delete/{{$user->id}}">
                                <span class="fa fa-user-minus"></span>
                            </button>
                        </div>
                    </td>
                    <td>{{$user->userName}}</td>
                    <td>{{$user->userLogin}}</td>
                    <td>{{$user->userEmail}}</td>
                    <td>{{$user->userRegistered}}</td>
                </tr>
            #endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Registro</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
{{ include('includes.modaldelete') }}
#end

#set[scripts]
#end
