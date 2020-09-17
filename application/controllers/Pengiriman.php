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

	public function get_pengiriman()
	{
		$Penjual = new Model_penjual();
		$Pengiriman = new Model_pengiriman();
		$id_pengiriman = $this->input->get('id_pengiriman', TRUE);
		$id_penjual = $this->input->get('akun', TRUE);
		$result = array();
		try {
			// $id_pj = $this->input->post("id_akun", TRUE);
			$data_penjual = $Penjual->data_profile($id_penjual);
			// echo "id_pj : " . $id_pj;
			if ($data_penjual->num_rows() > 0) {
				response(401, array('statusMessage' => "authentication is needed"));
			}
			$data_pengiriman = $this->Model_pengiriman->get_pengiriman_penjual($id_penjual);
			if ($data_pengiriman->num_rows() > 0) {
				$result = $data_pengiriman->row_array();
				$id_pengiriman = $data_pengiriman->row()->id_pengiriman;
				$Pengiriman->set_id_pengiriman($id_pengiriman);
				// $this->Model_pengiriman->id_pengiriman = $data_pengiriman->row()->id_pengiriman;
				$detail_pengiriman = $this->Model_pengiriman->get_detail_pengiriman();

				foreach ($detail_pengiriman->result() as $detail) {
					$where = "pemesanan.id_pemesanan = $detail->id_pemesanan";
					$select_pemesanan = "pemesanan.tipe_pengiriman, pemesanan.id_pb, pemesanan.id_usaha, pembeli.nama_pb, pembeli.latitude_pb, pembeli.longitude_pb, pembeli.alamat_pb, pembeli.kel_pb, pembeli.kec_pb, pembeli.kab_pb, usaha.nama_usaha, usaha.alamat_usaha, usaha.latitude, usaha.longitude";
					$join[] = array('table' => "data_pembeli pembeli", 'on' => 'pemesanan.id_pb = pembeli.id_pb', 'join' => null);
					$join[] = array('table' => "data_usaha usaha", 'on' => 'pemesanan.id_usaha = usaha.id_usaha', 'join' => null);
					$pemesanan = $this->Model_pemesanan->get_where($select_pemesanan, $where, $join);
					$data_pemesanan = $pemesanan->row();
					$alamat_pembeli = $data_pemesanan->alamat_pb;
					$kelurahan_pembeli = ($data_pemesanan->kel_pb !== "") ? $data_pemesanan->kel_pb : "";
					$kecamatan_pembeli = ($data_pemesanan->kec_pb !== "") ? $data_pemesanan->kec_pb : "";
					$kabupaten_pembeli = ($data_pemesanan->kab_pb !== "") ? $data_pemesanan->kab_pb : "";
					// var_dump($data_pemesanan->result());
					$detail_pemesanan = $this->Model_pemesanan->getDetailPemesanan($detail->id_pemesanan);
					$data_detail_pemesanan = $detail_pemesanan->row();
					$result['detail_pengiriman'][] = array(
						'urutan' => $detail->urutan,
						'status' => $detail->status,
						'id_pembeli' => $data_pemesanan->id_pb,
						'penerima' => $detail->penerima,
						'detail_pemesanan' => array(
							'nama_produk' => $data_detail_pemesanan->nama_produk,
							'berat' => $data_detail_pemesanan->jml_produk,
							'nama_variasi' => $data_detail_pemesanan->nama_variasi,
							'foto_produk' => base_url('foto_usaha/foto_produk') . $data_detail_pemesanan->foto_produk
						),
						'destinasi' => array(
							'lokasi' => array(
								'latitude' => $data_pemesanan->latitude_pb,
								'longitude' => $data_pemesanan->longitude_pb
							),
							'alamat_pembeli' => $alamat_pembeli,
							'kelurahan' => $kelurahan_pembeli,
							'kecamatan' => $kecamatan_pembeli,
							'kabupaten' => $kabupaten_pembeli
						)
					);
				}
				$result['lokasi_kurir'] = $this->track_lokasi_kurir($result['id_kurir']);
				response(200, $result);
			} else {
				response(404, $result);
			}
		} catch (Exception $e) {
			response(500, $result);
		}
	}

	protected function track_lokasi_kurir(int $id_kurir)
	{
		$lokasi_kurir = $this->Model_pengiriman->get_lokasi_kurir($id_kurir);
		if ($lokasi_kurir->num_rows() > 0) {
			$data_lokasi = $lokasi_kurir->row();
			return array('id_kurir' => $id_kurir, 'latitude' => $data_lokasi->latitude, 'longitude' => $data_lokasi->longitude);
		} else {
			return array('id_kurir' => $id_kurir, 'latitude' => 0, 'longitude' => 0);
		}
	}

	public function track_pengiriman_pesanan()
	{

		try {
			$id_kurir = $this->input->get("id_kurir", TRUE);
			$id_pengiriman = $this->input->get("id_pengiriman", TRUE);

			$Pengiriman = new Model_pengiriman();
			$Kurir = new Model_kurir();
			$Pesanan = new Model_pemesanan();
			$Kendaraan = new Model_kendaraan();
			$data_track_kurir = $Kurir->get_location_kurir($id_kurir);
			if (!$data_track_kurir) {
				response(404, array('message' => 'Data track kurir kosong'));
			}
			$data_pengiriman = $Pengiriman->data_pengiriman($id_pengiriman)->row();
			$this->db->where("detail.id_pengiriman", $id_pengiriman)
				->where("detail.status", "pengantaran");
			$Detail_pengiriman = $Pengiriman->Detail_pengiriman()->get()->row();
			$id_pengiriman = $Detail_pengiriman->id_pengiriman;
			$id_pemesanan = $Detail_pengiriman->id_pemesanan;
			$id_kendaraan = $data_pengiriman->id_kendaraan;
			$this->db->reset_query();

			$this->db->select("pembeli.nama_pb, pembeli.latitude_pb, pembeli.longitude_pb, usaha.nama_usaha, usaha.latitude as latitude_usaha, usaha.longitude as longitude_usaha")
				->join("data_pembeli pembeli", "pemesanan.id_pb = pembeli.id_pb")
				->join("data_usaha usaha", "pemesanan.id_usaha = usaha.id_usaha");
			$where = "pemesanan.id_pemesanan = " . $id_pemesanan;
			$Data_pesanan = $Pesanan->get_pemesanan_pengiriman($where)->row();
			$this->db->reset_query();

			$Data_kendaraan = $Kendaraan->get_detail_kendaraan($id_kendaraan)->row();
			// var_dump($Data_pesanan);
			$destination = array(
				'latitude' => floatval($Data_pesanan->latitude_pb),
				'longitude' => floatval($Data_pesanan->longitude_pb)
			);
			$origin = array(
				'latitude' => floatval($Data_pesanan->latitude_usaha),
				'longitude' => floatval($Data_pesanan->longitude_usaha)
			);
			$vehicle = array(
				'latitude' => floatval($data_track_kurir->latitude),
				'longitude' => floatval($data_track_kurir->longitude),
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
}
