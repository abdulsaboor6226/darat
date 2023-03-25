<?php
/**
 * The template for Settings.
 *
 * This is the template that edit form settings
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap wdk-wrap">
    <h1 class="wp-heading-inline"><?php echo __('Settings', 'wpdirectorykit'); ?></h1>
    <br /><br />
    <div class="wdk-body">
        <form method="post" action="" novalidate="novalidate">
            <div class="postbox" style="display: block;">
                <div class="postbox-header">
                    <h3><?php echo __('General Settings', 'wpdirectorykit'); ?></h3>
                </div>
                <div class="inside">
                    <?php
                    $form->messages('class="alert alert-danger"',  __('Successfully saved', 'wpdirectorykit'));
                    ?>
                    <?php if(!get_option('wdk_results_page')):?>
                        <p class="alert alert-info"><?php echo __('Missing results page', 'wpdirectorykit'); ?></p>
                    <?php endif;?>
                    <?php echo wdk_generate_fields($fields, $db_data); ?>   
                    <div class="wdk-field-edit">
                        <a href="<?php echo get_admin_url() . "admin.php?page=wdk_settings&function=remove"; ?>" 
                           
                            class="button button-primary event-ajax-indicator confirm" id="reset_data_field_button"><?php echo __('Remove plugin data (Listings, fields, location, categories)','wpdirectorykit'); ?></a>               
                        <span class="wdk-ajax-indicator wdk-infinity-load color-primary dashicons dashicons-update-alt hidden" style="margin-top: 4px;margin-left: 4px;"></span>              
                    </div>
                    <div class="wdk-field-edit">
                        <a href="<?php echo get_admin_url() . "admin.php?page=wdk_settings&function=import_demo"; ?>" class="button button-primary" id="import_demo_field_button"><?php echo __('Import Demo Data','wpdirectorykit'); ?></a> 
                    </div>
                    <div class="wdk-field-edit">
                        <a href="#" class="button button-primary" id="generate_listings_images_path"><?php echo __('Generate Listings Images Path','wpdirectorykit'); ?></a>               
                    </div>
                </div>
            </div>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save Changes', 'wpdirectorykit'); ?>">
        </form>
    </div>
    <br/>
    <div class="alert alert-info" role="alert"><a href="//wpdirectorykit.com/documentation/#change_currency" target="_blank"><?php echo __('How to change currency?','wpdirectorykit'); ?></a></div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#reset_data_field_button').on('click', function(e){
        var self = $(this);
        if(confirm('<?php echo __('Are you sure? All Listings, fields, categories, locations will be completely removed', 'wpdirectorykit')?>')) {
            return true;
        } else {
            e.preventDefault();
            e.stopPropagation();
            $(this).parent().find('.wdk-ajax-indicator').addClass('hidden');
            return false;
        }
    })
    $('#generate_listings_images_path').on('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        var self = $(this);

        if(self.attr('disabled')) {
            return false;
        }

        self.addClass('wdk_btn_load_indicator out');
      
        self.attr('disabled','disabled');
        var ajax_param = {
            "page": 'wdk_backendajax',
            "function": 'generated_listings_images_path',
            "action": 'wdk_public_action',
        };
        $.post("<?php echo admin_url( 'admin-ajax.php' );?>", ajax_param, 
            function(data){
                
            if(data.popup_text_success)
                wdk_log_notify(data.popup_text_success);
                
            if(data.popup_text_error)
                wdk_log_notify(data.popup_text_error, 'error');
                
            if(data.success) {
                self.removeClass('wdk_btn_load_indicator out');
                self.addClass('wdk_btn_load_success out');
            } else {
                self.removeClass('wdk_btn_load_indicator out');
                self.addClass('wdk_btn_load_error out');
            }
        }).always(function(data) {

        });
        return false;
    })
})
</script>

<?php $this->view('general/footer', $data); ?>