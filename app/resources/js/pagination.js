(function () {
	$(document).on('click', 'ul.pagination a', function (event) {
		event.preventDefault();
		var element = $(this);
		
		if (element.closest('li').hasClass('disabled')) {
			return false;
		}
		
		var url = element.closest('.page-container').data('url');
		var dataPaged = element.data('paged');
		
		reloadData(url, dataPaged);
	});
	
	$(document).on('keyup', 'input.search-paged', function (event) {
		if (event.keyCode === 13) {
			var element = $(this);
			var url = element.closest('.page-container').data('url');
			var dataPaged = 'paged=' + element.val();
			
			reloadData(url, dataPaged);
		}
	});
})();
