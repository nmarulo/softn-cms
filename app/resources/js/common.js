var idMessages = '';
var asideMenuUl = '';

(function () {
	setVars();
	registerEvents();
})();

function setVars() {
	idMessages = '#messages';
	asideMenuUl = $('.menu-content');
}

function registerEvents() {
	menuCollapse();
	
	if ($(document).find(idMessages).length > 0) {
		removeMessagesTimeOut(idMessages);
	}
	
	asideMenuUl.on('hide.bs.collapse', function (e) {
		changeGlyphicon(e.target);
	});
	
	asideMenuUl.on('show.bs.collapse', function (e) {
		changeGlyphicon(e.target);
	});
	
	//Comprueba si hay errores en la estructura de bootstrap
	//javascript:(function(){var s=document.createElement("script");s.onload=function(){bootlint.showLintReportForCurrentDocument([]);};s.src="https://maxcdn.bootstrapcdn.com/bootlint/latest/bootlint.min.js";document.body.appendChild(s)})();
}

function menuCollapse() {
	var collapse = $(document).find('.page-container').data('menu-collapse-id');
	
	if (collapse !== undefined) {
		collapse = '#' + collapse;
		$(collapse).addClass('in');
		changeGlyphicon(collapse, 'up', 'down');
	}
}

/**
 * Método que cambia la clase "glyphicon-chevron-up" por "glyphicon-chevron-down" y viceversa.
 * Solo cambia la palabra final, por ejemplo, cambia "up" por "down" de "glyphicon-chevron-up"
 * @param collapse Identificador del elemento.
 */
function changeGlyphicon(collapse) {
	var add = 'up';
	var remove = 'down';
	var span = $(collapse).closest('li').children(':first').children(':last');
	
	if (span.hasClass('glyphicon-chevron-up')) {
		add = 'down';
		remove = 'up';
	}
	
	span.removeClass('glyphicon-chevron-' + remove);
	span.addClass('glyphicon-chevron-' + add);
}

function removeMessagesTimeOut(idMessages) {
	if (timeout !== undefined) {
		clearTimeout(timeout);
	}
	var timeout = setTimeout(function () {
		removeMessages(idMessages);
	}, 5000);
}

function removeMessages(idMessages) {
	var divMessages = $(document).find(idMessages);
	var messagesContent = divMessages.find('.messages-content');
	
	if (messagesContent.length > 1) {
		messagesContent.get(0).remove();
		removeMessagesTimeOut(idMessages);
	} else {
		divMessages.remove();
	}
}

function includeMessages(dataHtml) {
	var element = $(document).find(idMessages);
	
	if (element.length === 0) {
		element = $('body');
	} else {
		dataHtml = $(dataHtml).html();
	}
	
	element.append(dataHtml);
	
	removeMessagesTimeOut(idMessages);
}

function callAjaxParseJSON(url, method, data, callback) {
	callAjax(url, method, data, callback, true);
}

function callAjax(url, method, data, callback, parseJSON) {
	$.ajax({
		url: url,
		method: method,
		data: data
	}).done(function (data, textStatus, jqXHR) {
		if (callback !== undefined) {
			var parseData = data;
			
			if (parseJSON === true) {
				parseData = JSON.parse(data);
			}
			
			callback(parseData);
		}
	}).fail(function (jqXHR, textStatus, errorThrown) {
		console.log('ERROR: ' + textStatus);
	});
}

function reloadData(url, data) {
	var callback = function (data) {
		$('#data-container').html(data);
	};
	
	callAjax(url + 'reload', 'GET', data, callback);
	
	return true;
}
