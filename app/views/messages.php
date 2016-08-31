<?php if (!empty($data['messages'])) { ?>
    <div id="messages">
        <div id="messages-content" class="modal-dialog">
            <?php foreach ($data['messages'] as $message) { ?>
                <div class="alert alert-<?php echo $message['type']; ?> alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $message['message']; ?>
                </div>
            <?php } ?>
            <script>
                if (timeout != undefined) {
                    clearTimeout(timeout);
                }
                var timeout = setTimeout(function () {
                    $("#messages").remove();
                }, 5000);
            </script>
        </div>
    </div>
<?php } ?>