/* Author:
 * Fabricatorz JS Adaptor Code for CC site
 */


j(document).ready(function(){


  // Dropdown for topbar nav
  // ===============================

  j(".topbar").dropdown();
  
  // Help popovers
  // ===============================

	j("a.helpLink").bind("click", function(e) {
		j(this).next('.help-popover').toggleClass('open');
		e.preventDefault();
	});
	
	j(".help-popover a.close").bind('click', function(e) {
		j(this).parent().parent().parent().removeClass('open');
		e.preventDefault();
	});
  
  // Carousel slides
  // ===============================

  j(function(){
    j("#slides").slides({
		generateNextPrev: true,
		next: 'next',
		previous: 'prev',
		pagination: true,
		paginationClass: 'frames',
		preload: false,
		preloadImage: 'img/loading.gif',
		play: 4000,
		pause: 10000,
		effect: 'fade',
		fadeSpeed: 500,
		crossfade: 'true',
		fadeEasing: 'easeOutQuad',
		hoverPause: true,
		animationStart: function(current){
			j('.caption').animate({
				bottom: -100
			},100);
			if (window.console && console.log) {
				// example return of current slide number
				console.log('animationStart on slide: ', current);
			};
		},
		animationComplete: function(current){
			j('.caption').animate({
				bottom:0
			},200);
			if (window.console && console.log) {
				// example return of current slide number
				console.log('animationComplete on slide: ', current);
			};
		},
		slidesLoaded: function() {
			j('.caption').animate({
				bottom:0
			},200);
		}
	});
  });

  // Case studies
  // ===============================

  j(function() {
	j('#case').slides({
		container: 'studies',
		generateNextPrev: false,
		paginationClass: 'frames',
		play: 15000,
		pause: 15000,
		effect: 'fade',
		fadeSpeed: 0
	});
  });

  // Store slides
  // ===============================

  j(function() {
	j("#store").slides({
		container: 'swag',
		generateNextPrev: false,
		pagination: false,
		generatePagination: false,
		play: 7000,
		pause: 7000,
		effect: 'fade',
		fadeSpeed: 500,
		crossfade: 'true',
		fadeEasing: 'easeOutQuad',
		hoverPause: true
	});
  });

});


// Single out IE6 for not displaying the carousel, for all other browsers just
// hope/expect it to work.
if (navigator.userAgent.match(/MSIE\s6/)) {
	j(window).load( j('div.carousel').css('display', 'none') );
} else {
    j(window).load( j('div.carousel').css('display', 'block') );
}
