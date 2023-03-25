<?php
/**
 * Theme updater class.
 *
 */

class CT_Theme_Updater {

	private $remote_api_url;
	private $request_data;
	private $response_key;
	private $theme_slug;
	private $license_key;
	private $version;
	private $author;
	protected $strings = null;


	/**
	 * Initiate the Theme updater
	 *
	 * @param array $args    Array of arguments from the theme requesting an update check
	 * @param array $strings Strings for the update process
	 */
	function __construct( $args = array(), $strings = array() ) {

		$defaults = array(
			'remote_api_url' => '',
			'request_data'   => array(),
			'theme_slug'     => get_template(), // use get_stylesheet() for child theme updates
			'item_name'      => '',
			'license'        => '',
			'version'        => '',
			'author'         => '',
			'beta'           => false,
			'item_id'        => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$this->license        = $args['license'];
		$this->item_name      = $args['item_name'];
		$this->version        = $args['version'];
		$this->theme_slug     = sanitize_key( $args['theme_slug'] );
		$this->author         = $args['author'];
		$this->beta           = $args['beta'];
		$this->remote_api_url = $args['remote_api_url'];
		$this->response_key   = $this->theme_slug . '-' . $this->beta . '-update-response';
		$this->strings        = $strings;
		$this->item_id        = $args['item_id'];

		add_filter( 'site_transient_update_themes',        array( $this, 'theme_update_transient' ) );
		add_filter( 'delete_site_transient_update_themes', array( $this, 'delete_theme_update_transient' ) );
		add_action( 'load-update-core.php',                array( $this, 'delete_theme_update_transient' ) );
		add_action( 'load-themes.php',                     array( $this, 'delete_theme_update_transient' ) );

	}

	/**
	 * Update the theme update transient with the response from the version check
	 *
	 * @param  array $value   The default update values.
	 * @return array|boolean  If an update is available, returns the update parameters, if no update is needed returns false, if
	 *                        the request fails returns false.
	 */
	public function theme_update_transient( $value ) {
		$update_data = $this->check_for_update();
		if ( $update_data ) {

			// Make sure the theme property is set. See issue 1463 on Github in the Software Licensing Repo.
			$update_data['theme'] = $this->theme_slug;

			if ( version_compare( $this->version, $update_data['new_version'], '<' ) ) {
				if( ! $value ) {
					$value = new stdClass();
				}

				if( ! isset( $value->response ) ) {
					$value->response = array();
				}
				
				$value->response[ $this->theme_slug ] = $update_data;
			} else {
				if( ! $value ) {
					$value = new stdClass();
				}

				if( ! isset( $value->no_update ) || ! is_array( $value->no_update ) ) {
					$value->no_update = array();
				}
				
				$value->no_update[ $this->theme_slug ] = $update_data;
			}
		}

		return $value;
	}

	/**
	 * Remove the update data for the theme
	 *
	 * @return void
	 */
	function delete_theme_update_transient() {
		delete_transient( $this->response_key );
	}

	/**
	 * Call the EDD SL API (using the URL in the construct) to get the latest version information
	 *
	 * @return array|boolean  If an update is available, returns the update parameters, if no update is needed returns false, if
	 *                        the request fails returns false.
	 */
	private function check_for_update() {

		$update_data = get_transient( $this->response_key );

		if ( false === $update_data ) {
			$failed = false;

			$api_params = array(
				'edd_action' => 'get_version',
				'license'    => $this->license,
				'name'       => $this->item_name,
				'slug'       => $this->theme_slug,
				'version'    => $this->version,
				'author'     => $this->author,
				'beta'       => $this->beta,
				'item_id'    => $this->item_id,
				'url'    	 => get_site_url(),
			);

			$response = wp_remote_post( $this->remote_api_url, array( 'timeout' => 15, 'body' => $api_params ) );

			// Make sure the response was successful
			if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
				$failed = true;
			}

			$update_data = json_decode( wp_remote_retrieve_body( $response ) );
			

			if ( ! is_object( $update_data ) ) {
				$failed = true;
			}

			// If the response failed, try again in 30 minutes
			if ( $failed ) {
				$data = new stdClass;
				$data->new_version = $this->version;
				set_transient( $this->response_key, $data, strtotime( '+30 minutes', time() ) );
				return false;
			}

			// If the status is 'ok', return the update arguments
			if ( ! $failed ) {
				$update_data->url = 'https://contempothemes.com/wp-real-estate-7/changelog/';
				$update_data->sections = maybe_unserialize( $update_data->sections );
				set_transient( $this->response_key, $update_data, strtotime( '+12 hours', time() ) );
			}
		}

		return (array) $update_data;
	}
}
