<div class="modal fade" id="modal-user-password" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="modal-user-password-form" class="form-horizontal form-control-label-left" action="{{ url('/dashboard/users/form/password/') }}{{$user->id}}" method="post">
                <div class="modal-header">
                    <h3 class="modal-title text-center">Modificar contraseña</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="input-user-current-password" class="control-label col-sm-4">
                            Actual
                        </label>
                        <div class="col-sm-8">
                            <input id="input-user-current-password" type="password" class="form-control" name="userCurrentPassword" placeholder="Contraseña actual">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input-user-new-password" class="control-label col-sm-4">
                            Nueva
                        </label>
                        <div class="col-sm-8">
                            <input id="input-user-new-password" type="password" class="form-control" name="userPassword" placeholder="Nueva contraseña">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input-user-new-password-re" class="control-label col-sm-4">
                            Repetir
                        </label>
                        <div class="col-sm-8">
                            <input id="input-user-new-password-re" type="password" class="form-control" name="userPasswordRe" placeholder="Repetir nueva contraseña">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-sm-6">
                            <button id="button-password-cancel" type="button" class="btn btn-primary btn-block" data-dismiss="modal">
                                Cancelar
                            </button>
                        </div>
                        <br class="visible-xs"/>
                        <div class="col-sm-6">
                            <button id="button-password-update" type="submit" class="btn btn-default btn-block">Modificar</button>
                        </div>
                    </div>
                </div>
                <input name="userChangePassword" type="hidden"/>
            </form>
        </div>
    </div>
</div>
