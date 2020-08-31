<?php
/**
 * 
 */
class Model_pembayaran extends CI_Model
{
	
	public function createPembayaran($data)
    {
        return $this->db->insert('data_pembayaran', $data);
    }

    
    function updatePembayaran($data, $where)
    {
        return $this->db->update('data_pembayaran',$data,$where);
    }

    public function getPembayaran($where, $limit=NULL)
    {
    	return $this->db->get_where("data_pembayaran", $where, $limit);
    }

    public function getDataPembayaranOnlyByIdPemesanan($id_pemesanan)
    {
        $this->db->where("id_pemesanan", $id_pemesanan);
        $this->db->limit(1);
        return $this->db->get("data_pembayaran");
    }
}