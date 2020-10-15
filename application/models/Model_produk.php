<?php

use phpDocumentor\Reflection\Types\Boolean;

/**
 * 
 */
class Model_produk extends CI_Model
{
	public function ambil_produk_penjual($id_akun)
	{
		return $this->db->query("SELECT p.id_produk, p.nama_produk, p.kategori, p.foto_produk, p.berat_produk, p.min_pemesanan,  (SELECT MIN(harga) FROM `data_variasi_produk` WHERE id_produk = p.id_produk) as minprice, (SELECT MAX(harga) FROM `data_variasi_produk` WHERE id_produk = p.id_produk) as maxprice FROM data_produk p 
				JOIN data_variasi_produk vp ON p.id_produk = vp.id_produk
				JOIN data_usaha u ON p.id_usaha = u.id_usaha
				WHERE u.id_pj = '$id_akun'
				GROUP BY p.id_produk;");
	}

	public function get_by_id_pj($id_pj = 0)
	{
		$query = $this->db->query("SELECT produk.id_produk, produk.nama_produk, produk.kategori, produk.foto_produk, produk.berat_produk, produk.min_pemesanan, 
			(SELECT MIN(harga) FROM `data_variasi_produk` WHERE id_produk = produk.id_produk) as minprice, 
			(SELECT MAX(harga) FROM `data_variasi_produk` WHERE id_produk = produk.id_produk) as max_price
			FROM data_produk produk
			JOIN data_usaha usaha
			ON produk.id_usaha = usaha.id_usaha
			JOIN data_penjual penjual
			ON penjual.id_pj = usaha.id_pj
			WHERE penjual.id_pj = $id_pj");
		return $query;
	}

	public function ambil_produk_penjual_by_id($id_usaha, $filter = NULL)
	{
		$this->db->where_in('u.id_usaha', $id_usaha);
		if ($filter == "terbaru") {
			$this->db->order_by('p.id_produk', 'DESC');
		} elseif ($filter == "harga_murah") {
			$this->db->order_by('minprice', 'ASC');
		} elseif ($filter == "harga_mahal") {
			$this->db->order_by('minprice', 'DESC');
		} elseif ($filter == "stok_sedikit") {
			$this->db->order_by('stok', 'DESC');
		} elseif ($filter == "terlaris") {
		} elseif ($filter = NULL) {
			# code...
		}
		$this->db->select('p.id_produk, p.nama_produk, 
			(SELECT MAX(stok) FROM data_variasi_produk WHERE id_produk = p.id_produk) as stok,
			(SELECT MIN(harga) FROM `data_variasi_produk` WHERE id_produk = p.id_produk) as minprice, 
			(SELECT MAX(harga) FROM `data_variasi_produk` WHERE id_produk = p.id_produk) as maxprice, 
			p.foto_produk, u.id_usaha, p.status_p');
		$this->db->from('data_produk p');
		// $this->db->join('data_variasi_produk dvp', 'dvp.id_produk = p.id_produk');
		$this->db->join('data_usaha u', 'p.id_usaha = u.id_usaha');
		$this->db->order_by("u.id_usaha");
		// $this->db->group_by('dvp.id_produk');
		return $this->db->get();
	}

	public function get_detail_produk_where($where, $order = NULL)
	{
		$this->db->select('dvp.id_produk, p.nama_produk, p.foto_produk, u.nama_usaha, u.longitude, u.latitude, p.kategori, (SELECT MIN(harga) FROM `data_variasi_produk` WHERE id_produk = p.id_produk) as minprice, (SELECT MAX(harga) FROM `data_variasi_produk` WHERE id_produk = p.id_produk) as maxprice');
		$this->db->from('data_produk p');
		$this->db->join('data_variasi_produk dvp', 'dvp.id_produk = p.id_produk');
		$this->db->join('data_usaha u', 'p.id_usaha = u.id_usaha');
		$this->db->where($where);
		if ($order !== NULL) {
			$this->db->order_by($order);
		}
		$this->db->group_by('dvp.id_produk');
		return $this->db->get();
	}

	public function ambil_produk_penjual_like($filter)
	{
		if ($filter !== "" || $filter !== null) {
			$this->db->like('p.produk', $filter, 'both');
			$this->db->or_like('v.nama_variasi', $filter, 'both');
			$this->db->or_like('p.kategori', $filter, 'both');
		}
	}

	protected function SearchingConstruct()
	{
		return $this->db->from("data_produk produk")
		->join("data_usaha usaha", "produk.id_usaha = usaha.id_usaha")
		->join("data_penjual penjual", "penjual.id_pj = usaha.id_pj")
		->where("produk.status_p", "aktif")
		->like("penjual.id_pj", "", 'both');
	}

	public function search_produk(String $filter_input, String $order_type = null, int $id_usaha = 0)
	{
		// HANDLE ORDER TYPE
		$defaultKolomOrder = "produk.nama_produk";
		$defaultOrder = "ASC";
		if ($order_type === null || $order_type === "") {
			$order_type = "ASC_PRODUK";
		}
		if($id_usaha===0 || $id_usaha==="" || $id_usaha===null || $id_usaha === "undefined"){
			return $this->emptyResultSearch();
		}

		// QUERY
		$this->getSelectProdukOnSearch();
		$this->SearchingConstruct();

		// FILTER INPUT
		if ($filter_input === "" || $filter_input === null) {
			return $this->emptyResultSearch();
		}
		$this->db->like('produk.nama_produk', $filter_input, 'both');
		$this->db->where("usaha.id_usaha", $id_usaha);
		// FILTER ORDER
		$this->filterOrderOnSearch($order_type);

		return $this->db->get();
	}

	protected function getSelectProdukOnSearch()
	{
		return $this->db->select("produk.id_produk, produk.nama_produk, produk.kategori, produk.foto_produk, produk.berat_produk, produk.min_pemesanan, 
		(SELECT MIN(harga) FROM `data_variasi_produk` WHERE id_produk = produk.id_produk and status_p='aktif') as minprice, 
		(SELECT MAX(harga) FROM `data_variasi_produk` WHERE id_produk = produk.id_produk and status_p='aktif') as max_price,
		usaha.nama_usaha, usaha.id_usaha, usaha.latitude as lat, usaha.longitude as lng");
	}

	public function staticConstructProdukOnSearch($key, $id_usaha, $distance)
	{
		$returnProduk = array(
			'id_produk' => intval($key->id_produk),
			'nama_produk' => $key->nama_produk,
			'foto' => base_url('foto_usaha/produk/') . $key->foto_produk,
			'berat_produk' => intval($key->berat_produk),
			'minprice' => intval($key->minprice),
			'max_price' => intval($key->max_price),
			'id_usaha' => intval($key->id_usaha),
			'distance' => $distance['text'],
			'detail_usaha' => array(
							'nama_usaha' => $key->nama_usaha, 
							'lat' => floatval($key->lat),
							'lng' => floatval($key->lng),
							)				
		);
		return $returnProduk;
	}

	public function constructProdukOnSearch($data_produk)
	{
		$returnProduk = array();
		foreach ($data_produk->result() as $key) {
			$returnProduk[] = array(
				'id_produk' => intval($key->id_produk),
				'nama_produk' => $key->nama_produk,
				'foto' => base_url('foto_usaha/produk/') . $key->foto_produk,
				'berat_produk' => intval($key->berat_produk),
				'minprice' => intval($key->minprice),
				'max_price' => intval($key->max_price),
				'id_usaha' => intval($key->id_usaha),
				'detail_usaha' => array(
								'nama_usaha' => $key->nama_usaha, 
								'lat' => floatval($key->lat),
								'lng' => floatval($key->lng),
								)				
			);
		}
		return $returnProduk;
	}

	public function constructResultDataSearchProduk($data_produk, $id_usaha)
	{
		$resultArray = array();
		while ($id_usaha) {
			
		}
		for ($i=0; $i < count($data_produk); $i++) { 
			
		}
	}

	public function searchUsahaOnSearch(String $keyword="", String $order_type=null)
	{
		$returnUsaha = array();
		$resultDataUsaha = $this->search_usaha_on_search($keyword, $order_type);
		if($this->isResultDataUsahaNotEmpty($resultDataUsaha)){
			foreach ($resultDataUsaha->result() as $key) {
				$returnUsaha[] = array('id_usaha' => intval($key->id_usaha), 'lat' => floatval($key->lat), 'lng' => floatval($key->lng));
			}
		}
		return $returnUsaha;
	}

	protected function search_usaha_on_search(String $keyword="", String $order_type=null)
	{
		if ($order_type === null || $order_type === "") {
			$order_type = "ASC_PRODUK";
		}

		// QUERY
		$this->getUsahaOnSearch();
		$this->SearchingConstruct();

		// FILTER INPUT
		if ($keyword === "" || $keyword === null) {
			return $this->emptyResultSearch();
		}
		$this->db->like('produk.nama_produk', $keyword, 'both');
		// FILTER ORDER
		// $this->filterOrderOnSearch($order_type);

		return $this->db->get();
	}

	protected function getUsahaOnSearch()
	{
		$this->db->select("usaha.id_usaha, usaha.latitude as lat, usaha.longitude as lng");
		return $this->db->distinct();
	}

	protected function isResultDataUsahaNotEmpty($data_usaha)
	{
		if($data_usaha->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	protected function filterOrderOnSearch(String $order_type="")
	{
		$defaultKolomOrder = "produk.nama_produk";
		$defaultOrder = "ASC";
		$split_order_type = explode("_", $order_type);
		if ($order_type == "ASC_PRODUK") {
			return $this->db->order_by("produk.nama_produk", $split_order_type[0], TRUE);
		} elseif ($order_type == "ASC_HARGA") {
			return $this->db->order_by("minprice", $split_order_type[0], TRUE);
			// $this->db->order_by("max_price", $split_order_type[0], TRUE);
		} elseif ($order_type == "DESC_HARGA") {
			return $this->db->order_by("minprice", $split_order_type[0], TRUE);
			// $this->db->order_by("max_price", $split_order_type[0], TRUE);
		} else {
			return $this->db->order_by($defaultKolomOrder, $defaultOrder, TRUE);
		}
	}

	protected function emptyResultSearch()
	{
		$this->getSelectProdukOnSearch();
		$this->SearchingConstruct();
		$this->db->where("produk.id_produk", "")->where("usaha.id_usaha", "");
		return $this->db->get();
	}

	public function isProdukNotEmpty($data_produk)
	{
		if($data_produk->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function insert_produk($data)
	{
		return $this->db->insert('data_produk', $data);
	}

	public function insert_variasi_multi($data)
	{
		return $this->db->insert_batch('data_variasi_produk', $data);
	}

	public function insert_variasi($data)
	{
		return $this->db->insert('data_variasi_produk', $data);
	}

	public function ambil_id_variasi($id_var)
	{
		return $this->db->query("SELECT id_variasi FROM data_variasi WHERE nama_variasi = '$id_var' LIMIT 1;");
	}

	public function hapus($id_produk)
	{
		$this->db->where('id_produk', $id_produk);
		return $this->db->delete('data_produk');
	}

	public function ambil_data_by_id($id_produk)
	{
		$this->db->select('*, (SELECT MIN(harga) FROM `data_variasi_produk` WHERE id_produk = p.id_produk) as minprice, (SELECT MAX(harga) FROM `data_variasi_produk` WHERE id_produk = p.id_produk) as maxprice, ekor_per_kg');
		$this->db->where('p.id_produk', $id_produk);
		$this->db->from('data_produk p');
		return $this->db->get();
	}

	public function ambil_data_by($id_produk, $variasi)
	{
		$this->db->select('*, (SELECT MIN(harga) FROM `data_variasi_produk` WHERE id_produk = p.id_produk) as minprice, (SELECT MAX(harga) FROM `data_variasi_produk` WHERE id_produk = p.id_produk) as maxprice, (select nama_variasi FROM data_variasi v join data_variasi_produk vp on vp.id_variasi = v.id_variasi WHERE vp.id_variasiproduk = ' . $variasi . ') as variasi');
		$this->db->where('p.id_produk', $id_produk);
		$this->db->from('data_produk p');
		return $this->db->get();
	}

	public function ambilVariasiProduk($idProduk, $Variasi)
	{
		$this->db->select("*");
		$this->db->from("data_produk a");
		$this->db->join("data_variasi_produk b", "b.id_produk = a.id_produk", "LEFT");
		$this->db->join("data_variasi c", "c.id_variasi = b.id_variasi", "left");
		$this->db->where("a.id_produk = ", $idProduk);
		$this->db->where("b.id_variasiproduk =", $Variasi);
		return $this->db->get();
	}

	public function ambilVariasiProdukById($id_variasiproduk)
	{
		$this->db->select("*");
		$this->db->from("data_produk a");
		$this->db->join("data_variasi_produk b", "b.id_produk = a.id_produk", "LEFT");
		$this->db->join("data_variasi c", "c.id_variasi = b.id_variasi", "left");
		$this->db->where("b.id_variasiproduk =", $id_variasiproduk);
		return $this->db->get();
	}

	public function ambil_var_by_produk($id_produk)
	{
		$this->db->where('id_produk', $id_produk);
		return $this->db->get('data_variasi_produk');
	}

	public function ambil_stok_variasi($id_variasiproduk)
	{
		$this->db->where('id_variasiproduk', $id_variasiproduk);
		$this->db->select('a.stok, a.harga, b.nama_variasi');
		$this->db->from('data_variasi_produk a');
		$this->db->join('data_variasi b', 'a.id_variasi = b.id_variasi', 'left');
		return $this->db->get();
	}

	public function ambil_data_variasi()
	{
		return $this->db->query("SELECT * FROM data_variasi ORDER BY id_variasi;");
	}

	// public function ambil_variasi_produk($id_produk, $id_variasi)
	// {
	// 	$this->db->where('id_produk', $id_produk);
	// 	$this->db->where('id_variasi', $id_variasi);
	// 	return $this->db->get('data_variasi_produk');
	// }

	public function ambil_variasi_produk($id_produk)
	{
		$this->db->select('vp.id_variasiproduk, vp.harga, p.nama_produk, p.id_produk, vp.id_variasi, v.nama_variasi, vp.stok, vp.status_vp');
		$this->db->from('data_variasi_produk vp');
		$this->db->join('data_produk p', 'vp.id_produk = p.id_produk');
		$this->db->join('data_variasi v', 'vp.id_variasi = v.id_variasi');
		$this->db->where('p.id_produk', $id_produk);
		return $this->db->get();
	}

	public function ubah_produk($data, $id_produk)
	{
		$this->db->where('id_produk', $id_produk);
		return $this->db->update('data_produk', $data);
	}

	public function hapus_variasi_produk($id_produk)
	{
		$this->db->where('id_produk', $id_produk);
		return $this->db->delete('data_variasi_produk');
	}

	public function hapus_variasi_produk_by_id($id_variasiproduk)
	{
		$this->db->where('id_variasiproduk', $id_variasiproduk);
		return $this->db->delete('data_variasi_produk');
	}

	public function ambil_variasi_by_id($id_var)
	{
		$this->db->select('vp.id_variasiproduk, vp.harga, vp.id_variasi');
		$this->db->from('data_variasi_produk vp');
		$this->db->join('data_produk p', 'vp.id_produk = p.id_produk');
		$this->db->where('vp.id_variasiproduk', $id_var);
		return $this->db->get();
	}

	public function cek_variasi($id_var, $id_Var2)
	{
		$this->db->select('vp.id_variasiproduk, vp.harga, vp.id_variasi');
		$this->db->from('data_variasi_produk vp');
		$this->db->join('data_produk p', 'vp.id_produk = p.id_produk');
		$this->db->where('vp.id_variasiproduk', $id_var);
		$this->db->where('vp.id_variasi', $id_Var2);
		return $this->db->get();
	}

	public function update_variasi($data, $id_variasiproduk)
	{
		$this->db->where('id_variasiproduk', $id_variasiproduk);
		return $this->db->update('data_variasi_produk', $data);
	}

	public function ambil_img_slider_produk()
	{
		$this->db->select('id_produk, nama_produk, foto_produk, id_usaha');
		$this->db->limit(3);
		return $this->db->get('data_produk');
	}

	public function ambil_produk_kategori($kategori)
	{
		$this->db->select('*, (SELECT MIN(harga) FROM `data_variasi_produk` WHERE id_produk = p.id_produk) as minprice, (SELECT MAX(harga) FROM `data_variasi_produk` WHERE id_produk = p.id_produk) as maxprice');
		$this->db->where('kategori', $kategori);
		return $this->db->get('data_produk p');
	}

	public function updateStokProdukFromPemesanan($idVariasiProduk, array $data)
	{
		return $this->db->update("data_variasi_produk", $data, array('id_variasiproduk' => $idVariasiProduk), 1);
		// return $this->db->query("UPDATE data_variasi_produk SET stok = $stokSisa WHERE id_variasiproduk = " . $idVariasiProduk . " LIMIT 1");
	}

	public function getVariasiProdukByIdProdukIdVariasi($idProduk, $idVariasi)
	{
		$this->db->where('id_produk', $idProduk);
		$this->db->where('id_variasi', $idVariasi);
		$this->db->limit(1);
		return $this->db->get('data_variasi_produk');
	}

	public function cari($cari)
	{
		$this->db->like("nama_produk", $cari, "after");
		$this->db->select("id_produk, id_usaha, foto_produk");
		// $
	}
}
