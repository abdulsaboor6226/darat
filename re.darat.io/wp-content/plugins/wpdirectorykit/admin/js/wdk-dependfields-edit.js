
(function ($) {
	'use strict';
	jQuery(document).ready(function ($) {
		wdk_dependfields_update('form.wdk-depend-fields');
	});
  })(jQuery);
  
  var wdk_dependfields_update_jqxhr = null;
  const wdk_dependfields_update = ($selector = null) => {
	if(!$selector){
		console.log('form not detected')
		return false;
	}

	jQuery($selector).find('input').on('change', function(){
		var _this = jQuery(this);
		var this_form = _this.closest('form');
		var loading_el = _this.closest('.wdk_field');
		
		//var data = this_form.serializeArray();
		var data = new Array();
		data.push({ name: 'action', value: "wdk_admin_action" });
		data.push({ name: 'page', value: "wdk_backendajax" });
		data.push({ name: 'function', value: "update_depend" });
		data.push({ name: 'main_field', value: this_form.find('input[name="main_field"]').val() });
		data.push({ name: 'field_id', value: this_form.find('input[name="field_id"]').val() });

		this_form.find('input:not(:checked)').each(function(){
			if(jQuery(this).attr('name').indexOf('field_hide_') != -1)
				data.push({ name: jQuery(this).attr('name'), value: "1" });
		});
   		// Assign handlers immediately after making the request,
		// and remember the jqxhr object for this request
		if (wdk_dependfields_update_jqxhr != null)
			wdk_dependfields_update_jqxhr.abort();

		loading_el.addClass('wdk_btn_load_indicator out');
		wdk_dependfields_update_jqxhr = jQuery.post(script_dependfields_parameters.ajax_url, data, function (data) {
		
			
		}).always(function(data) {
			loading_el.removeClass('wdk_btn_load_indicator out');
        });
	})
  }