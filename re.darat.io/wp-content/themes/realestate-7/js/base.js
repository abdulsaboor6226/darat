/**
 * Base
 *
 * @package WP Pro Real Estate 7
 * @subpackage JavaScript
 */

jQuery.noConflict();


function manageFeaturedTags() {

	// Wrapped this into a function
	// so that its available in ct.mapping.js
	jQuery('h6.snipe.status.featured').removeClass('featured');

	jQuery('h6.snipe.status span').text(function (_, txt) {
		return txt.replace('Featured', '');
	});

	jQuery('h6.snipe span').text(function (_, txt) {
		return txt.replace('Ghost', '');
	});

	jQuery('h6.snipe span').text(function (_, txt) {
		return txt.replace('Weekly', '');
	});

	jQuery('h6.snipe span').text(function (_, txt) {
		return txt.replace('Week', '');
	});

	jQuery('h6.snipe span').text(function (_, txt) {
		return txt.replace('Daily', '');
	});

	jQuery('h6.snipe span').text(function (_, txt) {
		return txt.replace('Day', '');
	});

	/* Show once text is replaced */
	jQuery('h6.snipe').show();
}

(function ($) {

	"use strict";

	$('.flexslider').resize();

	$(".mo-openid-app-icons a.login-button").removeAttr("style");

	/*-----------------------------------------------------------------------------------*/
	/* Login/Register Loader */
	/*-----------------------------------------------------------------------------------*/

	$('#ct_login_submit').click(function () {
		$('#login-register-progress').css("display", "inline-block");
		$("#login-register-progress").delay(1700).fadeOut(300);
	});

	$('#ct_register_submit').click(function () {
		
		var targetElement = {
			submit_button: $('#ct_register_submit')
		};

		var originalButtonHtml = targetElement.submit_button.html();

		re7MaybeVerifyRequest( targetElement, function(){

			$('#ct_register_submit').html( originalButtonHtml );

			$('#register-progress').css("display", "inline-block");
			$("#register-progress").delay(1700).fadeOut(300);

			$.ajax({
				url: mapping_ajax_object.ajax_url,
				type: 'POST',
				data: $("#ct_registration_form").serialize() + '&action=ct_add_new_member',
				success: function (response) {
					console.log(response);
					if (response.success) {
						location.href = response.redirect;
					} else {
						let error_text = '';
						if (response.errors) {
							$.each(response.errors, function (k, error) {
								error_text += '<span class="error"><strong>' + error + '</strong></span><br />';
							});
						}
						$('#ct_account_register_errors').html('<div class="ct_errors">' + error_text + '</div>');
					}
				}
			});

		}, function( errors ){

		});

		
		return false;
	});

	$(".wp-social-login-provider-list a").addClass("btn");

	/*-----------------------------------------------------------------------------------*/
	/* Main Nav Setup */
	/*-----------------------------------------------------------------------------------*/

	$("#masthead ul.sub-menu").closest("li").addClass("drop");

	$('#masthead .multicolumn > ul > li.menu-item-has-children > a').each(function () {
		if ($(this).attr("href") == "#" || $(this).attr("href") == "") {
			var $this = $(this);
			$('<span class="col-title">' + $(this).text() + '</span>').insertAfter($this);
			$this.remove();
		}
	});

	/*-----------------------------------------------------------------------------------*/
	/* Remove "Ghost" status from Search Select */
	/*-----------------------------------------------------------------------------------*/

	$("#ct_ct_status option[value='ghost']").remove();

	$(".post-categories").remove();

	/*-----------------------------------------------------------------------------------*/
	/* CT IDX Pro Disclaimer */
	/*-----------------------------------------------------------------------------------*/

	$("#disclaimer").html(function (i, html) {
		return html.replace(/ /g, '');
	});

	$("#disclaimer").html(function (i, html) {
		return html.replace('<br>', '');
	});

	/*-----------------------------------------------------------------------------------*/
	/* Comparison Slide Out */
	/*-----------------------------------------------------------------------------------*/

	let ct_compare_toggle = (e) => {

		e.preventDefault();

		// Create menu variables.
		let slideoutMenu = $('#compare-list'),
			slideoutMenuWidth = $('#compare-list').width();

		// Toggle open class.
		slideoutMenu.toggleClass("open");
		$("i.fa-chevron-left").toggleClass("fa-chevron-right");

		// Slide menu.
		if (slideoutMenu.hasClass("open")) {
			slideoutMenu.animate({
				right: "0px"
			});
		} else {
			slideoutMenu.animate({
				right: -slideoutMenuWidth
			}, 240);
		}
	}

	$(document).on("click", ".alike-button", function(e){
		if ( ! $('#compare-list').hasClass("open") ) {
			ct_compare_toggle(e);
		}
	});

	$('#compare-panel-btn').on('click', function(e){
		ct_compare_toggle(e);
	});

	/*-----------------------------------------------------------------------------------*/
	/* Header Search - Status Multi */
	/*-----------------------------------------------------------------------------------*/

	$("#header_status_multi label[for=ct_status_multi]").click(function () {
		$('#header_status_multi').toggleClass('open');
		return false;
	});

	$(document).click(function () {
		$('#header_status_multi').removeClass('open');
	});

	$('#header_status_multi, #header_status_multi label[for=ct_status_multi]').on('click', function (e) {
		e.stopPropagation();
	});

	// Status Count Checked Displau
	var $status_checkboxes = $('#header_status_multi input[type="checkbox"]');
	var status_num_checked = $status_checkboxes.filter(':checked').length;

	console.log(status_num_checked);

	if(status_num_checked == 0) {
		$('#ct-status-text').text(object_name.all_statuses);
		$('#ct-status-count').text('');
	} else {
		$('#ct-status-text').text(object_name.status_label);
		$('#ct-status-count').text('(' + status_num_checked + ')');
	}

	$status_checkboxes.change(function(){
        var statusCountCheckedCheckboxes = $status_checkboxes.filter(':checked').length;
        if(statusCountCheckedCheckboxes == 0) {
        	$('#ct-status-text').text(object_name.all_statuses);
			$('#ct-status-count').text('');
		} else {
			$('#ct-status-text').text(object_name.status_label);
			$('#ct-status-count').text('(' + statusCountCheckedCheckboxes + ')');
		}
    });

	/*-----------------------------------------------------------------------------------*/
	/* Header Search - City Multi */
	/*-----------------------------------------------------------------------------------*/

	$("#header_city_multi label[for=header_city_multi]").click(function () {
		$('#header_city_multi').toggleClass('open');
		return false;
	});

	$(document).click(function () {
		$('#header_city_multi').removeClass('open');
	});

	$('#header_city_multi, #header_city_multi label[for=header_city_multi]').on('click', function (e) {
		e.stopPropagation();
	});

	// City Count Checked Displau
	var $city_checkboxes = $('#header_city_multi input[type="checkbox"]');
	var city_num_checked = $city_checkboxes.filter(':checked').length;

	if(city_num_checked == 0) {
		$('#ct-city-text').text(object_name.all_cities);
		$('#ct-city-count').text('');
	} else {
		$('#ct-city-text').text(object_name.city_label);
		$('#ct-city-count').text('(' + city_num_checked + ')');
	}

	$city_checkboxes.change(function(){
        var cityCountCheckedCheckboxes = $city_checkboxes.filter(':checked').length;
        if(cityCountCheckedCheckboxes == 0) {
        	$('#ct-city-text').text(object_name.all_cities);
			$('#ct-city-count').text('');
		} else {
			$('#ct-city-text').text(object_name.city_label);
			$('#ct-city-count').text('(' + cityCountCheckedCheckboxes + ')');
		}
    });

    /*-----------------------------------------------------------------------------------*/
	/* Header Search - State Multi */
	/*-----------------------------------------------------------------------------------*/

	$("#header_state_multi label[for=header_state_multi]").click(function () {
		$('#header_state_multi').toggleClass('open');
		return false;
	});

	$(document).click(function () {
		$('#header_state_multi').removeClass('open');
	});

	$('#header_state_multi, #header_state_multi label[for=header_state_multi]').on('click', function (e) {
		e.stopPropagation();
	});

	// City Count Checked Displau
	var $state_checkboxes = $('#header_state_multi input[type="checkbox"]');
	var state_num_checked = $state_checkboxes.filter(':checked').length;

	if(state_num_checked == 0) {
		$('#ct-state-text').text(object_name.all_states);
		$('#ct-state-count').text('');
	} else {
		$('#ct-state-text').text(object_name.state_label);
		$('#ct-state-count').text('(' + state_num_checked + ')');
	}

	$state_checkboxes.change(function(){
        var stateCountCheckedCheckboxes = $state_checkboxes.filter(':checked').length;
        if(stateCountCheckedCheckboxes == 0) {
        	$('#ct-state-text').text(object_name.all_states);
			$('#ct-state-count').text('');
		} else {
			$('#ct-state-text').text(object_name.state_label);
			$('#ct-state-count').text('(' + stateCountCheckedCheckboxes + ')');
		}
    });

    /*-----------------------------------------------------------------------------------*/
	/* Header Search - County Multi */
	/*-----------------------------------------------------------------------------------*/

	$("#header_county_multi label[for=header_county_multi]").click(function () {
		$('#header_county_multi').toggleClass('open');
		return false;
	});

	$(document).click(function () {
		$('#header_county_multi').removeClass('open');
	});

	$('#header_county_multi, #header_county_multi label[for=header_county_multi]').on('click', function (e) {
		e.stopPropagation();
	});

	// City Count Checked Displau
	var $county_checkboxes = $('#header_county_multi input[type="checkbox"]');
	var county_num_checked = $county_checkboxes.filter(':checked').length;

	if(county_num_checked == 0) {
		$('#ct-county-text').text(object_name.all_counties);
		$('#ct-county-count').text('');
	} else {
		$('#ct-county-text').text(object_name.county_label);
		$('#ct-county-count').text('(' + county_num_checked + ')');
	}

	$county_checkboxes.change(function(){
        var countyCountCheckedCheckboxes = $county_checkboxes.filter(':checked').length;
        if(countyCountCheckedCheckboxes == 0) {
        	$('#ct-county-text').text(object_name.all_counties);
			$('#ct-county-count').text('');
		} else {
			$('#ct-county-text').text(object_name.county_label);
			$('#ct-county-count').text('(' + countyCountCheckedCheckboxes + ')');
		}
    });

    /*-----------------------------------------------------------------------------------*/
	/* Reset Header Listings Search Form */
	/*-----------------------------------------------------------------------------------*/

    function resetHeaderListingsSearchForm($form) {
	    // Form Fields
	    $form.find('input:text, #price-from-to-slider input:hidden, #size-from-to-slider input:hidden, #lotsize-from-to-slider input:hidden, input:password, input:file, select, textarea').val('');
	    $form.find('input:radio, input:checkbox, option').removeAttr('checked').removeAttr('selected');

	    // Reset Status (multi)
	    $('#ct-status-text').text(object_name.all_statuses);
	    $('#ct-status-count').text('');

	    // Reset City (multi)
	    $('#ct-city-text').text(object_name.all_cities);
	    $('#ct-city-count').text('');

	    // Reset State (multi)
	    $('#ct-state-text').text(object_name.all_states);
	    $('#ct-state-count').text('');

	    // Reset County (multi)
	    $('#ct-county-text').text(object_name.all_counties);
	    $('#ct-county-count').text('');

	    // Reset Keywords (additional features tags)
	    $('#additional-features-tags').empty();

	    // Set Bed+ to Any
	    $('#ct-beds-plus-radio-button-1').attr('checked', true); 

	    // Set Bath+ to Any
	    $('#ct-baths-plus-radio-button-1').attr('checked', true);
	    
	    // Reset NiceSelect
	    setTimeout(function() { $('select:not(.nice-ignore)').niceSelect('update'); }, 100);

	    // Reset UI Sliders
	    $('.ui-slider').each(function(){
		  var options = $(this).slider( 'option' );
		  $(this).slider( 'values', [ options.min, options.max ] );
		});

	    if(object_name.currency_placement == 'after') {
			$('#price-from-to-slider span.min-range').html( object_name.price_slider_min_value + object_name.currency );
			$('#price-from-to-slider span.max-range').html( object_name.price_slider_max_value + object_name.currency );
		} else {
			$('#price-from-to-slider span.min-range').html( object_name.currency + object_name.price_slider_min_value );
			$('#price-from-to-slider span.max-range').html( object_name.currency + object_name.price_slider_max_value );
		}

		$('#size-from-to-slider span.min-range').html( object_name.size_slider_min_value + object_name.sqftsm);
		$('#size-from-to-slider span.max-range').html( object_name.size_slider_max_value + object_name.sqftsm );

		$('#lotsize-from-to-slider span.min-range').html( object_name.lot_size_slider_min_value + ' ' + object_name.acres);
		$('#lotsize-from-to-slider span.max-range').html( object_name.lot_size_slider_max_value + ' ' + object_name.acres);
	}

    $('#advanced_search.header-search #ct-reset').click(function() {
        resetHeaderListingsSearchForm($('#advanced_search.header-search'));
    });

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - IDX Interior */
	/*-----------------------------------------------------------------------------------*/

	$(document).on('click','#idx-interior.info-toggle', function () {
		$('#idx-interior.info-toggle + .info-inner').slideToggle('fast');
		$('#idx-interior.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - IDX Exterior */
	/*-----------------------------------------------------------------------------------*/

	$(document).on('click','#idx-exterior.info-toggle', function () {
		$('#idx-exterior.info-toggle + .info-inner').slideToggle('fast');
		$('#idx-exterior.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - IDX Construction */
	/*-----------------------------------------------------------------------------------*/

	$(document).on('click','#idx-construction.info-toggle', function () {
		$('#idx-construction.info-toggle + .info-inner').slideToggle('fast');
		$('#idx-construction.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - IDX Utilities */
	/*-----------------------------------------------------------------------------------*/

	$(document).on('click','#idx-utilities.info-toggle', function () {
		$('#idx-utilities.info-toggle + .info-inner').slideToggle('fast');
		$('#idx-utilities.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - IDX Location */
	/*-----------------------------------------------------------------------------------*/

	$(document).on('click','#idx-location.info-toggle', function () {
		$('#idx-location.info-toggle + .info-inner').slideToggle('fast');
		$('#idx-location.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - IDX Lot */
	/*-----------------------------------------------------------------------------------*/

	$(document).on('click','#idx-lot.info-toggle', function () {
		$('#idx-lot.info-toggle + .info-inner').slideToggle('fast');
		$('#idx-lot.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - IDX Overview */
	/*-----------------------------------------------------------------------------------*/

	$(document).on('click','#idx-overview.info-toggle', function () {
		$('#idx-overview.info-toggle + .info-inner').slideToggle('fast');
		$('#idx-overview.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - IDX Features */
	/*-----------------------------------------------------------------------------------*/

	$(document).on('click', '#idx-features.info-toggle', function () {
		$('#idx-features.info-toggle + .info-inner').slideToggle('fast');
		$('#idx-features.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - IDX Rooms */
	/*-----------------------------------------------------------------------------------*/

	$(document).on('click', "#idx-rooms.info-toggle", function () {
		$('#idx-rooms.info-toggle + .info-inner').slideToggle('fast');
		$('#idx-rooms.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - IDX Schools */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#idx-schools.info-toggle", function () {
		$('#idx-schools.info-toggle + .info-inner').slideToggle('fast');
		$('#idx-schools.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - IDX HOA */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#idx-hoa.info-toggle", function () {
		$('#idx-hoa.info-toggle + .info-inner').slideToggle('fast');
		$('#idx-hoa.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - Book This Listing */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#book-this-listing.info-toggle", function () {
		$('#book-this-listing.info-toggle + .info-inner').slideToggle('fast');
		$('#book-this-listing.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - Multi Floorplan */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#listing-floor-plans.info-toggle", function () {
		$('#listing-floor-plans.info-toggle + .info-inner').slideToggle('fast');
		$('#listing-floor-plans.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - Open House */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#open-house-info.info-toggle", function () {
		$('#open-house-info.info-toggle + .info-inner').slideToggle('fast');
		$('#open-house-info.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - Energy Efficiency */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#energy-efficiency.info-toggle", function () {
		$('#energy-efficiency.info-toggle + .info-inner').slideToggle('fast');
		$('#energy-efficiency.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - Features */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#listing-prop-features.info-toggle", function () {
		$('#listing-prop-features.info-toggle + .info-inner').slideToggle('fast');
		$('#listing-prop-features.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - Attachments */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#listing-attachments-info.info-toggle", function () {
		$('#listing-attachments-info.info-toggle + .info-inner').slideToggle('fast');
		$('#listing-attachments-info.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - Rental Info */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#rental-info.info-toggle", function () {
		$('#rental-info.info-toggle + .info-inner').slideToggle('fast');
		$('#rental-info.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - Reviews */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#listing-reviews-info.info-toggle", function () {
		$('#listing-reviews-info.info-toggle + .info-inner').slideToggle('fast');
		$('#listing-reviews-info.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - Video */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#listing-video-heading.info-toggle", function () {
		$('#listing-video-heading.info-toggle + .info-inner').slideToggle('fast');
		$('#listing-video-heading.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - Virtual Tour */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#listing-virtual-tour-heading.info-toggle", function () {
		$('#listing-virtual-tour-heading.info-toggle + .info-inner').slideToggle('fast');
		$('#listing-virtual-tour-heading.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - What's Nearby */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#listing-nearby-heading.info-toggle", function () {
		$('#listing-nearby-heading.info-toggle + .info-inner').slideToggle('fast');
		$('#listing-nearby-heading.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Lisitng Single Info Toggle - Map */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#listing-map-heading.info-toggle", function () {
		$('#listing-map-heading.info-toggle + .info-inner').slideToggle('fast');
		$('#listing-map-heading.info-toggle').toggleClass('info-toggle-open');
		return false;
	});

	/*-----------------------------------------------------------------------------------*/
	/* Tools Toggle */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", "#tools-toggle", function () {
		$("#text-toggle").text(function (i, text) {
			return text === object_name.close_tools ? object_name.open_tools : object_name.close_tools;
		})
		$('#tools ul').toggle("fast");
	});

	/*-----------------------------------------------------------------------------------*/
	/* Map Toggle */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", ".map-toggle", function () {
		$("#text-toggle").text(function (i, text) {
			return text === object_name.close_map ? object_name.open_map : object_name.close_map;
		})
		$("i.fa-minus-square").toggleClass("fa-plus-square");
		$("#map-wrap").slideToggle(200, function () {
		});
	});

	/*-----------------------------------------------------------------------------------*/
	/* Search Toggle */
	/*-----------------------------------------------------------------------------------*/

	$(document).on("click", ".search-toggle", function () {
		$("#text-toggle").text(function (i, text) {
			return text === object_name.open_search ? object_name.close_search : object_name.open_search;
		})
		$("i.fa-plus-square").toggleClass("fa-minus-square");
		$(".advanced-search").slideToggle(200, function () {
		});
	});

	/*-----------------------------------------------------------------------------------*/
	/* Login/Register & Agent Contact Modal Form */
	/*-----------------------------------------------------------------------------------*/

	$(".login-register").click(function () {
		$('#overlay').addClass('open');
		$('body').addClass('noscroll-modal');
		$('html, body').animate({scrollTop: 0}, 800);
		return false;
	});

	$(".close").click(function () {
		$('body').removeClass('noscroll-modal');
		$("#overlay").removeClass('open');
	});

	$(".ct-registration").click(function () {
		$("#login").slideUp("slow", function () {
			$(document.body).trigger('ct/modal_registration/before');
			$("#register").slideDown("slow", null, function () {
				$(document.body).trigger('ct/modal_registration/after');
			});
		});
	});

	$(".ct-lost-password").click(function () {
		$("#login").slideUp("slow", function () {
			$("#lost-password").slideDown("slow");
		});
	});

	// On Click SignIn It Will Hide Registration Form and Display Login Form
	$(".ct-login").click(function () {
		$("#register").slideUp("slow", function () {
			$("#login").slideDown("slow");
		});
	});

	/*-----------------------------------------------------------------------------------*/
	/* Listing Modal */
	/*-----------------------------------------------------------------------------------*/

	$("#overlay.listing-modal .close").click(function () {
		$("#overlay.listing-modal").removeClass('open');
		$('body').css('overflow', '');
	});

	/*-----------------------------------------------------------------------------------*/
	/* Agent & Brokerage Contact Modal Form */
	/*-----------------------------------------------------------------------------------*/

	$(".agent-contact").click(function (e) {
		$("#overlay.contact-modal").addClass('open');
	});

	$(".brokerage-contact").click(function (e) {
		e.preventDefault();
		$("#overlay.contact-modal").addClass('open');
	});

	$(".close").click(function () {
		$("#overlay.contact-modal").removeClass('open');
		$(".formError").hide();
	});

	/*-----------------------------------------------------------------------------------*/
	/* Brokerage Contact Modal Form */
	/*-----------------------------------------------------------------------------------*/

	$(".brokerage-contact").click(function () {
		$("#overlay.contact-modal").addClass('open');
	});

	$(".close").click(function () {
		$("#overlay.contact-modal").removeClass('open');
		$(".formError").hide();
	});

	/*-----------------------------------------------------------------------------------*/
	/* Edit Profile */
	/*-----------------------------------------------------------------------------------*/

	$("#your-profile h3").addClass("marT0 col span_3 first");
	$("table.form-table").addClass("col span_9");
	$("table.form-table tbody").addClass("col span_12");
	$("#your-profile .description").addClass("muted");
	$(".user-profile-img").addClass("col span_3 first");

	$("#your-profile").show();

	/*-----------------------------------------------------------------------------------*/
	/* WPFP Delete Link */
	/*-----------------------------------------------------------------------------------*/

	$(".wpfp-link.remove-parent:contains('remove')").html("<i class='fa fa-trash-o'></i>");

	$(".wpfp-link.remove-parent").show();

	/*-----------------------------------------------------------------------------------*/
	/* Add Zoom Class to Default WordPress Gallery */
	/*-----------------------------------------------------------------------------------*/

	$(".gallery-icon").addClass("zoom");

	/*-----------------------------------------------------------------------------------*/
	/* Add Btn Class to dsIDXpress Submit */
	/*-----------------------------------------------------------------------------------*/

	$(".advanced-search.dsidxpress .submit").addClass("btn");

	/*-----------------------------------------------------------------------------------*/
	/* FitVids */
	/*-----------------------------------------------------------------------------------*/

	$("article, .videoplayer").fitVids();

	/*-----------------------------------------------------------------------------------*/
	/* Remove height/width from WP inserted images */
	/*-----------------------------------------------------------------------------------*/

	$('img').removeAttr('width').removeAttr('height');

	/*-----------------------------------------------------------------------------------*/
	/* Remove Text from Status Snipes */
	/*-----------------------------------------------------------------------------------*/

	manageFeaturedTags();

	/*-----------------------------------------------------------------------------------*/
	/* Initialize FitVids */
	/*-----------------------------------------------------------------------------------*/

	$(".container").fitVids();

	/*-----------------------------------------------------------------------------------*/
	/* Add class for prev/next icons */
	/*-----------------------------------------------------------------------------------*/

	$('.prev-next .nav-prev a').addClass('fa fa-arrow-left');
	$('.prev-next .nav-next a').addClass('fa fa-arrow-right');

	/*-----------------------------------------------------------------------------------*/
	/* Add Font Awesome Icon to Sitemap list */
	/*-----------------------------------------------------------------------------------*/

	$('.page-template-template-sitemap-php #main-content li a').before('<i class="fa fa-caret-right"></i>');

	/*-----------------------------------------------------------------------------------*/
	/* Add last class to every third related item, and every second testimonial */
	/*-----------------------------------------------------------------------------------*/

	$('li.related:nth-child(3n+3), .testimonial-home li:nth-child(2n+1)').addClass('last');

	/*-----------------------------------------------------------------------------------*/
	/* Smooth ScrollTo */
	/*-----------------------------------------------------------------------------------*/

	$('.est-payment a[href*="#"]:not([href="#"]), .single-listings #listing-map-btn a[href*="#"]:not([href="#"]), .single-listings #listing-sections a[href*="#"]:not([href="#"]), .single-listings #call-email a[href*="#"]:not([href="#"]), .widget_ct_scrolltolistingcontact a[href*="#"]:not([href="#"])').click(function () {
		if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			if (target.length) {
				$('html, body').animate({
					scrollTop: target.offset().top - 220
				}, 1000);
				return false;
			}
		}
	});

	/*-----------------------------------------------------------------------------------*/
	/* Toggle Search Listing */
	/*-----------------------------------------------------------------------------------*/

	$('.search-listings-wrap').on('click', '#search-results-layout-toggle > button, #search-results-layout-toggle-mobile > button', function(){

		let $main_content = $('.search-listings-wrap');

		$main_content.attr('data-layout','grid');
		Cookies.set('ct_search_listing_layout', 'grid');

		if ( $(this).is('#map-layout')) {
			$main_content.attr('data-layout','map');
			Cookies.set('ct_search_listing_layout', 'map');
		}

		$('#search-results-layout-toggle > button, #search-results-layout-toggle-mobile > button').removeClass('current');
		$(this).addClass('current');

	});

	/*-----------------------------------------------------------------------------------*/
	/* Additional Features */
	/*-----------------------------------------------------------------------------------*/

	let ct_keywords_container = $('#ct_keywords_container');
	let ct_additional_features_keyword_search  = $("#ct_additional_features_keyword_search");
	$(document).on("click", "#feature-add-btn", function(e){
		e.preventDefault();

		if ( ct_additional_features_keyword_search.length ) {
			var value = $.trim( ct_additional_features_keyword_search.val() );

			if( value == '' ){
				return;
			}

			ct_additional_features_keyword_search.val( '' ); // reset the text box.

			var lowercase_value = value.toLowerCase();
			var already_added = $("input.ct_additional_features[type=checkbox][value='"+lowercase_value+"']").prop("checked");

			if( already_added ){
				return; // bail out.
			}

			var html = '<span class="additional-feature-tag"><input style="display:none;" checked="checked" type="checkbox" class="ct_additional_features" name="ct_additional_features[]" value="'+lowercase_value+'" />';
			html += '<span class="ct-keyword-name">'+ value +'</span> '+ $('#ct_additional_features_close_tpl').html() +'</span>';

			var additional_features_tags = $('#additional-features-tags');
			additional_features_tags.append( html );

			ct_keywords_container.html( '' );

		}
	});

	$(document).mouseup(function(e){
	    var container = $("#ct_keywords_container");
	    var input = $("#ct_additional_features_keyword_search");

	    // if the target of the click isn't the container nor a descendant of the container
	    if ( container.is(e.target) || container.has(e.target).length || input.is(e.target) || input.has(e.target).length ){
	       return;
	    }

	    container.hide();
	});

	$(document).on("click", ".additional-feature-tag", function(e){
		e.preventDefault();
		var self = $( this );
		self.remove(); 
	});

	$(document).on("click", "ul.ct-additional-features-list li", function(e){
		e.preventDefault();
		var self = $( this );
		var value = self.text();
		ct_additional_features_keyword_search.val( value );
		$("#feature-add-btn").trigger( 'click' );
		ct_keywords_container.hide();
	});

	// Init a timeout variable to be used below
	let keyword_timeout = null;
	let keyword_xhr = null;
	var keyword_selected = -1;

	$(document).on("keydown", "#ct_additional_features_keyword_search", function(e){
		var keycode =  e.which ?  e.which :  e.keyCode;
		if( keycode == 13 ){
			e.preventDefault();

			var selected_keyword = $( "ul.ct-additional-features-list li.ct-keyword-selected" );
			if( selected_keyword.length ){
				selected_keyword.trigger( 'click' );
			}else{
				var first_keyword = $( "ul.ct-additional-features-list li.ct-keyword:first" );
				if( first_keyword.length ){
					first_keyword.trigger( 'click' );
				}
			}
		}
	});

	$(document).on("keyup", "#ct_additional_features_keyword_search", function(e){
		
		var self = $( this );
		var value = $.trim( self.val() );

		if( '' == value || ! value.length ){
			keyword_selected = -1;
			ct_keywords_container.hide();
			return;
		}
	
		var keycode =  e.which ?  e.which :  e.keyCode;

		if(  keycode === 38 || keycode === 40  ){
			var keywords_nodes = document.querySelectorAll('li.ct-keyword');
			if(  keywords_nodes.length  ){

				var ul_keywords = document.querySelector('ul.ct-additional-features-list');

				if ( keycode === 38 ) { // up
					keyword_selected = keyword_selected - 1;
					if( keyword_selected < 0 ){
						keyword_selected = 0;
					}
			        ct_additional_keyword_select(ul_keywords,keywords_nodes, keywords_nodes[keyword_selected]);
			        return;
			    }

			    if ( keycode === 40 ) { // down
			    	keyword_selected = keyword_selected + 1;
			    	if( keyword_selected >= keywords_nodes.length ){
			    		keyword_selected = keywords_nodes.length - 1;
			    	}
			        ct_additional_keyword_select(ul_keywords,keywords_nodes, keywords_nodes[keyword_selected]);
			        return;
			    }
		    }
	    }

		if ( ( keycode <= 90 && keycode >= 48 ) || keycode == 8 ){

 			var data = new Object();
			data.action = 'ct_additional_feature_search';
			data.keyword = value;

		 	clearTimeout( keyword_timeout );

			if( keyword_xhr ){
				keyword_xhr.abort();
			}


			keyword_timeout = setTimeout(function () {
		       keyword_xhr = $.ajax({
					type:"post",
					url:ct_base.ajax_url,
					dataType: 'json',
					data:data,
					beforeSend: function() {
						keyword_selected = -1;
						ct_keywords_container.html('<ul id="additional-features-suggested-results" class="ct-features-loading"><li><span id="additional-features-suggested-results-loader"><i class="fas fa-circle-notch fa-spin fa-fw"></i></span></li></ul>');
						ct_keywords_container.show();
					},
					error: function(e) {

					},
					success: function(jsonR, textStatus, XMLHttpRequest) {
				        ct_keywords_container.html( jsonR.html );
				        ct_keywords_container.show();
				    }
				});
		    }, 100);
		}

	});

})(jQuery);

/*-----------------------------------------------------------------------------------*/
/* Social Popups */
/*-----------------------------------------------------------------------------------*/

function popup(pageURL, title, w, h) {
	var left = (screen.width / 2) - (w / 2);
	var top = (screen.height / 2) - (h / 2);
	var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}

/*-----------------------------------------------------------------------------------*/
/* Mouse Over Effect on Featured Images */
/*-----------------------------------------------------------------------------------*/

jQuery(document).ready(function($){
	
	"use strict";
	// MouseEnter.
	$(document).on("mouseenter", '#search-listing-mapper li.listing', function(){
		$(this).parent().css("background", "none");
		var image = $(this).find('img[data-secondary-img="show-secondary-image-true"]');
		image.next().css({opacity: 1});
		image.css({opacity: 0});
	});
	// MouseLeave.
	$(document).on("mouseleave", '#search-listing-mapper li.listing', function(){
		$(this).parent().css("background", "none");
		var image = $(this).find('img[data-secondary-img="show-secondary-image-true"]');
		image.css({opacity: 1});
		image.next().css({opacity: 0});
	});
});

/*-----------------------------------------------------------------------------------*/
/* Adding custom class to wpadminbar */
/*-----------------------------------------------------------------------------------*/

jQuery(document).ready(function($){
	"use strict";
	// Making theme check happy.
	$("#"+"wpadminbar").addClass("ct-admin-bar");
});

/*-----------------------------------------------------------------------------------*/
/* Google ReCaptcha Verification */
/*-----------------------------------------------------------------------------------*/

var re7MaybeVerifyRequest = function( targetElements, __callback, __errorCallback ) {
	
	console.info('re7MaybeVerifyRequest triggered');

	var $ = jQuery.noConflict();
	
	// Skip to callback when module is disabled.
	if ( "no" === re7GoogleRecaptchaV3.is_enabled ) {
		console.info('Recaptcha is disabled. Submitting the form...');
		__callback();
		return;
	}

	// Skip to callback if there button is not found.
	if ( 0 === targetElements.submit_button.length ) {
		console.warn('Target button is not found in DOM: ' + typeof targetElements.submit_button );
		__callback();	
		return;
	}
	
	// The submit button placeholder.
	var $submitBtn = targetElements.submit_button;
			
	// Apply some CSS to make the button.
	$submitBtn.val( re7GoogleRecaptchaV3.gettext.verifying );

	// Check if button.
	if ( $submitBtn.is( "button" ) ) {
		$submitBtn.html( re7GoogleRecaptchaV3.gettext.verifying );
	}

	// Apple some css.
	$submitBtn.css({
		opacity: '0.65',
		cursor: 'progress'
	}).attr('disabled', 'disabled');

	// Try Invoking ReCaptcha Manually.
	try {

		grecaptcha.execute(
			re7GoogleRecaptchaV3.public_key, 
			{ action: 'submit' }
		).then(
			
			// Callback function for google recaptcha v3 token.
			function(token) {
				
				// Send request to Google Endpoint.
				var parameters = {
					action: 'ct-re-google-verifier',
					token: token
				};
	
				jQuery.post( re7GoogleRecaptchaV3.ajax_url, parameters, function( response, status ){
	
					// Re-apply the button default state.
					$submitBtn.val( re7GoogleRecaptchaV3.gettext.requesting );
	
					$submitBtn.css({
						opacity: '',
						cursor: ''
					}).removeAttr('disabled');
	
					// Remove whatever recaptcha message there is.
					$('.google-recaptcha-response').remove();
	
					var $response = $('<div class="google-recaptcha-response"><p>'+response.message+'</p></div>');
	
					if ( 'success' === response.type ) {
	
						$response.addClass('google-recaptcha-v3-success');
						$submitBtn.before( $response );
	
						// Do callback.
						$('.google-recaptcha-response').remove();
						__callback();
						
					} else {
						
						$response.addClass('google-recaptcha-v3-error');
	
						$submitBtn.before( '<div class="google-recaptcha-response google-recaptcha-response-error"><p>Recaptcha Validation Failed: ' + response.message + '</p></div>' );
						
						__errorCallback( response );
	
					}
	
	
				}).fail(function( error ){
	
					// Remove all existing error message.
					$('.re7-recaptcha-http-error').remove();
									
					// Re-apply the button default state.
					$submitBtn.val( re7GoogleRecaptchaV3.gettext.requesting );
					$submitBtn.css({
						opacity: '',
						cursor: ''
					}).removeAttr('disabled');
	
					var general_error_message = 'Error ' + error.status + ': ' + error.statusText;
	
					$submitBtn.before('<div class="google-recaptcha-v3-error re7-recaptcha-http-error"><p>' + general_error_message + '</p></div>');
	
					__errorCallback( error );
	
				});
			}
		);

	} catch( err ) {

		if ( $('.re7-recaptcha-http-error').length >=1 ) {
			$('.re7-recaptcha-http-error').remove();
		}

		$submitBtn.before('<div class="google-recaptcha-v3-error re7-recaptcha-http-error"><p>' + err + '</p></div>');

		// Re-apply the button default state.
		$submitBtn.val( re7GoogleRecaptchaV3.gettext.requesting );
		$submitBtn.css({
			opacity: '',
			cursor: ''
		}).removeAttr('disabled');

		__errorCallback( err );

	}
}

/*-----------------------------------------------------------------------------------*/
/* Keywords Select */
/*-----------------------------------------------------------------------------------*/

function ct_additional_keyword_select(ul_keywords,keywords_nodes,el) {

    var s = [].indexOf.call(keywords_nodes, el);
    if (s === -1) return;
    
    keyword_selected = s;
    
    var elHeight = jQuery(el).height();
    var scrollTop = jQuery(ul_keywords).scrollTop();
    var viewport = scrollTop + jQuery(ul_keywords).height();
    var elOffset = elHeight * keyword_selected;
    
    if (elOffset < scrollTop || (elOffset + elHeight) > viewport)
        jQuery(ul_keywords).scrollTop(elOffset);
    
    jQuery('li.ct-keyword').removeClass('ct-keyword-selected');
    el.classList.add('ct-keyword-selected');
}
