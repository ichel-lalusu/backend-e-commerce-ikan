<?php 
/**
 * 
 */
class Penjual extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		$this->load->model('Model_penjual', 'penjual');
		$this->load->model('Model_rekening', 'rekening');
		$this->load->model('Model_kendaraan', 'kendaraan');
		$this->load->model("Model_user");
	}

	public function index()
	{
		echo "Ini adalah class penjual/index";
	}

	private function failed_sign_up($term, $length_term = NULL)
	{
		$status = 500;
		$_error_message = "Maaf " . $term . " wajib diisi.";
		if ($length_term !== NULL) {
			$_error_message .= " " . $term . " minimal " . $length_term . " karakter.";
		}
		$this->output
			->set_status_header($status)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode(array('status' => "failed", 'message' => $_error_message), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	private function username_existed($username)
	{
		$status = 500;
		$_error_message = "Maaf username $username sudah pernah ada";
		$body = array('status' => "failed", 'message' => $_error_message);
		$this->response($status, $body);
		// $this->output
		// 	->set_status_header($status)
		// 	->set_content_type('application/json', 'utf-8')
		// 	->set_output(json_encode($ JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	private function response($status_header, $body = array())
	{
		$this->output
			->set_status_header($status_header)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	// PROSES DAFTAR PENJUAL
	public function prosessignuppenjual()
	{
		$username		= (!empty($this->input->post('username'))) ? $this->input->post('username') : $this->failed_sign_up("Username");
		$password	 	= (!empty($this->input->post('password'))) ? $this->input->post('password') : $this->failed_sign_up("Password");
		$nama_pj		= (!empty($this->input->post('nama_pj'))) ? $this->input->post('nama_pj') : $this->failed_sign_up("Nama Lengkap");
		$noktp_pj		= (!empty($this->input->post('noktp_pj'))) ? $this->input->post('noktp_pj') : $this->failed_sign_up("No. KTP");
		$jk_pj			= (!empty($this->input->post('jk_pj'))) ? $this->input->post('jk_pj') : $this->failed_sign_up("Jenis Kelamin");
		$tgllahir_pj 	= (!empty($this->input->post('tgllahir_pj'))) ? $this->input->post('tgllahir_pj') : $this->failed_sign_up("Tanggal Lahir");
		$alamat_pj		= (!empty($this->input->post('alamat_pj'))) ? $this->input->post('alamat_pj') : $this->failed_sign_up("Alamat Lengkap");
		$telp_pj		= (!empty($this->input->post('telp_pj'))) ? $this->input->post('telp_pj') : $this->failed_sign_up("No. Telp");
		$jenis_petani	= (!empty($this->input->post('jenis_petani'))) ? $this->input->post('jenis_petani') : $this->failed_sign_up("Jenis Petani");
		// print_r($this->input->post());
		// exit();
		$response = array();
		$status_header = 100;

		// CEK USERNAME EXISTING
		$this->load->model("Model_user", "user");
		try {
			$cek_username = $this->user->check_username($username);
			if ($cek_username->num_rows() > 0) {
				$this->username_existed($username);
			}

			$data_penjual_array = array(
				'nama_pj' => $nama_pj,
				'noktp_pj' => $noktp_pj,
				'jk_pj' => $jk_pj,
				'tgllahir_pj' => $tgllahir_pj,
				'alamat_pj' => $alamat_pj,
				'telp_pj' => $telp_pj,
				'jenis_petani' => $jenis_petani
			);

			$file_name						= date('dmYHis') . $_FILES['foto_pj']['name'];
			// var_dump($_FILES);
			$config['upload_path']          = './foto_penjual/';
			$config['allowed_types']        = 'gif|jpg|jpeg|png';
			$config['max_size']             = 20480;
			$config['max_width']            = 5000;
			$config['max_height']           = 5000;
			$config['file_name']			= $file_name;

			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('foto_pj')){
				$dataFoto = array('error' => $this->upload->display_errors());
			} else {

				$dataFoto = array('upload_data' => $this->upload->data());
				$config = array();
				$config['image_library'] = 'gd2';
				$config['source_image'] = './foto_penjual/' . $file_name;
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = FALSE;
				$config['quality'] = '50%';
				$config['width'] = 640;
				$config['height'] = 640;
				$config['new_image'] = './foto_penjual/' . $file_name;
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				// var_dump($dataFoto);
				// exit();
				$foto_penjual		=   $file_name;
				$data_penjual_array['foto_pj'] = $foto_penjual;
			}


			if (!empty($_FILES['fotoktp_pj'])) {
				$path = "./foto_ktp_penjual/";
				$file_name_KTP = basename($_FILES['fotoktp_pj']['name']);
				$path = $path . $file_name_KTP;

				if (move_uploaded_file($_FILES['fotoktp_pj']['tmp_name'], $path)) {
					$config = array();
					$config['image_library'] = 'gd2';
					$config['source_image'] = $path;
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = FALSE;
					$config['quality'] = '50%';
					$config['width'] = 640;
					$config['height'] = 640;
					$config['new_image'] = $path;
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();
					$data_penjual_array['fotoktp_pj'] = $file_name_KTP;
				} else {
					$dataFoto = $_FILES['fotoktp_pj']['error'];
				}
			}


			// if (is_array($_FILES)) {
			// 	if (is_uploaded_file($_FILES['foto_pj']['tmp_name'])) {
			// 		$sourcePath2 = $_FILES['foto_pj']['tmp_name'];
			// 		$foto_pj = date('dmYHis') . $_FILES['foto_pj']['name'];
			// 		$targetPath2 = "./foto_penjual/" . $foto_pj;
			// 		move_uploaded_file($sourcePath2, $targetPath2);
			// 	} if (is_uploaded_file($_FILES['fotoktp_pj']['tmp_name'])) {
			// 		$sourcePath2 = $_FILES['fotoktp_pj']['tmp_name'];
			// 		$fotoktp_pj = date('dmYHis') . $_FILES['fotoktp_pj']['name'];
			// 		$targetPath2 = "./foto_ktp_penjual/" . $fotoktp_pj;
			// 		move_uploaded_file($sourcePath2, $targetPath2);
			// 	} if (is_uploaded_file($_FILES['foto_toko']['tmp_name'])) {
			// 		$sourcePath2 = $_FILES['foto_toko']['tmp_name'];
			// 		$foto_toko = date('dmYHis') . $_FILES['foto_toko']['name'];
			// 		$targetPath2 = "./foto_toko/" . $foto_toko;
			// 		move_uploaded_file($sourcePath2, $targetPath2);
			// 	}
			$INSERT_DATA_PENJUAL = $this->penjual->insert_penjual($data_penjual_array);
			if ($INSERT_DATA_PENJUAL) {
				$id_pj = $this->db->insert_id();
				$data_pengguna_array = array(
					'username' => $username,
					'password' => $password,
					'id_akun' => $id_pj,
					'level_user' => 'penjual'
				);
				$INSERT_DATA_PENGGUNA = $this->user->insert_user_sign($data_pengguna_array);
				if ($INSERT_DATA_PENGGUNA) {
					$status_header = 200;
					$status = 'sukses';
					$message = 'berhasil tambahkan data';
					$response = array(
						'id_akun' => $id_pj,
						'status' => $status,
						'message' => $message
					);
				} else {
					$status_header = 400;
					$status = 'gagal';
					$message = 'gagal tambahkan data pengguna karena . ' . $this->db->_error_message();
					$rolling_back_data_penjual = $this->penjual->delete_penjual($id_pj);
					$response = array(
						'status' => $status,
						'message' => $message
					);
				}
			} else {
				$status_header = 404;
				$status = 'gagal';
				$message = 'gagal tambahkan data penjual';
				$response = array(
					'status' => $status,
					'message' => $message
				);
			}
		} catch (Exception $e) {
			$status_header = 500;
			$status = 'error';
			$message = 'server error ' . $e->getMessage();
			$response = array(
				'status' => $status,
				'message' => $message
			);
		}
		$this->response($status_header, $response);
	}

	public function prosesUpdatePenjual()
	{
		$nama_pj = $this->input->post('nama_pj');
		$noktp_pj = $this->input->post('noktp_pj');
		$jk_pj = $this->input->post('jk_pj');
		$tgllahir_pj = $this->input->post('tgllahir_pj');
		$telp_pj = $this->input->post('telp_pj');
		$alamat_pj = $this->input->post('alamat_pj');
		$id_akun = $this->input->post('id_akun');


		try {
			$data_penjual = $this->penjual->cek_penjual($id_akun);
			if ($data_penjual->num_rows() > 0) {
			}
			$updateArray = array(
				'nama_pj' => $nama_pj,
				'noktp_pj' => $noktp_pj,
				'jk_pj' => $jk_pj,
				'tgllahir_pj' => $tgllahir_pj,
				'alamat_pj' => $alamat_pj,
				'telp_pj' => $telp_pj
			);

			$file_name						= date('dmYHis') . $_FILES['foto_pj']['name'];
			// var_dump($_FILES);
			$configPJ['upload_path']          = './foto_penjual/';
			$configPJ['allowed_types']        = 'gif|jpg|jpeg|png';
			$configPJ['max_size']             = 20480;
			$configPJ['max_width']            = 5000;
			$configPJ['max_height']           = 5000;
			$configPJ['remove_spaces']		  = TRUE;
			// $configPJ['encrypt_name']		  = TRUE;
			$configPJ['file_name']			= $file_name;

			$this->load->library('upload', $configPJ);
			if (!$this->upload->do_upload('foto_pj')) {
				$dataFoto = array('error' => $this->upload->display_errors());
			} else {

				$dataFoto = array('upload_data' => $this->upload->data());
				// var_dump($dataFoto);
				$config = array();
				$config['image_library'] = 'gd2';
				$config['source_image'] = './foto_penjual/' . $file_name;
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = FALSE;
				$config['quality'] = '50%';
				$config['width'] = 640;
				$config['height'] = 640;
				$config['new_image'] = './foto_penjual/' . $file_name;
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				// var_dump($dataFoto);
				// exit();
				$foto_penjual		=   $file_name;
				$updateArray['foto_pj'] = $dataFoto['upload_data']['file_name'];
			}
			// exit();

			if (!empty($_FILES['fotoktp_pj'])) {
				$path = "./foto_ktp_penjual/";
				$file_name_KTP = basename($_FILES['fotoktp_pj']['name']);
				$path = $path . $file_name_KTP;

				if (move_uploaded_file($_FILES['fotoktp_pj']['tmp_name'], $path)) {
					$config = array();
					$config['image_library'] = 'gd2';
					$config['source_image'] = $path;
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = FALSE;
					$config['quality'] = '50%';
					$config['width'] = 640;
					$config['height'] = 640;
					$config['new_image'] = $path;
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();
					$updateArray['fotoktp_pj'] = $file_name_KTP;
				} else {
					$dataFoto = $_FILES['fotoktp_pj']['error'];
				}
			}
			$updating = $this->penjual->update_penjual($updateArray, $id_akun);


			if ($updating) {
				$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode(array('status' => "success"), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			} else {
				$this->output
					->set_status_header(404)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode(array('status' => "failed"), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}
		} catch (Exception $e) {
			$this->output
				->set_status_header(500)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode(array('status' => "failed " . $e->getMessage()), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}

	public function rollback()
	{
		$id_pj = $this->input->post('id_akun');
		$response = array();
		$status_header = 500;
		try {
			$cek_penjual = $this->penjual->cek_penjual($id_pj);
			if($cek_penjual->num_rows() > 0){
				$this->load->model("Model_user", "user");
				$rolling_back_pengguna = $this->user->delete_pengguna($id_pj);
				$rolling_back_penjual = $this->penjual->delete_penjual($id_pj);
				if($rolling_back_penjual && $rolling_back_pengguna){
					$status_header = 200;
					$response = array('status' => 'berhasil', 'message' => 'Berhasil');
				} else {
					$status_header = 400;
					$response = array('status' => 'gagal', 'message' => 'Gagal');
				}
			} else {
				$status_header = 404;
				$response = array('status' => 'gagal', 'message' => 'data tidak ditemukan');
			}
		} catch (Exception $e) {
			$status_header = 500;
			$response = array('status' => 'error', 'message' => 'Error ' . $e->getMessage());
		}

		$this->response($status_header, $response);
	}

	public function ambil_semua()
	{
		$ambil_data = $this->penjual->ambil_semua();
		if ($ambil_data->num_rows() > 0) {
			$data_json = $ambil_data->result_array();
			header("Content-type: application/json");
			echo json_encode($data_json);
		} else {
		}
	}

	public function ambil_data_profile()
	{
		$id_pj = $this->input->get('id_akun');
		$data = $this->penjual->data_profile($id_pj);
		$data_profile = $data->row();

		header("Content-type: application/json");
		echo json_encode($data_profile, JSON_PRETTY_PRINT);
	}

	public function detail(int $id_pj)
	{
		$data = $this->penjual->data_profile($id_pj);
		$data_profile = $data->row();

		header("Content-type: application/json");
		echo json_encode($data_profile, JSON_PRETTY_PRINT);
	}

	public function detail_usaha()
	{
		$id_usaha = $this->input->get('id_usaha');
		$data = $this->penjual->ambil_usaha_by_id($id_usaha);
		$data_usaha = $data->row_array();
		// header("Content-type: application/json");
		// echo json_encode($data_usaha);
		response(200, $data_usaha);
	}

	public function prosessignupusaha()
	{
		$id_pj 			= $this->input->post('id_pj');
		$nama_usaha		= $this->input->post('nama_usaha');
		$alamat_usaha	= $this->input->post('alamat_usaha');
		$jamBuka		= (!empty($this->input->post('jamBuka'))) ? $this->input->post('jamBuka') : $this->failed_sign_up("Jam Buka");
		$jamTutup		= (!empty($this->input->post('jamTutup'))) ? $this->input->post('jamTutup') : $this->failed_sign_up("Jam Tutup");
		$jml_kolam 		= (!empty($this->input->post('jml_kolam'))) ? $this->input->post('jml_kolam') : 0;
		$jml_kapal		= (!empty($this->input->post('jml_kapal'))) ? $this->input->post('jml_kapal') : 0;
		$kapasitas_kapal = (!empty($this->input->post('kapasitas_kapal'))) ? $this->input->post('kapasitas_kapal') : 0;
		$kab_usaha 		= $this->input->post('kab_usaha');
		$kec_usaha 		= $this->input->post('kec_usaha');
		$kel_usaha 		= $this->input->post('kel_usaha');
		$longitude 		= $this->input->post('longitude');
		$latitude 		= $this->input->post('latitude');
		// print_r($this->input->post());
		// exit();
		$response 		= array();
		$status_header 	= 500;
		try {
			$cek_penjual = $this->penjual->cek_penjual($id_pj);
			if ($cek_penjual->num_rows() > 0) {
				$data_insert_usaha = array(
					'nama_usaha' => $nama_usaha,
					'alamat_usaha' => $alamat_usaha,
					'jml_kolam' => $jml_kolam,
					'jml_kapal' => $jml_kapal,
					'kapasitas_kapal' => $kapasitas_kapal,
					'kab' => $kab_usaha,
					'kec' => $kec_usaha,
					'kel' => $kel_usaha,
					'longitude' => $longitude,
					'latitude' => $latitude,
					'id_pj' => $id_pj,
					'jamBuka' => $jamBuka,
					'jamTutup' => $jamTutup
				);

				// CEK foto_usaha
				// UPLOAD FOTO USAHA
				if(isset($_FILES['foto_usaha'])){
					$file_name						= date('dmYHis') . $_FILES['foto_usaha']['name'];
					// var_dump($_FILES);
					$config['upload_path']          = './foto_usaha/';
					$config['allowed_types']        = 'gif|jpg|jpeg|png';
					$config['max_size']             = 20480;
					$config['max_width']            = 5000;
					$config['max_height']           = 5000;
					$config['file_name']			= $file_name;

					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('foto_usaha')) {
						$dataFoto = array('error' => $this->upload->display_errors());
					} else {

						$dataFoto = array('upload_data' => $this->upload->data());
						$config = array();
						$config['image_library'] = 'gd2';
						$config['source_image'] = './foto_usaha/' . $file_name;
						$config['create_thumb'] = FALSE;
						$config['maintain_ratio'] = FALSE;
						$config['quality'] = '50%';
						$config['width'] = 640;
						$config['height'] = 640;
						$config['new_image'] = './foto_usaha/' . $file_name;
						$this->load->library('image_lib', $config);
						$this->image_lib->resize();
						// var_dump($dataFoto);
						// exit();
						$foto_pembeli		=   $file_name;
						$data_insert_usaha['foto_usaha'] = $foto_pembeli;
					}
				}
				$INSERT_USAHA = $this->Model_user->insert_usaha($data_insert_usaha);
				if ($INSERT_USAHA) {
					$status_header = 200;
					$response = array('status' => 'success', 'message' => "Berhasil Lengkapi Detail Usaha");
				} else {
					$status_header = 400;
					$response = array('status' => 'failed', 'message' => "Gagal Lengkapi Detail Usaha");
				}
			} else {
				$status_header = 404;
				$response = array('status' => 'failed', 'message' => "Gagal Lengkapi Detail Usaha Karena Data Penjual Tidak Ditemukan");
			}
		} catch (Exception $e) {
			$status_header = 400;
			$response = array('status' => 'error', 'message' => "Gagal Lengkapi Detail Usaha " . $e->getMessage());
		}
		$this->response($status_header, $response);
	}

	public function prosesupdateprofileusaha()
	{
		$id_pj 			= $this->input->post('id_akun');
		$id_usaha		= $this->input->post('id_usaha');
		$nama_usaha		= $this->input->post('nama_usaha');
		$alamat_usaha	= $this->input->post('alamat_usaha');
		$jamBuka		= (!empty($this->input->post('jamBuka'))) ? $this->input->post('jamBuka') : $this->failed_sign_up("Jam Buka");
		$jamTutup		= (!empty($this->input->post('jamTutup'))) ? $this->input->post('jamTutup') : $this->failed_sign_up("Jam Tutup");
		$jml_kolam 		= (!empty($this->input->post('jml_kolam'))) ? $this->input->post('jml_kolam') : 0;
		$jml_kapal		= (!empty($this->input->post('jml_kapal'))) ? $this->input->post('jml_kapal') : 0;
		$kapasitas_kapal = (!empty($this->input->post('kapasitas_kapal'))) ? $this->input->post('kapasitas_kapal') : 0;
		$kab_usaha 		= $this->input->post('kab_usaha');
		$kec_usaha 		= $this->input->post('kec_usaha');
		$kel_usaha 		= $this->input->post('kel_usaha');
		$longitude 		= $this->input->post('longitude');
		$latitude 		= $this->input->post('latitude');
		// var_dump($this->input->post());
		$response 		= array();
		$status_header 	= 500;
		try {
			$cek_penjual = $this->penjual->cek_penjual($id_pj);
			$cek_usaha = $this->penjual->cek_usaha($id_pj, $id_usaha);
			if ($cek_penjual->num_rows() > 0 && $cek_usaha->num_rows() > 0) {
				$data_insert_usaha = array(
					'nama_usaha' => $nama_usaha,
					'alamat_usaha' => $alamat_usaha,
					'jml_kolam' => $jml_kolam,
					'jml_kapal' => $jml_kapal,
					'kapasitas_kapal' => $kapasitas_kapal,
					'kab' => $kab_usaha,
					'kec' => $kec_usaha,
					'kel' => $kel_usaha,
					'longitude' => $longitude,
					'latitude' => $latitude,
					'id_pj' => $id_pj,
					'jamBuka' => $jamBuka,
					'jamTutup' => $jamTutup
				);


				// CEK foto_usaha
				// UPLOAD FOTO USAHA
				$file_name						= date('dmYHis') . $_FILES['foto_usaha']['name'];
				// var_dump($_FILES);
				$config['upload_path']          = './foto_usaha/';
				$config['allowed_types']        = 'gif|jpg|jpeg|png';
				$config['max_size']             = 20480;
				$config['max_width']            = 5000;
				$config['max_height']           = 5000;
				$config['file_name']			= $file_name;

				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('foto_usaha')) {
					$dataFoto = array('error' => $this->upload->display_errors());
				} else {

					$dataFoto = array('upload_data' => $this->upload->data());
					$config = array();
					$config['image_library'] = 'gd2';
					$config['source_image'] = './foto_usaha/' . $file_name;
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = FALSE;
					$config['quality'] = '50%';
					$config['width'] = 640;
					$config['height'] = 640;
					$config['new_image'] = './foto_usaha/' . $file_name;
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();
					// var_dump($dataFoto);
					// exit();
					$foto_pembeli		=   $file_name;
					$data_insert_usaha['foto_usaha'] = $foto_pembeli;
				}
				// var_dump($data_insert_usaha);
				$UPDATE_USAHA = $this->penjual->update_usaha($data_insert_usaha, $id_usaha);
				// echo $this->db->last_query();
				if ($UPDATE_USAHA) {
					$status_header = 200;
					$response = array('status' => 'success', 'message' => "Berhasil Lengkapi Detail Usaha");
					$response['data_usaha'] = $cek_usaha->row_array();
				} else {
					$status_header = 400;
					$response = array('status' => 'failed', 'message' => "Gagal Lengkapi Detail Usaha");
				}
			} else {
				$status_header = 404;
				$response = array('status' => 'failed', 'message' => "Gagal Lengkapi Detail Usaha Karena Data Penjual Tidak Ditemukan");
			}
		} catch (Exception $e) {
			$status_header = 400;
			$response = array('status' => 'error', 'message' => "Gagal Lengkapi Detail Usaha " . $e->getMessage());
		}
		$this->response($status_header, $response);
	}

	public function ambil_data_kel_tani()
	{
		$id_pj = $this->input->get('id_akun');
		$data = $this->penjual->data_kel_tani_penjual($id_pj);
		$hasil = $data->result_array();
		$this->response(200, $hasil);
	}

	public function ambil_data_tani()
	{
		$id_pj = $this->input->get('id_akun');
		$data_semua_tani = $this->penjual->data_kelompok_tani();
		$data_kel_tani_pj = $this->penjual->data_kel_tani_penjual($id_pj);
		$hasil = array();
		foreach ($data_semua_tani->result() as $key) {
			$cek = $this->penjual->bandingkan_kel_tani_pj($id_pj, $key->id_kelompoktani);
			if ($cek->num_rows() > 0) {
				$check = "checked='checked'";
			} else {
				$check = "";
			}
			$hasil['data'][] = array(
				'id' => $key->id_kelompoktani,
				'label' => $key->nama_kelompoktani,
				'check' => $check
			);
			$hasil['status'] = "success";
		}
		$this->response(200, $hasil);
	}

	public function ambil_data_taniV2()
	{
		// 
	}

	public function ambil_data_usaha()
	{
		$id_akun = $this->input->post('id_akun');
		$data = $this->penjual->ambil_data_usaha($id_akun)->row();
		$this->response(200, $data);
	}

	public function ambil_data_usaha_with_pj()
	{
		$id_akun = $this->input->get('id_akun');
		$data = array();
		try {
			$data = $this->penjual->ambil_data_usaha_with_pj($id_akun)->row_array();
			$data['response'] = "success";
			$this->response(200, $data);
		} catch (Exception $e) {
			$data = array('message' => 'Data not found');
			$this->response(404, $data);
		}
	}

	public function simpan_kel_tani()
	{
		$id_akun = $this->input->post('id_akun');
		$kel_tani = $this->input->post('kel_tani');
		if ($kel_tani !== "") :
			// $kel_tani = explode(',', $kel_tani);
			$total = count($this->input->post('kel_tani'));

			$id_usaha = $this->penjual->ambil_data_usaha($id_akun)->row()->id_usaha;

			$delete = $this->penjual->delete_kel_tani($id_usaha);
			if ($total > 0) {
				if ($total > 1) {
					for ($i = 0; $i < $total; $i++) {
						$data[] = array(
							'id_kelompoktani' => $kel_tani[$i],
							'id_usaha' => $id_usaha
						);
					}
					$insert = $this->penjual->insert_tani_multi($data);
				} else {
					$data = array(
						'id_kelompoktani' => $kel_tani[0],
						'id_usaha' => $id_usaha
					);
					$insert = $this->penjual->insert_tani($data);
				}
				if ($insert) {
					$status = "berhasil";
				} else {
					$status = "gagal";
				}
			} else {
				$status = "kosong";
			}
		else :
			$status = "kosong";
		endif;
		$respons = array('status' => $status);
		header("Content-type: application/json");
		echo json_encode($respons);
	}

	// PROSES JAM PENGIRIMAN USAHA
	public function ambil_jam_pengiriman_usaha()
	{
		$id_usaha = $this->input->get('id_usaha');
		$data = $this->penjual->ambil_jam_pengiriman_usaha($id_usaha)->result_array();
		header("Content-type: application/json");
		echo json_encode($data);
	}

	public function simpan_jam_pengiriman_usaha()
	{
		$id_usaha = $this->input->post('id_usaha');
		$jam = $this->input->post('jam');
		$data = array(
			'id_usaha' => $id_usaha,
			'jam_pengiriman' => $jam
		);
		$proses = $this->penjual->simpan_jam_pengiriman_usaha($data);
		if ($proses) {
			$status = "berhasil";
		} else {
			$status = "gagal";
		}

		$respons = array('status' => $status);
		header("Content-type: application/json");
		echo json_encode($respons);
	}

	public function ambil_jam_pengiriman_usaha_by_id()
	{
		$id_jampengiriman = $this->input->get('id_jampengiriman');
		$data = $this->penjual->ambil_jam_pengiriman_usaha_by_id($id_jampengiriman)->row_array();
		header("Content-type: application/json");
		echo json_encode($data);
	}

	public function ubah_jam_pengiriman_usaha()
	{
		$id_jampengiriman = $this->input->post('id_jampengiriman');
		$jam = $this->input->post('jam');
		$data = array(
			'jam_pengiriman' => $jam
		);
		$proses = $this->penjual->ubah_jam_pengiriman_usaha($data, $id_jampengiriman);
		if ($proses) {
			$status = "berhasil";
		} else {
			$status = "gagal";
		}

		$respons = array('status' => $status);
		header("Content-type: application/json");
		echo json_encode($respons);
	}

	public function hapus_jam_pengiriman_usaha()
	{
		$id_jampengiriman = $this->input->post('id_jampengiriman');
		$proses = $this->penjual->hapus_jam_pengiriman_usaha($id_jampengiriman);
		if ($proses) {
			$status = "berhasil";
		} else {
			$status = "gagal";
		}

		$respons = array('status' => $status);
		header("Content-type: application/json");
		echo json_encode($respons);
	}
	// END PROSES JAM PENGIRIMAN USAHA



	public function ambil_data_lokasi_penjual()
	{
		$data_penjual = $this->penjual->ambil_lokasi_usaha();
		// echo $this->db->last_query();
		// exit();
		header("Content-type: application/json");
		echo json_encode($data_penjual->result_array(), JSON_PRETTY_PRINT);
	}

	public function getKendaraanUsaha()
	{
		$id_usaha = $this->input->get('id_usaha');
		$data = $this->kendaraan->getKendaraanUsaha($id_usaha);
		// echo $this->db->last_query();
		if ($data->num_rows() > 0) {
			$response['data_kendaraan'] = $data->result();
			$response['status'] = 'sukses';
			response(200, $response);
			// $this->load->view('penjual/DataKendaraanPenjual', array('data' => $data));
			// $result = array('dataKendaraan' => $data->result_array(),
			// 				'responseMessage' => 'success',
			// 				'responseCode' => '00');
			// echo json_encode($result, JSON_PRETTY_PRINT);
		} else {
			$response['data_kendaraan'] = array();
			$response['status'] = 'kosong';
			response(404, $response);
		}
	}

	public function get_detail_kendaraan_usaha()
	{
		$id_kendaraan = $this->input->get('id_kendaraan');
		$data = $this->kendaraan->get_detail_kendaraan($id_kendaraan);
		if ($data->num_rows() > 0) {
			$result = array(
				'dataKendaraan' => $data->row_array(),
				'responseMessage' => 'success'
			);
			echo json_encode($result, JSON_PRETTY_PRINT);
		} else {
			$result = array(
				'dataKendaraan' => null,
				'responseMessage' => 'failed'
			);
			echo json_encode($result, JSON_PRETTY_PRINT);
		}
	}

	public function simpanKendaraanUsaha()
	{
		$jenis_kendaraan = $this->input->post('jenis_kendaraan');
		$plat_kendaraan = $this->input->post('plat_kendaraan');
		$kapasitas_kendaraan = $this->input->post('kapasitas_kendaraan');
		$id_usaha = $this->input->post('id_usaha');
		$array_insert = array('jenis_kendaraan' => $jenis_kendaraan, 'plat_kendaraan' => $plat_kendaraan, 'kapasitas_kendaraan' => $kapasitas_kendaraan, 'id_usaha' => $id_usaha);
		$insert = $this->db->insert("data_kendaraan", $array_insert);
		if ($this->db->affected_rows() > 0) {
			$array = array('status' => "berhasil", 'message' => 'Berhasil Tambahkan Kendaraan');
			$this->output
				->set_status_header(201)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		} else {
			$array = array('status' => "gagal", 'message' => "Gagal Tambahkan Kendaraan");
			$this->output
				->set_status_header(404)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}

	public function UpdateKendaraanUsaha()
	{
		$id_kendaraan = $this->input->post('id_kendaraan');
		$jenis_kendaraan =  $this->input->post('jenis_kendaraan');
		$plat_kendaraan = $this->input->post('plat_kendaraan');
		$kapasitas_kendaraan = $this->input->post('kapasitas_kendaraan');
		$id_usaha = $this->input->post('id_usaha');

		$where = 'id_kendaraan = ' . $id_kendaraan;
		$data = array('jenis_kendaraan' => $jenis_kendaraan, 'plat_kendaraan' => $plat_kendaraan, 'kapasitas_kendaraan' => $kapasitas_kendaraan);
		$update = $this->kendaraan->updateKendaraan($data, $where);
		if ($update) {
			echo "success";
		} else {
			echo "failed";
		}
	}

	public function hapusKendaraan()
	{
		$id = $this->input->post('id_kendaraan');
		$delete = $this->db->delete("data_kendaraan", "id_kendaraan = '$id'", 1);
		if ($delete) {
			$array = array('status' => "berhasil", 'message' => 'Berhasil Menghapus Kendaraan');
			$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		} else {
			$array = array('status' => "gagal", 'message' => 'Gagal Menghapus Kendaraan');
			$this->output
				->set_status_header(400)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}


	// ADMIN CUSTOME

	public function all_penjual()
	{
		try {
			$data_penjual = $this->penjual->get_all();
			foreach ($data_penjual->result() as $data_pj) {
				$query_usaha = 
				$result_data_pj[] = array(
					'id_pj' => $data_pj->id_pj,
					'nama_pj' => $data_pj->nama_pj,
					'foto_pj' => $data_pj->foto_pj,
					'noktp_pj' => $data_pj->noktp_pj,
					'fotoktp_pj' => $data_pj->fotoktp_pj,
					'jk_pj' => $data_pj->jk_pj,
					'tgllahir_pj' => $data_pj->tgllahir_pj,
					'alamat_pj' => $data_pj->alamat_pj,
					'telp_pj' => $data_pj->telp_pj,
					'jenis_petani' => $data_pj->jenis_petani,
					'data_usaha' => $this->penjual->ambil_data_usaha($data_pj->id_pj)->row()
				);
			}
			if ($data_penjual->num_rows() > 0) {
				$response = array(
					'data' => $result_data_pj,
					'status' => 'success',
					'code' => 200,
					'message' => "data penjual success"
				);
				response(200, $response);
			} else {
				$response = array(
					'data' => array(),
					'status' => 'failed',
					'code' => 404,
					'message' => "data penjual not found"
				);
			}
		} catch (Exception $e) {
			$response = array('data' => array(),
						'status' => 'failed',
						'code' => 500,
						'message' => "Error " . $e->getMessage());
		}
		response($response['code'], $response);
	}
}
