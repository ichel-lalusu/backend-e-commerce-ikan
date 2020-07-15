<?php

/**
 * 
 */
class Kurir extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata("username")) {
			redirect(base_url('admin/User/login'));
		}
		$this->load->model("Model_kurir");
		$this->load->model("admin/Model_usaha", "usaha");
	}

	public function add_kurir($id_usaha)
	{
		if (!$this->session->userdata("username")) {
		} else {
			$menu = "Penjual";
			$where = "id_usaha = '$id_usaha'";
			$data_shop = $this->usaha->get_where("*", $where)->row();
			$id_usaha = $data_shop->id_usaha;
			$data_page = array(
				'title' => 'Tambah Data Kurir ' . $data_shop->nama_usaha,
				'menu' => 'penjual',
				'id_usaha' => $id_usaha
			);
			$this->load->view('admin/kurir/add', $data_page);
		}
	}

	public function simpan_kurir($value = '')
	{
		$nama_kurir = $this->input->post('nama_kurir');
		$jk_kurir = $this->input->post('jk_kurir');
		$telp_kurir = $this->input->post('telp_kurir');
		$id_usaha = $this->input->post('id_usaha');
		$foto_kurir = "";
		$array_insert = array(
			'nama_kurir' => $nama_kurir,
			'jk_kurir' => $jk_kurir,
			'telp_kurir' => $telp_kurir,
			'id_usaha' => $id_usaha
		);
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
		if ($this->db->affected_rows() > 0) {
			$array = array('status' => "Berhasil tambahkan kurir " . $nama_kurir);
			$this->session->set_flashdata("success", $array['status']);
			redirect(base_url('admin/Usaha/detail/' . $id_usaha));
		} else {
			$array = array('status' => "Gagal tambahkan kurir");
			$this->session->flashdata("error", $array['status']);
		}
	}

	public function ubah_kurir(Int $id_kurir, Int $id_usaha)
	{
		if (!$this->session->userdata("username")) {
		} else {
			$menu = "Penjual";
			$where = "id_usaha = '$id_usaha'";
			$data_shop = $this->usaha->get_where("*", $where)->row();
			$id_usaha = $data_shop->id_usaha;
			$data_kurir = $this->Model_kurir->get_detail_kurir($id_kurir, $id_usaha);
			if ($data_kurir->num_rows() > 0) :
				$data_page = array(
					'title' => 'Tambah Data Kurir ' . $data_shop->nama_usaha,
					'menu' => 'penjual',
					'id_usaha' => $id_usaha,
					'kurir' => $data_kurir->row()
				);
				$this->load->view('admin/kurir/edit', $data_page);
			else :
				$this->session->set_flashdata("error", "Data kurir tidak ditemukan");
				redirect(base_url('admin/Usaha/detail/' . $id_usaha));
			endif;
		}
	}

	public function simpan_ubah_kurir()
	{
		$nama_kurir = $this->input->post('nama_kurir');
		$jk_kurir = $this->input->post('jk_kurir');
		$telp_kurir = $this->input->post('telp_kurir');
		$id_usaha = $this->input->post('id_usaha');
		$id_kurir = $this->input->post('id_kurir');
		// if (is_array($_FILES)) {
		// 	if (is_uploaded_file($_FILES['foto_kurir']['tmp_name'])) {

		// 		$sourcePath2 = $_FILES['foto_kurir']['tmp_name'];
		// 		$foto_kurir = date('dmYHis') . $_FILES['foto_kurir']['name'];
		// 		$targetPath2 = "./foto_kurir/" . $foto_kurir;
		// 		move_uploaded_file($sourcePath2, $targetPath2);
		// 	}
		// }
		$array_insert = array(
			'nama_kurir' => $nama_kurir,
			'jk_kurir' => $jk_kurir,
			'telp_kurir' => $telp_kurir,
		);
		if (isset($_FILES)) :
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
			$this->load->library('upload', $config);
			$data_kurir = $this->Model_kurir->get_detail_kurir($id_kurir, $id_usaha)->row();
			if (!$this->upload->do_upload('foto_kurir')) {
				$dataFoto = array('error' => $this->upload->display_errors());
				// var_dump($dataFoto);

			} else {
				unlink('./foto_kurir/' . $data_kurir->foto_kurir);
				$dataFoto = array('upload_data' => $this->upload->data());
				$config = array();
				$config['image_library'] = 'gd2';
				$config['source_image'] = './foto_kurir/' . $dataFoto['upload_data']['file_name'];
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = FALSE;
				$config['quality'] = '50%';
				$config['width'] = 600;
				$config['height'] = 400;
				$config['new_image'] = './foto_kurir/' . $dataFoto['upload_data']['file_name'];
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				// var_dump($dataFoto);
				// exit();
				$foto_kurir		=   $dataFoto['upload_data']['file_name'];
				$array_insert['foto_kurir'] = $foto_kurir;
			}
		endif;


		$update = $this->Model_kurir->update_kurir($array_insert, $id_kurir, $id_usaha);
		if ($update) {
			$array = array('status' => "Berhasil Ubah Kurir");
			$this->session->set_flashdata("success", $array['status']);
			redirect(base_url('admin/Usaha/detail/' . $id_usaha));
		} else {
			$array = array('status' => "Gagal Ubah Kurir");
			$this->session->set_flashdata("success", $array['status']);
			redirect(base_url('admin/kurir/ubah_kurir/' . $id_kurir . "/" . $id_usaha));
		}
	}

	public function delete_kurir($id_kurir, $id_usaha)
	{
		$data_kurir = $this->Model_kurir->get_detail_kurir($id_kurir, $id_usaha)->row();
		$foto_kurir = $data_kurir->foto_kurir;
		if($foto_kurir!=="" || !empty($foto_kurir)):
			unlink('./foto_kurir/' . $data_kurir->foto_kurir);
		endif;
		$delete = $this->Model_kurir->delete_kurir($id_kurir, $id_usaha);
		if($delete){
			$array = array('status' => "Berhasil Hapus Kurir");
			$this->session->set_flashdata("success", $array['status']);
			redirect(base_url('admin/Usaha/detail/' . $id_usaha));
		}else{
			$array = array('status' => "Gagal Hapus Kurir");
			$this->session->set_flashdata("success", $array['status']);
			redirect(base_url('admin/Usaha/detail/' . $id_usaha));
		}
	}

	private function detail_kurir($id_kurir)
	{
		# code...
	}
}
