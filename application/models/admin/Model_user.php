<?php

class Model_user extends CI_Model{
    public function cek_login($username, $password)
    {
        return $this->db->get_Where('data_pengguna', array('username' => $username, 'password' => $password), 1);
    }

    public function register($value='')
    {
        # code...
    }


}