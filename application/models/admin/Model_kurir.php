<?php
/**
 * 
 */
class Model_kurir extends CI_Model
{

	public function get_track_kurirV1($where)
	{
		$this->db->select("*");
		$this->db->where($where);
		$this->db->order_by("id_track", "DESC");
		$this->db->from("data_track_kurir");
		return $this->db->get();
	}
}