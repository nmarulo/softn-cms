<?php
get_header();
get_sidebar();
?>
<div id="comment-edit" class="sn-content col-sm-9 col-md-10"><!-- Contenido -->
    <div id="snwrap"><!-- #snwarp -->
        <div id="header" class="clearfix">
            <br/>
            <h1>Editar comentario</h1>
        </div>
        <div id="content"><!-- #content -->
            <div class="row clearfix">
                <form role="form" method="post">
                    <div id="content-left" class="col-sm-9">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="control-label">Autor</label>
                                    <input type="text" class="form-control input-lg" name="comment_autor" value="<?php echo $comment['comment_autor'] ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Correo electronico</label>
                                    <input type="email" class="form-control input-lg" name="comment_author_email" value="<?php echo $comment['comment_author_email'] ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Fecha de publicación</label>
                                    <input type="text" class="form-control input-lg" name="comment_date" value="<?php echo $comment['comment_date'] ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Entrada</label>
                                    <p class="input-lg">
                                        <a href="<?php echo $comment['comment_post_url']; ?>" target="_blank"><?php echo $comment['comment_post_title'] ?></a>
                                    </p>
                                </div>
                                <div class="form-group form-inline">
                                    <label class="control-label">Estado</label>
                                    <select class="form-control" name="comment_status">
                                        <option value="0" <?php echo $comment['comment_status'] ? '' : ' selected'; ?>>Pendiente</option>
                                        <option value="1" <?php echo $comment['comment_status'] ? ' selected' : ''; ?>>Aprobado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Contenido del comentario</label>
                            <textarea id="textContent" class="form-control" name="comment_contents" rows="5"><?php echo $comment['comment_contents'] ?></textarea>
                        </div>
                    </div>
                    <div id="content-right" class="col-sm-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">Publicación</div>
                            <div class="panel-body">
                                <button class="btn btn-primary btn-block" type="submit" name="update" value="update">Actualizar</button>
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
