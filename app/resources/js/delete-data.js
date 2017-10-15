(function () {
	var btnElement;
	
	$(document).on('click', 'button.btn-danger', function (event) {
		event.preventDefault();
		btnElement = this;
	});
	
	$('#btn-modal-delete-confirm').on('click', function(){
		deleteData(btnElement);
	});
})();

function deleteData(element) {
	var url = $(element).closest('.page-container').data('url');
	var id = $(element).data('id');
	var data = 'deleteAJAX=true';
	var callback = function (dataMessages) {
		var data = '';
		var dataPaged = $(document).find('.pagination > li.active > a').data('paged');
		var dataAdd = $(document).find('.page-container').data('add-url');
		
		if (dataPaged !== undefined) {
			data = dataPaged;
			
			if (dataAdd !== undefined) {
				data += '&' + dataAdd;
			}
		} else if (dataAdd !== undefined) {
			data = dataAdd;
		}
		
		reloadData(url, data);
		includeMessages(dataMessages);
	};
	callAjax(url + 'delete/' + id, 'POST', data, callback);
}
