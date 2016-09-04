<?php
$pagination = $data['pagination'];
$paged = '';

if ($pagination->isShowPagination()) {
    $output = "";
    $href = $pagination->getUrl() . 'paged';
    $paged = '/paged/' . $pagination->getPagedNow();
    $dataPaged = $pagination->getDataPaged();
    $leftArrow = $pagination->getDataLeftArrow();
    $rightArrow = $pagination->getDataRightArrow();
    $outLeftArrow = "<li><a data-paged='' href='$href/$leftArrow'><span>&laquo;</span></a></li>";
    $outRightArrow = "<li><a data-paged='' href='$href/$rightArrow'><span>&raquo;</span></a></li>";

    if (empty($leftArrow)) {
        $outLeftArrow = '<li class="disabled"><a href="#"><span>&laquo;</span></a></li>';
    }

    if (empty($rightArrow)) {
        $outRightArrow = '<li class="disabled"><a href="#"><span>&raquo;</span></a></li>';
    }

    foreach ($dataPaged as $value) {
        //Si es la pagina actual se agrega la clase "active".
        $active = $value['active'] ? 'class="active"' : '';
        $output .= "<li $active><a data-paged='$value[dataPaged]' href='$href/$value[dataPaged]'>$value[page]</a></li>";
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
                <?php echo $outLeftArrow . $output . $outRightArrow; ?>
            </ul>
        </nav>
    </div>
    <?php
}