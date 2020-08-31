<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Model_kendaraan extends CI_Model
{
    public $id_kendaraan;
    public $jenis_kendaraan;
    public $plat_kendaraan;
    public $kapasitas_kendaraan;
    public $id_usaha;
    public $from = "data_kendaraan";
    public $data;

    public function setJenisKendaraan(String $jenis_kendaraan="")
    {
        return $this->jenis_kendaraan = $jenis_kendaraan;
    }

    public function setPlatKendaraan(String $plat_kendaraan="")
    {
        return $this->plat_kendaraan = $plat_kendaraan;
    }

    public function setKapasitasKendaraan($kapasitas_kendaraan=0)
    {
        return $this->kapasitas_kendaraan = $kapasitas_kendaraan;
    }

    public function setIdKendaraan($id_kendaraan=0)
    {
        return $this->id_kendaraan = $id_kendaraan;
    }

    public function setIdUsaha($id_usaha)
    {
        return $this->id_usaha = $id_usaha;
    }

    public function do_update()
    {
        if($this->id_kendaraan) {
            $this->db->where('id_kendaraan', $id_kendaraan);
            return $this->db->update($this->from, $this);
        }else{
            return FALSE;
        }
    }

    protected function data_kendaraan()
    {
        $kendaraan = $this->db;
        $this->data = $kendaraan->select("`id_kendaraan`, `jenis_kendaraan`, `plat_kendaraan`, `kapasitas_kendaraan`, `id_usaha`")
                  ->from($this->from)->get();
    }

    public function setQueryOrderBy($order_by)
    {
        return $this->db->order_by($order_by);
    }

    public function getAll()
    {
        return $this->db->select("`id_kendaraan`, `jenis_kendaraan`, `plat_kendaraan`, `kapasitas_kendaraan`, `id_usaha`")
                  ->get($this->from);
    }

    public function getWhere($where)
    {
        // $this->id_usaha = $this->input->get('id_usaha');
        return $this->db->select("`id_kendaraan`, `jenis_kendaraan`, `plat_kendaraan`, `kapasitas_kendaraan`, `id_usaha`")
                  ->from("data_kendaraan")
                  ->where($where)->get();
    }

    public function getIdKendaraan()
    {
        return $this->id_kendaraan;
    }

    public function getJenisKendaraan()
    {
        return $this->jenis_kendaraan;
    }
	
    public function getPlatKendaraan()
    {
        return $this->plat_kendaraan;
    }

    public function getKapasitasKendaraan()
    {
        return $this->kapasitas_kendaraan;
    }

    public function getIdUsaha()
    {
        return $this->id_usaha;
    }

    public function do_create()
    {
        // $this->
        return $this->db->insert($this->_table, $this);
    }

    public function do_delete()
    {
        if(!empty($this)){
            return $this->db->delete($this->_table, $this);
        }
    }

	public function createKendaraan($data)
    {
        $this->
        return $this->db->insert('data_kendaraan', $data);
    }

    
    public function updateKendaraan($data, $where)
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