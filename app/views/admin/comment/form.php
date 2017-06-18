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
$showAuthorAndEmail = empty($comment->getCommentUserID());
?>
<div class="page-container">
    <div>
        <h1><?php echo $title; ?></h1>
    </div>
    <div>
        <div class="row clearfix">
            <form role="form" method="post">
                <div id="content-left" class="col-sm-9">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php if ($showAuthorAndEmail) { ?>
                                <div class="form-group">
                                <label class="control-label">Autor</label>
                                <input type="text" class="form-control input-lg" name="<?php echo CommentsManager::COMMENT_AUTHOR; ?>" value="<?php echo $comment->getCommentAuthor(); ?>">
                            </div>
                                <div class="form-group">
                                <label class="control-label">Correo electrónico</label>
                                <input type="email" class="form-control input-lg" name="<?php echo CommentsManager::COMMENT_AUTHOR_EMAIL; ?>" value="<?php echo $comment->getCommentAuthorEmail(); ?>">
                            </div>
                            <?php }

                            if ($isUpdate) { ?>
                                <div class="form-group">
                                    <label class="control-label">Fecha de publicación</label>
                                    <input type="text" class="form-control input-lg" value="<?php echo $comment->getCommentDate(); ?>" disabled>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label class="control-label">Entrada</label>
                                <input type="text" class="form-control input-lg" name="<?php echo CommentsManager::POST_ID; ?>" value="<?php echo $comment->getPostID(); ?>" <?php echo $disabledPostId; ?>>
                            </div>
                            <div class="form-group form-inline checkbox">
                                <label class="control-label">
                                    <input type="checkbox" name="<?php echo CommentsManager::COMMENT_STATUS; ?>" <?php echo $comment->getCommentStatus() ? 'checked' : ''; ?>> Comentario aprobado.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Contenido del comentario</label>
                        <textarea id="textContent" class="form-control" name="<?php echo CommentsManager::COMMENT_CONTENTS; ?>" rows="5"><?php echo $comment->getCommentContents(); ?></textarea>
                    </div>
                </div>
                <div id="content-right" class="col-sm-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">Publicación</div>
                        <div class="panel-body">
                            <?php if ($isUpdate) { ?>
                                <button class="btn btn-primary btn-block" type="submit" name="<?php echo CommentsManager::FORM_UPDATE; ?>" value="<?php echo CommentsManager::FORM_UPDATE; ?>">Actualizar</button>
                            <?php } else { ?>
                                <button class="btn btn-primary btn-block" type="submit" name="<?php echo CommentsManager::FORM_CREATE; ?>" value="<?php echo CommentsManager::FORM_CREATE; ?>">Publicar</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="<?php echo CommentsManager::ID; ?>" value="<?php echo $comment->getId(); ?>"/>
                <input type="hidden" name="<?php echo CommentsManager::COMMENT_USER_ID; ?>" value="<?php echo $comment->getCommentUserID(); ?>"/>
            </form>
        </div>
    </div>
</div>
