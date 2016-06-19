<?php
get_header();
get_sidebar();
?>
<div id="sidebars" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Barra lateral</h1>
        </div>
        <div id="content"><!-- #content -->
            <div class="row clearfix">
                <div class="col-sm-4">
                    <div id="formGroup">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control input-lg" name="sidebar_title" placeholder="Escribe el título" value="<?php echo $sidebar['sidebar_title']; ?>">
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Contenido</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <textarea class="form-control" name="sidebar_contents" rows="5"><?php echo $sidebar['sidebar_contents']; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php
                            if ($action_edit) {
                                echo "<button class='btnAction btn btn-primary btn-block' data-action='update=$sidebar[ID]'>Actualizar</span>";
                            } else {
                                echo '<button class="btnAction btn btn-primary btn-block" data-action="publish=1">Publicar</span>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div id="reloadData" class="col-sm-8">
                    <?php reloadData(); ?>
                </div>
            </div>
        </div><!-- #content -->
        <?php get_credits(); ?>
    </div><!-- #snwarp -->
</div><!-- Fin - Informacion - Contenido -->
<?php
get_footer();
