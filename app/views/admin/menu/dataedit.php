<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\tables\Menu;

$subMenus = ViewController::getViewData('subMenus');
?>
<ul class="list-unstyled">
    <?php array_walk($subMenus, function(Menu $menu) {
        ViewController::sendViewData('subMenu', $menu);
        ViewController::singleView('dataparentmenu');
    }); ?>
</ul>
