/**
 * Author: James Olson
 * Options:
 *	sensitivity: (number) sensitivity threshold (must be 1 or higher)    
 *	interval: (number) milliseconds for onMouseOver polling interval    
 *	timeout: (number) milliseconds delay before onMouseOut
 *	ARIA: (boolean) to apply aria accessability roles and states
 *  removeTitle: (boolean) to remove title attributes using javascript
 * Usage Example: $(element).boojstrapDropdownNavigation({options});
 * Head Example:
 * Markup Example:
 */
;(function($){
	var defaults = {    
		sensitivity: 2,   
		interval: 50,
		timeout: 50,
		ARIA: true,
		removeTitle: true
	};
	var BoojstrapDropdownNavigation = function(element, o) {
		this.options = $.extend({}, defaults, o || {});
		this.container = $(element);
		var self = this;
		
		if (!$.fn.hoverIntent) {
			$.error('The jquery hoverIntent plugin is required for the boojstrapDropdownNavigation to work. Please load this file: /js/lib/jquery.hoverIntent.minified.js');
		}
		
		if (this.options.ARIA) {
			this.container.attr('role', 'menubar');
		}

		this.container.find('li').each(function() {
			var li = $(this);
			if (self.options.ARIA) {
				li.find('a').attr('role', 'menuitem');
			}
			if (li.hasClass('dropdown')) {
				li.hoverIntent({
					sensitivity: self.options.sensitivity,
					interval: self.options.interval,
					timeout: self.options.timeout,
					over: function() {
						var el = $(this);
						var sub = el.find("ul.dropdown-menu");
						el.find('a:first').addClass('active');
						self.container.find("ul.dropdown-menu").hide();
						if (sub.length) {
							sub.show();
							if (self.options.ARIA) {
								sub.attr('aria-hidden', 'false');
							}
						}
					},
					out: function() {  
						var el = $(this);
						var sub = el.find("ul.dropdown-menu");
						el.find('a:first').removeClass('active');
						if (sub.length) {
							sub.hide();
							if (self.options.ARIA) {
								sub.attr('aria-hidden', 'true');
							}
						}
					}
				});
				if (self.options.ARIA) {
					li.find("ul.dropdown-menu").attr('aria-hidden', 'true');
				}				
			}
		});

		if (this.options.removeTitle) {
			this.container.find('a').each(function() {
				$(this).removeAttr('title');
			});
		}
	};

	$.fn.boojstrapDropdownNavigation = function(o) {
		return this.each(function() {
			var element = $(this);
			if (element.data('boojstrapDropdownNavigation')) {
				return;
			}
			var boojstrapDropdownNavigation_instance = new BoojstrapDropdownNavigation(this, o);
			element.data('boojstrapDropdownNavigation',boojstrapDropdownNavigation_instance);
		});		
	};	
})(jQuery);