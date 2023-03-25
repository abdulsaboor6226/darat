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

$output .='<div class="elementinvader_addons_for_elementor_f_group checkbox elementinvader_addons_for_elementor_f_group_el_'.$element['_id'].'" style="'.$styles.'">
            <label for="'.$field_id.'">
                <input name="'.$element['field_label'].'" id="'.$field_id.'" type="checkbox" class="elementinvader_addons_for_elementor_f_field_checkbox" '.$required.' value="'.$value.'" placeholder="'.$element['placeholder'].'" >
                '.$element['field_label'].$required_icon.'
            </label>
        </div>';
echo $output;