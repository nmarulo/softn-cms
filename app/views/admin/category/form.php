<?php

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\CategoriesManager;

$category = ViewController::getViewData('category');
$title    = ViewController::getViewData('title');
$isUpdate = ViewController::getViewData('isUpdate');
?>
<div class="page-container" data-menu-collapse-id="post">
    <div>
        <h1><?php echo $title; ?></h1>
    </div>
    <div>
        <form method="post">
            <div id="content-left" class="col-sm-9">
                <div class="form-group">
                    <label class="control-label"><?php echo __('Nombre'); ?></label>
                    <input class="form-control" name="<?php echo CategoriesManager::CATEGORY_NAME; ?>" placeholder="Escribe el título" value="<?php echo $category->getCategoryName(); ?>">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo __('Descripción'); ?></label>
                    <textarea class="form-control" name="<?php echo CategoriesManager::CATEGORY_DESCRIPTION; ?>" rows="5"><?php echo $category->getCategoryDescription(); ?></textarea>
                </div>
            </div>
            <div id="content-right" class="col-sm-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><?php echo __('Publicación'); ?></div>
                    <div class="panel-body">
                        <?php if ($isUpdate) { ?>
                            <button class="btn btn-primary btn-block" name="<?php echo Constants::FORM_UPDATE; ?>" value="<?php echo Constants::FORM_UPDATE; ?>"><?php echo __('Actualizar'); ?></button>
                        <?php } else { ?>
                            <button class="btn btn-primary btn-block" name="<?php echo Constants::FORM_CREATE; ?>" value="<?php echo Constants::FORM_CREATE; ?>"><?php echo __('Publicar'); ?></button>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="<?php echo CategoriesManager::COLUMN_ID; ?>" value="<?php echo $category->getId(); ?>"/>
            <?php \SoftnCMS\util\Token::formField(); ?>
        </form>
    </div>
</div>
