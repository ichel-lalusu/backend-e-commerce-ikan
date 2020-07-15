<?php
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
}