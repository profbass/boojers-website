;(function ($) {
	"use strict";
	
	var HomeGallery = function (element, options) {
		this.container = $(element);
		this.options = $.extend(true, {}, $.fn.homeGallery.defaults, options);
		this._build();
	};

	HomeGallery.prototype = {
		imageList: [],
		rawList: [],

		_build: function() {
			var self = this;
			this.bdy = $('body');
			this.bdy.css('overflow-y', 'scroll');
			
			$.getJSON(this.options.imgSrcUrl, function(data) {
				self.rawList = data.thumbs;
				self._render();
				$(window).on('resize', function() {
					self._render();
				});
			});
		},

		_render: function() {
			var w = this.container.width(), rows = [];

			this.tempItems = this.rawList.slice();

			while(this.tempItems.length > 0) {
				rows.push(this._buildImageRow(w));
			}

			for (var r in rows) {
				for (var j in rows[r]) {
					if (rows[r][j].el) {
						this._updateImageElement(rows[r][j]);
					} else {
						this._createImageElement(rows[r][j]);
					}
				}
			}
		},

		_buildImageRow: function(maxWidth) {
			var len = 0;
			var item;
			var marg;
			var diff = 0;
			var perBlock = 0;
			var row = [];
			var j;

			while(this.tempItems.length > 0 && len < maxWidth) {
				item = this.tempItems.shift();
				item.twidth = parseInt(item.twidth, 10);
				item.theight = parseInt(item.theight, 10);
				
				row.push(item);
				item.divWidth = item.twidth;
				len += item.twidth + this.options.imageMargin;
			}

			diff = len - maxWidth;

			perBlock = Math.ceil(diff / row.length);
			
			if (perBlock > 0) {
				for (j = 0; j < row.length; j++) {
					row[j].divWidth = row[j].divWidth - perBlock;
				}
			}

			row[row.length - 1].last = true;

			return row;
		},

		_showImages: function() {
			for(var img in this.imageList) {
				if (this.imageList.hasOwnProperty(img)) {
					this._createImageElement(this.imageList[img]);
				}
			}
		},

		_updateImageElement: function (item) {
			var imageContainer = item.el;
			
			imageContainer.css({
				width: item.divWidth
			});
		},

		_createImageElement: function (item) {
			var imageContainer, link, img;
			imageContainer = $('<div class="imageContainer"></div>').css({
				width: item.divWidth
			});

			if (item.last) {
				imageContainer.addClass('last')
			}

			link = $('<a class="viewImageAction" href="' + item.url + '" target="_blank" />');

			img = $("<img/>");
			img.attr("src", item.thumbUrl);
			img.attr("alt", item.alt);
			img.hide();

			link.append(img);
			
			imageContainer.append(link);

			// fade in the image after load
			img.bind("load", function () {
				var el = $(this);
				el.fadeIn(500); 
			});

			this.container.append(imageContainer);

			item.el = imageContainer;
		},

		widget: function() {
			return this;
		}
	};

	$.fn.homeGallery = function (option) {
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
				var instance = $(this).data('homeGallery'),
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
					data = $this.data('homeGallery'),
					options = typeof option === 'object' && option;
				if (!data) {
					$this.data('homeGallery', (data = new HomeGallery(this, options)));
				}
			});
		}

		return returnValue;
	};

	$.fn.homeGallery.defaults = {
		imgSrcUrl: null,
		imageMargin: 4
	};

	$.fn.homeGallery.Constructor = HomeGallery;

})(jQuery, window);