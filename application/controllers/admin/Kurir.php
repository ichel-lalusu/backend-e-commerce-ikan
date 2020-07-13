<?php
/**
 * 
 */
class Kurir extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata("username")){
			redirect(base_url('admin/User/login'));
		}
		$this->load->model("admin/Model_kurir");
		$this->load->model("admin/Model_usaha", "usaha");
	}

	public function add_kurir($id_usaha)
	{
		if(!$this->session->userdata("username")){

		}else{
			$menu = "Penjual";
			$where = "id_usaha = '$id_usaha'";
			$data_shop = $this->usaha->get_where("*", $where)->row();
			$id_usaha = $data_shop->id_usaha;
			$data_page = array('title' => 'Tambah Data Kendaraan ' . $data_shop->nama_usaha, 
				'menu' => 'penjual',
				'id_usaha' => $id_usaha);
			$this->load->view('admin/'.$menu."/Usaha/Kendaraan/add_kendaraan", $data_page);
		}
	}

	public function simpan_kurir($value='')
	{
		# code...
	}

	public function ubah_kurir($id_kurir, $id_usaha)
	{
		# code...
	}

	public function simpan_ubah_kurir()
	{
		# code...
	}

	public function delete_kurir($id_kurir, $id_usaha)
	{
		# code...
	}

	private function detail_kurir($id_kurir)
	{
		# code...
	}
}