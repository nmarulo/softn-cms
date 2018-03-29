var deleteFormAction = '';

$(function () {
    $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        deleteFormAction = button.data('form-action');
        $(this).find('#modal-delete-input-id').val(button.data('delete-id'));
    });
    
    $('#modal-delete-form').submit(function (event) {
        event.preventDefault();
        makeRequest('POST', deleteFormAction, $(this).serialize());
    });
});
