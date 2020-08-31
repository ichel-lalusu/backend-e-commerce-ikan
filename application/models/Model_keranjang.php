<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Model_keranjang extends CI_Model
{

	public function get_pembeli_keranjang($id_akun)
	{
		$this->db->where("id_pb", $id_akun);
		$this->db->select("`id_keranjang`, `id_produk`, `id_variasi_produk`, `id_pb`, `id_usaha`, `harga_produk`, `jml_produk`, `created_date`, `sub_total`");
		$this->db->order_by("created_date DESC, id_usaha");
		$this->db->group_by("id_usaha");
		return $this->db->get("data_keranjang");
	}

	public function get_keranjang_pembeli_by_usaha($id_akun, $id_usaha)
	{
		$this->db->where("k.id_pb", $id_akun);
		$this->db->where("k.id_usaha", $id_usaha);
		$this->db->select("k.`id_keranjang`, p.nama_produk, v.nama_variasi, k.`id_produk`, k.`id_variasi_produk`, k.`id_pb`, k.`id_usaha`, k.`harga_produk`, k.`jml_produk`, k.`created_date`, k.`sub_total`, p.foto_produk, k.estimasi_ongkir, k.distance");
		$this->db->from("data_keranjang k");
		$this->db->join("data_variasi_produk vp", "vp.id_variasiproduk = k.id_variasi_produk");
		$this->db->join("data_variasi v", "v.id_variasi = vp.id_variasi");
		$this->db->join("data_produk p", "p.id_produk = k.id_produk");
		$this->db->order_by("k.created_date DESC, k.id_usaha");
		return $this->db->get();
	}

	public function simpan_keranjang_pembeli()
	{
		$id_usaha = $this->input->post('id_usaha');
		$id_akun = $this->input->post('id_akun');
		$id_variasi_produk = $this->input->post('variasi');
		$id_produk = $this->input->post('id_produk');
		$jml_produk = intval($_POST['qty']);
		$harga_produk = $this->input->post('harga_produk');
		$ikan_per_kg = $this->input->post('ikan_per_kg');
		$potong_per_ekor = $this->input->post('potong_per_ekor');
		$distance = floatval($this->input->post('distance'));
		$estimasi_ongkir = intval($this->input->post('estimasi_ongkir'));
		$sub_total = ($jml_produk * $harga_produk);
		$status_do = TRUE;
		if($status_do){
			$cek_di_keranjang = $this->get_detail_produk_keranjang_pembeli($id_variasi_produk, $id_akun);
			if($cek_di_keranjang->num_rows() > 0){
				$data_keranjang = $cek_di_keranjang->row();
				$this->db->reset_query();
				$data_update = array('jml_produk' => ($jml_produk + $data_keranjang->jml_produk), 
							'sub_total' => ($sub_total + $data_keranjang->sub_total), 
							'ikan_per_kg' => $ikan_per_kg,
							'potong_per_ekor' => $potong_per_ekor,
							'distance' => $distance,
							'estimasi_ongkir' => $estimasi_ongkir);
				$where_update = "id_pb = '$id_akun' AND id_variasi_produk = '$id_variasi_produk'";
				return $this->update_keranjang_akun_yang_sudah_ada($where_update, $data_update);
			}else{
				$data = array(
					'id_produk' => $id_produk,
					'jml_produk' => $jml_produk, 
					'sub_total' => $sub_total, 
					'id_usaha' => $id_usaha, 
					'id_pb' => $id_akun, 
					'id_variasi_produk' => $id_variasi_produk,
					'harga_produk' => $harga_produk,
					'ikan_per_kg' => $ikan_per_kg,
					'potong_per_ekor' => $potong_per_ekor,
					'distance' => $distance,
					'estimasi_ongkir' => $estimasi_ongkir,
					'created_date' => date('Y-m-d H:i:s'));
				return $this->create_data_keranjang_variasi_produk($data);
			}
		}
	}

	private function get_detail_produk_keranjang_pembeli($id_variasi_produk, $id_akun)
	{
		$this->db->where("k.id_pb", $id_akun);
		$this->db->where("k.id_variasi_produk", $id_variasi_produk);
		$this->db->select("k.`id_keranjang`, p.nama_produk, v.nama_variasi, k.`id_produk`, k.`id_variasi_produk`, k.`id_pb`, k.`id_usaha`, k.`harga_produk`, k.`jml_produk`, k.`created_date`, k.`sub_total`");
		$this->db->from("data_keranjang k");
		$this->db->join("data_variasi_produk vp", "vp.id_variasiproduk = k.id_variasi_produk");
		$this->db->join("data_variasi v", "v.id_variasi = vp.id_variasi");
		$this->db->join("data_produk p", "p.id_produk = k.id_produk");
		$this->db->order_by("k.created_date DESC, k.id_usaha");
		$this->db->limit(1);
		return $this->db->get();
	}

	private function update_keranjang_akun_yang_sudah_ada($where, $data)
	{
		$this->db->where($where);
		$this->db->limit(1);
		return $this->db->update("data_keranjang", $data);
	}

	private function is_keranjang_has_akun($id_akun)
	{
		$this->db->select("`id_keranjang`, `id_produk`, `id_variasi_produk`, `id_pb`, `id_usaha`, `harga_produk`, `jml_produk`, `created_date`, `sub_total`");
		$this->db->where("id_pb", $id_akun);
		$this->db->limit(1);
		return $this->db->get("data_keranjang");
	}

	private function get_keranjang_akun_by_id_variasi_produk($id_akun, $id_variasi_produk)
	{
		$this->db->where("id_akun", $id_akun);
		$this->db->where("id_variasi_produk", $id_variasi_produk);
		$this->db->select("`id_keranjang`, `id_produk`, `id_variasi_produk`, `id_pb`, `id_usaha`, `harga_produk`, `jml_produk`, `created_date`, `sub_total`");
		$this->db->limit(1);
		return $this->db->get("data_keranjang");
	}

	private function create_data_keranjang_variasi_produk($data)
	{
		return $this->db->insert("data_keranjang", $data);
	}

	public function delete_keranjang_by_id_usaha($id_usaha)
	{
		return $this->db->delete("data_keranjang", array('id_usaha' => $id_usaha));
	}
}