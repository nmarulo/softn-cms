<?php

use SoftnCMS\models\tables\Term;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;

$terms          = ViewController::getViewData('terms');
$optionsManager = new OptionsManager();
$siteUrl        = $optionsManager->getSiteUrl();
$siteUrlUpdate  = $siteUrl . 'admin/term/update/';
ViewController::singleViewDirectory('pagination'); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Nombre</th>
                <th>Entradas</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th>Nombre</th>
                <th>Entradas</th>
            </tr>
        </tfoot>
        <tbody>
        <?php array_walk($terms, function(Term $term) use ($siteUrlUpdate) { ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $term->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <button class="btn-action-sm btn btn-danger" data-id="<?php echo $term->getId(); ?>" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button>
                </td>
                <td><?php echo $term->getTermName(); ?></td>
                <td><?php echo $term->getTermPostCount(); ?></td>
            </tr>
        <?php }); ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewDirectory('pagination');
