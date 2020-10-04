<?php
/**
 * 
 */
class Model_keranjang extends CI_Model
{

	public function get_id_usaha_in_keranjang_pembeli($id_akun)
	{
		$this->db->select("id_usaha")
				->where("id_pb", $id_akun)
				->group_by("id_usaha")
				->order_by("id_usaha ASC")
				->from("data_keranjang");
		return $this->db->get();
	}

	public function get_by_id_keranjang($id_keranjang=0)
	{
		$this->db->select("`id_keranjang`, `id_produk`, `id_variasi_produk`, `id_pb`, `id_usaha`, `harga_produk`, `jml_produk`, `sub_total`, `ikan_per_kg`, `potong_per_ekor`, `distance`, `estimasi_ongkir`, `created_date`")
				 ->from("data_keranjang")
				 ->where("id_keranjang", $id_keranjang)
				 ->limit(1);
		return $this->db->get();
	}

	public function delete_by_id_keranjang(Int $id_keranjang = null)
	{
		if($id_keranjang!==null){
			return $this->db->delete("data_keranjang", array('id_keranjang' => $id_keranjang));
		}else{
			return false;
		}
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

	public function get_detail_produk_keranjang_pembeli($id_variasi_produk, $id_akun)
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

	public function update_keranjang_akun_yang_sudah_ada($where, $data)
	{
		$this->db->where($where);
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

	public function create_data_keranjang_variasi_produk($data)
	{
		return $this->db->insert("data_keranjang", $data);
	}

	public function delete_keranjang_by_id_usaha($id_usaha=0, $id_pb=0)
	{
		if($id_usaha!=="" && $id_pb!=="" || ($id_usaha!==0 && $id_pb!==0)){
			return $this->db->delete("data_keranjang", array('id_usaha' => $id_usaha, 'id_pb' => $id_pb));
		}else{
			return FALSE;
		}
	}

	public function delete_keranjang_by_id_keranjang($id_keranjang)
	{
		return $this->db->delete("data_keranjang", array('id_keranjang' => intval($id_keranjang)));
	}
}