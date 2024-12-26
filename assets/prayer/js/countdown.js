(function($) {
	$.fn.countdown = function(options, callback) {
		
		//custom 'this' selector
		thisEl = $(this);	
		
		// array of custom settings
		var settings = { 
			'date': null,
			'format': null
		};

		// append the settings array to options
		if(options) {
			$.extend(settings, options);
		}
		
	}
}) (jQuery);