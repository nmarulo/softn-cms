<?php
get_header('login');
?>
<div id="login" class="sn-content center-block">
    <div class="panel panel-default clearfix">
        <div class="panel-body">
            <div id="logo-SoftN">
                <img class="center-block" src="sn-admin/img/logo.png" alt="CMS - SoftN"/>
            </div>
            <hr />

            <form class="form-horizontal" role="form" method="post">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Usuario:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="user_login" placeholder="Usuario" autofocus="true" value="<?php echo $auxLogin['user_login']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Contraseña:</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="user_pass" placeholder="Contraseña" value="<?php echo $auxLogin['user_pass']; ?>">
                    </div>
                </div>
                <?php
                if (isset($_GET['action']) && $_GET['action'] == 'register') {
                    ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Repetir Contraseña:</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="ruser_pass" placeholder="Contraseña" value="<?php echo $auxLogin['ruser_pass']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Correo electrónico:</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="user_email" placeholder="Correo electrónico" value="<?php echo $auxLogin['user_email']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" name="signup" value="signup">Registrar</button>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <a href="<?php siteUrl(true); ?>login.php" class="btn btn-success btn-block">Conectar</a>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input class="" type="checkbox" name="user_rememberMe"> Mantener la sesión iniciada
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" name="signin" value="signin">Conectar</button>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <a href="?action=register" class="btn btn-success btn-block">Registro</a>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </form>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <a href="<?php siteUrl(true); ?>">Volver a la pagina principal.</a>
        </div>
    </div>
</div>
<?php
get_footer();
