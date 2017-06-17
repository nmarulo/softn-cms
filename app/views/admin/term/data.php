<?php
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;

$terms          = ViewController::getViewData('terms');
$optionsManager = new OptionsManager();
$siteUrl        = $optionsManager->getSiteUrl();
$siteUrlUpdate  = $siteUrl . 'admin/term/update/';
ViewController::singleViewDirectory('pagination');
?>
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
        <?php foreach ($terms as $term) { ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $term->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <a class="btn-action-sm btn btn-danger" data-id="<?php echo $term->getId(); ?>" href="#" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></a>
                </td>
                <td><?php echo $term->getTermName(); ?></td>
                <td><?php echo $term->getTermPostCount(); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewDirectory('pagination');
