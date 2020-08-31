<?php

use phpDocumentor\Reflection\Types\String_;

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

	public function cek_pengguna_by_id_akun_username($id_akun, $username)
	{
		$this->db->where("id_pengguna", $id_akun);
		$this->db->where("username", $username);
		$this->db->select("`id_pengguna`, `username`, `password`, `id_akun`, `level_user`");
		return $this->db->get("data_pengguna");
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
		return $this->db->delete("data_pengguna", array('id_akun' => $id), 1);
	}

	public function delete_penjual($id)
	{

	}
}