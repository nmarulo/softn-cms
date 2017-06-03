<?php
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;
$posts = ViewController::getViewData('posts');
$optionsManager = new OptionsManager();
$siteUrl = $optionsManager->getSiteUrl();
?>
<div class="sn-content">
    <div>
        <h1>Publicaciones <a href="<?php echo $siteUrl . 'admin/post/create'; ?>" class="btn btn-default">Nueva entrada</a></h1>
    </div>
    <div>
        <div class="row pagination-content">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">Pagina</span>
                    <input id="goToPage" title="Presione Enter para continuar." class="form-control" type="number" name="pagination_num" min="1" value="0">
                    <input type="hidden" name="search" value="#" id="goToPageHide">
                </div>
            </div>
            <nav class="col-md-8">
                <ul class="pagination clearfix">
                    <li><a data-paged="" href="#"><span>&laquo;</span></a></li>
                    <li><a data-paged='0' href='#'>01</a></li>
                    <li><a data-paged='0' href='#'>02</a></li>
                    <li><a data-paged='0' href='#'>03</a></li>
                    <li><a data-paged="" href="#"><span>&raquo;</span></a></li>
                </ul>
            </nav>
        </div>
        <div id="contentPost" class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Comentarios</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Comentarios</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </tfoot>
                <tbody>
                <?php foreach ($posts as $post) { ?>
                    <tr>
                        <td class="options">
                            <a class="btnAction-sm btn btn-primary" href="<?php echo $siteUrl . 'admin/post/update/' . $post->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                            <a class="btnAction-sm btn btn-danger" href="<?php echo $siteUrl . 'admin/post/delete/' . $post->getId(); ?>" title="Editar"><span class="glyphicon glyphicon-remove-sign"></span></a>
                        </td>
                        <td><a href="#" target="_blank"><?php echo $post->getPostTitle(); ?></a></td>
                        <td><a href="#" target="_blank"><?php echo $post->getUserID(); ?></a></td>
                        <td><span class="badge"><?php echo $post->getCommentCount(); ?></span></td>
                        <td><?php echo $post->getPostDate(); ?></td>
                        <td><?php echo $post->getPostStatus(); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php //$data['template']::getPagedNav(); ?>
    </div>
</div>
