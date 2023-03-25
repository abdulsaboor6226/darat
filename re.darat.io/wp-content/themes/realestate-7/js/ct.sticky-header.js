(function ($) {
  window.init_sticky = function() {

    if ( 0 === $("#header-wrap").length ) {
      return;
    }

    var marginTop = 0;
    // Making theme check happy.
    var $wpadminbar = $( "#" + "wpadminbar" );
   
    // Add the zindex on dom content loaded.
    $(document).ready(function(){
      $wpadminbar.css("z-index", "999999");
    });

    if ( $wpadminbar.length && !$("#topbar-wrap").length) {
      marginTop = 32;
      if ($wpadminbar.has("mobile")) {
        marginTop = 46;
      }
    }

    if ($("#header-wrap").is(".header-style-two")) {
      new Sticky("#nav-full-width", {
        stickyContainer: "#wrapper",
        marginTop: marginTop,
        stickyClass: "sticky",
      });
    } else if ($("#header-wrap").is(".header-style-three")) {
      new Sticky("#header-wrap", {
        stickyContainer: "#wrapper",
        marginTop: marginTop,
        stickyClass: "sticky",
      });
    } else {
      new Sticky("#header-wrap", {
        stickyContainer: "#wrapper",
        marginTop: marginTop,
        stickyClass: "sticky",
      });
    }
  }

  $(function () {

    var masthead_placeholder = $("#header-wrap-placeholder");
    var masthead = $("#header-wrap");
    var masthead_h = parseInt(masthead.height(), 10);

    if (masthead_h > 0) {
      masthead_placeholder.css("height", masthead_h + "px");
    }
    if ( typeof ct_sticky_header_config !== "undefined" && "always_sticky" === ct_sticky_header_config.show_type ) {
      init_sticky();
    }

  });
  if ( typeof ct_sticky_header_config !== "undefined" && "always_sticky" === ct_sticky_header_config.show_type ) {
    init_sticky();
  }

})(jQuery);

/*-----------------------------------------------------------------------------------*/
/* Scroll Up Effect for Sticky Header */
/*-----------------------------------------------------------------------------------*/

jQuery(document).ready(function($) {

  "use strict";

  // Bailout if disabled.
  if ( typeof ct_sticky_header_config !== "undefined" && "always_sticky" === ct_sticky_header_config.show_type ) {
     return;
  } 

  var lastScrollTop = 0;
  var scrolledDown = false;
  var scrolledUp = false;
  var zIndex = 99990;
  
  // Add offset so it doesnt trigger prematurely.
  var offset = 0;

  if ( $('#wpadminbar').length !== 0 ) {
     offset = $('#wpadminbar').height();
  }

  // Getting header height.
  var headerHeight = ($('#header-wrap').height() + offset);

  if ( $('#header-wrap').hasClass('header-style-two') ) {
    headerHeight = $('#nav-full-width').height() + offset;
  }

  // Adjust zIndex for transparent header.
  if ( $('#header-wrap').hasClass('trans-header') ) {
    zIndex = 99990;
    // Snap to top for transparent header.
    $('#header-wrap').css({position: "absolute", width: "100%",left: 0,"z-index": zIndex}).addClass('snap-to-top');
  }

  if( $('#header-wrap').hasClass('trans-header') ) {
     $('#header-wrap').addClass('snap-to-top');
  } 
   
  window.addEventListener("scroll", function() {
  
      var st = window.pageYOffset || document.documentElement.scrollTop;

      // Only trigger when scroll top is greater than the height of header + offset.
      if ( st <= headerHeight ) {

        $('#header-wrap').removeClass('active');
       
        if ( $('#header-wrap').hasClass('trans-header') ) {
          $('#header-wrap').css({position: "absolute", width: "100%",left: 0,"z-index": zIndex}).addClass('snap-to-top');;
        } else {
          $('#header-wrap').css({position: "", width: "", left: "", "z-index": "" } );
        }

        // Remove sticky class for header style 2.
        $('#header-wrap').removeClass("sticky");

        return;
      }

      if (st > lastScrollTop) {
          // User is scrolling down.
          if (!scrolledDown) {
              $('#header-wrap').removeClass('active snap-to-top').addClass('inactive');
              scrolledDown = true;
              scrolledUp = false;
              setTimeout(function(){
                $('#header-wrap').css({position: "static", width: "100%",left: 0,"z-index": zIndex});
              }, 100 );
          }
      } else {
          // User is scrolling up.
          if (!scrolledUp) {
              $('#header-wrap').addClass("sticky");
              $('#header-wrap').removeClass('inactive').addClass('active');
              $('#header-wrap').css({position: "fixed", width: "100%",left: 0,"z-index": zIndex});
              scrolledUp = true;
              scrolledDown = false;
          }
      }

  // For Mobile or negative scrolling.
  lastScrollTop = st <= 0 ? 0 : st; 
  
  }, false);

});
