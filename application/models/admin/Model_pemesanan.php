<?php
/**
 * 
 */
class Model_pemesanan extends CI_Model
{
	
	public function get_where($select="*", $where, $group=NULL, $order=NULL, $limit=NULL)
	{
		$this->db->select($select);
		$this->db->from("data_pemesanan");
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

	public function get_detail_where($select="*", $where, $group=NULL, $order=NULL, $limit=NULL)
	{
		$this->db->select($select);
		$this->db->from("data_detail_pemesanan");
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

	public function get_custom($select, $from, $where='', $join='', $group='', $order='', $limit='')
	{
		return $this->db->query("SELECT $select FROM $from $join $where $group $order $limit");
	}
}