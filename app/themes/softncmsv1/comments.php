<?php if ($post->hasPostComments()) { ?>
    <div id="container-comments" class="clearfix">
        <h2>
            Comentarios
            <small>
                <span class="label label-warning">
                    <?php echo $post->getCommentCount(); ?>
                </span>
            </small>
        </h2>
        <?php foreach ($post->getPostComments() as $comment) { ?>
            <div id="comment-<?php echo $comment->getID(); ?>" class="media">
                <div class="media-left">
                    <?php if ($comment->isRegisteredUser()) { ?>
                        <a href="<?php $comment->getUrlCommentUser() ?>">
                            <img class="media-object" src="<?php $data['template']::getUrlTheme() ?>img/avatar.svg">
                        </a>
                    <?php } else { ?>
                        <img class="media-object" src="<?php $data['template']::getUrlTheme() ?>img/avatar.svg">
                    <?php } ?>
                </div>
                <div class="media-body">
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <strong><?php echo $comment->getCommentAuthor(); ?></strong>
                        </div>
                        <div class="col-md-6"><?php echo $comment->getCommentDate(); ?></div>
                    </div>
                    <p><?php echo $comment->getCommentContents(); ?></p>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php
}

if ($post->getCommentStatus()) {
    ?>
    <div id="container-comments-form" class="clearfix">
        <h2>Publicar comentario</h2>
        <form method="post">
            <?php if ($data['template']::isLogin()) { ?>
                <p>
                    Conectado como <strong>
                        <?php $data['template']::getUserName(); ?></strong>
                </p>
                <input type="hidden" name="commentUserID" value="<?php echo $data['template']::getUserLoginID(); ?>"/>
            <?php } else { ?>
                <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control" name="commentAuthor"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Correo electrónico</label>
                    <input type="email" class="form-control" name="commentAuthorEmail"/>
                </div>
            <?php } ?>
            <div class="form-group">
                <label class="control-label">Comentario</label>
                <textarea class="form-control" name="commentContents" rows="5"></textarea>
            </div>
            <button class="btn btn-primary" name="publish" value="publish" type="submit">Publicar</button>
            <?php $data['template']::getTokenForm(); ?>
        </form>
    </div>
<?php } else { ?>
    <div class="alert alert-info">Los comentarios están cerrados.</div>
    <?php
}//end if
