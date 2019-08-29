{{ extends('layouts.master') }}

#set[header]
<h1>
    Permisos
    <small>Gestión de permisos</small>
</h1>
#end

#set[breadcrumb]
<li>
    <a href="#"><i class="fa fa-users"></i> Usuarios</a>
</li>
<li class="active">Permisos</li>
#end

#set[content]
<div id="container-permissions-table" class="box container-table-data" data-update="#container-permissions-table:.container-pagination,tbody #messages">
    <div class="box-header row">
        <div class="col-sm-6">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-manager-permission" data-call-ajax="/dashboard/users/permissions" data-call-method="GET" data-update="#modal-manager-permission #messages">
                <span class="fa fa-plus-circle"></span>
                <small>Agregar permiso</small>
            </button>
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
                    <th>Nombre</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
            #foreach($permissions as $permission)
                <tr>
                    <td class="td-btn-actions">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-xs" data-call-ajax="/dashboard/users/permissions/{{$permission->id}}" data-call-method="POST" data-update="#modal-manager-permission #messages" data-toggle="modal" data-target="#modal-manager-permission">
                                <span class="fa fa-edit"></span>
                            </button>
                            <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-delete" data-delete-action="/dashboard/users/permissions/delete/{{$permission->id}}">
                                <span class="fa fa-minus"></span>
                            </button>
                        </div>
                    </td>
                    <td>{{$permission->permissionName}}</td>
                    <td>{{$permission->permissionDescription}}</td>
                </tr>
            #endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
{{ include('includes.modaldelete') }}
{{ component('user.permissions.modalform') }}
#end

#set[scripts]
#end
