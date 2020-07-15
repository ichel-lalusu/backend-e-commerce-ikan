<?php

/**
 * 
 */
class Pembeli extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata("username")) {
			redirect(base_url('admin/User/login'));
		}
		$this->load->model("admin/Model_pembeli");
	}

	public function index()
	{
		$menu = "Pembeli";
		$data_pembeli = $this->Model_pembeli->get_all();
		$data_page = array('title' => 'Data Pembeli', 'data_pembeli' => $data_pembeli, 'menu' => 'pembeli');
		$this->load->view('admin/' . $menu . '/index', $data_page);
	}

	public function add()
	{
		$menu = "Pembeli";
		$data_pembeli = $this->Model_pembeli->get_all();
		$data_page = array('title' => 'Data Pembeli', 'data_pembeli' => $data_pembeli, 'menu' => 'pembeli');
		$this->load->view('admin/' . $menu . '/add', $data_page);
	}

	public function create()
	{
		# code...
	}

	public function edit($id_pembeli)
	{
		$menu = "Pembeli";
		$data_pembeli = $this->Model_pembeli->get_where("id_pb = '$id_pembeli'");
		$data_page = array('title' => 'Ubah Data Pembeli', 'data_pembeli' => $data_pembeli, 'menu' => 'pembeli');
		$this->load->view('admin/' . $menu . '/edit', $data_page);
	}

	public function update()
	{
		$id = $this->input->post('id');
	}

	public function delete($id)
	{
		# code...
	}

	public function pesanan_pembeli(Int $id_pembeli)
	{
		
		try {
			$this->load->model("admin/Model_pemesanan", "Pemesanan");
			$this->load->model("admin/Model_pengiriman", "Pengiriman");
			$this->load->model("Model_penjual");
			$data_pembeli = $this->Model_pembeli->detail_pembeli($id_pembeli);
			$Pembeli = $data_pembeli->row();
			$where = " id_pb = \"$id_pembeli\"";
			$order = " id_pemesanan DESC, waktu_pemesanan DESC ";
			$select_pemesanan = "`id_pemesanan`, `waktu_pemesanan`, `tipe_pengiriman`, `tgl_pengiriman`, `jarak`, `biaya_kirim`, `total_harga`, `status_pemesanan`, `id_pb`, `id_usaha`";
			$dataPemesanan = $this->Pemesanan->get_where($select_pemesanan, $where, NULL, $order, NULL);
			$page = "Pesanan";
			$data = array('title' => 'Ikanku - Data Pesanan ' . $Pembeli->nama_pb, 'menu' => 'pembeli', 'page' => $page . "&nbsp;" . $Pembeli->nama_pb, 'id_pembeli' => $id_pembeli, 'data_transaksi' => $dataPemesanan, 'data_pembeli' => $Pembeli);
			$this->load->view("admin/Pembeli/".$page."/index", $data);
		} catch (Exception $e) {
			$this->session->set_flashdata("error", "Data Pembeli Error");
			redirect(base_url('admin/Pembeli'));
		}
	}
}
