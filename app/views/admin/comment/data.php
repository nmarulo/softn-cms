<?php

use SoftnCMS\models\tables\Comment;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;

$comments       = ViewController::getViewData('comments');
$optionsManager = new OptionsManager();
$siteUrl        = $optionsManager->getSiteUrl();
$siteUrlUpdate  = $siteUrl . 'admin/comment/update/';
ViewController::singleViewDirectory('pagination'); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Autor</th>
                <th>Comentario</th>
                <th>Estado</th>
                <th>Entrada</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th>Autor</th>
                <th>Comentario</th>
                <th>Estado</th>
                <th>Entrada</th>
                <th>Fecha</th>
            </tr>
        </tfoot>
        <tbody>
        <?php array_walk($comments, function(Comment $comment) use ($siteUrlUpdate) { ?>
            <tr>
                <td class="options">
                    <a class="btn-action-sm btn btn-primary" href="<?php echo $siteUrlUpdate . $comment->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                    <button class="btn-action-sm btn btn-danger" data-id="<?php echo $comment->getId(); ?>" title="Borrar"><span class="glyphicon glyphicon-remove-sign"></span></button>
                </td>
                <td><?php echo $comment->getCommentAuthor(); ?></td>
                <td><?php echo $comment->getCommentContents(); ?></td>
                <td><?php echo $comment->getCommentStatus(); ?></td>
                <td><?php echo $comment->getPostID(); ?></td>
                <td><?php echo $comment->getCommentDate(); ?></td>
            </tr>
        <?php }); ?>
        </tbody>
    </table>
</div>
<?php ViewController::singleViewDirectory('pagination');
