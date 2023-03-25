<?php


namespace CtElementorTemplate;

use Elementor\Plugin as ElementorPlugin;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;

define('CT_ET_URL', get_template_directory_uri() . '/admin/elementor-templates/');
define('CT_ET_VERSION', '1.1.0');

class Bootstrap {
  private $domain = 'https://contempothemes.com/wp-real-estate-7/';

  public function __construct() {
    add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'editor_enqueue_scripts' ], 0 );
    add_action( 'elementor/editor/footer', [ $this, 'add_editor_templates' ], 9 );
    add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );
  }

  public function editor_enqueue_scripts() {
    $key = 'ct_et';

		$is_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

    \wp_enqueue_script(
      $key,
      CT_ET_URL . '/build/' . 'editor.js',
      [ 'jquery' ],
      $is_debug ? time() : CT_ET_VERSION,
      false
    );

    \wp_enqueue_style(
      $key,
      CT_ET_URL . '/build/' . 'editor.css',
      [],
      $is_debug ? time() : CT_ET_VERSION
    );

    \wp_localize_script( $key, 'contempo_editor', [
      'has_license' => !ct_is_not_activated() && $this->get_license_key(),
      'translations' => [
        'The error message is empty' => __('The error message is empty', 'contempo'),
        'We’re having trouble connecting to the library, please refresh your browser or ' => __('We’re having trouble connecting to the library, please refresh your browser or ', 'contempo'),
        'click here' => __('click here', 'contempo'),
        'RE7 Blocks' => __('RE7 Blocks', 'contempo'),
        'RE7 Pages' => __('RE7 Pages', 'contempo'),
        'Real Estate 7 Library' => __('Real Estate 7 Library', 'contempo'),
      ]
    ] );
  }

  public function add_editor_templates() {
		ElementorPlugin::$instance->common->add_template( __DIR__ . '/templates.php' );
	}

  public function register_ajax_actions( Ajax $ajax ) {
		$library_ajax_requests = [
			'contempo_get_library_data',
			'contempo_get_template_data'
		];

		foreach ( $library_ajax_requests as $ajax_request ) {
			$ajax->register_ajax_action( $ajax_request, function( $data ) use ( $ajax_request ) {
				return $this->handle_ajax_request( $ajax_request, $data );
			} );
		}
	}

  private function handle_ajax_request( $ajax_request, array $data ) {
		if ( ! empty( $data['editor_post_id'] ) ) {
			$editor_post_id = absint( $data['editor_post_id'] );

			if ( ! get_post( $editor_post_id ) ) {
				throw new \Exception( __( 'Post not found.', 'contempo' ) );
			}

			ElementorPlugin::$instance->db->switch_to_post( $editor_post_id );
		}

		$ajax_request = str_replace( 'contempo_', '', $ajax_request );

		$result = call_user_func( [ $this, $ajax_request ], $data );

		if ( is_wp_error( $result ) ) {
			throw new \Exception( $result->get_error_message() );
		}

		return $result;
	}

  private function get_license_key() {
    return trim( get_option( 'realestate-7' . '_license_key' ) );
  }

  private function prepare_url($action) {
    $license_key = $this->get_license_key();
    
    $url = $this->domain . 'wp-admin/admin-ajax.php';
    $url = add_query_arg( 'action', $action, $url);
    $url = add_query_arg( 'url', home_url(), $url);
    $url = add_query_arg( 'license_key', $license_key, $url);

    return $url;
  }

  public function get_library_data( array $args ) {
    $url = $this->prepare_url('get_library_data');

    $response = wp_remote_get( $url, [
      'timeout' => 30,
      'sslverify' => false,
    ] );

    if ( is_wp_error( $response ) ) {
      return new \WP_Error( 'template_data_error', 'License server request error');
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );

    if(!$data['success']) {
      $message = 'License server request result error';
      if(is_array($data) && isset( $data['data']) && isset( $data['data']['message'])) {
        $message = $data['data']['message'];
      }

      return new \WP_Error( 'template_data_error', $message);
    }

    return $data;
	}

  public function get_template_data( array $args ) {
    $template_id = $args['template_id'];
    $relative_path = $args['relative_path'];

    if(!$template_id) {
      return new \WP_Error( 'template_data_error', 'No template id');
    }

		if ( isset( $args['edit_mode'] ) ) {
			ElementorPlugin::instance()->editor->set_edit_mode( $args['edit_mode'] );
		}

    $url = $this->prepare_url('get_template_data');
    $url = add_query_arg( 'template_id', $template_id, $url);
    $url = add_query_arg( 'relative_path', $relative_path, $url);

    $response = wp_remote_get( $url, [
      'timeout' => 30,
      'sslverify' => false,
    ] );

    if ( is_wp_error( $response ) ) {
      return new \WP_Error( 'template_data_error', 'License server request error');
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );

    if(!$data['success']) {
      $message = 'License server request result error';
      if(is_array($data) && isset( $data['data']) && isset( $data['data']['message'])) {
        $message = $data['data']['message'];
      }

      return new \WP_Error( 'template_data_error', $message);
    }

    return $data;
	}
}

add_action( 'init', function() {
  if ( ! class_exists( '\Elementor\Plugin' ) ) {
    return;
  }

  new Bootstrap();
});
