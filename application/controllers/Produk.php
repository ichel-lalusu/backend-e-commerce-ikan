<?php
/**
 * 
 */
class Produk extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		// header("'Access-Control-Allow-Credentials' : true");
		$this->load->model("Model_produk", "produk");
		$this->load->model("Model_penjual", "penjual");
		$this->load->model("Model_pembeli");
		$this->load->model("Model_keranjang");
		$this->load->helper("Response_helper");
	}

	public function index()
	{
		exit("The Page Are Not Allowed!");
	}

	public function ambil_produk_penjual()
	{
		$id_akun = $this->input->post('id_akun');
		// echo $id_akun;
		$data_produk = $this->produk->get_by_id_pj($id_akun);
		$ambil_data = $data_produk->result_array();
		$response = array();
		if($data_produk->num_rows() > 0):
			$response = $data_produk;
			header("Content-type: application/json");
			echo json_encode($ambil_data);
		else:
			echo json_encode($response);
		endif;
	}

	public function ambil_produk_penjual_by_id_usaha()
	{
		$id_usaha = $this->input->get('id_usaha');
		$filter = ($this->input->get('filter')!==null) ? $this->input->get('filter') : '';
		$data_produk = $this->produk->ambil_produk_penjual_by_id($id_usaha, $filter);
		$data = $data_produk->result_array();
		$this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function getProdukDashboard()
	{
		// "http://localhost/produk/getProdukDashboard/1"
		$id_usaha = $this->input->get('id_usaha');
		$distance = $this->input->get('distance_text');
		// var_dump($id_usaha);
		// exit();
		try {
			$data_produk = array();
			foreach ($id_usaha as $key => $value) {
				$where = "u.id_usaha = '$value'";
				$data_produk_by_id_usaha = $this->produk->get_detail_produk_where($where);
				// var_dump($data_produk_by_id_usaha);
				if($data_produk_by_id_usaha->num_rows() > 0){
					foreach ($data_produk_by_id_usaha->result() as $each_produk) {
						$data_produk[$value][] = array(
							'nama_produk' => $each_produk->nama_produk,
							'id_produk' => $each_produk->id_produk,
							'minprice' => $each_produk->minprice,
							'maxprice' => $each_produk->maxprice,
							'distance' => $distance[$value]['text'],
							'foto_produk' => $each_produk->foto_produk,
							'nama_usaha' => $each_produk->nama_usaha,
							'id_usaha' => $value);
					}				
				}
			}
			// $where = "u.id_usaha = '$id_usaha'";
			// $data_produk_by_id_usaha = $this->produk->get_detail_produk_where($where);
			// echo $this->db->last_query();
			// exit();
			if(count($data_produk) > 0){
				response(200, $data_produk);
			}else{
				response(404, array());
			}
		} catch (Exception $e) {
			response(500, array('Server Error'));
		}
	}

	public function cariProdukLike()
	{
		$input = $this->input->get('input', true);
		$order = $this->input->get("order_type", TRUE);
		$lower_input = strtolower($input);
		$uppwerOrder = strtoupper($order);
		$data = array();
		try {
			$cek_str = $this->cek_str($input);
			if(!$cek_str){
				response(400, array('status' => "failed", 'message' => "karakter harus lebih dari 2", 'data' => $data));
			}
			$data_produk = $this->produk->search_produk($lower_input, $uppwerOrder);
			if($data_produk->num_rows() > 0){
				$data['data'] = $data_produk->result_array();
				$data['status'] = 'success';
				$data['message'] = "Berhasil ditemukan";
			}else{
				response(404, array('status' => 'failed', 'message' => "Tidak ditemukan", 'data' => $data));
			}
			// $data = $data_produk->result_array();
		} catch (Exception $e) {
			response(500, array('status' => 'failed', 'message' => "Gagal karena " . $e->getMessage(), 'data'=>$data));
		}
		response(200, $data);
	}

	private function cek_str(String $input){
		$str = str_split($input);
		$counted_str = count($str);
		if($counted_str < 3){
			return false;
		}else{
			return true;
		}
	}

	public function prosesinput_produk()
	{
		$nama_produk = $this->input->post('nama_produk');
		$kategori = $this->input->post('kategori');
		
		$berat_produk = $this->input->post('berat_produk');
		$min_pemesanan = $this->input->post('min_pemesanan');
		$id_usaha = $this->input->post('id_usaha');
		$total_ekor_per_kg = $this->input->post('total_ekor_per_kg');	// Maksimal Ekor Dalam 1 Kg
		// SETTING UPLOAD FOTO
		$file_name						= date('dmYHis') . $_FILES['foto_produk']['name'];
		// var_dump($_FILES);
		$config['upload_path']          = './foto_usaha/produk/';
		$config['allowed_types']        = 'gif|jpg|jpeg|png';
		$config['max_size']             = 20480;
		$config['max_width']            = 5000;
		$config['max_height']           = 5000;
		$config['file_name']			= $file_name;
		// var_dump($config);
		// exit();
		$status = 100;
		$responseMessage = '';
		$response = array();
		
		$array_produk = array(
			'nama_produk' =>$nama_produk,
			'kategori' => $kategori,
			'berat_produk' => $berat_produk,
			'min_pemesanan' => $min_pemesanan,
			'id_usaha' => $id_usaha,
			'ekor_per_kg' => $total_ekor_per_kg);
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('foto_produk')){
			$dataFoto = array('error' => $this->upload->display_errors());
			// var_dump($dataFoto);
			
		}else{
			$dataFoto = array('upload_data' => $this->upload->data());
			$config = array();
			$config['image_library']='gd2';
            $config['source_image']='./foto_usaha/produk/'.$dataFoto['upload_data']['file_name'];
            $config['create_thumb']= FALSE;
            $config['maintain_ratio']= FALSE;
            $config['quality']= '50%';
            $config['width']= 600;
            $config['height']= 400;
            $config['new_image']= './foto_usaha/produk/'.$dataFoto['upload_data']['file_name'];
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
			// var_dump($dataFoto);
			// exit();
			$foto_produk		=   $dataFoto['upload_data']['file_name'];
			$array_produk['foto_produk'] = $foto_produk;
		}
		
		// END UPLOAD FOTO
		
		
		$insert_produk = $this->produk->insert_produk($array_produk);
		if($insert_produk){
			$id_produk = $this->db->insert_id();
			$variasi = $this->produk->ambil_data_variasi()->result();
			$insert=false;
			$variasi_produk = array();
			foreach ($variasi as $key) {
				$variasi_produk[] = array('id_produk' => $id_produk, 'id_variasi' => $key->id_variasi, 'harga' => 0, 'stok' => 0, 'status_vp' => 'tidak aktif');
			}
			$insert = $this->db->insert_batch("data_variasi_produk", $variasi_produk);

			if ($insert) {
				$status = 200;
				$responseMessage = 'Berhasil Menambahkan Produk Dengan Variasi';
				$response = array('status' => 'berhasil', 'responseMessage' => $responseMessage, 'id_produk' => $id_produk);
			} else {
				// echo $koneksi->error . '<br>';
				$status = 202;
				$responseMessage = 'Berhasil Menambahkan Produk, Tetapi Gagal Menambahkan Variasi';
				$response = array('status' => 'berhasil', 'responseMessage' => $responseMessage, 'id_produk' => $id_produk);
				// echo $status;
				// exit();
			}
		} else {
			$status=400;
			$responseMessage = 'Gagal Menginput Produk';
			$response = array('status' => 'gagal', 'responseMessage' => $responseMessage);
		}

		$this->output
		            ->set_status_header($status)
		            ->set_content_type('application/json', 'utf-8')
		            ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function ambil_data_produk_update()
	{
		$id_produk = $this->input->post('id_produk');
		$data = $this->produk->ambil_data_by_id($id_produk);
		if($data->num_rows() > 0){
			$produk = $data->row_array();
		}else{
			$produk = array();
		}
		header("Content-type:application/json");
		echo json_encode($produk);
	}

	public function getAllVariasi()
	{
		$data = $this->db->get("data_variasi")->result_array();
		$response = array('data' => $data, 'responseMessage' => 'success');
		$this->responseJSON(200, $response);
	}

	public function ambil_data_variasi()
	{
		$id_produk = $this->input->get('id_produk');
		$ambil_data_variasi = $this->produk->ambil_data_variasi($id_produk);
		if($ambil_data_variasi->num_rows() > 0){
			foreach ($ambil_data_variasi->result_array() as $dv) {
				$produk_var = $this->produk->ambil_variasi_produk($id_produk, $dv['id_variasi']);
				// echo $this->db->last_query();
				if($produk_var->num_rows() > 0){
					$SELECT = 'checked';
				}else{
					$SELECT = '';
				}
				$data[] = array('id'=>$dv['id_variasi'], 'text'=>$dv['nama_variasi'], 'selected' => $SELECT);
			}
		}else{
			$data = array();
		}
		header("Content-type:application/json");
		echo json_encode($data);
	}

	public function prosesupdate_produk()
	{
		$id_produk			= intval($this->input->post('id_produk'));
		$nama_produk		= $this->input->post('nama_produk');
		$berat_produk		= intval($this->input->post('berat_produk'));
		$id_toko			= intval($this->input->post('id_toko'));
		$min_pemesanan		= intval($this->input->post('minOrder'));
		$ekor_per_kg 		= intval($this->input->post('total_ekor_per_kg'));
		$deskripsi			= $this->input->post('deskripsi');
		// $variasi			= ($_POST['variasi']) ? $_POST['variasi'] : [];
		// $variasi			= ($this->input->post('variasi')!==null) ? $this->input->post('variasi') : null;

		// SETTING UPLOAD FOTO
		$file_name						= date('dmYHis') . $_FILES['foto_produk']['name'];
		$config['upload_path']          = './foto_usaha/produk/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 20480;
		$config['max_width']            = 5000;
		$config['max_height']           = 5000;
		$config['file_name']			= $file_name;
		// $config['file_name']			= $foto_name;
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('foto_produk')){
			$dataFoto = array('error' => $this->upload->display_errors());
			$datafoto_produk = $this->produk->ambil_data_by_id($id_produk)->row();
			$foto_produk	= $datafoto_produk->foto_produk;
			// echo "Gagal";
			// var_dump($dataFoto);
			// ->row()->foto_produk
			// echo $this->db->last_query();
		}else{
			$dataFoto			= array('upload_data' => $this->upload->data());
			// var_dump($dataFoto);
			$foto_produk		= $dataFoto['upload_data']['file_name'];
			$config = array();
			$config['image_library']='gd2';
            $config['source_image']='./foto_usaha/produk/'.$dataFoto['upload_data']['file_name'];
            $config['create_thumb']= FALSE;
            $config['maintain_ratio']= TRUE;
            $config['quality']= '50%';
            $config['width']= 600;
            $config['height']= 400;
            $config['new_image']= './foto_usaha/produk/'.$dataFoto['upload_data']['file_name'];
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
            $datafoto_produk = $this->produk->ambil_data_by_id($id_produk)->row();
            if(unlink('./foto_usaha/produk/'.$datafoto_produk->foto_produk)){

            }
		}
		// exit();
		// END UPLOAD FOTO
		$data_update = array(
			'nama_produk' => $nama_produk,
			'foto_produk' => $foto_produk,
			'berat_produk' => $berat_produk,
			'min_pemesanan' => $min_pemesanan,
			'ekor_per_kg' => $ekor_per_kg,
			'deskripsi' => $deskripsi
		);
		try {
			$update = $this->produk->ubah_produk($data_update, $id_produk);
			// echo $this->db->last_query();
			$statusCode = 200;
			if($update){
				$status = 'berhasil';
			}else{
				$statusCode = 304;
				$status = 'Gagal Mengubah Data Produk ';
			}
			$response = array(
				'status' => $status
			);
			$this->output
	        ->set_status_header($statusCode)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		} catch (Exception $e) {
			$response = array(
				'status' => 'Failed'
			);
			$this->output
	        ->set_status_header(500)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
		
	}

	public function prosesupdate_variasiproduk()
	{
		$id_produk = $this->input->post('id_produk');
		$variasi = $this->input->post('variasi');

	}

	public function hapus_data_produk()
	{
		$id_produk = $this->input->post('id_produk');
		$data = array('status_p' => "tidak aktif");
		
		$status_code = 0;
		$status = '';
		try {
			$update = $this->produk->ubah_produk($data, $id_produk);
			if($update){
				$status_code = 200;
				$status = 'berhasil';
			}else{
				$status_code = 404;
				$status = 'gagal';
			}
		} catch (Exception $e) {
			$status_code = 500;
			$status = 'error ' . $e->getMessage();
		}

		$response = array(
			'status' => $status
		);

		$this->output
	        ->set_status_header($status_code)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function aktifkan_produk()
	{
		$id_produk = $this->input->post('id_produk');
		$array = array('status_p' => 'aktif');
		try {
			$update = $this->produk->ubah_produk($array, $id_produk);
			if($update){
				$status_code = 200;
				$status = 'berhasil';
			}else{
				$status_code = 404;
				$status = 'gagal';
			}
		} catch (Exception $e) {
			$status_code = 500;
			$status = 'error ' . $e->getMessage();
		}
		$response = array(
			'status' => $status
		);

		$this->output
	        ->set_status_header($status_code)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function detail_produk()
	{
		$id_produk = $this->input->get('id_produk');
		$data = $this->produk->ambil_data_by_id($id_produk);
		$result = $data->row_array();
		echo json_encode($result);
	}

	public function detail_produk_variasi()
	{
		$id_produk = $this->input->post('id_produk');
		$variasi = $this->input->post('variasi');
		$data = $this->produk->ambilVariasiProduk($id_produk, $variasi);
		$result = $data->row_array();
		echo json_encode($result);
	}

	public function ambil_stok_variasi()
	{
		$PEMBELI = new Model_pembeli();
		$KERANJANG = new Model_keranjang();
		$variasi = intval($this->input->get('variasi', TRUE));
		$id_akun = intval($this->input->get('id_akun', TRUE));
		$data = array();
		// var_dump($this->input->get());
		// exit();
		try {
			$stok_item = $this->produk->ambil_stok_variasi($variasi);
			if($stok_item->num_rows() > 0){
				$data['stok_item'] = $stok_item->row_array();
				if($id_akun){
					$cek_pembeli = $PEMBELI->detail_pembeli($id_akun);
					$row_pb = $cek_pembeli->num_rows();
					if($row_pb > 0){
						$cek_keranjang = $KERANJANG->get_detail_produk_keranjang_pembeli($variasi, $id_akun);
						// echo $this->db->last_query();
						$row_keranjang = $cek_keranjang->num_rows();
						if($row_keranjang > 0){
							$data['item_keranjang'] = $cek_keranjang->row_array();
						}else{
							$data['item_keranjang'] = array();
						}
					}
				}
				$data['status'] = "success";
				response(200, $data);
			}else{
				$data['status'] = "not found";
				response(404, $data);
			}
		} catch (\Throwable $th) {
			$data['message'] = $th->getMessage();
			$data['status'] = "failed";
			response(500, $data);
		}
	}

	public function all_var_produk()
	{
		$id_produk = $this->input->get('id_produk');
		$data = $this->produk->ambil_var_by_produk($id_produk);

		$this->output
	        ->set_status_header(200)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode($data->result_array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function detail_var_produk()
	{
		$id_produk = $this->input->post('id_produk');
		$data = $this->produk->ambil_var_by_produk($id_produk);
		$result = $data->row_array();
		echo json_encode($result);
	}

	public function updatevariasi_produk($data)
	{
		$id_produk = $this->input->post('id_produk');
		$variasi = ($this->input->post('dVariasi')) ? $this->input->post('dVariasi') : [];
		$harga = $this->input->post('harga');
		if(count($variasi) > 0){
			$insert_variasi = $this->produk->insert_variasi($data);
		}
	}

	public function ambil_variasi_produk()
	{
		$id_produk = $this->input->get('id_produk');
		$data = $this->produk->ambil_variasi_produk($id_produk);
		// echo $this->db->last_query()
		// exit();
		$result = $data->result_array();
		$this->output
	        ->set_status_header(200)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function ambil_variasi_by_id()
	{
		$id_var = $this->input->post('id_var');
		$data = $this->produk->ambil_variasi_by_id($id_var);
		$result = $data->row_array();
		header("Content-type:application/json");
		echo json_encode($result);
	}

	public function cek_variasi_var()
	{
		$id_produk = $this->input->post('id_produk');
		$id_var = $this->input->post('var');
		$ambil_data_variasi = $this->produk->ambil_data_variasi();
		if($ambil_data_variasi->num_rows() > 0){
			foreach ($ambil_data_variasi->result_array() as $dv) {
				$produk_var = $this->produk->ambil_variasi_by_id($id_var, $dv['id_variasi']);
				// echo $this->db->last_query();
				if($produk_var->num_rows() > 0){
					$SELECT = 'checked';
				}else{
					$SELECT = '';
				}
				$data[] = array('id'=>$dv['id_variasi'], 'text'=>$dv['nama_variasi'], 'selected' => $SELECT);
			}
		}else{
			$data = array();
		}
		header("Content-type:application/json");
		echo json_encode($data);
	}

	public function updatevariasi_produk2()
	{
		$variasi = $this->input->post('variasi');
		$harga = $this->input->post('harga');
		$id_variasiproduk = $this->input->post('id_variasiproduk');

		$data = array('id_variasi' => $variasi, 'harga' => $harga);
		$update = $this->produk->update_variasi($data, $id_variasiproduk);
		if($update){
			$status = 'berhasil';
		}else{
			$status = 'gagal';
		}
		$response = array('status' => $status);
		header("Content-type:application/json");
		echo json_encode($response);
	}

	public function updateVariasiProdukV3()
	{
		$jenis_variasi = $this->input->post('variasi');
		$id_produk = $this->input->post('id_produk');
		$hargaInput = $this->input->post('harga');
		$stokInput = $this->input->post('stok');
		$arrayData = array();
		try {
			$disablingVariasi = $this->db->update("data_variasi_produk", array('status_vp' => 'tidak aktif'), "id_produk = '$id_produk'");
			if(!empty($jenis_variasi)){
				for ($i=0; $i < count($jenis_variasi); $i++) { 
					$id = $jenis_variasi[$i];
					$harga = $hargaInput[$i];
					$stok = $stokInput[$i];
					$where = "id_produk = '$id_produk' AND id_variasi = '$id'";
					$arrayData = array('harga' => $harga, 'stok' => $stok, 'status_vp' => 'aktif');
					$update = $this->db->update("data_variasi_produk", $arrayData, $where);
					$this->db->reset_query();
					if(!$update){
						$this->responseJSON(400, array('status' => 'Failed'));
					}
				}
			}
			$response = array('status' => 'success');
			$this->responseJSON(200, $response);
		} catch (Exception $e) {
			$response = array('status' => 'failed');
			$this->responseJSON(500, $response);
		}	
	}

	public function responseJSON($statusHeader, $response)
	{
		$this->output
		        ->set_status_header($statusHeader)
		        ->set_content_type('application/json', 'utf-8')
		        ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function tambahvariasi_produk()
	{
		$id_produk = $this->input->post('id_produk');
		$variasi = $this->input->post('variasi');
		$harga = $this->input->post('harga');

		$data = array('id_produk' => $id_produk, 'id_variasi' => $variasi, 'harga' => $harga);
		$tambah = $this->produk->insert_variasi($data);
		if($tambah){
			$status = "berhasil";
		}else{
			$status = "gagal";
		}
		$response = array('status' => $status);
		header("Content-type:application/json");
		echo json_encode($response);
	}

	public function hapus_variasi_by_id()
	{
		$id_variasiproduk = $this->input->post('id_var');
		$hapus = $this->produk->hapus_variasi_produk_by_id($id_variasiproduk);
		if($hapus){
			$status = "berhasil";
		}else{
			$status = "gagal";
		}
		$response = array('status' => $status);
		header("Content-type:application/json");
		echo json_encode($response);
	}

	public function get_image_slider()
	{
		$data = $this->produk->ambil_img_slider_produk();
		$response = array();
		if($data->num_rows() > 0){
			$response = array('data' => $data->result_array(), 'max' => $data->num_rows());
		}else{
			$response = array('data' => null);
		}
		header("X-Content-Type-Options: nosniff");
		header("Content-type: application/json");
		echo json_encode($response);
	}

	public function get_all()
	{
		# code...
	}

	public function ambil_produk_kategori()
	{
		$kategori = $this->input->post('kat');
		$data = $this->produk->ambil_produk_kategori($kategori);
		$response = array();
		if($data->num_rows() > 0){
			$response = $data->result_array();
		}

		header("Content-type: application/json");
		echo json_encode($response);
	}

	public function all_produk()
	{
		echo "ALL PRODUK";
	}

	

	
}