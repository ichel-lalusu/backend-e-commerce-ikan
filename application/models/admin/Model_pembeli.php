<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Model_pembeli extends CI_Model
{
	public function get_where($where)
	{
		$this->db->select("`id_pb`, `nama_pb`, `foto_pb`, `jk_pb`, `tgllahir_pb`, `telp_pb`, `alamat_pb`, `kab_pb`, `kec_pb`, `kel_pb`, `longitude_pb`, `latitude_pb`");
		return $this->db->get_where("data_pembeli", $where);
	}

	public function get_all($order=NULL)
	{
		$this->db->select("`id_pb`, `nama_pb`, `foto_pb`, `jk_pb`, `tgllahir_pb`, `telp_pb`, `alamat_pb`, `kab_pb`, `kec_pb`, `kel_pb`, `longitude_pb`, `latitude_pb`");
		if($order!==NULL){
			$this->db->order_by($order);
		}
		return $this->db->get("data_pembeli");
	}

	public function detail_pembeli($id_akun)
	{
		$this->db->select('`id_pb`, `nama_pb`, `foto_pb`, `jk_pb`, `tgllahir_pb`, `telp_pb`, `alamat_pb`, `kab_pb`, `kec_pb`, `kel_pb`, `longitude_pb`, `latitude_pb`, latitude_pb as latitude, longitude_pb as longitude, alamat_pb as nama_tempat, id_pb as id_tempat');
		$this->db->from('data_pembeli');
		$this->db->where('id_pb =', $id_akun);
		return $this->db->get();
	}

	public function updateAlamat($data, $id_akun)
	{
		return $this->db->update('data_pembeli', $data, array('id_pb'=>$id_akun));
	}

	public function updatePembeli($data, $id_akun)
	{
		$this->db->where("id_pb", $id_akun);
		return $this->db->update("data_pembeli", $data);
	}
}