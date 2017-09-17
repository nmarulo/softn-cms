<?php
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\SidebarsManager;

$sidebar  = ViewController::getViewData('sidebar');
$title    = ViewController::getViewData('title');
$method   = ViewController::getViewData('method');
$isUpdate = $method == SidebarsManager::FORM_UPDATE;
?>
<div class="page-container">
    <div>
        <h1><?php echo $title; ?></h1>
    </div>
    <div>
        <form role="form" method="post">
            <div id="content-left" class="col-sm-9">
                <div class="form-group">
                    <label class="control-label"><?php echo __('Título'); ?></label>
                    <input class="form-control" name="<?php echo SidebarsManager::SIDEBAR_TITLE; ?>" value="<?php echo $sidebar->getSidebarTitle(); ?>">
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo __('Descripción'); ?></label>
                    <textarea class="form-control" name="<?php echo SidebarsManager::SIDEBAR_CONTENTS; ?>" rows="5"><?php echo $sidebar->getSidebarContents(); ?></textarea>
                </div>
            </div>
            <div id="content-right" class="col-sm-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><?php echo __('Publicación'); ?></div>
                    <div class="panel-body">
                        <?php if ($isUpdate) { ?>
                            <button class="btn btn-primary btn-block" name="<?php echo SidebarsManager::FORM_UPDATE; ?>" value="<?php echo SidebarsManager::FORM_UPDATE; ?>"><?php echo __('Actualizar'); ?></button>
                        <?php } else { ?>
                            <button class="btn btn-primary btn-block" name="<?php echo SidebarsManager::FORM_CREATE; ?>" value="<?php echo SidebarsManager::FORM_CREATE; ?>"><?php echo __('Publicar'); ?></button>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="<?php echo SidebarsManager::ID; ?>" value="<?php echo $sidebar->getId(); ?>"/>
            <?php \SoftnCMS\util\Token::formField(); ?>
        </form>
    </div>
</div>
