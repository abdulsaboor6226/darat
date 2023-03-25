/**
 * Add option to load listings modal style for search results in Real Estate 7
 *
 * @Joseph G.
 */

/*jshint esversion: 6 */
/*globals jQuery, ajax_listing_single_config*/

jQuery(document).ready(function ($) {

    "use strict";

    let $template = $('#ct-listing-single-modal-template');
    let $original_template = $template.html();

    window.ct_load_single_listing = function (e) {

        // Use local native e.currentTarget, since Google map produces weird issues when using $(this).
        let target = $(e.currentTarget);

        // Normal prevent default.
        e.preventDefault();

        // Disable body scroll.
        $('body').css('overflow', 'hidden');

        // The template.
        let $template = $('#ct-listing-single-modal-template');

        // The link element.
        let link = target;

        // The post id.
        let $post_id = target.attr('rel');

        // Use theme's object to get listing data.
        let listing_object = {};

        var found_property = null;

        if ( typeof property_list !== "undefined" ) {
            found_property = property_list.find( obj => {

                if (obj.listingID === $post_id.toString()) {
                    return obj;
                }

                return null;
                
            });
        }

        if ( found_property ) {
            listing_object = found_property;
        }

        if ( $('.formError').length >= 1) {

            $('.formError').remove();

        }

        // The container list element.
        let $list_element = $('li[data-listing-id="' + $post_id + '"]');

        // Restore the template to original.
        $template.html($original_template);

        // Assigning local changes.
         
        // Title
        $('#listing-title').html(listing_object.title);

        // Snipes (e.g. Featured/"For Sale")
        let $snipes_html = '';

        if ( $list_element.find('.snipe').length >= 1 ) {

            $.each( $list_element.find('.snipe'), function () {
                $snipes_html += $( this ).get(0).outerHTML;
            });

        } else {
            
            if ( listing_object.ctStatus ) {
            
                let ct_status = listing_object.ctStatus.trim();

                let ct_status_name = ct_status.toLowerCase().replace(/\b[a-z]/g, function (letter) {
                    return letter.toUpperCase();
                });

                $snipes_html += '<h6 class="snipe ' + ct_status + '"><span>' + ct_status_name.replace("-", " ") + '</span></h6>';
                
            }

        }

        // Snipes (e.g. For Sale/For Rent)
        $('#snipe-wrap-local').html($snipes_html + '<div class="clear"></div>');

        // Location.
        let $location = '';

        if (typeof listing_object.city !== "undefined" && listing_object.city.length > 0) {
            $location += listing_object.city + ', ';
        }
        if (typeof listing_object.state !== "undefined" && listing_object.state.length > 0) {
            $location += listing_object.state + ', ';
        }
        if (typeof listing_object.zip !== "undefined" && listing_object.zip.length > 0) {
            $location += listing_object.zip;
        }
        // Remove trailing comma ','.
        $location = $location.replace(/,\s*$/, "");

        $('#listing-location').html($location);

        // Price.
        $('#listing-price-local').html(listing_object.fullPrice);

        // Image.
        let image_src = $(listing_object.thumb);

        // Support single listing.
        if ( $('body').hasClass('single-listings')) {
            image_src = target.find('> img.attachment-listings-featured-image');
        }

        $('#listing-first-image').attr('src', image_src.attr('src'));

        history.pushState(
            {urlPath: link.attr('href')}, link.attr('title'),
            link.attr('href')
        );

        $template.addClass('active');

        ct_listing_load_chunk($post_id, 'gallery', $('#ajax-single-listing-gallery-outer-wrap'), function () {

            /**
             * Flex Slider
             */
            $.each($('#ajax-single-listing-gallery-wrap li'), function () {
                $('#ct-listing-single-modal-slides').append( '<li>' + $(this).find('a').html() + '</li>');
            });

            window.ct_single_listing_slider = $('.ajax-listing-modal-flex');

            /**
             * window.addEventListener("orientationchange", function(event) {
             *  console.log("the orientation of the device is now " + event.target.screen.orientation.angle);
             * });
             */
            $( ct_single_listing_slider ).flexslider({
                animation: "slide",
                smoothHeight: true
            });

            /**
             * Magnific Popup
             */
            $('#ajax-single-listing-gallery.multi-image .gallery-item').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true
                }
            });

            if ( $('#ajax-single-listing-gallery').hasClass('single-image') ) {

                $('#single-listing-content-gallery').addClass('single-image-gallery');

            }

        });

        // Header.
        ct_listing_load_chunk($post_id, 'header', $('#ajax-single-listing-heading-wrap'));
        // Price.
        ct_listing_load_chunk($post_id, 'price', $('#ajax-single-listing-price-wrap'));
        // Chunk 1.
        ct_listing_load_chunk($post_id, 'chunk-1', $('#ajax-single-listing-chunk-1-wrap'), function () {
            $('#ajax-single-listing-chunk-1-wrap').removeClass('ajax-single-listing-skeleton-ui');
        });
        // Chunk 2.
        ct_listing_load_chunk($post_id, 'chunk-2', $('#ajax-single-listing-chunk-2-wrap'), function () {
            $('#ajax-single-listing-chunk-2-wrap').removeClass('ajax-single-listing-skeleton-ui');
            // Re-enabled validation engine for the form.
            $("#listingscontact,.listingscontact-form").validationEngine({
                ajaxSubmit: true,
                ajaxSubmitFile: ajax_listing_single_config.ajax_submit_handler,
                ajaxSubmitMessage: ajax_listing_single_config.ajax_submit_message,
                success: false,
                failure: (function () {
                })
            });
            $('#listing-sections-tab a[href="#listing-content"]').trigger('click');

            // Initialized mortgage calculator.

            let price = $("#ajax-single-listing-price-wrap .listing-price").html();
            
            if ( price ) {
                if ( price.length >=1 ) {
                    price = accounting.unformat( price );
                } else {
                    price = 0.00;
                }
            } else {
                price = 0.00;
            }

            ct_affordability_calculator.model.price = price;

            ct_affordability_calculator_widget_init( function(){
                $("#ct-af-form-field-interest-rate").trigger("ct-interest-rate-format");
                let price_maxlength = accounting.formatNumber(ct_affordability_calculator.price_to).length + 1; //+1 for dollar sign.
                $("#ct-af-form-field-home-price").attr("maxlength", price_maxlength);
                $("#ct-af-form-field-downpayment").attr("maxlength", price_maxlength);
            });
            
            setTimeout(function(){
                $("#ct-af-form-field-downpayment-percentage").trigger("change");
            }, 500);
            

            // Format font sizes and faces of percentage.
            let font_size = $("#ct-af-form-field-interest-rate").css('font-size');
            let font_family = $("#ct-af-form-field-interest-rate").css('font-family');
            let font_line_height = $("#ct-af-form-field-interest-rate").css('line-height');
            let css_rule = {
                "font-size": font_size,
                "font-family": font_family,
                "line-height": font_line_height
            };

            $("#interest_rate_percentage").css(css_rule);
            $("#downpayment_percentage_symbol").css( css_rule );

            // Re-instantiate nice select.
            $('#ct-af-form-field-loan-type').niceSelect();

            // Manually trigger some dom content dispatcher for contact form 7 plugin.
            document.querySelectorAll("#ct-listing-single-modal .wpcf7 > form").forEach((
                    function(e){
                        console.log( "Dispatching event for CF7... ");
                        if ( typeof wpcf7.init ==="function" ) {
                            console.log( "CF7 returned wpcf7.init");
                            return wpcf7.init(e)
                        } else {
                            console.log( "Dispatching event for CF7 5.3.2 and below");
                            console.log( "Calling wpcf7.initForm method to dispatch CF 7 event ");
                            wpcf7.initForm(e);
                            
                        }
                    }
                )
            );

            // Re-instantiate the carousel.
            var owl = $('.ajax-modal-sub-listing #owl-listings-carousel-sub-listings');
            owl.owlCarousel({
                items: 2,
                slideBy: 2,
                loop: false,
                //rewind: true,
                margin: 20,
                nav: true,
                navContainer: '.ajax-modal-sub-listing #ct-listings-carousel-nav-sub-listings',
                navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                dots: false,
                autoplay: false,
                responsive:{
                    0:{
                        items: 1,
                        nav: false
                    },
                    600:{
                        items: 2,
                        nav: false
                    },
                    1000:{
                        items: 2,
                        nav: true,
                    }
                }
            });
            
            
        });
    }

    /**
     * This function returns the state of the url as well as reset the view into normal.
     *
     * @returns {boolean} Always true.
     */
    let ct_listing_search_state_return = function () {

        if ($template.hasClass('active')) {

            // Return body overflow to default state when closing.
            $('body').css('overflow', 'visible');

            // Only close when magnific popup is not active.
            if (0 === $('.mfp-wrap').length) {

                // Restore the template to original.
                $template.html($original_template);

                $('#ct-listing-single-modal-template').removeClass('active');

                let saved_link = localStorage.getItem('search_url');
                let saved_link_title = localStorage.getItem('search_page_title');

                history.pushState(
                    {urlPath: saved_link},
                    saved_link_title,
                    saved_link
                );
            }

            if ($('.formError').length >= 1) {
                $('.formError').remove();
            }

        }
        return true;
    };

    /**
     * Loads chunks of data to wp-ajax via $.ajax.
     *
     * @param __post_id     integer The ID of the post.
     * @param template      DOM The template/HTML string.
     * @param $container    DOM Where will template be loaded.
     * @param __success     function Callback function on success.
     * @param __fail        function The Callback function on failure.
     */
    let ct_listing_load_chunk = function (__post_id, template, $container, __success, __fail) {

        // Load the listing single.
        $.ajax({
            url: ajax_listing_single_config.ajax_url,
            data: {
                action: 're7_load_single_listing',
                post_id: __post_id,
                template: template
            },
            success: function (response) {
                $container.html(response.html);
                if (typeof __success == "function") {
                    __success();
                }
            },
            error: function () {
                $container.css('opacity', 1);
                if (typeof __fail == "function") {
                    __fail();
                }
            }
        });
    };
    // Collect all clickable elements.
    let $elements = '.list-listing-title-a, a.listing-link, a.search-view-listing, a.listing-featured-image';

    // Save the search url.
    localStorage.setItem("search_url", window.location.href);
    // Save the page title as well.
    localStorage.setItem("search_page_title", document.title);

    $(document).on('click', $elements, ct_load_single_listing);

    $(document).keyup(function (e) {
        if (e.key === "Escape") { // escape key maps to keycode `27`
            ct_listing_search_state_return();
        }
    });

    $('body').on('click', '#single-listing-close-modal, #ct-listing-back--button', function (e) {
        ct_listing_search_state_return();
    });

    $(window).on("popstate", function (e) {
        if (!window.location.hash) {
            ct_listing_search_state_return();
        }
    });

});