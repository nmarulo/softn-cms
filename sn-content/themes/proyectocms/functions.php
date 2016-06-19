<?php

function navMenu() {
    $menuParent = SN_Menus::dataList('fetchObject');
    if ($menuParent) {
        $menuItems = SN_Menus::getChildrens($menuParent->ID);
        ?>
        <nav class="menu-nav">
            <div class="container">
                <div class="showMenu">
                    <div class="titleMenu">SoftN-CMS</div>
                    <ul class="menu-list">
                        <?php
                        foreach ($menuItems as $item) {
                            echo "<li><a href='$item[menu_url]'>$item[menu_title]</a></li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
        <?php
    }
}
