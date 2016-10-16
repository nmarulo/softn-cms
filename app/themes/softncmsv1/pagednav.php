<?php

if ($pagedNav->isShowPagination()) {
    $output        = "";
    $href          = $pagedNav->getUrl();
    $dataPaged     = $pagedNav->getDataPaged();
    $leftArrow     = $pagedNav->getDataLeftArrow();
    $rightArrow    = $pagedNav->getDataRightArrow();
    $outLeftArrow  = '<li><a data-paged="" href="' . $href . $leftArrow . '"><span>&laquo;</span></a></li>';
    $outRightArrow = '<li><a data-paged="" href="' . $href . $rightArrow . '"><span>&raquo;</span></a></li>';
    
    if (empty($leftArrow)) {
        $outLeftArrow = '<li class="disabled"><a href="#"><span>&laquo;</span></a></li>';
    }
    
    if (empty($rightArrow)) {
        $outRightArrow = '<li class="disabled"><a href="#"><span>&raquo;</span></a></li>';
    }
    
    foreach ($dataPaged as $value) {
        //Si es la pagina actual se agrega la clase "active".
        $active = $value['active'] ? 'class="active"' : '';
        $output .= "<li ${active}><a data-paged='$value[dataPaged]' href='" . $href . $value['dataPaged'] . "'>$value[page]</a></li>";
    }
    ?>
    <div class="pagination-content">
        <ul class="pagination clearfix">
            <?php echo $outLeftArrow . $output . $outRightArrow; ?>
        </ul>
    </div>
    <?php
}
