<?php
/**
 * 
 */
class Model_user extends CI_Model
{
	public function cek_pengguna($username, $password)
	{
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		$this->db->limit(1);
		return $this->db->get('data_pengguna');
	}

	public function updateUser($data, $where)
	{
		$this->db->where($where);
		return $this->db->update('data_pembeli', $data);
	}

	public function insert_user_sign($data)
	{
		return $this->db->insert("data_pengguna", $data);
	}

	public function insert_pembeli($data)
	{
		return $this->db->insert("data_pembeli", $data);
	}

	

	public function insert_usaha($data)
	{
		return $this->db->insert("data_usaha", $data);
	}

	public function check_username($username)
	{
		$this->db->where("username", $username);
		$this->db->limit(1);
		return $this->db->get('data_pengguna');
	}

	

	public function delete_pengguna($id)
	{
		$this->db->where("id_akun", $id);
		$this->db->limit(1);
		return $this->db->delete("data_pengguna");
	}
}