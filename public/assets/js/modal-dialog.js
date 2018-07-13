var elementBtnTriggerModalDelete;

$(function () {
    $('#modal-delete').on('show.bs.modal', function (event) {
        elementBtnTriggerModalDelete = $(event.relatedTarget); // Button that triggered the modal
        $(this).find('#modal-delete-input-id').val(elementBtnTriggerModalDelete.data('delete-id'));
    });
    
    modalDeleteTableData();
});

function modalDeleteTableData() {
    $('#modal-delete-form').submit(function (event) {
        $('#modal-delete').modal('hide');
        event.preventDefault();
        
        var deleteCallback = function (deleteData) {
            tableDataRequest(elementBtnTriggerModalDelete);
        };
        
        makeRequest('POST', elementBtnTriggerModalDelete.data('delete-action'), $(this).serializeArray(), deleteCallback);
    });
}
