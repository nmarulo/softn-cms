<?php
get_header();
get_sidebar();
?>
<div id="page-new" data-collapse="#pages" class="sn-content col-sm-9 col-md-10"><!-- Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1><?php echo $action_edit ? 'Actualizar' : 'Publicar nueva' ?> pagina</h1>
        </div>
        <div id="content"><!-- #content -->
            <form role="form" method="post">
                <div class="row clearfix">
                    <div id="content-left" class="col-sm-9">
                        <div class="form-group">
                            <input type="text" class="form-control input-lg" name="post_title" placeholder="Escribe el título" value="<?php echo $page['post_title'] ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Contenido de la pagina</label>
                            <textarea id="textContent" class="form-control" name="post_contents" rows="5"><?php echo $page['post_contents'] ?></textarea>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-inline">
                                    <label class="control-label">Comentarios</label>
                                    <select class="form-control" name="post_comment_status">
                                        <option value="1" <?php echo $page['comment_status'] ? ' selected' : ''; ?>>Abierto</option>
                                        <option value="0" <?php echo $page['comment_status'] ? '' : ' selected'; ?>>Cerrado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="content-right" class="col-sm-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">Publicación</div>
                            <div class="panel-body">
                                <div class="form-group form-inline">
                                    <label class="control-label">Estado</label>
                                    <select class="form-control" name="post_status">
                                        <option value="1" <?php echo $page['post_status'] ? ' selected' : ''; ?>>Publicar</option>
                                        <option value="0" <?php echo $page['post_status'] ? '' : ' selected'; ?>>Borrador</option>
                                    </select>
                                </div>
                                <?php
                                if ($action_edit) {
                                    $str = "<p>Ultima actualización: <span class='label label-warning'><span class='glyphicon glyphicon-time'></span> $page[post_update]</span></p>";
                                    $str .= '<button class="btn btn-primary btn-block" type="submit" name="update" value="update">Actualizar</button>';
                                    echo $str;
                                } else {
                                    echo '<button class="btn btn-primary btn-block" type="submit" name="publish" value="publish">Publicar</button>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div><!-- #content -->
        <?php get_credits(); ?>
    </div><!-- #snwarp -->
</div><!-- Fin - Contenido -->
<?php
get_footer();
