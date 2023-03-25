/**
 * Custom JS for WP Favorite Posts Plugin
 *
 * @package WP Pro Real Estate 7
 * @subpackage JavaScript
 */

jQuery(document).ready(function ($) {

  
    $(document).on("click", ".save-this-btn", function(e){
      
        "use strict";

        e.preventDefault();

        const anchor = $(this).find('a#ct-btn-fav');
        const oldFavBtnHtml = $("a.wpfp-link[href*='postid="+anchor.attr('rel')+"']");

        anchor.parent().css({
          'opacity': '0.25',
          'pointer-events': 'none'
        });

        let state = {
          url: '?wpfpaction='+ anchor.attr('method') +'&postid=' + anchor.attr('rel') + '&ajax=1',
          button: {
              add: {
                html: ct_favorite_config.saved, //'Saved',
                method: 'remove',
                icon: '<i class="fa fa-heart"></i>'
              },
              remove: {
                html: ct_favorite_config.save, //'Save',
                method: 'add',
                icon: '<i class="fa fa-heart-o"></i>'
              }
          }
        };

        $.ajax({
          url: state.url,
          success: function( response ) {

              const button = state.button[anchor.attr('method') ];

              anchor.html( button.icon + " " + button.html);
              anchor.attr('method', button.method );

              oldFavBtnHtml.html( button.icon );
              oldFavBtnHtml.attr( 'href', '?wpfpaction='+button.method+'&postid=' + anchor.attr('rel') );

              anchor.parent().css({
                'opacity': '',
                'pointer-events': ''
              });

              return;

          }
        });
      
    });

    /**
     * Previous handling of favourite button. 
     */
    
    $(".clear-saved a").addClass("fav-clear");
  
    $(document.body).on("click", ".wpfp-link", function (e) {
      dhis = $(this);
      wpfp_do_js(dhis, 1);
      // for favorite post listing page
      if (dhis.hasClass("remove-parent")) {
        dhis.closest("li.fav-listing").fadeOut();
      }
      // for favorite post listing page
      if (dhis.hasClass("fav-clear")) {
        dhis.closest("ul").fadeOut();
      }
      return false;
    });
  });
  
  function wpfp_do_js(dhis, doAjax) {
    loadingImg = dhis.prev();
    loadingImg.show();
    beforeImg = dhis.prev().prev();
    beforeImg.hide();
    url = document.location.href.split("#")[0];
    params = dhis.attr("href").replace("?", "") + "&ajax=1";
    if (doAjax) {

      
      jQuery.get(url, params, function (data) {
        // Original: dhis.parent().html(data);
        // New: Start ==================================== /
          dhis.html(data);
          
          let $icon = dhis.find('.fa');
          
          var method = 'remove';

          if ( $icon.hasClass('fa-heart-o')) {
            method = 'add';
          }

          var href = decodeURIComponent( dhis.attr('href') );
          var parsedHref = JSON.parse('{"' + decodeURI(href).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');

          dhis.attr('href', '?wpfpaction='+method+'&postid=' + parsedHref.postid );

          // Update the newly created button.
          var $newButton = jQuery("a#ct-btn-fav[rel='"+parsedHref.postid+"']");
          
          if ( "add" === method ) {
            jQuery('#ct-btn-fav').html( data + ct_favorite_config.save );
          } else {
            jQuery('#ct-btn-fav').html( data + ct_favorite_config.saved );
          }

          jQuery('#ct-btn-fav').attr('method', method);
          
        // New: End ==================================== /

        if (typeof wpfp_after_ajax == "function") {
          wpfp_after_ajax(dhis); // use this like a wp action.
        }
        loadingImg.hide();
      });
    }
  }
  