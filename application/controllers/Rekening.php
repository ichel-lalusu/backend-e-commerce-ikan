<?php
/**
 * 
 */
class Rekening extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		$this->load->model('Model_rekening', 'rekening');
	}

		// PROSES BAGIAN REKENING
	public function ambil_rekening()
	{
		$id_akun = $this->input->get('id_akun');
		$data = $this->rekening->ambil_rekening_by_user($id_akun);
		$respons = array();
		if($data->num_rows() > 0){
			$respons['data'] = $data->result_array();
			$respons['status'] = "success";
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($respons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}else{
			$respons['status'] = "Not Found";
			$this->output
			->set_status_header(404)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($respons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}

	public function ambil_rekening_usaha()
	{
		$id_usaha = $this->input->get("id_usaha");
		try {
			$data_rekening = $this->rekening->ambil_rekening_usaha($id_usaha);
			if($data_rekening->num_rows() > 0){
				$respons['data'] = $data_rekening->result_array();
				$respons['status'] = "success";
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($respons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}else{
				$respons['status'] = "Not Found";
				$this->output
				->set_status_header(404)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($respons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}
		} catch (Exception $e) {
			$respons['status'] = "Error " .$e->getMessage() ;
			$this->output
			->set_status_header(404)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($respons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}

	public function ambil_rekening_by_id()
	{
		// $id_akun = $this->input->post('id_akun');
		$id_rekening = $this->input->get('id_rekening');
		$data = $this->rekening->ambil_rekening_by_id($id_rekening);
		$respons = array();
		if($data->num_rows() > 0){
			$respons['data'] = $data->row_array();
			$respons['status'] = "success";
		}else{
			$respons['status'] = "failed";
		}
		header("Content-type: application/json");
		echo json_encode($respons);
	}

	public function ambil_data_bank()
	{
		try {
			$data = $this->rekening->ambil_semua();
			if($data->num_rows() > 0){
				$respons['data'] = $data->result_array();
				$respons['status'] = "success";
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($respons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}else{
				$respons['status'] = "Not Found";
				$this->output
				->set_status_header(404)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($respons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}
		} catch (Exception $e) {
			$respons['status'] = "Error " . $e->getMessage();
			$this->output
			->set_status_header(404)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($respons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}

	public function ambil_all_bank()
	{
		$respons = array();
		try {
			$data = $this->db->get('data_master_bank');
			
			if($data->num_rows() > 0){
				$respons['data'] = $data->result_array();
				$respons['status'] = "success";
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($respons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}else{
				$respons['status'] = "Not Found";
				$this->output
				->set_status_header(404)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($respons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}
		} catch (Exception $e) {
			$respons['status'] = "Error " . $e->getMessage();
			$this->output
			->set_status_header(404)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($respons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}

	public function uploadBukti_ambilBank_html()
	{
		$id_akun = $this->input->post('id_akun');
		$data = $this->rekening->ambil_semua();
		// $respons = $data->result_array();
		// header("Content-type: text/html");
		$this->load->view("rekening/unggahBukti_ambilBank_html", array('dataRekening' => $data));
		// echo json_encode($respons);
	}

	public function simpan_rekening()
	{
		$bank = $this->input->post('kode_bank');
		$norek = $this->input->post('no_rekening');
		$namarek = $this->input->post('nama_rekening');
		$id_akun = $this->input->post('id_akun');
		$data_ins = array('kode_bank' => $bank, 'id_akun' => $id_akun, 'no_rekening' => $norek, 'nama_rekening' => $namarek);
		$insert = $this->rekening->simpan_rekening($data_ins);
		if($insert){
			$status = "berhasil";
			response(200, array('status'=>$status));
		}else{
			$status = "gagal";
			response(400, array('status' => $status));
		}
	}

	public function ubah_rekening()
	{
		$id_rekening = $this->input->post('id_rekening');
		$kode_bank = $this->input->post('bank_edit');
		$no_rekening = $this->input->post('no_rekening');
		$nama_rekening = $this->input->post('nama_rekening');

		$data = array(
			'kode_bank' => $kode_bank,
			'no_rekening' => $no_rekening,
			'nama_rekening' => $nama_rekening);
		$ubah = $this->rekening->ubah_rekening($data, $id_rekening);
		if($ubah){
			$status = "berhasil";
		}else{
			$status = "gagal";
		}

		$respons = array('status' => $status);
		header("Content-type: application/json");
		echo json_encode($respons);
	}

	public function hapus_rekening()
	{
		$id_rekening = $this->input->post('id_rekening');
		$proses = $this->rekening->hapus_rekening($id_rekening);
		if($proses){
			$status = "berhasil";
		}else{
			$status = "gagal";
		}

		$respons = array('status' => $status);
		header("Content-type: application/json");
		echo json_encode($respons);
	}
	// END BAGIAN REKENING
}