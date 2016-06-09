<?php
get_header();
get_sidebar();
?>
<div id="post-new" data-collapse="#posts" class="sn-content col-sm-9 col-md-10"><!-- Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1><?php echo $action_edit ? 'Actualizar' : 'Publicar nueva' ?> entrada</h1>
        </div>
        <div id="content"><!-- #content -->
            <div class="row clearfix">
                <form role="form" method="post">
                    <div id="content-left" class="col-sm-9">
                        <div class="form-group">
                            <input type="text" class="form-control input-lg" name="post_title" placeholder="Escribe el título" value="<?php echo $post['post_title'] ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Contenido de la entrada</label>
                            <textarea id="textContent" class="form-control" name="post_contents" rows="5"><?php echo $post['post_contents'] ?></textarea>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-inline">
                                    <label class="control-label">Comentarios</label>
                                    <select class="form-control" name="post_comment_status">
                                        <option value="1" <?php echo $post['comment_status'] ? ' selected' : ''; ?>>Abierto</option>
                                        <option value="0" <?php echo $post['comment_status'] ? '' : ' selected'; ?>>Cerrado</option>
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
                                        <option value="1" <?php echo $post['post_status'] == 1 ? ' selected' : ''; ?>>Publicar</option>
                                        <option value="0" <?php echo $post['post_status'] == 0 ? ' selected' : ''; ?>>Borrador</option>
                                    </select>
                                </div>
                                <?php
                                if ($action_edit) {
                                    $str = "<p>Ultima actualización: <span class='label label-warning'><span class='glyphicon glyphicon-time'></span> $post[post_update]</span></p>";
                                    $str .= '<button class="btn btn-primary btn-block" type="submit" name="update" value="update">Actualizar</button>';
                                    echo $str;
                                } else {
                                    echo '<button class="btn btn-primary btn-block" type="submit" name="publish" value="publish">Publicar</button>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Categorías</div>
                            <div class="panel-body">
                                <select name="relationships_category_ID[]" multiple class="form-control">
                                    <?php
                                    foreach ($dataTable['category']['dataList'] as $category) {
                                        $selected = '';
                                        if (in_array($category['ID'], $post['relationships_category_ID'])) {
                                            $selected = ' selected';
                                        }
                                        echo "<option value='$category[ID]'$selected>$category[category_name]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Etiquetas</div>
                            <div class="panel-body">
                                <select name="relationships_term_ID[]" multiple class="form-control">
                                    <?php
                                    foreach ($dataTable['term']['dataList'] as $term) {
                                        $selected = '';
                                        if (in_array($term['ID'], $post['relationships_term_ID'])) {
                                            $selected = ' selected';
                                        }
                                        echo "<option value='$term[ID]'$selected>$term[term_name]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- #content -->
        <?php get_credits(); ?>
    </div><!-- #snwarp -->
</div><!-- Fin - Contenido -->
<?php
get_footer();
