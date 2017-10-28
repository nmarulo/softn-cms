(function () {
	var btnElement;
	
	$(document).on('click', 'button.btn-danger', function (event) {
		event.preventDefault();
		btnElement = $(this);
	});
	
	$('#btn-modal-delete-confirm').on('click', function () {
		deleteData(btnElement, $(this));
	});
})();

function deleteData(btnDelete, btnModal) {
	var pageContainer = btnDelete.closest('.page-container');
	var url = pageContainer.data('url');
	var id = btnDelete.data('id');
	var data = btnModal.data('token') + '&redirect=false';
	var callback = function (data) {
		var pagination = $(document).find('.pagination > li.active > a');
		var dataPaged = '';
		
		if (pagination.length > 0) {
			dataPaged = pagination.data('paged');
		}
		
		reloadDataContainer(pageContainer, dataPaged);
		callAjax(url + 'messages', 'GET', '', function (dataMessages) {
			includeMessages(dataMessages);
		});
	};
	callAjax(url + 'delete/' + id, 'POST', data, callback);
}
