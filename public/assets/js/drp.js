// $(window).scroll(function() {
//   if ($(document).scrollTop() > window.innerHeight / 2) {
//     $("#nav").removeClass("bg-transparent");
//     $("#nav").addClass("bg-black");
//   } else {
//     $("#nav").removeClass("bg-black");
//     $("#nav").addClass("bg-transparent");
//   }
// });

(function($) {
    "use strict"; // Start of use strict
  
    // Smooth scrolling using jQuery easing
    $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function() {
      if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
          $('html, body').animate({
            scrollTop: (target.offset().top - 120)
          }, 1000, "easeInOutExpo");
          return false;
        }
      }
    });
  
    // Closes responsive menu when a scroll trigger link is clicked
    $('.js-scroll-trigger').click(function() {
    });


    
  
    // Activate scrollspy to add active class to navbar items on scroll
    $('body').scrollspy({
      target: '#nav',
      offset: 90
    });
  
  })(jQuery); // End of use strict