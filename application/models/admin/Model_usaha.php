<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Model_usaha extends CI_Model{
	function get_all_usaha(){
        $this->db->select("`id_usaha`, `nama_usaha`, `foto_usaha`, `alamat_usaha`, `jamBuka`, `jamTutup`, `jml_kapal`, `kapasitas_kapal`, `jml_kolam`, `kab`, `kec`, `kel`, `longitude`, `latitude`, `id_pj`");
        return $this->db->get('data_usaha');
    }

    function get_usaha_by_id_penjual($id){
        $this->db->select('*');
        $this->db->from('data_usaha b');
        $this->db->join('data_penjual a', 'a.id_pj = b.id_pj');
        $this->db->where('a.id_pj =', $id);
        $this->db->limit(1);
        return $this->db->get();
    }

    public function get_where($where, $group=NULL, $order=NULL, $limit=NULL)
    {
        $this->db->select("`id_usaha`, `nama_usaha`, `foto_usaha`, `alamat_usaha`, `jamBuka`, `jamTutup`, `jml_kapal`, `kapasitas_kapal`, `jml_kolam`, `kab`, `kec`, `kel`, `longitude`, `latitude`, `id_pj`");
        $this->db->from("data_usaha");
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

    public function get_custom($select, $where, $join='', $group='', $order='', $limit='')
    {
        return $this->db->query("SELECT $select FROM data_usaha $join WHERE $where $group $order $limit");
    }

	public function create_usaha($data){
		return $this->db->insert('data_usaha', $data);
	}

	public function delete_by_penjual($id)
	{
		return $this->db->delete('data_usaha', array('id_pj' => $id));
	}

    public function get_detail_shop_by($id)
    {
        $this->db->select("`id_usaha`, `nama_usaha`, `foto_usaha`, `alamat_usaha`, `jamBuka`, `jamTutup`, `jml_kapal`, `kapasitas_kapal`, `jml_kolam`, `kab`, `kec`, `kel`, `longitude`, `latitude`, `id_pj`");
        return $this->db->get_where('data_usaha', array('id_usaha' => $id), 1);
    }

    public function getAllDetailShopByid($id)
    {
        $this->db->where("id_usaha = ". $id);
        return $this->db->select("*")->from("data_usaha a")->join("data_penjual b", "a.id_pj = b.id_pj")->get();
    }

    public function update_shop(Array $data_update, $where)
    {
        return $this->db->update('data_usaha', $data_update, $where, 1);
    }

    public function delete_shop($id)
    {
        return $this->db->delete('data_usaha', array('id_usaha' => $id), 1);
    }
}