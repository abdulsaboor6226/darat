/*!
 * accounting.js v0.4.2, copyright 2014 Open Exchange Rates, MIT license, http://openexchangerates.github.io/accounting.js
 *
 * Inline this small JS plugin so that it will not bloat the theme.
 */
(function(p,z){function q(a){return!!(""===a||a&&a.charCodeAt&&a.substr)}function m(a){return u?u(a):"[object Array]"===v.call(a)}function r(a){return"[object Object]"===v.call(a)}function s(a,b){var d,a=a||{},b=b||{};for(d in b)b.hasOwnProperty(d)&&null==a[d]&&(a[d]=b[d]);return a}function j(a,b,d){var c=[],e,h;if(!a)return c;if(w&&a.map===w)return a.map(b,d);for(e=0,h=a.length;e<h;e++)c[e]=b.call(d,a[e],e,a);return c}function n(a,b){a=Math.round(Math.abs(a));return isNaN(a)?b:a}function x(a){var b=c.settings.currency.format;"function"===typeof a&&(a=a());return q(a)&&a.match("%v")?{pos:a,neg:a.replace("-","").replace("%v","-%v"),zero:a}:!a||!a.pos||!a.pos.match("%v")?!q(b)?b:c.settings.currency.format={pos:b,neg:b.replace("%v","-%v"),zero:b}:a}var c={version:"0.4.1",settings:{currency:{symbol:"$",format:"%s%v",decimal:".",thousand:",",precision:2,grouping:3},number:{precision:0,grouping:3,thousand:",",decimal:"."}}},w=Array.prototype.map,u=Array.isArray,v=Object.prototype.toString,o=c.unformat=c.parse=function(a,b){if(m(a))return j(a,function(a){return o(a,b)});a=a||0;if("number"===typeof a)return a;var b=b||".",c=RegExp("[^0-9-"+b+"]",["g"]),c=parseFloat((""+a).replace(/\((.*)\)/,"-$1").replace(c,"").replace(b,"."));return!isNaN(c)?c:0},y=c.toFixed=function(a,b){var b=n(b,c.settings.number.precision),d=Math.pow(10,b);return(Math.round(c.unformat(a)*d)/d).toFixed(b)},t=c.formatNumber=c.format=function(a,b,d,i){if(m(a))return j(a,function(a){return t(a,b,d,i)});var a=o(a),e=s(r(b)?b:{precision:b,thousand:d,decimal:i},c.settings.number),h=n(e.precision),f=0>a?"-":"",g=parseInt(y(Math.abs(a||0),h),10)+"",l=3<g.length?g.length%3:0;return f+(l?g.substr(0,l)+e.thousand:"")+g.substr(l).replace(/(\d{3})(?=\d)/g,"$1"+e.thousand)+(h?e.decimal+y(Math.abs(a),h).split(".")[1]:"")},A=c.formatMoney=function(a,b,d,i,e,h){if(m(a))return j(a,function(a){return A(a,b,d,i,e,h)});var a=o(a),f=s(r(b)?b:{symbol:b,precision:d,thousand:i,decimal:e,format:h},c.settings.currency),g=x(f.format);return(0<a?g.pos:0>a?g.neg:g.zero).replace("%s",f.symbol).replace("%v",t(Math.abs(a),n(f.precision),f.thousand,f.decimal))};c.formatColumn=function(a,b,d,i,e,h){if(!a)return[];var f=s(r(b)?b:{symbol:b,precision:d,thousand:i,decimal:e,format:h},c.settings.currency),g=x(f.format),l=g.pos.indexOf("%s")<g.pos.indexOf("%v")?!0:!1,k=0,a=j(a,function(a){if(m(a))return c.formatColumn(a,f);a=o(a);a=(0<a?g.pos:0>a?g.neg:g.zero).replace("%s",f.symbol).replace("%v",t(Math.abs(a),n(f.precision),f.thousand,f.decimal));if(a.length>k)k=a.length;return a});return j(a,function(a){return q(a)&&a.length<k?l?a.replace(f.symbol,f.symbol+Array(k-a.length+1).join(" ")):Array(k-a.length+1).join(" ")+a:a})};if("undefined"!==typeof exports){if("undefined"!==typeof module&&module.exports)exports=module.exports=c;exports.accounting=c}else"function"===typeof define&&define.amd?define([],function(){return c}):(c.noConflict=function(a){return function(){p.accounting=a;c.noConflict=z;return c}}(p.accounting),p.accounting=c)})(this);

/*!
 * Mortgage Calculator initialize function.
 */
var ct_affordability_calculator_widget_init = function( __callback ) {

    let script = document.createElement('script');

    script.src = ct_affordability_calculator.chart_url;

    document.head.appendChild(script); //or something of the likes

    script.onload = function () {
        
        ct_affordability_calculator.chart = ct_mg_initiate_chart();
        ct_mg_recalc_output( ct_affordability_calculator.model );
        ct_mg_setup_sliders();

        if ( typeof __callback === "function" ) {
            __callback();
        }
    };

    script.onerror = function () {
        console.warn("An error has occured while loading Chart.js url");
        // Just continue.
        ct_mg_recalc_output( ct_affordability_calculator.model );
        ct_mg_setup_sliders();

        if ( typeof __callback === "function" ) {
            __callback();
        }
        
    }

    return;
    
}



/*!
 * Pass ct_affordability_calculator.model to model for brevity.
 */
let model = ct_affordability_calculator.model;

/*!
 * Start the chart.
 */
if ( typeof ct_affordability_calculator.chart === "undefined" ) { 
    ct_affordability_calculator.chart = ct_mg_initiate_chart();
}

jQuery(document).ready(function($){

    "use strict";
    
    ct_affordability_calculator_widget_init(function(){
       

        $("#ct-af-form-field-interest-rate").trigger("ct-interest-rate-format");
        let price_maxlength = accounting.formatNumber(ct_affordability_calculator.price_to).length + 1; //+1 for dollar sign.
        $("#ct-af-form-field-home-price").attr("maxlength", price_maxlength);
        $("#ct-af-form-field-downpayment").attr("maxlength", price_maxlength);

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

    });
    
    /**
     * Home Price: Add listener to listing price. 
     */
    $(document).on("input", "#ct-af-form-field-home-price", function(){
        
        var listing_price = accounting.formatNumber( $( this ).val() );
            model.price = accounting.unformat( listing_price );

        model.price_formatted = ct_affordability_calculator.currency + listing_price;
        model.downpayment = ( model.downpayment_percentage / 100 ) * model.price;

        $( "#ct-af-form-field-downpayment-percentage" ).trigger('change');
       
        ct_mg_recalc_output(model);

    });

    /**
     * Add listener to loan type change.
     */
    $(document).on("change", "#ct-af-form-field-loan-type", function(){

        let user_selection = $(this).val().trim().split("|");
        let loan_term = user_selection[0];
        let loan_interest_rate = user_selection[1];
        let loan_type = user_selection[2];
           
        if ( $.isNumeric( loan_term ) && $.isNumeric( loan_interest_rate ) ) {

            model.loan_term = parseInt( loan_term );
            model.loan_type = loan_type;
            model.interest_rate = accounting.formatNumber( loan_interest_rate, {precision: 2} );
           
        }
     
        ct_mg_recalc_output( model );  

    });

    /**
     * Add listener to interest rate field.
     *
     */
    $(document).on("keyup change", "#ct-af-form-field-interest-rate", function(e){

        let field_val = $(this).val();
        let interest_rate = accounting.unformat( field_val );
            
            if ( Number.isNaN(interest_rate ) )  return;
            if ( field_val.substring(field_val.length-1) === "." ) return;
            if ( field_val.substring(0,1) === "." ) return;
            if ( field_val === "0.00" || field_val === "0.0" ) { return; }
          
            if ( interest_rate >= 100 ) {
                interest_rate = 100;
            }

            model.interest_rate = interest_rate;
           
            model.interest_rate_formatted = accounting.formatNumber(interest_rate, {precision: 2});

            $("#ct-af-form-field-downpayment-interest-rate").slider({ value: model.interest_rate });

            ct_mg_recalc_output(model);
        
    }).on("keypress", "#ct-af-form-field-interest-rate", ct_mg_disallow_non_integer).on("input change keyup ct-interest-rate-format", "#ct-af-form-field-interest-rate", 
    function(e){
        let __left = 31;
        let length =  $(this).val().length;

        __left =  14 + ( 6 * length );
        
        if ( $(this).val().indexOf(".") >= 0 ) {
             __left = 13 + ( 6 * length );
        }

        if ( length >= 5 ) {
            __left = 16 + ( 6 * length );
        }

        $("#interest_rate_percentage").css({
            left: __left + "px"
        })
    });

     /**
     * Add listener to downpayment percentage.
     */
    $(document).on("input keyup change", "#ct-af-form-field-downpayment-percentage", function(){
        
        let field_val = $(this).val();
        let downpayment_percentage = ct_mg_parse_float( field_val );

        if ( Number.isNaN(downpayment_percentage ) )  return;
        
        if ( field_val.indexOf(".") >= 0 ) {
            $(this).attr("maxlength", 5);
        } else {
           
            $(this).attr("maxlength", 3);
        }

        if ( field_val.substring(field_val.length-1) === "." ) return;
        if ( field_val.substring(0,1) === "." ) return;

        let recalculated_downpayment = ( downpayment_percentage / 100 ) * model.price;

        model.downpayment = accounting.toFixed( recalculated_downpayment, 0 );
        
        model.downpayment_formatted = ct_affordability_calculator.currency + accounting.formatNumber( model.downpayment );
     
        model.downpayment_percentage = downpayment_percentage;

        if ( model.downpayment_percentage >= 100 ) {
            model.downpayment_percentage = 100;
        }
          

        $("#ct-af-form-field-downpayment-slider").slider({ value: model.downpayment_percentage });
        
        ct_mg_recalc_output(model);

    }).on("input keyup change ct-interest-rate-form-downpayment", "#ct-af-form-field-downpayment-percentage", function(){

        ct_mg_downpayment_format_percentage( $(this) );

    }).on("blur", "#ct-af-form-field-downpayment-percentage", function(){
        let length =  $(this).val().length;
        if ( length === 0 ) {
            model.downpayment_percentage = 0;
            $(this).val(0).trigger("change");
        }
    }).on("keypress", "#ct-af-form-field-downpayment-percentage", ct_mg_disallow_non_integer);

    /**
     * Add listener to downpayment figure change.
     */
    $(document).on("keyup change", "#ct-af-form-field-downpayment", function(){

        let field_val = $(this).val();

        if ( 0 === field_val.length ) return;
        if ( field_val.substring(field_val.length-1) === "." ) return;
        if ( field_val.substring(0,1) === "." ) return;

        let downpayment_formatted = accounting.formatNumber( field_val );
        
        model.downpayment = accounting.unformat( downpayment_formatted );
        
        model.downpayment_formatted = ct_affordability_calculator.currency + downpayment_formatted;

        let percentage = ( model.downpayment / model.price ) * 100;

        model.downpayment_percentage = ct_mg_parse_float( percentage.toFixed(2));
        
        $("#ct-af-form-field-downpayment-slider").slider({ value: model.downpayment_percentage });

        ct_mg_recalc_output(model);
        
    });

    /**
     * Disallow non integers for downpayment
     * and Prevent downpayment
     */
    $(document).on("input keyup keypress","#ct-af-form-field-downpayment", function(evt){
        
        let field_val = ct_mg_parse_float( $(this).val() );
        let downpayment = field_val.toFixed(0);
        
        model.downpayment = downpayment;

        ct_mg_downpayment_format_percentage( $("#ct-af-form-field-downpayment-percentage") );

        if ( model.downpayment >= model.price ) { 

            model.downpayment = model.price;
            model.downpayment_formatted = ct_affordability_calculator.currency + accounting.formatNumber( model.downpayment );
            model.downpayment_percentage = 100;
            // calculate the output.
            ct_mg_recalc_output(model);

            var is_highlighted = ct_mg_is_input_highlighted( $(this) );
            if ( is_highlighted ) {
                return true;
            }
            return false; 
        }

        
        
        return true;

    }).on("keypress","#ct-af-form-field-downpayment", function(evt){

        var charCode = (evt.which) ? evt.which : event.keyCode

        if (charCode > 31 && (charCode < 48 || charCode > 57)) {

           return false;
                
        }

    });

    /**
     * Setup the sliders
     */
    ct_mg_setup_sliders();

});


function ct_mg_downpayment_format_percentage( element ) {
    
    var $ = jQuery.noConflict();

    let __left = 24;
    let length =  element.val().length;

    __left =  17 + ( 6 * length );

    if ( element.val().indexOf(".") >= 0 ) {
        __left = 14 + ( 6 * length );
    }
    if ( length === 0 ) {
        __left = 25 + ( 6 * length );
    }
    if ( length === 1 ) {
        __left = 19 + ( 6 * length );
    }
    if ( length >= 3 ) {
        __left = 13 + ( 6 * length );
        if ( element.val().indexOf(".") >= 0 ) {
            __left = 11 + ( 6 * length );
            $("#ct-af-form-field-downpayment-percentage").css({padding: "0 10px 0 0", "text-align": "left;"});
        }
    }

    if ( length >= 5 ) {
        $("#downpayment_percentage_symbol").css({ display: "none"});
        $("#ct-af-form-field-downpayment-percentage").css({padding: "0 0 0 0", "text-align": "center;"});
    } else {
        $("#downpayment_percentage_symbol").css({ display: "flex"});
        $("#ct-af-form-field-downpayment-percentage").css({padding: "0 10px 0 0", "text-align": "left;"});
        if ( length === 3 ){
            $("#ct-af-form-field-downpayment-percentage").css({padding: "0 14px 0 0", "text-align": "left;"});
        }
    }
    let padding = 5;
    $("#downpayment_percentage_symbol").css({
        left: __left - padding + "px"
    });
}

function ct_mg_disallow_non_integer(e){

    // Prevent non numeric characteres except period.
    var charCode = (e.which) ? e.which : event.keyCode
    
    // Period.
    if ( charCode === 46 ) {
        return true;
    }
    if ( charCode > 31 && (charCode < 48 || charCode > 57) ) {
        return false;
    }

    var point = jQuery(this).val().split(".");

    if ( point[1] ) {

        var is_highlighted = ct_mg_is_input_highlighted( jQuery(this) );
        
        if (point[1].length>=2) {
            if ( is_highlighted ) {
                return true;
            }
            return false;
        }
    }

    return true;

}

/**
 * Setup Sliders.
 */
function ct_mg_setup_sliders() {
    
    var $ = jQuery.noConflict();

    $( "#ct-af-form-field-downpayment-slider" ).slider({
        min: 0,
        max: 30,
        range: "min",
        value: ct_affordability_calculator.model.downpayment_percentage,
        slide: function( event, ui ) {
            $( "#ct-af-form-field-downpayment-percentage" ).val( ui.value ).trigger('change');
        }
    });

    $( "#ct-af-form-field-downpayment-interest-rate" ).slider({
        min: 0,
        max: 6.50,
        range: "min",
        value: 2.78,
        step: 0.10,
        slide: function( event, ui ) {
            $( "#ct-af-form-field-interest-rate" ).val(ui.value).trigger('change');
        },
    });

    $( "#ct-af-form-field-listing-price" ).slider({
        min: parseInt( ct_affordability_calculator.price_from ),
        max: parseInt( ct_affordability_calculator.price_to ),
        range: "min",
        value: ct_affordability_calculator.model.price,
        step: 1000,
        slide: function( event, ui ) {
            $( "#ct-af-form-field-home-price" ).val(ui.value).trigger('input');
            $( "#ct-af-form-field-downpayment-percentage" ).trigger('change');
        },
    });
}

/**
 * Initiate Chart.
 */
function ct_mg_initiate_chart() {

    if ( ! document.getElementById('ct-affordability-calculator-chart') ) {
        return;
    }

    var ctx = document.getElementById('ct-affordability-calculator-chart').getContext('2d');

    if ( typeof Chart === "undefined" ) {
        return;
    }
    
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'doughnut',

        // The data for our dataset
        data: {
            datasets: [{
                data: [],
                backgroundColor: [
                    '#052286',
                    '#00adbb',
                    '#c2d500'
                ]
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Principal & Interest',
                'Property Taxes',
                'Home Insurance'
            ],
        
        },
        // Configuration options go here
        options: {
            legend: {
                display: false
            },
            responsive: true,
            maintainAspectRatio: true,
            cutoutPercentage: 75
        }
    });

    return chart;

}

/**
 * Recalculate the output.
 */
window.ct_mg_recalc_output = function ct_mg_recalc_output( args ) {

    /**
     * Format numbers accordingly.
     */
    args.interest_rate = accounting.formatNumber( args.interest_rate, { precision: 2 } );
    args.downpayment = accounting.unformat( args.downpayment );
    args.tax_rate = accounting.unformat( args.tax_rate );
    args.price = accounting.unformat( args.price );
    
    let price = args.price;
    let principal_and_interest = ct_mg_calc_get_principal_interest( args );
    let property_taxes_annual = (args.tax_rate / 100) * args.price;
    let property_taxes_monthly = property_taxes_annual / 12;
    let home_insurance = accounting.unformat(ct_affordability_calculator.model.home_insurance) / 12;
    let total_monthly = accounting.unformat( accounting.toFixed( principal_and_interest, 0 ) )
                    + accounting.unformat( accounting.toFixed( property_taxes_monthly, 0 )  )
                    + accounting.unformat( accounting.toFixed( home_insurance, 0 ) );
 

    // Data sanitizations.

    // 1.0 Downpayment must not be higher than listing price.
    if ( args.downpayment > args.price ) {
        args.downpayment = args.price;
    }
    // 2.0 Downpayment percentage must not be higher than 100%;
    if ( args.downpayment_percentage > 100 ) {
        args.downpayment_percentage = 100;
    }
    

    var $ = jQuery.noConflict();

    // Update the result
    $("#ct-af-calc-result-principal-interest").html( ct_affordability_calculator.currency + parseInt( principal_and_interest.toFixed(0) ).toLocaleString() );
    $("#ct-af-calc-result-property-taxes").html( ct_affordability_calculator.currency + parseInt( property_taxes_monthly.toFixed(0) ).toLocaleString() );
    $("#ct-af-calc-result-home-insurance").html( ct_affordability_calculator.currency + parseInt( home_insurance.toFixed(0) ).toLocaleString() );

    // Update the interest rate field.
    if( $("#ct-af-form-field-interest-rate").is(":focus") ) {
        $("#ct-af-form-field-interest-rate").val( args.interest_rate );
    } else {
        $("#ct-af-form-field-interest-rate").val( accounting.formatNumber( args.interest_rate, {precision: 2}) );
    }
  
    // Update the listing price.
    $('#ct-af-form-field-home-price').val( ct_affordability_calculator.currency + accounting.formatNumber( args.price ) );

    // Update the downpayment field.
    $('#ct-af-form-field-downpayment').val(args.downpayment_formatted);

    // Update the downpayment percentage field.
    $('#ct-af-form-field-downpayment-percentage').val(args.downpayment_percentage);

    // Donut Chart
    $("#ct-affordability-calculator-est-payment").html( ct_affordability_calculator.currency + parseInt( total_monthly ).toLocaleString() );
    $("#donut-chart-label-figure").html( ct_affordability_calculator.currency + parseInt( total_monthly ).toLocaleString() );
    $(".listing-est-payment").html( ct_affordability_calculator.currency + parseInt( total_monthly ).toLocaleString() );

    // Update Chart.
   
    if ( typeof ct_affordability_calculator.chart !== "undefined" && ct_affordability_calculator.chart !== "0" ) {
        ct_affordability_calculator.chart.data.datasets[0].data = [principal_and_interest.toFixed(2),property_taxes_monthly.toFixed(2),home_insurance.toFixed(2)];
        ct_affordability_calculator.chart.update();
    }
}

/**
 * Parse any numeric string into float. 
 */
function ct_mg_parse_float(s) {

    s = s.replace(/[^\d,.-]/g, ''); // strip everything except numbers, dots, commas and negative sign
    if (navigator.language.substring(0, 2) !== "de" && /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(s)) // if not in German locale and matches #,###.######
    {
        s = s.replace(/,/g, ''); // strip out commas
        return parseFloat(s); // convert to number
    }
    else if (/^-?(?:\d+|\d{1,3}(?:\.\d{3})+)(?:,\d+)?$/.test(s)) // either in German locale or not match #,###.###### and now matches #.###,########
    {
        s = s.replace(/\./g, ''); // strip out dots
        s = s.replace(/,/g, '.'); // replace comma with dot
        return parseFloat(s);
    }
    else // try #,###.###### anyway
    {
        s = s.replace(/,/g, ''); // strip out commas
        return parseFloat(s); // convert to number
    }
    
}

/**
 * Calculate and return the principal and interest rate.
 */
function ct_mg_calc_get_principal_interest( args ) {
        
    // formula M = P[r(1+r)^n/((1+r)^n)-1)]
    let price = args.price;
    let downpayment = model.downpayment;
    let principal_amount = price - downpayment;
    let monthly_payment = 0;

    // Sanitize values to prevent negative input.
    args.interest_rate = Math.abs( args.interest_rate );

    let m_interest_rate = args.interest_rate/1200;
    
    let loan_term = args.loan_term * 12;

    if ( "conventional" === args.loan_type ) {
     
        if ( args.interest_rate !== 0 ) {
            monthly_payment = (principal_amount * m_interest_rate) / (1 - (Math.pow((1 + m_interest_rate) , loan_term * -1)));
        } else {
            monthly_payment = principal_amount / loan_term;
        }
    }
    
    if ( "fha" === args.loan_type ) {
        monthly_payment = ct_mg_calc_get_fha( args.loan_term ) * 1;
    }

    if ( "va" === args.loan_type ) {
        monthly_payment = ct_mg_calc_get_va();
    }

    return monthly_payment;

}

/**
 * Calculate an return the monthly payment for FHA.
 */
function ct_mg_calc_get_fha( numYears ) {

    numYears = parseInt( numYears );
    let m_interest_rate = model.interest_rate/1200;
    let loan_term = model.loan_term * 12;


    function getTotal( principal, payment) {
        if ( model.interest_rate !== 0 ) {
            return ((((principal * m_interest_rate) / (1 - Math.pow(1 + m_interest_rate, (-1 * loan_term))) * 100) / 100) + payment);
        } else {
            return (principal / loan_term) + payment;
        }
        
    }
    
    var principal = model.price - (model.price * ( model.downpayment_percentage / 100));

    var fhaPrincipal = principal + (principal * 0.0175);

    var fhaPayment;

    if (numYears === 15) {
        
        if ( model.downpayment_percentage >= 10) {
            fhaPayment = ((fhaPrincipal * 0.0025) / 12);
        } else if ( model.downpayment_percentage < 10) {
            fhaPayment = ((fhaPrincipal * 0.0050) / 12);
        }

    } else if(numYears === 30) {

        if ( model.downpayment_percentage >= 5) {
            fhaPayment = ((fhaPrincipal * 0.0055) / 12);
        } else if ( model.downpayment_percentage < 5) {
            fhaPayment = ((fhaPrincipal * 0.0060) / 12);
        }

    }

    var fhaTotal;
    
    fhaTotal = getTotal(fhaPrincipal, fhaPayment);
    
    return fhaTotal.toFixed(2);
}

/**
 * Calculate VA
 */
function ct_mg_calc_get_va() {

    var va_principal;

    let m_interest_rate = model.interest_rate/1200;

    let loan_term = model.loan_term * 12;

    var principal = model.price - (model.price * ( model.downpayment_percentage / 100));

    function getTotal( principal, payment) {
        if ( model.interest_rate !== 0 ) {
            return ((((principal * m_interest_rate) / (1 - Math.pow(1 + m_interest_rate, (-1 * loan_term))) * 100) / 100) + payment);
        } else {
            return (principal / loan_term) + payment;
        }
        
    }

    if ( model.downpayment_percentage >= 10) {
        va_principal = (principal * 1.0125);
    } else if ( model.downpayment_percentage >= 5 && model.downpayment_percentage < 10) {
        va_principal = (principal * 1.015);
    } else {
        va_principal = (principal * 1.0215);
    }

    var vaTotal = getTotal( va_principal, 0);

    return vaTotal.toFixed(2) * 1;

}

/**
 * Check if text is highlighted.
 */
function ct_mg_is_input_highlighted(input) {

    var selecttxt = '';

    if (window.getSelection) {
        selecttxt = window.getSelection();
    } else if (document.getSelection) {
        selecttxt = document.getSelection();
    } else if (document.selection) {
        selecttxt = document.selection.createRange().text;
    }

    if ( selecttxt === '') {
        return false;
    }

    return true;
}