;(function ($) {
	"use strict";

	var BoojGallery = function(element, options) {
		this.options = $.extend(true, {}, $.fn.boojGallery.defaults, options);
		this.link = $(element);
		this.WIN = $(window);
		this._init();
	};

	BoojGallery.prototype = {
		container: [],
		leftContainer: [],
		rightContainer: [],
		imageContainer: [],
		infoContainer: [],
		currImage: 0,
		gallery: [],
		galleryHolder: [],
		galleryLength: 0,

		_init: function() {
			var self = this;

			this.galleryHolder = $('#gallery-viewer');
			if (!this.galleryHolder.length) {
				this.galleryHolder = $('<div class="hidden"><div id="gallery-viewer"><p class="text-center margin-top-30"><img src="/img/ajax-loader.gif" alt=""></p></div></div>').appendTo($('body'));
			}

			this.link.on('click', function(e) {
				e.preventDefault();
				var el = $(this);
				var obj = this;
				$.fancybox({
					href: '#gallery-viewer',
					type: 'inline',
					autoSize: false,
					width: self.WIN.width() - 100,
					height: self.WIN.height() - 100,
					afterLoad: function() {
						self.container = this.content;
						$.getJSON('/get_gallery_json/' + el.data('id'), function(data) {
							self._renderGallery(data);
						});
					},
					afterClose: function() {
						self.currImage = 0;
						this.content.html('');
					}
				});
			});
		},

		_renderGallery: function(data) {
			var self = this, main_template, next, prev;
			
			this.gallery = data.album || false;

			if (this.gallery) {
				this.galleryLength = this.gallery.photos.length || 0;
			}

			if (this.galleryLength > 0) {
				main_template = $('<div class="container-fluid"><div class="row-fluid"><div class="span9 gallery-viewer-left"></div><div class="span3 gallery-viewer-right"></div></div></div>');
				this.fbContainer = this.container.parents('.fancybox-inner');

				this.leftContainer = main_template.find('.gallery-viewer-left');
				this.rightContainer = main_template.find('.gallery-viewer-right');

				this.rightContainer.html('<div class="gallery-viewer-gallery-info"><h2>' + this.gallery.name + '</h2><p>' + this.gallery.description + '</p></div><hr>');

				this.imageContainer = $('<div class="gallery-viewer-image"></div>').appendTo(this.leftContainer);
				
				next = $('<a href="#" class="gallery-viewer-next gallery-viewer-nav"><img src="/img/gallery-next-arrow.png" alt="next"></a>').appendTo(this.leftContainer);
				prev = $('<a href="#" class="gallery-viewer-prev gallery-viewer-nav"><img src="/img/gallery-prev-arrow.png" alt="previous"></a>').appendTo(this.leftContainer);

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
			}
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
			
			if (num > this.galleryLength - 1) {
				num = 0;
			}
			if (num < 0) {
				num = this.galleryLength - 1;
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
						tags.push('<li><a href="/boojers/' + item.tags[i].username + '"><img src="' + item.tags[i].photo + '" alt=""></a> <a href="/boojers/' + item.tags[i].username + '">' + item.tags[i].name + '</a></li>');
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
					this.infoContainer.append('<hr><h4>Tags</h4><ul class="unstyled clearfix">' + tags.join('') + '</ul>');
				}
				this.infoContainer.append('<p class="gallery-viewer-photo-number">Photo ' + (num + 1) + ' of ' + this.galleryLength + '</p>');
			}
		},

		widget: function() {
			return this;
		}
	};

	$.fn.boojGallery = function (option) {
		var isMethodCall = typeof option === "string",
			args = Array.prototype.slice.call( arguments, 1 ),
			returnValue = this;
		// prevent calls to internal methods
		if ( isMethodCall && option.charAt( 0 ) === "_" ) {
			return returnValue;
		}

		// call internal method
		if ( isMethodCall ) {
			this.each(function() {
				var instance = $(this).data('boojGallery'),
					methodValue = instance && $.isFunction( instance[option] ) ? instance[ option ].apply( instance, args ) : instance;
				if (instance && methodValue && methodValue !== undefined ) {
					returnValue = methodValue;
					return false;
				}
				return false;
			});
		} 
		// instantiate plugin
		else {
			this.each(function () {
				var $this = $(this),
					data = $this.data('boojGallery'),
					options = typeof option === 'object' && option;
				if (!data) {
					$this.data('boojGallery', (data = new BoojGallery(this, options)));
				}
			});
		}

		return returnValue;
	};

	$.fn.boojGallery.defaults = {

	};

	$.fn.boojGallery.Constructor = BoojGallery;

})(jQuery, window);