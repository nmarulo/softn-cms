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
        <label class="col-sm-2 control-label">Repetir Contraseña:</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" name="userPassR" placeholder="Contraseña" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Correo electrónico:</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="userEmail" placeholder="Correo electrónico" value="">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" name="register" value="register">Registrar</button>
        </div>
    </div>
    <hr/>
    <div class="form-group">
        <div class="col-sm-12">
            <a href="<?php echo \LOCALHOST ?>login" class="btn btn-success btn-block">Conectar</a>
        </div>
    </div>
</form>