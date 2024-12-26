/***************************************************************************************************************
||||||||||||||||||||||||||||         CUSTOM SCRIPT FOR Smart Up             ||||||||||||||||||||||||||||||||||||
****************************************************************************************************************
||||||||||||||||||||||||||||              TABLE OF CONTENT                  ||||||||||||||||||||||||||||||||||||
****************************************************************************************************************
****************************************************************************************************************
1 revolutionSliderActiver
2. select menu
3. Brand Carosule
3. speaker Carosule
4. gallery
5 gMap
6. progressBarConfig
7. countDownTimer
8. contactFormValidation
9. mobileNavToggler
10. stickyHeader
11. GalleryFancyboxActivator
12. GalleryFancyboxActivator
13. GalleryFancyboxActivator 
****************************************************************************************************************
||||||||||||||||||||||||||||            End TABLE OF CONTENT                ||||||||||||||||||||||||||||||||||||
****************************************************************************************************************/


"use strict";
// 1 revolutionSliderActiver
function revolutionSliderActiver () {
	if ($('.rev_slider_wrapper #slider1').length) {
		$("#slider1").revolution({
            dottedOverlay:"yes",
			sliderType:"standard",
			sliderLayout:"auto",
			delay:5000000000000000,
            autoHeight:"off",
            
            
			navigation: {
				arrows:{enable:true} 
			}, 
			gridwidth:1170,
			gridheight:753
		});
	};
}


// 2. select menu
function selectMenu () {
	if ($('.select-menu').length) {
		$('.select-menu').selectmenu();
	};
}


// 3. Brand Carosule
function brandCarosule () {
	if ($('.brand-carousel').length) {
		$('.brand-carousel').owlCarousel({
		    loop: true,
		    margin: 90,
		    nav: false,
		    dots: false,
		    autoplay: true,
		    autoWidth: true,
		    autoplayTimeout: 3000,
		    autoplayHoverPause: true,
		    responsive: {
		        0:{
		            items:2
		        },
		        480:{
		            items:3
		        },
		        600:{
		            items:4
		        },
		        1000:{
		            items:5
		        },
		        1200:{
		            items:5
		        }
		    }
		});
	}
}
// 3. speaker Carosule
function speakerCarosule () {
	if ($('.speaker-carousel').length) {
		$('.speaker-carousel').owlCarousel({
		    loop: true,
		    margin: 5,
		    nav: true,
		    navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>'
            ],
		    dots: false,
		    autoplay: true,
		    // autoWidth: true,
		    autoplayTimeout: 3000,
		    autoplayHoverPause: true,
		    responsive: {
		        0:{
		            items:2
		        },
		        480:{
		            items:3
		        },
		        600:{
		            items:4
		        },
		        1000:{
		            items:6
		        },
		        1200:{
		            items:6
		        }
		    }
		});
	}
}


// 4. gallery
function smartupGallery () {
	if ($('.event-gallery-content').length) {
		$('.event-gallery-content').mixItUp();
	};
}


// 5 gMap
function gMap () {
	if ($('.google-map').length) {
        $('.google-map').each(function () {
        	// getting options from html 
        	var mapName = $(this).attr('id');
        	var mapLat = $(this).data('map-lat');
        	var mapLng = $(this).data('map-lng');
        	var iconPath = $(this).data('icon-path');
        	var mapZoom = $(this).data('map-zoom');
        	var mapTitle = $(this).data('map-title');

        	// if zoom not defined the zoom value will be 15;
        	if (!mapZoom) {
        		var mapZoom = 15;
        	};
        	// init map
        	var map;
            map = new GMaps({
                div: '#'+mapName,
                scrollwheel: false,
                lat: mapLat,
                lng: mapLng,
                zoom: mapZoom
            });
            // if icon path setted then show marker
            if(iconPath) {
        		map.addMarker({
	            	icon: iconPath,
	                lat: mapLat,
	                lng: mapLng,
	                title: mapTitle
	            });
        	}
        });  
	};
}


// 6. progressBarConfig
function progressBarConfig () {
	var progressBar = $('.skills-name li');
	if(progressBar.length) {
		progressBar.each(function () {
			var Self = $(this);
			Self.appear(function () {
				var progressValue = Self.data('value');

				Self.find('.progress-value-bar').animate({
					width:progressValue+'%'						
				}, 2000);

				Self.find('.value').countTo({
					from: 0,
				    to: progressValue,
				    speed: 5000
				});
			});
		})
	}

}


// 7. countDownTimer

function countDownTimer () {
	if ($('.slider-countdown').length) {

		$('.slider-countdown').each(function () {
			var Self = $(this);
			var countDate = Self.data('countdown-time'); // getting date

			Self.countdown(countDate, function(event) {
	     		$(this).html('<h2>'+ event.strftime('%D : %H : %M : %S') +'</h2>');
	   		});
		});

		

	};
	if ($('.slider-countdown-two').length) {

		$('.slider-countdown-two').each(function () {
			var Self = $(this);
			var countDate = Self.data('countdown-time'); // getting date

			Self.countdown(countDate, function(event) {
	     		$(this).html('<li> <div class="box"> <span class="days">'+ event.strftime('%D') +'</span> <p class="timeRef">days</p> </div> </li> <li> <div class="box"> <span class="hours">'+ event.strftime('%H') +'</span> <p class="timeRef">hours</p> </div> </li> <li> <div class="box"> <span class="minutes">'+ event.strftime('%M') +'</span> <p class="timeRef">minutes</p> </div> </li> <li> <div class="box"> <span class="seconds">'+ event.strftime('%S') +'</span> <p class="timeRef">seconds</p> </div> </li>');
	   		});
		});

		

	};
}
// 8. contactFormValidation
function contactFormValidation () {

	if($('.cf-validation').length){
		$('.cf-validation').validate({ // initialize the plugin
			rules: {
				name: {
					required: true
				},
				email: {
					required: true,
					email: true
				},
				message: {
					required: true
				},
				subject: {
					required: true
				}
			},
			submitHandler: function (form) { 
				// sending value with ajax request
				$.post($(form).attr('action'), $(form).serialize(), function (response) {
					$(form).parent('div').append(response);
					$(form).find('input[type="text"]').val('');
					$(form).find('input[type="email"]').val('');
					$(form).find('textarea').val('');
				});
				return false;
			}
		});
	}
}

// 9. mobileNavToggler
function mobileNavToggler () {
	if ($('#mainNavWrapper').length) {
		$('.nav-footer ul li.dropdown').children('a').append(function () {
			return '<button class="dropdown-expander"><i class="fa fa-bars"></i></button>';    			
		});
		$('.nav-footer ul .dropdown-expander').on('click', function () {
			var Self = $(this);
			Self.parent().parent().children('.submenu').toggleClass('closed opened');
			return false;
		});

	}
}
// 10. stickyHeader
function stickyHeader () {
	if ($('header.header').length) {
		var strickyScrollPos = 100;
		if($(window).scrollTop() > strickyScrollPos) {
			$('header.header').removeClass('fadeIn animated');
	      	$('header.header').addClass('stricky-fixed fadeInDown animated');
		}
		else if($(this).scrollTop() <= strickyScrollPos) {
			$('header.header').removeClass('stricky-fixed fadeInDown animated');
	      	$('header.header').addClass('slideIn animated');
		}
	};
}
// 11. GalleryFancyboxActivator
function GalleryFancyboxActivator () {
	var galleryFcb = $('.fancybox');
	if(galleryFcb.length){
		galleryFcb.fancybox({
			openEffect  : 'elastic',
			closeEffect : 'elastic',
			helpers : {
				media : {},
				overlay: {
			      locked: false
			    }
			}
		});
	}
}
// 12. GalleryFancyboxActivator
function backgroundSlider () {
	if($('.cooming-soon .background-slider').length) {
		$('.cooming-soon .background-slider').vegas({
			delay: 5000,
			timer: false,
			transition: ['slideDown', 'fade2'],
			animation: [ 'kenburnsUp', 'kenburnsDown', 'kenburnsLeft', 'kenburnsRight' ],
		    slides: [
		        { src: "img/slides/1.jpg" },
		        { src: "img/slides/2.jpg" },
		        { src: "img/slides/3.jpg" }
		    ]
		});
	}
}

// 13. GalleryFancyboxActivator 
function accrodion () {
	if ($('.accrodion-grp').length) {
		
		$('.accrodion-grp').each(function () {
			var accrodionName = $(this).data('grp-name');
			var Self = $(this);
			Self.addClass(accrodionName);
			Self.find('.accrodion .accrodion-content').hide();
			Self.find('.accrodion.active').find('.accrodion-content').show();
			Self.find('.accrodion').each(function() {
				var SelfAccrodion = $(this);
				SelfAccrodion.find('.accrodion-title').on('click', function () {
					var SelfAccrodionTitle = $(this);
					if (SelfAccrodionTitle.parent().hasClass('active') === false ) {					
						$('.accrodion-grp.'+accrodionName).find('.accrodion').removeClass('active');
						$('.accrodion-grp.'+accrodionName).find('.accrodion').find('.accrodion-content').slideUp();
						SelfAccrodionTitle.parent().addClass('active');					
						SelfAccrodionTitle.parent().find('.accrodion-content').slideDown();	
					};
				});
			});
		});
		
	};
}

// instance of fuction while Document ready event	
jQuery(document).on('ready', function () {
	(function ($) {
	revolutionSliderActiver();
    brandCarosule();
	selectMenu();
    smartupGallery();
    gMap();
    countDownTimer();
    speakerCarosule();
    contactFormValidation();
    mobileNavToggler();
    GalleryFancyboxActivator();
    backgroundSlider();
    accrodion();
        
	})(jQuery);
});



// instance of fuction while Window Load event
jQuery(window).on('load', function () {
	(function ($) {
		progressBarConfig();
	})(jQuery);
});

// instance of fuction while Window Load event
jQuery(window).on('scroll', function () {
	(function ($) {
		stickyHeader();
	})(jQuery);
});
