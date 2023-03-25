<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class User_m extends Winter_MVC_Model {

	public $_table_name = 'wdk_locations';
	public $_order_by = 'user_nicename';
    public $_primary_key = 'ID';
    public $_own_columns = array();
    public $_timestamps = TRUE;
    protected $_primary_filter = 'intval';
    public $form_admin = array();
    public $fields_list = NULL;
    
	public function __construct(){
        parent::__construct();

        global $wpdb;

        $this->_table_name = $wpdb->users;
	}

    public function get_agents_names($where = array(), $like = "meta_value LIKE '%wdk_agent%'")
    {
        $wp_usermeta_table = $this->db->prefix.'usermeta';

        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($wp_usermeta_table.' ON '.$this->_table_name.'.ID = '.$wp_usermeta_table.'.user_id');
        $this->db->where('meta_key', $this->db->prefix.'capabilities');
        $this->db->where($where);
        $this->db->like($like);
        
        $query = $this->get();

        $list_with_keys = array();
        if ($this->db->num_rows() > 0)
        {
            $results = $this->db->results();

            foreach($results as $result)
            {
                $list_with_keys[$result->user_id] = '#'.$result->ID.', '.$result->display_name;
            }
        }

        return $list_with_keys;
    }

    public function get_agents($where = array(), $like = "meta_value LIKE '%wdk_agent%'")
    {
        $wp_usermeta_table = $this->db->prefix.'usermeta';

        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($wp_usermeta_table.' ON '.$this->_table_name.'.ID = '.$wp_usermeta_table.'.user_id');
        $this->db->where('meta_key', $this->db->prefix.'capabilities');
        $this->db->where($where);
        $this->db->like($like);
        
        /*
        if(!empty($or_like) && is_array($or_like)){
            foreach($or_like as $key=>$value)
            {
                if(empty($value)) continue;
                $this->db->or_like('meta_value', "%$value%");
            }
        }*/

        $query = $this->get();

        //$list_with_keys = array(0 => __('Root', 'wpdirectorykit'));
        $list_with_keys = array();
        if ($this->db->num_rows() > 0)
        {
            $results = $this->db->results();

            foreach($results as $result)
            {
                $list_with_keys[$result->user_id] = $result->user_email.', '.$result->display_name;
            }
        }
        //wmvc_dump($list_with_keys);

        return $list_with_keys;
    }

    public function get_pagination($limit = NULL, $offset = NULL, $where = array(), $order_by = NULL, $like = "meta_value LIKE '%wdk_agent%'", $show_other_agents_litings = FALSE, $only_listings_assigned = FALSE)
    {
        $wp_usermeta_table = $this->db->prefix.'usermeta';
        $this->load->model('listing_m');
        $alt_agents_table = $this->db->prefix.'wdk_listings_users';

        $this->db->select('ID,user_login,user_nicename,user_email,display_name,user_status,user_url, COUNT('.$this->db->prefix.'wdk_listings.user_id_editor) AS listings_counter');
        $this->db->from($this->_table_name);

        $this->db->join($wp_usermeta_table.' ON '.$this->_table_name.'.ID = '.$wp_usermeta_table.'.user_id');
        $this->db->join($this->listing_m->_table_name.' ON '.$this->listing_m->_table_name.'.user_id_editor = '.$this->_table_name.'.ID', TRUE, 'LEFT');

        $this->db->join($alt_agents_table.' ON '.$alt_agents_table.'.user_id = '.$this->_table_name.'.ID', TRUE, 'LEFT');

        $this->db->where($where);
        $this->db->where('meta_key', $this->db->prefix.'capabilities');


        if($only_listings_assigned) {
            $this->db->where(array($this->db->prefix.'wdk_listings.user_id_editor != 0'=>NULL));
        }

        if(!empty($like))
            $this->db->like($like);

        $this->db->group_by('ID');

        $this->db->limit($limit);
        $this->db->offset($offset);

        if(!empty($order_by)){
            $this->db->order_by($order_by);
        } else {
            $this->db->order_by($this->_order_by);
        }

        $query = $this->db->get();
        
        if ($this->db->num_rows() > 0)
            return $this->db->results();
        
        return array();
    }
    
    public function total($where = array(), $like = "meta_value LIKE '%wdk_agent%'", $only_listings_assigned = FALSE)
    {
        $wp_usermeta_table = $this->db->prefix.'usermeta';
        $this->load->model('listing_m');
        $this->db->select('COUNT(DISTINCT ID) as total_count, COUNT('.$this->db->prefix.'wdk_listings.user_id_editor) AS listings_counter');
        $this->db->from($this->_table_name);

        $this->db->join($wp_usermeta_table.' ON '.$this->_table_name.'.ID = '.$wp_usermeta_table.'.user_id');
        $this->db->join($this->listing_m->_table_name.' ON '.$this->listing_m->_table_name.'.user_id_editor = '.$this->_table_name.'.ID', TRUE, 'LEFT');

        $this->db->where($where);
        $this->db->where('meta_key', $this->db->prefix.'capabilities');

        if($only_listings_assigned) {
            $this->db->where(array($this->db->prefix.'wdk_listings.user_id_editor != 0'=>NULL));
        }

        if(!empty($like))
            $this->db->like($like);

        $this->db->where($where);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();

        $res = $this->db->results();

        if(isset($res[0]->total_count))
            return $res[0]->total_count;

        return 0;
    }
    
    public function get_available_fields()
    {      
        $fields = $this->db->list_fields($this->_table_name);

        return $fields;
    }
}
?>