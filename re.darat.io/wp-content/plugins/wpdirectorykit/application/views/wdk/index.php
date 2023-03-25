<?php
/**
 * The template for Listings Management.
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
    <h1 class="wp-heading-inline"><?php echo __('Listings Management', 'wpdirectorykit'); ?> <a href="<?php echo get_admin_url() . "admin.php?page=wdk_listing"; ?>" class="button button-primary" id="add_listing_button"><?php echo __('Add Listing', 'wpdirectorykit'); ?></a></h1>
    <?php
    if(!get_option('wdk_listing_page') || get_post_status(get_option('wdk_listing_page')) !='publish'
        || !get_option('wdk_results_page') || get_post_status(get_option('wdk_results_page')) !='publish'
        ):?>
        <div  class="notice notice-success">
            <p>
                <?php echo __('Listing Preview page or Listings Result page missing','wpdirectorykit'); ?>
                <a href="<?php echo get_admin_url() . "admin.php?page=wdk_settings&function=import_demo"; ?>" class="button button-primary" id="reset_data_field_button">
                    <?php echo __('Import Demo Data','wpdirectorykit'); ?>
                </a>
            </p>
        </div>
    <?php endif;?>
    <form method="GET" action="<?php echo wmvc_current_edit_url(); ?>" novalidate="novalidate">
        <div class="tablenav top">
            <div class="alignleft actions">
                <input type="hidden" name="page" value="wdk" />

                <label class="screen-reader-text" for="location_id"><?php echo __('Filter by location', 'wpdirectorykit'); ?></label>
                <?php echo wmvc_select_option('location_id', $locations, wmvc_show_data('location_id', $db_data, ''), NULL, __('Location', 'wpdirectorykit')); ?>

                <label class="screen-reader-text" for="category_id"><?php echo __('Filter by category', 'wpdirectorykit'); ?></label>
                <?php echo wmvc_select_option('category_id', $categories, wmvc_show_data('category_id', $db_data, ''), NULL, __('Category', 'wpdirectorykit')); ?>

                <label class="screen-reader-text" for="user_id_editor"><?php echo esc_html__('Filter by user', 'wpdirectorykit'); ?></label>
                <?php echo wmvc_select_option('user_id_editor', $users, wmvc_show_data('user_id_editor', $db_data, ''), NULL, __('User', 'wpdirectorykit')); ?>

                <label class="screen-reader-text" for="search"><?php echo __('Filter by keyword', 'wpdirectorykit'); ?></label>
                <input type="text" name="search" id="search" class="postform left" value="<?php echo wmvc_show_data('search', $db_data, ''); ?>" placeholder="<?php echo __('Filter by keyword', 'wpdirectorykit'); ?>" />

                <label class="screen-reader-text" for="order_by"><?php echo __('Order By', 'wpdirectorykit'); ?></label>
                <?php echo wmvc_select_option('order_by', $order_by, wmvc_show_data('order_by', $db_data, ''), NULL, __('Order by', 'wpdirectorykit')); ?>

                <input type="submit" name="filter_action" id="post-query-submit" class="button" value="<?php echo __('Filter', 'wpdirectorykit'); ?>">
            </div>
            <?php echo wmvc_xss_clean($pagination_output); ?>
            <br class="clear">
        </div>
    </form>

    <?php 
    if (function_exists('PLL')){
        $pll_langs = pll_the_languages( array( 'raw' => 1 ) );
    } 
    ?>

    <form method="GET" action="<?php echo wmvc_current_edit_url(); ?>" novalidate="novalidate">
        <table class="wp-list-table widefat fixed striped table-view-list pages">
            <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1"><?php echo __('Select All', 'wpdirectorykit'); ?></label><input id="cb-select-all-1" type="checkbox"></td>
                    <th style="width:50px;"><?php echo __('#ID', 'wpdirectorykit'); ?></th>
                    <th><?php echo __('Title', 'wpdirectorykit'); ?></th>
                    <th><?php echo __('Category', 'wpdirectorykit'); ?></th>
                    <th style="text-align: center;"><?php echo __('Image', 'wpdirectorykit'); ?></th>
                    <th><?php echo __('Date', 'wpdirectorykit'); ?></th>
                    <?php if (function_exists('PLL')): ?>
                    <?php $pll_langs = pll_the_languages( array( 'raw' => 1 ) );
                          foreach($pll_langs as $pll_lang): ?>
                    <th class="manage-column column-language_<?php echo esc_attr($pll_lang['slug']); ?>"><img src="<?php echo esc_html($pll_lang['flag']); ?>" /></th>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <th class="actions_column"><?php echo __('Actions', 'wpdirectorykit'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($listings) == 0) : ?>
                    <tr class="no-items">
                        <td class="colspanchange" colspan="7"><?php echo __('No Listings found.', 'wpdirectorykit'); ?></td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($listings as $listing) : ?>
                    <tr>
                        <th scope="row" class="check-column">
                            <input id="cb-select-<?php echo wmvc_show_data('ID', $listing, '-'); ?>" type="checkbox" name="post[]" value="<?php echo wmvc_show_data('ID', $listing, '-'); ?>">
                            <div class="locked-indicator">
                                <span class="locked-indicator-icon" aria-hidden="true"></span>
                                <span class="screen-reader-text"><?php echo __('Is Locked', 'wpdirectorykit'); ?></span>
                            </div>
                        </th>
                        <td>
                            <?php echo wmvc_show_data('ID', $listing, '-'); ?>
                        </td>
                        <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                            <strong>
                                <a class="row-title" href="<?php echo get_admin_url() . "admin.php?page=wdk_listing&id=" . wmvc_show_data('ID', $listing, '-'); ?>"><?php echo wmvc_show_data('post_title', $listing, '-'); ?></a>
                                <?php if(!wmvc_show_data('is_activated', $listing, 0)): ?>
                                <span class="label label-danger"><?php echo __('Not activated', 'wpdirectorykit'); ?></span>
                                <?php endif; ?>
                                <?php if(!wmvc_show_data('is_approved', $listing, 0) && function_exists('run_wdk_membership')): ?>
                                <span class="label label-danger"><?php echo esc_html__('Not approved', 'wpdirectorykit'); ?></span>
                                <?php endif; ?>
                                <?php if(wmvc_show_data('is_featured', $listing, 0)): ?>
                                <span class="label label-info"><?php echo esc_html__('featured', 'wpdirectorykit'); ?></span>
                                <?php endif; ?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit"><a href="<?php echo get_admin_url() . "admin.php?page=wdk_listing&id=" . wmvc_show_data('ID', $listing, '-'); ?>"><?php echo __('Edit', 'wpdirectorykit'); ?></a> | </span>
                                <span class="trash "><a href="<?php echo get_admin_url() . "admin.php?page=wdk&function=delete&paged=".esc_attr($paged)."&id=" . wmvc_show_data('ID', $listing, '-'); ?>" class="submitdelete question_sure"><?php echo __('Delete', 'wpdirectorykit'); ?></a> | </span>
                                <span class="view"><a href="<?php echo get_permalink($listing); ?>" target="blank"><?php echo __('View', 'wpdirectorykit'); ?></a></span>
                            </div>
                        </td>

                        <td>

                            <?php echo wmvc_show_data($listing->category_id, $categories, '-'); ?>
                            <?php 
                                $other_categories = wdk_generate_other_categories_fast($listing->categories_list);

                                if(!empty($other_categories)):?>
                                    <br>
                                    <span style="display: inline-block;padding-top: 10px;" ><?php echo esc_html(join(', ',$other_categories));?></span>
                                <?php endif;?>
                        </td>
                        <td style="text-align: center;">
                            <a class="img-link" href="<?php echo get_admin_url() . "admin.php?page=wdk_listing&id=" . wmvc_show_data('ID', $listing, '-'); ?>">
                                <img src="<?php echo esc_url(wdk_image_src($listing));?>" alt="thumb" style="height:70px;width:110px;object-fit:cover;text-align: center;"/>
                            </a>
                        </td>
                        <td>
                            <?php echo wdk_get_date($listing->post_date, false); ?>
                        </td>
                        <?php if (function_exists('PLL')): ?>
                        <?php foreach($pll_langs as $pll_lang): ?>
                        <?php if($pll_lang['slug'] == pll_get_post_language($listing->post_id, 'slug' )): ?>
                        <td><img src="<?php echo esc_html($pll_lang['flag']); ?>" /></td>
                        <?php else: ?>
                        <td><a class="pll_icon_edit translation_<?php echo esc_attr($listing->post_id); ?>" href="<?php echo get_admin_url() . "admin.php?page=wdk_listing&id=" . pll_get_post( $listing->post_id, $pll_lang['slug'] ); ?>"></a></td>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <td class="actions_column">
                            <a href="<?php echo get_permalink($listing); ?>"  target="blank"><span class="dashicons dashicons-visibility"></span></a>
                            <a href="<?php echo get_admin_url() . "admin.php?page=wdk_listing&id=" . wmvc_show_data('ID', $listing, '-'); ?>"><span class="dashicons dashicons-edit"></span></a>
                            <a class="question_sure" href="<?php echo get_admin_url() . "admin.php?page=wdk&function=delete&paged=".esc_attr($paged)."&id=" . wmvc_show_data('ID', $listing, '-'); ?>"><span class="dashicons dashicons-no"></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>    
            <tfoot>
                <tr>
                    <td class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-2"><?php echo __('Select All', 'wpdirectorykit'); ?></label><input id="cb-select-all-2" type="checkbox"></td>
                    <th style="width:50px;"><?php echo __('#ID', 'wpdirectorykit'); ?></th>
                    <th><?php echo __('Title', 'wpdirectorykit'); ?></th>
                    <th><?php echo __('Category', 'wpdirectorykit'); ?></th>
                    <th style="text-align: center;"><?php echo __('Image', 'wpdirectorykit'); ?></th>
                    <th><?php echo __('Date', 'wpdirectorykit'); ?></th>
                    <?php if (function_exists('PLL')): ?>
                    <?php foreach($pll_langs as $pll_lang): ?>
                    <th><img src="<?php echo esc_html($pll_lang['flag']); ?>" /></th>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <th class="actions_column"><?php echo __('Actions', 'wpdirectorykit'); ?></th>
                </tr>
            </tfoot>
        </table>
        <div class="tablenav bottom">
            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-bottom" class="screen-reader-text"><?php echo __('Select bulk action', 'wpdirectorykit'); ?></label>
                <select name="action" id="bulk-action-selector-bottom">
                    <option value="-1"><?php echo __('Bulk actions', 'wpdirectorykit'); ?></option>
                    <option value="delete" class="hide-if-no-js"><?php echo __('Delete', 'wpdirectorykit'); ?></option>
                    <option value="deactivate" class="hide-if-no-js"><?php echo __('Deactivate', 'wpdirectorykit'); ?></option>
                    <option value="activate" class="hide-if-no-js"><?php echo __('Activate', 'wpdirectorykit'); ?></option>
                    <option value="deapprove" class="hide-if-no-js"><?php echo __('Deapprove', 'wpdirectorykit'); ?></option>
                    <option value="approve" class="hide-if-no-js"><?php echo __('Approve', 'wpdirectorykit'); ?></option>
                </select>
                <input type="hidden" name="page" value="wdk" />
                <input type="submit" id="table_action" class="button action" name="table_action" value="<?php echo esc_attr__('Apply', 'wpdirectorykit'); ?>">
            </div>

            <?php echo wmvc_xss_clean($pagination_output); ?>
            <br class="clear">
        </div>
    </form>
</div>

<script>
    // Generate table
    jQuery(document).ready(function($) {

        $('.question_sure').on('click', function() {
            return confirm("<?php echo esc_js(__('Are you sure? Selected item will be completely removed!', 'wpdirectorykit')); ?>");
        });

    });
</script>

<?php $this->view('general/footer', $data); ?>