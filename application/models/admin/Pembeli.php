<?php

/**
 * 
 */
class Pembeli extends CI_Model
{
	
	public function get_all($order=NULL)
	{
		if($order!==NULL){
			$this->db->order_by($order);
		}
		return $this->db->get("data_pembeli");
	}

	public function get_where($select="*", $where, $group=NULL, $order=NULL, $limit=NULL)
    {
        $this->db->select($select);
        $this->db->from("data_pembeli");
        $this->db->where($where);
        if($group!==NULL){
            $this->db->group_by($group);
        }
        if($order!==NULL){
            $this->db->order_by($order);
        }
        if($limit!==NULL){
            $this->db->limit($limit);
        }
        return $this->db->get();
    }
}