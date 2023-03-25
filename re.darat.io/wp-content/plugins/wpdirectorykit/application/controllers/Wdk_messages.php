<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly;

class Wdk_messages extends Winter_MVC_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
        $this->load->model('messages_m');

        $total_items = $this->messages_m->total();

        $current_page = 1;
        if(isset($_GET['paged']))
            $current_page = intval($_GET['paged']);

        $this->data['paged'] = $current_page;

        $per_page = 20;
        $offset = $per_page*($current_page-1);

        $this->data['pagination_output'] = '';

        if(function_exists('wmvc_wp_paginate'))
            $this->data['pagination_output'] = wmvc_wp_paginate($total_items, $per_page);

        $this->data['messages'] = $this->messages_m->get_pagination($per_page, $offset);

        // Load view
        $this->load->view('wdk_messages/index', $this->data);
    }

    public function delete()
    {
        $id = (int) $this->input->post_get('id');
        $paged = (int) $this->input->post_get('paged');

        $this->load->model('messages_m');

        $this->messages_m->delete($id);

        wp_redirect(admin_url("admin.php?page=wdk_messages&paged=$paged"));
    }
    
	public function edit()
	{
        $this->load->model('messages_m');

        $id = $this->input->post_get('id');
        wdk_access_check('messages_m', $id);
        $this->data['db_data'] = NULL;

        $this->data['form'] = &$this->form;

        //exit($this->db->last_query());

        $rules = array(
                array(
                    'field' => 'post_id',
                    'label' => __('Listing Id', 'wpdirectorykit'),
                    'rules' => 'required'
                ),
                array(
                    'field' => 'date',
                    'label' => __('Date', 'wpdirectorykit'),
                    'rules' => ''
                ),
                array(
                    'field' => 'message',
                    'label' => __('Message', 'wpdirectorykit'),
                    'rules' => ''
                ),
                array(
                    'field' => 'is_readed',
                    'label' => __('Readed', 'wpdirectorykit'),
                    'rules' => ''
                ),
        );

        if($this->form->run($rules))
        {
            // Save procedure for basic data
            $data = $this->messages_m->prepare_data($this->input->post(), $rules);

            // Save standard wp post

            $insert_id = $this->messages_m->insert($data, $id);

            //exit($this->db->last_error());

            // redirect
            if(!empty($insert_id) && empty($id))
            {
                wp_redirect(admin_url("admin.php?page=wdk_messages&id=$insert_id&is_updated=true"));
                exit;
            }
                
        }

        if(!empty($id))
        {
            $this->data['db_data'] = $this->messages_m->get($id, TRUE);
        }

        $this->load->view('wdk_messages/edit', $this->data);
    }
    
}
