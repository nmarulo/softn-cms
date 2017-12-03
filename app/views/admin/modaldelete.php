<div class="modal fade" id="modal-data-delete" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo __('Eliminar'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo __('¿Esta seguro que desea continuar?'); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo __('No'); ?></button>
                <button type="button" id="btn-modal-delete-confirm" class="btn btn-default" data-dismiss="modal" data-token="<?php echo \SoftnCMS\util\Token::urlParameters(''); ?>"><?php echo __('Si'); ?></button>
            </div>
        </div>
    </div>
</div>
