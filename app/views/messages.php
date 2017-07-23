<?php

use SoftnCMS\util\Messages;

$messages = Messages::getMessages();

if (!empty($messages)) { ?>
    <div id="messages">
        <?php foreach ($messages as $value) { ?>
            <div class="modal-dialog messages-content">
                <div class="message-alert alert alert-<?php echo Messages::getTypeMessage($value); ?> alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span>&times;</span>
                    </button>
                    <?php echo Messages::getMessage($value); ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php }
