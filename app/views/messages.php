<?php
use SoftnCMS\util\Messages;

$messages = Messages::getMessages();

if (!empty($messages)) { ?>
    <div id="messages">
        <?php foreach ($messages as $value) {
            $message     = Messages::getMessage($value);
            $typeMessage = Messages::getTypeMessage($value); ?>
            <div class="modal-dialog messages-content">
                <div class="message-alert alert alert-<?php echo $typeMessage; ?> alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $message; ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php }
