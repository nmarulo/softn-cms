<?php

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\PagesManager;

ViewController::registerScript('form');
$title    = ViewController::getViewData('title');
$page     = ViewController::getViewData('page');
$method   = ViewController::getViewData('method');
$isUpdate = ViewController::getViewData('isUpdate');
$linkPage = ViewController::getViewData('linkPage');
?>
<div class="page-container">
    <div>
        <h1><?php echo $title; ?></h1>
    </div>
    <div>
        <div class="row clearfix">
            <form method="post">
                <div class="col-sm-9">
                    <div class="form-group">
                        <input class="form-control input-lg" name="<?php echo PagesManager::PAGE_TITLE; ?>" value="<?php echo $page->getPageTitle(); ?>">
                    </div>
                    <?php if ($isUpdate) { ?>
                        <div class="form-group">
                            <label><?php echo __('Enlace'); ?>: <a href="<?php echo $linkPage; ?>" target="_blank"><?php echo $linkPage; ?></a></label>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label class="control-label"><?php echo __('Contenido'); ?></label>
                        <textarea id="textContent" class="form-control" name="<?php echo PagesManager::PAGE_CONTENTS; ?>" rows="5"><?php echo $page->getPageContents(); ?></textarea>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="checkbox">
                                <label>
                                    <input name="<?php echo PagesManager::PAGE_COMMENT_STATUS; ?>" type="checkbox" <?php echo $page->getPageCommentStatus() ? 'checked' : ''; ?>> <?php echo __('Habilitar comentarios'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="content-right" class="col-sm-3">
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php echo __('Publicación'); ?></div>
                        <div class="panel-body">
                            <div class="form-group checkbox">
                                <label>
                                    <input name="<?php echo PagesManager::PAGE_STATUS; ?>" type="checkbox" <?php echo $page->getPageStatus() ? 'checked' : ''; ?>> <?php echo __('Visible'); ?>
                                </label>
                            </div>
                            <?php if ($isUpdate) { ?>
                                <button class="btn btn-primary btn-block" name="<?php echo Constants::FORM_UPDATE; ?>" value="<?php echo Constants::FORM_UPDATE; ?>"><?php echo __('Actualizar'); ?></button>
                            <?php } else { ?>
                                <button class="btn btn-primary btn-block" name="<?php echo Constants::FORM_CREATE; ?>" value="<?php echo Constants::FORM_CREATE; ?>"><?php echo __('Publicar'); ?></button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="<?php echo PagesManager::COLUMN_ID; ?>" value="<?php echo $page->getId(); ?>"/>
                <?php \SoftnCMS\util\Token::formField(); ?>
            </form>
        </div>
    </div>
</div>
