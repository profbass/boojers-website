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

var AdminApp = {
	showAlert: function(str, c) {
		var type = c || 'info';
		var notice = $('#alert-notice');
		notice.removeClass('alert-error').removeClass('alert-success').removeClass('alert-info')
		notice.html(str).addClass('alert-' + type).fadeIn(200);
		setTimeout(function() {
			notice.fadeOut(300);
		}, 2000);
	}	
}