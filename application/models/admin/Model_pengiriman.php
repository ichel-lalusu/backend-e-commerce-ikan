<?php
/**
 * 
 */
class Model_pengiriman extends CI_Model
{
	public function get_where($where)
	{
		return $this->db->get_where("data_pengiriman", $where);
	}

	public function get_detail_pengiriman($where)
    {
        $this->db->select("`id_detail_pengiriman`, `id_pengiriman`, `id_pemesanan`, `urutan`, `status`, `penerima`");
        $this->db->from("data_detail_pengiriman");
        $this->db->where($where);
        return $this->db->get();
    }
}