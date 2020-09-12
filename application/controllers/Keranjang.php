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
		$this->load->model('Model_penjual');
	}

	public function index()
	{
		$result_data = array();
		$keranjang = array();
		try {
			if($this->input->get('id_akun')){
				$this->get_keranjang_pembeli();
			}else{
				$result_data = array(
					'message' => 'invalid request', 
					'keranjang' => $keranjang);
				response(400, $keranjang);
			}
			
		} catch (Exception $e) {
			$result_data['keranjang'] = $keranjang;
			$result_data['message'] = "Server error: " . $e->getMessage();
			response(500, $result_data);
		}
	}

	protected function get_keranjang_pembeli()
	{
		$id_akun = $this->input->get("id_akun");
		// print_r(cek_pb($id_akun));
		if(cek_pb($id_akun)){
			return $this->keranjang_init($id_akun);
		}else{
			unauthorize_user();
		}
	}

	public function simpan_keranjang()
	{
		$result_data = array();
		$keranjang = array();
		try {
			$id_akun = $this->input->post('id_akun', TRUE);
			// echo $id_akun;
			if(cek_pb($id_akun)){
				$id_usaha = $this->input->post('id_usaha', TRUE);
				$id_akun = $this->input->post('id_akun', TRUE);
				$id_variasi_produk = $this->input->post('variasi', TRUE);
				$id_produk = $this->input->post('id_produk', TRUE);
				$jml_produk = intval($this->input->post('qty', TRUE));
				$harga_produk = $this->input->post('harga_produk', TRUE);
				$ikan_per_kg = $this->input->post('ikan_per_kg', TRUE);
				$potong_per_ekor = $this->input->post('potong_per_ekor', TRUE);
				$distance = floatval($this->input->post('distance', TRUE));
				$estimasi_ongkir = intval($this->input->post('estimasi_ongkir', TRUE));
				$sub_total = ($jml_produk * $harga_produk);
				$simpan_keranjang = FALSE;

				$cek_di_keranjang = $this->Model_keranjang->get_detail_produk_keranjang_pembeli($id_variasi_produk, $id_akun);
				if($cek_di_keranjang->num_rows() > 0){
					// KONDISI SAAT ADA DI DATABASE
					$data_keranjang = $cek_di_keranjang->row();
					$simpan_keranjang = $this->update_keranjang($data_keranjang, $id_akun, $id_variasi_produk, $jml_produk, $ikan_per_kg, $potong_per_ekor, $distance, $estimasi_ongkir, $sub_total);
				}else{
					// KONDISI SAAT TIDAK ADA DI DATABASE, CREATE DATA
					$simpan_keranjang = $this->create_keranjang($id_usaha, $id_akun, $id_variasi_produk, $id_produk, $jml_produk, $harga_produk, $ikan_per_kg, $potong_per_ekor, $distance, $estimasi_ongkir, $sub_total);
				}
				if($simpan_keranjang){
					$this->keranjang_init($id_akun);
				}else{
					response(400, array('message' => "Gagal Menyimpan"));
				}
			}else{
				unauthorize_user();
			}
		} catch (Exception $e) {
			response(500, array('message' => "Gagal menyimpan : " . $e->getMessage()));
		}
	}

	protected function update_keranjang($data_keranjang, $id_akun=null, $id_variasi_produk=null, $jml_produk=0, $ikan_per_kg=0, $potong_per_ekor=null, $distance=0, $estimasi_ongkir=0, $sub_total=0)
	{
		$data_update = array('jml_produk' => intval($jml_produk + $data_keranjang->jml_produk), 
								'sub_total' => intval($sub_total + $data_keranjang->sub_total), 
								'ikan_per_kg' => $ikan_per_kg,
								'potong_per_ekor' => $potong_per_ekor,
								'distance' => $distance,
								'estimasi_ongkir' => $estimasi_ongkir);
		$where_update = "id_pb = '$id_akun' AND id_variasi_produk = '$id_variasi_produk'";
		return $this->Model_keranjang->update_keranjang_akun_yang_sudah_ada($where_update, $data_update);
	}

	protected function create_keranjang($id_usaha=null, $id_akun=null, $id_variasi_produk=null, $id_produk=null, $jml_produk=0, $harga_produk=0, $ikan_per_kg=0, $potong_per_ekor=null, $distance=0, $estimasi_ongkir=0, $sub_total=0)
	{
		$data = array(
						'id_produk' => $id_produk,
						'jml_produk' => intval($jml_produk), 
						'sub_total' => intval($sub_total), 
						'id_usaha' => $id_usaha, 
						'id_pb' => $id_akun, 
						'id_variasi_produk' => $id_variasi_produk,
						'harga_produk' => intval($harga_produk),
						'ikan_per_kg' => $ikan_per_kg,
						'potong_per_ekor' => $potong_per_ekor,
						'distance' => $distance,
						'estimasi_ongkir' => $estimasi_ongkir,
						'created_date' => date('Y-m-d H:i:s'));
		return $this->Model_keranjang->create_data_keranjang_variasi_produk($data);
	}

	protected function keranjang_init($id_akun)
	{
		$keranjang = array();
		$result_data = array();
		$data_keranjang = $this->Model_keranjang->get_id_usaha_in_keranjang_pembeli($id_akun);
		if($data_keranjang->num_rows() > 0){
			foreach ($data_keranjang->result() as $key) {
				$data_produk = $this->Model_keranjang->get_keranjang_pembeli_by_usaha($id_akun, $key->id_usaha);
				if($data_produk->num_rows() > 0){
					$data_usaha = $this->Model_penjual->ambil_usaha_by_id($key->id_usaha)->row_array();
					$keranjang[] = array('id_usaha' => $key->id_usaha,
										'nama_usaha' => $data_usaha['nama_usaha'],
										'data_produk' => $data_produk->result_array(),
										'detail_usaha' => $data_usaha);
				}
			}
			if(count($keranjang) > 0){
				response(200, $keranjang);
			}
		}else{
			response(404, array('message' => "Not found"));
		}
		// return $result_data;
	}

	public function delete_keranjang()
	{
		try {
			if($this->input->get('id_keranjang')){
				$this->delete_by_id_keranjang();
			}elseif($this->inpit->get('id_usaha')){
				$this->delete_keranjang_by_id_usaha();
			}else{
				response(400, array('message' => 'request failed'));
			}
		} catch (Exception $e) {
			response(500, array('message' => 'error ' . $e->getMessage()));
			$result_data['keranjang'] = array();
			$result_data['response_status'] = "not found";
			$result_data['status_code'] = 404;
		}
	}

	protected function delete_keranjang_by_id_usaha()
	{
		$id_usaha = $this->input->get('id_usaha', TRUE);
		$id_user = $this->input->get('id_akun', TRUE);
		if(cek_pb($id_user)){
			$delete = $this->Model_keranjang->delete_keranjang_by_id_usaha($id_usaha, $id_user);
			if($delete){
				response(200, array('message' => "Success"));
			}else{
				response(404, array('message' => "Failed"));
			}
		}else{
			unauthorize_user();
		}
	}

	protected function delete_by_id_keranjang()
	{
		$id_keranjang = $this->input->get('id_keranjang', TRUE);
		$id_user = $this->input->get('id_akun', TRUE);
		if(cek_pb($id_user)){
			$delete = $this->Model_keranjang->delete_keranjang_by_id_keranjang($id_keranjang);
			if($delete){
				$result =  array('message' => 'success');
				response(200, $result);
			}else{
				$result =  array('message' => 'failed');
				response(404, $result);
			}
		}else{
			unauthorize_user();
		}
	}
}