var deleteFormAction = '';
var btnModalDelete = null;
var modalDialogDataIdUpdate;

$(function () {
    $('#modal-delete').on('show.bs.modal', function (event) {
        btnModalDelete = $(event.relatedTarget); // Button that triggered the modal
        deleteFormAction = btnModalDelete.data('delete-action');
        modalDialogDataIdUpdate = getDataIdUpdateElement(btnModalDelete);
        $(this).find('#modal-delete-input-id').val(btnModalDelete.data('delete-id'));
    });
    
    $('#modal-delete-form').submit(function (event) {
        $('#modal-delete').modal('hide');
        event.preventDefault();
        
        var deleteCallback = function (deleteData) {
            makeRequest('GET', getRoute(), createDataToSendPagination(), function (dataHTML) {
                viewUpdate(dataHTML, modalDialogDataIdUpdate);
            });
        };
        
        makeRequest('POST', deleteFormAction, $(this).serializeArray(), deleteCallback);
    });
});
