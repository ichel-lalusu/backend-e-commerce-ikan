<?php
/**
 * 
 */
class Model_rekening extends CI_Model
{
	public function ambil_semua()
	{
		$this->db->order_by('kode_bank', 'asc');
		return $this->db->get('data_master_bank');
	}

	public function ambil_rekening_by_id($id_rekening)
	{
		$this->db->where('a.id_rekening', $id_rekening);
		$this->db->select('a.kode_bank, a.id_akun, a.no_rekening, a.nama_rekening, b.nama_bank');
		$this->db->from('data_rekening a');
		$this->db->join('data_master_bank b', 'a.kode_bank = b.kode_bank');
		return $this->db->get();
	}

	public function ambil_rekening_by_user($id_akun)
	{
		$this->db->where('a.id_akun', $id_akun);
		$this->db->select('a.kode_bank, a.id_akun, a.no_rekening, a.nama_rekening, b.nama_bank, a.id_rekening');
		$this->db->from('data_rekening a');
		$this->db->join('data_master_bank b', 'a.kode_bank = b.kode_bank');
		return $this->db->get();
	}

	public function ambil_rekening_usaha($id_usaha)
	{
		$this->db->where('usaha.id_usaha', $id_usaha);
		$this->db->select('a.kode_bank, a.id_akun, a.no_rekening, a.nama_rekening, b.nama_bank, a.id_rekening');
		$this->db->from('data_rekening a');
		$this->db->join('data_master_bank b', 'a.kode_bank = b.kode_bank');
		$this->db->join("data_usaha usaha", 'a.id_akun = usaha.id_pj');
		return $this->db->get();
	}
	
	public function simpan_rekening($data)
	{
		return $this->db->insert('data_rekening', $data);
	}

	public function ubah_rekening($data, $id_rekening)
	{
		$this->db->where('id_rekening', $id_rekening);
		return $this->db->update('data_rekening', $data);
	}

	public function hapus_rekening($id_rekening)
	{
		$this->db->where('id_rekening', $id_rekening);
		return $this->db->delete('data_rekening');
	}
}