<div id="messages">
#if(\App\Facades\MessagesFacade::isMessages())
    #foreach(\App\Facades\MessagesFacade::getMessages() as $messageModal)
        <div class="modal-dialog messages-content">
            <div class="message-alert alert alert-{{$messageModal['type']}} alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span>&times;</span>
                </button>
                {{$messageModal['message']}}
            </div>
        </div>
    #endforeach
    {{\App\Facades\MessagesFacade::delete()}}
#endif
</div>
