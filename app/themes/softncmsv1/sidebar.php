<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\tables\Sidebar;

$sidebars = ViewController::getViewData('sidebars');
?>
<aside>
    <?php array_walk($sidebars, function(Sidebar $sidebar) { ?>
        <div class="sidebar">
            <div class="sidebar-title"><?php echo $sidebar->getSidebarTitle(); ?></div>
            <div class="sidebar-content"><?php echo $sidebar->getSidebarContents(); ?></div>
        </div>
    <?php }); ?>
</aside>
