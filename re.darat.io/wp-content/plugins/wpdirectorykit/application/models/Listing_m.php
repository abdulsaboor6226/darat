<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Listing_m extends Winter_MVC_Model {

	public $_table_name = 'wdk_listings';
	public $_order_by = 'post_id DESC';
    public $_primary_key = 'post_id';
    public $_own_columns = array();
    public $_timestamps = TRUE;
    protected $_primary_filter = 'intval';
    public $form_admin = array();
    public $fields_list = NULL;
    public $current_user_id = NULL;
    
	public function __construct(){
        $this->current_user_id = get_current_user_id();
        parent::__construct();
	}

    /* [START] For dynamic data table */
    
    public function get_available_fields()
    {      
        $fields = $this->db->list_fields($this->_table_name);

        return $fields;
    }
    
    public function total($where = array(), $user_check=FALSE, $user_id=NULL, $show_other_agents_litings = FALSE)
    {
        $this->_primary_key = $this->db->prefix.'wdk_listings`.`post_id';
        $this->_order_by = $this->db->prefix.'wdk_listings.post_id DESC';

        $post_table = $this->db->prefix.'posts';
        $fields_table = $this->db->prefix.'wdk_listings_fields';

        $this->db->select('COUNT(DISTINCT '.$this->db->prefix.'wdk_listings.post_id) as total_count');
        $this->db->from($this->_table_name);
        
        $this->db->join($post_table.' ON '.$this->_table_name.'.post_id = '.$post_table.'.ID');
        $this->db->join($fields_table.' ON '.$this->_table_name.'.post_id = '.$fields_table.'.post_id');

        /* locations */
        $this->db->join($this->db->prefix.'wdk_locations AS location_table ON '.$this->_table_name.'.location_id = location_table.idlocation', NULL, 'LEFT');
        $this->db->join($this->db->prefix.'wdk_categories AS category_table ON '.$this->_table_name.'.category_id = category_table.idcategory', NULL, 'LEFT');
        /* for parents search*/
        /*
        for($i = 2 ; $i <= 2; $i++) {
            $this->db->join($this->db->prefix.'wdk_locations AS location_'.$i.' ON location_'.($i-1).'.parent_id = location_'.$i.'.idlocation', NULL, 'LEFT');
        }*/

        if(isset($where['is_activated'])){
            $where[$this->db->prefix.'wdk_listings`.`is_activated'] = $where['is_activated'];
            unset($where['is_activated']);
        }

        if(isset($where['is_approved'])){
            $where[$this->db->prefix.'wdk_listings`.`is_approved'] = $where['is_approved'];
            unset($where['is_approved']);
        }
        
        if( (!wmvc_user_in_role('administrator') && $user_check) || (!is_null($user_id) && !empty($user_id) ) )
        {
            if($show_other_agents_litings)
            {
                $this->db->join($this->db->prefix.'wdk_listings_users ON '.$this->_table_name.'.post_id = '.$this->db->prefix.'wdk_listings_users.post_id', NULL, 'LEFT');
                $this->db->distinct($this->_table_name.'.post_id');
                if(!is_null($user_id) && !empty($user_id))
                {
                    $this->db->where(array('(`'.$this->db->prefix.'wdk_listings`.`user_id_editor` = '.$user_id.' OR `'.$this->db->prefix.'wdk_listings_users`.`user_id` = '.$user_id.' )'=>NULL));
                }
                elseif($this->current_user_id) 
                {
                    $this->db->where(array('(`'.$this->db->prefix.'wdk_listings`.`user_id_editor` = '.$this->current_user_id.' OR `'.$this->db->prefix.'wdk_listings_users`.`user_id` = '.$this->current_user_id.' )'=>NULL));
                }
            } else {
                if(!is_null($user_id) && !empty($user_id))
                {
                    $this->db->where($this->db->prefix.'wdk_listings`.`user_id_editor', $user_id);
                }
                elseif($this->current_user_id)
                {
                    $this->db->where($this->db->prefix.'wdk_listings`.`user_id_editor', $this->current_user_id);
                }
            }
        }

        $this->db->where($where);
        $this->db->order_by($this->_order_by);
        
        $query = $this->db->get();

        $res = $this->db->results();

        $this->_primary_key = 'post_id';
        $this->_order_by = 'post_id DESC';

        if(isset($res[0]->total_count))
            return $res[0]->total_count;

        return 0;
    }
    
    public function get_pagination($limit, $offset, $where = array(), $user_check = FALSE, $user_id=NULL, $show_other_agents_litings = FALSE)
    {
        $this->_primary_key = $this->db->prefix.'wdk_listings`.`post_id';
        $this->_order_by = $this->db->prefix.'wdk_listings.post_id DESC';

        $post_table = $this->db->prefix.'posts';
        $fields_table = $this->db->prefix.'wdk_listings_fields';


        $this->db->select($this->_table_name.'.*, '.$post_table.'.*,'.$fields_table.'.*,'.$fields_table.'.post_id AS fields_post_id');
        $this->db->from($this->_table_name);
        $this->db->join($post_table.' ON '.$this->_table_name.'.post_id = '.$post_table.'.ID');
        $this->db->join($fields_table.' ON '.$this->_table_name.'.post_id = '.$fields_table.'.post_id');

        /* locations */
        $this->db->join($this->db->prefix.'wdk_locations AS location_table ON '.$this->_table_name.'.location_id = location_table.idlocation', NULL, 'LEFT');
        $this->db->join($this->db->prefix.'wdk_categories AS category_table ON '.$this->_table_name.'.category_id = category_table.idcategory', NULL, 'LEFT');
        /* for parents search*/
        /*for($i = 2 ; $i <= 2; $i++) {
            $this->db->join($this->db->prefix.'wdk_locations AS location_'.$i.' ON location_'.($i-1).'.parent_id = location_'.$i.'.idlocation', NULL, 'LEFT');
        }*/

        if(isset($where['is_activated'])){
            $where[$this->db->prefix.'wdk_listings`.`is_activated'] = $where['is_activated'];
            unset($where['is_activated']);
        }

        if(isset($where['is_approved'])){
            $where[$this->db->prefix.'wdk_listings`.`is_approved'] = $where['is_approved'];
            unset($where['is_approved']);
        }

        if( (!wmvc_user_in_role('administrator') && $user_check) || (!is_null($user_id) && !empty($user_id) ) )
        {
            if($show_other_agents_litings)
            {
                $this->db->join($this->db->prefix.'wdk_listings_users ON '.$this->_table_name.'.post_id = '.$this->db->prefix.'wdk_listings_users.post_id', NULL, 'LEFT');
                $this->db->distinct($this->_table_name.'.post_id');
                if(!is_null($user_id) && !empty($user_id))
                {
                    $this->db->where(array('(`'.$this->db->prefix.'wdk_listings`.`user_id_editor` = '.$user_id.' OR `'.$this->db->prefix.'wdk_listings_users`.`user_id` = '.$user_id.' )'=>NULL));
                }
                elseif($this->current_user_id) 
                {
                    $this->db->where(array('(`'.$this->db->prefix.'wdk_listings`.`user_id_editor` = '.$this->current_user_id.' OR `'.$this->db->prefix.'wdk_listings_users`.`user_id` = '.$this->current_user_id.' )'=>NULL));
                }
            } else {
                if(!is_null($user_id) && !empty($user_id))
                {
                    $this->db->where($this->db->prefix.'wdk_listings`.`user_id_editor', $user_id);
                }
                elseif($this->current_user_id) 
                {
                    $this->db->where($this->db->prefix.'wdk_listings`.`user_id_editor', $this->current_user_id);
                }
            }

            $this->db->where($where);
        } else {
            $this->db->where($where);
        }


        $this->db->limit($limit);
        $this->db->offset($offset);

        $this->db->order_by($this->_order_by);
        
        $query = $this->db->get();

        $this->_primary_key = 'post_id';
        $this->_order_by = 'post_id DESC';

        if ($this->db->num_rows() > 0)
            return $this->db->results();
        
        return array();
        
    }
    
    public function get($id = NULL, $single = FALSE)
    {
        $this->_primary_key = $this->db->prefix.'wdk_listings`.`post_id';
        $this->_order_by = $this->db->prefix.'wdk_listings.post_id DESC';

        $post_table = $this->db->prefix.'posts';
        $fields_table = $this->db->prefix.'wdk_listings_fields';

        $this->db->select($this->_table_name.'.*, '.$post_table.'.*,'.$fields_table.'.*,'.$fields_table.'.post_id AS fields_post_id');
        $this->db->from($this->_table_name);
        $this->db->join($post_table.' ON '.$this->_table_name.'.post_id = '.$post_table.'.ID');
        $this->db->join($fields_table.' ON '.$this->_table_name.'.post_id = '.$fields_table.'.post_id');

        /*$query = $this->db->get();

        if ($this->db->num_rows() > 0)
            return $this->db->row();
        
        return array();*/

        $return = parent::get($id, $single);
        
        $this->_primary_key = 'post_id';
        $this->_order_by = 'post_id DESC';

        return $return;
    }
    
    public function check_deletable($post_id)
    {
        if(wmvc_user_in_role('administrator')) return true;
        
        $this->load->model('listing_m');
        $listing = $this->listing_m->get($post_id, TRUE);
        if(wmvc_show_data('user_id_editor', $listing) == $this->current_user_id)
            return true;
            
        return false;

    }

    public function delete($post_id) {

        if(!$this->check_deletable($post_id)) return false;

        $this->load->model('listingfield_m');
        $this->load->model('listingusers_m');

        /* remove listing */
        parent::delete($post_id);
        $this->listingfield_m->delete($post_id);
        $this->listingusers_m->delete($post_id);

        /* remove post */
        wp_delete_post($post_id, true);

        do_action('wpdirectorykit/model/listing/delete', $post_id);

        return true;

    }
    
    /* [END] For dynamic data table */

    public function is_related($item_id, $user_id, $method = 'edit')
    {
        $this->load->model('listing_m');
        $listing = $this->listing_m->get($item_id, TRUE);
        if(wmvc_show_data('user_id_editor', $listing) == $user_id)
            return true;
            
        return false;
    }

    public function update_counter($listing_id = NULL)
    {
        if(empty($listing_id) )
            return false;

        $counter = 0;
        if(wdk_field_value('counter_views', $listing_id))
        {
            $counter = intval(wdk_field_value('counter_views', $listing_id));
        }
    
        $this->update(array('counter_views' => ++$counter), $listing_id);
    }

}
?>