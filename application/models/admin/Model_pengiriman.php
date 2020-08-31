<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Model_pengiriman extends CI_Model
{
	public function get_where($where)
	{
		$this->db->select("`id_pengiriman`, `waktu_pengiriman`, `urutan_pengiriman`, `status_pengiriman`, `penerima`, `id_pemesanan`, `id_jam_pengiriman`, `id_kurir`, `id_kendaraan`");
		return $this->db->get_where("data_pengiriman", $where);
	}

	public function get_detail_pengiriman(String $where)
	{
		$this->db->select("`id_detail_pengiriman`, `id_pengiriman`, `id_pemesanan`, `urutan`, `status`, `penerima`");
		return $this->db->get_where("data_detail_pengiriman", $where);
	}
}