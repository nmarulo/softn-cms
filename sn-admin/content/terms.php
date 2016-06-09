<?php
get_header();
get_sidebar();
?>
<div id="terms" data-collapse="#posts" class="sn-content col-sm-9 col-md-10"><!-- Informacion - Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Etiquetas</h1>
        </div>
        <div id="content"><!-- #content -->
            <div class="row clearfix">
                <div class="col-sm-4">
                    <div id="formGroup">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control input-lg" name="term_name" placeholder="Escribe el título" value="<?php echo $term['term_name'] ?>">
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Descripción</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <textarea class="form-control" name="term_description" rows="5"><?php echo $term['term_description'] ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php
                            if (filter_input(INPUT_GET, 'action') && $_GET['action'] == 'edit') {
                                echo "<span class='spanAction btn btn-primary btn-block' data-action='update=$term[ID]'>Actualizar</span>";
                            } else {
                                echo '<span class="spanAction btn btn-primary btn-block" data-action="publish=1">Publicar</span>';
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
