;(function ($) {
	"use strict";
	
	var GPlusGallery = function (element, options) {
		this.container = $(element);

		this.options = $.extend(true, {}, $.fn.gPlusGallery.defaults, options);
		this._build();
	};

	GPlusGallery.prototype = {
		imageList: [],
		tempItems: [],

		_build: function() {
			var self = this;
			$.getJSON(this.options.imgSrcUrl, function(data) {
				self.imageList = data.thumbs;
				self._showImages();
				$(window).on('resize', function () {
					// layout the images with new width
					self._showImages();
				});
			});
		},

		_showImages: function() {
			// reduce width by 1px due to layout problem in IE
			var containerWidth = this.container.width() - 1;
			
			// Make a copy of the array
			this.tempItems = this.imageList.slice();
		
			// calculate rows of images which each row fitting into
			// the specified windowWidth.
			var rows = [];
			while(this.tempItems.length > 0) {
				rows.push(this._buildImageRow(containerWidth));
			}

			for(var r in rows) {
				for(var i in rows[r]) {
					var item = rows[r][i];
					if(item.el) {
						// this image is already on the screen, update it
						this._updateImageElement(item);
					} else {
						// create this image
						this._createImageElement(item);
					}
				}
			}
		},

		/** Utility function that returns a value or the defaultvalue if the value is null */
		_$nz: function (value, defaultvalue) {
			if( typeof (value) === undefined || value == null) {
				return defaultvalue;
			}
			return value;
		},

		/**
		 * Distribute a delta (integer value) to n items based on
		 * the size (width) of the items thumbnails.
		 * 
		 * @method calculateCutOff
		 * @property len the sum of the width of all thumbnails
		 * @property delta the delta (integer number) to be distributed
		 * @property items an array with items of one row
		 */
		_calculateCutOff: function (len, delta, items) {
			// resulting distribution
			var cutoff = [];
			var cutsum = 0;

			// distribute the delta based on the proportion of
			// thumbnail size to length of all thumbnails.
			for(var i in items) {
				var item = items[i];
				var fractOfLen = item.twidth / len;
				cutoff[i] = Math.floor(fractOfLen * delta);
				cutsum += cutoff[i];
			}

			// still more pixel to distribute because of decimal
			// fractions that were omitted.
			var stillToCutOff = delta - cutsum;
			while(stillToCutOff > 0) {
				for(i in cutoff) {
					// distribute pixels evenly until done
					cutoff[i]++;
					stillToCutOff--;
					if (stillToCutOff === 0) break;
				}
			}
			return cutoff;
		},

		/**
		 * Takes images from the items array (removes them) as 
		 * long as they fit into a width of maxwidth pixels.
		 *
		 * @method buildImageRow
		 */
		_buildImageRow: function (maxwidth) {
			var row = [], len = 0, i;
				
			// each image a has a 3px margin, i.e. it takes 6px additional space
			var marginsOfImage = this.options.imageMargin;

			var item;

			// Build a row of images until longer than maxwidth
			while(this.tempItems.length > 0 && len < maxwidth) {
				item = this.tempItems.shift();
				row.push(item);
				len += (item.twidth + marginsOfImage);
			}

			// calculate by how many pixels too long?
			var delta = len - maxwidth;

			// if the line is too long, make images smaller
			if(row.length > 0 && delta > 0) {

				// calculate the distribution to each image in the row
				var cutoff = this._calculateCutOff(len, delta, row);

				for(i in row) {
					var pixelsToRemove = cutoff[i];
					item = row[i];

					item.hideit = false;

					// move the left border inwards by half the pixels
					item.vx = Math.floor(pixelsToRemove / 2);

					// shrink the width of the image by pixelsToRemove
					item.vwidth = item.twidth - pixelsToRemove;
				}
			} else {
				// all images fit in the row, set vx and vwidth
				for(i in row) {
					item = row[i];
					item.vx = 0;
					item.hideit = false;
					item.vwidth = item.twidth;
				}
			}

			if (len < maxwidth) {
				for(i in row) {
					item = row[i];
					item.hideit = true;
				}
			}

			return row;
		},
	
		/**
		 * Creates a new thumbail in the image area. An attaches a fade in animation
		 * to the image. 
		 */
		_createImageElement: function (item) {
			var imageContainer = $('<div class="imageContainer"/>');

			if (item.hideit === true) {
				imageContainer.hide();
			}

			var overflow = $("<div/>");
			overflow.css({
				width: this._$nz(item.vwidth, 120),
				height: this._$nz(item.theight, 120),
				overflow: 'hidden'
			});

			var link = $('<a class="viewImageAction" href="' + item.url + '" target="_blank" />');

			var img = $("<img/>");
			img.attr("src", item.thumbUrl);
			img.attr("alt", item.alt);
			img.css({
				width: this._$nz(item.twidth, 120),
				height: this._$nz(item.theight, 120),
				marginLeft: (item.vx ? (-item.vx) : 0),
				display: 'none'
			});

			link.append(img);
			overflow.append(link);
			imageContainer.append(overflow);

			// fade in the image after load
			img.bind("load", function () { 
				$(this).fadeIn(500); 
			});

			this.container.append(imageContainer);
			item.el = imageContainer;
			return imageContainer;
		},
		
		/**
		 * Updates an exisiting tthumbnail in the image area. 
		 */
		_updateImageElement: function (item) {
			var overflow = item.el.find("div:first");
			var img = overflow.find("img:first");
			overflow.css({
				width: this._$nz(item.vwidth, 120),
				height: this._$nz(item.theight, 120)
			});
			img.css({
				marginLeft: (item.vx ? (-item.vx) : 0)
			});
		},

		widget: function() {
			return this;
		}
	};

	$.fn.gPlusGallery = function (option) {
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
				var instance = $(this).data('gPlusGallery'),
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
					data = $this.data('gPlusGallery'),
					options = typeof option === 'object' && option;
				if (!data) {
					$this.data('gPlusGallery', (data = new GPlusGallery(this, options)));
				}
			});
		}

		return returnValue;
	};

	$.fn.gPlusGallery.defaults = {
		imgSrcUrl: null,
		imageMargin: 6
	};

	$.fn.gPlusGallery.Constructor = GPlusGallery;

})(jQuery, window);