<?php

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\tables\User;

$users                  = ViewController::getViewData('users');
$siteUrlUpdate          = ViewController::getViewData('siteUrl') . 'admin/user/update/';
$strTranslateUser       = __('Usuario');
$strTranslateName       = __('Nombre');
$strTranslateEmail      = __('Correo electrónico');
$strTranslateRegistered = __('Registro');
$strTranslatePosts      = __('Entradas');
ViewController::singleViewByDirectory('pagination'); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th><?php echo $strTranslateUser; ?></th>
                <th><?php echo $strTranslateName; ?></th>
                <th><?php echo $strTranslateEmail; ?></th>
                <th><?php echo $strTranslateRegistered; ?></th>
                <th><?php echo $strTranslatePosts; ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th><?php echo $strTranslateUser; ?></th>
                <th><?php echo $strTranslateName; ?></th>
                <th><?php echo $strTranslateEmail; ?></th>
                <th><?php echo $strTranslateRegistered; ?></th>
                <th><?php echo $strTranslatePosts; ?></th>
            </tr>
        </tfoot>
        <tbody>
        <?php array_walk($users, function(User $user) use ($siteUrlUpdate) { ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $user->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <button class="btn-action-sm btn btn-danger" data-id="<?php echo $user->getId(); ?>" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button>
                </td>
                <td><?php echo $user->getUserLogin(); ?></td>
                <td><?php echo $user->getUserName(); ?></td>
                <td><?php echo $user->getUserEmail(); ?></td>
                <td><?php echo $user->getUserRegistered(); ?></td>
                <td><?php echo $user->getUserPostCount(); ?></td>
            </tr>
        <?php }); ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewByDirectory('pagination');
