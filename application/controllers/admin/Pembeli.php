<?php
/**
 * 
 */
class Pembeli extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata("username")){
			redirect(base_url('admin/User/login'));
		}
		$this->load->model("admin/Model_pembeli");
	}

	public function index()
	{
		$menu = "Pembeli";
		$data_pembeli = $this->Model_pembeli->get_all();
		$data_page = array('title' => 'Data Pembeli', 'data_pembeli' => $data_pembeli, 'menu' => 'pembeli');
	    $this->load->view('admin/'.$menu.'/index', $data_page);
	}

	public function edit($id_pembeli)
	{
		$menu = "Pembeli";
		$data_pembeli = $this->Model_pembeli->get_where("id_pb = '$id_pembeli'");
		$data_page = array('title' => 'Ubah Data Pembeli', 'data_pembeli' => $data_pembeli, 'menu' => 'pembeli');
	    $this->load->view('admin/'.$menu.'/index', $data_page);
	}

	public function update()
	{
		$id = $this->input->post('id');
	}

	public function delete($id)
	{
		# code...
	}
}