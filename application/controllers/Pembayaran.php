<?php
/**
 * 
 */
class Pembayaran extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		$this->load->model("Model_rekening", "rekening");
		$this->load->model("Model_pembayaran", "pembayaran");
		$this->load->model("Model_pemesanan", "pemesanan");
	}

	public function pembayaran_ambil_rekening_html()
	{
		$id_akun = $this->input->post('id_akun');
		$data = $this->rekening->ambil_rekening_by_user($id_akun);
		// $respons = $data->result_array();
		header("Content-type: text/html");
		$this->load->view("pembeli/pesanan-saya/pembayaran", array('dataRekening' => $data));
		// echo json_encode($respons);
	}

	public function getPembayaranPemesananByIdPemesanan($id_pemesanan)
	{
		$data_pembayaran = $this->db->get_where("data_pembayaran", "id_pemesanan = '$id_pemesanan'");
		if($data_pembayaran->num_rows() > 0){
			return $data_pembayaran->row_array();
		}else{
			return null;
		}
	}

	public function ProsesUnggahBuktiPembayaran_Pemesanan()
	{
		$bankTerpilih = $_POST['bankTerpilih'];
		$noRek = $_POST['noRek'];
		$namaRekening = $_POST['namaRekening'];
		$struk_pembayaranFile = $_POST['struk_pembayaranFile'];
		$idPemesanan = $_POST['idPemesanan'];
		$dataPembayaran = $this->pembayaran->getDataPembayaranOnlyByIdPemesanan($idPemesanan);
		$result = array();
		if($dataPembayaran->num_rows() > 0){
			// if(is_array($_FILES)){
			// 	if(is_uploaded_file($_FILES['struk_pembayaran']['tmp_name'])){
			// 		$sourcePath = $_FILES['struk_pembayaran']['tmp_name'];
			// 		$fotoBukti = date('dmYHis') . $_FILES['struk_pembayaran']['name'];
			// 		$targetPath = './foto_struk/'.$fotoBukti;
			// 		move_uploaded_file($sourcePath, $targetPath);
			// 	}
			// }

			// DATA UPDATE REQUIRED
			$waktuPembayaran = date('Y-m-d H:i:s');
			$DataPembayaranRow = $dataPembayaran->row();
			$status_pembayaran = "";
			if($DataPembayaranRow->metode_pembayaran=="Transfer Cash"){
				$status_pembayaran = "DP";
			}else{
				$status_pembayaran = "Lunas";
			}
			$dataUpdatePembayaran = array('waktu_pembayaran' => $waktuPembayaran,
				'kode_bank' => $bankTerpilih,
				'no_rekening_pb' => $noRek,
				'status_pembayaran' => $status_pembayaran,
				'nama_rekening_pb' => $namaRekening,
				'verifikasi' => '0');

			// UPLOAD FOTO STRUK
			$file_name						= date('dmYHis') . $_FILES['struk_pembayaran']['name'];
			// var_dump($_FILES);
			$config['upload_path']          = './foto_struk/';
			$config['allowed_types']        = 'gif|jpg|jpeg|png';
			$config['max_size']             = 20480;
			$config['max_width']            = 5000;
			$config['max_height']           = 5000;
			$config['file_name']			= $file_name;

			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('struk_pembayaran')){
				$dataFoto = array('error' => $this->upload->display_errors());
			}else{
				
				$dataFoto = array('upload_data' => $this->upload->data());
				$foto_success = $dataFoto['upload_data']['file_name'];
				$config = array();
				$config['image_library']='gd2';
	            $config['source_image']='./foto_struk/'.$foto_success;
	            $config['create_thumb']= FALSE;
	            $config['maintain_ratio']= FALSE;
	            $config['quality']= '50%';
	            $config['width']= 640;
	            $config['height']= 640;
	            $config['new_image']= './foto_struk/'.$foto_success;
	            $this->load->library('image_lib', $config);
	            $this->image_lib->resize();
				// var_dump($dataFoto);
				// exit();
				$foto_struk		=   $foto_success;
				$dataUpdatePembayaran['struk_pembayaran'] = $foto_struk;
			}

			
			$UpdatePembayaran = $this->pemesanan->UpdatePembayaran($dataUpdatePembayaran, $idPemesanan);
			if($UpdatePembayaran){
				$dataPembayaran = $this->pemesanan->getDetailDataPembayaranByIdPemesanan($idPemesanan);
				$result = array('status' => 'berhasil', 'code' => '00');

			}else{
				$result = array('status' => 'gagal', 'code' => '01');
			}
		}else{
			$result = array('status' => 'gagal', 'code' => '99');
		}
		echo json_encode($result, JSON_PRETTY_PRINT);
	}
}