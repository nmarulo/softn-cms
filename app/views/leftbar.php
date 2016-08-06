<aside class="col-sm-3 col-md-2 sn-menu-content">
    <ul class="sn-menu">
        <?php
        $str = '';
        foreach ($data['menu'] as $menu) {

            if (is_array($menu['sub'])) {
                $str .= "<li><a data-toggle='collapse' href='#$menu[id]'>$menu[title] <span class='pull-right glyphicon glyphicon-chevron-down'></span></a>";
                $str .= "<ul id='$menu[id]' class='sn-submenu collapse'>";
                $str .= "<li><a href='" . \LOCALHOST . "$menu[href]'>$menu[title]</a></li>";

                foreach ($menu['sub'] as $sub) {
                    $str .= "<li><a href='" . \LOCALHOST . "$sub[href]'>$sub[title]</a></li>";
                }
                $str .= '</ul></li>';
            } else {
                $str .= "<li><a href='" . \LOCALHOST . "$menu[href]'>$menu[title]</a></li>";
            }
        }
        echo $str;
        ?>
    </ul>
</aside>