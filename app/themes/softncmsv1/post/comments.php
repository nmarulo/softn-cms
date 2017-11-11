<?php

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\models\template\CommentTemplate;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\CommentsManager;

$postTemplate     = ViewController::getViewData('post');
$siteUrl          = $postTemplate->getSiteUrl();
$urlUser          = $siteUrl . 'user/';
$post             = $postTemplate->getPost();
$commentsTemplate = $postTemplate->getCommentsTemplate();
$userSession      = ViewController::getViewData('userSession');

if (!empty($commentsTemplate)) {
    ?>
    <div id="container-comments" class="clearfix">
        <h2>
            Comentarios
            <small>
                <span class="label label-warning">
                    <?php echo $post->getPostCommentCount(); ?>
                </span>
            </small>
        </h2>
        <?php array_walk($commentsTemplate, function(CommentTemplate $commentTemplate) use ($siteUrl, $urlUser) {
            $commentTemplate->initUser();
            $comment      = $commentTemplate->getComment();
            $userTemplate = $commentTemplate->getUserTemplate();
            ?>
            <div id="comment-<?php echo $comment->getID(); ?>" class="media">
                <div class="media-left">
                    <?php if (empty($userTemplate)) { ?>
                        <img class="media-object" src="<?php echo $commentTemplate->getDefaultUserImage(); ?>">
                    <?php } else {
                        $user = $userTemplate->getUser();
                        ?>
                        <a href="<?php echo $urlUser . $user->getId(); ?>">
                            <img class="media-object" src="<?php echo $user->getUserUrlImage(); ?>">
                        </a>
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
        <?php }); ?>
    </div>
<?php }

if ($post->getPostCommentStatus()) {
    ?>
    <div id="container-comments-form" class="clearfix">
        <h2>Publicar comentario</h2>
        <?php if ($postTemplate->isCanCommentAnyUser() || !empty($userSession)) { ?>
            <form method="post">
            <?php if (empty($userSession)) { ?>
                <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input class="form-control" name="<?php echo CommentsManager::COMMENT_AUTHOR; ?>"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Correo electrónico</label>
                    <input type="email" class="form-control" name="<?php echo CommentsManager::COMMENT_AUTHOR_EMAIL; ?>"/>
                </div>
            <?php } else { ?>
                <p>Conectado como <strong><?php echo $userSession->getUserName(); ?></strong></p>
                <input type="hidden" name="<?php echo CommentsManager::COMMENT_USER_ID; ?>" value="<?php echo $userSession->getId(); ?>"/>
            <?php } ?>
                <div class="form-group">
                <label class="control-label">Comentario</label>
                <textarea class="form-control" name="<?php echo CommentsManager::COMMENT_CONTENTS; ?>" rows="5"></textarea>
            </div>
            <input type="hidden" name="<?php echo CommentsManager::POST_ID; ?>" value="<?php echo $post->getId(); ?>"/>
            <button class="btn btn-primary" name="<?php echo Constants::FORM_SUBMIT; ?>" value="<?php echo Constants::FORM_SUBMIT; ?>">Publicar</button>
                <?php \SoftnCMS\util\Token::formField(); ?>
        </form>
        <?php } else { ?>
            <div class="alert alert-info">Debes iniciar sesión para comentar.</div>
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="alert alert-info">Los comentarios están cerrados.</div>
<?php }
