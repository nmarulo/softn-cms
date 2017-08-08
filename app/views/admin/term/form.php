<?php
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\TermsManager;

$term     = ViewController::getViewData('term');
$title    = ViewController::getViewData('title');
$method   = ViewController::getViewData('method');
$isUpdate = $method == TermsManager::FORM_UPDATE;
?>
<div class="page-container" data-menu-collapse-id="post">
    <div>
        <h1><?php echo $title; ?></h1>
    </div>
    <div>
        <form role="form" method="post">
            <div id="content-left" class="col-sm-9">
                <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input class="form-control" name="<?php echo TermsManager::TERM_NAME; ?>" placeholder="Escribe el título" value="<?php echo $term->getTermName(); ?>">
                </div>
                <div class="form-group">
                    <label class="control-label">Descripción</label>
                    <textarea class="form-control" name="<?php echo TermsManager::TERM_DESCRIPTION; ?>" rows="5"><?php echo $term->getTermDescription(); ?></textarea>
                </div>
            </div>
            <div id="content-right" class="col-sm-3">
                <div class="panel panel-default">
                    <div class="panel-heading">Publicación</div>
                    <div class="panel-body">
                        <?php if ($isUpdate) { ?>
                            <button class="btn btn-primary btn-block" name="<?php echo TermsManager::FORM_UPDATE; ?>" value="<?php echo TermsManager::FORM_UPDATE; ?>">Actualizar</button>
                        <?php } else { ?>
                            <button class="btn btn-primary btn-block" name="<?php echo TermsManager::FORM_CREATE; ?>" value="<?php echo TermsManager::FORM_CREATE; ?>">Publicar</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="<?php echo TermsManager::ID; ?>" value="<?php echo $term->getId(); ?>"/>
            <?php \SoftnCMS\util\Token::formField(); ?>
        </form>
    </div>
</div>
