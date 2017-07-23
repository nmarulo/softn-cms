<?php

use SoftnCMS\controllers\ViewController;

ViewController::registerScript('pagination');
ViewController::registerScript('delete-data');
$siteUrl       = ViewController::getViewData('siteUrl');
$siteUrlMenu   = $siteUrl . 'admin/menu/';
$siteUrlCreate = $siteUrlMenu . 'create/';
?>
<div class="page-container" data-url="<?php echo $siteUrlMenu; ?>">
    <div>
        <h1>Menus <a href="<?php echo $siteUrlCreate; ?>" class="btn btn-success">Nuevo menu</a></h1>
    </div>
    <div id="data-container">
        <?php ViewController::singleView('data'); ?>
    </div>
</div>
