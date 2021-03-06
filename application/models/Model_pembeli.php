<?php
/**
 * 
 */
class Model_pembeli extends CI_Model
{
	public $id_pembeli;
	public function detail_pembeli($id_akun)
	{
		$this->db->select('`id_pb`, `nama_pb`, `foto_pb`, `jk_pb`, `tgllahir_pb`, `telp_pb`, `alamat_pb`, `kab_pb`, `kec_pb`, `kel_pb`, `longitude_pb`, `latitude_pb`, latitude_pb as latitude, longitude_pb as longitude, alamat_pb as nama_tempat, id_pb as id_tempat');
		$this->db->from('data_pembeli');
		$this->db->where('id_pb =', $id_akun);
		return $this->db->get();
	}

	public function profile()
	{
		return $this->db->select('`id_pb`, `nama_pb`, `foto_pb`, `jk_pb`, `tgllahir_pb`, `telp_pb`, `alamat_pb`, `kab_pb`, `kec_pb`, `kel_pb`, 
		`longitude_pb`, `latitude_pb`, latitude_pb as latitude, longitude_pb as longitude, alamat_pb as nama_tempat, id_pb as id_tempat')
			->from('data_pembeli')
			->where('id_pb =', $this->id_pembeli);
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

	public function get()
	{
		$this->db->select("`id_pb`, `nama_pb`, `foto_pb`, `jk_pb`, `tgllahir_pb`, `telp_pb`, `alamat_pb`, `kab_pb`, `kec_pb`, `kel_pb`, `longitude_pb`, `latitude_pb`")
		->from("data_pembeli")
		->order_by("id_pb", 'DESC');
		return $this->db->get();
	}

	public function set_id_pembeli($id_pembeli)
	{
		$this->id_pembeli = $id_pembeli;
	}

	public function get_id_pembeli()
	{
		return $this->id_pembeli;
	}
}