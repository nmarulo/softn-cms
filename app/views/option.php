<div id="options" class="sn-content col-sm-9 col-md-10"><!-- Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Configuración general</h1>
        </div>
        <div id="content"><!-- #content -->
            <form class="form-horizontal" role="form" method="post">
                <div class="form-group">
                    <label for="inputTituloWeb" class="col-sm-2 control-label">Título del sitio</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="optionTitle" value="<?php echo $data['optionTitle']->getOptionValue(); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputDescripcion" class="col-sm-2 control-label">Descripción corta</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="optionDescription" value="<?php echo $data['optionDescription']->getOptionValue(); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmailAdmin" class="col-sm-2 control-label">E-mail administrador</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="optionEmailAdmin" value="<?php echo $data['optionEmailAdmin']->getOptionValue(); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="optionSiteUrl" class="col-sm-2 control-label">Dirrección URL</label>
                    <div class="col-sm-10">
                        <input type="url" class="form-control" name="optionSiteUrl"  value="<?php echo $data['optionSiteUrl']->getOptionValue(); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="optionPaged" class="col-sm-2 control-label">Paginación</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="optionPaged" min="1" value="<?php echo $data['optionPaged']->getOptionValue(); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="optionTheme" class="col-sm-2 control-label">Seleccionar plantilla</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="optionTheme" value="<?php echo $data['optionTheme']->getOptionValue();?>" disabled>
<!--                        <select class="form-control" name="optionTheme">
                            <?php // echo $data['optionTheme']; ?>
                        </select>-->
                    </div>
                </div>
                <div class="form-group">
                    <label for="optionMenu" class="col-sm-2 control-label">Seleccionar menu</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="optionMenu" value="<?php echo $data['optionMenu']->getOptionValue();?>" disabled>
<!--                        <select class="form-control" name="optionMenu">
                            <option value="0">--ninguno--</option>
                            <?php // echo $data['optionMenu']; ?>
                        </select>-->
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button class="btn btn-primary" type="submit" name="update" value="update">Guardar cambios</button>
                    </div>
                </div>
            </form>
        </div><!-- #content -->
    </div><!-- #snwarp -->
</div><!-- Fin - Contenido -->