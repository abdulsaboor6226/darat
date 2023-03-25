

jQuery(document).ready(function ($) {
    "use strict";
	var userID = UserInfo.userID;
	var ajaxurl = UserInfo.ajaxurl;
	var process_loader_spinner = UserInfo.process_loader_spinner;
	var confirm_message = UserInfo.confirm;


    $.fn.select2.amd.define('select2/data/extended-ajax',['./ajax','./tags','../utils','module','jquery'], function(AjaxAdapter, Tags, Utils, module, $){
        function ExtendedAjaxAdapter ($element,options) {
            this.minimumInputLength = options.get('minimumInputLength');
            this.defaultResults     = options.get('defaultResults');
            ExtendedAjaxAdapter.__super__.constructor.call(this,$element,options);
        }
    
        Utils.Extend(ExtendedAjaxAdapter,AjaxAdapter);
    
        var originQuery = AjaxAdapter.prototype.query;
    
        ExtendedAjaxAdapter.prototype.query = function (params, callback) {
            var defaultResults = (typeof this.defaultResults == 'function') ? this.defaultResults.call(this) : this.defaultResults;
            if (defaultResults && defaultResults.length && (!params.term || params.term.length < this.minimumInputLength)){
                var data = { results: defaultResults };
                var processedResults = this.processResults(data, params);
                callback(processedResults);
            } else if (params.term && params.term.length >= this.minimumInputLength) {
                originQuery.call(this, params, callback);
            } else {
                this.trigger('results:message', {
                    message: 'inputTooShort',
                    args: {
                        minimum: this.minimumInputLength,
                        input: '',
                        params: params
                    }
                });
            }
        };
    
        if (module.config().tags) {
            return Utils.Decorate(ExtendedAjaxAdapter, Tags);
        } else {
            return ExtendedAjaxAdapter;
        }
    });

    $('.recipient-select').each(function () {
        var $select = $(this);
        var $defaultResults = $('option[value]', $select);
        var defaultResults = [];
        
        $defaultResults.each(function () {
            var $option = $(this);
            defaultResults.push({
                id: $option.attr('value'),
                text: $option.text()
            });
        });

        var id = $select.data('propid') || '0';
        var nonce = $select.data('nonce');

        $select.select2({
            minimumInputLength: 3,
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                  return {
                    'action': 'ct_email_alerts_search_recipient',
                    'q': params.term,
                    'id': id,
                    'nonce': nonce
                  };
                },
                processResults: function (data) {
                    if(data.results) {
                        return data;
                    }

                    return {
                        results: data.data
                    };
                },
                cache: true
            },
            allowClear: true,
            dataAdapter: $.fn.select2.amd.require('select2/data/extended-ajax'),
            placeholder: $select.data('placeholder') || 'Search for a lead...',
            defaultResults: defaultResults
        });
    });

    // Change recipient email
    $('.recipient-setting').each(function (){ 
        var $this = $(this);

        $this.data('lastEmail', $this.val());

        $this.change(function (e) {
            e.preventDefault();
            var id = $this.data('propid');
            var nonce = $this.data('nonce');
            var email = $this.val();

            $.post({ 
                url: ajaxurl,
                data: { 
                    'action': 'ct_email_alerts_change_recipient',
                    'email': email,
                    'id': id,
                    'nonce': nonce
                },
                
                beforeSend: function () {
                    $this.attr('disabled', 'disabled');
                    
                    $this.prop("disabled", true);
                },

                success: function (response) {
                    if (response.success) {
                        $this.data('lastEmail', email);
                        console.log(response);
                    } else if(response.data) {
                        $this.val($this.data('lastEmail'));
                        alert(response.data);
                    }
                },

                complete: function () {
                    $this.removeAttr('disabled');
                    $this.prop("disabled", false);
                }
            });
        });
    });

		/*--------------------------------------------------------------------------
         *  Save esetting on Saved Alerts
         * -------------------------------------------------------------------------*/
		 $('.esetting').each(function (i){ 

			var $this = $(this);
			
            $this.change(function (e) {

				e.preventDefault();

				var id = $(this).data('propid');
				var esetting = $(this).val();

                // Apply blur effect.
                $(this).parent().find('.nice-select').css({opacity: '0.5', 'pointer-events': 'none'});

				if( parseInt( userID, 10 ) === 0 ) {
					$('#overlay').addClass('open');
				} else {
					$.ajax({ 
						url:ajaxurl,
						data: { 
							'action': 'ct_email_cron_onoff',
							'esetting': esetting,
							'id': id,
							'author_id': userID,
						},
						method: 'POST',
						dataType: 'JSON',
						beforeSend: function () {
							$this.next('span.customSelect').css('border','1px solid greenyellow');
							
						},
						success: function (response) {
                            
                            var $label = $this.parent().parent().find('> .saved-search-alert-status');

                                $label.removeClass('alert-off alert-on');
                                
                                $label.find('.indicator').removeClass('alert-off alert-on');

                                if ( "off" !== response.esetting ) {
                                    $label.addClass('alert-on');
                                    $label.find('.indicator').addClass('alert-on');
                                } else {
                                    $label.addClass('alert-off');
                                    $label.find('.indicator').addClass('alert-off');
                                }

                                $label.find('.esetting-label').html( response.label );

							if (response.success) {

								console.log(response);

							}
						},
						error: function (xhr, status, error) {
							var err = eval("(" + xhr.responseText + ")");
							console.log(err.Message);
						},
						complete: function () {
                            // Remove blur effect.
                            $this.parent().find('.nice-select').css({opacity: '', 'pointer-events': ''});
							$this.next('span.customSelect').css('border','1px solid #d5d9dd');
							$this.addClass('set');
						}
					});
				}
			 
			 });
		 });
		/*--------------------------------------------------------------------------
         *  Save Search on Searched Listings
         * -------------------------------------------------------------------------*/
        $("#searched-save-search").click(function(e) {
            e.preventDefault();
            var $this = $(this);
            var $form = $('.form-searched-save-search');
			var serialized = $form.serialize();

            if( parseInt( userID, 10 ) === 0 ) {
                $('#overlay').addClass('open');
            } else {
                $.ajax({
                    url: ajaxurl,
                    data: serialized,
                    method: 'POST',
                    dataType: 'JSON',

                    beforeSend: function () {
                        $this.children('i').remove();
                        $this.prepend('<i class="fa-left ' + process_loader_spinner + '"></i>');
                    },
                    success: function (response) {
                        if (response.success) {
                            $this.children('i').remove();
                            $('#searched-save-search').addClass('saved');
							$('#searched-save-search').html('Saved');
                        }
                    },
                    error: function (xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        console.log(err.Message);
                    },
                    complete: function () {
                        $this.children('i').removeClass(process_loader_spinner);
                    }
                });
           }

        });
		/*--------------------------------------------------------------------------
         *  Save Alert Creation Search - Email Alerts
         * -------------------------------------------------------------------------*/
        $("#ct-alert-creation").click(function(e) {
            e.preventDefault();
            var $this = $(this);
            var $form = $('.ctea-alert-creation-form');
			var serialized = $form.serialize();

            if( parseInt( userID, 10 ) === 0 ) {
                $('#overlay').addClass('open');
            } else {
                $.ajax({
                    url: ajaxurl,
                    data: serialized,
                    method: 'POST',
                    dataType: 'JSON',

                    beforeSend: function () {
                        $this.children('i').remove();
                        $this.prepend('<i class="fa-left ' + process_loader_spinner + '"></i>');
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#ct-alert-creation').addClass('saved');
							//console.log(response);
							location.reload();
                        }
                    },
                    error: function (xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        console.log(err.Message);
                    },
                    complete: function () {
                        $this.children('i').removeClass(process_loader_spinner);
                    }
                });
           }

        });
        /*--------------------------------------------------------------------------
        * Delete Search
        * --------------------------------------------------------------------------*/
        $('.remove-search').click(function(e) {
            e.preventDefault();
            var $this = $(this);
            var prop_id = $this.data('propertyid');
            var removeBlock = $this.closest('.saved-search-block');

            if (confirm(confirm_message)) {
                $.ajax({
                    url: ajaxurl,
                    dataType: 'JSON',
                    method: 'POST',
                    data: {
                        'action': 'ct_delete_search',
                        'property_id': prop_id
                    },
                    beforeSend: function () {
                        $this.children('i').remove();
                        $this.prepend('<i class="' + process_loader_spinner + '"></i>');
                    },
                    success: function (res) {
                        if (res.success) {
                            removeBlock.remove();
                        }
                    },
                    error: function (xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        console.log(err.Message);
                    }
                });
            }
        });
}); // end document ready
