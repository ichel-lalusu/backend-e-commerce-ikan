<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Model_kurir extends CI_Model
{

	public function get_track_kurirV1($where)
	{
		$this->db->select("`id_track`, `id_kurir`, `longitude`, `latitude`");
		$this->db->where($where);
		$this->db->order_by("id_track", "DESC");
		$this->db->from("data_track_kurir");
		return $this->db->get();
	}
}