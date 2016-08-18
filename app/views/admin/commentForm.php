<div id="comment" class="sn-content col-sm-9 col-md-10"><!-- Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1><?php echo $data['actionUpdate'] ? 'Actualizar' : 'Publicar nuevo'; ?> comentario</h1>
        </div>
        <div id="content"><!-- #content -->
            <div class="row clearfix">
                <form role="form" method="post">
                    <div id="content-left" class="col-sm-9">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="control-label">Autor</label>
                                    <input type="text" class="form-control input-lg" name="commentAutor" value="<?php echo $data['comment']->getCommentAutor(); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Correo electronico</label>
                                    <input type="email" class="form-control input-lg" name="commentAuthorEmail" value="<?php echo $data['comment']->getCommentAuthorEmail(); ?>">
                                </div>
                                <?php if ($data['actionUpdate']) { ?>
                                    <div class="form-group">
                                        <label class="control-label">Fecha de publicación</label>
                                        <input type="text" class="form-control input-lg" name="commentDate" value="<?php echo $data['comment']->getCommentDate(); ?>" disabled>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label class="control-label">Entrada</label>
                                    <input type="text" class="form-control input-lg" name="postID" value="<?php echo $data['comment']->getPostID(); ?>">
                                </div>
                                <div class="form-group form-inline">
                                    <label class="control-label">Estado</label>
                                    <select class="form-control" name="commentStatus">
                                        <option value="0" <?php echo $data['comment']->getCommentStatus() ? '' : ' selected'; ?>>Pendiente</option>
                                        <option value="1" <?php echo $data['comment']->getCommentStatus() ? ' selected' : ''; ?>>Aprobado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Contenido del comentario</label>
                            <textarea id="textContent" class="form-control" name="commentContents" rows="5"><?php echo $data['comment']->getCommentContents(); ?></textarea>
                        </div>
                    </div>
                    <div id="content-right" class="col-sm-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">Publicación</div>
                            <div class="panel-body">
                                <?php
                                if ($data['actionUpdate']) {
                                    echo '<button class="btn btn-primary btn-block" type="submit" name="update" value="update">Actualizar</button>';
                                } else {
                                    echo '<button class="btn btn-primary btn-block" type="submit" name="publish" value="publish">Publicar</button>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- #content -->
    </div><!-- #snwarp -->
</div><!-- Fin - Contenido -->