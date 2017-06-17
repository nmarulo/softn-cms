<?php
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;

$users          = ViewController::getViewData('users');
$optionsManager = new OptionsManager();
$siteUrl        = $optionsManager->getSiteUrl();
$siteUrlUpdate  = $siteUrl . 'admin/user/update/';
ViewController::singleViewDirectory('pagination'); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>E-mail</th>
                <th>Rol</th>
                <th>Registro</th>
                <th>Entradas</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>E-mail</th>
                <th>Rol</th>
                <th>Registro</th>
                <th>Entradas</th>
            </tr>
        </tfoot>
        <tbody>
        <?php foreach ($users as $user) { ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $user->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <a class="btn-action-sm btn btn-danger" data-id="<?php echo $user->getId(); ?>" href="#" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></a>
                </td>
                <td><?php echo $user->getUserLogin(); ?></td>
                <td><?php echo $user->getUserName(); ?></td>
                <td><?php echo $user->getUserEmail(); ?></td>
                <td><?php echo $user->getUserRol(); ?></td>
                <td><?php echo $user->getUserRegistered(); ?></td>
                <td><?php echo $user->getUserPostCount(); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewDirectory('pagination');
