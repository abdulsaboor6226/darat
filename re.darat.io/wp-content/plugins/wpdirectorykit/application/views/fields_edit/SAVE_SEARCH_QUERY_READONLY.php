<?php
/**
 * The template for Edit field SAVE SEARCH QUERY READONLY.
 *
 * This is the template that field layout for edit form, readonly
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php

//wmvc_dump($field);

if(isset($field->field))
{
    $field_id = $field->field;
}
else
{
    $field_id = 'field_'.$field->idfield;
}

if(!isset($field->hint))$field->hint = '';
if(!isset($field->columns_number))$field->columns_number = '';
if(!isset($field->class))$field->class = '';

$field_label = $field->field_label;

$required = '';
if(isset($field->is_required) && $field->is_required == 1)
    $required = '*';
        
    global $Winter_MVC_WDK;
    $Winter_MVC_WDK->load_helper('listing');
?>

<div class="wdk-field-edit <?php echo esc_attr($field->field_type); ?> wdk-col-<?php echo esc_attr($field->columns_number); ?> <?php echo esc_attr($field->class); ?>">
    <label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($field_label).esc_html($required); ?></label>
    <div class="wdk-field-container">
        <div class="regular-span">
        <?php 
            $custom_parameters = array();
            $qr_string = trim(wmvc_show_data(esc_attr($field_id), $db_data, ''));
            $string_par = array();
            parse_str($qr_string, $string_par);
            $custom_parameters = array_map('trim', $string_par);
        ?>
        <?php foreach ($custom_parameters as $field_key => $field_value) :?>
            <?php  
                $field_data = explode('_', $field_key);
                if(isset($field_data[1])) {
                    if(is_numeric($field_data[1])) {
                        echo '<b style="font-weight: 500;">'.esc_html(wdk_field_label($field_data[1])).'</b>: '.esc_html($field_value).'<br>';
                    } 
                    elseif($field_data[1] == 'category') {
                            $Winter_MVC_WDK->model('category_m');
                            $tree_data = $Winter_MVC_WDK->category_m->get($field_value, TRUE);
                            echo '<b style="font-weight: 500;">'.esc_html__('Category','wpdirectorykit').'</b>: '.esc_html(wmvc_show_data('category_title', $tree_data)).'<br>';
                        }
                    elseif($field_data[1] == 'location') {
                            $Winter_MVC_WDK->model('location_m');
                            $tree_data = $Winter_MVC_WDK->location_m->get($field_value, TRUE);
                            echo '<b style="font-weight: 500;">'.esc_html__('Location','wpdirectorykit').'</b>: '.esc_html(wmvc_show_data('location_title', $tree_data)).'<br>';
                    }
                    elseif($field_data[1] == 'search') {
                            echo '<b style="font-weight: 500;">'.esc_html__('Search Smart','wpdirectorykit').'</b>: '.esc_html($field_value).'<br>';
                    }
                    else {
                        echo '<b style="font-weight: 500;">'.esc_html($field_data[1]).'</b>: '.esc_html($field_value).'<br>';
                    }
                }
            ?>
        <?php endforeach;?>
        </div>
        <?php if(!empty($field->hint)):?>
        <p class="wdk-hint">
            <?php echo esc_html($field->hint); ?>
        </p>
        <?php endif;?>
    </div>
</div>