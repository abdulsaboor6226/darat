<?php
 $output ='';
$styles ='';
$helper_classes ='';
$value = '';
$required = '';
$required_icon = '';
$field_id = $this->_ch($element['custom_id'],'elementinvader_addons_for_elementor_f_field_id_'.$element['_id']).strtolower(str_replace(' ', '_', $element['field_label']));
$value = $this->_ch($element['field_value']);
$this->add_field_css($element);
if($element['required']){
    $required = 'required="required"';
    $required_icon = '*';
}

if($element['label_position'] == 'inline'){
    $helper_classes .='inline';
}

$output .='<div class="elementinvader_addons_for_elementor_f_group elementinvader_addons_for_elementor_f_group_el_'.$element['_id'].' '.$helper_classes.'" style="'.$styles.'">';
if($element['show_label'])
    $output .='<label for="'.$field_id.'">'.$element['field_label'].$required_icon.'</label>';

$output .='<textarea name="'.$element['field_label'].'" id="'.$field_id.'" rows="'.$element['rows'].'" class="elementinvader_addons_for_elementor_f_field" '.$required.' placeholder="'.$element['placeholder'].'" >'.$value.'</textarea>
        </div>';

echo $output;