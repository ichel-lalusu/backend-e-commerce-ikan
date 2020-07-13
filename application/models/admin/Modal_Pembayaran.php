<?php
/**
 * 
 */
class Modal_Pembayaran extends CI_Model
{
	public function get_where($select="*", $where, $group=NULL, $order=NULL, $limit=NULL)
	{
		$this->db->select($select);
		$this->db->from("data_pembayaran");
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