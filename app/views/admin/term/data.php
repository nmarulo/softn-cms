<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\tables\Term;

$terms             = ViewController::getViewData('terms');
$siteUrlUpdate     = ViewController::getViewData('siteUrl') . 'admin/term/update/';
$strTranslateName  = __('Nombre');
$strTranslatePosts = __('Entradas');
ViewController::singleViewByDirectory('pagination'); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th><?php echo $strTranslateName; ?></th>
                <th><?php echo $strTranslatePosts; ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th><?php echo $strTranslateName; ?></th>
                <th><?php echo $strTranslatePosts; ?></th>
            </tr>
        </tfoot>
        <tbody>
        <?php array_walk($terms, function(Term $term) use ($siteUrlUpdate) { ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $term->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <button class="btn-action-sm btn btn-danger" data-id="<?php echo $term->getId(); ?>" title="Borrar"><span class="glyphicon glyphicon-remove"></span></button>
                </td>
                <td><?php echo $term->getTermName(); ?></td>
                <td><?php echo $term->getTermPostCount(); ?></td>
            </tr>
        <?php }); ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewByDirectory('pagination');
