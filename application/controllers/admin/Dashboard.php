<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata("username")){
			redirect(base_url('admin/User/login'));
		}
		$this->load->model("admin/Model_pemesanan", "Pemesanan");
		$this->load->model("admin/Modal_Penjual");
		$this->load->model("admin/Pembeli");
	}

	public function index()
	{
		$menu = "home";

		$jenis = "Terkirim";
		$select1 = "COUNT(*) AS total";
		$where1 = "status_pemesanan = '$jenis'";
		$DataPesananSelesai = $this->Pemesanan->get_where($select1, $where1)->row();

		$DataPenjual = $this->Modal_Penjual->get_all();

		$DataPembeli = $this->Pembeli->get_all();

		$jenis2 = "Terbayar";
		$select2 = "(select count(id_pengiriman) FROM data_pengiriman WHERE data_pengiriman.id_pemesanan =  id_pemesanan) as total, status_pemesanan";
		$where2 = "status_pemesanan = '$jenis2'";
		$DataPesananOnDelivery = $this->Pemesanan->get_where($select2, $where2, NULL, NULL, 1)->row();
		$data_page = array('title' => 'Home', 
							'menu' => 'home', 
							'data_pesanan_selesai' => $DataPesananSelesai, 
							'data_penjual' => $DataPenjual, 
							'data_pembeli' => $DataPembeli,
							'data_pesanan_on_delivery' => $DataPesananOnDelivery);
		$this->load->view('dashboard', $data_page);
	}
}