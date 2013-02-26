jQuery(document).ready(function($) {
	$('a[data-action="confirm"]').on('click', function(e) {
		var test = confirm('Are you sure?');
		if (!test) {
			e.preventDefault();
		}
	});

	$('input.date-widget').datepicker({
		dateFormat: 'yy-mm-dd'
	});
});