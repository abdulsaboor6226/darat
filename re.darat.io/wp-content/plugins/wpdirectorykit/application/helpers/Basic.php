<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function wdk_generate_fields($fields, $db_data)
{
    if(is_array($fields))
    foreach($fields as $field)
    {
        $field = (object) $field;

        $field_type = $field->field_type;

        $field_view_path = WPDIRECTORYKIT_PATH.'application/views/fields_edit/'.$field_type.'.php';

        if(file_exists($field_view_path))
        {
            include $field_view_path;
        }
        else
        {
            echo __('Missing VIEW file: ', 'wpdirectorykit').esc_html($field_type).'.php';
        }
    }
}

if ( ! function_exists('wdk_generate_search_form'))
{
    function wdk_generate_search_form($form_id=1, $subfolder='', $print = TRUE, $predefinedfields_query = array())
    {
        /* load WMVC data */
		$WMVC = &wdk_get_instance();
		$WMVC->model('searchform_m');
        $WMVC->model('field_m');
        $output = '';
        
        /* get WMVC form data */
		$fields = $WMVC->searchform_m->get($form_id, TRUE);
        $user_fields = NULL;

         /* get WMVC form fields */
        if(is_object($fields))
            $user_fields = json_decode($fields->searchform_json);
        /* generate WMVC form fields html */
        if(is_array($user_fields))
        foreach($user_fields as $used_field)
        {  

            if($used_field->field_id == 'cat') {
                $field = array(
                    "idfield" => 'category',
                    "field_type" => 'CATEGORY',
                    "field_label" => __('Category', 'wpdirectorykit'),
                    "prefix" => '',
                    "suffix" => '',
                    "values_list" => '',
                );
            }else if($used_field->field_id == 'loc') {
                $field = array(
                    "idfield" => 'location',
                    "field_type" => 'LOCATION',
                    "field_label" => __('Location', 'wpdirectorykit'),
                    "prefix" => '',
                    "suffix" => '',
                    "values_list" => '',
                );
            }else if($used_field->field_id == 'address') {
                $field = array(
                    "idfield" => 'address',
                    "field_type" => 'INPUTBOX',
                    "field_label" => __('Address', 'wpdirectorykit'),
                    "prefix" => '',
                    "suffix" => '',
                    "values_list" => '',
                );
            }else if($used_field->field_id == 'post_title') {
                $field = array(
                    "idfield" => 'post_title',
                    "field_type" => 'INPUTBOX',
                    "field_label" => __('Title', 'wpdirectorykit'),
                    "prefix" => '',
                    "suffix" => '',
                    "values_list" => '',
                );
            }else if($used_field->field_id == 'search') {
                $field = array(
                    "idfield" => 'search',
                    "field_type" => 'INPUTBOX',
                    "field_label" => __('Search', 'wpdirectorykit'),
                    "prefix" => '',
                    "suffix" => '',
                    "values_list" => '',
                );
            }else if(function_exists('run_wdk_bookings') && $used_field->field_id == 'booking_date') {
                $field = array(
                    "idfield" => 'booking_date',
                    "field_type" => 'BOOKINGS_DATE',
                    "field_label" => __('Booking Date', 'wpdirectorykit'),
                    "prefix" => '',
                    "suffix" => '',
                    "values_list" => '',
                );
            }else if($used_field->field_id == 'more') {
                $field = array(
                    "idfield" => 'MORE',
                    "field_type" => 'MORE',
                    "field_label" => __('MORE', 'wpdirectorykit'),
                    "prefix" => '',
                    "suffix" => '',
                    "values_list" => '',
                );
            } else {
                $field_data = $WMVC->field_m->get_fields_data($used_field->field_id);
                if($field_data) {
                    if($used_field->query_type == 'slider_range') {
                        $field = array(
                            "idfield" => wmvc_show_data('idfield', $field_data),
                            "field_type" => 'SLIDER_RANGE',
                            "field_label" =>wmvc_show_data('field_label', $field_data),
                            "prefix" => wmvc_show_data('prefix', $field_data),
                            "suffix" => wmvc_show_data('suffix', $field_data),
                            "value_min" => wmvc_show_data('value_min', $used_field),
                            "value_max" => wmvc_show_data('value_max', $used_field),
                            "values_list" => wmvc_show_data('values_list', $field_data)
                        );
                    } else {
                        $field = array(
                            "idfield" => wmvc_show_data('idfield', $field_data),
                            "field_type" => wmvc_show_data('field_type', $field_data),
                            "field_label" =>wmvc_show_data('field_label', $field_data),
                            "prefix" => wmvc_show_data('prefix', $field_data),
                            "suffix" => wmvc_show_data('suffix', $field_data),
                            "values_list" => wmvc_show_data('values_list', $field_data)
                        );
                    }
                }
            }

            /* if field not detected or remove from fields list, skip field in search */
            if(empty($field)) continue;

            /* add from user fields data */
            $field['field_id'] = wmvc_show_data('field_id', $used_field);
            $field['class'] = wmvc_show_data('class', $used_field);
            $field['query_type'] = wmvc_show_data('query_type', $used_field);
            $field['columns'] = wmvc_show_data('columns', $used_field);

            
            $field_data = array();
            $field_data ['field_data'] = $field;
            $field_data ['predefinedfields_query'] = $predefinedfields_query;

            if(in_array(wmvc_show_data('field_type', $field),array('SECTION'))) {
                continue;
            }

            $field_view_path = WPDIRECTORYKIT_PATH.'application/views/search_fields/'.$subfolder.(wmvc_show_data('field_type', $field)).'.php';
            if(file_exists($field_view_path))
            {
                $output .= $WMVC->view('search_fields/'.$subfolder.(wmvc_show_data('field_type', $field)), $field_data, FALSE);
            }
            else
            {
                /* show missing view file of field, only if print enable */
                if($print)
                    $output .= __('Missing VIEW file: ', 'wpdirectorykit').esc_html($subfolder).(wmvc_show_data('field_type', $field)).'.php';
            }

        }

        if($print){
            echo $output;
            return FALSE;
        }

        return $output;
    }
}

function &wdk_get_instance()
{
    global $Winter_MVC_WDK;
    
    if(empty($Winter_MVC_WDK))
    {
        $Winter_MVC_WDK = new MVC_Loader(plugin_dir_path( __FILE__ ).'../../');

        $Winter_MVC_WDK->load_helper('basic');
    }

	return $Winter_MVC_WDK;
}

function wdk_prepare_search_query_GET($columns = array(), $model_name = NULL, $external_columns = array(), $custom_parameters = array(), $skip_postget = FALSE)
{

    global $Winter_MVC_WDK;
    $WMVC = &$Winter_MVC_WDK;
    $wdk_bookings_joinded = false;
    static $available_fields_listings = NULL;
    static $available_fields_static = array();
    static $list_fields_static = NULL;
    
    /* add if missing columns */
    if(!in_array('post_id', $columns)) {
        $columns[] = $Winter_MVC_WDK->db->prefix.'wdk_listings.post_id';
    }

    $_GET_clone = array();

    if(!$skip_postget)
        $_GET_clone = array_merge($_GET, $_POST);

    if(isset($_GET_clone['order_by']) && $_GET_clone['order_by'] =='undefined') {
        unset($_GET_clone['order_by']);
    }

    if(isset($custom_parameters['order_by'])) {

        if(isset($_GET_clone['order_by']) && !empty($_GET_clone['order_by'])) {
            $_GET_clone['order_by'] = $custom_parameters['order_by'].', '.$_GET_clone['order_by'];
        } else {
            $_GET_clone['order_by'] =  $custom_parameters['order_by'];
        }

        unset($custom_parameters['order_by']);
    }
       

    if(!empty($custom_parameters))
        $_GET_clone = array_merge($_GET_clone, $custom_parameters); 
    
    $WMVC->model('listing_m');
    $WMVC->model('field_m');
    $WMVC->model('listingfield_m');

    $smart_search = '';
    if(isset($_GET_clone['search']))
        $smart_search = sanitize_text_field($_GET_clone['search']);
    
    if(isset($_GET_clone['field_search']))
        $smart_search = sanitize_text_field($_GET_clone['field_search']);
        
    if(!isset($available_fields_static[$model_name]))
    {
        $available_fields = $WMVC->$model_name->get_available_fields();

        $available_fields_static[$model_name] = $available_fields;
    }
    else
    {
        $available_fields = $available_fields_static[$model_name];
    }

    if($available_fields_listings === NULL)
        $available_fields_listings = $WMVC->listingfield_m->get_available_fields();

    /* Пet column names from wdk_listings_fields and add to allow search + 
        in _GET_clone replace field_#id to field_id_TYPE*/

    if($list_fields_static === NULL)
    {
        $WMVC->db->where(array('field_type !='=> 'SECTION'));
        $list_fields = $WMVC->field_m->get();
        $list_fields_static = $list_fields;
    }
    else
    {
        $list_fields = $list_fields_static;
    }


    foreach($list_fields as $field_data) {
       
        $field_id = wmvc_show_data('idfield',$field_data);
        $field_type = wmvc_show_data('field_type',$field_data);
        $column_name ='field_'.$field_id.'_'.$field_type;
        if(!isset($available_fields_listings[$column_name])) continue;

        if(isset($_GET_clone['field_'.$field_id])) {

            if($field_type == 'DROPDOWNMULTIPLE') {
                $_GET_clone['field_'.$field_id.'_'.$field_type] = $_GET_clone['field_'.$field_id];
            } else {
                $_GET_clone['field_'.$field_id.'_'.$field_type] = apply_filters( 'wdk-currency-conversion/convert/default_value',sanitize_text_field($_GET_clone['field_'.$field_id]), $field_id);
            }
        }

        if(isset($_GET_clone['field_'.$field_id.'_max'])) {
            $_GET_clone['field_'.$field_id.'_'.$field_type.'_max'] = apply_filters( 'wdk-currency-conversion/convert/default_value',sanitize_text_field($_GET_clone['field_'.$field_id.'_max']), $field_id);
        }
        if(isset($_GET_clone['field_'.$field_id.'_min'])) {
            $_GET_clone['field_'.$field_id.'_'.$field_type.'_min'] = apply_filters( 'wdk-currency-conversion/convert/default_value',sanitize_text_field($_GET_clone['field_'.$field_id.'_min']), $field_id);
        }
        if(isset($_GET_clone['field_'.$field_id.'_exactly'])) {
            $_GET_clone['field_'.$field_id.'_'.$field_type.'_exactly'] = sanitize_text_field($_GET_clone['field_'.$field_id.'_exactly']);
        }

        $columns[] = $column_name;
        $columns[] = $column_name.'_max';
        $columns[] = $column_name.'_min';
        $columns[] = $column_name.'_exactly';
        $external_columns[] = $column_name;
        $external_columns[] = $column_name.'_max';
        $external_columns[] = $column_name.'_min';
        $external_columns[] = $column_name.'_exactly';
    }
    
    if(isset($_GET_clone['search_agents_ids'])) {
        $column_name ='agents_ids';
        $_GET_clone[$column_name] = $_GET_clone['search_agents_ids'];
        $columns[] = $column_name;
        $external_columns[] = $column_name;
    }

    if(isset($_GET_clone['search_user_editor_ids'])) {
        $column_name ='user_editor_ids';
        $_GET_clone[$column_name] = $_GET_clone['search_user_editor_ids'];
        $columns[] = $column_name;
        $external_columns[] = $column_name;
    }

    if(isset($_GET_clone['search_location'])) {
        $column_name ='location_id';
        $_GET_clone[$column_name] = $_GET_clone['search_location'];
        $columns[] = $column_name;
        $external_columns[] = $column_name;
    }

    if(isset($_GET_clone['search_category'])) {
        $column_name ='category_id';
        $_GET_clone[$column_name] = $_GET_clone['search_category'];
        $columns[] = $column_name;
        $external_columns[] = $column_name;
    }

    if(isset($_GET_clone['search_gps'])) {
        $column_name ='gps';
        $_GET_clone[$column_name] = $_GET_clone['search_gps'];
        $columns[] = $column_name;
        $external_columns[] = $column_name;
    }
    
    if(isset($_GET_clone['field_address'])) {
        $column_name ='address';
        $_GET_clone[$column_name] = $_GET_clone['field_address'];
        $columns[] = $column_name;
        $external_columns[] = $column_name;
    }

    if (function_exists('run_wdk_bookings') && isset($_GET_clone['field_booking_date_from'])) {
        $column_name ='field_booking_date_from';
        $_GET_clone[$column_name] = $_GET_clone['field_booking_date_from'];
        $columns[] = $column_name;
        $external_columns[] = $column_name;
    }

    if (function_exists('run_wdk_bookings') && isset($_GET_clone['field_booking_date_to'])) {
        $column_name ='field_booking_date_to';
        $_GET_clone[$column_name] = $_GET_clone['field_booking_date_to'];
        $columns[] = $column_name;
        $external_columns[] = $column_name;
    }

    //$table_name = substr($model_name, 0, -2);  
    $columns_original = array();
    foreach($columns as $key=>$val)
    {
        $columns_original[$val] = $val;
        
        // if column contain also "table_name.*"
        $splited = explode('.', $val);
        if(wmvc_count($splited) == 2)
            $val = $splited[1];
        
        if(isset($available_fields[$val]))
        {
            
        }
        else
        {
            if(!in_array($columns[$key], $external_columns))
            {
                unset($columns[$key]);
            }
        }
    }

    if(wmvc_count($_GET_clone) > 0)
    {
        unset($_GET_clone['search']);
        unset($_GET_clone['field_search']);
        
        // For quick/smart search
        if(wmvc_count($columns) > 0 && !empty($smart_search))
        {
            $gen_q = '';
            foreach($columns as $key=>$value)
            {

                if($value == 'field_booking_date_from' || $value == 'field_booking_date_to' || $value == 'agents_ids' || $value == 'gps' || $value == 'is_featured') continue;

                if(strpos($value, '_CHECKBOX') !== FALSE) continue;

                // if smart is number, search only im number fields 
                

                if(is_intval($smart_search)) {
                    if(strpos($value, '_NUMBER') === FALSE && $value != 'category_id' && $value != 'location_id' && strpos($value, 'post_id') === FALSE ) continue;
                } else {
                    if($value == 'category_id' || $value == 'location_id' || strpos($value, 'post_id') !== FALSE) continue;
                }
                
                if(substr_count($value, 'id') > 0 && is_numeric($smart_search))
                {
                    $gen_q.="$value = $smart_search OR ";
                }
                else if(substr_count($value, 'date') > 0)
                {
                    $gen_search = $smart_search;
                    
                    $gen_q.="$value LIKE '%$gen_search%' OR ";
                }
                elseif(substr($value, -8) == '_exactly')
                {
                  
                }
                elseif(substr($value, -4) == '_max')
                {
                  
                }
                else if(substr($value, -4) == '_min')
                {
                 
                }
                else if(strpos($value, '_NUMBER') !== FALSE)
                {
                    if(is_intval($smart_search)){
                        $gen_search = $smart_search;
                        $gen_q.="$value = $gen_search OR ";
                    }
                }
                else
                {
                    $gen_q.="$value LIKE '%$smart_search%' OR ";
                }
            }

            /* location / categories */
            if(!is_intval($smart_search)) {
                $gen_q.="location_title LIKE '%$smart_search%' OR ";
                $gen_q.="category_title LIKE '%$smart_search%' OR ";
            }
            /* get address and gps */
            if(strlen($smart_search) > 5) {
                $coordinates_center = wdk_get_gps($smart_search);
                $search_radius = 5;
                if($coordinates_center && $coordinates_center['lat'] != 0 && is_numeric($search_radius))
                {
                    $distance_unit = 'km';
                    if(__('km', 'wpdirectorykit') == 'm')
                    {
                        $distance_unit = 'm';
                    }
                    
                    // calculate rectangle
                    $rectangle_ne = wdk_getDueCoords($coordinates_center['lat'], $coordinates_center['lng'], 45, $search_radius, $distance_unit);
                    $rectangle_sw = wdk_getDueCoords($coordinates_center['lat'], $coordinates_center['lng'], 225, $search_radius, $distance_unit);
                    
                    $gps_ne = explode(', ', $rectangle_ne);
                    $gps_sw = explode(', ', $rectangle_sw);
                    
                    $gen_q .="(".$WMVC->db->prefix."wdk_listings.lat < '$gps_ne[0]' AND ".$WMVC->db->prefix."wdk_listings.lat > '$gps_sw[0]' AND 
                               ".$WMVC->db->prefix."wdk_listings.lng < '$gps_ne[1]' AND ".$WMVC->db->prefix."wdk_listings.lng > '$gps_sw[1]') OR ";
                    
                }
            }

            /* detect is gps */
            if(wdk_is_gps($smart_search)) {
                $gps = explode(',',$smart_search);
                $coordinates_center = array('lat'=>trim($gps[0]),'lng'=>trim($gps[1]));
                $search_radius = 5;
                if($coordinates_center && $coordinates_center['lat'] != 0 && is_numeric($search_radius))
                {
                    $distance_unit = 'km';
                    if(__('km', 'wpdirectorykit') == 'm')
                    {
                        $distance_unit = 'm';
                    }
                    
                    // calculate rectangle
                    $rectangle_ne = wdk_getDueCoords($coordinates_center['lat'], $coordinates_center['lng'], 45, $search_radius, $distance_unit);
                    $rectangle_sw = wdk_getDueCoords($coordinates_center['lat'], $coordinates_center['lng'], 225, $search_radius, $distance_unit);
                    
                    $gps_ne = explode(', ', $rectangle_ne);
                    $gps_sw = explode(', ', $rectangle_sw);
                    
                    $gen_q .="(".$WMVC->db->prefix."wdk_listings.lat < '$gps_ne[0]' AND ".$WMVC->db->prefix."wdk_listings.lat > '$gps_sw[0]' AND 
                               ".$WMVC->db->prefix."wdk_listings.lng < '$gps_ne[1]' AND ".$WMVC->db->prefix."wdk_listings.lng > '$gps_sw[1]') OR ";
                    
                }
            }

            $gen_q = substr($gen_q, 0, -4);
            
            if(!empty($gen_q))
                $WMVC->db->where("($gen_q)");
        }

        // For column search
        if(isset($_GET_clone)) 
        {
            $gen_q = '';
            foreach($_GET_clone as $key=>$val)
            {
                if(!empty($val) && in_array($key, $columns))
                {
                    $col_name = $key;

                    $val = esc_sql($val);
                    //if(isset($key))
                    //    $col_name = $key;

                    if(strpos($key,  'skip') !== FALSE) continue;

                    if(function_exists('pll_current_language'))
                    {
                        $lang_term_id = pll_current_language('term_id');

                        if(is_numeric($lang_term_id))
                        {
                            $WMVC->db->join($WMVC->db->prefix.'term_relationships ON '.$WMVC->listing_m->_table_name.'.post_id = '.$WMVC->db->prefix.'term_relationships.object_id', NULL, NULL);
                            $WMVC->db->where("({$WMVC->db->prefix}term_relationships.term_taxonomy_id = $lang_term_id)");
                        }
                    }
                   
                    if($key == 'field_booking_date_from')
                    {
                        if(!$wdk_bookings_joinded){
                            $WMVC->db->join($WMVC->db->prefix.'wdk_booking_price ON '.$WMVC->listing_m->_table_name.'.post_id = '.$WMVC->db->prefix.'wdk_booking_price.post_id', NULL, 'LEFT');
                            $WMVC->db->distinct($WMVC->listing_m->_table_name.'.post_id');
                            $WMVC->db->join($WMVC->db->prefix.'wdk_booking_reservation ON '.$WMVC->listing_m->_table_name.'.post_id = '.$WMVC->db->prefix.'wdk_booking_price.post_id', NULL, 'LEFT');
                            $wdk_bookings_joinded = true;
                            $gen_q.= $WMVC->db->prefix.'wdk_booking_price.is_activated = 1 AND ';
                        }

                        if(wdk_is_date($val))
                        {
                            if(!get_option('wdk_bookings_is_hours_enabled')) {
                                $gen_search = wdk_normalize_date_db($val, 'Y-m-d H:i:s', 'Y-m-d');
                                $gen_q.= "DATE_FORMAT(".$WMVC->db->prefix."wdk_booking_price.date_from, '%Y-%m-%d') <= '".$gen_search."' AND ";
                            } else {
                                $gen_search = wdk_normalize_date_db($val);
                                $gen_q.= $WMVC->db->prefix."wdk_booking_price.date_from >'".$gen_search."' AND ";
                            }
                        }

                        if( isset($_GET_clone['field_booking_date_from']) && isset($_GET_clone['field_booking_date_to'])) {

                            if(!get_option('wdk_bookings_is_hours_enabled')) {
                                $gen_search_from = wdk_normalize_date_db($_GET_clone['field_booking_date_from'], 'Y-m-d H:i:s', 'Y-m-d');
                                $gen_search_to = wdk_normalize_date_db($_GET_clone['field_booking_date_to'], 'Y-m-d H:i:s', 'Y-m-d');
                         
                            $gen_q.= $WMVC->listing_m->_table_name.".post_id NOT IN ( 
                                                    SELECT post_id
                                                    FROM ".$WMVC->db->prefix."wdk_booking_reservation
                                                    WHERE 
                                                        ".$WMVC->db->prefix."wdk_booking_reservation.is_approved = 1 
                                                        AND 
                                                            DATE_FORMAT(".$WMVC->db->prefix."wdk_booking_reservation.date_from, '%Y-%m-%d') < '".$gen_search_to."' 
                                                        AND 
                                                            DATE_FORMAT(".$WMVC->db->prefix."wdk_booking_reservation.date_to, '%Y-%m-%d') > '".$gen_search_from."' 
                                        ) AND ";

                            } else {
                                $gen_search_from = wdk_normalize_date_db($_GET_clone['field_booking_date_from']);
                                $gen_search_to = wdk_normalize_date_db($_GET_clone['field_booking_date_to']);
                                
                            $gen_q.= $WMVC->listing_m->_table_name.".post_id NOT IN ( 
                                    SELECT post_id
                                    FROM ".$WMVC->db->prefix."wdk_booking_reservation
                                    WHERE 
                                        ".$WMVC->db->prefix."wdk_booking_reservation.is_approved = 1 
                                        AND 
                                            ".$WMVC->db->prefix."wdk_booking_reservation.date_from < '".$gen_search_to."' 
                                        AND 
                                            ".$WMVC->db->prefix."wdk_booking_reservation.date_to > '".$gen_search_from."' 
                                   ) AND ";
                            }
                        }
                        
                    }
                    elseif($key == 'field_booking_date_to')
                    {
                        if(!$wdk_bookings_joinded){
                            $WMVC->db->join($WMVC->db->prefix.'wdk_booking_price ON '.$WMVC->listing_m->_table_name.'.post_id = '.$WMVC->db->prefix.'wdk_booking_price.post_id',NULL, 'LEFT');
                            $WMVC->db->join($WMVC->db->prefix.'wdk_booking_reservation ON '.$WMVC->listing_m->_table_name.'.post_id = '.$WMVC->db->prefix.'wdk_booking_price.post_id',NULL, 'LEFT');
                            $wdk_bookings_joinded = true;
                            $gen_q.= $WMVC->db->prefix.'wdk_booking_price.is_activated = 1 AND ';
                        }
                      
                        if(wdk_is_date($val))
                        {
                            if(!get_option('wdk_bookings_is_hours_enabled')) {
                                $gen_search = wdk_normalize_date_db($val, 'Y-m-d H:i:s', 'Y-m-d');
                                $gen_q.= "DATE_FORMAT(".$WMVC->db->prefix."wdk_booking_price.date_to, '%Y-%m-%d') >= '".$gen_search."' AND ";
                            } else {
                                $gen_search = wdk_normalize_date_db($val);
                                $gen_q.= $WMVC->db->prefix."wdk_booking_price.date_to >'".$gen_search."' AND ";
                            }
                        }
                        
                    }
                    elseif(substr($key, -8) == '_exactly')
                    {
                        $col_name = substr($key, 0, -8);
                        $gen_q.=$col_name." = '".$val."' AND ";
                    }
                    elseif(substr($key, -4) == '_max')
                    {
                        $col_name = substr($key, 0, -4);
                        
                        if(strpos($key, 'NUMBER') !== FALSE) {
                            $gen_q.=$col_name." <= ".intval($val)." AND ";
                        } else if(strpos($key, 'DATE') !== FALSE) {
                            if(wdk_is_date($val))
                            {
                                $gen_search = wdk_normalize_date_db($val, 'Y-m-d H:i:s', 'Y-m-d');
                                $gen_q.=$col_name." >= ".$gen_search." AND ";
                            }
                        } else {
                            $gen_q.=$col_name." <= '".$val."' AND ";
                        }
                    }
                    else if(substr($key, -4) == '_min')
                    {
                        $col_name = substr($key, 0, -4);

                        if(strpos($key, 'NUMBER') !== FALSE) {
                            $gen_q.=$col_name." >= ".intval($val)." AND ";
                        } else if(strpos($key, 'DATE') !== FALSE) {
                            if(wdk_is_date($val))
                            {
                                $gen_search = wdk_normalize_date_db($val, 'Y-m-d H:i:s', 'Y-m-d');
                                $gen_q.=$col_name." >= ".$gen_search." AND ";
                            }
                        } else {
                            $gen_q.=$col_name." >= '".$val."' AND ";
                        }
                    }
                    elseif(substr_count($key, 'id') > 0 && is_numeric($val) && $key != 'agents_ids' && $key !='user_editor_ids' && $key !='location_id' && $key !='category_id')
                    {
                        // ID is always numeric
                        
                        if($key == 'location_id') {
                           /* $gen_q .= "(`location_table`.`parent_path` = ".$val." OR ";
                           $gen_q .= "`location_2`.`parent_path` = ".$val." OR ";*/
                            $gen_q .= "(`location_table`.`parent_id` = ".$val." OR ";
                            //$gen_q .= "`".$WMVC->db->prefix."wdk_locations `.`level_0_id` = ".$val." OR ";

                            $gen_q .= $col_name." = ".$val.") AND ";
                        } elseif($key == 'category_id') {
                            $gen_q .= "('parent_path' = ".$val." OR ";
                            $gen_q .= $col_name." = ".$val.") AND ";
                        } else {
                            $gen_q.=$col_name." = ".$val." AND ";
                        }
                    }
                    else if(substr_count($key, '_DATE') > 0)
                    {
                        // DATE VALUES
                        
                        $gen_search = $val;
                        if(wdk_is_date($val))
                        {
                            $gen_search = wdk_normalize_date_db($gen_search, 'Y-m-d H:i:s', 'Y-m-d');
                            $gen_q.=$col_name." LIKE '%".$gen_search."%' AND ";
                        }
                        else
                        {
                            $gen_q.=$col_name." LIKE '%".$gen_search."%' AND ";
                            
                        }
                    }
                    else if(substr_count($key, 'is_') > 0)
                    {
                        // CHECKBOXES
                        
                        if($val=='on')
                        {
                            $gen_search = 1;
                            $gen_q.=$col_name." LIKE '%".$gen_search."%' AND ";
                        }
                        else if($val=='off')
                        {
                            $gen_q.=$col_name." IS NULL AND ";
                        }
                    }
                    elseif($key == 'agents_ids')
                    {
                        if(is_string($val) && strpos($val, ',') !== FALSE){
                            $val =explode(',', $val);
                        } elseif(is_string($val)){
                            $val =array($val);
                        }
                        
                        if(is_array($val)) {

                            $WMVC->db->join($WMVC->db->prefix.'wdk_listings_users ON '.$WMVC->listing_m->_table_name.'.post_id = '.$WMVC->db->prefix.'wdk_listings_users.post_id', NULL, 'LEFT');
                            $WMVC->db->distinct($WMVC->listing_m->_table_name.'.post_id');
                            $sql_search = '';
                            foreach ($val as $v) {
                                if(empty($v)) continue;
                
                                if(!empty($sql_search))
                                    $sql_search .= " OR ";
                

                                $sql_search .="  ".$WMVC->listing_m->_table_name.".`user_id_editor` = ".intval($v)." OR `".$WMVC->db->prefix."wdk_listings_users`.`user_id` = ".intval($v)." ";
                            }

                            if(!empty($sql_search))
                                $gen_q.=" ( ".$sql_search." )  AND ";

                        } elseif(is_string($val) && !empty($val)) {
                            $WMVC->db->join($WMVC->db->prefix.'wdk_listings_users ON '.$WMVC->listing_m->_table_name.'.post_id = '.$WMVC->db->prefix.'wdk_listings_users.post_id', NULL, 'LEFT');
                            $WMVC->db->distinct($WMVC->listing_m->_table_name.'.post_id');
                            $sql_search ="  ".$WMVC->listing_m->_table_name.".`user_id_editor` = ".intval($val)." OR `".$WMVC->db->prefix."wdk_listings_users`.`user_id` = ".intval($val)." ";
                            $gen_q.=" ( ".$sql_search." )  AND ";
                        }
                    }
                    elseif($key == 'user_editor_ids')
                    {
                        
                        if(is_array($val)) {

                            $sql_search = '';
                            foreach ($val as $v) {
                                if(empty($v)) continue;
                
                                if(!empty($sql_search))
                                    $sql_search .= " OR ";
                

                                $sql_search .="  ".$WMVC->listing_m->_table_name.".`user_id_editor` = ".intval($v)." ";
                            }

                            if(!empty($sql_search))
                                $gen_q.=" ( ".$sql_search." )  AND ";

                        } elseif(is_string($val) && !empty($val)) {
                            $sql_search ="  ".$WMVC->listing_m->_table_name.".`user_id_editor` = ".intval($val)." ";
                            $gen_q.=" ( ".$sql_search." )  AND ";
                        }
                    }
                    elseif($key == 'location_id')
                    {

                        if(is_string($val) && strpos($val, ',') !== FALSE){
                            $val =explode(',', $val);
                        } elseif(is_string($val)){
                            $val =array($val);
                        }

                        if(is_array($val)) {

                            if(false){
                                $WMVC->db->join($WMVC->db->prefix.'wdk_listings_locations ON '.$WMVC->listing_m->_table_name.'.post_id = '.$WMVC->db->prefix.'wdk_listings_locations.post_id', NULL, 'LEFT');
                                $WMVC->db->distinct($WMVC->listing_m->_table_name.'.post_id');
                            }

                            $sql_search = '';
                            foreach ($val as $v) {
                                if(empty($v)) continue;
                
                                if(!empty($sql_search))
                                    $sql_search .= " OR ";
                
                                $sql_search .= "  ".$WMVC->listing_m->_table_name.".`location_id`=".intval($v)."";
                                $sql_search .= " OR `location_table`.`parent_id`=".intval($v)."";

                                /* other locations */
                                /* multi select */
                                if(false){
                                    $sql_search .= " OR `".$WMVC->db->prefix."wdk_listings`.`post_id` IN (SELECT `post_id` FROM `".$WMVC->db->prefix."wdk_listings_locations` WHERE `location_id` = ".intval($v).") ";
                                } elseif(false) {
                                    $sql_search .= " OR ".$WMVC->db->prefix."wdk_listings_locations.location_id=".intval($v)."";
                                } else {
                                    $sql_search .= " OR ".$WMVC->listing_m->_table_name.".locations_list LIKE '%,".intval($v).",%'";
                                }

                            }

                            if(!empty($sql_search))
                                $gen_q.=" ( ".$sql_search." )  AND ";
                        }
                    }
                    elseif($key == 'category_id')
                    {
                        
                        if(is_string($val) && strpos($val, ',') !== FALSE){
                            $val =explode(',', $val);
                        } elseif(is_string($val)){
                            $val =array($val);
                        }

                        
                        if(is_array($val)) {
                            if(false){
                                $WMVC->db->join($WMVC->db->prefix.'wdk_listings_categories ON '.$WMVC->listing_m->_table_name.'.post_id = '.$WMVC->db->prefix.'wdk_listings_categories.post_id', NULL, 'LEFT');
                            }
                            
                            $sql_search = '';
                            foreach ($val as $v) {
                                if(empty($v)) continue;
                
                                if(!empty($sql_search))
                                    $sql_search .= " OR ";
                
                                $sql_search .= " ".$WMVC->db->prefix."wdk_listings.`category_id` = ".intval($v)."";
                                $sql_search .= " OR `category_table`.`parent_id`=".intval($v)."";
                                
                                /* other categories */
                                if(false){
                                    $sql_search .= " OR `".$WMVC->db->prefix."wdk_listings`.`post_id` IN (SELECT `post_id` FROM `".$WMVC->db->prefix."wdk_listings_categories` WHERE `category_id` = ".intval($v).")";
                                } elseif(false) {
                                    $sql_search .= " OR ".$WMVC->db->prefix."wdk_listings_categories.category_id=".intval($v)."";
                                } else {
                                    $sql_search .= " OR ".$WMVC->listing_m->_table_name.".categories_list LIKE '%,".intval($v).",%'";
                                }
                        
                            }

                            if(!empty($sql_search))
                                $gen_q.=" ( ".$sql_search." )  AND ";
                        }
                    }
                    elseif(is_string($val))
                    {

                        if(strpos($val, ',') !== FALSE){
                            $val = explode(',', $val);
                        }

                        if(is_array($val)) {
                            $sql_search = '';
                            foreach ($val as $v) {
                                if(empty($v)) continue;
                
                                if(!empty($sql_search))
                                    $sql_search .= " OR ";
                
                                $sql_search .= $col_name." LIKE '%".$v."%'";
                                 
                            }

                            if(!empty($sql_search))
                                $gen_q.=" ( ".$sql_search." )  AND ";
                        } else {
                            $gen_q.=$col_name." LIKE '%".$val."%' AND ";
                        }
                       
                    }
                    elseif(is_array($val))
                    {
                        $sql_search = '';
                        foreach ($val as $v) {
                            if(empty($v)) continue;
            
                            if(!empty($sql_search))
                                $sql_search .= " OR ";
            
                            $sql_search .= " ".$col_name." LIKE '%".esc_sql($v)."%'  ";
                        }

                        if(!empty($sql_search))
                            $gen_q.=" ( ".$sql_search." )  AND ";
                    }
                }

            }
            
            $gen_q = substr($gen_q, 0, -5);
            
            if(!empty($gen_q))
                $WMVC->db->where("($gen_q)");
        }
        
        // order
        if(isset($_GET_clone['order_by']))
        {
            $_GET_clone['order_by'] = str_replace('post_id', $WMVC->db->prefix.'wdk_listings.post_id', $_GET_clone['order_by']);
            $WMVC->db->order_by($_GET_clone['order_by']);
        }

    }
}

function wdk_users_prepare_search_query_GET($columns = array(), $model_name = NULL, $external_columns = array(), $custom_parameters = array(), $skip_postget = FALSE)
{
    global $Winter_MVC_WDK;
    $WMVC = &$Winter_MVC_WDK;

   $_GET_clone = array();

    if(!$skip_postget)
        $_GET_clone = array_merge($_GET, $_POST);

    if(isset($custom_parameters['order_by'])) {

        if(isset($_GET_clone['order_by']) && !empty($_GET_clone['order_by'])) {
            $_GET_clone['order_by'] = $custom_parameters['order_by'].', '.$_GET_clone['order_by'];
        } else {
            $_GET_clone['order_by'] =  $custom_parameters['order_by'];
        }

        unset($custom_parameters['order_by']);
    }
        

    if(!empty($custom_parameters))
        $_GET_clone = array_merge($_GET_clone, $custom_parameters); 

    $WMVC->model($model_name);
    
    $smart_search = '';
    if(isset($_GET_clone['profile_search']))
        $smart_search = sanitize_text_field($_GET_clone['profile_search']);
        
    $available_fields = array('user_login','user_nicename','user_email','user_url','display_name');

    if(isset($_GET_clone['is_activated'])){
        $_GET_clone[$WMVC->$model_name->_table_name.'.is_activated'] = sanitize_text_field($_GET_clone['is_activated']);
        unset($_GET_clone['is_activated']);
    }

    if(isset($_GET_clone['is_approved'])){
        $_GET_clone[$WMVC->$model_name->_table_name.'.is_approved'] = sanitize_text_field($_GET_clone['is_approved']);
        unset($_GET_clone['is_approved']);
    }


    //$table_name = substr($model_name, 0, -2);  
    $columns_original = array();
    foreach($columns as $key=>$val)
    {
        $columns_original[$val] = $val;
        
        // if column contain also "table_name.*"
        $splited = explode('.', $val);
        if(wmvc_count($splited) == 2)
            $val = $splited[1];
        
        if(isset($available_fields[$val]))
        {
            
        }
        else
        {
            if(!in_array($columns[$key], $external_columns))
            {
                unset($columns[$key]);
            }
        }
    }

    if(wmvc_count($_GET_clone) > 0)
    {
        unset($_GET_clone['search']);
        
        // For quick/smart search
        if(wmvc_count($columns) > 0 && !empty($smart_search))
        {
            $gen_q = '';
            foreach($columns as $key=>$value)
            {

                if($value == 'post_type')
                {
                    $value = $WMVC->$model_name->_table_name.'.'.$value;
                }

                if(substr_count($value, 'id') > 0 && is_numeric($smart_search))
                {
                    $gen_q.="$value = $smart_search OR ";
                }
                else if(substr_count($value, 'date') > 0)
                {
                    //$gen_search = wmvc_generate_slug($smart_search, ' ');
                    
                    $gen_search = $smart_search;
                    
                    $gen_q.="$value LIKE '%$gen_search%' OR ";
                }
                else
                {
                    $gen_q.="$value LIKE '%$smart_search%' OR ";
                }
            }
            $gen_q = substr($gen_q, 0, -4);
            
            if(!empty($gen_q))
                $WMVC->db->where("($gen_q)");
        }

        // For column search
        if(isset($_GET_clone)) 
        {
            $gen_q = '';
            
            foreach($_GET_clone as $key=>$val)
            {
                if(!empty($val) && in_array($key, $columns))
                {
                    $col_name = $key;

                    if($col_name == 'post_type')
                    {
                        $col_name = $WMVC->$model_name->_table_name.'.'.$col_name;
                    }
                   
                    //if(isset($key))
                    //    $col_name = $key;

                    if(strpos($key,  'skip') !== FALSE) continue;
                
                    if(substr($key, -8) == '_exactly')
                    {
                        $col_name = substr($key, 0, -8);
                        $gen_q.=$col_name." = '".$val."' AND ";
                    }
                    elseif(substr($key, -4) == '_max')
                    {
                        $col_name = substr($key, 0, -4);
                        $gen_q.=$col_name." <= '".$val."' AND ";
                    }
                    else if(substr($key, -4) == '_min')
                    {
                        $col_name = substr($key, 0, -4);

                        $gen_q.=$col_name." >= '".$val."' AND ";
                    }
                    elseif(substr_count($key, 'id') > 0 && is_numeric($val))
                    {
                        // ID is always numeric
                        
                        $gen_q.=$col_name." = ".$val." AND ";
                    }
                    else if(substr_count($key, 'date') > 0)
                    {
                        // DATE VALUES
                        
                        $gen_search = $val;
                        
                        if(wdk_is_date($val))
                        {
                            $gen_search = wdk_normalize_date_db($gen_search);
                            $gen_q.=$col_name." > '".$gen_search."' AND ";
                        }
                        else
                        {
                            $gen_q.=$col_name." LIKE '%".$gen_search."%' AND ";
                        }
                    }
                    else if(substr_count($key, 'is_') > 0)
                    {
                        // CHECKBOXES
                        
                        if($val=='on')
                        {
                            $gen_search = 1;
                            $gen_q.=$col_name." LIKE '%".$gen_search."%' AND ";
                        }
                        else if($val=='off')
                        {
                            $gen_q.=$col_name." IS NULL AND ";
                        }
                    }
                    else
                    {
                        $gen_q.=$col_name." LIKE '%".$val."%' AND ";
                    }
                }

            }
            
            $gen_q = substr($gen_q, 0, -5);
            
            if(!empty($gen_q))
                $WMVC->db->where("($gen_q)");
        }
        
        // order
        if(isset($_GET_clone['order_by']))
        {
            $_GET_clone['order_by'] = str_replace('post_id', $WMVC->db->prefix.'wdk_favorite.post_id', $_GET_clone['order_by']);
            $WMVC->db->order_by($_GET_clone['order_by']);
        }

    }
}

function wdk_messages_prepare_search_query_GET($columns = array(), $model_name = NULL, $external_columns = array(), $custom_parameters = array(), $skip_postget = FALSE)
{
    global $Winter_MVC_WDK;
    $WMVC = &$Winter_MVC_WDK;

   $_GET_clone = array();

    if(!$skip_postget)
        $_GET_clone = array_merge($_GET, $_POST);

    if(isset($custom_parameters['order_by'])) {

        if(isset($_GET_clone['order_by']) && !empty($_GET_clone['order_by'])) {
            $_GET_clone['order_by'] = $custom_parameters['order_by'].', '.$_GET_clone['order_by'];
        } else {
            $_GET_clone['order_by'] =  $custom_parameters['order_by'];
        }

        unset($custom_parameters['order_by']);
    }
        

    if(!empty($custom_parameters))
        $_GET_clone = array_merge($_GET_clone, $custom_parameters); 

    $WMVC->model($model_name);
    
    $smart_search = '';
    if(isset($_GET_clone['profile_search']))
        $smart_search = sanitize_text_field($_GET_clone['profile_search']);
        
    $available_fields = array('user_login','user_nicename','user_email','user_url','display_name');

    if(isset($_GET_clone['is_activated'])){
        $_GET_clone[$WMVC->$model_name->_table_name.'.is_activated'] = sanitize_text_field($_GET_clone['is_activated']);
        unset($_GET_clone['is_activated']);
    }

    if(isset($_GET_clone['is_approved'])){
        $_GET_clone[$WMVC->$model_name->_table_name.'.is_approved'] = sanitize_text_field($_GET_clone['is_approved']);
        unset($_GET_clone['is_approved']);
    }


    //$table_name = substr($model_name, 0, -2);  
    $columns_original = array();
    foreach($columns as $key=>$val)
    {
        $columns_original[$val] = $val;
        
        // if column contain also "table_name.*"
        $splited = explode('.', $val);
        if(wmvc_count($splited) == 2)
            $val = $splited[1];
        
        if(isset($available_fields[$val]))
        {
            
        }
        else
        {
            if(!in_array($columns[$key], $external_columns))
            {
                unset($columns[$key]);
            }
        }
    }

    if(wmvc_count($_GET_clone) > 0)
    {
        unset($_GET_clone['search']);
        
        // For quick/smart search
        if(wmvc_count($columns) > 0 && !empty($smart_search))
        {
            $gen_q = '';
            foreach($columns as $key=>$value)
            {

                if($value == 'post_type')
                {
                    $value = $WMVC->$model_name->_table_name.'.'.$value;
                }

                if(substr_count($value, 'id') > 0 && is_numeric($smart_search))
                {
                    $gen_q.="$value = $smart_search OR ";
                }
                else if(substr_count($value, 'date') > 0)
                {
                    //$gen_search = wmvc_generate_slug($smart_search, ' ');
                    
                    $gen_search = $smart_search;
                    
                    $gen_q.="$value LIKE '%$gen_search%' OR ";
                }
                else
                {
                    $gen_q.="$value LIKE '%$smart_search%' OR ";
                }
            }
            $gen_q = substr($gen_q, 0, -4);
            
            if(!empty($gen_q))
                $WMVC->db->where("($gen_q)");
        }

        // For column search
        if(isset($_GET_clone)) 
        {
            $gen_q = '';
            
            foreach($_GET_clone as $key=>$val)
            {
                if(!empty($val) && in_array($key, $columns))
                {
                    $col_name = $key;

                    if($col_name == 'post_type')
                    {
                        $col_name = $WMVC->$model_name->_table_name.'.'.$col_name;
                    }
                   
                    //if(isset($key))
                    //    $col_name = $key;

                    if(strpos($key,  'skip') !== FALSE) continue;
                
                    if(substr($key, -8) == '_exactly')
                    {
                        $col_name = substr($key, 0, -8);
                        $gen_q.=$col_name." = '".$val."' AND ";
                    }
                    elseif(substr($key, -4) == '_max')
                    {
                        $col_name = substr($key, 0, -4);
                        $gen_q.=$col_name." <= '".$val."' AND ";
                    }
                    else if(substr($key, -4) == '_min')
                    {
                        $col_name = substr($key, 0, -4);

                        $gen_q.=$col_name." >= '".$val."' AND ";
                    }
                    elseif(substr_count($key, 'id') > 0 && is_numeric($val))
                    {
                        // ID is always numeric
                        
                        $gen_q.=$col_name." = ".$val." AND ";
                    }
                    else if(substr_count($key, 'date') > 0)
                    {
                        // DATE VALUES
                        
                        $gen_search = $val;
                        

                        if(wdk_is_date($val))
                        {
                            $gen_search = wdk_normalize_date_db($gen_search);
                            $gen_q.=$col_name." > '".$gen_search."' AND ";
                        }
                        else
                        {
                            $gen_q.=$col_name." LIKE '%".$gen_search."%' AND ";
                        }
                    }
                    else if(substr_count($key, 'is_') > 0)
                    {
                        // CHECKBOXES
                        
                        if($val=='on')
                        {
                            $gen_search = 1;
                            $gen_q.=$col_name." LIKE '%".$gen_search."%' AND ";
                        }
                        else if($val=='off')
                        {
                            $gen_q.=$col_name." IS NULL AND ";
                        }
                    }
                    else
                    {
                        $gen_q.=$col_name." LIKE '%".$val."%' AND ";
                    }
                }

            }
            
            $gen_q = substr($gen_q, 0, -5);
            
            if(!empty($gen_q))
                $WMVC->db->where("($gen_q)");
        }
        
        // order
        if(isset($_GET_clone['order_by']))
        {
            $_GET_clone['order_by'] = str_replace('post_id', $WMVC->db->prefix.'wdk_favorite.post_id', $_GET_clone['order_by']);
            $WMVC->db->order_by($_GET_clone['order_by']);
        }

    }
}

function wdk_date() {
	$date_format = get_option('date_format');
	$time_format = get_option('time_format');
	$date = date("{$date_format} {$time_format}", current_time('timestamp'));
	return $date;
}

if(!function_exists('wdk_placeholder_image_src')) {
    function wdk_placeholder_image_src () {
        $i = WPDIRECTORYKIT_URL.'public/img/placeholder.jpg';

        if(wdk_get_option('wdk_placeholder')) {
            $image = wp_get_attachment_image_src(wdk_get_option('wdk_placeholder'), 'full'  );
            if(!empty($image) && file_exists(str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $image[0])))
                $i = $image[0];
        }

        return $i;
    }
}


function wdk_wp_frontend_paginate($total_items, $per_page = 10, $page_var = 'wmvc_paged', $texts = array(), $enable_latest_first = TRUE, $enable_count = FALSE, $enable_compact = FALSE, $limit_items = 1)
{
    $current_page = 1;

    if(isset($_GET[$page_var]))
        $current_page = intval(wmvc_xss_clean($_GET[$page_var]));

    if(!isset($texts['previous_page']))$texts['previous_page'] = 'Previous page';
    if(!isset($texts['next_page']))$texts['next_page'] = 'Next page';
    if(!isset($texts['first_page']))$texts['first_page'] = '';
    if(!isset($texts['last_page']))$texts['last_page'] = '';
    if(!isset($texts['items']))$texts['items'] = 'items';

    if(empty($current_page))$current_page = 1;

    // get url
    $url = strtok($_SERVER["REQUEST_URI"], '?');
    $qs_parameters = wmvc_xss_clean( $_GET );
    unset($qs_parameters[$page_var]);
    $qs_part = http_build_query($qs_parameters);
    $url = wdk_url_suffix($url, $qs_part);

    // total pages
    $total_pages = intval($total_items/$per_page+0.99);

    if($current_page == 1) {
        $limit_items = $limit_items * 2;
    }

    if($current_page == $total_pages) {
        $limit_items = $limit_items * 2;
    }

    $output = '<nav class="wdk-pagination navigation pagination" role="navigation">';
    if($enable_count){
        $output.= '<span class="displaying-num">'.$total_items.' '.$texts['items'].'</span>';
    }
   
    $output .= '<div class="nav-links">';
        
        if($enable_latest_first && $current_page-1 > 0)
            $output.= '<a class="next page-numbers" href="'.esc_url($url).'#results"><span class="screen-reader-text">'.esc_html($texts['first_page']).'</span><span aria-hidden="true">«</span></a>';
        
        if ($current_page-1 > 0) {
            
            $output.= '<a class="next page-numbers" href="'.esc_url(wdk_url_suffix($url,esc_attr($page_var).'='.esc_attr($current_page-1))).'#results"><span class="screen-reader-text">'.esc_html($texts['previous_page']).'</span><span aria-hidden="true">‹</span></a>';
        } elseif($enable_compact)
        {
            $output.= '<span class="next page-numbers disabled" href=""><span class="screen-reader-text">'.esc_html($texts['previous_page']).'</span><span aria-hidden="true">‹</span></span>';
        }
        
        if (!$enable_compact) {
            for ($i = 1 ; $i <= $total_pages; $i++) {
                $class = ($current_page == $i) ? "active" : "";
                if(($current_page-$limit_items) <= $i && ($current_page+$limit_items) >= $i) {
                    if (($current_page == $i)) {
                        $output.= '<span aria-current="page" class="page-numbers current">'.$i.'</span>';
                    } else {
                        $output.= '<a class="page-numbers" href="'.esc_url(wdk_url_suffix($url, esc_attr($page_var).'='.esc_attr($i))).'#results">'.esc_attr($i).'</a>';
                    }
                }
            }
        } else {
            $output.= '<span class="wdk_pages_range">'.$current_page.' '.esc_html__('of','wpdirectorykit').' '.$total_pages.'</span>';
        }

        if($current_page+1 <= $total_pages)
        {
            $output.= '<a class="next page-numbers" href="'.esc_url(wdk_url_suffix($url, esc_attr($page_var).'='.esc_attr($current_page+1))).'#results"><span class="screen-reader-text">'.esc_html($texts['next_page']).'</span><span aria-hidden="true">›</span></a>';
        }
        elseif($enable_compact)
        {
            $output.= '<a class="next page-numbers disabled" href=""><span class="screen-reader-text">'.esc_html($texts['next_page']).'</span><span aria-hidden="true">›</span></a>';
        }
        
        if($enable_latest_first && $current_page+1 <= $total_pages)
            $output.= '<a class="next page-numbers" href="'.esc_url(wdk_url_suffix($url, esc_attr($page_var).'='.esc_attr($total_pages))).'#results"><span class="screen-reader-text">'.esc_html($texts['last_page']).'</span><span aria-hidden="true">»</span></a>';
        
        
    $output.= '</div>';
    $output.= '</nav>';

    return $output;
}

function wdk_viewe($content) {
    // @codingStandardsIgnoreStart
        echo wp_kses_post($content); // WPCS: XSS ok, sanitization ok.
    // @codingStandardsIgnoreEnd
}


if(!function_exists('wdk_search_fields_toggle')){
    function wdk_search_fields_toggle ($class_add = NULL){
        static $wdk_visible_filters = 0;
        global $wdk_visible_filters_limit;
        global $wdk_button_search_defined;
        global $wdk_enable_search_fields_toggle;
        global $wdk_activate_more;

        if(!$wdk_enable_search_fields_toggle) return false;
        
        global $wdk_search_fields_toggle_reset;
        if($wdk_search_fields_toggle_reset) {
            $wdk_visible_filters = 0;
            $wdk_search_fields_toggle_reset = false;
        }

        $wdk_visible_filters++;

        if($wdk_activate_more || (!empty($wdk_visible_filters_limit) && !$wdk_button_search_defined && $wdk_visible_filters == $wdk_visible_filters_limit)){
            $wdk_button_search_defined=true;
            $wdk_activate_more = false;

            global $wdk_text_search_button;
            if(empty($wdk_text_search_button))
                $wdk_text_search_button = esc_html__('Search','wpdirectorykit');

            global $wdk_text_more_button;
            if(empty($wdk_text_more_button))
                $wdk_text_more_button = esc_html__('More','wpdirectorykit');
                    
            $form_closed = 'display: none;';
            if(isset($_GET['wdk_search_additional_opened']) && wmvc_xss_clean($_GET['wdk_search_additional_opened']) == 1) {
                $form_closed = '';
            }

            ?>
            <div class="wdk-col wdk-col-btns">
                <div class="wdk-field wdk-field-btn">
                    <div class="wdk-field-group">
                        <button id="wdk-search-additional" type="button" class="wdk-search-additional-btn"><?php echo esc_html($wdk_text_more_button); ?><i class="wdk-toggle-icon"></i></button>
                        <input type='checkbox' style="display: none !important" value='1' name='wdk_search_additional_opened' />   
                    </div>
                    <div class="wdk-field-group">
                        <button id="wdk-start-primary" type="submit" class="wdk-search-start wdk-click-load-animation">&nbsp;&nbsp;<?php echo esc_html($wdk_text_search_button);?>&nbsp;<i class="fa fa-spinner fa-spin fa-ajax-indicator" style="display: none;"></i>&nbsp;</button>
                        <?php if(function_exists('run_wdk_save_search') && get_option('wdk_save_search_show_on_searchform')):?>
                        <div class="section-widget-control right">
                            <a class="wdk-c-btn wdk-c-edit wdk-save-search-button" href="#" data-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" title="<?php echo esc_attr_e('Save Search', 'selio'); ?>" target="_blank">
                                <i class="fas fa-save" aria-hidden="true"></i>
                                <i class="fa fa-spinner fa-spin fa-ajax-indicator"></i>
                            </a>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <div id='wdk-form-additional' class="wdk-col" style="<?php echo esc_attr($form_closed) ;?>">
                <div class="wdk-row">
            <?php
        }
    }
}


/*
 * @param $filter_ids string|array, included ids, what will be visible, other skiped, if set 1_fetch_child, where 1 is category_id, will be detected all childs and filtered, for visible
 * 
 */
if ( ! function_exists('wdk_treefield_option'))
{
	function wdk_treefield_option($name = '', $table=NULL, $selected = NULL, 
                            $column = 'category_title', $language_id=NULL, $empty_value='', $filter_ids = '', $user_check = FALSE)
	{
        $WMVC = &wdk_get_instance();

        if(is_array($filter_ids)) $filter_ids = implode(',', $filter_ids);
        
	    static $counter = 0;
        
        $model_name = $table;
        if($table == 'calendar_listing_m')
            $model_name = 'listing_m';

        $table_name = str_replace('_m', '', $table);
        
        $attribute_id = 'id'.$table_name;
        
        if($table_name == 'icons_list') {

        } else {
            $WMVC->model($model_name);
            $attribute_id = $WMVC ->$model_name->_primary_key;
            $selected = (int) $selected;
        }
        
        if(empty($selected))
            $selected='';

		$form = '<input name="'.$name.'" value="'.$selected.'" class="wdk-hidden" type="text" id="wdktreeelem'.$counter.'" readonly/>';
        
        $skip_id = '';
        //load javascript library
        if($counter==0)
        {
            wp_enqueue_script('wdk-treefield');
            wp_enqueue_style('wdk-treefield');
        }
        ?>
        <script>
            jQuery(document).ready(function($) {
                $('#wdktreeelem<?php echo esc_js($counter);?>:not(.init)').wdkTreefield({
                    ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    ajax_param: { 
                                "page": 'wdk_frontendajax',
                                "function": 'treefieldid',
                                "action": 'wdk_public_action',
                                "table": '<?php echo esc_js($table); ?>',
                                "filter_ids": '<?php echo esc_js($filter_ids); ?>',
                                "start_id": '<?php if(!empty($filter_ids)) esc_js($selected); else echo ""; ?>',
                                "empty_value": '<?php echo esc_js($empty_value); ?>',
                                "user_check": '<?php echo esc_js($user_check); ?>'
                                },
                    attribute_id: '<?php echo esc_js($attribute_id); ?>',
                    language_id: '<?php echo esc_js($language_id); ?>',
                    attribute_value: '<?php echo esc_js($column); ?>',
                    skip_id: '<?php echo esc_js($skip_id); ?>',
                    empty_value: ' - ',
                    text_search: '<?php esc_html_e('Search term', 'wpdirectorykit');?>',
                    text_no_results: '<?php esc_html_e('No results found', 'wpdirectorykit');?>',
                    callback_selected: function(key) {
                        $('#wdktreeelem<?php echo esc_js($counter);?>').trigger("change");
                    }
                }).addClass('init');
            });
        </script>
        <?php
        $counter++;
		return $form;
	}
}

/*
 * @param $filter_ids string|array, included ids, what will be visible, other skiped, if set 1_fetch_child, where 1 is category_id, will be detected all childs and filtered, for visible
 * 
 */
if ( ! function_exists('wdk_treefield_option_checkboxes'))
{
	function wdk_treefield_option_checkboxes($name = '', $table=NULL, $selected = NULL, 
                            $column = 'category_title', $language_id=NULL, $empty_value='', $filter_ids = '', $user_check = FALSE)
	{
        $WMVC = &wdk_get_instance();

        if(is_array($filter_ids)) $filter_ids = implode(',', $filter_ids);
        
	    static $counter = 0;
        
        $model_name = $table;

        $table_name = str_replace('_m', '', $table);
        
        $attribute_id = 'id'.$table_name;
        
      
        $WMVC->model($model_name);
        $attribute_id = $WMVC ->$model_name->_primary_key;
        
        $values = '';
        if(!empty($selected)){
            $ids = array();

            if(is_string($selected) && strpos($selected, ',') !== FALSE){
                $val_selected = explode(',', $selected);
            } elseif(is_string($selected)){
                $val_selected = array($selected);
            }

            foreach($val_selected as $selected_item) {
                if(!empty($selected_item) && is_intval($selected_item)) {
                    $ids [] = $selected_item;
                }
            }

            /* where in */
            if(!empty($ids)){
                $WMVC->db->select($WMVC->$table->_table_name.'.*');
                $WMVC->db->where($WMVC->$table->_table_name.'.'.$WMVC->$table->_primary_key.' IN(' . implode(',', $ids) . ')', null, false);
                $WMVC->db->order_by('FIELD('.$WMVC->$table->_table_name.'.'.$WMVC->$table->_primary_key.', '. implode(',', $ids) . ')');
                
                $results = $WMVC->$table->get();
                foreach ($results as $item) {
                    if($item) {
                        $values .= wmvc_show_data($column, $item, false, TRUE, TRUE).',';
                    }
                }
                $values = substr($values,0,-1);

                if(strlen($values)>23) {
                    $values = substr($values,0,20).'...';
                }
            }
        }

        if(empty($values)) {
            $values = $empty_value;
        }
        
		$form = '<input name="'.$name.'" value="'.esc_html($selected).'" data-placehoder="'.esc_html($values).'" class="wdk-hidden" type="text" id="wdktreeelem_checkbox'.$counter.'" readonly/>';
        
        $skip_id = '';
        //load javascript library
        if($counter==0)
        {
            wp_enqueue_script('wdk-treefield');
            wp_enqueue_style('wdk-treefield');
        }
        ?>
        <script>
            jQuery(document).ready(function($) {
                $('#wdktreeelem_checkbox<?php echo esc_js($counter);?>:not(.init)').wdkTreefieldCheckboxes({
                    ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    ajax_param: { 
                                "page": 'wdk_frontendajax',
                                "function": 'treefieldid_checkboxes',
                                "action": 'wdk_public_action',
                                "table": '<?php echo esc_js($table); ?>',
                                "filter_ids": '<?php echo esc_js($filter_ids); ?>',
                                "empty_value": '<?php echo esc_js($empty_value); ?>',
                                "user_check": '<?php echo esc_js($user_check); ?>',
                            },
                    selected: '<?php echo esc_js($selected); ?>',
                    attribute_id: '<?php echo esc_js($attribute_id); ?>',
                    language_id: '<?php echo esc_js($language_id); ?>',
                    attribute_value: '<?php echo esc_js($column); ?>',
                    skip_id: '<?php echo esc_js($skip_id); ?>',
                    empty_value: ' - ',
                    text_search: '<?php esc_html_e('Search term', 'wpdirectorykit');?>',
                    text_no_results: '<?php esc_html_e('No results found', 'wpdirectorykit');?>',
                    callback_selected: function(key) {
                        $('#wdktreeelem_checkbox<?php echo esc_js($counter);?>').trigger("change");
                    }
                }).addClass('init');
            });
        </script>
        <?php
        $counter++;
		return $form;
	}
}

if ( ! function_exists('wdk_filter_decimal'))
{
	function wdk_filter_decimal($string = '')
	{
        if(substr($string, -3, 3) === ".00") {
            return substr($string, 0, -3);
        }

        return $string;
	}
}

if ( ! function_exists('wdk_is_date'))
{
	function wdk_is_date($date, $format = 'Y-m-d H:i:s')
	{

        /* check is date on strtotime */
        if((bool)strtotime($date)){
            return true;
        }

        /* check is date from argument */
        $d = \DateTime::createFromFormat($format, $date);
        if($d) {
            return true;
        }

        /* check is date from wp date and time */
        $d = \DateTime::createFromFormat(get_option('date_format').' '.get_option('time_format'), $date);
        if($d) {
            return true;
        }

        /* check is date from wp date */
        $d = \DateTime::createFromFormat(get_option('date_format'), $date);
        if($d) {
            return true;
        }

        /* try with default */
        if('Y-m-d H:i:s' != $format) {
            $d = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
            if($d) {
                return true;
            }

            $d = \DateTime::createFromFormat('Y-m-d', $date);
            if($d) {
                return true;
            }
        }

        return false;
	}
}


if ( ! function_exists('wdk_normalize_date_db'))
{
    /**
	 * Convert date to db format, from wp format
	 * @param  string  $date    Date in string
	 * @param  string  $format    Special format
	 * @param  string  $format    Special format
	 * @param  string  $return_format    return date format
	 * @return string date in format Y-m-d H:i:s
	*/
	function wdk_normalize_date_db($date, $format = 'Y-m-d H:i:s', $return_format = 'Y-m-d H:i:s')
	{
        /* check is date on strtotime */
        if((bool)strtotime($date)){
            return date($return_format, strtotime($date));
        }

        /* check is date from argument */
        $d = \DateTime::createFromFormat($format, $date);
        if($d) {
            return $d->format($return_format);
        }

        /* check is date from wp date and time */
        $d = \DateTime::createFromFormat(get_option('date_format').' '.get_option('time_format'), $date);
        if($d) {
            return $d->format($return_format);
        }

        /* check is date from wp date */
        $d = \DateTime::createFromFormat(get_option('date_format'), $date);
        if($d) {
            return $d->format($return_format);
        }

        /* try with default */
        if('Y-m-d H:i:s' != $format) {
            $d = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
            if($d) {
                return $d->format($return_format);
            }

            $d = \DateTime::createFromFormat('Y-m-d', $date);
            if($d) {
                return $d->format($return_format);
            }
        }

        return false;
	}
}

if ( ! function_exists('wdk_url_suffix'))
{
	function wdk_url_suffix($base_url, $extension_url="")
	{   
        if(substr($base_url, -1) == '?'){
            $base_url .='';
        } elseif(strpos($base_url,'?') !== FALSE){
            $base_url .='&';
        } else {
            $base_url .='?';
        }
        return  $base_url.$extension_url;
	}
}

/**
 * Insert an attachment from an URL address.
 *
 * @param  String $url
 * @param  Int    $parent_post_id
 * @return Int    Attachment ID
 */
function wdk_insert_attachment_from_url($url, $parent_post_id = null) {

	if( !class_exists( 'WP_Http' ) )
		include_once( ABSPATH . WPINC . '/class-http.php' );

	$http = new WP_Http();
	$response = $http->request( $url );
	if( is_wp_error($response) || $response['response']['code'] != 200 ) {
		return false;
	}

	$upload = wp_upload_bits( basename($url), null, $response['body'] );
	if( !empty( $upload['error'] ) ) {
		return false;
	}

	$file_path = $upload['file'];
	$file_name = basename( $file_path );
	$file_type = wp_check_filetype( $file_name, null );
	$attachment_title = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );
	$wp_upload_dir = wp_upload_dir();

	$post_info = array(
		'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
		'post_mime_type' => $file_type['type'],
		'post_title'     => $attachment_title,
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	// Create the attachment
	$attach_id = wp_insert_attachment( $post_info, $file_path, $parent_post_id );

	// Include image.php
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	// Define attachment metadata
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );

	// Assign metadata to attachment
	wp_update_attachment_metadata( $attach_id,  $attach_data );

	return $attach_id;

}

if(!function_exists('wdk_jsdateformat')) {
    function wdk_jsdateformat($format = '') {
        $date_specific = array(
                // Day
                'd' => 'dd',
                'D' => 'D',
                'j' => 'd',
                'l' => 'DD',
                'N' => '',
                'S' => '',
                'w' => '',
                'z' => 'o',
                // Week
                'W' => '',
                // Month
                'F' => 'MM',
                'm' => 'mm',
                'M' => 'M',
                'n' => 'm',
                't' => '',
                // Year
                'L' => '',
                'o' => '',
                'y' => 'y',
                'Y' => 'yy',
                // Time
                'a' => '',
                'A' => '',
                'B' => '',
                'g' => '',
                'G' => '',
                'h' => '',
                'H' => '',
                'i' => '',
                's' => '',
                'u' => ''
        );
        return str_replace(array_keys($date_specific), array_values($date_specific), $format);
    }

}

function wdk_current_url()
{
    global $wp;
    return add_query_arg( $wp->query_vars, home_url( $wp->request ) );
}

if(!function_exists('wdk_generate_profile_permalink')) {
    /**
     * Insert user id and return link on user profile page
     *
     * @return Int    user ID
     */

    function wdk_generate_profile_permalink($profile = array())
    {
      
        $profile_slug = '';
        if(wmvc_show_data('user_login', $profile, false)){
            $profile_slug =  wmvc_show_data('user_login', $profile);
        } else if(is_intval($profile)){
            $profile_data = get_userdata($profile);
            if(wmvc_show_data('user_login', $profile_data, false)){
                $profile_slug = wmvc_show_data('user_login', $profile_data);
            } else {
                $profile_slug = $profile;
            }
        }

        $user_profile_page = get_option('wdk_membership_profile_preview_page');
        if(!$user_profile_page) {
            return '#';
        }
        // for polylang to detect translated page version
        if(function_exists('pll_get_post'))
            $user_profile_page = pll_get_post($user_profile_page);

        return wdk_url_suffix(get_permalink($user_profile_page), 'slug='. $profile_slug);
    }
}

if(!function_exists('wdk_get_profile_page_id')) {
    /**
     * Insert user id and return link on user profile page
     *
     * @return Int    user ID
     */

    function wdk_get_profile_page_id()
    {
        global $wp_query;
        if(get_option('wdk_membership_profile_preview_page') && isset($wp_query->post) && get_option('wdk_membership_profile_preview_page') == $wp_query->post->ID) {
            if (wmvc_show_data('slug', $_GET, false)) {
                if(is_intval(wmvc_show_data('slug', $_GET))) {
                    return wmvc_show_data('slug', $_GET);
                } else {
                    $profile = get_user_by('login',wmvc_show_data('slug', $_GET));
                    return (wmvc_show_data('ID', $profile, false)) ? wmvc_show_data('ID', $profile, false) : false;
                }
            }
        }

        return false;
    }
}

if(!function_exists('wdk_get_date')) {
    /**
     * Return date in format
     *
     */
    function wdk_get_date($datetime = NULL, $time = TRUE, $default='timestamp') 
    {
        $init_datetime = $datetime;
        if(is_null($datetime))
        {
            if($default == 'timestamp')
            {
                $datetime = current_time('timestamp');
            }
            else
            {
                return $default;
            }
        }
        else if(!is_numeric($datetime))
        {
            $datetime = strtotime($datetime);
        }
        
        $date_format = get_option('date_format');

        $time_format = ($time && strpos($init_datetime, '00:00:00')===FALSE) ? get_option('time_format') : '';
        
        $date = date_i18n("{$date_format} {$time_format}", $datetime);
        return $date;
    }
}

if(!function_exists('wdk_login_url')) {
    /**
     * Return date in format
     * @param string|url redirect url
     * @param string custom_message, showed on login form like alert, visible only on wdk login form
     */
    function wdk_login_url($url_redirect = NULL, $custom_message = '') 
    {
        $url = wp_login_url($url_redirect);

        if(get_option('wdk_membership_login_page')){
            $url = wdk_url_suffix(get_permalink(get_option('wdk_membership_login_page')), 'redirect_to='. urlencode($url_redirect).'&custom_message='. urlencode($custom_message));
        } 

        return $url;
    }
}

if(!function_exists('wdk_mail')) {
    /**
     * Return date in format
     * @param string|url redirect url
     */
    function wdk_mail( $email_to, $subject = '', $data = array(), $layout = 'default', $email_from = '', $attachments = array(), $reply_to = '')
    {
        $WMVC = &wdk_get_instance();

        $ret = false;
        if(empty($email_from))
            $email_from = get_bloginfo('admin_email');
            
        if(empty($subject))
            $subject = __("New message", "wpdirectorykit");

        $headers = array('Content-Type: text/html; charset=UTF-8');
        $headers[] = 'From: '.$email_from;

        if(!empty($reply_to)) {
            $headers[] = 'Reply-To: '.$reply_to;
        } else {
            $headers[] = 'Reply-To: '.$email_from;
        }

        $data['subject'] = $subject;

        $message = $WMVC->view('email/'.$layout, $data, FALSE);
        $ret = wp_mail( $email_to, $subject, $message, $headers, $attachments );

        return $ret;
    }
}

if(!function_exists('wdk_show_data')) {
    function wdk_show_data($field_name, &$db_value = NULL, $default = '', $xss_clean = TRUE, $skip_post = FALSE)
    {
        if(!$skip_post && isset($_POST[$field_name]))
        {
            if($xss_clean === FALSE)
                return stripslashes($_POST[$field_name]);

            return wmvc_xss_clean(stripslashes($_POST[$field_name]));
        }
            

        if(is_array($db_value))
        {
            if(isset($db_value[$field_name]))
            {
                if($xss_clean === FALSE)
                    return $db_value[$field_name];

                return wmvc_xss_clean($db_value[$field_name]);
            }
            else
            {
                return $default;
            }
        }

        if(is_object($db_value))
        {
            if(isset($db_value->$field_name))
            {
                if($xss_clean === FALSE)
                    return $db_value->$field_name;

                return wmvc_xss_clean($db_value->$field_name);
            }
            else
            {
                return $default;
            }
        }

        if(!empty($db_value))
        {
            if($xss_clean === FALSE)
                return $db_value;

            return wmvc_xss_clean($db_value);  
        }
            
        if($xss_clean === FALSE)
            return $default;

        return wmvc_xss_clean($default);
    }
}


if (!function_exists('wdk_input_checked'))
{
    function wdk_input_checked($field_id, $db_data, $value = 1)
    {
  
        if(wmvc_show_data($field_id, $db_data, false) && wmvc_show_data($field_id, $db_data) == $value)
        {
            return 'checked';
        }
        
        return '';
    }
}

function wdk_upload_file($field_name, $file_id)
{
    static $media_element_counter = 0;
        
    $media_element_counter++;
    
    $img_field = $field_name.'_'.$media_element_counter;
    
    wp_register_script( 'wpmediaelement_file', WPDIRECTORYKIT_URL . 'admin/js/jquery.wpmediaelement_file.js', array( 'jquery' ), false, false );
    wp_enqueue_script(  'wpmediaelement_file' );
    wp_enqueue_media();

    ?>
    <div id="<?php echo esc_attr($field_name); ?>meta-box-id" class="postbox-upload">
    <?php
    // Get WordPress' media upload URL
    $upload_link = '#';
    
    // Get the file src
        
    // Get the file src
    $your_file_src = get_attached_file(intval( $file_id));

    // For convenience, see if the array is valid
    $you_have_file = false;
    if($your_file_src)
        $you_have_file = basename($your_file_src);

    ?>
    
    <!-- Your file container, which can be manipulated with js -->
    <div class="custom-img-container">
        <?php if ( $you_have_file ) : ?>
            <?php echo esc_html($you_have_file); ?>
        <?php endif; ?>
    </div>
    
    <?php //if(sw_user_in_role('administrator')):  ?>
    <!-- Your add & remove file links -->
    <p class="hide-if-no-js">
        <a class="upload-custom-img <?php if ( $you_have_file  ) { echo 'hidden'; } ?>" 
        href="<?php echo esc_url($upload_link) ?>">
            <?php echo esc_html__('Select file','wmvc_win') ?>
        </a>
        <a class="delete-custom-img <?php if ( ! $you_have_file  ) { echo 'hidden'; } ?>" 
        href="#">
            <?php echo esc_html__('Remove file','wmvc_win') ?>
        </a>
    </p>
    <?php //endif; ?>
    
    <!-- A hidden input to set and post the chosen file id -->
    <input class="logo_file_id" type="hidden" id="<?php echo esc_html($field_name); ?>" name="<?php echo esc_html($field_name); ?>" value="<?php echo esc_html($file_id); ?>" />
    </div>
    
    <?php
    $custom_js ='';
    $custom_js .=" jQuery(function($) {
                        if( typeof jQuery.fn.wpMediaElementFile == 'function')
                            $('#".esc_js($field_name)."meta-box-id.postbox-upload').wpMediaElementFile();
                    });";
    
    echo "<script>".$custom_js."</script>";

    ?>

    <?php
}

if ( ! function_exists('wdk_file_extension'))
{
    function wdk_file_extension($filepath)
    {
        return substr($filepath, strrpos($filepath, '.')+1);
    }
}

if ( ! function_exists('wdk_file_extension_type'))
{
    /**
	 * Get image type based on extension
	 *
	 * @param      string    $filepath string path(url) to file with extension
	 * @return     string    type of file (image,video,docs)
	 */
    function wdk_file_extension_type($filepath = NULL)
    {
        if(in_array(wdk_file_extension($filepath),array('jpg','jpeg','bmp','png','webp'))) {
            return 'image';
        }
        if(in_array(wdk_file_extension($filepath),array('mp4','mov','flv','mkv','avi','webm'))) {
            return 'video';
        }

        return 'docs';
    }
}

function wdk_upload_multi_files($field_name, $image_ids='', $texts = array())
{
    static $media_element_counter = 0;

    if(!isset($texts['file_select']))$texts['file_select'] = esc_html__('Select file','wmvc_win');
    if(!isset($texts['file_remove']))$texts['file_remove'] = esc_html__('Remove file','wmvc_win');

    $media_element_counter++;
    
    $img_field = $field_name.'_'.$media_element_counter;
    
    wp_register_script( 'wpmediaelement_file', WPDIRECTORYKIT_URL . 'admin/js/jquery.wpmediaelement_file.js', array( 'jquery' ), false, false );
    wp_enqueue_script(  'wpmediaelement_file' );
    wp_enqueue_media();
    
    wp_enqueue_script(  'wpmediamultiple' );
    wp_enqueue_script(  'jquery-ui-mouse' );
    wp_enqueue_media();
    
        ?>
        <div id="<?php echo esc_attr($field_name); ?>meta-box-id" class="postbox-upload-multiple">
        <?php
        // Get WordPress' media upload URL
        $upload_link = '#';
        
        
        // Get the image src
    
        $your_img_src = array();
    
        foreach(explode(',', $image_ids) as $image_id)
        {
            if(is_numeric($image_id)){
                $src = wp_get_attachment_url( $image_id, 'full' );
                if(!in_array(wdk_file_extension($src),array('jpg','jpeg','bmp','png','webp'))) {
                    if(file_exists(WPDIRECTORYKIT_PATH.'/public/img/filetype/'.wdk_file_extension($src).'.png')) {
                        $src = WPDIRECTORYKIT_URL.'public/img/filetype/'.wdk_file_extension($src).'.png';
                    } else {
                        $src = WPDIRECTORYKIT_URL.'public/img/filetype/_blank.png';
                    }
                }
                $your_img_src[$image_id] = $src;
            }
        }
    
        // For convenience, see if the array is valid
        $you_have_img = count($your_img_src) > 0;
        ?>
    
        <!-- Your image container, which can be manipulated with js -->
        <div class="custom-img-container winter_mvc-media">
            <?php if($you_have_img)foreach($your_img_src as $image_id => $img_src) : ?>
                <div class="winter_mvc-media-card" data-media-id="<?php echo esc_attr($image_id);?>">
                    <img src="<?php echo esc_html($img_src); ?>" style="object-fit: contain;" alt="<?php echo esc_attr__('thumb', 'wmvc_win');?>" style="max-width:100%;" class="thumbnail"/>
                    <a href="#" class="remove"></a>
                </div>
            <?php endforeach; ?>
        </div>
        <br style="clear:both;" />
        
        <?php //if(sw_user_in_role('administrator')): ?>
        <!-- Your add & remove image links -->
        <p class="hide-if-no-js">
            <a class="button button-primary upload-custom-img <?php if ( $you_have_img  ) { echo ''; } ?>" 
            href="<?php echo esc_url($upload_link) ?>">
            <?php echo esc_html($texts['file_select']) ?>
            </a>
            <a class="button button-secondary delete-custom-img <?php if ( ! $you_have_img  ) { echo 'hidden'; } ?>" 
            href="#">
            <?php echo esc_html($texts['file_remove']) ?>
            </a>
        </p>
        <?php //endif; ?>
        
        <!-- A hidden input to set and post the chosen image id -->
        <input class="logo_image_id" type="hidden" id="<?php echo esc_html(esc_html($field_name)); ?>" name="<?php echo esc_html($field_name); ?>" value="<?php echo esc_html($image_ids); ?>" />
        </div>
        <?php
        $custom_js ='';
        $custom_js .=" jQuery(function($) {
                            if( typeof jQuery.fn.wpMediaMultiple == 'function')
                                $('#".esc_js($field_name)."meta-box-id.postbox-upload-multiple').wpMediaMultiple();
                                /* order */
                                var re_order = function(media_element){
                                    var list_media = '';
                                    media_element.find('.winter_mvc-media-card').each(function(){
                                        if(list_media !='')
                                            list_media +=',';
    
                                        list_media += $(this).attr('data-media-id');
                                    })
                                    media_element.closest('.postbox-upload-multiple').find('.logo_image_id').val(list_media);
                                }
                                /* Sort table */
                                $( '.winter_mvc-media' ).sortable({
                                    update: function(event, ui) {
                                        re_order($(this));
                                    }
                                });
                                /* remove media */
                                $( '.winter_mvc-media' ).find('.winter_mvc-media-card .remove').on('click', function(e){
                                    e.preventDefault();
                                    var media = $(this).closest('.winter_mvc-media')
                                    $(this).closest('.winter_mvc-media-card').remove();
                                    re_order(media)
                                })
                            });
                        ";
        
        echo "<script>".$custom_js."</script>";
    
        ?>
    
    <?php
}


if(!function_exists('wdk_access_check')) {
    /**
	 * Generate listing card html
	 *
	 * @param      array    $listing        The listing data.
	 * @param      array    $settings       The settings.
	 * @param      bool   	$json_output    Encode for json, default false
	 * @param      string   $html           Html for sprintf(), where
  	 * 										%1$s - content		
	 * @return     string
	 */
    function wdk_access_check($model_name, $item_id, $user_id=NULL, $method='edit') {

        if(!empty($item_id) && !is_numeric($item_id))
            exit('Issue with ID');
        
        if (in_array($model_name, array('reviews_m','reviews_type_m','reviews_option_m','reviews_data_m'))) {
            global $Winter_MVC_wdk_reviews;
            $WMVC = $Winter_MVC_wdk_reviews;
        }elseif(in_array($model_name,array('package_m','payment_m'))) {
            global $Winter_MVC_wdk_payments;
            $WMVC = $Winter_MVC_wdk_payments;
        }elseif(in_array($model_name,array('favorite_m','favoritecategory_m'))) {
            global $Winter_MVC_wdk_favorites;
            $WMVC = $Winter_MVC_wdk_favorites;
        }elseif(in_array($model_name,array('currency_m'))) {
            global $Winter_MVC_wdk_currency;
            $WMVC = $Winter_MVC_wdk_currency;
        }elseif(in_array($model_name,array('calendar_m','price_m','reservation_m'))) {
            global $Winter_MVC_wdk_bookings;
            $WMVC = $Winter_MVC_wdk_bookings;
        }elseif(in_array($model_name,array('save_search_m'))) {
            global $Winter_MVC_wdk_save_search;
            $WMVC = $Winter_MVC_wdk_save_search;
        } else {
            $WMVC = &wdk_get_instance();
        }

        if(empty($user_id))
            $user_id = get_current_user_id();
        
        if(wmvc_user_in_role('administrator')) {
            return true;
        }

        if(substr($model_name,-2,2) == '_m')
        {
            // its model
            $WMVC->model($model_name);
    
            if(empty($item_id) || $WMVC->$model_name->is_related($item_id, $user_id, $method))
            {
                // User is related
                return true;
            }
            else
            {
                //echo $CI->db->last_query();
                exit('Access denied ROLES RELATED');
            }
        }
        else
        {
            return true;
        }
        
        exit('Access denied ROLES');

    }
}

if ( ! function_exists('wdk_recaptcha_field'))
{
    function wdk_recaptcha_field($is_compact=false, $style="", $load_script=true)
    {
        static $counter = 0;
        static $recaptcha_array = array();
        
        if(get_option('wdk_recaptcha_site_key') !== FALSE && get_option('wdk_recaptcha_secret_key') !== NULL)
        {
            if($load_script && $counter===0)
            {
                echo "<script src='https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&amp;render=explicit'></script>";
            }
            $counter++;
            
            $compact_tag='';
            $size_tag='';
            if($is_compact)
            {
                $compact_tag='data-size="compact"';
                $size_tag='compact';
            }

            $recaptcha_array[$counter] = array('size'=>$size_tag);
                    
            echo '<div id="recaptcha_called_'.$counter.'" class="g-recaptcha" style="'.$style.'"  '.$compact_tag.' data-sitekey="'.get_option('wdk_recaptcha_site_key').'"></div>';
    ?>

    <script>
    <?php if($counter===1)echo 'var ';?>CaptchaCallback = function(){
    <?php for($j=1;$j<=$counter;$j++): ?>
        grecaptcha.render(document.getElementById('recaptcha_called_<?php echo $j;?>'), {'size' : '<?php echo $recaptcha_array[$j]['size']; ?>',  'sitekey' : '<?php echo get_option('wdk_recaptcha_site_key'); ?>'});
    <?php endfor; ?>
    };
    </script>

    <?php
        }
    }
}

function is_wdk_valid_recaptcha()
{
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
        //your site secret key
        if(wdk_valid_recaptcha_curl($_POST['g-recaptcha-response']))
        {
            return TRUE;
        }
    }
    return FALSE;
}

function wdk_valid_recaptcha_curl($g_recaptcha_response='') {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $args = array(
            'timeout'     => 200,
            'blocking'    => true,
            'headers'     => array(),
            'body'        => array(
                'secret' => get_option('wdk_recaptcha_secret_key'),
                'response' => $g_recaptcha_response,
                'remoteip' => sanitize_textarea_field($_SERVER['REMOTE_ADDR'])
            ),
            'cookies'     => array()
    );
    $response = wp_remote_post( $url, $args );
 
    if ( is_wp_error( $response ) ) {
       /*$error_message = $response->get_error_message();*/
       return true;
    } else {
        $response = json_decode($response['body']);
        return $response->success;
    }
}

if(!function_exists('wdk_get_option')) {
    /**
	 * Cached and get wp options
	 *
	 * @param      string    option       Option key
	 * @return     string    alt or title
	 */

	function wdk_get_option($option_key = '') {
        return get_option($option_key);
        if(isset($options[$option_key])) {
            return $options[$option_key];
        }

        $options[$option_key] = get_option($option_key);

		return $options[$option_key];
	}
}

if(!function_exists('wdk_server_current_url')) {
    /**
	 * Get current url basic on $_SERVER, exists function wdk_current_url(), on some urls have issue on listing preview page
	 *
	 * @return     string   url
    */

    function wdk_server_current_url()
    {
        return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
}


if(!function_exists('wdk_filter_date_decimal')) {
    /**
	 * Filter  00:00:00 from date
     * 
	 * @param string
	 * @return   string   date without  00:00:00
    */

    function wdk_filter_date_decimal($data)
    {
        return str_replace(' 00:00:00','', $data);
    }
}

function wdk_get_post()
{
    $post = array();

    foreach($_POST as $key => $val)
    {
        if(is_array($val))
        {
            return wdk_clean($_POST);
        }
        else
        {
            $post[sanitize_text_field($key)] = wp_kses_post($val);
        }
    }

    return $post;
}


function wdk_get_get()
{
    $log_array = array();

    foreach($_GET as $key => $val)
    {
        if(is_array($val))
        {
            return wdk_clean($_POST);
        }
        else
        {
            $log_array[sanitize_text_field($key)] = wp_kses_post($val);
        }
    }

    return $log_array;
}

function wdk_clean($array)
{
    $arr_cleaned = array();
    foreach($array as $key=>$val)
    {
        if(is_array($val))
        {
            $arr_cleaned[sanitize_text_field($key)] = wdk_clean($val);
        }
        else
        {
            $arr_cleaned[sanitize_text_field($key)] = wp_kses_post($val);
        }
        
    }

    //dump($arr_cleaned);

    return $arr_cleaned;
}

/*
<select class="form-control" name="<?php echo 'control_operator_'.$i_fieldnum; ?>">
    <option value="CONTAINS">CONTAINS</option>
    <option value="NOT_CONTAINS">NOT_CONTAINS</option>
</select>
*/
function wdk_select_multi_option($field_name, $options = array(), $selected = NULL, $extra=NULL, $empty_text = NULL, $empty_val = '')
{
    $output = '<select name="'.$field_name.'" '.$extra.' multiple="multiple">';

    if(!is_null($empty_text))
        $output.= '<option value="'.esc_attr($empty_val).'">'.esc_html($empty_text).'</option>';

    if(is_array($options) && count($options) > 0)
    foreach($options as $key=>$val)
    {
        $output.= '<option value="'.$key.'" '.($selected==$key?'selected':'').'>'.$val.'</option>';
    }

    $output.= '</select>';

    return $output;
}


if ( ! function_exists('wdk_treefield_select_ajax'))
{
    /**
	 * Return select2 ajax
	 *
	 * @param      string    option       Option key
	 * @return     string    alt or title
	 */
	function wdk_treefield_select_ajax($name = '', $table=NULL, $selected = NULL, 
                            $column_print = 'category_title', $column_key = 'idcategory',  $language_id=NULL, $empty_value='', $filter_ids = '', $attr = '')
	{
        $WMVC = &wdk_get_instance();
        $WMVC->model($table);
        
        if(is_array($filter_ids)) $filter_ids = implode(',', $filter_ids);
        
	    static $counter = 0;
		$form = '<select data-ajax="'.admin_url('admin-ajax.php').'" name="'.$name.'" data-table="'.$table.'" '.$attr.' data-placeholder="'.$empty_value.'" class="form-control select_ajax" id="wdk_select2_'.$counter.'" multiple="">';
        

        if(false)
        if( $column_key == 'idcategory') {
            $form .= '<option selected="selected" value="">'.esc_html__('Select Category', 'wpdirectorykit').'</option>';
        } else {
            $form .= '<option selected="selected" value="">'.esc_html__('Select Location', 'wpdirectorykit').'</option>';
        }
        
        if($selected) {
                if(is_array($selected)) {
                   
                    $ids = array();
                    foreach($selected as $selected_item) {
                        if(!empty($selected_item)) {
                            $ids [] = $selected_item;
                        }
                    }
                    
                    /* where in */
                    if(!empty($ids)){
                        $WMVC->db->select($WMVC->$table->_table_name.'.*');
                        $WMVC->db->where($WMVC->$table->_table_name.'.'.$column_key.' IN(' . implode(',', $ids) . ')', null, false);
                        $WMVC->db->order_by('FIELD('.$WMVC->$table->_table_name.'.'.$column_key.', '. implode(',', $ids) . ')');
                       
                        $results = $WMVC->$table->get();
                        foreach ($results as $item) {
                            if($item)
                                $form .= '<option selected="selected" value="'.esc_attr(wmvc_show_data($column_key, $item, false, TRUE, TRUE)).'">'.esc_html(wmvc_show_data($column_print, $item, false, TRUE, TRUE)).'</option>';
                        }
                    }
                } elseif(is_intval($selected)) {
                    $db_item = $WMVC->$table->get($selected, TRUE);
                    if($db_item)
                        $form .= '<option selected="selected" value="'.esc_attr(wmvc_show_data($column_key, $db_item, false, TRUE, TRUE)).'">'.esc_attr(wmvc_show_data($column_print, $db_item, false, TRUE, TRUE)).'</option>';
                } 
            } else {
                //$form .= '<option value="" selected="selected">'.esc_html__('Not selected', 'wpdirectorykit').'</option>';
            }
        $form .= '</select>';
        
        //load javascript library
        if($counter==0)
        {
            wp_enqueue_script('select2');
            wp_enqueue_script('wdk-select2');
            wp_enqueue_style('select2');
        }
        ?>
        <?php
        $counter++;
		return $form;
	}
}

if ( ! function_exists('wdk_user_select_ajax'))
{
    /**
	 * Return select2 ajax
	 *
	 * @param      string    option       Option key
	 * @return     string    alt or title
	 */
	function wdk_user_select_ajax($name = '', $selected = NULL, $empty_value='', $filter_ids = '')
	{
        $WMVC = &wdk_get_instance();
        
        if(is_array($filter_ids)) $filter_ids = implode(',', $filter_ids);
        
	    static $counter = 0;
		$form = '<select data-ajax="'.admin_url('admin-ajax.php').'" name="'.$name.'" data-placeholder="'.$empty_value.'" class="form-control select_ajax_user" id="wdk_select2_user_'.$counter.'" multiple="">';
            if($selected) {
                if(is_array($selected)) {
                    /* where in */
                    foreach ($selected as $item) {
                        $user_data =  get_userdata($item);
                        if($user_data)
                            $form .= '<option selected="selected" value="'.esc_attr(wmvc_show_data('ID', $user_data, false, TRUE, TRUE)).'">'.esc_html(wmvc_show_data('display_name', $user_data, false, TRUE, TRUE)).'</option>';
                    }
                } elseif(is_intval($selected)) {
                    $user_data =  get_userdata($selected);
                    if($user_data)
                        $form .= '<option selected="selected" value="'.esc_attr(wmvc_show_data('ID', $user_data, false, TRUE, TRUE)).'">'.esc_html(wmvc_show_data('display_name', $user_data, false, TRUE, TRUE)).'</option>';
                } 
            } else {
                //$form .= '<option value="" selected="selected">'.esc_html__('Not selected', 'wpdirectorykit').'</option>';
            }
        $form .= '</select>';
        
        //load javascript library
        if($counter==0)
        {
            wp_enqueue_script('select2');
            wp_enqueue_script('wdk-select2');
            wp_enqueue_style('select2');
        }
        ?>
        <?php
        $counter++;
		return $form;
	}
}


/*
<select class="form-control" name="<?php echo 'control_operator_'.$i_fieldnum; ?>">
    <option value="CONTAINS">CONTAINS</option>
    <option value="NOT_CONTAINS">NOT_CONTAINS</option>
</select>
*/
function wdk_select_option_multiple($field_name, $options = array(), $selected = array(), $extra=NULL, $empty_text = NULL, $empty_val = '')
{
    $output = '<select name="'.$field_name.'" '.$extra.' multiple >';

    if(!is_null($empty_text))
        $output.= '<option value="'.esc_attr($empty_val).'">'.esc_html($empty_text).'</option>';

    if(is_array($options) && count($options) > 0)
    foreach($options as $key=>$val)
    {
        if(is_string($selected)) {
            $output.= '<option value="'.$key.'" '.(($selected == $key)?'selected="selected"':'').'>'.$val.'</option>';
        } else if(is_array($selected)){
            $output.= '<option value="'.$key.'" '.((in_array($key, $selected) === TRUE)?'selected="selected"':'').'>'.$val.'</option>';
        }
    }

    $output.= '</select>';

    return $output;
}



if ( ! function_exists('wdk_convert_date_format_js'))
{
    /**
	 * Convert date format for support in js
	 * From: https://wordpress.org/support/article/formatting-date-and-time/
     * To: https://momentjscom.readthedocs.io/en/latest/moment/04-displaying/01-format/
     * 
	 * @param      string    php date format       Option key
	 * @return     string    js date format
	 */
	function wdk_convert_date_format_js($date_format)
	{
        $replaced = array(
            'D'=>'ddd',
            'j'=>'D',
            'd'=>'DD',
            'l'=>'dddd',
            'F'=>'MMMM',
            'Y'=>'YYYY',
            'S'=>'Do',
            'm'=>'MM',
            'n'=>'MM',
            'F'=>'MMMM',
            'M'=>'MMM',
            'Y'=>'YYYY',
            'y'=>'YY',
            'a'=>'a',
            'A'=>'A',
            'h'=>'hh',
            'G'=>'H',
            'H'=>'HH',
            'g'=>'h',
            'i'=>'mm',
            's'=>'ss',
            'T'=>'zz',
            'c'=>'',
            'r'=>'llll',
            'u'=>'x',
        );

        $replaced_from = array_keys($replaced);
        $date_format = str_replace($replaced_from, array_keys($replaced_from), $date_format);
		return str_replace(array_reverse(array_keys($replaced_from)), array_reverse($replaced), $date_format);
	}
}

if ( ! function_exists('wdk_convert_date_format_jquery'))
{
    /**
	 * Convert date format for support in js
	 * From: https://wordpress.org/support/article/formatting-date-and-time/
     * To: https://api.jqueryui.com/datepicker/
     * 
	 * @param      string    php date format       Option key
	 * @return     string    js date format
	 */
	function wdk_convert_date_format_jquery($date_format)
	{
        $replaced = array(
            'D'=>'D',
            'd'=>'dd',
            'j'=>'d',
            'l'=>'DD',
            'Y'=>'yy',
            'S'=>'d',
            'm'=>'mm',
            'n'=>'m',
            'M'=>'m',
            'F'=>'MM',
            'y'=>'y',
            'a'=>'',
            'A'=>'',
            'h'=>'',
            'G'=>'',
            'H'=>'',
            'g'=>'',
            'i'=>'',
            's'=>'',
            'T'=>'',
            'c'=>'ISO_8601',
            'r'=>'RFC_2822',
            'u'=>'@',
            ':'=>'',
        );

        $replaced_from = array_keys($replaced);
        $date_format = str_replace($replaced_from, array_keys($replaced_from), $date_format);
		return str_replace(array_reverse(array_keys($replaced_from)), array_reverse($replaced), $date_format);
	}
}

if ( ! function_exists('wdk_convert_date_format_jquery'))
{
    /**
	 * Convert date format for support in js
	 * From: https://wordpress.org/support/article/formatting-date-and-time/
     * To: https://api.jqueryui.com/datepicker/
     * 
	 * @param      string    php date format       Option key
	 * @return     string    js date format
	 */
	function wdk_convert_date_format_jquery($date_format)
	{
        $replaced = array(
            'D'=>'D',
            'd'=>'dd',
            'j'=>'d',
            'l'=>'DD',
            'Y'=>'yy',
            'S'=>'d',
            'm'=>'mm',
            'n'=>'m',
            'M'=>'m',
            'F'=>'MM',
            'y'=>'y',
            'a'=>'',
            'A'=>'',
            'h'=>'',
            'G'=>'',
            'H'=>'',
            'g'=>'',
            'i'=>'',
            's'=>'',
            'T'=>'',
            'c'=>'ISO_8601',
            'r'=>'RFC_2822',
            'u'=>'@',
            ':'=>'',
        );

        $replaced_from = array_keys($replaced);
        $date_format = str_replace($replaced_from, array_keys($replaced_from), $date_format);
		return str_replace(array_reverse(array_keys($replaced_from)), array_reverse($replaced), $date_format);
	}
}

if ( ! function_exists('wdk_get_gps'))
{
    
	/**
	* Get Latitude/Longitude/Altitude based on an address
	* @param string $address The address for converting into coordinates
	* @return array An array containing Latitude/Longitude/Altitude data
	*/
	function wdk_get_gps($address = '')
	{
        static $results = array();
        $address = str_replace(' ','+',$address);

        if(!isset($results[$address])) {
            $results[$address] = NULL;
            $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . $address;
            $request    = wp_remote_get( $url );
            $response = '';

            // request failed
            if ( is_wp_error( $request ) ) {
                $response = $request;
            }
            $code = (int) wp_remote_retrieve_response_code( $request );

            // make sure the fetch was successful
            if (empty($response) && $code == 200 ) {
                $response = wp_remote_retrieve_body( $request );
                // Decode the json
                $resp = json_decode( $response, true ); 

                if(!empty($resp) && isset($resp[0]) && isset($resp[0]['lat']) && isset($resp[0]['lon'])) {
                    $results[$address] = array('lat' => $resp[0]['lat'], 'lng' => $resp[0]['lon'], 'alt' => 0);
                    return $results[$address];
                }
            } 
        } else {
            return $results[$address];
        }
        return false;
	}
}

if ( ! function_exists('wdk_get_gps_google'))
{
    
	/**
	* Get Latitude/Longitude/Altitude based on an address from Google Api
	* @param string $address The address for converting into coordinates
	* @param string $api_key Custom google API Key
    *
	* @return array An array containing Latitude/Longitude/Altitude data
	*/
	function wdk_get_gps_google($address = '', $api_key = NULL)
	{
        static $results = array();
        
        if ( empty( $api_key ) ) {
            $api_key =  wdk_get_option('wdk_geo_google_api_key');
        }

        $address = str_replace(' ','+',$address);

        if(!isset($results[$address])) {
            $results[$address] = NULL;
            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.trim($address).'&key='.$api_key;
            $request    = wp_remote_get( $url );
            $response = '';

            // request failed
            if ( is_wp_error( $request ) ) {
                $response = $request;
            }
            $code = (int) wp_remote_retrieve_response_code( $request );

            // make sure the fetch was successful
            if (empty($response) && $code == 200 ) {
                $response = wp_remote_retrieve_body( $request );
                // Decode the json
                $resp = json_decode( $response, true ); 
                $status = $resp['status'];
                
                //if request status is successful
                if($status == "OK"){
                    //get address from json data
                    $lat = $resp['results'][0]['geometry']['location']['lat'];
                    $lng = $resp['results'][0]['geometry']['location']['lng'];

                    $results[$address] = array('lat' =>  $lat , 'lng' =>  $lng, 'alt' => 0);
                    return $results[$address];
                }
            } 
        } else {
            return $results[$address];
        }

        return false;
	}
}

 
if ( ! function_exists('wdk_getDueCoords'))
{
    // Modified from:
    // http://www.sitepoint.com/forums/showthread.php?656315-adding-distance-gps-coordinates-get-bounding-box
    /**
    * bearing is 0 = north, 180 = south, 90 = east, 270 = west
    *
    */
    function wdk_getDueCoords($latitude, $longitude, $bearing, $distance, $distance_unit = "km", $return_as_array = FALSE) {
    
        if ($distance_unit == "m") {
          // Distance is in miles.
        	  $radius = 3963.1676;
        }
        else {
          // distance is in km.
          $radius = 6378.1;
        }
        
        //	New latitude in degrees.
        $new_latitude = rad2deg(asin(sin(deg2rad($latitude)) * cos($distance / $radius) + cos(deg2rad($latitude)) * sin($distance / $radius) * cos(deg2rad($bearing))));
        		
        //	New longitude in degrees.
        $new_longitude = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad($bearing)) * sin($distance / $radius) * cos(deg2rad($latitude)), cos($distance / $radius) - sin(deg2rad($latitude)) * sin(deg2rad($new_latitude))));
        
        if ($return_as_array) {
          //  Assign new latitude and longitude to an array to be returned to the caller.
          $coord = array();
          $coord['lat'] = $new_latitude;
          $coord['lng'] = $new_longitude;
        }
        else {
          $coord = $new_latitude . ", " . $new_longitude;
        }
        
        return $coord;
    
    }	
}	
 
if ( ! function_exists('wdk_is_gps'))
{
    // Validation gps
    /**
    * @param string $string_gps gps in string like xx.xxxx,xx.xxxxx
    * @return bool
    */
    function wdk_is_gps($string_gps = '') {
        if(preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $string_gps)) {
            return true;
        }
        
        return false;
    }	
}	
    
 
if ( ! function_exists('wdk_is_phone'))
{
    // Validation phone
    /**
    * @param string $value phone in string
    * @return bool
    */
    function wdk_is_phone($value = '') {
        if(preg_match("/.*[0-9]{3,4}-[0-9]{3,4}.*$/", $value)) {
            return true;
        }
        
        return false;
    }	
}	
 
if ( ! function_exists('wdk_filter_phone'))
{
    // Validation phone
    /**
    * @param string $value phone in string
    * @return bool
    */
    function wdk_filter_phone($value = '') {
        return str_replace(array(' ','-','(',')'),'',$value);
    }	
}	
    
 
if ( ! function_exists('wdk_generate_media_field'))
{
    // Based on value return video embed, youtube, source video tag
    /**
    * @param string $value value
    * @param string $class help css class
    * @return string html or NULL
    */
    function wdk_generate_media_field($value = '', $class="wdk-image") {
        $output = NULL;

        if(strpos($value, 'vimeo.com') !== FALSE)
        {
            $output = wp_oembed_get($value, array("width"=>"800", "height"=>"450"));
            $output = str_replace( '<iframe', '<iframe class="'.$class.'" ', $output);
        }
        elseif(strpos($value, 'watch?v=') !== FALSE)
        {
            $embed_code = substr($value, strpos($value, 'watch?v=')+8);
            $output =  wp_oembed_get('https://www.youtube.com/watch?v='.$embed_code, array("width"=>"800", "height"=>"455"));
            $output = str_replace( '<iframe', '<iframe class="'.$class.'" ', $output );
        }
        elseif(strpos($value, 'youtu.be/') !== FALSE)
        {
            $embed_code = substr($value, strpos($value, 'youtu.be/')+9);
            $output = wp_oembed_get('https://www.youtube.com/watch?v='.$embed_code, array("width"=>"800", "height"=>"455"));
            $output = str_replace( '<iframe', '<iframe class="'.$class.'" ', $output );
        } 
        elseif(strpos($value, 'youtube.com/embed/') !== FALSE)
        {
            $embed_code = substr($value, strpos($value, 'youtube.com/embed/')+18);
            $output = wp_oembed_get('https://www.youtube.com/watch?v='.$embed_code, array("width"=>"800", "height"=>"455"));
            $output = str_replace( '<iframe', '<iframe class="'.$class.'" ', $output );
        } 
        elseif(filter_var($value, FILTER_VALIDATE_URL) !== FALSE && preg_match('/\.(mp4|flv|wmw|ogv|webm|ogg)$/i', $value))
        {
            $output  = '<video src="'.$value.'" controls class="'.$class.'"></video> ';
        }

        return $output;
    }	
}	
    
 
if ( ! function_exists('wdk_depend_get_hidden_fields'))
{
    // Based on value return video embed, youtube, source video tag
    /**
    * @param int $tree_id tree_id of category
    * @param string $type type, default 'categories'
    * @return string string with hidden fields like ,1,2,3,4, or NULL
    */
    function wdk_depend_get_hidden_fields($tree_id = NULL, $type="categories") {
        static  $output = array();

        if(isset($output[$tree_id])) {
            return $output[$tree_id];
        }
        if(empty($tree_id)) {
            return NULL;
        } else {
            $output[$tree_id] = false;
        
            global $Winter_MVC_WDK;
            $Winter_MVC_WDK->model('dependfields_m');

            $depend_fields = $Winter_MVC_WDK->dependfields_m->get_by(array('field_id' => $tree_id,'main_field' => $type), TRUE);
           
            if($depend_fields && !empty($depend_fields->hidden_fields_list)) {
                $output[$tree_id] = ','.$depend_fields->hidden_fields_list. ',';
            }
            return $output[$tree_id];
        }

        return false;
    }	
}	
 
if ( ! function_exists('wdk_depend_is_hidden_field'))
{
    // Based on value return video embed, youtube, source video tag
    /**
    * @param int $field_id field_id
    * @param int $tree_id id for category
    * @param string $type type, default 'categories'
    * @return string string with hidden fields like ,1,2,3,4, or NULL
    */
    function wdk_depend_is_hidden_field($field_id = NULL, $tree_id = NULL, $type="categories") {
        $hidden_fields = wdk_depend_get_hidden_fields($tree_id, $type);
        if($hidden_fields && strpos($hidden_fields, ','.trim($field_id).',') !== FALSE) {
            return true;
        }
     
        return false;
    }	
}	


if ( ! function_exists('is_wdk_slug_format'))
{
    function is_wdk_slug_format($string)
    {
        if(empty($string))return FALSE;

    
        if (preg_match("/^[a-z0-9-_]+\$/", $string)) {

            return TRUE;
        }

        return FALSE;
    }
}

if ( ! function_exists('wdk_number_format_i18n'))
{
    function wdk_number_format_i18n($value)
    {
        if(empty($value))return NULL;

    
        $value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $value = (float)str_replace(array(","," ",'&nbsp;'), "", $value);

        return number_format_i18n($value);
    }
}
    
?>