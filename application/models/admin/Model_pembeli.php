<?php
/**
 * 
 */
class Model_pembeli extends CI_Model
{
	public function get_where($where)
	{
		return $this->db->get_where("data_pembeli", $where);
	}

	public function get_all($order=NULL)
	{
		if($order!==NULL){
			$this->db->order_by($order);
		}
		return $this->db->get("data_pembeli");
	}
}