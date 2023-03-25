<?php
/**
 * The template for Edit Listing.
 *
 * This is the template that form edit
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap wdk-wrap">
    <h1 class="wp-heading-inline"><?php echo __('Add/Edit Listing', 'wpdirectorykit'); ?></h1>
    <br /><br />
    <div class="wdk-body">
        <form method="post" class="form_listing" action="<?php echo wmvc_current_edit_url(); ?>" enctype="multipart/form-data" novalidate="novalidate">
            <div class="postbox" style="display: block;">
                <div class="postbox-header">
                    <h3 class="wide"><?php echo __('Main Data', 'wpdirectorykit'); ?> </h3>
                    <?php if(!empty(wmvc_show_data('ID', $db_data))):?>

                        <?php if($calendar_id):?>
                        <a href="<?php echo get_admin_url() . "admin.php?page=wdk-bookings-calendar&function=edit&id=".esc_attr($calendar_id); ?>" 
                                class="wdk-mr-5 button button-secondary alignright"
                        >
                            <span class="dashicons dashicons-calendar" style="margin-top: 4px;"></span> <?php echo __('Edit Calendar','wpdirectorykit')?>
                        </a>
                        <?php endif;?>

                        <a href="<?php echo get_admin_url() . "admin.php?page=wdk-duplicate-listing&function=duplicate&listing_post_id=".wmvc_show_data('ID', $db_data); ?>" 
                            <?php if ( !file_exists(ABSPATH . 'wp-content/plugins/wdk-duplicate-listing/wdk-duplicate-listing.php') ):?>
                                class="wdk-mr-5 button button-secondary alignright wdk-pro"
                                data-button-succuss = "<?php echo esc_attr__('Purchase Now', 'wpdirectorykit');?>" 
                                data-title = "<?php echo esc_attr__('Your version doesn\'t support this functionality, please upgrade', 'wpdirectorykit');?>" 
                                data-content = "<?php echo esc_attr__('We constantly maintain compatibility and improving this plugin for living, please support us and purchase, we provide very reasonable prices and will always do our best to help you!','wpdirectorykit');?>" 
                                data-action =  "https://www.wpdirectorykit.com/plugins/wp-duplicate-listing.html" 
                            <?php elseif ( file_exists(ABSPATH . 'wp-content/plugins/wdk-duplicate-listing/wdk-duplicate-listing.php') && !function_exists('run_wdk_duplicate_listing')):?>
                                class="wdk-mr-5 button button-secondary alignright wdk-pro"
                                data-button-succuss = "<?php echo esc_attr__('Activate addon WDK Duplicate Listing', 'wpdirectorykit');?>" 
                                data-title = "<?php echo esc_attr__('Your version doesn\'t support this functionality, please upgrade', 'wpdirectorykit');?>" 
                                data-content = "<?php echo esc_attr__('We constantly maintain compatibility and improving this plugin for living, please support us and purchase, we provide very reasonable prices and will always do our best to help you!','wpdirectorykit');?>" 
                           
                                data-action =  "<?php echo get_admin_url() . "plugins.php?plugin_status=all#activate-wdk-duplicate-listing"; ?>" 
                            <?php else:?>
                                class="wdk-mr-5 button button-secondary alignright"
                            <?php endif;?>
                        >
                            <span class="dashicons dashicons-admin-page" style="margin-top: 4px;"></span> <?php echo __('Duplicate Listing','wpdirectorykit')?>
                        </a>
                        <a href="<?php echo get_permalink(wmvc_show_data('ID', $db_data)); ?>" class="button button-secondary alignright" target="_blank" style="margin-right:15px;"><span class="dashicons dashicons-visibility" style="margin-top: 4px;"></span> <?php echo __('View listing', 'wpdirectorykit'); ?></a>
                    <?php endif;?>
                </div>
                <div class="inside">
                    <?php

                        $success_message = NULL;
                        if(isset($_GET['custom_message']))
                            $success_message = esc_html(urldecode($_GET['custom_message']));

                        $form->messages('class="alert alert-danger"', $success_message);
                    ?>
                    <div class="wdk-side-content">
                        <div class="wdk-col main">
                            <table class="form-table" role="presentation">
                                <tbody>
                                    <tr>
                                        <th scope="row"><label for="post_title"><?php echo __('Title', 'wpdirectorykit'); ?>*</label></th>
                                        <td><input name="post_title" type="text" id="post_title" value="<?php echo wmvc_show_data('post_title', $db_data, ''); ?>" placeholder="<?php echo esc_html__('Title', 'wpdirectorykit');?>" class="regular-text"></td>
                                    </tr>
                                    <?php if(get_option('wdk_is_address_enabled', FALSE)): ?>
                                    <tr>
                                        <th scope="row"><label for="input_address"><?php echo __('Address', 'wpdirectorykit'); ?></label></th>
                                        <td>
                                        <input name="address" type="text" id="input_address" value="<?php echo wmvc_show_data('address', $db_data, ''); ?>" placeholder="<?php echo esc_html__('Address', 'wpdirectorykit');?>" class="regular-text">
                                        <p class="description" id="input_address-description"><?php echo __('After you enter address system will try to autodetect and pin location on map, then you can drag and drop pin on map to fine tune location','wpdirectorykit'); ?></p>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if(get_option('wdk_is_category_enabled', FALSE)): ?>
                                    <tr>
                                        <th scope="row"><label for="category_id"><?php echo __('Category', 'wpdirectorykit'); ?></label></th>
                                        <td >
                                            <?php
                                            echo wmvc_select_option('category_id', $categories, wmvc_show_data('category_id', $db_data, ''), "id='category_id'", __('Not Selected', 'wpdirectorykit'));
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php if(get_option('wdk_is_category_enabled', FALSE) && get_option('wdk_multi_categories_other_enable', FALSE)): ?>
                                    <tr>
                                        <th scope="row"><label for="listing_categories"><?php echo __('More Categories', 'wpdirectorykit'); ?></label></th>
                                        <td class="">
                                            <?php echo wdk_treefield_select_ajax ('listing_sub_categories[]', 'category_m', wmvc_show_data('listing_sub_categories', $db_data, '', TRUE, TRUE), 'category_title', 'idcategory', '', __('All Categories', 'wpdirectorykit'), '', 'data-limit="10"');?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>

                                    <?php if(get_option('wdk_is_location_enabled', FALSE)): ?>
                                    <tr>
                                        <th scope="row"><label for="location_id"><?php echo __('Location', 'wpdirectorykit'); ?></label></th>
                                        <td>
                                            <?php
                                            echo wmvc_select_option('location_id', $locations, wmvc_show_data('location_id', $db_data, ''), "id='location_id'", __('Not Selected', 'wpdirectorykit'));
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>

                                    <?php if(get_option('wdk_is_location_enabled', FALSE) && get_option('wdk_multi_locations_other_enable', FALSE)): ?>
                                    <tr>
                                        <th scope="row"><label for="listing_agents"><?php echo __('More Locations', 'wpdirectorykit'); ?></label></th>
                                        <td class="">
                                            <?php echo wdk_treefield_select_ajax ('listing_sub_locations[]', 'location_m', wmvc_show_data('listing_sub_locations', $db_data, '', TRUE, TRUE), 'location_title', 'idlocation', '', __('All Locations', 'wpdirectorykit'), '', 'data-limit="10"');?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th scope="row"><label for="user_id_editor"><?php echo __('Agent Editor', 'wpdirectorykit'); ?></label></th>
                                        <td>
                                            <div class="wdk-field-edit edittable">
                                                <div class="wdk-field-container">
                                                    <?php echo wdk_treefield_option('user_id_editor', 'user_m', wmvc_show_data('user_id_editor', $db_data, ''), 'display_name', '', __('Not Selected', 'wpdirectorykit'));?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php if(function_exists('run_wdk_membership')): ?>
                                    <tr>
                                        <th scope="row"><label for="listing_agents"><?php echo __('Alternative Agents', 'wpdirectorykit'); ?></label></th>
                                        <td class="">
                                            <?php echo wdk_user_select_ajax ('listing_agents[]', array_keys( wmvc_show_data('listing_agents', $db_data, '', TRUE, TRUE)), __('Add Agents', 'wpdirectorykit'));?>
                                        </td>
                                        <?php if(false):?>
                                        <td class="agents_group">
                                            <?php echo wdk_select_multi_option('listing_agents[]',  wmvc_show_data('listing_agents', $db_data, '', TRUE, TRUE), array_keys( wmvc_show_data('listing_agents', $db_data, '', TRUE, TRUE)), "id='listing_agents'");?>
                                            <div class="agent_add form-inline">
                                                <div class="wdk-field-edit">
                                                    <div class="wdk-field-container">
                                                        <?php echo wdk_treefield_option('agent_id', 'user_m', '', 'display_name', '', __('Not Selected', 'wpdirectorykit'));?>
                                                    </div>
                                                </div>
                                                <button type="button" class="button button-primary add_button"><?php echo __('Add agent', 'wpdirectorykit'); ?></button>
                                                <button type="button" title="<?php echo __('Remove latest on list', 'wpdirectorykit'); ?>" class="button button-secondary rem_button"><?php echo __('X', 'wpdirectorykit'); ?></button>
                                            </div>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if(wdk_get_option('wdk_membership_is_enable_subscriptions') && function_exists('run_wdk_membership')):?>
                                    <tr>
                                        <th scope="row"><label for="subscription_id"><?php echo __('Membership Subscription', 'wpdirectorykit'); ?></label></th>
                                        <td>
                                            <?php
                                            echo wmvc_select_option('subscription_id', $subscriptions, wmvc_show_data('subscription_id', $db_data, ''), "id='subscription_id'", __('Not Selected', 'wpdirectorykit'));
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endif;?>

                                    <?php if(function_exists('run_wdk_payments') && isset($packages)):?>
                                    <tr>
                                        <th scope="row"><label for="packages"><?php echo __('Package', 'wpdirectorykit'); ?></label></th>
                                        <td>
                                            <?php
                                            echo wmvc_select_option('package_id', $packages, wmvc_show_data('package_id', $db_data, ''), "id='packages'", __('Not Selected', 'wpdirectorykit'));
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endif;?>
                                    <tr>
                                        <th scope="row"><label for="rank"><?php echo __('Rank', 'wpdirectorykit'); ?></label></th>
                                        <td>
                                        <input <?php if(!wmvc_user_in_role('administrator')):?> readonly="readonly" <?php endif;?> name="rank" type="number" id="rank" value="<?php echo wmvc_show_data('rank', $db_data, ''); ?>" placeholder="<?php echo esc_html__('Rank', 'wpdirectorykit');?>" class="regular-text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="is_featured"><?php echo __('Is Featured', 'wpdirectorykit'); ?></label></th>
                                        <td>
                                            <input name="is_featured" type="checkbox" id="is_featured" value="1" <?php echo !empty(wmvc_show_data('is_featured', $db_data, ''))?'checked':''; ?>><label for="is_featured"><?php echo __('Make it featured','wpdirectorykit'); ?></label>
                                            <p class="description" id="is_featured-description"><?php echo __('Featured/Highlighted listing in results','wpdirectorykit'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="is_activated"><?php echo __('Is Activated', 'wpdirectorykit'); ?></label></th>
                                        <td>
                                            <input name="is_activated" type="checkbox" id="is_activated" value="1" <?php echo !empty(wmvc_show_data('is_activated', $db_data, ''))?'checked':''; ?>><label for="is_activated"><?php echo __('Make it available for public','wpdirectorykit'); ?></label>
                                            <p class="description" id="is_activated-description"><?php echo __('When listing is activated will be visible on frontend','wpdirectorykit'); ?></p>
                                        </td>
                                    </tr>
                                    <?php if(function_exists('run_wdk_membership')):?>
                                    <tr>
                                        <th scope="row"><label for="is_approved"><?php echo __('Is Approved', 'wpdirectorykit'); ?></label></th>
                                        <td>
                                            <input name="is_approved" type="checkbox" id="is_approved" value="1" <?php echo !empty(wmvc_show_data('is_approved', $db_data, ''))?'checked':''; ?>><label for="is_approved"><?php echo __('Make it approved for public','wpdirectorykit'); ?></label>
                                            <p class="description" id="is_approved-description"><?php echo __('When listing is approved will be visible on frontend','wpdirectorykit'); ?> (<?php echo __('only admin can approve','wpdirectorykit'); ?>)</p>
                                        </td>
                                    </tr>
                                    <?php endif;?>
                                    <tr class='hidden'>
                                        <th scope="row"><label for="location_id"><?php echo __('Address', 'wpdirectorykit'); ?></label></th>
                                        <td>
                                            <input name="lat" type="text" id="input_lat" value="<?php echo wmvc_show_data('lat', $db_data, ''); ?>" class="regular-text" placeholder="<?php echo esc_html__('lat', 'wpdirectorykit');?>">
                                            <input name="lng" type="text" id="input_lng" value="<?php echo wmvc_show_data('lng', $db_data, ''); ?>" class="regular-text" placeholder="<?php echo esc_html__('lng', 'wpdirectorykit');?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="slug"><?php echo __('Slug', 'wpdirectorykit'); ?></label></th>
                                        <td>
                                            <input <?php if(!wmvc_user_in_role('administrator')):?> readonly="readonly" <?php endif;?> name="slug" type="text" id="slug" value="<?php echo wmvc_show_data('post_name', $db_data, ''); ?>" placeholder="<?php echo esc_html__('Slug', 'wpdirectorykit');?>" class="regular-text">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="wdk-col sidebar">
                            <?php if(get_option('wdk_is_address_enabled', FALSE)): ?>
                                <div class="clearfix">
                                    <a href="#" class="button button-secondary alignright wdk_action_clear_gps_reset_map"><?php echo esc_html__('Clear Gps coordinates', 'wpdirectorykit');?></a>
                                </div>
                                <br/>
                                <div id="map" class="listing_edit_map"></div>
                                <br/>
                                <p class="alert alert-info"><?php echo esc_html__('Drag and drop pin to desired location','wpdirectorykit');?></p>
                            <?php endif;?>
                        </div>
                    </div>
                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th scope="row"><label for="post_content"><?php echo __('Content', 'wpdirectorykit'); ?>*</label></th>
                                <td><?php wp_editor(wmvc_show_data('post_content', $db_data, ''), 'post_content', array('media_buttons' => FALSE)); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="postbox listing_custom_fields" style="display: block;">
                <div class="inside wdk-row">
                    <?php if(count($fields) == 0): ?>
                        <div class="wdk-col-12">
                            <div class="alert alert-success mb0"><p><?php echo __('Fields doesn\'t exists','wpdirectorykit'); ?> <a href="<?php echo get_admin_url() . "admin.php?page=wdk_fields"; ?>" class="button button-primary" id="add_field_button"><?php echo __('Manage Fields','wpdirectorykit'); ?></a></p></div>
                        </div>
                    <?php endif; ?>

                    <?php echo wdk_generate_fields($fields, $db_data); ?>                   
                </div>
            </div>
        <?php if(!wdk_get_option('wdk_listing_plangs_documents_disable')):?>
            <div class="postbox" style="display: block;">
                <div class="postbox-header">
                    <h3><?php echo __('Listing plans and documents', 'wpdirectorykit'); ?></h3>
                </div>
                <div class="inside">
                    <p class="alert alert-info"><?php echo __('Drag and drop image to change order', 'wpdirectorykit'); ?></p>
                    <?php  
                        echo wdk_upload_multi_files('listing_plans_documents', wmvc_show_data('listing_plans_documents', $db_data, '')); 
                    ?>               
                </div>
            </div>
        <?php endif;?>
            <div class="postbox" style="display: block;">
                <div class="postbox-header">
                    <h3><?php echo __('Listing Images/Videos', 'wpdirectorykit'); ?></h3>
                </div>
                <div class="inside">
                    <p class="alert alert-info"><?php echo __('Drag and drop image to change order', 'wpdirectorykit'); ?></p>
                    <?php  
                        echo wmvc_upload_multiple('listing_images', wmvc_show_data('listing_images', $db_data, '')); 
                    ?>               
                </div>
            </div>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html__('Save Changes','wpdirectorykit'); ?>"> 
        </form>
    </div>

    <?php do_action('wpdirectorykit/admin/listing/edit/after_form', $db_data);?>
</div>
<?php
    wp_enqueue_style('leaflet');
    wp_enqueue_script('leaflet');
            
    wp_enqueue_style('wdk-notify');
    wp_enqueue_script('wdk-notify');
            
    wp_enqueue_style('jquery-confirm');
    wp_enqueue_script('jquery-confirm');
?>

<script>
    // Generate table
    jQuery(document).ready(function($) {

        <?php if(get_option('wdk_is_address_enabled', FALSE)): ?>
            var wdk_edit_map_marker,wdk_timerMap,wdk_edit_map;
            wdk_edit_map = L.map('map', {
                center: [<?php echo (wmvc_show_data('lat', $db_data) ?: get_option('wdk_default_lat', 51.505)); ?>, <?php echo (wmvc_show_data('lng', $db_data) ?: get_option('wdk_default_lng', -0.09)); ?>],
                zoom: 4,
            });   

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(wdk_edit_map);

            wdk_edit_map_marker = L.marker(
                [<?php echo (wmvc_show_data('lat', $db_data) ?: get_option('wdk_default_lat', 51.505)); ?>, <?php echo (wmvc_show_data('lng', $db_data) ?: get_option('wdk_default_lng', -0.09)); ?>],
                {draggable: true}
            ).addTo(wdk_edit_map);

            wdk_edit_map_marker.on('dragend', function(event){
                clearTimeout(wdk_timerMap);
                var marker = event.target;
                var {lat,lng} = marker.getLatLng();
                $('#input_lat').val(lat);
                $('#input_lng').val(lng);
                //retrieved the position
            });

            /* reset map and gps */
            $('.wdk_action_clear_gps_reset_map').on('click', function(e){
                e.preventDefault();
                $('#input_lat,#input_lng').val('');

                /* move marker on default poisition */
                wdk_edit_map_marker.setLatLng([<?php echo esc_js(get_option('wdk_default_lat', 51.505)); ?>,  <?php echo esc_js(get_option('wdk_default_lng', -0.09)); ?>]).update(); 
                wdk_edit_map.panTo(new L.LatLng(<?php echo esc_js(get_option('wdk_default_lat', 51.505)); ?>,  <?php echo esc_js(get_option('wdk_default_lng', -0.09)); ?>));
            });

            $('#input_address').on('change keyup', function (e) {
                clearTimeout(wdk_timerMap);
                wdk_timerMap = setTimeout(function () {
                    $.get('https://nominatim.openstreetmap.org/search?format=json&q='+$('#input_address').val(), function(data){
                        if(data.length && typeof data[0]) {
                            var {lat,lon} =data[0];
                            wdk_edit_map_marker.setLatLng([lat, lon]).update(); 
                            wdk_edit_map.panTo(new L.LatLng(lat, lon));
                            $('#input_lat').val(lat);
                            $('#input_lng').val(lon);
                        } else {
                            wdk_log_notify('<?php echo esc_js(__('Address not found', 'wpdirectorykit')); ?>', 'error');
                            return;
                        }
                    });
                }, 1000);
            });

            $('#input_gps').on('change keyup', function (e) {
                wdk_edit_map.panTo(new L.LatLng($('#input_lat').val(), $('#input_lng').val()));
                wdk_edit_map_marker.setLatLng([parseFloat($('#input_lat').val()), parseFloat($('#input_lng').val())]).update(); 
            })
        <?php endif;?>

        /* agents */
                        
        $('.agents_group .add_button').on( "click", function(e) {
            e.preventDefault();
            var group_agent = $(this).closest('.agents_group');
            var agent_id = group_agent.find('input[name="agent_id"]').val();
        
            if(agent_id != '')
            {
                var exists = 0 != group_agent.find('#listing_agents').find('option[value='+agent_id+']').length;
                var agent_name =  group_agent.find('.wdk_dropdown_tree .btn-group .btn:first').text();
                
                if(!exists)
                {
                    group_agent.find('#listing_agents').append('<option value="'+agent_id+'" selected>'+agent_name+'</option>');
                }
                else
                {
                    wdk_log_notify('<?php echo esc_js(__('Already on list', 'wpdirectorykit')); ?>', 'error');
                }
            }   
            else
            {
                wdk_log_notify('<?php echo esc_js(__('Not selected', 'wpdirectorykit')); ?>', 'error');
            }
        });

        $('.agents_group .rem_button').on( "click", function() {
            $(this).closest('.agents_group').find('#listing_agents option:selected:last').remove();
        });

                
        $('form.form_listing').on('submit', function(e){
            if($(this).find('#listing_agents').length) {
                $('#listing_agents').find('option').prop("selected", true).trigger('change')  
            } 
        });
        
    });
</script>

<style>

.form-table .agent_add.form-inline .wdk-field-edit {
  display: inline-block;
}

.form-table .agent_add.form-inline .wdk-field-edit .wdk-field-container {
    padding: 0;
}

.form-table .agent_add.form-inline .wdk-field-edit,
.form-table .agent_add.form-inline .button  {
  margin: 15px 0px;
}

.form-table #listing_agents {
    max-width: 100%;
    width: 25em;
}

.form-table .agent_add.form-inline .wdk_dropdown_tree {
    width: 230px;
    max-width: 100%;
}
    
</style>

<?php $this->view('general/footer', $data); ?>