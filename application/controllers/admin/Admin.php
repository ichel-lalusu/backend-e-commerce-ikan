<?php

/**
 * 
 */
class Admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata("data")) {
			$data_session = $this->session->userdata('data');
			if($data_session['usergroup']!=="admin"){
				redirect(base_url('admin/User/login'));
			}
		}
		$this->load->model("admin/Model_pemesanan", "Pemesanan");
		$this->load->model("admin/Modal_Penjual");
		$this->load->model("admin/Pembeli");
		$this->load->model("Model_user");
	}

	public function index()
	{
		$data_page = array(
			'title' => 'Home',
			'menu' => 'home',
		);
		$this->load->view('admin/dashboard', $data_page);
	}

	public function data_dashborad_admin()
	{
		$data_user = $this->input->post("data_user");
		$response = array();
		if ($data_user['usergroup'] == "admin") {
			$id_akun = $data_user['id_akun'];
			$username = $data_user['username'];
			$cekuser = $this->Model_user->cek_pengguna_by_id_akun_username($id_akun, $username);
			if ($cekuser->num_rows() > 0) {
				$jenis = "Terkirim";
				$select1 = "COUNT(*) AS total";
				$where1 = "status_pemesanan = '$jenis'";
				$DataPesananSelesai = $this->Pemesanan->get_where($select1, $where1)->row();
				$DataPenjual = $this->Modal_Penjual->get_all();
				$DataPembeli = $this->Pembeli->get_all();
				$jenis2 = "Terbayar";
				$select2 = "(select count(id_pengiriman) FROM data_detail_pengiriman WHERE id_pemesanan =  id_pemesanan) as total, status_pemesanan";
				$where2 = "status_pemesanan = '$jenis2'";
				$DataPesananOnDelivery = $this->Pemesanan->get_where($select2, $where2, NULL, NULL, 1)->row();
				$response = array(
					'data' => array(
						'title' => 'Home',
						'menu' => 'home',
						'data_pesanan_selesai' => $DataPesananSelesai,
						'data_penjual' => $DataPenjual->result(),
						'data_pembeli' => $DataPembeli->result(),
						'data_pesanan_on_delivery' => ($DataPesananOnDelivery == null) ? 0 : $DataPesananOnDelivery,
						'url_pesanan_selesai' => base_url('admin/Pemesanan/selesai'),
						'url_pesanan_on_delivery' => base_url('admin/Pemesanan/on_delivery'),
						'url_penjual' => base_url('admin/Penjual'),
						'url_pembeli' => base_url('admin/Pembeli')
					),
					'status' => "success",
					'code' => 200
				);
				return $this->output
					->set_status_header($response['code'])
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			} else {
				$response = array(
					'title' => 'Home',
					'menu' => 'home',
					'message' => 'Anda Bukan Admin yang sebenarnya',
					'status' => "failed",
					'code' => 400
				);
			}
		} else {
			$response = array(
				'title' => 'Home',
				'menu' => 'home',
				'message' => 'Anda Bukan Admin.',
				'status' => "failed",
				'code' => 404
			);
		}
		$this->output
			->set_status_header($response['code'])
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
}
