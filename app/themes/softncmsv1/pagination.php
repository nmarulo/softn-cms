<?php
use SoftnCMS\controllers\ViewController;

$pagination = ViewController::getViewData('pagination');

if (!empty($pagination)) {
    $href          = $pagination->getUrl();
    $dataPaged     = $pagination->getDataPaged();
    $leftArrow     = $pagination->getDataLeftArrow();
    $rightArrow    = $pagination->getDataRightArrow();
    $output        = '';
    $outLeftArrow  = '<li><a href="' . "$href?$leftArrow" . '"><span>&laquo;</span></a></li>';
    $outRightArrow = '<li><a href="' . "$href?$rightArrow" . '"><span>&raquo;</span></a></li>';
    
    if (empty($leftArrow)) {
        $outLeftArrow = '<li class="disabled"><a href="#"><span>&laquo;</span></a></li>';
    }
    
    if (empty($rightArrow)) {
        $outRightArrow = '<li class="disabled"><a href="#"><span>&raquo;</span></a></li>';
    }
    ?>
    <div class="pagination-content">
        <nav>
            <ul class="pagination clearfix">
                <?php
                $output .= $outLeftArrow;

                foreach ($dataPaged as $value) {
                    $active = $value['active'] ? 'class="active"' : '';
                    $output .= "<li ${active}>";
                    $output .= "<a href='$href?$value[dataPaged]'>";
                    $output .= "$value[page]</a>";
                    $output .= '</li>';
                }

                $output .= $outRightArrow;

                echo $output;
                ?>
            </ul>
        </nav>
    </div>
    <?php
}
