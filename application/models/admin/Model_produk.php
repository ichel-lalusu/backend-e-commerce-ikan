<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_produk extends CI_Model{
    
    public function ambil_produk_penjual($id_usaha)
	{
		return $this->db->query("SELECT p.id_produk, p.status_p, p.nama_produk, p.kategori, p.foto_produk, p.berat_produk, p.min_pemesanan, (SELECT MIN(harga) FROM `data_variasi_produk` WHERE id_produk = p.id_produk AND status_vp = 'aktif') as minprice, (SELECT MAX(harga) FROM `data_variasi_produk` WHERE id_produk = p.id_produk AND status_vp = 'aktif') as maxprice 
                FROM data_produk p 
				JOIN data_variasi_produk vp ON p.id_produk = vp.id_produk
				JOIN data_usaha u ON p.id_usaha = u.id_usaha
				WHERE u.id_usaha = '$id_usaha'
				GROUP BY p.id_produk;");
	}

	public function get_variation_product($select="*", $where, $JOIN=NULL, $group=NULL, $order=NULL, $limit=NULL)
	{
		$this->db->select($select);
		$this->db->from("data_variasi_produk");
		$this->db->where($where);
		if($JOIN!==NULL){
			if(count($JOIN)==1)
				$this->db->join($JOIN['table'], $JOIN['on'], $JOIN['join']);
			else
				for ($i=0; $i < count($JOIN); $i++) { 
				$this->db->join($JOIN[$i]['table'], $JOIN[$i]['on'], $JOIN[$i]['join']);	
				}
		}
		if($group!==NULL){
			$this->db->group_by($group);
		}
		if($order!==NULL){
			$this->db->order_by($order);
		}
		if($limit!==NULL){
			$this->db->limit($limit);
		}
		return $this->db->get();
	}

	public function get_product($select="*", $where, $group=NULL, $order=NULL, $limit=NULL)
	{
		$this->db->select("`id_produk`, `id_usaha`, `nama_produk`, `kategori`, `foto_produk`, `berat_produk`, `min_pemesanan`, `status_p`");
		$this->db->from("data_produk");
		$this->db->where($where);
		if($group!==NULL){
			$this->db->group_by($group);
		}
		if($order!==NULL){
			$this->db->order_by($order);
		}
		if($limit!==NULL){
			$this->db->limit($limit);
		}
		return $this->db->get();
	}
}