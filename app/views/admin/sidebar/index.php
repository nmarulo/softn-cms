<?php

use SoftnCMS\controllers\ViewController;

ViewController::registerScript('delete-data');
$siteUrl = \SoftnCMS\rute\Router::getSiteURL() . "admin/sidebar/";
?>
<div class="page-container" data-url="<?php echo $siteUrl; ?>">
    <div>
        <h1>Barras laterales <a href="<?php echo $siteUrl . 'create'; ?>" class="btn btn-success">Nuevo</a></h1>
    </div>
    <div id="data-container">
        <?php ViewController::singleView('data'); ?>
    </div>
</div>
