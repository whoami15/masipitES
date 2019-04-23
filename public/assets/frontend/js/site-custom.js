	/* ==============================================
	Revolution Slider Plugin
	=============================================== */
	$(window).bind("load", function() {
		$(function(){
			
		'use strict';
			$("#pageloader").delay(1200).fadeOut("slow");
	    	$(".loader-item").delay(700).fadeOut();
			
			$('#blog-galler').owlCarousel({
				center: false,
				items:2,
				loop:true,
				dots: false,
				nav:true,
				lazyLoad : true,
				navText: ['&#xf104;', '&#xf105'],
				margin:1,
				responsive:{
					0:{
						items:1,
					},
					600:{
						items:1,
					},
					1000:{
						items:1
					}
				}
			});
		});
	});
	
	/* ==============================================
	Revolution Slider Plugin
	=============================================== */
	$(window).bind("load", function() {
		$(function(){
			
			'use strict';
			var container = jQuery('#bloglist');
			container.masonry({
				layoutMode: 'fitRows'
			});

		});
	});	
	
	
	$(document).ready(function () {
		
		'use strict';
		
		jQuery("#menuzord").menuzord({
			indicatorFirstLevel: "<i class='fa fa-angle-down'></i>",
			indicatorSecondLevel: "<i class='fa fa-angle-right'></i>",
			align: "right"
		});
		
		$(function() {
			var header = $("#nav-wrap"),
				yOffset = 0,
				triggerPoint = 150;
			$(window).scroll(function() {
				yOffset = $(window).scrollTop();
	
				if (yOffset >= triggerPoint) {
					header.addClass("navbar-fixed-top animated fadeInDown");
				} else {
					header.removeClass("navbar-fixed-top animated fadeInDown");
				}
	
			});
		});
		
		$('.icon').click(function () {
			$('.search-input').toggleClass('expanded');
		});
		
		/* Tooltip */
		$('header .social-icons ul li a').tooltip({
			placement: 'left',
			animation:true,
			delay: { show: 200, hide: 100 }
		});
		$('a.like-icons, a.comments-icon, .social-icons ul li a, .demo-button a').tooltip({
			placement: 'top',
			animation:true,
			delay: { show: 200, hide: 100 }
		});
		
		/* hide #back-top first */
		$("#back-top").hide();		
		// fade in #back-top
		$(function () {
			$(window).scroll(function () {
				if ($(this).scrollTop() > 100) {
					$('#back-top').fadeIn();
				} else {
					$('#back-top').fadeOut();
				}
			});	
			// scroll body to 0px on click
			$('#back-top a').click(function () {
				$('body,html').animate({
					scrollTop: 0
				}, 800);
				return false;
			});
		});	
		
		/* Fancybox Lightbox */
		$(".fancybox").fancybox({
			helpers: {
				overlay: {
					locked: false, // try changing to true and scrolling around the page
					title: {
						type: 'outside'
					},
					thumbs: {
						width: 50,
						height: 50
					}
				}
			}
		});
		
		// Twitter Feed
	    $(".tweet-stream").tweet({
	        username: "envato",
	        modpath: "twitter/",
	        count: 1,
	        template: "{text}{time}",
	        loading_text: "loading twitter feed..."
	    });
		
		// Flickr Photostream
		$('#basicuse').jflickrfeed({
	        limit: 12,
	        qstrings: {
	            id: '52617155@N08'
	        },
	        itemTemplate: '<li><a href="{{image_b}}"><img src="{{image_s}}" alt="{{title}}" /></a></li>'
	    });
		
		$('#main-slider-boxed').owlCarousel({
			center: false,
			items:2,
			loop:true,
			margin:2,
			nav:true,
			dots:false,
			navText: ['&#xf104;', '&#xf105'],
			responsive:{
				0:{
					items:1,
				},
				600:{
					items:1,
				},
				1000:{
					items:1,
				},
				1600:{
					items:1
				}
			}
		});
		
		
		$('#main-slider').owlCarousel({
			center: false,
			items:2,
			loop:true,
			margin:2,
			nav:true,
			dots:false,
			navText: ['&#xf104;', '&#xf105'],
			responsive:{
				0:{
					items:1,
				},
				600:{
					items:2,
				},
				1000:{
					items:3,
				},
				1600:{
					items:3
				}
			}
		});
		
		$('#relatedpost-slider').owlCarousel({
			center: false,
			items:1,
			loop:true,
			margin:30,
			nav:true,
			dots:false,
			navText: ['&#xf104;', '&#xf105'],
			responsive:{
				0:{
					items:1,
				},
				600:{
					items:2,
				},
				1000:{
					items:3,
				},
				1600:{
					items:3
				}
			}
		});
		$('#single-slider').owlCarousel({
			center: false,
			items:1,
			loop:true,
			margin:30,
			nav:false,
			dots:true,
			autoHeight: true,
			navText: ['&#xf104;', '&#xf105'],
		});
		
		
		$('#alternate-slider').owlCarousel({
			center: false,
			items:1,
			loop:true,
			margin:0,
			nav:true,
			dots:false,
			navText: ['&#xf104;', '&#xf105'],
			stagePadding: 0,
			responsive:{
				0:{
					items:1,
				},
				600:{
					items:1,
				},
				1000:{
					items:1,
				},
				1600:{
					items:1
				}
			}
		});
		
		//// Google Map
		//$("#map_extended").gMap({
	    //    markers: [{
	    //        address: "",
	    //        html: '<h4>Office</h4>' +
	    //            '<address>' +
	    //            '<div>' +
	    //            '<div><b>Address:</b></div>' +
	    //            '<div>Envato Pty Ltd, 13/2<br> Elizabeth St Melbourne VIC 3000,<br> Australia</div>' +
	    //            '</div>' +
	    //            '<div>' +
	    //            '<div><b>Phone:</b></div>' +
	    //            '<div>+1 (408) 786 - 5117</div>' +
	    //            '</div>' +
	    //            '<div>' +
	    //            '<div><b>Fax:</b></div>' +
	    //            '<div>+1 (408) 786 - 5227</div>' +
	    //            '</div>' +
	    //            '<div>' +
	    //            '<div><b>Email:</b></div>' +
	    //            '<div><a href="mailto:info@mithiliya.com">info@info@mithiliya.com</a></div>' +
	    //            '</div>' +
	    //            '</address>',
	    //        latitude: -33.87695388579145,
	    //        longitude: 151.22183918952942,
	    //        icon: {
	    //            image: "images/pin.png",
	    //            iconsize: [35, 48],
	    //            iconanchor: [17, 48]
	    //        }
	    //    }, ],
	    //    icon: {
	    //        image: "images/pin.png",
	    //        iconsize: [35, 48],
	    //        iconanchor: [17, 48]
	    //    },
	    //    latitude: -33.87695388579145,
	    //    longitude: 151.22183918952942,
	    //    zoom: 16
	    //});
		
		// Contact Form
		jQuery("#contact_form").validate({
	        meta: "validate",
	        submitHandler: function(form) {

	            var s_name = $("#name").val();
	            var s_lastname = $("#lastname").val();
	            var s_email = $("#email").val();
	            var s_phone = $("#phone").val();
	            var s_suject = $("#subject").val();
	            var s_comment = $("#comment").val();
	            $.post("contact.php", {
	                    name: s_name,
	                    lastname: s_lastname,
	                    email: s_email,
	                    phone: s_phone,
	                    subject: s_suject,
	                    comment: s_comment
	                },
	                function(result) {
	                    $('#sucessmessage').append(result);
	                });
	            $('#contact_form').hide();
	            return false;
	        },
	        /* */
	        rules: {
	            name: "required",

	            lastname: "required",
	            // simple rule, converted to {required:true}
	            email: { // compound rule
	                required: true,
	                email: true
	            },
	            phone: {
	                required: true,
	            },
	            comment: {
	                required: true
	            },
	            subject: {
	                required: true
	            }
	        },
	        messages: {
	            name: "Please enter your name.",
	            lastname: "Please enter your last name.",
	            email: {
	                required: "Please enter email.",
	                email: "Please enter valid email"
	            },
	            phone: "Please enter a phone.",
	            subject: "Please enter a subject.",
	            comment: "Please enter a comment."
	        },
	    }); /*========================================*/
		
	});
	
	$(document).ready(function () {
		$(".switch-button").click(function () {
			if ($(this).is(".open")) {
				$(this).addClass("closed");
				$(this).removeClass("open");
				$(".styleswitcher").animate({
					"left": "-202px"
				})
			} else {
				$(this).addClass("open");
				$(this).removeClass("closed");
				$(".styleswitcher").animate({
					"left": "0px"
				})
			}
		})
	});
	$(document).ready(function () {
		if($.cookie("css")) {
			$("link.alt").attr("href",$.cookie("css"));
		}
		$(".color-scheme a").click(function () {
			$("link.alt").attr("href", $(this).attr("rel"));
			$.cookie("css",$(this).attr('rel'), {expires: 365, path: '/'});
			return false
		});
	});