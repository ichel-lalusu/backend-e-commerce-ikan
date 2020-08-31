<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Modal_Pembayaran extends CI_Model
{
	public function get_where($select, $where, $group=NULL, $order=NULL, $limit=NULL)
	{
		$this->db->select("`id_pembayaran`, `metode_pembayaran`, `expiredDate`, `waktu_pembayaran`, `kode_bank`, `no_rekening_pb`, `nama_rekening_pb`, `struk_pembayaran`, `status_pembayaran`, `id_pemesanan`, `verifikasi`");
		$this->db->from("data_pembayaran");
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