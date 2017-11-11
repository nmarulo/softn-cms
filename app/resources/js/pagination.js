(function () {
	$(document).on('click', 'ul.pagination a', function (event) {
		event.preventDefault();
		var element = $(this);
		
		if (element.closest('li').hasClass('disabled')) {
			return false;
		}
		
		reloadDataContainer(element.closest('.page-container'), element.data('paged'));
	});
	
	$(document).on('keyup', 'input.search-paged', function (event) {
		if (event.keyCode === 13) {
			reloadDataContainer($(this).closest('.page-container'), 'paged=' + $(this).val())
		}
	});
})();
