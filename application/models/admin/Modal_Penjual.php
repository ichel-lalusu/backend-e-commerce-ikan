<?php
/**
 * 
 */
class Modal_Penjual extends CI_Model
{
	public function get_all($order=NULL)
	{
		if($order!==NULL){
			$this->db->order_by($order);
		}
		return $this->db->get("data_penjual");
	}

	public function get_where($select="*", $where, $group=NULL, $order=NULL, $limit=NULL)
	{
		$this->db->select($select);
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