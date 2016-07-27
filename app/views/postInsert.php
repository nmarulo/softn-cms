<div id="post-new" data-collapse="#posts" class="sn-content col-sm-9 col-md-10"><!-- Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1><?php echo $data['actionUpdate'] ? 'Actualizar' : 'Publicar nueva' ?> entrada</h1>
        </div>
        <div id="content"><!-- #content -->
            <div class="row clearfix">
                <form role="form" method="post">
                    <div id="content-left" class="col-sm-9">
                        <div class="form-group">
                            <input type="text" class="form-control input-lg" name="postTitle" placeholder="Escribe el título" value="<?php echo $data['post']->getPostTitle(); ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Contenido de la entrada</label>
                            <textarea id="textContent" class="form-control" name="postContents" rows="5"><?php echo $data['post']->getPostContents(); ?></textarea>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-inline">
                                    <label class="control-label">Comentarios</label>
                                    <select class="form-control" name="commentStatus">
                                        <option value="1" <?php echo $data['post']->getCommentStatus() ? ' selected' : ''; ?>>Abierto</option>
                                        <option value="0" <?php echo $data['post']->getCommentStatus() ? '' : ' selected'; ?>>Cerrado</option>
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
                                    <select class="form-control" name="postStatus">
                                        <option value="1" <?php echo $data['post']->getPostStatus() ? ' selected' : ''; ?>>Publicar</option>
                                        <option value="0" <?php echo $data['post']->getPostStatus() ? '' : ' selected'; ?>>Borrador</option>
                                    </select>
                                </div>
                                <?php
                                if ($data['actionUpdate']) {
                                    $str = '<p>Ultima actualización: <span class="label label-warning"><span class="glyphicon glyphicon-time"></span> ' . $data['post']->getPostUpdate() . '</span></p>';
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
                                <select name="relationshipsCategoryID[]" multiple class="form-control">
                                    <option value=''>category</option>
                                </select>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Etiquetas</div>
                            <div class="panel-body">
                                <select name="relationshipsTermID[]" multiple class="form-control">
                                    <option value=''>term</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- #content -->
    </div><!-- #snwarp -->
</div><!-- Fin - Contenido -->