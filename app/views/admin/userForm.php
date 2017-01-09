<div id="user" data-collapse="#user" class="sn-content col-sm-9 col-md-10"><!-- Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1><?php echo $data['user']->isDefault() ? 'Agregar nuevo' : 'Actualizar' ?> usuario</h1>
        </div>
        <div id="content"><!-- #content -->
            <form class="form-horizontal" role="form" method="post">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Usuario</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="userLogin" value="<?php echo $data['user']->getUserLogin(); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Nombre</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="userName" value="<?php echo $data['user']->getUserName(); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">E-mail</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="userEmail" value="<?php echo $data['user']->getUserEmail(); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Contraseña</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="userPass">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Repetir contraseña</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="userPassR">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Tipo de usuario</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="userRol" value="<?php echo $data['user']->getUserRol(); ?>" disabled>
                        <!--<select class="form-control" name="user_rol">-->
                        <?php
                        //                                foreach ($dataTable['option']['rol'] as $rol) {
                        //                                    if ($rol['rol'] == $user['user_rol']) {
                        //                                        echo "<option value='$rol[rol]' selected>$rol[title]</option>";
                        //                                    } else {
                        //                                        echo "<option value='$rol[rol]'>$rol[title]</option>";
                        //                                    }
                        //                                }
                        ?>
                        <!--</select>-->
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Sitio web</label>
                    <div class="col-sm-10">
                        <input type="url" class="form-control" name="userUrl" value="<?php echo $data['user']->getUserUrl(); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <?php
                        if ($data['user']->isDefault()) {
                            echo '<button class="btn btn-primary" type="submit" name="publish" value="publish">Agregar usuario</button>';
                        } else {
                            echo '<button class="btn btn-primary" type="submit" name="update" value="update">Actualizar usuario</button>';
                        }
                        ?>
                    </div>
                </div>
                <?php $data['template']::getTokenForm(); ?>
            </form>
        </div><!-- #content -->
    </div><!-- #snwarp -->
</div><!-- Fin - Contenido -->
