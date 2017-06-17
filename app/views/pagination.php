<?php
use SoftnCMS\controllers\ViewController;

$pagination = ViewController::getViewData('pagination');

if (!empty($pagination)) {
    $href          = $pagination->getUrl();
    $dataPaged     = $pagination->getDataPaged();
    $leftArrow     = $pagination->getDataLeftArrow();
    $rightArrow    = $pagination->getDataRightArrow();
    $output        = '';
    $outLeftArrow  = '<li><a data-paged="' . $leftArrow . '" href="#"><span>&laquo;</span></a></li>';
    $outRightArrow = '<li><a data-paged="' . $rightArrow . '" href="#"><span>&raquo;</span></a></li>';
    
    if (empty($leftArrow)) {
        $outLeftArrow = '<li class="disabled"><a href="#"><span>&laquo;</span></a></li>';
    }
    
    if (empty($rightArrow)) {
        $outRightArrow = '<li class="disabled"><a href="#"><span>&raquo;</span></a></li>';
    }
    ?>
    <div class="row clearfix pagination-container">
        <div class="col-md-2">
            <div class="input-group">
                <span class="input-group-addon">Pagina</span>
                <input title="Presione Enter para continuar." class="form-control search-paged" type="number" name="search-paged" min="1" value="<?php echo $pagination->getPagedNow(); ?>">
            </div>
        </div>
        <nav class="col-md-8">
            <ul class="pagination clearfix">
                <?php
                $output .= $outLeftArrow;

                foreach ($dataPaged as $value) {
                    $active = $value['active'] ? 'class="active"' : '';
                    $output .= "<li ${active}>";
                    $output .= "<a data-paged='$value[dataPaged]' href='#'>";
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
