<div class="modal fade" id="modal-manager-profile-edit-permissions" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="modal-manager-profile-edit-permissions-form" class="form-horizontal form-control-label-left" action="{{ url('/dashboard/users/profiles/form/') }}{{$component_profile->id}}" method="post">
                <div class="modal-header">
                    <h3 class="modal-title text-center">
                        Gestión permisos del perfil
                        <span class="text-uppercase">{{$component_profile->profileName}}</span>
                    </h3>
                </div>
                <div class="modal-body">
                    <div id="container-permissions-table" class="container-table-data" data-update="#container-permissions-table:.container-pagination,tbody #messages">
                        <div>
                            {{ component('pagination') }}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                #foreach($component_permissions as $permission)
                                    <tr>
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="permissionsId[]" value="{{$permission->id}}" {{$permission->checked ? 'checked': ''}}>
                                                </label>
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
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-xs-6">
                            <button type="button" class="btn btn-default btn-block" data-dismiss="modal" data-toggle="modal" data-target="#modal-manager-profile">
                                Cancelar
                            </button>
                        </div>
                        <div class="col-xs-6">
                            <button id="button-profile-edit-permissions" type="submit" class="btn btn-success btn-block">
                                Actualizar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
