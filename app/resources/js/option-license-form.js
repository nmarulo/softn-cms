var divIdPage = '';
var btnIdCheckAll = '';
var btnIdUnCheckAll = '';
var inputsCheckBoxes = '';
var divClassFormInputCheckBoxes = '';
var btnClassCheckAll = '';
var btnClassUnCheckAll = '';

(function () {
	initVars();
	initEvents();
})();

function initVars() {
	divIdPage = '#option-license';
	btnIdCheckAll = '#btn-check-all';
	btnIdUnCheckAll = '#btn-uncheck-all';
	inputsCheckBoxes = findInputsCheckBoxes($(divIdPage));
	divClassFormInputCheckBoxes = '.form-input-checkboxes';
	btnClassCheckAll = '.btn-check-all';
	btnClassUnCheckAll = '.btn-uncheck-all';
}

function initEvents() {
	$(btnIdCheckAll).on('click', function () {
		changeAllCheckBox(inputsCheckBoxes, true);
	});
	$(btnIdUnCheckAll).on('click', function () {
		changeAllCheckBox(inputsCheckBoxes, false);
	});
	$(btnClassCheckAll).on('click', function () {
		var inputsCheckBoxes = findInputsCheckBoxes($(this).closest(divClassFormInputCheckBoxes));
		changeAllCheckBox(inputsCheckBoxes, true);
	});
	$(btnClassUnCheckAll).on('click', function () {
		var inputsCheckBoxes = findInputsCheckBoxes($(this).closest(divClassFormInputCheckBoxes));
		changeAllCheckBox(inputsCheckBoxes, false);
	});
}

function findInputsCheckBoxes(element) {
	return element.find('input[type="checkbox"]');
}

function changeAllCheckBox(inputsCheckBoxes, check) {
	inputsCheckBoxes.prop('checked', check);
}
