var App = $(document);
var WIN = $(window);


$(function () {
	$('#site-header li a').removeAttr('title');
});

var pageJS = {

	galleryPage: function() {
		$('a.gallery-link').boojGallery();
	},

	homePage: function() {
		var gal = $('#home-gallery');
		// $('a.viewImageAction', gal).fancybox();
		gal.homeGallery({
			imgSrcUrl: '/get_home_images'
		});
		gal.on('click', 'a.viewImageAction', function(e) {
			e.preventDefault();
			var el = $(this);
			$.fancybox({
				href: el.attr('href')
			});
		});
	},

	homeTest: function() {
		var HEIGHTS = [];
		var COLUMN_WIDTH = 210;
		var MARGIN = 5;
		var DELTA = 20;

		function create_columns(n) {
			HEIGHTS = [];
			for (var i = 0; i < n; ++i) {
				HEIGHTS.push(MARGIN);
			}
		}

		function get_min_column() {
			var min_height = Infinity;
			var min_i = -1;
			for (var i = 0; i < HEIGHTS.length; ++i) {
				if (HEIGHTS[i] < min_height) {
					min_height = HEIGHTS[i];
					min_i = i;
				}
			}
			return min_i;
		}

		function fit_width(image, rowspan) {
			var new_width = COLUMN_WIDTH * rowspan;
			if (rowspan > 1) {
				new_width += MARGIN;
			}
			var new_height = ($(image).data('height') * new_width) / $(image).data('width');

			return {
				width: new_width,
				height: new_height
			};
		}

		function add_column_elem(i, elem, rowspan/* -1, 0 or 1 */) {
			var new_dim = fit_width(elem, 1 + Math.abs(rowspan));

			new_dim.height -= (HEIGHTS[i] + new_dim.height + MARGIN) % DELTA;

			$(elem).css({
				'margin-left': MARGIN + (COLUMN_WIDTH + MARGIN) * (i + (rowspan === -1 ? -1 : 0)),
				'margin-top': HEIGHTS[i],
				'width': new_dim.width,
				'height': new_dim.height
			});

			$(elem).attr('src', $(elem).attr('src').replace(/w[0-9]+-h[0-9]+/, 'w' + $(elem).width() + '-h' + $(elem).height()));

			var next_height = HEIGHTS[i] + new_dim.height + MARGIN;
			HEIGHTS[i + rowspan] = HEIGHTS[i] = next_height;
		}

		function add_image(image) {
			var column = get_min_column();
			var rowspan = 0;
			if (Math.random() > 0.5) {
				if (column - 1 > 0 && HEIGHTS[column - 1] <= HEIGHTS[column]) {
					rowspan = -1;
				} else if (column + 1 < HEIGHTS.length && HEIGHTS[column + 1] <= HEIGHTS[column]) {
					rowspan = 1;
				}
			}
			add_column_elem(column, image, rowspan);
		}

		function run() {
			var size = WIN.width();
			var n_columns = Math.floor(size / (COLUMN_WIDTH + MARGIN));
			create_columns(n_columns);

			var images = $('#home-gallery img');
			for (var i = 0; i < images.length; ++i) {
				add_image(images[i]);
			}
		}

		window.addEventListener('resize', run);
		run();
	},

	boojerBioPage: function() {
		var bio = $('#boojer-bio-container');
		App.on('click', '[data-action="toggle-bio-info"]', function(e) {
			e.preventDefault();
			var el = $(this);
			$('.bio-toggle', bio).hide();
			$(el.data('target'), bio).show();
			el.parent().find('.active').removeClass('active');
			el.addClass('active');
		});
		$('.fancybox', bio).fancybox();
	},

	boojersPage: function() {
		var scope = $('#boojers-page');
		var btns = $('[data-action="toggle-tag"]', scope);
		var tabs = $('.boojer-tab', scope);
		var header = $('#site-header');
		var BODY = $('body');
		var bio = $('<div id="boojer-bio-ajax"></div>').hide().appendTo(BODY);
		var tempHolder = $('<ul></ul>').appendTo(BODY);
		var currentTab = '#pro';

		btns.on('click', function(e) {
			e.preventDefault();
			var el = $(this);				
			btns.removeClass('active');
			tabs.removeClass('active')
			el.addClass('active');
			currentTab = el.data('target');
			$(currentTab).addClass('active');
			tempHolder.html('');
		});

		var hideBio = function() {
			if ("onhashchange" in window) {
				try {
					window.removeEventListener("hashchange", hideBio, false);
				} catch (e) {

				}
			}
			window.location.hash = '';
			bio.fadeOut(600, function() {
				bio.hide();
			});
		};

		$('.show-taged', scope).on('click', function (e) {
			var el = $(this);
			var currTag = el.data('id');
			var currType = el.data('type');
			var currTab = $('#' + currType);
			var sourceList = currType === 'fun' ? fun : pros;
			var i;

			e.preventDefault();
			tempHolder.html('');

			el.parents('ul').find('.active').removeClass('active');
			el.addClass('active');
			
			for (i = 0; i < sourceList.length; i++) {
				if (currTag === 'all' || $.inArray(currTag, sourceList[i].tags.substr(0, sourceList[i].tags.length - 1).split(',')) > -1) {
					tempHolder.append('<li data-id="' + currTag + '">' + sourceList[i].html + '</li>');
				}
			}

			$('.display-list', currTab).quicksand(tempHolder.find('li'), {
				adjustHeight: 'auto',
				attribute: function(v) {
					return $(v).find('a').attr('href') + currTab;
				}
			}, function() {
				$('.display-list', currTab).css({
					width: 'auto',
					height: 'auto'
				});
			});				
		});

		App.on('click', 'a[data-action="get-bio"]', function(e) {
			e.preventDefault();
			window.location.hash = '#viewing-bio';
			bio.html('<div class="container-fluid"><p class="text-center"><img src="/img/ajax-loader.gif" alt=""></p></div>').css({
				top: header.height(),
				minHeight: BODY.height()
			}).fadeIn(600);

			$('html, body').animate({scrollTop: '95px'}, 0);

			$.get($(this).attr('href'), function(data) {
				bio.html(data);
				bio.find('.fancybox').fancybox();
				if (currentTab === '#fun') {
					bio.find('a[data-target=".bio-life"]').trigger('click');
				}
				if ("onhashchange" in window) {
					try {
						window.addEventListener("hashchange", hideBio, false);
					} catch(e) {

					}
				}	
			});
		});

		App.on('click', 'a[data-action="close-bio"]', function(e) {
			e.preventDefault();
			hideBio();
		});

		App.on('click', '[data-action="toggle-bio-info"]', function(e) {
			e.preventDefault();
			var el = $(this);
			$('.bio-toggle', bio).hide();
			$(el.data('target'), bio).show();
			el.parent().find('.active').removeClass('active');
			el.addClass('active');
		});

		WIN.on('resize', function() {
			bio.css({
				minHeight: BODY.height()
			});
		});
	}
};

