<?php defined('BASEPATH') OR exit('No direct script access allowed');
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

	public function get_detail_pengiriman_where_only($id_pengiriman="", $condition=null)
	{
		$this->db->select("`id_detail_pengiriman`, `id_pengiriman`, `id_pemesanan`, `urutan`, `status`, `penerima`")
				->from("data_detail_pengiriman")
				->where("id_pengiriman", $id_pengiriman)
				->order_by("urutan", 'ASC');
		if($condition!==null){
			$this->db->where($condition);
		}
		return $this->db->get();
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

	public function get_specified_pengiriman_penjual($id_penjual, $id_pengiriman)
	{
		$this->db->select("`id_pengiriman`, `waktu_pengiriman`, `id_kurir`, `id_kendaraan`, `status`")
				 ->from("data_pengiriman")
				 ->where("id_pj", $id_penjual)
				 ->where("id_pengiriman", $id_pengiriman);
	 	return $this->db->get();
	}

	public function get_detail_pengiriman($id_pengiriman="", $status_condition=FALSE, $status="pengantaran")
	{
		$this->db->select("detail.`id_detail_pengiriman`, detail.`id_pengiriman`, detail.`id_pemesanan`, detail.`urutan`, detail.`status`, detail.`penerima`, pemesanan.jarak as distance, pemesanan.biaya_kirim as ongkir, pemesanan.id_pb")
				->from("data_detail_pengiriman detail")
				->join("data_pemesanan pemesanan", "pemesanan.id_pemesanan = detail.id_pemesanan")
				->where("detail.id_pengiriman", $id_pengiriman);
		if($status_condition){
			$this->db->where("detail.status", $status);
		}
		return $this->db->get();
	}

	public function get_detail_pengiriman_with_detail_pembeli($id_pengiriman)
	{
		$this->db->select("detail.id_pemesanan, detail.urutan, pemesanan.id_pb, pembeli.nama_pb, pembeli.foto_pb, pembeli.jk_pb, pembeli.telp_pb, pembeli.alamat_pb, pembeli.kab_pb as kabupaten, pembeli.kec_pb as kecamatan, pembeli.kel_pb as kelurahan, pembeli.latitude_pb as latitude, pembeli.longitude_pb as longitude")
				 ->from("data_detail_pengiriman detail")
				 ->join("data_pemesanan pemesanan", "pemesanan.id_pemesanan = detail.id_pemesanan")
				 ->join("data_pembeli pembeli", "pembeli.id_pb = pemesanan.id_pb")
				 ->where("detail.id_pengiriman", $id_pengiriman)
				 ->order_by("urutan", "ASC");
		return $this->db->get();
	}

	public function get_detail_pengiriman_where($where="")
	{
		$this->db->select("detail.`id_detail_pengiriman`, detail.`id_pengiriman`, detail.`id_pemesanan`, detail.`urutan`, detail.`status`, detail.`penerima`, pemesanan.jarak as distance, pemesanan.biaya_kirim as ongkir")
				->from("data_detail_pengiriman detail")
				->join("data_pemesanan pemesanan", "pemesanan.id_pemesanan = detail.id_pemesanan")
				->where($where);
		return $this->db->get();
	}

	public function data_kurir_pelacakan($id_kurir="")
	{
		$this->db->select("`id_kurir`, `nama_kurir`, `foto_kurir`, `jk_kurir`, `telp_kurir`, `id_usaha`")
				->where("id_kurir", $id_kurir)
				->from("data_kurir")
				->limit(1);
		return $this->db->get();
	}

	public function get_lokasi_kurir($kurir)
	{
		return $this->db->select("`id_track`, `id_kurir`, `longitude`, `latitude`")
				 ->order_by("id_track", 'DESC')
				 ->get("data_track_kurir");
	}
}