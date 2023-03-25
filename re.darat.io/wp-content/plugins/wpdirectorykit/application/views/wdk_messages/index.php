<?php
/**
 * The template for Messages Management.
 *
 * This is the template that table, search layout
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap wdk-wrap">

    <h1 class="wp-heading-inline"><?php echo __('Messages Management', 'wpdirectorykit'); ?></h1>
    <br /><br />

    <table class="wp-list-table widefat fixed striped table-view-list pages">
        <thead>
            <tr>
                <th style="width:50px;"><?php echo __('#ID', 'wpdirectorykit'); ?></th>
                <th><?php echo __('Email', 'wpdirectorykit'); ?></th>
                <th><?php echo __('Date', 'wpdirectorykit'); ?></th>
                <th><?php echo __('Message', 'wpdirectorykit'); ?></th>
                <th class="actions_column"><?php echo __('Actions', 'wpdirectorykit'); ?></th>
            </tr>
        </thead>
        <?php if (count($messages) == 0) : ?>
            <tr class="no-items">
                <td class="colspanchange" colspan="5"><?php echo __('No Messages found.', 'wpdirectorykit'); ?></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($messages as $item) : ?>
            <tr>
                <td>
                    <?php echo wmvc_show_data('idmessage', $item, '-'); ?>
                </td>
                <td>
                    <?php echo wmvc_show_data('email_sender', $item, '-'); ?>
                </td>
                <td>
                    <?php echo wdk_get_date(wmvc_show_data('message_date', $item), false); ?>
                </td>
                <td>
                    <?php echo wp_trim_words(wmvc_show_data('message', $item, '-'), 10); ?>
                </td>
                <td class="actions_column">
                    <a href="<?php echo get_admin_url() . "admin.php?page=wdk_messages&function=edit&id=" . wmvc_show_data('idmessage', $item, '-'); ?>"><span class="dashicons dashicons-edit"></span></a>
                    <a class="question_sure" href="<?php echo get_admin_url() . "admin.php?page=wdk&function=delete&paged=".esc_attr($paged)."&id=" . wmvc_show_data('idmessage', $item, '-'); ?>"><span class="dashicons dashicons-no"></span></a>
                </td>
            </tr>
        <?php endforeach; ?>
        <tfoot>
            <tr>
                <th style="width:50px;"><?php echo __('#ID', 'wpdirectorykit'); ?></th>
                <th><?php echo __('Email', 'wpdirectorykit'); ?></th>
                <th><?php echo __('Date', 'wpdirectorykit'); ?></th>
                <th><?php echo __('Message', 'wpdirectorykit'); ?></th>
                <th class="actions_column"><?php echo __('Actions', 'wpdirectorykit'); ?></th>
            </tr>
        </tfoot>
    </table>
    <div class="tablenav bottom">
        <div class="alignleft actions">
        </div>
        <?php echo wmvc_xss_clean($pagination_output); ?>
        <br class="clear">
    </div>
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