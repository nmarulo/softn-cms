<?php
use SoftnCMS\util\Messages;

$messages    = Messages::getMessages();
$typeMessage = Messages::getTypeMessage();

if ($messages !== FALSE) { ?>
    <div id="messages">
        <div class="modal-dialog messages-content">
            <div class="message-alert alert alert-<?php echo $typeMessage; ?> alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $messages; ?>
            </div>
        </div>
    </div>
<?php }
