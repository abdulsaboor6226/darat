<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly;

class Wdk_location extends Winter_MVC_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
        $this->data['form'] = &$this->form;

        /* [Table Actions Bulk Form] */
        $table_action = $this->input->post_get('table_action');
        $action = $this->input->post_get('action');
        $posts_selected = $this->input->post_get('post');

        if(!empty($table_action))
        {
            switch ($action) {
                case 'delete':
                    $this->bulk_delete($posts_selected);
                    break;
                default:
            } 
        }

        $this->load->model('location_m');

        $this->data['locations'] = $this->location_m->get_tree_table();
        // Load view
        $this->load->view('wdk_location/index', $this->data);
    }

    public function delete()
    {
        $id = (int) $this->input->post_get('id');
        $paged = (int) $this->input->post_get('paged');

        $this->load->model('location_m');

        $this->location_m->delete($id);

        wp_redirect(admin_url("admin.php?page=wdk_location&paged=$paged"));
    }

    public function bulk_delete($posts_selected)
    {
        $this->load->model('location_m');
        foreach($posts_selected as $id)
        {
            $this->location_m->delete($id);
        }

        wp_redirect(admin_url("admin.php?page=wdk_location&is_updated=true&custom_message=".urlencode(esc_html__('Selected Locations removed', 'wpdirectorykit'))));
        exit;
    }

	public function edit()
	{
        $this->load->model('location_m');

        $id = $this->input->post_get('id');
        wdk_access_check('location_m', $id);
        
        $this->data['db_data'] = NULL;

        $this->data['form'] = &$this->form;

        $this->data['parents'] = $this->location_m->get_parents($id);

        //exit($this->db->last_query());

        $rules = array(
                array(
                    'field' => 'location_title',
                    'label' => __('Title', 'wpdirectorykit'),
                    'rules' => 'required'
                ),
                array(
                    'field' => 'order_index',
                    'label' => __('Order Index', 'wpdirectorykit'),
                    'rules' => ''
                ),
                array(
                    'field' => 'parent_id',
                    'label' => __('Parent', 'wpdirectorykit'),
                    'rules' => ''
                ),
                array(
                    'field' => 'icon_id',
                    'label' => __('Parent', 'wpdirectorykit'),
                    'rules' => ''
                ),
                array(
                    'field' => 'image_id',
                    'label' => __('Parent', 'wpdirectorykit'),
                    'rules' => ''
                ),
        );

        if($this->form->run($rules))
        {
            // Save procedure for basic data
            $data = $this->location_m->prepare_data($this->input->post(), $rules);

            if(empty($id) && strpos($data['location_title'], ',') !== FALSE) {
                
                foreach (explode(',', $data['location_title']) as $title) {
                    // Save standard wp post
                    $insert_id = $this->location_m->insert(array_merge($data, array('location_title' => $title)), NULL);
                }
                wp_redirect(admin_url("admin.php?page=wdk_location&is_updated=true&custom_message=".urlencode(esc_html__('Bulk Locations added', 'wpdirectorykit'))));
                exit;
            } else {

                // Save standard wp post
                $insert_id = $this->location_m->insert($data, $id);

                //exit($this->db->last_error());

                /* indexes by new order and reorder */
                $results = $this->location_m->get_tree_table();
                $values = array();
                $order_index = 1;
                foreach( $results as $item)
                {
                    $values[] = array('order_index' => $order_index, 'idlocation' => $item->idlocation);
                    $order_index++;
                }
                $this->db->updateBatch( $this->location_m->_table_name, $values, 'idlocation');

                // redirect
                if(!empty($insert_id) && empty($id))
                {
                    wp_redirect(admin_url("admin.php?page=wdk_location&id=$insert_id&is_updated=true"));
                    exit;
                }
            }
                
        }

        if(!empty($id))
        {
            $this->data['db_data'] = $this->location_m->get($id, TRUE);
        }

        $this->load->view('wdk_location/location_edit', $this->data);
    }
    
}
