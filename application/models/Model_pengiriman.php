<?php
/**
 * 
 */
class Model_pengiriman extends CI_Model
{
	public $data_pengiriman;
	public $id_pengiriman=0;
	public $id_detail_pengiriman=0;
	public $id_penjual=0;
	public $status_detail_pengiriman="";
	public $status_pengiriman="";
	public function insert_pengiriman($data)
	{
		return $this->db->insert("data_pengiriman", $data);
	}

	public function insert_batch($data)
	{
		return $this->db->insert_batch("data_pengiriman", $data);
	}

	public function get_detail_by_id_pemesanan($id_pemesanan)
	{
		$this->db->select("`id_detail_pengiriman`, `id_pengiriman`, `id_pemesanan`, `urutan`, `status`")
				->where("id_pemesanan", $id_pemesanan)
				->from("data_detail_pengiriman");
		return $this->db->get();
	}

	public function data_pengiriman($id_pengiriman)
	{
		$this->db->where("id_pengiriman", $id_pengiriman);
		$this->db->select("`id_pengiriman`, `waktu_pengiriman`, `id_kurir`, `id_kendaraan`");
		return $this->db->get("data_pengiriman");
	}

	public function getPengirimanByIdPengiriman($id_pengiriman)
	{
		$this->db->where(array('id_pengiriman' => $id_pengiriman));
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

	public function update_status_detail_pengiriman($id_pemesanan=0, array $data=array())
	{
		return $this->db->update("data_detail_pengiriman", $data, "id_pemesanan = $id_pemesanan", 1);
	}

	public function updateStatusDetailPengirimanByIdPemesanan($id_pemesanan, $data)
	{
		$this->db->where("id_pemesanan = ", $id_pemesanan);
		return $this->db->update("data_detail_pengiriman", $data);
	}

	public function getPengirimanByIdPenjual($id_penjual = null, $statusPengiriman=FALSE)
	{
		
		$this->db->select("`id_pengiriman`, `waktu_pengiriman`, `id_kurir`, `id_kendaraan`, `status`, id_pj")
				 ->from("data_pengiriman")
				 ->where("id_pj =", $id_penjual);
		if($statusPengiriman){
			$this->db->where("data_pengiriman.status =", "pengiriman");
		}
	 	return $this->db->get();
	}

	// public function getPengirimanByIdPengiriman($id_pengiriman=null)
	// {
	// 	$this->db->select("`id_pengiriman`, `waktu_pengiriman`, `id_kurir`, `id_kendaraan`, `status`, id_pj")
	// 			 ->from("data_pengiriman")
	// 			 ->where("id_pengiriman =", $id_pengiriman);
	//  	return $this->db->get();
	// }

	public function get_pengiriman_penjual($id_penjual)
	{
		$this->db->select("`id_pengiriman`, `waktu_pengiriman`, `id_kurir`, `id_kendaraan`, `status`, id_pj")
				 ->from("data_pengiriman")
				 ->where("id_pj", $id_penjual);
	 	return $this->db->get();
	}

	public function pengiriman_pembeli($id_pembeli, $id_pengiriman=null)
	{
		if($id_pengiriman!==null){
			$this->db->where('id_pengirmian', $id_pengiriman);
		}
		return $this->db->select("`id_pengiriman`, `waktu_pengiriman`, `id_kurir`, `id_kendaraan`, `status`")
		->from("data_pengiriman")
		->where("id_pb", $id_pembeli);
	}

	public function getDetailPengirimanByIdPengiriman($id_pengiriman = null)
	{
		$this->db->select("detail.`id_detail_pengiriman`, detail.`id_pengiriman`, detail.`id_pemesanan`, detail.`urutan`, detail.`status`, detail.`penerima`, pemesanan.jarak as distance, pemesanan.biaya_kirim as ongkir")
				->from("data_detail_pengiriman detail")
				->join("data_pemesanan pemesanan", "pemesanan.id_pemesanan = detail.id_pemesanan")
				->where("detail.id_pengiriman =", $id_pengiriman);
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

	public function Detail_pengiriman()
	{
		return $this->db->select("detail.`id_detail_pengiriman`, detail.`id_pengiriman`, detail.`id_pemesanan`, detail.`urutan`, detail.`status`, detail.`penerima`, pemesanan.jarak as distance, pemesanan.biaya_kirim as ongkir")
				->from("data_detail_pengiriman detail")
				->join("data_pemesanan pemesanan", "pemesanan.id_pemesanan = detail.id_pemesanan");
	}

	public function getDetailPengirimanPembeliByIdPemesanan($id_pemesanan=null, $id_pembeli=null)
	{
		$this->db->select("detail.`id_detail_pengiriman`, detail.`id_pengiriman`, detail.`id_pemesanan`, detail.`urutan`, detail.`status`, 
						detail.`penerima`, pemesanan.jarak as distance, pemesanan.biaya_kirim as ongkir")
				->from("data_detail_pengiriman detail")
				->join("data_pemesanan pemesanan", "pemesanan.id_pemesanan = detail.id_pemesanan")
				->where(array('detail.id_pemesanan' => $id_pemesanan, 'pemesanan.id_pb' => $id_pembeli));
		return $this->db->get();
	}

	public function get_lokasi_kurir($kurir)
	{
		$this->db->where("id_kurir", $kurir);
		return $this->db->select("`id_track`, `id_kurir`, `longitude`, `latitude`")
				 ->order_by("id_track", 'DESC')
				 ->get("data_track_kurir");
	}

	public function set_id_pengiriman($id_pengiriman)
	{
		$this->id_pengiriman = $id_pengiriman;
		$this->db->set("id_pengiriman", $id_pengiriman);
	}

	public function get_id_pengiriman()
	{
		return $this->id_pengiriman;
	}

	public function set_idDetailPengiriman($id_detail_pengiriman)
	{
		$this->id_detail_pengiriman = $id_detail_pengiriman;
	}

	public function get_idDetailPengiriman()
	{
		return $this->id_detail_pengiriman;
	}

	public function set_status_detail($status)
	{
		$this->status_detail_pengiriman = $status;
		$this->db->set("data_detail_pengiriman.status", $status);
	}

	public function set_status_pengiriman($status_p)
	{
		$this->status_pengiriman = $status_p;
		$this->db->set("data_pengiriman.status", $status_p);
	}
}