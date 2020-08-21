<?php
/**
 * 
 */
class Model_pengiriman extends CI_Model
{
	public $data_pengiriman;
	public $id_pengiriman;
	public $id_penjual;
	public function insert_pengiriman($data)
	{
		return $this->db->insert("data_pengiriman", $data);
	}

	public function insert_batch($data)
	{
		return $this->db->insert_batch("$data_pengiriman", $data);
	}

	public function data_pengiriman($id_pengiriman)
	{
		$this->db->where("id_pengiriman", $id_pengiriman);
		$this->db->select("`id_pengiriman`, `waktu_pengiriman`, `id_kurir`, `id_kendaraan`");
		return $this->db->get("data_pengiriman");
	}

	public function insert_detail_pengiriman($data)
	{
		return $this->db->insert("data_detail_pengiriman", $data);
	}

	public function update_status_pengiriman($id_pengiriman, $data_pengiriman)
	{
		$this->db->where("id_pengiriman", $id_pengiriman);
		return $this->db->update("data_pengiriman", $data_pengiriman);
	}

	public function get_pengiriman_penjual($id_penjual)
	{
		$this->db->select("`id_pengiriman`, `waktu_pengiriman`, `id_kurir`, `id_kendaraan`, `status`")
				 ->from("data_pengiriman")
				 ->where("id_pj", $id_penjual);
	 	return $this->db->get();
	}

	public function get_detail_pengiriman()
	{
		$this->db->select("detail.`id_detail_pengiriman`, detail.`id_pengiriman`, detail.`id_pemesanan`, detail.`urutan`, detail.`status`, detail.`penerima`, pemesanan.jarak as distance, pemesanan.biaya_kirim as ongkir")
				->from("data_detail_pengiriman detail")
				->join("data_pemesanan pemesanan", "pemesanan.id_pemesanan = detail.id_pemesanan")
				->where("detail.id_pengiriman", $this->id_pengiriman);
		return $this->db->get();
	}

	public function get_lokasi_kurir($kurir)
	{
		return $this->db->select("`id_track`, `id_kurir`, `longitude`, `latitude`")
				 ->order_by("id_track", 'DESC')
				 ->get("data_track_kurir");
	}
}