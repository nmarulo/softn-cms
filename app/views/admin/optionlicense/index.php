<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\util\Arrays;

$controllers = ViewController::getViewData('controllers');
$managers    = ViewController::getViewData('managers');
var_dump($managers);
?>
<div class="page-container" data-menu-collapse-id="option">
    <div>
        <h1><?php echo __('Permisos'); ?></h1>
    </div>
    <div>
        
        <form method="post">
        <?php array_walk($controllers, function($controller) {
            ViewController::sendViewData('controllerName', Arrays::get($controller, 'controllerName'));
            ViewController::sendViewData('methods', Arrays::get($controller, 'controllerMethods'));
            ViewController::singleView('data');
        }); ?>
        </form>
    </div>
</div>
