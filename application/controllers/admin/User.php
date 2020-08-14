<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model("admin/Model_user");
		//BUAT MODEL USER//
	}

	//DISINI SEMUA YANG BERHUBUNGAN DENGAN INTERAKSI USER, SEPERTI LOGIN, DAFTAR USER DSB. login.php bisa dihapus :*
	public function login()
	{
		# code...
		$this->load->view("admin/login");
	}
	public function prosesLogin()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$cek = $this->Model_user->cek_login($username, $password);
		if($cek->num_rows() > 0){
			//session: how to use: $this->session->set_userdata('username', $var);
			$this->session->set_flashdata("success", "Sukses Login");
			$this->session->set_userdata("username", $username);
			redirect(base_url('admin/'));
		}else{
			//redirect login
		}
	}

	public function logout()
	{
		$this->session->unset_userdata("username");
		$this->session->sess_destroy();
		redirect(base_url('admin/User/login'));
	}

	//dst
}
