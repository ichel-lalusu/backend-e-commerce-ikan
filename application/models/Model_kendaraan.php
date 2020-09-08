<?php
/**
 * 
 */
class Model_kendaraan extends CI_Model
{
	
	public function createKendaraan($data)
    {
        return $this->db->insert('data_kendaraan', $data);
    }

    
    function updateKendaraan($data, $where)
    {
        $this->db->where($where);
        return $this->db->update('data_kendaraan',$data);
    }

    public function getKendaraanUsaha($id_usaha)
    {
        $this->db->select("`id_kendaraan`, `jenis_kendaraan`, `plat_kendaraan`, `kapasitas_kendaraan`, `id_usaha`");
        $this->db->from("data_kendaraan");
        $this->db->where("id_usaha", $id_usaha);
        return $this->db->get();
    }

    public function get_detail_kendaraan($id_kendaraan)
    {
        $this->db->select("`id_kendaraan`, `jenis_kendaraan`, `plat_kendaraan`, `kapasitas_kendaraan`, `id_usaha`");
        $this->db->from("data_kendaraan");
        $this->db->where("id_kendaraan", $id_kendaraan);
        return $this->db->get();
    }

    public function delete_kendaraan($id_kendaraan)
    {
        $data = $this->get_detail_kendaraan($id_kendaraan);
        if($data->num_rows() > 0){
            return $this->db->delete("data_kendaraan", "id_kendaraan = '$id_kendaraan'", 1);
        }else{
            return FALSE;
        }
    }
}