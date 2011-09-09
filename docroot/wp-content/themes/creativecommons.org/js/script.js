/* Author:

*/




$(document).ready(function(){


  // Dropdown example for topbar nav
  // ===============================

  $("body").bind("click", function (e) {
    $('.dropdown-toggle, .menu').parent("li").removeClass("open");
  });

  $(".dropdown-toggle, .menu").click(function (e) {
    $('.dropdown-toggle, .menu').not(this).parent("li").removeClass("open");
    var $li = $(this).parent("li").toggleClass('open');
    return false;
  });

  $(function(){
    $("#slides").slides({
		generateNextPrev: false,
		pagination: true,
		paginationClass: 'frames',
		preload: false,
		preloadImage: 'img/loading.gif',
		play: 10000,
		pause: 10000,
		effect: 'fade',
		fadeSpeed: 500,
		crossfade: 'true',
		fadeEasing: 'easeOutQuad',
		hoverPause: true,
		animationStart: function(current){
			$('.caption').animate({
				bottom: -100
			},100);
			if (window.console && console.log) {
				// example return of current slide number
				console.log('animationStart on slide: ', current);
			};
		},
		animationComplete: function(current){
			$('.caption').animate({
				bottom:0
			},200);
			if (window.console && console.log) {
				// example return of current slide number
				console.log('animationComplete on slide: ', current);
			};
		},
		slidesLoaded: function() {
			$('.caption').animate({
				bottom:0
			},200);
		}
	});
  });

});

$(window).load( $('div.carousel').css('display', 'block') );
