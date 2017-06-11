(function () {
	// setDataGitHub();
})();

function setDataGitHub() {
	var url = $(document).find('.page-container').data('url');
	var setData = function (data) {
		$(document).find('#data-github').html(data);
	};
	
	callAjax(url + 'apiGitHub', 'POST', '', setData);
}
