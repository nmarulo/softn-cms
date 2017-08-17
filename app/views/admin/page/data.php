<?php

use SoftnCMS\models\tables\Page;
use SoftnCMS\controllers\ViewController;

$pages                = ViewController::getViewData('pages');
$siteUrlUpdate        = \SoftnCMS\rute\Router::getSiteURL() . 'admin/page/update/';
$strTranslateTitle    = __('Título');
$strTranslateComments = __('Comentarios');
$strTranslateDate     = __('Fecha');
$strTranslateState    = __('Estado');
ViewController::singleViewByDirectory('pagination'); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th><?php echo $strTranslateTitle; ?></th>
                <th><?php echo $strTranslateDate; ?></th>
                <th><?php echo $strTranslateState; ?></th>
                <th><?php echo $strTranslateComments; ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th><?php echo $strTranslateTitle; ?></th>
                <th><?php echo $strTranslateDate; ?></th>
                <th><?php echo $strTranslateState; ?></th>
                <th><?php echo $strTranslateComments; ?></th>
            </tr>
        </tfoot>
        <tbody>
        <?php array_walk($pages, function(Page $page) use ($siteUrlUpdate) { ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $page->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <button class="btn-action-sm btn btn-danger" data-id="<?php echo $page->getId(); ?>" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button>
                </td>
                <td><a href="#" target="_blank"><?php echo $page->getPageTitle(); ?></a></td>
                <td><?php echo $page->getPageDate(); ?></td>
                <td><?php echo $page->getPageStatus(); ?></td>
                <td><span class="badge"><?php echo $page->getPageCommentCount(); ?></span></td>
            </tr>
        <?php }); ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewByDirectory('pagination');
