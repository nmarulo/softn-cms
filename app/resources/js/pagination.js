(function () {
	$(document).on('click', 'ul.pagination a', function (event) {
		event.preventDefault();
		var element = $(this);
		
		if (element.closest('li').hasClass('disabled')) {
			return false;
		}
		
		reloadPaged(element.closest('.page-container'), element.data('paged'));
	});
	
	$(document).on('keyup', 'input.search-paged', function (event) {
		if (event.keyCode === 13) {
			reloadPaged($(this).closest('.page-container'), 'paged=' + $(this).val())
		}
	});
})();

function reloadPaged(pageContainer, dataPaged) {
	var url = pageContainer.data('url');
	var reloadView = '&view=' + pageContainer.data('reload-view');
	var reloadAction = '&action=' + pageContainer.data('reload-action');
	reloadData(url, dataPaged + reloadView + reloadAction);
}
