<?php
/**
 * 
 */
class Pembeli extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		$this->load->model("Model_pembeli", "Pembeli");
		$this->load->model("Model_user", "user");
		
	}

	public function detail_pembeli()
	{
		$id_akun = $this->input->get('id_akun');
		$data_pembeli = $this->Pembeli->detail_pembeli($id_akun);
		// echo $this->db->last_query();
		$data[] = $data_pembeli->row();
		// header("Content-type: application/json");
		// echo json_encode($data, JSON_PRETTY_PRINT);
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function detail($id_akun)
	{
		try {
			$data_pembeli = $this->Pembeli->detail_pembeli($id_akun);
			// echo $this->db->last_query();
			$data['detail_pembeli'] = $data_pembeli->row_array();
			$data['message']		= "success";
			// header("Content-type: application/json");
			// echo json_encode($data, JSON_PRETTY_PRINT);
			$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		} catch (Exception $e) {
			$this->output
				->set_status_header(500)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode(array('message' => 'server error'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
		
	}

	public function updateAlamat()
	{
		$id_akun = $this->input->post('id_akun');
		$alamatLengkap = $this->input->post('alamatLengkap');
		$kotaKabupaten = $this->input->post('kotaKabupaten');
		$kecamatan = $this->input->post('kecamatan');
		$kelurahan = $this->input->post('kelurahan');
		$latitude = $this->input->post('latitude');
		$longitude = $this->input->post('longitude');
		$array_update = array('alamat_pb' => $alamatLengkap,
							'kab_pb'=>$kotaKabupaten,
							'kec_pb'=>$kecamatan,
							'kel_pb'=>$kelurahan,
							'longitude_pb'=>$longitude,
							'latitude_pb'=>$latitude);
		try {
			$updateAlamat = $this->Pembeli->updateAlamat($array_update, $id_akun);
			$result = null;
			$result['responseMessage'] = "success";
		} catch (Exception $e) {
			$result['responseMessage'] = "failed with " . $e->getMessage();
		}
		header("Content-type: application/json");
		echo json_encode($result);
	}

	public function ViewUbahProfile()
	{
		$dataProfile = $this->input->post('dataProfile');
		$dataProfile = json_decode($dataProfile);
		$dataProfile = $dataProfile[0];
		$this->load->view('pembeli/ubah-profile', $dataProfile);
	}

	// PROSES DAFTAR PEMBELI
	public function prosessignuppembeli()
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
		$longitude_pb 	= $this->input->post('longitude');
		$latitude_pb 	= $this->input->post('latitude');
		$foto_pb 		= '';
		// print_r($this->input->post());
		// exit();


		try {
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

			// CEK USERNAME EXISTING

			$cek_username = $this->user->check_username($username);
			if($cek_username->num_rows() > 0){
				$this->username_existed($username);
			}

			$INSERT_DATA_PEMBELI = $this->user->insert_pembeli($data_pembeli_array);

			if($INSERT_DATA_PEMBELI) {
				$last_id = $this->db->insert_id();
				$this->db->reset_query();
				$data_pengguna_array = array(
					'username' => $username,
					'password' => $password,
					'id_akun' => $last_id,
					'level_user' => 'pembeli');
				$INSERT_DATA_PENGGUNA = $this->user->insert_user_sign($data_pengguna_array);

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

		$this->response($status_header, $response);
	}

	public function prosesupdatepembeli()
	{
		$nama_pb		= $this->input->post('nama_pb');
		//$foto_pb		= $_POST['foto_pb'];
		// $noktp_pj		= $_POST['noktp_pj'];
		// $fotoktp_pj		= $_POST['fotoktp_pj'];
		$jk_pb			= $this->input->post('jk_pb');
		$tgllahir_pb 	= $this->input->post('tgllahir_pb');
		$telp_pb		= $this->input->post('telp_pb');
		$alamat_pb		= $this->input->post('alamat_pb');
		$kab_pb			= $this->input->post('kab_pb');
		$kec_pb			= $this->input->post('kec_pb');
		$kel_pb			= $this->input->post('kel_pb');
		$longitude_pb 	= $this->input->post('longitude');
		$latitude_pb 	= $this->input->post('latitude');
		$id_akun		= $this->input->post('idAkun');

		$dataUpdate = array(
			'nama_pb' => ucwords($nama_pb),
			'jk_pb' => $jk_pb,
			'tgllahir_pb' => $tgllahir_pb,
			'telp_pb' => $telp_pb,
			'alamat_pb' => ucwords($alamat_pb),
			'kab_pb' => ucwords($kab_pb),
			'kec_pb' => ucwords($kec_pb),
			'kel_pb' => ucwords($kel_pb),
			'latitude_pb' => $latitude_pb,
			'longitude_pb' => $longitude_pb
		);

		$file_name						= date('dmYHis') . $_FILES['foto_pb']['name'];
		// var_dump($_FILES);
		$config['upload_path']          = './foto_pembeli/';
		$config['allowed_types']        = 'gif|jpg|jpeg|png';
		$config['max_size']             = 20480;
		$config['max_width']            = 5000;
		$config['max_height']           = 5000;
		$config['file_name']			= $file_name;

		$this->load->library('upload', $config);
		$data_pb = $this->db->get_where("data_pembeli", "id_pb = '$id_akun'", 1)->row();
		// echo $this->db->last_query();
		if ( ! $this->upload->do_upload('foto_pb')){
			$dataFoto = array('error' => $this->upload->display_errors());
			
		}else{
			if($data_pb->foto_pb!=="" || !empty($data_pb->foto_pb)){
				if(unlink('./foto_pembeli/'.$data_pb->foto_pb)){

				}
			}
			
			$dataFoto = array('upload_data' => $this->upload->data());
			$config = array();
			$config['image_library']='gd2';
            $config['source_image']='./foto_pembeli/'.$dataFoto['upload_data']['file_name'];
            $config['create_thumb']= FALSE;
            $config['maintain_ratio']= FALSE;
            $config['quality']= '50%';
            $config['width']= 640;
            $config['height']= 640;
            $config['new_image']= './foto_pembeli/'.$dataFoto['upload_data']['file_name'];
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();

			$foto_pembeli		=   $dataFoto['upload_data']['file_name'];
			$dataUpdate['foto_pb'] = $foto_pembeli;
		}

		$updatee = $this->Pembeli->updatePembeli($dataUpdate, $id_akun);
		// echo $this->db->last_query();
		if($updatee) {
				$status = 'berhasil';
		} else {
			$status = 'gagal';
		}

		$response = array(
			'status' => $status
		);

		echo json_encode($response);
	}

	private function failed_sign_up($term, $length_term=NULL)
	{
		$status = 500;
		$_error_message = "Maaf " . $term . " wajib diisi.";
		if($length_term!==NULL){
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

	private function response($status_header, $body=array())
	{
		$this->output
			->set_status_header($status_header)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
}