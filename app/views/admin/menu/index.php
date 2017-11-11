<?php

use SoftnCMS\controllers\ViewController;

ViewController::registerScript('pagination');
ViewController::registerScript('delete-data');
$siteUrlMenu   = ViewController::getViewData('siteUrl') . 'admin/menu/';
$siteUrlCreate = $siteUrlMenu . 'create/';
?>
<div class="page-container" data-url="<?php echo $siteUrlMenu; ?>" data-reload-view="data" data-reload-action="index">
    <div>
        <h1><?php echo __('Menus'); ?> <a href="<?php echo $siteUrlCreate; ?>" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></a></h1>
    </div>
    <div id="data-container">
        <?php ViewController::singleView('data'); ?>
    </div>
    <?php ViewController::singleRootView('modaldelete'); ?>
</div>
