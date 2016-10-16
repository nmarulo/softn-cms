<form class="form-horizontal" role="form" method="post">
    <div class="form-group">
        <label class="col-sm-2 control-label">Usuario:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="userLogin" placeholder="Usuario" autofocus="true" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Contraseña:</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" name="userPass" placeholder="Contraseña" value="">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
                <label>
                    <input class="" type="checkbox" name="userRememberMe"> Mantener la sesión iniciada
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" name="login" value="login">Conectar</button>
        </div>
    </div>
    <hr/>
    <div class="form-group">
        <div class="col-sm-12">
            <a href="<?php $data['template']::getUrlRegister(); ?>" class="btn btn-success btn-block">Registro</a>
        </div>
    </div>
    <?php $data['template']::getTokenForm(); ?>
</form>
