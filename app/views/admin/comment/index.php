<?php

use SoftnCMS\controllers\ViewController;

ViewController::registerScript('pagination');
ViewController::registerScript('delete-data');
$siteUrl = \SoftnCMS\rute\Router::getSiteURL() . "admin/comment/";
?>
<div class="page-container" data-url="<?php echo $siteUrl; ?>">
    <div>
        <h1>Comentarios <a href="<?php echo $siteUrl . 'create'; ?>" class="btn btn-success">Nuevo comentario</a></h1>
    </div>
    <div id="data-container">
        <?php ViewController::singleView('data'); ?>
    </div>
</div>
