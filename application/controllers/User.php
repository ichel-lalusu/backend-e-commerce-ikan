<?php

/**
 * 
 */
class User extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		// header("Access-Control-Allow-Origin: *");
		$this->load->model("Model_user", 'user');
		$this->load->model("Model_penjual", "penjual");
		$this->load->model("Model_pembeli");
		$this->load->model("Model_kurir");
	}

	public function proseslogin()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		// echo json_encode($this->input->post());
		// exit();
		$set_status_header = 100;
		try {
			$cek = $this->user->cek_pengguna($username, $password);
			if ($cek->num_rows() > 0) {
				$set_status_header = 200;
				$status = 'berhasil';
				$data_pengguna = $cek->row_array();
				$id_akun = $data_pengguna['id_akun'];
				$usergroup = $data_pengguna['level_user'];
				$response['data'] = array(
					'username' => $username,
					'status' => $status,
					'usergroup' => $usergroup,
					'id_akun' => $id_akun
				);
				if ($usergroup == "penjual") {
					$data_usaha = $this->penjual->ambil_data_usaha($id_akun)->row_array();
					$response['data_usaha'] = $data_usaha;
				}
				$this->session->set_userdata($response);
				response(200, $response);
			} else {
				$status = 'gagal';
				$usergroup = '';
				$id_akun = '';
				$response = array(
					'status' => $status
				);
				response(404, $response);
			}
		} catch (Exception $e) {
			$status = 'gagal';
			$response = array(
				'status' => $status
			);
			response(500, $response);
		}
	}

	public function logout()
	{
		$result_data = array();
		$username = $this->input->post('username');
		$id_akun = $this->input->post('id_akun');
		$usergroup = $this->input->post('usergroup');
		try {
			$cek_pengguna = $this->user->cek_pengguna_by_id_akun_username($id_akun, $username);
			if ($cek_pengguna->num_rows() > 0) {
				if ($usergroup == "admin") {
					$this->session->unset_userdata(base64_encode($username));
					$result_data = array(
						'message' => "success logout",
						'status' => 'success',
						'code' => 200
					);
				} else {
					$this->session->unset_userdata($username);
					$result_data = array(
						'message' => "success logout",
						'status' => 'success',
						'code' => 200
					);
				}
			} else {
				$result_data = array(
					'message' => "failed logout",
					'status' => 'failed',
					'code' => 400
				);
			}
		} catch (Exception $error) {
			$result_data = array(
				'message' => "failed logout " . $error->getMessage(),
				'status' => 'failed',
				'code' => 500
			);
		}
		$this->output
			->set_status_header($result_data['code'])
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function login_kurir()
	{
		$login = $this->input->post('phone');
		if ($login[0] == "8" || $login == 8) {
			$login = "0" . $login;
		}
		$data_login = array();
		$status_header = 500;
		try {
			$cek_login_kurir = $this->Model_kurir->cek_kurir_by_telp($login);
			if ($cek_login_kurir) {
				$status_header = 200;
				$data_login['kurir'] = $cek_login_kurir->row_array();
				$data_login['status'] = "berhasil";
				$data_login['message'] = "Berhasl Login";
				$this->session->set_userdata($data_login);
			} else {
				$status_header = 402;
				$data_login['kurir'] = array();
				$data_login['stats'] = 'gagal';
				$data_login['message'] = "Gagal Login";
			}
		} catch (Exception $e) {
			$data_login['kurir'] = array();
			$data_login['stats'] = 'error';
			$data_login['message'] = "Error Login";
		}
		$this->output
			->set_status_header($status_header)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data_login, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function signup()
	{
		$usertype = $this->input->post("usertype", TRUE);
		$signup_init = null;
		if ($usertype=="pembeli") {
			$signup_init = $this->signup_pembeli();
		}elseif ($usertype=="penjual") {
			$signup_init = $this->signup_penjual();
		}elseif($usertype=="usaha"){
			$id_pj 			= $this->input->post('id_pj', TRUE);
			$cek_pj = cek_pj($id_pj);
			if($cek_pj){
				$this->signup_usaha();
			}
		}
		if($signup_init==null){
			response(500, array('status' => "failed", 'message' => "error"));
		}
	}

	protected function signup_pembeli()
	{
		$username		= (!empty($this->input->post('username'))) ? $this->input->post('username') : $this->failed_sign_up('Username') ;
		$password	 	= (!empty($this->input->post('password'))) ? $this->input->post('password') : $this->failed_sign_up('Password');
		$nama_pb		= (!empty($this->input->post('nama_pb'))) ? $this->input->post('nama_pb') : $this->failed_sign_up('Nama Panjang');
		
		//$foto_pb		= $_POST['foto_pb'];
		// $noktp_pj		= $_POST['noktp_pj'];
		// $fotoktp_pj		= $_POST['fotoktp_pj'];
		$jk_pb			= (!empty($this->input->post('jk_pb'))) ? $this->input->post('jk_pb') : $this->failed_sign_up('Jenis Kelamin');
		$tgllahir_pb 	= (!empty($this->input->post('tgllahir_pb'))) ? $this->input->post('tgllahir_pb') : $this->failed_sign_up('Tanggal Lahir');
		$telp_pb		= (!empty($this->input->post('telp_pb'))) ? $this->input->post('telp_pb') : $this->failed_sign_up('Nomor Telp');
		$alamat_pb		= (!empty($this->input->post('alamat_pb'))) ? $this->input->post('alamat_pb') : $this->failed_sign_up('Alamat');
		$kab_pb			= (!empty($this->input->post('kab_pb'))) ? $this->input->post('kab_pb') : $this->failed_sign_up('Kabupaten');
		$kec_pb			= (!empty($this->input->post('kec_pb'))) ? $this->input->post('kec_pb') : $this->failed_sign_up('Kecamatan');
		$kel_pb			= (!empty($this->input->post('kel_pb'))) ? $this->input->post('kel_pb') : $this->failed_sign_up('Kelurahan');
		$usertype		= $this->input->post("usertype", TRUE);
		$longitude_pb 	= $this->input->post('longitude');
		$latitude_pb 	= $this->input->post('latitude');
		$foto_pb 		= '';
		// var_dump($this->input->post());
		
		try {
			$cek_user = cek_username($username);
			if($cek_user){
				$this->username_existed($username);
			}
			$data_pembeli_array = array(
				'nama_pb' => ucwords($nama_pb),
				'jk_pb' => $jk_pb,
				'tgllahir_pb' => $tgllahir_pb,
				'telp_pb' => $telp_pb,
				'alamat_pb' => ucwords($alamat_pb),
				'kab_pb' => ucwords($kab_pb),
				'kec_pb' => ucwords($kec_pb),
				'kel_pb' => ucwords($kel_pb),
				'longitude_pb' => $longitude_pb,
				'latitude_pb' => $latitude_pb);

			// UPLOAD FOTO PEMBELI
			$file_name						= date('dmYHis') . $_FILES['foto_pb']['name'];
			// var_dump($_FILES);
			$config['upload_path']          = './foto_pembeli/';
			$config['allowed_types']        = 'gif|jpg|jpeg|png';
			$config['max_size']             = 20480;
			$config['max_width']            = 5000;
			$config['max_height']           = 5000;
			$config['file_name']			= $file_name;

			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('foto_pb')){
				$dataFoto = array('error' => $this->upload->display_errors());
			}else{
				
				$dataFoto = array('upload_data' => $this->upload->data());
				$foto_success = $dataFoto['upload_data']['file_name'];
				$config = array();
				$config['image_library']='gd2';
	            $config['source_image']='./foto_pembeli/'.$foto_success;
	            $config['create_thumb']= FALSE;
	            $config['maintain_ratio']= FALSE;
	            $config['quality']= '50%';
	            $config['width']= 640;
	            $config['height']= 640;
	            $config['new_image']= './foto_pembeli/'.$foto_success;
	            $this->load->library('image_lib', $config);
	            $this->image_lib->resize();
				// var_dump($dataFoto);
				// exit();
				$foto_pembeli		=   $foto_success;
				$data_pembeli_array['foto_pb'] = $foto_pembeli;
			}

			$INSERT_DATA_PEMBELI = $this->user->insert_pembeli($data_pembeli_array);

			if($INSERT_DATA_PEMBELI) {
				$last_id = $this->db->insert_id();
				$this->db->reset_query();
				$data_pengguna_array = array(
					'username' => $username,
					'password' => $password,
					'id_akun' => $last_id,
					'level_user' => $usertype);
				$INSERT_DATA_PENGGUNA = $this->sign_user($data_pengguna_array);

				if($INSERT_DATA_PENGGUNA){
					$status_header = 200;
					$status = 'berhasil';
					$message = 'Berhasil Tambahkan Data Anda';
				} else{
					$status_header = 400;
					$status = 'gagal2 . ' . $this->db->_error_message();
					$message = 'Gagal Tambahkan Data Anda';
				}
			} else {
				$status_header = 404;
				$status = 'gagal1';
				$message = 'Error';
			}
		} catch (Exception $e) {
			$status_header = 500;
			$status = 'Error Server';
			$message = 'Error ' . $e->getMessage();
		}

		$response = array(
			'status' => $status,
			'message' => $message
		);

		response($status_header, $response);
	}

	protected function signup_penjual()
	{
		$username		= (!empty($this->input->post('username'))) ? $this->input->post('username', TRUE) : $this->failed_sign_up("Username");
		$password	 	= (!empty($this->input->post('password'))) ? $this->input->post('password', TRUE) : $this->failed_sign_up("Password");
		$nama_pj		= (!empty($this->input->post('nama_pj'))) ? $this->input->post('nama_pj', TRUE) : $this->failed_sign_up("Nama Lengkap");
		$noktp_pj		= (!empty($this->input->post('noktp_pj'))) ? $this->input->post('noktp_pj', TRUE) : $this->failed_sign_up("No. KTP");
		$jk_pj			= (!empty($this->input->post('jk_pj'))) ? $this->input->post('jk_pj', TRUE) : $this->failed_sign_up("Jenis Kelamin");
		$tgllahir_pj 	= (!empty($this->input->post('tgllahir_pj'))) ? $this->input->post('tgllahir_pj', TRUE) : $this->failed_sign_up("Tanggal Lahir");
		$alamat_pj		= (!empty($this->input->post('alamat_pj'))) ? $this->input->post('alamat_pj', TRUE) : $this->failed_sign_up("Alamat Lengkap");
		$telp_pj		= (!empty($this->input->post('telp_pj'))) ? $this->input->post('telp_pj', TRUE) : $this->failed_sign_up("No. Telp");
		$jenis_petani	= (!empty($this->input->post('jenis_petani'))) ? $this->input->post('jenis_petani', TRUE) : $this->failed_sign_up("Jenis Petani");
		// print_r($this->input->post());
		// exit();
		$response = array();
		$status_header = 100;

		// CEK USERNAME EXISTING
		try {
			$cek_user = cek_username($username);
			if($cek_user){
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
				$INSERT_DATA_PENGGUNA = $this->sign_user($data_pengguna_array);
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
		response($status_header, $response);
	}

	protected function signup_usaha()
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
		} catch (Exception $e) {
			$status_header = 400;
			$response = array('status' => 'error', 'message' => "Gagal Lengkapi Detail Usaha " . $e->getMessage());
		}
		response($status_header, $response);
	}

	protected function sign_user($data_pengguna_array)
	{
		return $this->user->insert_user_sign($data_pengguna_array);
	}

	public function try_toPut()
	{
		// if()
		$req_method = $_SERVER['REQUEST_METHOD'];
		if($req_method=="PUT"){
			parse_str(file_get_contents("php://input"), $data_put);
			// var_dump($data_put);
			response(200, $data_put);
		}
		// echo $this->input->input_stream('id_akun');
	}

	private function username_existed($username)
	{
		$status = 500;
		$_error_message = "Maaf username $username sudah pernah ada";
		$body = array('status' => "failed", 'message' => $_error_message);
		response($status, $body);
	}

	private function failed_sign_up($term, $length_term = NULL)
	{
		$status = 500;
		$_error_message = "Maaf " . $term . " wajib diisi.";
		if ($length_term !== NULL) {
			$_error_message .= " " . $term . " minimal " . $length_term . " karakter.";
		}
		response($status, array('status' => "failed", 'message' => $_error_message));
	}
}
