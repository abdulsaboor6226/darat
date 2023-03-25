<?php
/**
 * Theme updater admin page and functions.
 */

class CT_Theme_Updater_Admin {

	/**
	 * Variables required for the theme updater
	 *
	 * @since 1.0.0
	 * @type string
	 */
	 protected $remote_api_url = null;
	 protected $theme_slug = null;
	 protected $version = null;
	 protected $author = null;
	 protected $download_id = null;
	 protected $renew_url = null;
	 protected $strings = null;

	 private static $instance = null;

	 public static function getInstance(){
		if(!isset(self::$instance)){
			self::$instance = new CT_Theme_Updater_Admin();
  		}
  		return self::$instance; 
   	}

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		$config = wp_parse_args(
			array(
				'remote_api_url' => 'https://contempothemes.com/', // Site where EDD is hosted
				'item_name'      => 'WP Pro Real Estate 7', // Name of theme
				'theme_slug'     => 'realestate-7', // Theme slug
				'version'        => REALESTATE7_SL_THEME_VERSION, // The current version of this theme
				'author'         => 'Contempo Themes', // The author of this theme
				// 'license'    => '', 
				'download_id'    => '', // Optional, used for generating a license renewal link
				'renew_url'      => 'https://contempothemes.com/checkout/?edd_action=add_to_cart&download_id=9797', // Optional, allows for a custom license renewal link
				'beta'           => false, // Optional, set to true to opt into beta versions
				'item_id'        => '8734',
			),
			array(
				'remote_api_url' => 'http://easydigitaldownloads.com',
				'theme_slug'     => get_template(),
				'item_name'      => '',
				'license'        => '',
				'version'        => '',
				'author'         => '',
				'download_id'    => '',
				'renew_url'      => '',
				'beta'           => false,
				'item_id'        => '',
			)
		);

		$strings = array(
			'theme-license'             => __( 'Theme License', 'contempo' ),
			'enter-key'                 => __( 'Enter your purchase code.', 'contempo' ),
			'license-key'               => __( 'License Key', 'contempo' ),
			'license-action'            => __( 'License Action', 'contempo' ),
			'deactivate-license'        => __( 'Deactivate License', 'contempo' ),
			'activate-license'          => __( 'Activate License', 'contempo' ),
			'status-unknown'            => __( 'License status is unknown.', 'contempo' ),
			'renew'                     => __( 'Upgrade to', 'contempo' ),
			'unlimited'                 => __( 'unlimited', 'contempo' ),
			'license-key-is-active'     => __( 'License key is active.', 'contempo' ),
			'expires%s'                 => __( 'Expires %s.', 'contempo' ),
			'expires-never'             => __( 'Lifetime License.', 'contempo' ),
			'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'contempo' ),
			'license-key-expired-%s'    => __( 'License key expired %s.', 'contempo' ),
			'license-key-expired'       => __( 'License key has expired.', 'contempo' ),
			'license-keys-do-not-match' => __( 'License keys do not match.', 'contempo' ),
			'license-is-inactive'       => __( 'License is inactive.', 'contempo' ),
			'license-key-is-disabled'   => __( 'License key is disabled.', 'contempo' ),
			'site-is-inactive'          => __( 'Site is inactive.', 'contempo' ),
			'license-status-unknown'    => __( 'License status is unknown.', 'contempo' ),
			'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'contempo' ),
			'update-available'          => __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'contempo' ),
		);

		/**
		 * Fires after the theme $config is setup.
		 *
		 * @since x.x.x
		 *
		 * @param array $config Array of EDD SL theme data.
		 */
		do_action( 'post_edd_sl_theme_updater_setup', $config );

		// Set config arguments
		$this->remote_api_url = $config['remote_api_url'];
		$this->item_name      = $config['item_name'];
		$this->theme_slug     = sanitize_key( $config['theme_slug'] );
		$this->version        = $config['version'];
		$this->author         = $config['author'];
		$this->download_id    = $config['download_id'];
		$this->renew_url      = $config['renew_url'];
		$this->beta           = $config['beta'];
		$this->item_id        = $config['item_id'];

		// Populate version fallback
		if ( '' == $config['version'] ) {
			$theme = wp_get_theme( $this->theme_slug );
			$this->version = $theme->get( 'Version' );
		}

		// Strings passed in from the updater config
		$this->strings = $strings;

		add_action( 'init', array( $this, 'updater' ) );
		add_action( 'admin_init', array( $this, 'register_option' ) );
		add_action( 'admin_init', array( $this, 'license_action' ) );
		add_action( 'admin_menu', array( $this, 'license_menu' ) );
		// Make theme check happy.
		add_action( 'admin_' . 'bar_menu', array( $this, 'adminbar_menu' ), 40 );
		add_action( 'update_option_' . $this->theme_slug . '_license_key', array( $this, 'activate_license' ), 10, 2 );
		add_filter( 'http_request_args', array( $this, 'disable_wporg_request' ), 5, 2 );

		add_filter( "pre_update_option_" . $this->theme_slug . '_license_key_status', array( $this, 'license_email' ), 10, 3 );

		if (!wp_next_scheduled('ct_theme_check_license')) {
      		wp_schedule_event(time(), 'hourly', 'ct_theme_check_license');
		}
		
		// if( isset($_REQUEST['check_license_cron'])) {
	    // 	$this->check_license_cron();
		// }

	    add_action('ct_theme_check_license', array( $this, 'check_license_cron' ));
	}

	public function check_and_send_email_notification() {		
		try {
			if( $this->get_license_download_id() === 9797 ) {
				ct_license_expire_email_admin();
			}
		} catch(Exception $e) {
			// Sure, that we have no problems with remote request.
		}
	}

	public function license_email($value, $old_value, $option) {		
		if( in_array( $old_value, [ 'active','valid', false ], true ) && in_array( $value, [ 'site_inactive', 'invalid', 'expired' ], true ) ) {
			$this->check_and_send_email_notification();
		}

		return $value;
	}

	/**
	 * Creates the updater class.
	 *
	 * since 1.0.0
	 */
	public function updater() {

		// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
		$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
		if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
			return;
		}

		/* If there is no valid license key status, don't allow updates. */
		if ( get_option( $this->theme_slug . '_license_key_status', false) != 'valid' ) {
			return;
		}

		if ( !class_exists( 'CT_Theme_Updater' ) ) {
			// Load our custom theme updater
			include( dirname( __FILE__ ) . '/theme-updater-class.php' );
		}

		new CT_Theme_Updater(
			array(
				'remote_api_url' => $this->remote_api_url,
				'version'        => $this->version,
				'license'        => trim( get_option( $this->theme_slug . '_license_key' ) ),
				'item_name'      => $this->item_name,
				'author'         => $this->author,
				'beta'           => $this->beta,
				'item_id'        => $this->item_id,
				'theme_slug'     => $this->theme_slug,
			),
			$this->strings
		);
	}

	function adminbar_menu($wp_admin_bar){
		if ( !(get_option( $this->theme_slug . '_license_key_status', false) != 'valid') ) {
			return;
		}

    $args = array(
        'id' => 'theme-license',
        'title' => 'Theme License',
        'href' => admin_url( 'themes.php?page=' . $this->theme_slug . '-license' ),
        
    );
    $wp_admin_bar->add_node($args);
}

	/**
	 * Adds a menu item for the theme license under the appearance menu.
	 *
	 * since 1.0.0
	 */
	function license_menu() {

		$strings = $this->strings;

		add_theme_page(
			$strings['theme-license'],
			$strings['theme-license'],
			'manage_options',
			$this->theme_slug . '-license',
			array( $this, 'license_page' )
		);
	}

	/**
	 * Outputs the markup used on the theme license page.
	 *
	 * since 1.0.0
	 */
	function license_page() {

		$strings = $this->strings;

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		$status = get_option( $this->theme_slug . '_license_key_status', false );

		// Checks license status to display under license key
		if ( ! $license ) {
			$message    = $strings['enter-key'];
		} else {
			//delete_transient( $this->theme_slug . '_license_message' );
			if ( ! get_transient( $this->theme_slug . '_license_message', false ) ) {
				set_transient( $this->theme_slug . '_license_message', $this->check_license(), ( 60 * 60 * 24 ) );
			}
			$message = get_transient( $this->theme_slug . '_license_message' );
		}
		?>
		<div class="wrap">
			<h2><?php echo esc_html($strings['theme-license']); ?></h2>
			<form method="post" action="options.php">

				<?php settings_fields( $this->theme_slug . '-license' ); ?>

				<table class="form-table">
					<tbody>

						<tr valign="top">
							<th scope="row" valign="top">
								<?php echo esc_html($strings['license-key']); ?>
							</th>
							<td>
								<input id="<?php echo esc_html($this->theme_slug); ?>_license_key" name="<?php echo esc_html($this->theme_slug); ?>_license_key" type="text" class="regular-text" value="<?php echo esc_attr( $license ); ?>" />
								<p class="description">
									<?php 
									$ct_allowed_html = array(
									    'a' => array(
									        'href' => array(),
									        'target' => array(),
									        'title' => array()
									    ),
									    'br' => array(),
									    'em' => array(),
									    'strong' => array(),
									);
									echo wp_kses($message, $ct_allowed_html); ?>
									<?php if($status != 'expired'){ ?>
										<p><a href="https://contempothemes.com/docs/installation-setup-wizard/#2-toc-title" target="_blank"><?php _e('Where can I find my license key?', 'contempo'); ?></a></p>
									<?php } ?>
								</p>
							</td>
						</tr>

						<?php if ( $license ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php echo esc_html($strings['license-action']); ?>
							</th>
							<td>
								<?php
								wp_nonce_field( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' );
								if ( 'valid' == $status ) { ?>
									<input type="submit" class="button-secondary" name="<?php echo esc_html($this->theme_slug); ?>_license_deactivate" value="<?php esc_attr_e( $strings['deactivate-license'], 'contempo' ); ?>"/>
								<?php } else { ?>
									<input type="submit" class="button-secondary" name="<?php echo esc_html($this->theme_slug); ?>_license_activate" value="<?php esc_attr_e( $strings['activate-license'], 'contempo' ); ?>"/>
								<?php }
								?>
							</td>
						</tr>
						<?php } ?>

					</tbody>
				</table>
				<?php submit_button(); ?>
			</form>
		<?php
	}

	/**
	 * Registers the option used to store the license key in the options table.
	 *
	 * since 1.0.0
	 */
	function register_option() {
		register_setting(
			$this->theme_slug . '-license',
			$this->theme_slug . '_license_key',
			array( $this, 'sanitize_license' )
		);
	}

	/**
	 * Sanitizes the license key.
	 *
	 * since 1.0.0
	 *
	 * @param string $new License key that was submitted.
	 * @return string $new Sanitized license key.
	 */
	function sanitize_license( $new ) {

		$old = get_option( $this->theme_slug . '_license_key' );

		if ( $old && $old != $new ) {
			// New license has been entered, so must reactivate
			delete_option( $this->theme_slug . '_license_key_status' );
			delete_transient( $this->theme_slug . '_license_message' );
		}

		return $new;
	}

	/**
	 * Makes a call to the API.
	 *
	 * @since 1.0.0
	 *
	 * @param array $api_params to be used for wp_remote_get.
	 * @return array $response decoded JSON response.
	 */
	 function get_api_response( $api_params ) {

		// Call the custom API.
		$verify_ssl = (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true );
		$response   = wp_remote_post( $this->remote_api_url, array( 'timeout' => 15, 'sslverify' => $verify_ssl, 'body' => $api_params ) );

		return $response;
	 }

	/**
	 * Activates the license key.
	 *
	 * @since 1.0.0
	 */
	function activate_license() {

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );

		// Data to send in our API request.
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( $this->item_name ),
			'url'        => home_url(),
			'item_id'    => $this->item_id,
		);

		$response = $this->get_api_response( $api_params );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'contempo' );
			}

			$base_url = admin_url( 'themes.php?page=' . $this->theme_slug . '-license' );
			$redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {
				if( in_array( $license_data->error, [ 'expired' , 'no_activations_left', 'disabled', 'revoked', 'missing' , 'invalid', 'site_inactive' ], true ) ) {
					$this->check_and_send_email_notification();
				}
				switch( $license_data->error ) {

					case 'expired' :

						$message = sprintf(
							__( 'Your license key expired on %s. ', 'contempo' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'disabled':
					case 'revoked' :

						$message = __( 'Your license key has been disabled.', 'contempo' );
						break;

					case 'missing' :

						$message = __( 'Invalid license.', 'contempo' );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$message = __( 'Your license is not active for this URL.', 'contempo' );
						break;

					case 'item_name_mismatch' :

						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'contempo' ), $this->item_name );
						break;

					case 'no_activations_left':

						$message = __( 'Your license key has reached its activation limit.', 'contempo' );
						break;

					default :

						$message = __( 'An error occurred, please try again.', 'contempo' );
						break;
				}

				if ( ! empty( $message ) ) {
					$base_url = admin_url( 'themes.php?page=' . $this->theme_slug . '-license' );
					$redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

					wp_redirect( $redirect );
					exit();
				}

			}

		}

		// $response->license will be either "active" or "inactive"
		if ( $license_data && isset( $license_data->license ) ) {
			update_option( $this->theme_slug . '_license_key_status', $license_data->license );
			
			if($license_data->license == 'valid') {
				if ( function_exists('envato_market') ) {
          			deactivate_plugins('envato-market/envato-market.php');    
        		}
			}

			delete_transient( $this->theme_slug . '_license_message' );
		}

		wp_redirect( admin_url( 'themes.php?page=' . $this->theme_slug . '-license' ) );
		exit();

	}

	/**
	 * Deactivates the license key.
	 *
	 * @since 1.0.0
	 */
	function deactivate_license() {

		// Retrieve the license from the database.
		$license = trim( get_option( $this->theme_slug . '_license_key' ) );

		// Data to send in our API request.
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => rawurlencode( $this->item_name ),
			'url'        => home_url(),
			'item_id'    => $this->item_id,
		);

		$response = $this->get_api_response( $api_params );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'contempo' );
			}

			$base_url = admin_url( 'themes.php?page=' . $this->theme_slug . '-license' );
			$redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if ( $license_data && ( $license_data->license == 'deactivated' ) ) {
				delete_option( $this->theme_slug . '_license_key_status' );
				delete_transient( $this->theme_slug . '_license_message' );
			}

		}

		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'themes.php?page=' . $this->theme_slug . '-license' );
			$redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		wp_redirect( admin_url( 'themes.php?page=' . $this->theme_slug . '-license' ) );
		exit();

	}

	/**
	 * Constructs a renewal link
	 *
	 * @since 1.0.0
	 */
	function get_renewal_link() {

		// If a renewal link was passed in the config, use that
		if ( '' != $this->renew_url ) {
			return $this->renew_url;
		}

		// If download_id was passed in the config, a renewal link can be constructed
		$license_key = trim( get_option( $this->theme_slug . '_license_key', false ) );
		if ( '' != $this->download_id && $license_key ) {
			$url = esc_url( $this->remote_api_url );
			$url .= '/checkout/?edd_license_key=' . $license_key . '&download_id=' . $this->download_id;
			return $url;
		}

		// Otherwise return the remote_api_url
		return $this->remote_api_url;

	}



	/**
	 * Checks if a license action was submitted.
	 *
	 * @since 1.0.0
	 */
	function license_action() {

		if ( isset( $_POST[ $this->theme_slug . '_license_activate' ] ) ) {
			if ( check_admin_referer( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' ) ) {
				$this->activate_license();
			}
		}

		if ( isset( $_POST[$this->theme_slug . '_license_deactivate'] ) ) {
			if ( check_admin_referer( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' ) ) {
				$this->deactivate_license();
			}
		}

	}

	public function check_license_cron() {
		set_transient( $this->theme_slug . '_license_message', $this->check_license(), ( 60 * 60 * 24 ) );
	}

	function get_license_download_id() {

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		
		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_name'  => rawurlencode( $this->item_name ),
			'url'        => home_url(),
			'item_id'    => $this->item_id,
		);

		$response = $this->get_api_response( $api_params );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			return $license_data->license_id;
		}
	}

	function get_license_item_id() {

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		
		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_name'  => rawurlencode( $this->item_name ),
			'url'        => home_url(),
			'item_id'    => $this->item_id,
		);

		$response = $this->get_api_response( $api_params );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if($license_data->license == 'valid') {
				return $license_data->license_id;
			}
		}
	}

	/**
	 * Checks if license is valid and gets expire date.
	 *
	 * @since 1.0.0
	 *
	 * @return string $message License status message.
	 */
	function check_license() {

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		$strings = $this->strings;

		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_name'  => rawurlencode( $this->item_name ),
			'url'        => home_url(),
			'item_id'    => $this->item_id,
		);

		$response = $this->get_api_response( $api_params );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = $strings['license-status-unknown'];
			}

			$base_url = admin_url( 'themes.php?page=' . $this->theme_slug . '-license' );
			$redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// If response doesn't include license data, return
			if ( !isset( $license_data->license ) ) {
				$message = $strings['license-status-unknown'];
				return $message;
			}

			// We need to update the license status at the same time the message is updated
			if ( $license_data && isset( $license_data->license ) ) {
				update_option( $this->theme_slug . '_license_key_status', $license_data->license );
			}

			// Get expire date
			$expires = false;
			if ( isset( $license_data->expires ) && 'lifetime' != $license_data->expires ) {
				$expires = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) );
				$renew_link = 'Upgrade to <a href="' . esc_url( $this->get_renewal_link() ) . '" target="_blank">Real Estate 7 Yearly</a>.<br>';
			} elseif ( isset( $license_data->expires ) && 'lifetime' == $license_data->expires ) {
				$expires = 'lifetime';
			}

			// Get site counts
			$site_count = $license_data->site_count;
			$license_limit = $license_data->license_limit;

			// If unlimited
			if ( 0 == $license_limit ) {
				$license_limit = $strings['unlimited'];
			}

			if ( $license_data->license == 'valid' ) {
				$message = $strings['license-key-is-active'] . ' ';
				if ( isset( $expires ) && 'lifetime' != $expires ) {
					$message .= sprintf( $strings['expires%s'], $expires ) . ' ';
				}
				if ( isset( $expires ) && 'lifetime' == $expires ) {
					$message .= $strings['expires-never'];
				}
				if ( $site_count && $license_limit ) {
					$message .= ' ' . sprintf( $strings['%1$s/%2$-sites'], $site_count, $license_limit );
				}
			} else if ( $license_data->license == 'expired' ) {
				if ( $expires ) {
					$message = sprintf( $strings['license-key-expired-%s'], $expires );
				} else {
					$message = $strings['license-key-expired'];
				}
				if ( $renew_link ) {
					$message .= ' ' . $renew_link;
				}
			} else if ( $license_data->license == 'invalid' ) {
				$message = $strings['license-keys-do-not-match'];
			} else if ( $license_data->license == 'inactive' ) {
				$message = $strings['license-is-inactive'];
			} else if ( $license_data->license == 'disabled' ) {
				$message = $strings['license-key-is-disabled'];
			} else if ( $license_data->license == 'site_inactive' ) {
				// Site is inactive
				$message = $strings['site-is-inactive'];
			} else {
				$message = $strings['license-status-unknown'];
			}

		}

		return $message;
	}

	/**
	 * Disable requests to wp.org repository for this theme.
	 *
	 * @since 1.0.0
	 */
	function disable_wporg_request( $r, $url ) {

		// If it's not a theme update request, bail.
		if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
 			return $r;
 		}

 		// Decode the JSON response
 		$themes = json_decode( $r['body']['themes'] );

 		// Remove the active parent and child themes from the check
 		$parent = get_option( 'template' );
 		$child = get_option( 'stylesheet' );
 		unset( $themes->themes->$parent );
 		unset( $themes->themes->$child );

 		// Encode the updated JSON response
 		$r['body']['themes'] = json_encode( $themes );

 		return $r;
	}

}

/*-----------------------------------------------------------------------------------*/
/* License Expire Admin Notification Email */
/*-----------------------------------------------------------------------------------*/

function ct_license_expire_email_admin() {

	$admin_email = get_option( 'admin_email' );

	if ( $admin_email ) {
		$blogname = get_option( 'blogname' );

		$parse = parse_url( get_site_url() );
		$host = $parse['host'];

		$to = $admin_email;
		$headers = [];
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-Type: text/html; charset=utf-8';
		$headers[] = 'From: ' . $blogname . ' <wordpress@' . $host . '>';
		$headers[] = 'Reply-To: <noreply@' . $host . '>';

		$subject = sprintf( 'Real Estate 7 License Expired on %1$s (%2$s) — Features Disabled (Action Required)', $blogname, get_site_url() );

		$body = __( 'Hi,', 'contempo' ) . '<br/><br/>';

		$body .= sprintf( 'Your license for Real Estate 7 Yearly has expired for %1$s, and exclusive features on your website have been disabled:', get_site_url() ) . '<br/>';
		$body .= '<ul>';
			$body .= '<li>200+ Blocks & Pages Library for Elementor is disabled</li>';
			if(function_exists('ct_lp_plugins_loaded')) {
				$body .= '<li>CT Leads Pro CRM & Lead Capture are disabled</li>';
				$body .= '<li>Lead Assigned Alerts are disabled</li>';
			}
			if(function_exists('ctsa_process_saved_alert')) {
				$body .= '<li>SMS Alerts are disabled</li>';
			}
			if(class_exists('ctListingAnalytics')) {
				$body .= '<li>Listing Analytics are disabled</li>';
			}
			$body .= '<li>New features, Critical Updates, and Support are disabled</li>';
		$body .= '</ul>';
		$body .= '<br/>';
		$body .= 'To easily re-enable these features you\'ll need to reactivate your subscription to Real Estate 7 Yearly:';
		$body .= '<br/>';
		$body .= '<ol>';
			$body .= '<li>Login to your account via <a href="https://contempothemes.com/login/" target="_blank">https://contempothemes.com/login/</a></li>';
			$body .= '<li>Make sure your payment method is updated via <a href="https://contempothemes.com/account-dashboard/account-settings/" target="_blank">https://contempothemes.com/account-dashboard/account-settings/</a> > scroll to Manage Payment Methods</li>';
			$body .= '<li>Then go to Subscriptions or <a href="https://contempothemes.com/account-dashboard/subscriptions/" target="_blank">https://contempothemes.com/account-dashboard/subscriptions/</a></li>';
			$body .= '<li>Find your Real Estate 7 Yearly subscription and click "Activate"</li>';
		$body .= '</ol>';
		$body .= '<br/>';
		$body .= '<a class="btn" style="background: #007AFF; color: #ffffff !important; border-radius: 3px; padding: 10px 16px; margin: 5px 0; display: inline-block; text-decoration: none;" href="https://contempothemes.com/login/" target="_blank">Login to My Account<a/><br/><br/>';
		$body .= '<br/>';
		$body .= 'Questions? Please open a live chat on our <a href="https://contempothemes.com/" target="_blank">website</a> and one of our agents will be happy to assist.';
		$body .= '<br/><br/>';

		$body .= '<div style="color:grey;">' . sprintf( 'This notification was generated from %s', get_site_url() ) . '</div>';

		wp_mail( $to, $subject, $body, $headers );
	}
}

/**
 * This is a means of catching errors from the activation method above and displyaing it to the customer
 */
function ct_theme_admin_notices() {
	if ( isset( $_GET['sl_theme_activation'] ) && ! empty( $_GET['message'] ) ) {

		switch( $_GET['sl_theme_activation'] ) {

			case 'false':
				$message = urldecode( $_GET['message'] );
				?>
				<div class="error">
					<p><?php echo esc_html($message); ?></p>
				</div>
				<?php
				break;

			case 'true':
			default:

				break;

		}
	}
}
add_action( 'admin_notices', 'ct_theme_admin_notices' );

CT_Theme_Updater_Admin::getInstance();
