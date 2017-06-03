<?php
use SoftnCMS\controllers\ViewController;

$pagination = ViewController::getViewData('pagination');

if ($pagination !== FALSE) {
    $href          = $pagination->getUrl();
    $dataPaged     = $pagination->getDataPaged();
    $leftArrow     = $pagination->getDataLeftArrow();
    $rightArrow    = $pagination->getDataRightArrow();
    $output        = '';
    $outLeftArrow  = '<li><a data-paged="" href="' . $href . $leftArrow . '"><span>&laquo;</span></a></li>';
    $outRightArrow = '<li><a data-paged="" href="' . $href . $rightArrow . '"><span>&raquo;</span></a></li>';
    
    if (empty($leftArrow)) {
        $outLeftArrow = '<li class="disabled"><a href="#"><span>&laquo;</span></a></li>';
    }
    
    if (empty($rightArrow)) {
        $outRightArrow = '<li class="disabled"><a href="#"><span>&raquo;</span></a></li>';
    }
    ?>
    <div class="row pagination-content">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon">Pagina</span>
                <input id="goToPage" title="Presione Enter para continuar." class="form-control" type="number" name="pagination_num" min="1" value="<?php echo $pagination->getPagedNow(); ?>">
                <input type="hidden" name="search" value="<?php echo $pagination->getUrlData(); ?>" id="goToPageHide">
            </div>
        </div>
        <nav class="col-md-8">
            <ul class="pagination clearfix">
                <?php
                $output .= $outLeftArrow;
    
                foreach ($dataPaged as $value) {
                    $active = $value['active'] ? 'class="active"' : '';
                    $output .= "<li ${active}>";
                    $output .= "<a data-paged='$value[dataPaged]' ";
                    $output .= "href='" . $href . $value['dataPaged'] . "'>";
                    $output .= "$value[page]</a>";
                    $output .= "</li>";
                }
    
                $output .= $outRightArrow;
    
                echo $output;
                ?>
            </ul>
        </nav>
    </div>
    <?php
}
