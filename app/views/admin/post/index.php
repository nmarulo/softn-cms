<?php

use SoftnCMS\controllers\ViewController;

ViewController::registerScript('pagination');
ViewController::registerScript('delete-data');
$siteUrl = \SoftnCMS\rute\Router::getSiteURL() . "admin/post/";
?>
<div class="page-container" data-menu-collapse-id="post" data-url="<?php echo $siteUrl; ?>">
    <div>
        <h1>Publicaciones <a href="<?php echo $siteUrl . 'create'; ?>" class="btn btn-success">Nueva entrada</a></h1>
    </div>
    <div id="data-container">
        <?php ViewController::singleView('data'); ?>
    </div>
</div>
