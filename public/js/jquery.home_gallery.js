;(function ($) {
	"use strict";
	
	var HomeGallery = function (element, options) {
		this.container = $(element);
		this.options = $.extend(true, {}, $.fn.homeGallery.defaults, options);
		this._build();
	};

	HomeGallery.prototype = {
		imageList: [],

		_build: function() {
			var self = this;
			$.getJSON(this.options.imgSrcUrl, function(data) {
				self.imageList = data.thumbs;
				self._showImages();
			});
		},

		_showImages: function() {
			// this.tempItems = this.imageList.slice();
			for(var img in this.imageList) {
				if (this.imageList.hasOwnProperty(img)) {
					this._createImageElement(this.imageList[img]);
				}
			}
		},
	
		_createImageElement: function (item) {
			console.log(item);
			var imageContainer, link, img;
			
			imageContainer = $('<div class="imageContainer"></div>');

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

			return imageContainer;
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
		imageMargin: 6
	};

	$.fn.homeGallery.Constructor = HomeGallery;

})(jQuery, window);