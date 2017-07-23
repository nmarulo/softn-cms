<?php

use SoftnCMS\models\tables\Menu;
use SoftnCMS\controllers\ViewController;

$subMenus = ViewController::getViewData('subMenus');
?>
<ul class="list-unstyled">
    <?php array_walk($subMenus, function(Menu $menu) {
        ViewController::sendViewData('subMenu', $menu);
        ViewController::singleView('dataparentmenu');
    }); ?>
</ul>
