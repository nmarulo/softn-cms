<?php
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;

ViewController::registerScript('pagination');
ViewController::registerScript('delete-data');
$optionsManager = new OptionsManager();
$siteUrl        = $optionsManager->getSiteUrl() . "admin/post/";
?>
<div class="page-container" data-url="<?php echo $siteUrl; ?>">
    <div>
        <h1>Publicaciones <a href="<?php echo $siteUrl . 'create'; ?>" class="btn btn-success">Nueva entrada</a></h1>
    </div>
    <div id="data-container">
        <?php ViewController::singleView('data'); ?>
    </div>
</div>
