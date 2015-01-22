$(function(){
	$('.datepicker-input').datepicker({
			dateFormat: js_date_format,
			minDate: new Date(1920, 10 - 1, 25),
                        yearRange: '1920',
			showButtonPanel: true,
			changeMonth: true,
			changeYear: true
	});
	$('.datepicker-input-clear').button();
	
	$('.datepicker-input-clear').click(function(){
		$(this).parent().find('.datepicker-input').val("");
		return false;
	});
});