<?php

use SoftnCMS\controllers\ViewController;

ViewController::registerScript('delete-data');
$siteUrl       = ViewController::getViewData('siteUrl');
$siteUrlUpdate = $siteUrl . 'admin/menu/update/';
$siteUrlCreate = $siteUrl . 'admin/menu/create/?parentMenu=';
$siteUrlMenu   = $siteUrl . 'admin/menu/';
$menu          = ViewController::getViewData('menu');
$menuId        = $menu->getId();
?>
<div class="page-container" data-url="<?php echo $siteUrlMenu; ?>" data-add-url="<?php echo "edit=$menuId"; ?>">
    <div>
        <h1><?php echo __('Menu'); ?>: <?php echo $menu->getMenuTitle(); ?>
            <a class="btn btn-success" href="<?php echo $siteUrlCreate . $menuId; ?>" title="Agregar"><span class="glyphicon glyphicon-plus"></span></a>
            <a class="btn btn-primary" href="<?php echo $siteUrlUpdate . $menuId; ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
        </h1>
    </div>
    <div id="data-container">
        <?php ViewController::singleView('dataedit'); ?>
    </div>
    <?php ViewController::singleRootView('modaldelete'); ?>
</div>
