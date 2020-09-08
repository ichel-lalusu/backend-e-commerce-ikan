<?php defined('BASEPATH') OR exit('No direct script access allowed');
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

	protected $id_penjual;
	protected $id_pengiriman;
	protected $id_kendaraan;

	public function index()
	{
		# code...
	}

	public function get_pengiriman()
	{
		$this->id_pengiriman = $this->input->get('id_pengiriman', TRUE);
		$this->id_penjual = $this->input->get('akun', TRUE);
		$result = array();
		try {
			// $id_pj = $this->input->post("id_akun", TRUE);
			$data_penjual = $this->Model_penjual->data_profile($id_pj);
			// echo "id_pj : " . $id_pj;
	        if($data_penjual->num_rows() > 0){
	            response(401, array('statusMessage' => "authentication is needed"));
	        }
	        $data_pengiriman = $this->Model_pengiriman->get_specified_pengiriman_penjual($id_pj, $id_pengiriman);
	        if($data_pengiriman->num_rows() > 0){
	        	$result = $data_pengiriman->row_array();
	        	$this->id_kendaraan = $result['id_kendaraan'];
	        	$detail_pengiriman = $this->Model_pengiriman->get_detail_pengiriman($id_pengiriman);

	        	foreach ($detail_pengiriman->result() as $detail) {
		        	$data_pemesanan = $this->set_data_pemesanan_pengiriman($detail->id_pemesanan);
		        	$alamat_pembeli = $data_pemesanan->alamat_pb;
		        	$kelurahan_pembeli = ($data_pemesanan->kel_pb!=="") ? $data_pemesanan->kel_pb : "";
					$kecamatan_pembeli = ($data_pemesanan->kec_pb!=="") ? $data_pemesanan->kec_pb : "";
					$kabupaten_pembeli = ($data_pemesanan->kab_pb!=="") ? $data_pemesanan->kab_pb : "";
		        	// var_dump($data_pemesanan->result());
	        		$detail_pemesanan = $this->Model_pemesanan->getDetailPemesanan($detail->id_pemesanan);
	        		$data_detail_pemesanan = $detail_pemesanan->row();
	        		$result['detail_pengiriman'][] = array(
	        										'id_pengiriman' => $detail->id_detail_pengiriman,
								        			'urutan' => $detail->urutan, 
								        			'status' => $detail->status, 
								        			'id_pembeli' => $data_pemesanan->id_pb,
								        			'penerima' => $detail->penerima,
								        			'detail_pemesanan' => $this->set_detail_pemesanan($detail->id_pemesanan),
								        			'asal' => $this->get_asal_pengiriman($detail->id_pemesanan),
									        		'tujuan' => array('lokasi' => array('latitude' => $data_pemesanan->latitude_pb,
																				        	'longitude' => $data_pemesanan->longitude_pb),
									        							'alamat' => $alamat_pembeli,
									        							'kelurahan' => $kelurahan_pembeli,
									        							'kecamatan' => $kecamatan_pembeli,
									        							'kabupaten' => $kabupaten_pembeli)
									        		);
	        	}
	        	$id_kurir = $result['id_kurir'];
	        	$data_kurir = $this->track_lokasi_kurir($id_kurir);
	        	if($data_kurir!==null){
	        		$result['data_kurir'] =  $data_kurir;
	        	}
	        	$result['data_kendaraan'] = $this->set_data_kendaraan();
	        	$result['semua_lokasi'] = $this->set_location_collection();
	        	response(200, $result);
	        }else{
	        	response(404, $result);
	        }
		} catch (Exception $e) {
			response(500, $result);
		}
	}
	public function track_pengiriman_pesanan($id_pengiriman, $id_pemesanan)
	{
		$this->id_pengiriman = $id_pengiriman;
		$result = array();
		try {
			$data_pengiriman_pesanan = $this->Model_pengiriman->data_pengiriman($id_pengiriman)->row();
			$detail_pengiriman = $this->Model_pengiriman->get_detail_pengiriman($id_pengiriman)->row();
			$status = $detail_pengiriman->status;
			if($status==="pengantaran"){

			}
			$data_pemesanan = $this->set_data_pemesanan_pengiriman($id_pemesanan);
        	$alamat_pembeli = $data_pemesanan->alamat_pb;
        	$kelurahan_pembeli = ($data_pemesanan->kel_pb!=="") ? $data_pemesanan->kel_pb : "";
			$kecamatan_pembeli = ($data_pemesanan->kec_pb!=="") ? $data_pemesanan->kec_pb : "";
			$kabupaten_pembeli = ($data_pemesanan->kab_pb!=="") ? $data_pemesanan->kab_pb : "";
			$result['data_track'] = array(
				'asal' => $this->get_asal_pengiriman($id_pemesanan),
				'tujuan' => array('lokasi' => array('latitude' => $data_pemesanan->latitude_pb,
										        	'longitude' => $data_pemesanan->longitude_pb),
    							'alamat' => $alamat_pembeli,
    							'kelurahan' => $kelurahan_pembeli,
    							'kecamatan' => $kecamatan_pembeli,
    							'kabupaten' => $kabupaten_pembeli),
				'realtime' => $this->track_lokasi_kurir($data_pengiriman_pesanan->id_kurir));
			response(200, $result);
		} catch (Exception $e) {
			
		}
	}

	protected function FunctionName()
	{
		# code...
	}

	protected function track_lokasi_kurir(int $id_kurir)
	{
		$data_kurir = $this->Model_pengiriman->data_kurir_pelacakan($id_kurir);
		$lokasi_kurir = $this->Model_pengiriman->get_lokasi_kurir($id_kurir);
		if($data_kurir->num_rows() > 0 && $lokasi_kurir->num_rows() > 0){
			$lokasi_kurir = $this->Model_pengiriman->get_lokasi_kurir($id_kurir);
			$data_lokasi = $lokasi_kurir->row();
			$detail_kurir = $this->Model_kurir->get_where(array('id_kurir'=>$id_kurir))->row();
			$nama_kurir = $detail_kurir->nama_kurir;
			$foto = ($detail_kurir->foto_kurir!=="") ? base_url('foto_kurir/' . $detail_kurir->foto_kurir) : "";
			return $this->set_data_kurir($data_lokasi->latitude, $data_lokasi->longitude, $id_kurir, $foto, $nama_kurir);	
		}else{
			$detail_pengiriman = $this->Model_pengiriman->get_detail_pengiriman($this->id_pengiriman, TRUE)->row();
			$id_pemesanan = $detail_pengiriman->id_pemesanan;
			$data_pemesanan = $this->set_data_pemesanan_pengiriman($id_pemesanan);
			$id_pj = $data_pemesanan->id_pj;
			$latitude = $data_pemesanan->latitude;
			$longitude = $data_pemesanan->longitude;
			$nama = $data_pemesanan->nama_pj;
			$foto = ($data_pemesanan->foto_pj!=="") ? base_url('foto_penjual/' . $data_pemesanan->foto_pj) : "";
			return $this->set_data_kurir($latitude, $longitude, $id_pj, $foto, $nama);	
		}
		
	}

	protected function get_asal_pengiriman($id_pengiriman="")
	{
		$asal_pengiriman = array();
		if($id_pengiriman!=="" || !empty($id_pengiriman)){
			$condition = "status='pengantaran'";
			$detail_pengiriman = $this->Model_pengiriman->get_detail_pengiriman_where_only($id_pengiriman, $condition);
			$data_detail_first = $detail_pengiriman->row();
			if($data_detail_first->urutan == 1 || $data_detail_first->urutan == "1"){
				$detail_pengiriman = $this->Model_pengiriman->get_detail_pengiriman_where_only($id_pengiriman, $condition)->row();
				$id_pemesanan_new = $detail_pengiriman->id_pemesanan;
	        	$data_pemesanan = $this->set_data_pemesanan_pengiriman($id_pemesanan_new);
				$id_usaha = $data_pemesanan->id_usaha;
				$data_usaha = $this->Model_penjual->ambil_usaha_by_id($id_usaha)->row();

				$latitude = $data_usaha->latitude;
				$longitude = $data_usaha->longitude;
				$alamat = $data_usaha->alamat_usaha;
				$kelurahan = $data_usaha->kel;
				$kecamatan = $data_usaha->kec;
				$kabupaten = $data_usaha->kab;
				return $this->set_asal_pengiriman($latitude, $longitude, $alamat, $kelurahan, $kecamatan, $kabupaten);
			}else{
				$urutan = intval($data_detail_first->urutan);
				$condition2 = "`urutan` = " . ($urutan > 1) ? $urutan-1 : $urutan;
				$detail_pengiriman = $this->Model_pengiriman->get_detail_pengiriman_where_only($id_pengiriman, $condition2)->row();
				$id_pemesanan_new = $detail_pengiriman->id_pemesanan;
	        	$data_pemesanan = $this->set_data_pemesanan_pengiriman($id_pemesanan_new);

	        	$latitude = $data_pemesanan->latitude_pb;
	        	$longitude = $data_pemesanan->longitude_pb;
	        	$alamat = $data_pemesanan->alamat_pb;
	        	$kelurahan = ($data_pemesanan->kel_pb!=="") ? $data_pemesanan->kel_pb : "";
				$kecamatan = ($data_pemesanan->kec_pb!=="") ? $data_pemesanan->kec_pb : "";
				$kabupaten = ($data_pemesanan->kab_pb!=="") ? $data_pemesanan->kab_pb : "";

				return $this->set_asal_pengiriman($latitude, $longitude, $alamat, $kelurahan, $kecamatan, $kabupaten);
			}
		}
	}

	protected function set_asal_pengiriman($latitude="", $longitude="", $alamat="", $kelurahan="", $kecamatan="", $kabupaten="")
	{
		$latlng = array('latitude' => $latitude,
			        	'longitude' => $longitude);
		$asal_pengiriman = array('lokasi' => $latlng,
    							'alamat' => $alamat,
    							'kelurahan' => $kelurahan,
    							'kecamatan' => $kecamatan,
    							'kabupaten' => $kabupaten);
		return $asal_pengiriman;
	}

	protected function set_data_kurir($latitude="", $longitude="", $id="", $foto="", $nama="")
	{
		$latlng = array('latitude' => $latitude, 'longitude' => $longitude);
		return array('id_kurir' => $id, 'lokasi' => $latlng, 'nama' => $nama, 'foto' => $foto);
	}

	protected function set_data_pemesanan_pengiriman($id_pemesanan="")
	{
		$where = "pemesanan.id_pemesanan = $id_pemesanan";
		$select_pemesanan = "pemesanan.tipe_pengiriman, pemesanan.id_pb, pemesanan.id_usaha, pembeli.nama_pb, pembeli.latitude_pb, pembeli.longitude_pb, pembeli.alamat_pb, pembeli.kel_pb, pembeli.kec_pb, pembeli.kab_pb, usaha.nama_usaha, usaha.alamat_usaha, usaha.latitude, usaha.longitude, usaha.id_pj, penjual.nama_pj, penjual.foto_pj";
		$join[] = array('table' => "data_pembeli pembeli", 'on' => 'pemesanan.id_pb = pembeli.id_pb', 'join' => null);
		$join[] = array('table' => "data_usaha usaha", 'on' => 'pemesanan.id_usaha = usaha.id_usaha', 'join' => null);
		$join[]	= array('table' => "data_penjual penjual", 'on' => "penjual.id_pj = usaha.id_pj", 'join' => null);
    	$pemesanan = $this->Model_pemesanan->get_where($select_pemesanan, $where, $join);
    	// $data_pemesanan = $pemesanan->row();
    	return $pemesanan->row();
	}

	protected function set_data_kendaraan()
	{
		$data_kendaraan = $this->Model_kendaraan->get_detail_kendaraan($this->id_kendaraan)->row();
		return array('jenis' => $data_kendaraan->jenis_kendaraan, 'plat' => $data_kendaraan->plat_kendaraan);
	}

	protected function set_detail_pemesanan($id_pemesanan){
		$result = array();
		$detail_pemesanan = $this->Model_pemesanan->getDetailPemesanan($id_pemesanan);
		foreach ($detail_pemesanan->result() as $detail) {
			$result[] = array('nama_produk' => $detail->nama_produk,
			        		'berat' => $detail->jml_produk,
			        		'nama_variasi' => $detail->nama_variasi,
			        		'foto_produk' => base_url('foto_usaha/foto_produk') . $detail->foto_produk);
		}
		return $result;
	}

	protected function set_location_collection(){
		$data = $this->Model_pengiriman->get_detail_pengiriman_with_detail_pembeli($this->id_pengiriman);
		if($data->num_rows() > 0){
			return $data->result_array();
		}else{
			return array();
		}
	}
}