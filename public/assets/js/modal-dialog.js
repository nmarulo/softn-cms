var deleteFormAction = '';
var dataUpdate = '';
var btnModalDelete = null;

$(function () {
    $('#modal-delete').on('show.bs.modal', function (event) {
        btnModalDelete = $(event.relatedTarget); // Button that triggered the modal
        deleteFormAction = btnModalDelete.data('delete-action');
        dataUpdate = btnModalDelete.data('update');
        $(this).find('#modal-delete-input-id').val(btnModalDelete.data('delete-id'));
    });
    
    $('#modal-delete-form').submit(function (event) {
        $('#modal-delete').modal('hide');
        event.preventDefault();
        
        var deleteCallback = function (deleteData) {
            var updateCallback = function (dataHTML) {
                viewUpdate(dataUpdate, dataHTML);
            };
            
            makeRequest('GET', getRoute(), '', updateCallback);
        };
        
        makeRequest('POST', deleteFormAction, $(this).serialize(), deleteCallback);
    });
});
