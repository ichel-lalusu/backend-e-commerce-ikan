<?php
/**
 * 
 */
class Keranjang extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("Model_keranjang");
		$this->load->model("Model_produk");
		$this->load->model("Model_penjual");
	}

	public function index()
	{
		$result_data = array();
		$keranjang = array();
		try {
			$id_akun = $this->input->get("id_akun");
			$result_data = $this->keranjang_init($id_akun);
		} catch (Exception $e) {
			$result_data['keranjang'] = $keranjang;
			$result_data['response_status'] = "not found " . $e->getMessage();
			$result_data['status_code'] = 500;
		}
		$this->output
			->set_status_header($result_data['status_code'])
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function simpan_keranjang()
	{
		$result_data = array();
		$keranjang = array();
		try {
			$simpan_keranjang = $this->Model_keranjang->simpan_keranjang_pembeli();
			// echo $this->db->last_query();
			if($simpan_keranjang){
				$id_akun = $this->input->post('id_akun');
				$result_data = $this->keranjang_init($id_akun);
			}else{
				$result_data['keranjang'] = $keranjang;
				$result_data['response_status'] = "Gagal Menyimpan";
				$result_data['status_code'] = 405;
			}
		} catch (Exception $e) {
			$result_data['keranjang'] = $keranjang;
			$result_data['response_status'] = "Gagal Menyimpan " . $e->getMessage();
			$result_data['status_code'] = 500;
		}
		$this->output
			->set_status_header($result_data['status_code'])
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	private function keranjang_init($id_akun)
	{
		$result_data = array();
		$data_keranjang = $this->Model_keranjang->get_pembeli_keranjang($id_akun);
		if($data_keranjang->num_rows() > 0){
			// var_dump($data_keranjang->result());
			foreach ($data_keranjang->result() as $key) {
				$data_produk = $this->Model_keranjang->get_keranjang_pembeli_by_usaha($id_akun, $key->id_usaha);
				if($data_produk->num_rows() > 0){
					$keranjang[$key->id_usaha] = $data_produk->result_array();
				}
			}
			if(count($keranjang) > 0){
				$result_data['keranjang'] = $keranjang;
				$result_data['response_status'] = "success";
				$result_data['status_code'] = 200;
			}
		}else{
			$result_data['keranjang'] = $keranjang;
			$result_data['response_status'] = "not found";
			$result_data['status_code'] = 404;
		}
		return $result_data;
	}
}