<div class="modal fade" id="modal-user-password" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="modal-user-password-form" class="form-horizontal" method="get">
                <div class="modal-header">
                    <h3 class="modal-title text-center">Modificar contraseña</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user-current-password" class="control-label col-md-2">
                            Actual
                        </label>
                        <div class="col-md-10">
                            <input type="password" class="form-control" id="user-current-password" name="userCurrentPassword" placeholder="Contraseña actual">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user-new-password" class="control-label col-md-2">
                            Nueva
                        </label>
                        <div class="col-md-10">
                            <input type="password" class="form-control" id="user-new-password" name="userNewPassword" placeholder="Nueva contraseña">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user-new-re-password" class="control-label col-md-2">
                            Repetir
                        </label>
                        <div class="col-md-10">
                            <input type="password" class="form-control" id="user-new-re-password" name="userNewRePassword" placeholder="Repetir nueva contraseña">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-xs-6">
                            <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">
                                Cancelar
                            </button>
                        </div>
                        <div class="col-xs-6">
                            <button type="submit" class="btn btn-default btn-block">Modificar</button>
                        </div>
                    </div>
                </div>
                <input name="userChangePassword" type="hidden"/>
            </form>
        </div>
    </div>
</div>
