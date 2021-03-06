<?php

/**
 * 
 */
class Pengiriman extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		$this->load->model("Model_pemesanan");
		$this->load->model("Model_pembayaran");
		$this->load->model("Model_produk");
		$this->load->model("Model_penjual");
		$this->load->model("Model_pembeli");
		$this->load->model("Model_pengiriman");
		$this->load->model("Model_kurir");
		$this->load->model("Model_kendaraan");
		// $this->load->model("Model_usaha");
		$this->StatusPemesananBaru = "Baru";
		$this->load->library('encryption');
		$this->encryption->initialize(array('driver' => 'mcrypt'));
		date_default_timezone_set("Asia/Bangkok");
		$this->load->helper("Response_helper");
	}

	public function index()
	{
		# code...
	}

	public function insert_location_tracking()
	{
		try {
			$DataTrack = new Model_kurir();
			$latitude = floatval($this->input->post('latitude', TRUE));
			$longitude = floatval($this->input->post('longitude', TRUE));
			$id_kurir = intval($this->input->post('id_kurir', TRUE));
			$data_track = array('id_kurir' => $id_kurir, 'latitude' => $latitude, 'longitude' => $longitude);
			$DataTrack->insert_location_kurir($data_track);
			$id_track = $DataTrack->get_idTrack();
			$result = array('id_track' => $id_track, 'message' => "success", $this->input->post());
			response(200, $result);
		} catch (\Throwable $th) {
			//throw $th;
			response(500, array('message' => "Error: " . $th->getMessage()));
		}
	}

	public function get_pengiriman()
	{
		$Penjual = new Model_penjual();
		$Pengiriman = new Model_pengiriman();
		$Pembeli = new Model_pembeli();
		$id_akun = $this->input->get('akun', TRUE);
		$result = array();
		try {
			// $id_pj = $this->input->post("id_akun", TRUE);
			$data_penjual = $Penjual->data_profile($id_akun);
			// echo "id_pj : " . $id_pj;
			if ($data_penjual->num_rows() == 0) {
				$this->db->reset_query();
				$this->Model_pembeli->set_id_pembeli($id_akun);
				$Data_pembeli = $this->Model_pembeli->profile()->get();
				if ($Data_pembeli->num_rows() > 0) {
					return $this->get_pengiriman_pembeli();
				} else {
					response(401, array('message' => "authentication is needed"));
				}
			}
			$id_pengiriman = $this->input->get('id_pengiriman', TRUE);
			// echo "Proses cari pengirman dengan id penjual :" . $id_akun;
			$data_pengiriman = $this->Model_pengiriman->getPengirimanByIdPengiriman($id_pengiriman);
			$detail_pengiriman = $this->Model_pengiriman->getDetailPengirimanByIdPengiriman($id_pengiriman);
			if ($detail_pengiriman->num_rows() == 0) {
				response(400, array('status' => "Failed", 'message' => "Data pengiriman tidak ditemukan", 'id_pengiriman' => $id_pengiriman));
			}
			$result = $data_pengiriman->row_array();
			foreach ($detail_pengiriman->result() as $detail) {
				$where = "pemesanan.id_pemesanan = $detail->id_pemesanan";
				$select_pemesanan = "pemesanan.tipe_pengiriman, pemesanan.total_harga as total_harga, pemesanan.id_pb, pemesanan.id_usaha, pembeli.nama_pb, pembeli.latitude_pb, pembeli.longitude_pb, pembeli.alamat_pb, pembeli.kel_pb, pembeli.kec_pb, pembeli.foto_pb, pembeli.telp_pb, pembeli.kab_pb, usaha.nama_usaha, usaha.alamat_usaha, usaha.latitude, usaha.longitude";
				$join[] = array('table' => "data_pembeli pembeli", 'on' => 'pemesanan.id_pb = pembeli.id_pb', 'join' => null);
				$join[] = array('table' => "data_usaha usaha", 'on' => 'pemesanan.id_usaha = usaha.id_usaha', 'join' => null);
				$pemesanan = $this->Model_pemesanan->get_where($select_pemesanan, $where, $join);
				$data_pemesanan = $pemesanan->row();
				$alamat_pembeli = $data_pemesanan->alamat_pb;
				$kelurahan_pembeli = ($data_pemesanan->kel_pb !== "") ? $data_pemesanan->kel_pb : "";
				$kecamatan_pembeli = ($data_pemesanan->kec_pb !== "") ? $data_pemesanan->kec_pb : "";
				$kabupaten_pembeli = ($data_pemesanan->kab_pb !== "") ? $data_pemesanan->kab_pb : "";
				$notelp_pembeli = ($data_pemesanan->telp_pb !== "") ? $data_pemesanan->telp_pb : "";
				$foto_pb = base_url("foto_pembeli/") . $data_pemesanan->foto_pb;
				// var_dump($data_pemesanan->result());
				$detail_pemesanan = $this->Model_pemesanan->getDetailPemesanan($detail->id_pemesanan);
				$data_detail_pemesanan = $detail_pemesanan->row();
				$result['detail_pengiriman'][] = array(
					'urutan' => intval($detail->urutan),
					'id_pemesanan' => intval($detail->id_pemesanan),
					'id_pembeli' => intval($data_pemesanan->id_pb),
					'status' => $detail->status,
					'detail_pembeli' => array(
						'nama' => $data_pemesanan->nama_pb,
						'alamat_pembeli' => $alamat_pembeli,
						'kelurahan' => $kelurahan_pembeli,
						'kecamatan' => $kecamatan_pembeli,
						'kabupaten' => $kabupaten_pembeli,
						'no_telp' => $notelp_pembeli,
						'foto_pb' => $foto_pb,
					),
					'detail_pemesanan' => array(
						'total_harga' => intval($data_pemesanan->total_harga),
						'detail_produk' => $this->construct_data_produk($detail->id_pemesanan),
						'detail_pembayaran' => $this->construct_detail_pembayaran($detail->id_pemesanan),
					),
					'destinasi' => array(
						'latitude' => floatval($data_pemesanan->latitude_pb),
						'longitude' => floatval($data_pemesanan->longitude_pb)
					)
				);
			}
			$result_usaha = $this->Model_penjual->ambil_data_usaha($id_akun)->row();
			$result['asal'] = array(
				'latitude' => floatval($result_usaha->latitude),
				'longitude' => floatval($result_usaha->longitude)
			);
			$cek_lokasi_kurir = $this->track_lokasi_kurir(intval($result['id_kurir']));
			$result['detail_kurir'] = $this->contruct_detail_kurir($result['id_kurir']);
			$result['detail_kendaraan'] = $this->construct_detail_kendaraan($result['id_kendaraan']);
			$result['detail_usaha'] = $this->construct_detail_usaha($result['id_pj']);
			if($cek_lokasi_kurir==null){
				
				$result['lokasi_kurir'] = $this->construct_detail_usaha($result['id_pj']);
			}else{
				$result['lokasi_kurir'] = $this->track_lokasi_kurir(intval($result['id_kurir']));
			}
			response(200, $result);
		} catch (Exception $e) {
			response(500, $result);
		}
	}

	protected function construct_data_produk($id_pemesanan)
	{
		// $Pemesanan = new Model_pemesanan();
		$Data_produk = $this->Model_pemesanan->getDetailPemesanan($id_pemesanan);
		// echo $this->db->last_query();
		// exit();
		$result = array();
		foreach ($Data_produk->result() as $produk) {
			$result[] = array(
				'id_produk' => intval($produk->id_produk),
				'nama_produk' => $produk->nama_produk,
				'foto_produk' => base_url('foto_usaha/produk/') . $produk->foto_produk,
				'nama_variasi' => $produk->nama_variasi,
				'harga' => intval($produk->harga),
				'qty' => intval($produk->berat_akhir),
				'sub_total' => intval($produk->sub_total)
			);
		}
		return $result;
	}

	protected function construct_detail_usaha($id_pj)
	{
		$Usaha = new Model_penjual();
		$this->db->where("id_pj", $id_pj)
			->limit(1);
		$response_detail_usaha = $Usaha->ambil_semua_usaha()->row();
		return array(
			'nama_usaha' => $response_detail_usaha->nama_usaha,
			'alamat_usaha' => $response_detail_usaha->alamat_usaha,
			'longitude' => floatval($response_detail_usaha->longitude),
			'latitude' => floatval($response_detail_usaha->latitude)
		);
	}

	protected function contruct_detail_kurir($id_kurir)
	{
		$data_kurir = $this->Model_kurir->get_by_id($id_kurir);
		if ($data_kurir->num_rows() > 0) {
			$response_detail_kurir = $data_kurir->row();
			return array(
				'nama_kurir' => $response_detail_kurir->nama_kurir,
				'foto_kurir' => base_url('foto_kurir/' . $response_detail_kurir->foto_kurir),
				'kontak' => $response_detail_kurir->telp_kurir
			);
		} else {
			return null;
		}
	}

	protected function construct_detail_kendaraan($id_kendaraan)
	{
		$Kendaraan = new Model_kendaraan();
		$response_detail_kendaraan = $Kendaraan->get_detail_kendaraan($id_kendaraan)->row();
		return array(
			'jenis_kendaraan' => $response_detail_kendaraan->jenis_kendaraan,
			'plat' => $response_detail_kendaraan->plat_kendaraan,
			'kapasitas' => $response_detail_kendaraan->kapasitas_kendaraan
		);
	}

	protected function construct_detail_pembayaran($id_pemesanan)
	{
		$detail_pembayaran = $this->Model_pembayaran->getDataPembayaranOnlyByIdPemesanan($id_pemesanan)->row();//->get_selected_pembayaran("", $where)->row();
		return array(
			'metode_pembayaran' => $detail_pembayaran->metode_pembayaran,
			'waktu_pembayaran' => $detail_pembayaran->waktu_pembayaran,
			'status_pembayaran' => $detail_pembayaran->status_pembayaran
		);
	}

	private function get_pengiriman_pembeli()
	{
		$Pengiriman = new Model_pengiriman();
		$Pembeli = new Model_pembeli();
		$id_akun = intval($this->input->get("akun", TRUE));
		$id_pemesanan = intval($this->input->get("id_pemesanan", TRUE));
		try {
			//code...
			$detail_pengiriman = $this->Model_pengiriman->getDetailPengirimanPembeliByIdPemesanan($id_pemesanan, $id_akun);
			
			if ($detail_pengiriman->num_rows() == 0) {
				response(404, array('status' => "failed", 'message' => "data pengiriman tidak ditemukan", 'id_pemesanan' => $id_pemesanan));
			}
			// echo $this->db->last_query();
			$detail = $detail_pengiriman->row();
			$id_pengiriman = $detail->id_pengiriman;
			$data_pengiriman = $this->Model_pengiriman->getPengirimanByIdPengiriman($id_pengiriman);
			
			$result = $data_pengiriman->row_array();
			$resultObject = $data_pengiriman->row();
			$this->db->reset_query();
			$pemesanan = $this->Model_pemesanan->getDataPemesananPembeliDanUsahaByIdPemesanan($id_pemesanan);
			
			$data_pemesanan = $pemesanan->row();
			$alamat_pembeli = $data_pemesanan->alamat_pb;
			$kelurahan_pembeli = ($data_pemesanan->kel_pb !== "") ? $data_pemesanan->kel_pb : "";
			$kecamatan_pembeli = ($data_pemesanan->kec_pb !== "") ? $data_pemesanan->kec_pb : "";
			$kabupaten_pembeli = ($data_pemesanan->kab_pb !== "") ? $data_pemesanan->kab_pb : "";
			$notelp_pembeli = ($data_pemesanan->telp_pb !== "") ? $data_pemesanan->telp_pb : "";
			$foto_pb = base_url("foto_pembeli/") . $data_pemesanan->foto_pb;
			


			$result['detail_pengiriman'] = array(
				'urutan' => intval($detail->urutan),
				'id_pemesanan' => intval($detail->id_pemesanan),
				'id_pembeli' => intval($data_pemesanan->id_pb),
				'status' => $detail->status,
				'detail_pembeli' => array(
					'nama' => $data_pemesanan->nama_pb,
					'alamat_pembeli' => $alamat_pembeli,
					'kelurahan' => $kelurahan_pembeli,
					'kecamatan' => $kecamatan_pembeli,
					'kabupaten' => $kabupaten_pembeli,
					'no_telp' => $notelp_pembeli,
					'foto_pb' => $foto_pb,
				),
				'detail_pemesanan' => array(
					'total_harga' => intval($data_pemesanan->total_harga),
					'detail_produk' => $this->construct_data_produk($detail->id_pemesanan),
					'detail_pembayaran' => $this->construct_detail_pembayaran($detail->id_pemesanan),
				),
				'destinasi' => array(
					'latitude' => floatval($data_pemesanan->latitude_pb),
					'longitude' => floatval($data_pemesanan->longitude_pb)
				)
			);
			$result_usaha = $this->Model_penjual->ambil_data_usaha($resultObject->id_pj)->row();
			$result['asal'] = array(
				'latitude' => floatval($result_usaha->latitude),
				'longitude' => floatval($result_usaha->longitude)
			);
			$cek_lokasi_kurir = $this->track_lokasi_kurir(intval($result['id_kurir']));
			$result['detail_kurir'] = $this->contruct_detail_kurir($result['id_kurir']);
			$result['detail_kendaraan'] = $this->construct_detail_kendaraan($result['id_kendaraan']);
			$result['detail_usaha'] = $this->construct_detail_usaha($result['id_pj']);
			if($cek_lokasi_kurir==null){
				
				$result['lokasi_kurir'] = $this->construct_detail_usaha($result['id_pj']);
			}else{
				$result['lokasi_kurir'] = $this->track_lokasi_kurir(intval($result['id_kurir']));
			}
			
			response(200, $result);
		} catch (\Throwable $th) {
			//throw $th;
		}
	}

	protected function track_lokasi_kurir(int $id_kurir)
	{
		$lokasi_kurir = $this->Model_pengiriman->get_lokasi_kurir($id_kurir);
		if ($lokasi_kurir->num_rows() > 0) {
			$data_lokasi = $lokasi_kurir->row();
			return array('latitude' => $data_lokasi->latitude, 'longitude' => $data_lokasi->longitude);
		} else {
			return null;
		}
	}

	public function track_pengiriman_pesanan()
	{

		try {
			$id_kurir = $this->input->get("id_kurir", TRUE);
			$id_pengiriman = $this->input->get("id_pengiriman", TRUE);
			// $data_tracking = new Object();
			$data_tracking = null;
			$data_track_kurir = $this->Model_kurir->get_location_kurir($id_kurir);
			$data_pengiriman = $this->Model_pengiriman->getPengirimanByIdPengiriman($id_pengiriman)->row();
			if($data_track_kurir->num_rows() == 0){
				$id_pj = $data_pengiriman->id_pj;
				$data_usaha = $this->Model_penjual->ambil_data_usaha($id_pj)->row();
				$data_tracking = array('latitude' => floatval($data_usaha->latitude), 'longitude' => floatval($data_usaha->longitude));
			}else{
				$resultLokasiKurir = $data_track_kurir->row();
				$data_tracking = array('latitude' => floatval($resultLokasiKurir->latitude), 'longitude' => floatval($resultLokasiKurir->longitude));
			}
			$this->db->where("detail.status", "pengantaran");
			$Detail_pengiriman = $this->Model_pengiriman->getDetailPengirimanByIdPengiriman($id_pengiriman)->row();
			// $id_pengiriman = $id_pengiriman;
			$id_pemesanan = $Detail_pengiriman->id_pemesanan;
			$id_kendaraan = $data_pengiriman->id_kendaraan;
			$this->db->reset_query();
			
			$Data_pesanan = $this->Model_pemesanan->getPemesananPengirimanByIdPemesanan($id_pemesanan)->row();
			$this->db->reset_query();

			$Data_kendaraan = $this->Model_kendaraan->get_detail_kendaraan($id_kendaraan)->row();
			// var_dump($Data_pesanan);
			$destination = array(
				'latitude' => floatval($Data_pesanan->latitude_pb),
				'longitude' => floatval($Data_pesanan->longitude_pb)
			);
			$origin = $data_tracking;
			$vehicle = array(
				'latitude' => floatval($data_tracking['latitude']),
				'longitude' => floatval($data_tracking['longitude']),
				'detail' => array(
					'jenis' => $Data_kendaraan->jenis_kendaraan,
					'plat' => $Data_kendaraan->plat_kendaraan
				)
			);
			$result = array(
				'id_pengiriman' => intval($id_pengiriman),
				'id_kurir'  => intval($id_kurir),
				'id_pembeli' => intval($Data_pesanan->id_pb),
				'id_pesanan' => intval($id_pemesanan),
				'tipe' => $Data_pesanan->tipe_pengiriman,
				'destination' => $destination,
				'origin' => $origin,
				'vehicle' => $vehicle,
				'message' => "success"
			);

			response(200, $result);
		} catch (\Throwable $th) {
			//throw $th;
			response(500, array('messsage' => $th->getMessage()));
		}
	}

	public function update_detail_tracking_pengiriman()
	{
		try {
			$id_pemesanan = intval($this->input->post("id_pemesanan", TRUE));
			$id_pengiriman = $this->input->post("id_pengiriman", TRUE);
			$nama_peneriman = $this->input->post("nama_penerima", TRUE);
			$datetime = date("Y-m-d H:i:s");
			$status_pengiriman = "terkirim";
			$status_pemesanan = ucfirst($status_pengiriman);
			$result = array();

			//TODO: UPDATE DATA DETAIL PENGIRIMAN DAN PEMESANAN YANG SEDANG DI SELESAIKAN KURIR
			$data_pengiriman = array('status' => $status_pengiriman, 'penerima' => ucwords($nama_peneriman));
			$data_pemesanan = array('status_pemesanan' => $status_pemesanan);
			$updatePengiriman = $this->Model_pengiriman->updateStatusDetailPengirimanByIdPemesanan($id_pemesanan, $data_pengiriman);
			$this->db->reset_query();
			// echo $this->db->last_query() . "\n";
			$updatePemesanan = $this->Model_pemesanan->update_pemesanan($id_pemesanan, $data_pemesanan);
			$this->db->reset_query();
			// echo $this->db->last_query() . "\n";

			//TODO: UPDATE DATA DETAIL PENGIRIMAN SETELAH DATA TERAKHIR TERUPDATE
			$next_pengiriman = array();
			if ($updatePengiriman && $updatePengiriman) {
				$this->db->where("status", "menunggu")
						->order_by("detail.urutan", 'ASC');
				$Detail_pengiriman1 = $this->Model_pengiriman->getDetailPengirimanByIdPengiriman($id_pengiriman);
				$result = array();
				// CEK KONDISI PENGIRIMAN, MASIH ADA ATAU ENGGAK
				if ($Detail_pengiriman1->num_rows() == 0) {
					// KONDISI JIKA PENGIRIMAN SUDAH TIDAK ADA YANG MENGANTRI
					$result = array(
						'nama_penerima' => $nama_peneriman,
						'status_pengiriman' => $status_pengiriman,
						'status_pemesanan' => $status_pemesanan,
						'id_pemesanan' => $id_pemesanan,
						'message'	=> "Pengiriman telah selesai",
						'code'		=> 2,
						'date_end' => $datetime,
						'next_pengiriman' => $next_pengiriman
					);
					response(200, $result);
				} else {
					// KONDISI JIKA PENGIRIMAN MASIH ADA LAINNYA / MENGANTRI
					$next_pengiriman = $Detail_pengiriman1->row_array();
					$result = array(
						'nama_penerima' => $nama_peneriman,
						'status_pengiriman' => $status_pengiriman,
						'status_pemesanan' => $status_pemesanan,
						'id_pemesanan' => $id_pemesanan,
						'message' 	=> "Melanjutkan pengiriman selanjutnya",
						'code'		=> 1,
						'date_end' => $datetime,
						'next_pengiriman' => $next_pengiriman
					);
					response(200, $result);
				}
				// $row_detail_pengiriman = $Detail_pengiriman1->row();

			} else {
				$error = $this->db->error();
				$result = array(
					'message' 	=> "failed " . $error['message'],
					'code'		=> 99
				);
				response(400, $result);
			}
		} catch (\Throwable $th) {
			//throw $th;
			response(500, array('message' => "Error: " . $th->getMessage(), 'code' => 0));
		}
	}
}
