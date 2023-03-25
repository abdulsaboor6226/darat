<?php
/**
 * The template for Search field DROPDOWN.
 *
 * This is the template that field layout for search form
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php

$field_key = 'field_'.wmvc_show_data('idfield',$field_data);
$field_attr_id = 'wdk_field_'.wmvc_show_data('idfield', $field_data);
$placeholder = wmvc_show_data('field_label', $field_data);
$field_value = '';

$query_type = wmvc_show_data('query_type', $field_data, '');

if(!empty($query_type) && $query_type !='min_max')
    $field_key .='_'.$query_type;
 
if(isset($predefinedfields_query) && !empty($predefinedfields_query[$field_key])) {
    $field_value = sanitize_text_field($predefinedfields_query[$field_key]);
}

if(isset($_GET[$field_key])) {
    $field_value = sanitize_text_field($_GET[$field_key]);
}

if($query_type =='min')
    $placeholder = esc_html__('Min','wpdirectorykit').' '.$placeholder;

if($query_type =='max')
    $placeholder = esc_html__('Max','wpdirectorykit').' '.$placeholder;
    
$values = array();
if(!empty(wmvc_show_data('values_list', $field_data))){
    $values = explode(',', wmvc_show_data('values_list', $field_data));
    $values = array(''=> $placeholder) + array_combine($values, $values);
}
wdk_search_fields_toggle();
?>

<div class="wdk-field wdk-col wdk_search_<?php echo esc_attr($field_key);?> <?php if($query_type == 'min_max'):?>min_max_wdk-field<?php endif;?>  <?php echo esc_attr(wmvc_show_data('field_type', $field_data)); ?> <?php echo esc_attr(wmvc_show_data('class', $field_data)); ?> 
    wdk_field_id_<?php echo wmvc_show_data('idfield',$field_data);?>">
    <label class="wdk-field-label"><?php echo esc_html(wmvc_show_data('field_label', $field_data)); ?></label>
    <div class="wdk-field-group">
        <?php if($query_type == 'min_max'):?>
            <div class="wdk-row min_max_row">
                <div class="wdk-col-6">
                    <?php echo wmvc_select_option($field_key.'_min' , array_merge($values, array(''=> esc_html__('Min','wpdirectorykit').' '.$placeholder)), (isset($_GET[$field_key.'_min'])) ? esc_attr($_GET[$field_key.'_min']) : '', 'class="wdk-control"'); ?>
                </div>
                <div class="wdk-col-6">
                    <?php echo wmvc_select_option($field_key.'_max',  array_merge($values, array(''=> esc_html__('Max','wpdirectorykit').' '.$placeholder)), (isset($_GET[$field_key.'_max'])) ? esc_attr($_GET[$field_key.'_max']) : '', 'class="wdk-control"'); ?>
                </div>
            </div>
        <?php else:?>
            <?php echo wmvc_select_option($field_key , $values, $field_value, 'class="wdk-control"'); ?>
        <?php endif;?>
    </div>
</div>

<?php
wp_enqueue_script( 'jquery-ui-core', false, array('jquery') );
wp_enqueue_script( 'jquery-ui-sortable', false, array('jquery') );
wp_enqueue_script( 'jquery-ui-selectmenu', false, array('jquery') );
wp_enqueue_style( 'jquery-ui');
wp_enqueue_style( 'jquery-ui-selectmenu');
?>