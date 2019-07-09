<div class="modal fade" id="modal-manager-profile" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="modal-manager-profile-form" class="form-horizontal form-control-label-left" action="{{ url('/dashboard/users/profiles/form/') }}{{$component_profile->id}}" method="post">
                <div class="modal-header">
                    <h3 class="modal-title text-center">Gestionar perfil</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="profile-name" class="control-label col-md-2">
                            Nombre
                        </label>
                        <div class="col-md-10">
                            <input id="profile-name" type="text" class="form-control" name="profileName" placeholder="Nombre" value="{{$component_profile->profileName}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="profile-key-name" class="control-label col-md-2">
                            Nombre clave
                        </label>
                        <div class="col-md-10">
                            <input id="profile-key-name" type="text" class="form-control" name="profileKeyName" placeholder="Nombre clave. Ejemplo: my_new_profile" value="{{$component_profile->profileKeyName}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="profile-description" class="control-label col-md-2">
                            Descripción
                        </label>
                        <div class="col-md-10">
                            <textarea id="profile-description" class="form-control" name="profileDescription" placeholder="Descripción" cols="3">{{$component_profile->profileDescription}}</textarea>
                        </div>
                    </div>
                    #if(!is_null($component_profile->id))
                    <div class="row clearfix">
                        <div class="col-md-10 col-md-offset-2">
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-manager-profile-edit-permissions" data-dismiss="modal">
                                    Gestionar permisos
                            </button>
                        </div>
                    </div>
                    #endif
                </div>
                <div class="modal-footer">
                    <div class="col-xs-6">
                        <button type="button" class="btn btn-default btn-block" data-dismiss="modal">
                            Cancelar
                        </button>
                    </div>
                    <div class="col-xs-6">
                        <button id="button-profile-new-update" type="submit" class="btn btn-success btn-block">
                            #if(is_null($component_profile->id))
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
