<?php
/**
 * 
 */
class Kurir extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		$this->load->model("Model_kurir");
	}

	public function ambilKurirByUsaha()
	{
		$id_usaha = $this->input->get('id_usaha');
		try {
			$data = $this->Model_kurir->get_kurir_usaha($id_usaha);
			// echo $this->db->last_query();
			if($data->num_rows() > 0){
				$array = array('status' => "berhasil", 'data' => $data->result_array());
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}else{
				$array = array('status' => "kosong");
				$this->output
				->set_status_header(404)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}
		} catch (Exception $e) {
			$array = array('status' => "Gagal");
				$this->output
				->set_status_header(500)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}

	public function ambilDetailKurir()
	{
		$id_kurir =$this->input->get('id_kurir');
		$id_usaha = $this->input->get("id_usaha");
		$data = $this->Model_kurir->get_detail_kurir($id_kurir, $id_usaha);
		if($data->num_rows() > 0){
			$array = array('status' => "berhasil", 'data' => $data->row_array());
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}else{
			$array = array('status' => "gagal");
			$this->output
			->set_status_header(404)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}

	public function simpanKurir()
	{
		$nama_kurir = $this->input->post('nama_kurir');
		$jk_kurir = $this->input->post('jk_kurir2');
		$telp_kurir = $this->input->post('telp_kurir');
		$id_usaha = $this->input->post('id_usaha');
		$foto_kurir = "";
		$array_insert = array('nama_kurir' => $nama_kurir, 
			'jk_kurir' => $jk_kurir,
			'telp_kurir' => $telp_kurir,
			'id_usaha' => $id_usaha);
		if (is_array($_FILES)) {
			if (is_uploaded_file($_FILES['foto_kurir']['tmp_name'])) {

				$sourcePath2 = $_FILES['foto_kurir']['tmp_name'];
				$foto_kurir = date('dmYHis') . $_FILES['foto_kurir']['name'];
				$targetPath2 = "./foto_kurir/" . $foto_kurir;
				move_uploaded_file($sourcePath2, $targetPath2);
				$array_insert['foto_kurir'] = $foto_kurir;
			}
		}
		
		$insert = $this->Model_kurir->create_kurir($array_insert);
		if($this->db->affected_rows() > 0){
			$array = array('status' => "berhasil");
			$this->output
			->set_status_header(201)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}else{
			$array = array('status' => "gagal");
			$this->output
			->set_status_header(404)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));	
		}
	}

	public function ubahKurir()
	{
		$nama_kurir = $this->input->post('nama_kurir');
		$jk_kurir = $this->input->post('jk_kurir2');
		$telp_kurir = $this->input->post('telp_kurir');
		$id_usaha = $this->input->post('id_usaha');
		$id_kurir = $this->input->post('id_kurir');
		$foto_kurir = "";
		// if (is_array($_FILES)) {
		// 	if (is_uploaded_file($_FILES['foto_kurir']['tmp_name'])) {

		// 		$sourcePath2 = $_FILES['foto_kurir']['tmp_name'];
		// 		$foto_kurir = date('dmYHis') . $_FILES['foto_kurir']['name'];
		// 		$targetPath2 = "./foto_kurir/" . $foto_kurir;
		// 		move_uploaded_file($sourcePath2, $targetPath2);
		// 	}
		// }
		$file_name						= date('dmYHis') . $_FILES['foto_kurir']['name'];
		// var_dump($_FILES);
		$config['upload_path']          = './foto_kurir/';
		$config['allowed_types']        = 'gif|jpg|jpeg|png';
		$config['max_size']             = 20480;
		$config['max_width']            = 5000;
		$config['max_height']           = 5000;
		$config['file_name']			= $file_name;
		// var_dump($config);
		// exit();
		$array_insert = array('nama_kurir' => $nama_kurir, 
			'foto_kurir' => $foto_kurir, 
			'jk_kurir' => $jk_kurir,
			'telp_kurir' => $telp_kurir,);
		$this->load->library('upload', $config);
		$data_kurir = $this->Model_kurir->get_detail_kurir($id_kurir, $id_usaha)->row();
		if ( ! $this->upload->do_upload('foto_kurir')){
			$dataFoto = array('error' => $this->upload->display_errors());
			// var_dump($dataFoto);
			
		}else{
			unlink('./foto_kurir/'.$data_kurir->foto_kurir);
			$dataFoto = array('upload_data' => $this->upload->data());
			$config = array();
			$config['image_library']='gd2';
            $config['source_image']='./foto_kurir/'.$dataFoto['upload_data']['file_name'];
            $config['create_thumb']= FALSE;
            $config['maintain_ratio']= FALSE;
            $config['quality']= '50%';
            $config['width']= 600;
            $config['height']= 400;
            $config['new_image']= './foto_kurir/'.$dataFoto['upload_data']['file_name'];
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
			// var_dump($dataFoto);
			// exit();
			$foto_kurir		=   $dataFoto['upload_data']['file_name'];
			$array_insert['foto_kurir'] = $foto_kurir;
		}

		
		$update = $this->Model_kurir->update_kurir($array_insert, $id_kurir, $id_usaha);
		if($update){
			$array = array('status' => "berhasil");
			$this->output
			->set_status_header(201)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}else{
			$array = array('status' => "gagal");
			$this->output
			->set_status_header(404)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));	
		}
	}

	public function hapusKurir()
	{
		$id_kurir = $this->input->post('id_kurir');
		$id_usaha = $this->input->post('id_usaha');
		$delete = $this->Model_kurir->delete_kurir($id_kurir, $id_usaha);
		if($delete){
			$array = array('status' => "berhasil");
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}else{
			$array = array('status' => "gagal");
			$this->output
			->set_status_header(404)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}
}