<?php
/**
 * 
 */
class Model_kurir extends CI_Model
{
	public function get_kurir_usaha($id_usaha)
	{
		$this->db->select("id_kurir, nama_kurir, foto_kurir, jk_kurir, telp_kurir, id_usaha");
		$this->db->where("id_usaha", $id_usaha);
		$this->db->from("data_kurir");
		return $this->db->get();
	}

	public function get_detail_kurir($id_kurir, $id_usaha)
	{
		$this->db->select("id_kurir, nama_kurir, foto_kurir, jk_kurir, telp_kurir, id_usaha");
		$this->db->where("id_kurir", $id_kurir);
		$this->db->where("id_usaha", $id_usaha);
		$this->db->from("data_kurir");
		return $this->db->get();
	}

	public function create_kurir($data)
	{
		return $this->db->insert("data_kurir", $data);
	}

	public function update_kurir($data, $id_kurir)
	{
		$this->db->where("id_kurir", $id_kurir);
		return $this->db->update("data_kurir", $data);
	}

	public function delete_kurir($id_kurir, $id_usaha)
	{
		$this->db->where("id_usaha", $id_usaha);
		$this->db->where("id_kurir", $id_kurir);
		return $this->db->delete("data_kurir");
	}

	public function cek_kurir_by_telp($login)
	{
		$this->db->where("telp_kurir", $login);
		$this->db->select("id_kurir, nama_kurir, foto_kurir, jk_kurir, telp_kurir, id_usaha");
		$this->db->from("data_kurir");
		$this->db->limit(1);
		$result = $this->db->get();
		if($result->num_rows() > 0){
			$result_ext = $result->row();
			$data_detail_kurir = $this->detail_kurir($result_ext->id_usaha);
			if($data_detail_kurir->num_rows() > 0){
				return $data_detail_kurir;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}

	private function detail_kurir($id_usaha)
	{
		$this->db->reset_query();
		$this->db->select("kurir.id_kurir, kurir.nama_kurir, kurir.foto_kurir, kurir.jk_kurir, kurir.telp_kurir, kurir.id_usaha, 
							usaha.id_usaha, usaha.nama_usaha, usaha.foto_usaha, usaha.alamat_usaha, usaha.jamBuka, usaha.jamTutup, usaha.jml_kapal, usaha.kapasitas_kapal, 
							usaha.jml_kolam, usaha.kab, usaha.kec, usaha.kel, usaha.longitude, usaha.latitude, usaha.id_pj,
							penjual.id_pj, penjual.nama_pj, penjual.foto_pj, penjual.noktp_pj, penjual.fotoktp_pj, penjual.jk_pj, penjual.tgllahir_pj, penjual.alamat_pj, penjual.telp_pj, penjual.jenis_petani");
		$this->db->from("data_kurir kurir");
		$this->db->join("data_usaha usaha", 'usaha.id_usaha = kurir.id_usaha');
		$this->db->join("data_penjual penjual", "penjual.id_pj = usaha.id_pj");
		$this->db->where("kurir.id_usaha", $id_usaha);
		return $this->db->get();
	}
}