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
			if($cek->num_rows() > 0){
				$set_status_header = 200;
				$status = 'berhasil';
				$data_pengguna = $cek->row_array();
				$id_akun = $data_pengguna['id_akun'];
				$usergroup = $data_pengguna['level_user'];
				$response = array(
					'username' => $username,
					'status' => $status,
					'usergroup' => $usergroup,
					'id_akun' => $id_akun
				);
				if($usergroup=="penjual"){
					$data_usaha = $this->penjual->ambil_data_usaha($id_akun)->row_array();
					$response['data_usaha'] = $data_usaha;
				}
				$this->session->set_userdata($response);
			}else{
				$set_status_header = 404;
				$status = 'gagal';
				$usergroup = '';
				$id_akun = '';
				$response = array(
					'status' => $status
				);
			}
		} catch (Exception $e) {
			$set_status_header = 500;
			$status = 'gagal';
			$response = array(
				'status' => $status
			);
		}
		
		$this->output
		->set_status_header($set_status_header)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function login_kurir()
	{
		$login = $this->input->post('phone');
		if($login[0]=="8" || $login==8){
			$login = "0".$login;
		}
		$data_login = array();
		$status_header = 500;
		try {
			$cek_login_kurir = $this->Model_kurir->cek_kurir_by_telp($login);
			if($cek_login_kurir){
				$status_header = 200;
				$data_login['kurir'] = $cek_login_kurir->row_array();
				$data_login['status'] = "berhasil";
				$data_login['message'] = "Berhasl Login";
				$this->session->set_userdata($data_login);
			}else{
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

	public function try_toPut()
	{
		// if()
		parse_str(file_get_contents("php://input"),$post_vars);
		echo $post_vars['fruit']." is the fruit\n";
		echo "I want ".$post_vars['quantity']." of them\n\n";
		echo $this->input->input_stream('fruit');
	}
}