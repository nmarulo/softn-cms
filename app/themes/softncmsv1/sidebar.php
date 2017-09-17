<?php

use SoftnCMS\models\managers\SidebarsManager;
use SoftnCMS\models\tables\Sidebar;

$sidebarsManager = new SidebarsManager();
$sidebars        = $sidebarsManager->read();
?>
<aside>
    <?php array_walk($sidebars, function(Sidebar $sidebar) { ?>
        <div class="sidebar">
            <div class="sidebar-title"><?php echo $sidebar->getSidebarTitle(); ?></div>
            <div class="sidebar-content"><?php echo $sidebar->getSidebarContents(); ?></div>
        </div>
    <?php }); ?>
</aside>
