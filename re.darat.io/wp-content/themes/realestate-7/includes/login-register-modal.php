<?php
/**
 * Booking Modal
 *
 * @package WP Pro Real Estate 7
 * @subpackage Template
 */

global $ct_options;

$ct_enable_front_end_registration_elementor_template_area = isset( $ct_options['ct_enable_front_end_registration_elementor_template_area'] ) ? esc_attr( $ct_options['ct_enable_front_end_registration_elementor_template_area'] ) : '';
$ct_front_end_registration_elementor_template_area_id = isset( $ct_options['ct_front_end_registration_elementor_template_area_id'] ) ? esc_attr( $ct_options['ct_front_end_registration_elementor_template_area_id'] ) : '';

?>
    
<div id="overlay">
    <div id="modal" <?php if($ct_enable_front_end_registration_elementor_template_area == 'yes') { echo 'class="elementor-modal-content"'; } ?>>
    	<div id="modal-inner">
	        <span class="close"><?php ct_close_svg(); ?></span>
	        
	        <?php if($ct_enable_front_end_registration_elementor_template_area == 'yes') {
		        echo '<div id="elementor-modal-content-inner" class="col span_6 first">';
		        	echo do_shortcode('[elementor-template id="' . $ct_front_end_registration_elementor_template_area_id . '"]');
	        	echo '</div>';
        	} ?>

        	<?php if($ct_enable_front_end_registration_elementor_template_area == 'yes') {
		        echo '<div id="login-registration-pass-forms" class="col span_6">';
        	} ?>

		        <div id="login">
			        <?php echo ct_login_form(); ?>
		        </div>
		        
		        <div id="register">
					<?php echo ct_registration_form(); ?>
		        </div>

		        <div id="lost-password">
					<?php echo ct_lost_password_fields(); ?>
		        </div>

	        <?php if($ct_enable_front_end_registration_elementor_template_area == 'yes') {
		        echo '</div>';
        	} ?>

        		<div class="clear"></div>
        </div>
    </div>
</div>