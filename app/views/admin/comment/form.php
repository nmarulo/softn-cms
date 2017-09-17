<?php
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\CommentsManager;

$title          = ViewController::getViewData('title');
$comment        = ViewController::getViewData('comment');
$method         = ViewController::getViewData('method');
$isUpdate       = $method == CRUDManagerAbstract::FORM_UPDATE;
$disabledPostId = $isUpdate ? 'disabled="disabled"' : '';
//Mostrara los campos inputs si es de un usuario no registrado.
$showAuthorAndEmail = empty($comment->getCommentUserId());
?>
<div class="page-container">
    <div>
        <h1><?php echo $title; ?></h1>
    </div>
    <div>
        <div class="row clearfix">
            <form method="post">
                <div id="content-left" class="col-sm-9">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php if ($showAuthorAndEmail) { ?>
                                <div class="form-group">
                                <label class="control-label"><?php echo __('Autor'); ?></label>
                                <input class="form-control input-lg" name="<?php echo CommentsManager::COMMENT_AUTHOR; ?>" value="<?php echo $comment->getCommentAuthor(); ?>">
                            </div>
                                <div class="form-group">
                                <label class="control-label"><?php echo __('Correo electrónico'); ?></label>
                                <input type="email" class="form-control input-lg" name="<?php echo CommentsManager::COMMENT_AUTHOR_EMAIL; ?>" value="<?php echo $comment->getCommentAuthorEmail(); ?>">
                            </div>
                            <?php }

                            if ($isUpdate) { ?>
                                <div class="form-group">
                                    <label class="control-label"><?php echo __('Fecha de publicación'); ?></label>
                                    <input class="form-control input-lg" value="<?php echo $comment->getCommentDate(); ?>" disabled>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label class="control-label"><?php echo __('Entrada'); ?></label>
                                <input class="form-control input-lg" name="<?php echo CommentsManager::POST_ID; ?>" value="<?php echo $comment->getPostId(); ?>" <?php echo $disabledPostId; ?>>
                            </div>
                            <div class="form-group form-inline checkbox">
                                <label class="control-label">
                                    <input type="checkbox" name="<?php echo CommentsManager::COMMENT_STATUS; ?>" <?php echo $comment->getCommentStatus() ? 'checked' : ''; ?>>
                                    <?php echo __('Comentario aprobado'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo __('Contenido del comentario'); ?></label>
                        <textarea id="textContent" class="form-control" name="<?php echo CommentsManager::COMMENT_CONTENTS; ?>" rows="5"><?php echo $comment->getCommentContents(); ?></textarea>
                    </div>
                </div>
                <div id="content-right" class="col-sm-3">
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php echo __('Publicación'); ?></div>
                        <div class="panel-body">
                            <?php if ($isUpdate) { ?>
                                <button class="btn btn-primary btn-block" name="<?php echo CommentsManager::FORM_UPDATE; ?>" value="<?php echo CommentsManager::FORM_UPDATE; ?>"><?php  echo __('Actualizar');?></button>
                            <?php } else { ?>
                                <button class="btn btn-primary btn-block" name="<?php echo CommentsManager::FORM_CREATE; ?>" value="<?php echo CommentsManager::FORM_CREATE; ?>"><?php echo __('Publicar'); ?></button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="<?php echo CommentsManager::ID; ?>" value="<?php echo $comment->getId(); ?>"/>
                <input type="hidden" name="<?php echo CommentsManager::COMMENT_USER_ID; ?>" value="<?php echo $comment->getCommentUserId(); ?>"/>
                <?php \SoftnCMS\util\Token::formField(); ?>
            </form>
        </div>
    </div>
</div>
