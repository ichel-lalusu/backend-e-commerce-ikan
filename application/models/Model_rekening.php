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
		$this->db->select("rekening.`kode_bank`, rekening.`id_akun`, rekening.`no_rekening`, rekening.`nama_rekening`, bank.`nama_bank`, rekening.`id_rekening`")
				->from("data_usaha usaha")
				->join("data_penjual penjual", "penjual.id_pj = usaha.id_pj")
				->join("data_rekening rekening", "rekening.id_akun = penjual.id_pj")
				->join("data_master_bank bank", "bank.kode_bank = rekening.kode_bank")
				->where('usaha.id_usaha', $id_usaha);
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