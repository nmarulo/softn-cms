<?php
get_header();
get_sidebar();
?>
<div id="user-new" data-collapse="#users" class="sn-content col-sm-9 col-md-10"><!-- Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1><?php echo $action_edit ? 'Actualizar' : 'Agregar nuevo' ?> usuario</h1>
        </div>
        <div id="content"><!-- #content -->
            <form class="form-horizontal" role="form" method="post">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Usuario</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="user_login" value="<?php echo $user['user_login']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Nombre</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="user_name" value="<?php echo $user['user_name']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">E-mail</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="user_email" value="<?php echo $user['user_email']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Contraseña</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="user_pass">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Repetir contraseña</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="user_pass2">
                    </div>
                </div>
                <?php if (SN_Users::checkRol()) { ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Tipo de usuario</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="user_rol">
                                <?php
                                foreach ($dataTable['option']['rol'] as $rol) {
                                    if ($rol['rol'] == $user['user_rol']) {
                                        echo "<option value='$rol[rol]' selected>$rol[title]</option>";
                                    } else {
                                        echo "<option value='$rol[rol]'>$rol[title]</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Sitio web</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="user_url" value="<?php echo $user['user_url']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <?php
                        if ($action_edit) {
                            echo '<button class="btn btn-primary" type="submit" name="update" value="update">Actualizar usuario</button>';
                        } else {
                            echo '<button class="btn btn-primary" type="submit" name="publish" value="publish">Agregar usuario</button>';
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div><!-- #content -->
        <?php get_credits(); ?>
    </div><!-- #snwarp -->
</div><!-- Fin - Contenido -->
<?php
get_footer();
