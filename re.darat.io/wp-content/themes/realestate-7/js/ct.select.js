/**
 * CT Custom Select
 *
 * @package WP Pro Real Estate 7
 * @subpackage JavaScript
 */

jQuery.noConflict();

(function($) {
	$(document).ready(function(){

		/*-----------------------------------------------------------------------------------*/
		/* Add Custom Select */
		/*-----------------------------------------------------------------------------------*/
		
		jQuery('select:not(.nice-ignore)').niceSelect();
		jQuery('select:not(.nice-ignore)').niceSelect('update');

		jQuery(document).one('click', '.saved-alert-on-off .nice-select .option:not(.disabled)', function (t) {
                                            
		    var s = jQuery(this),
		    n = s.closest('.nice-select');
		    
		    if(ct_select['user_role'] == 'buyer') {
			    if(s.data('value') == 'sms' || s.data('value') == 'onsms'){
			    	if(ct_select['user_mobile'] == '' && jQuery('.ct-no-mobile-alert').length === 0) {
				    	jQuery('ul#saved-searches').before(jQuery('<div class="col span_12 first ct-no-mobile-alert"><p class="ct-note ct-alert-sm marB20">Mobile number does not exist for SMS, please enter a mobile number via your <a href="' + ct_select['account_settings_url'] + '#mobile"><strong>account settings</strong></a>.</p></div>').hide().fadeIn('normal'));
				    }
			    }
			}

		});
		
	});
	
})(jQuery);
