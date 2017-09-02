<?php

use SoftnCMS\controllers\ViewController;

ViewController::registerScript('pagination');
ViewController::registerScript('delete-data');
$siteUrl = \SoftnCMS\rute\Router::getSiteURL() . "admin/optionlicense/";
?>
<div class="page-container" data-menu-collapse-id="option" data-url="<?php echo $siteUrl; ?>">
    <div>
        <h1><?php echo __('Configuración de Permisos'); ?>
            <a href="<?php echo $siteUrl . 'create'; ?>" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></a></h1>
    </div>
    <div id="data-container">
        <?php ViewController::singleView('data'); ?>
    </div>
</div>
