<?php

class Model_penjual extends CI_Model{
    

    function get_seller_by($id){
        $this->db->select("`id_pj`, `nama_pj`, `foto_pj`, `noktp_pj`, `fotoktp_pj`, `jk_pj`, `tgllahir_pj`, `alamat_pj`, `telp_pj`, `jenis_petani`");
        $this->db->from('data_penjual');
        // $this->db->join('data_usaha b', 'a.id_pj = b.id_pj');
        $this->db->where('id_pj =', $id);
        $this->db->limit(1);
        return $this->db->get();
    }
    
    public function get_all($order=NULL)
    {
        $this->db->select("`id_pj`, `nama_pj`, `foto_pj`, `noktp_pj`, `fotoktp_pj`, `jk_pj`, `tgllahir_pj`, `alamat_pj`, `telp_pj`, `jenis_petani`");
        if($order!==NULL){
            $this->db->order_by($order);
        }
        return $this->db->get("data_penjual");
    }

    public function get_where($select="*", $where, $group=NULL, $order=NULL, $limit=NULL)
    {
        $this->db->select("`id_pj`, `nama_pj`, `foto_pj`, `noktp_pj`, `fotoktp_pj`, `jk_pj`, `tgllahir_pj`, `alamat_pj`, `telp_pj`, `jenis_petani`");
        $this->db->from("data_penjual");
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

    public function create($data)
    {
        return $this->db->insert('data_penjual', $data);
    }

    public function delete($id)
    {
        return $this->db->delete('data_penjual', array('id_pj' => $id), 1);
    }

    public function update($data_update, $where)
    {
        return $this->db->update('data_penjual', $data_update, $where, 1);
    }


}