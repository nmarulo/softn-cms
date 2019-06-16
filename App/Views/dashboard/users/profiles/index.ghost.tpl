{{ extends('layouts.master') }}

#set[header]
<h1>
    Perfiles
    <small>Gestión de perfiles</small>
</h1>
#end

#set[breadcrumb]
<li>
    <a href="#"><i class="fa fa-users"></i> Usuarios</a>
</li>
<li class="active">Perfiles</li>
#end

#set[content]
<div id="container-profiles-table" class="box container-table-data" data-update="#container-profiles-table:.container-pagination,tbody #messages">
    <div class="box-header">
        <div class="col-sm-6">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-manager-profile" data-call-ajax="/dashboard/users/profiles" data-call-method="GET" data-update="#modal-manager-profile #messages">
                <span class="fa fa-plus-circle"></span>
                <small>Agregar perfil</small>
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
            #foreach($profiles as $profile)
                <tr>
                    <td class="td-btn-actions">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-xs" data-call-ajax="/dashboard/users/profiles/{{$profile->id}}" data-call-method="POST" data-update="#modal-manager-profile #messages" data-toggle="modal" data-target="#modal-manager-profile">
                                <span class="fa fa-edit"></span>
                            </button>
                            <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-delete" data-delete-action="/dashboard/users/profiles/delete/{{$profile->id}}">
                                <span class="fa fa-minus"></span>
                            </button>
                        </div>
                    </td>
                    <td>{{$profile->profileName}}</td>
                    <td>{{$profile->profileDescription}}</td>
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
{{ component('user.profiles.modalform') }}
#end

#set[scripts]
#end
