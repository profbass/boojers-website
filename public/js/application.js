var App = $(document);
var WIN = $(window);

var pageJS = {

	galleryPage: function() {
		$('a.gallery-link').on('click', function(e) {
			e.preventDefault();
			var el = $(this);
			var obj = this;
			$.fancybox({
				href: '#gallery-viewer',
				type: 'inline',
				autoSize: false,
				width: WIN.width() - 100,
				height: WIN.height() - 100,
				afterLoad: function() {
					var fb = this.content;
					$.getJSON('/get_gallery_json/' + el.data('id'), function(data) {
						new BoojGallery(fb, data);
					});
				},
				afterClose: function() {
					this.content.html('');
				}
			});
		});
	},

	homePage: function() {
		$('body').css('overflow-y', 'scroll');
		var gal = $('#home-gallery');
		gal.gPlusGallery({
			imgSrcUrl: '/get_home_images',
			imageMargin: 2
		});

		gal.on('click', 'a.viewImageAction', function(e) {
			e.preventDefault();
			$.fancybox({
				href: $(this).attr('href'),
				helpers: {
					overlay: {
						locked: false
					}
				}
			});
		});
	},

	boojersPage: function() {
		var scope = $('#boojers-page');
		var btns = $('[data-action="toggle-tag"]', scope);
		var tabs = $('.boojer-tab', scope);
		var bio = $('#boojer-bio');
		var header = $('#site-header');
		var BODY = $('body');
		var tempHolder = $('<ul></ul>').appendTo(BODY);

		btns.on('click', function(e) {
			e.preventDefault();
			var el = $(this);				
			btns.removeClass('active');
			tabs.removeClass('active')
			el.addClass('active');
			$(el.data('target')).addClass('active');
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
					return $(v).find('img').attr('src') + currTab;
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
			bio.html('<div class="container-fluid"><p class="text-center">loading...</p></div>').css({
				top: header.height(),
				minHeight: BODY.height()
			}).fadeIn(600);
			$.post($(this).attr('href'), function(data) {
				bio.html(data);
				bio.find('.fancybox').fancybox();
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
			$('.bio-toggle', bio).hide();
			$($(this).data('target'), bio).show();
		});

		WIN.on('resize', function() {
			bio.css({
				minHeight: BODY.height()
			});
		});
	}
};


var BoojGallery = function(container, data) {
	console.log(container)
	this.container = $(container);
	this.gallery = data.album || false;
	if (this.gallery) {
		this.init();
	}
};

BoojGallery.prototype = {
	leftContainer: [],
	rightContainer: [],
	imageContainer: [],
	infoContainer: [],
	currImage: 0,

	init: function() {
		var self = this, main_template;

		main_template = $('<div class="container-fluid"><div class="row-fluid"><div class="span9 gallery-viewer-left"></div><div class="span3 gallery-viewer-right"></div></div></div>');
		this.fbContainer = this.container.parents('.fancybox-inner');

		this.leftContainer = main_template.find('.gallery-viewer-left');
		this.rightContainer = main_template.find('.gallery-viewer-right');

		this.rightContainer.html('<div class="gallery-viewer-gallery-info"><h2>' + this.gallery.name + '</h2><p>' + this.gallery.description + '</p></div><hr>');

		this.imageContainer = $('<div class="gallery-viewer-image"></div>').appendTo(this.leftContainer);
		
		var next = $('<button class="gallery-viewer-next gallery-viewer-nav"><i class="icon-chevron-right"></i></button>').appendTo(this.leftContainer);
		var prev = $('<button class="gallery-viewer-prev gallery-viewer-nav"><i class="icon-chevron-left"></i></button>').appendTo(this.leftContainer);

		prev.on('click', function(e) {
			e.preventDefault();
			self.prevImage();
		});

		next.on('click', function(e) {
			e.preventDefault();
			self.nextImage();
		});

		this.infoContainer = $('<div class="gallery-viewer-photo-info"></div>').appendTo(this.rightContainer);

		this.container.html(main_template);

		$(window).on('resize', function() {
			self.fixGallerySize();
		});

		this.loadImage(this.currImage);
	},

	nextImage: function() {
		this.loadImage(this.currImage + 1);
	},

	prevImage: function() {
		this.loadImage(this.currImage - 1);
	},

	fixGallerySize: function() {
		var height = this.fbContainer.height();
		var img = this.imageContainer.find('img.gallery-view-main-image');

		this.leftContainer.css('height', height);
		if (img) {
			img.css({
				maxWidth: '100%',
				maxHeight: height
			});
		}
	},

	loadImage: function(num) {
		var height, img, item, tags = [], i;
		
		if (num > this.gallery.photos.length - 1) {
			num = 0;
		}
		if (num < 0) {
			num = this.gallery.photos.length - 1;
		}

		this.currImage = num;

		item = this.gallery.photos[num];

		if (item) {
			height = this.fbContainer.height();
			img = $('<img class="gallery-view-main-image">');

			this.leftContainer.css('height', height);
			this.imageContainer.html('<img class="gallery-image-loading" src="/img/ajax-loader.gif" alt="">');

			img.attr("src", item.url);
			img.attr("alt", 'photo');
			img.css({
				maxWidth: '100%',
				maxHeight: height,
				display: 'none'
			});

			this.imageContainer.append(img);

			img.bind("load", function () { 
				var im = $(this);
				im.fadeIn(300); 
				im.prev('.gallery-image-loading').remove();
			});

			if (item.tags && item.tags.length) {
				for (i = 0; i < item.tags.length; i += 1) {
					tags.push('<li><span>' + item.tags[i].name + '</span></li>');
				}
			}
			this.infoContainer.html('<h4>About this Photo</h4>');
			if (item.caption) {
				this.infoContainer.append('<p>' + item.caption + '</p>');
			}
			if (item.date) {
				this.infoContainer.append('<p>Added on ' + item.date + '</p>');
			}
			if (tags.length) {
				this.infoContainer.append('<hr><h4>Tags</h4><ul>' + tags.join('') + '</ul>');
			}
		}
	}
}