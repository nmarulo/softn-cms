<div class="modal fade" id="modal-manager-permission" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="modal-manager-permission-form" class="form-horizontal form-control-label-left" action="{{ url('/dashboard/users/permissions/form/') }}{{$component_permission->id}}" method="post">
                <div class="modal-header">
                    <h3 class="modal-title text-center">Gestionar permiso</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="permission-name" class="control-label col-md-2">
                            Nombre
                        </label>
                        <div class="col-md-10">
                            <input id="permission-name" type="text" class="form-control" name="permissionName" placeholder="Nombre" value="{{$component_permission->permissionName}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="permission-key-name" class="control-label col-md-2">
                            Nombre clave
                        </label>
                        <div class="col-md-10">
                            <input id="permission-key-name" type="text" class="form-control" name="permissionKeyName" placeholder="Nombre clave. Ejemplo: my_new_permission" value="{{$component_permission->permissionKeyName}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="permission-description" class="control-label col-md-2">
                            Descripción
                        </label>
                        <div class="col-md-10">
                            <textarea id="permission-description" class="form-control" name="permissionDescription" placeholder="Descripción" cols="3">{{$component_permission->permissionDescription}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-6">
                        <button type="button" class="btn btn-default btn-block" data-dismiss="modal">
                            Cancelar
                        </button>
                    </div>
                    <div class="col-xs-6">
                        <button id="button-permission-new-update" type="submit" class="btn btn-success btn-block">
                            #if(is_null($component_permission->id))
                                Crear
                            #else
                                Actualizar
                            #endif
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
