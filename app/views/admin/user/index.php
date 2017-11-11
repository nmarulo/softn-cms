<?php

use SoftnCMS\controllers\ViewController;

ViewController::registerScript('pagination');
ViewController::registerScript('delete-data');
$siteUrl = ViewController::getViewData('siteUrl') . "admin/user/";
?>
<div class="page-container" data-menu-collapse-id="user" data-url="<?php echo $siteUrl; ?>" data-reload-view="data" data-reload-action="index">
    <div>
        <h1><?php echo __('Usuarios'); ?> <a href="<?php echo $siteUrl . 'create'; ?>" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></a></h1>
    </div>
    <div id="data-container">
        <?php ViewController::singleView('data'); ?>
    </div>
    <?php ViewController::singleRootView('modaldelete'); ?>
</div>
