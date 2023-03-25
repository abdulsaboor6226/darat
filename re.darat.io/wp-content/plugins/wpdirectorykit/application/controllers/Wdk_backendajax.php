<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly;

class Wdk_backendajax extends Winter_MVC_Controller {

	public function __construct(){
		ini_set('display_errors',1);
		ini_set('display_startup_errors',1);
		error_reporting(-1);
		parent::__construct();

        $this->data['is_ajax'] = true;
        
	}
    
	public function index(&$output=NULL, $atts=array())
	{

	}

	  
    public function plugin_news()
    {
        $data = array();
        $data['message'] = __('No message returned!', 'wpdirectorykit');
        $data['success'] = false;
        $data['response'] = NULL;
        $data['rss'] = array();

        //https://wpdirectorykit.com/wp/last_news.php?f=news.json

        $request    = wp_remote_get( 'https://wpdirectorykit.com/wp/last_news.php?f=news.json' );

        // request failed
        if ( is_wp_error( $request ) ) {
            $data['response'] = $request;
        }
        $code = (int) wp_remote_retrieve_response_code( $request );

        // make sure the fetch was successful
        if (empty($data['response']) && $code == 200 ) {
            $response = wp_remote_retrieve_body( $request );

            // Decode the json
            $output = json_decode( $response ); 
            $count = 0;
            foreach ($output  as $key => $value) {
                $data['rss'][] = array(
                    'date'=>wdk_get_date(wmvc_show_data('date', $value, date('Y-m-d H:i:s'), TRUE, TRUE), false),
                    'title'=>wmvc_show_data('title', $value, '', TRUE, TRUE),
                    'link'=>wmvc_show_data('link', $value, '', TRUE, TRUE),
                );
                $count++;

                if($count > 10) break;
            }
        } else {
            $data['response'] = get_status_header_desc( $code );
        }

		$this->output($data);
    }
	  
    public function plugin_upgrader($output="", $atts=array(), $instance=NULL)
    {
        $data = array();
        $data['message'] = __('No message returned!', 'wpdirectorykit');
        $data['success'] = false;

        ob_start();
        
        $parameters = array();
        
		foreach ($_POST as $key => $value) {
			$parameters[$key] = sanitize_text_field($value);
		}

        $source = 'https://downloads.wordpress.org/plugin/'.$parameters['slug'].'.zip';

        if(!empty($parameters['source']))
            $source = $parameters['source'];

        if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        }

        $upgrader = new Plugin_Upgrader(new PluginInstallerSkinSilentWdk( $skin_args ));

        if(!file_exists(WP_PLUGIN_DIR .'/'.$parameters['slug'].'/'.$parameters['slug'].'.php'))
        {
            //exit(WP_PLUGIN_DIR .'/'.$parameters['slug'].'/'.$parameters['slug'].'.php');

            $upgrader->install( $source );
        }

        ob_clean();

        $activate = activate_plugin( $parameters['slug'].'/'.$parameters['slug'].'.php' );

        if ( is_wp_error( $activate ) )
        {
            $data['message'] = wp_kses_post( $activate->get_error_message() );
            $data['success'] = false;
        }
        else
        {
            $data['success'] = true;
        }

        $data['slug'] = $parameters['slug'];
		
        $data['parameters'] = $parameters;

        //$data['sql'] = $this->db->last_query();
		$this->output($data);
    }

    public function install_content($output="", $atts=array(), $instance=NULL)
    {
        $data = array();
        $data['message'] = __('No message returned!', 'wpdirectorykit');
        $data['success'] = false;

        ob_start();
        
        $parameters = array();
        
		foreach ($_POST as $key => $value) {
			$parameters[$key] = sanitize_text_field($value);
		}

		if ( ! class_exists( '\WP_Importer' ) ) {
			require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
		}

        require_once WPDIRECTORYKIT_PATH . 'vendor/WordPress-Importer/class-logger.php';
        require_once WPDIRECTORYKIT_PATH . 'vendor/WordPress-Importer/class-logger-html.php';
        require_once WPDIRECTORYKIT_PATH . 'vendor/WordPress-Importer/class-logger-serversentevents.php';
        require_once WPDIRECTORYKIT_PATH . 'vendor/WordPress-Importer/class-wxr-importer.php';
        require_once WPDIRECTORYKIT_PATH . 'vendor/WordPress-Importer/class-wxr-import-info.php';

        $importer_options = array(
            'fetch_attachments' => true
        );

        $logger = new WP_Importer_Logger();

        $importer = new WXR_Importer( $importer_options );

        $importer->set_logger($logger);

        $current_theme = wp_get_theme();

        $tmp_file = download_url( $current_theme->get( 'AuthorURI' ).'/demo_themes/'.$current_theme->get( 'TextDomain' ).'.xml' );

        $results_importer = $importer->import($tmp_file);

        ob_clean();

        if ( is_wp_error( $results_importer ) )
        {
            $data['message'] = wp_kses_post( $results_importer->get_error_message() );
            $data['success'] = false;
        }
        else
        {
            $data['success'] = true;
        }

        $data['parameters'] = $parameters;

        //$data['sql'] = $this->db->last_query();
		$this->output($data);
    }

    public function install_listings($output="", $atts=array(), $instance=NULL)
    {
		$this->load->load_helper('listing');
		$this->load->model('listing_m');
		$this->load->model('listingfield_m');

        $data = array();
        $data['message'] = __('No message returned!', 'wpdirectorykit');
        $data['success'] = false;

        ob_start();
        
        $parameters = array();
        
		foreach ($_POST as $key => $value) {
			$parameters[$key] = sanitize_text_field($value);
		}

        $data['parameters'] = $parameters;

        //$data['sql'] = $this->db->last_query();
		$this->output($data);
    }

    	  
    public function generated_listings_images_path ()
    {
        $data = array();
        $data['message'] = '';
        $data['popup_text_success'] = '';
        $data['popup_text_error'] = '';
        $data['parameters'] = $_POST;
		$data['success'] = false;

        $this->load->load_helper('listing');
		$this->load->model('listing_m');
        $listings = $this->listing_m->get();

        foreach($listings as $listing) {
            $image_ids = explode(',', $listing->listing_images);
            $listing_data = array('listing_images_path' => '');
            if(is_array($image_ids)) {
                foreach ($image_ids as $image_id) {
                    if(is_numeric($image_id))
                    {
                        $image_path = wp_get_original_image_path( $image_id);
                        if(!$image_path) continue;

                        /* path of image */
                        $next_path = str_replace(WP_CONTENT_DIR . '/uploads/','', $image_path);

                        /* check length listing_images_path + next image + comma, should be less 200*/
                        if(strlen($listing_data['listing_images_path'].$next_path)>195) break;

                        if(!empty($listing_data['listing_images_path']))
                            $listing_data['listing_images_path'] .= ',';

                        $listing_data['listing_images_path'] .= $next_path;
                    }
                }
            } 

            $this->listing_m->insert($listing_data, $listing->post_id);
        }

        $data['success'] = true;
        $data['popup_text_success'] = __('listing_images_path generated', 'wpdirectorykit');
		$this->output($data);
    }

    public function update_depend()
    {
        $data = array();
        $data['message'] = __('No message returned!', 'wpdirectorykit');
        $data['success'] = false;
        $data['parameters'] = $_POST;

        $this->load->load_helper('listing');
		$this->load->model('dependfields_m');

        if(wmvc_show_data('field_id', $data['parameters'], false) && wmvc_show_data('main_field', $data['parameters'], false)) {

            $hidden_fields = array();
            foreach ($data['parameters'] as $key => $value) {
                if(strpos($key, 'field_hide_') !== FALSE) {
                    $field_id = str_replace('field_hide_','',$key);
                    if(is_intval($field_id)) {
                        $hidden_fields[] = $field_id;
                    }
                }
            }

            $data_insert = array(
                'main_field'=>wmvc_show_data('main_field', $data['parameters'], false),
                'field_id'=> wmvc_show_data('field_id', $data['parameters'], false),
                'hidden_fields_list'=>join(',',$hidden_fields),
            );

            $this->dependfields_m->delete_where(array('field_id' => $data_insert['field_id'],'main_field' => $data_insert['main_field']));
            $this->dependfields_m->insert($data_insert);
        }

		$this->output($data);

    }
	    
    private function output($data, $print = TRUE) {
        if($print) {
            header('Pragma: no-cache');
            header('Cache-Control: no-store, no-cache');
            header('Content-Type: application/json; charset=utf8');
            //header('Content-Length: '.$length); // special characters causing troubles
            echo (wp_json_encode($data));
            exit();
        } else {
            return $data;
        }
    }
	
}

if ( ! class_exists( '\Plugin_Upgrader', false ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
}

/**
 * WordPress class extended for on-the-fly plugin installations.
 */
class PluginInstallerSkinSilentWdk extends \WP_Upgrader_Skin {

	/**
	 * Empty out the header of its HTML content.
	 */
	public function header() {}

	/**
	 * Empty out the footer of its HTML content.
	 */
	public function footer() {}

	/**
	 * Empty out the footer of its HTML content.
	 *
	 * @param string $string
	 * @param mixed  ...$args Optional text replacements.
	 */
	public function feedback( $string, ...$args ) {}

	/**
	 * Empty out JavaScript output that calls function to decrement the update counts.
	 *
	 * @param string $type Type of update count to decrement.
	 */
	public function decrement_update_count( $type ) {}

	/**
	 * Empty out the error HTML content.
	 *
	 * @param string|WP_Error $errors A string or WP_Error object of the install error/s.
	 */
	public function error( $errors ) {}
}
