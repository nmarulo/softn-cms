(function () {
	$(document).on('click', 'a.btn-danger', function (event) {
		event.preventDefault();
		var url = $(this).closest('.page-container').data('url');
		var id = $(this).data('id');
		var data = 'deleteAJAX=true';
		var callback = function (dataMessages) {
			var dataPaged = $(document).find('.pagination > li.active > a').data('paged');
			reloadData(url, dataPaged);
			includeMessages(dataMessages);
		};
		callAjax(url + 'delete/' + id, 'POST', data, callback);
	})
})();
