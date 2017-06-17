<?php
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\managers\CommentsManager;

$postTemplate     = ViewController::getViewData('post');
$siteUrl          = $postTemplate->getSiteUrl();
$urlUser          = $siteUrl . 'user/';
$post             = $postTemplate->getPost();
$commentsTemplate = $postTemplate->getCommentsTemplate();
$usersManager     = new UsersManager();
$userSession      = $usersManager->searchById(LoginManager::getSession());

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
        <?php foreach ($commentsTemplate as $commentTemplate) {
            $commentTemplate->initUser();
            $comment      = $commentTemplate->getComment();
            $userTemplate = $commentTemplate->getUserTemplate();
            ?>
            <div id="comment-<?php echo $comment->getID(); ?>" class="media">
                <div class="media-left">
                    <?php if (empty($userTemplate)) { ?>
                        <img class="media-object" src="<?php echo $siteUrl; ?>app/themes/softncmsv1/resources/img/avatar.svg">
                    <?php } else {
                        $user = $userTemplate->getUser();
                        ?>
                        <a href="<?php echo $urlUser . $user->getId(); ?>">
                            <img class="media-object" src="<?php echo $siteUrl; ?>app/themes/softncmsv1/resources/img/avatar.svg">
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
        <?php } ?>
    </div>
<?php }

if ($post->getPostCommentStatus()) {
    ?>
    <div id="container-comments-form" class="clearfix">
        <h2>Publicar comentario</h2>
        <form method="post">
            <?php if (LoginManager::isLogin()) { ?>
                <p>
                    Conectado como <strong><?php echo $userSession->getUserName(); ?></strong>
                </p>
                <input type="hidden" name="<?php echo CommentsManager::COMMENT_USER_ID; ?>" value="<?php echo $userSession->getId(); ?>"/>
            <?php } else { ?>
                <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control" name="<?php echo CommentsManager::COMMENT_AUTHOR; ?>"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Correo electrónico</label>
                    <input type="email" class="form-control" name="<?php echo CommentsManager::COMMENT_AUTHOR_EMAIL; ?>"/>
                </div>
            <?php } ?>
            <div class="form-group">
                <label class="control-label">Comentario</label>
                <textarea class="form-control" name="<?php echo CommentsManager::COMMENT_CONTENTS; ?>" rows="5"></textarea>
            </div>
            <input type="hidden" name="<?php echo CommentsManager::POST_ID; ?>" value="<?php echo $post->getId(); ?>"/>
            <button class="btn btn-primary" name="<?php echo CommentsManager::FORM_SUBMIT; ?>" value="<?php echo CommentsManager::FORM_SUBMIT; ?>" type="submit">Publicar</button>
        </form>
    </div>
<?php } else {
    ?>
    <div class="alert alert-info">Los comentarios están cerrados.</div>
<?php
}
