<?php
/**
 * 
 */
class Pemesanan extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
        $this->load->model("Model_pemesanan", "Pemesanan");
        $this->load->model("Model_pembayaran", "Pembayaran");
        $this->load->model("Model_produk", "Produk");
        $this->load->model("Model_penjual", "Usaha");
        $this->load->model("Model_pembeli", "Pembeli");
        $this->StatusPemesananBaru = "Baru";
        $this->load->library('encryption');
        $this->encryption->initialize(array('driver' => 'mcrypt'));
        date_default_timezone_set("Asia/Bangkok");
    }

    public function pesanan_user()
    {
      $id_akun = $this->input->post('id_akun');
      $data_pembeli = $this->Pembeli->detail_pembeli($id_akun);
      header("Content-type: application/json");
      echo json_encode($data_pembeli->row());
  }

  public function updatePemesanan()
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

public function simpanPemesanan()
{
    $result = array();
    $id_akun = $this->input->post('id_akun');
    $id_usaha = $this->input->post('id_usaha');
    $produk = $this->input->post('keranjang');
    $totalBiayaPengiriman = $this->input->post('totalBiayaPengiriman');
    $totalBiayaProduk = $this->input->post('totalBiayaProduk');
    $totalPembayaran = $totalBiayaPengiriman + $totalBiayaProduk;

    $tipePengiriman = $this->input->post('jpengiriman');
    $metodePembayaran = $this->input->post('jpembayaran');
    $tglPengiriman = $this->input->post('tglPengiriman');
        // $totalHargaProduk = 
    $tglPemesanan = date("Y/m/d H:i:s", strtotime("now"));
    $expiredDate = date("Y/m/d H:i:s", strtotime("+60 minutes"));        
    $statusHeader=201;
    $result = array();
    try{
            //TODO:INSERT DATA PEMESANAN, DETAIL PEMESANAN, PEMBAYARAN
            // Pemesanan
        $jarak = $this->input->post('jarak');
        $dataPemesanan = array('waktu_pemesanan' => $tglPemesanan,
            'tipe_pengiriman' => $tipePengiriman,
            'tgl_pengiriman' => $tglPengiriman,
            'biaya_kirim' => $totalBiayaPengiriman,
            'total_harga' => $totalPembayaran,
            'status_pemesanan' => $this->StatusPemesananBaru,
            'id_pb' => $id_akun,
            'id_usaha' => $id_usaha,
            'jarak' => $jarak);
        $isPemesanan = $this->Pemesanan->createPemesanan($dataPemesanan);
        if($isPemesanan){
                //detailPemesanan
            $dataPemesanan = $this->Pemesanan->getDataPemesananByIdUser($id_akun, 1, 'id_pemesanan', 'DESC');
            $id_pemesanan = $dataPemesanan->row()->id_pemesanan;
            $data = array();
            $totalProduk = count($produk);
            for($i=0; $i<$totalProduk; $i++){
                $arrayProduk = $produk[$i];
                $idVariasi = $arrayProduk['variasi'];
                $data[] = array('harga' => $arrayProduk['harga_produk'],
                    'jml_produk' => $arrayProduk['qty'],
                    'sub_total' => $arrayProduk['total_harga'],
                    'id_pemesanan' => $id_pemesanan,
                    'id_produk' => $idVariasi);
            }
            $isDetailPemesanan = $this->Pemesanan->createDetailPemesanan_batch($data);
            if($isDetailPemesanan){
                    //PEMBAYARAN
                $dataPembayaran = array('metode_pembayaran' => $metodePembayaran,
                    'expiredDate' => $expiredDate,
                    'id_pemesanan' => $id_pemesanan);
                $isPembayaran = $this->Pembayaran->createPembayaran($dataPembayaran);
                if($isPembayaran){
                    $result = array('responseMessage' => 'success', 'listPemesanan' => $produk, 'responseCode' => '00', 'id_pemesanan' => $id_pemesanan);
                }else{
                    $statusHeader = 401;
                    $result = array('responseMessage' => 'failed ', 'listPemesanan' => null, 'responseCode' => '03');
                }
            }else{
                $statusHeader = 409;
                $result = array('responseMessage' => 'failed ', 'listPemesanan' => null, 'responseCode' => '02');
            }
        }else{
            $statusHeader = 400;
            $result = array('responseMessage' => 'failed ', 'listPemesanan' => null, 'responseCode' => '01');
        }

    }catch(Exception $e){
        $errorMessage =  $e->getMessage();
        $result = array('responseMessage' => 'failed ' . $errorMessage, 'listPemesanan' => null, 'responseCode' => '01');
        $statusHeader = 500;
    }
        // header("Content-type: application/json");
		// echo json_encode($result, JSON_PRETTY_PRINT);
    $this->output
    ->set_status_header($statusHeader)
    ->set_content_type('application/json', 'utf-8')
    ->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

public function getHargaPesananById()
{
    $idPesanan = $_POST['idPesanan'];
    $data = $this->Pemesanan->getHargaPemesananByIdPemesanan($idPesanan);
    $result = $data->row_array();
    echo json_encode($result, JSON_PRETTY_PRINT);
}

public function StatusPemesanan()
{
    $id_pemesanan = $_POST['id_pemesanan'];
    $statusHeader=200;
    try{
        $data = $this->Pemesanan->getDetailPemesanan($id_pemesanan);
        if($data->num_rows() > 0){
            $result = array('responseMessage' => 'success', 'responseCode' => '00', 'data' => $data->result_array());
        }else{
            $statusHeader = 404;
            $result = array('responseMessage' => 'failed', 'responseCode' => '02', 'status' => null);
        }
    }catch(Exception $e){
        $statusHeader = 500;
        $result = array('responseMessage' => 'failed', 'responseCode' => '01', 'status' => null);
    }
    $this->output
    ->set_status_header($statusHeader)
    ->set_content_type('application/json', 'utf-8')
    ->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

public function getAllPemesananByAkun()
{
    $idAkun = $this->input->get('id_akun');
    $status = $this->input->get('status');
    $limit = null;
    $orderBy = "id_pemesanan";
    $typeOrder = "DESC";
    $resultArray = array();
    $result = array();
    $statusHeader = 200;
    $resultArray = array();
    try{
        $query = $this->Pemesanan->getDataPemesananByIdUserAndStatus($idAkun, $status,$limit, $orderBy, $typeOrder);
        if($query->num_rows() > 0){
            foreach($query->result() as $dataPemesanan){
                $id1 = str_replace("-","",$dataPemesanan->waktu_pemesanan);
                $id2 = str_replace(" ", "", $id1);
                $id3 = str_replace(":", "", $id2);
                $id4 = $id3 . $dataPemesanan->id_pemesanan;
                $dateNew = date_create($dataPemesanan->waktu_pemesanan);
                $dateNew2 = date_create($dataPemesanan->tgl_pengiriman);
                $waktuPemesanan = date_format($dateNew, 'd/m/Y H:i');
                $tglPengiriman = date_format($dateNew2, 'd/m/Y');
                $idPemesanan = $dataPemesanan->id_pemesanan;
                $JenisPengiriman = $dataPemesanan->tipe_pengiriman;
                $TotalHargaAll = $dataPemesanan->total_harga;
                $BiayaPengiriman = $dataPemesanan->biaya_kirim;

                $IdUsaha = $dataPemesanan->id_usaha;
                $IdPembeli = $dataPemesanan->id_pb;
                $jarak = $dataPemesanan->jarak;
                $DataUsaha = $this->Usaha->ambil_usaha_by_id($IdUsaha)->row();
                $DataPembeli = $this->Pembeli->detail_pembeli($IdPembeli)->row();
                $DataPembayaran = $this->Pemesanan->getDataPembayaranByIdPemesanan($idPemesanan)->row();
                $queryDetailPesanan = $this->Pemesanan->getDetailPemesanan($idPemesanan);
                    // echo $this->db->last_query();
                $DataDetailPesanan = $queryDetailPesanan->row();
                if(isset($DataDetailPesanan)){
                        // var_dump($DataDetailPesanan);
                    $namaProduk = $DataDetailPesanan->nama_produk;
                    $namaProduk = $namaProduk . ' ' . $DataDetailPesanan->nama_variasi;
                    $hargaProduk = $DataDetailPesanan->harga;
                    $totalProduk = $DataDetailPesanan->jml_produk;
                    $fotoProduk = $DataDetailPesanan->foto_produk;
                    $arrayToDisplay = array('namaProduk' => $namaProduk,
                        'hargaProduk' => $hargaProduk,
                        'totalProduk' => $totalProduk,
                        'fotoProduk' => $fotoProduk);
                    $DaftarProduk = $queryDetailPesanan->result_array();
                    $TotalHargaProduk = 0;
                    $TotalBeratProduk = 0;
                    $TotalProduk = 0;
                    $SubTotal = null;
                    $SubBerat = null;
                    $SubTotalProduk = null;
                    foreach($queryDetailPesanan->result() as $Products){
                        $SubTotal[] = $Products->sub_total;
                        $SubBerat[] = $Products->jml_produk;
                        $SubTotalProduk[] = $Products->jml_produk;
                    }
                    $TotalHargaProduk = array_sum($SubTotal);
                    $TotalBeratProduk = array_sum($SubBerat);
                    $TotalProduk = array_sum($SubTotalProduk);
                    $result[] = array('ID' => $id4,
                        'idPemesanan' => $idPemesanan,
                        'TotalHargaAll' => $TotalHargaAll,
                        'BiayaPengiriman' => $BiayaPengiriman,
                        'JenisPengiriman' => $JenisPengiriman,
                        'display' => $arrayToDisplay,
                        'AllPurchaseProduk' => $DaftarProduk,
                        'waktuPemesanan' => $waktuPemesanan,
                        'tglPengiriman' => $tglPengiriman,
                        'TotalHargaProduk' => $TotalHargaProduk,
                        'TotalBeratProduk' => $TotalBeratProduk,
                        'TotalProduk' => $TotalProduk,
                        'DataUsaha' => $DataUsaha,
                        'DataPembeli' => $DataPembeli,
                        'DataPembayaran' => $DataPembayaran,
                        'jarak' => $jarak));
                }
            }
            $resultArray = array('dataPesanan' => $result,
                'responseMessage' => 'success',
                'responseCode' => "00");
        }else{
            $statusHeader = 404;
            $resultArray = array('dataPesanan' => array(),
                'responseMessage' => 'success',
                'responseCode' => "00");
        }
    }catch(Exception $e){
        $statusHeader = 500;
        $resultArray = array('dataPesanan' => null,
            'responseMessage' => 'failed ' + $e->getMessage(),
            'responseCode' => "01");
    }
    $this->output
    ->set_status_header($statusHeader)
    ->set_content_type('Application/json')
    ->set_output(json_encode($resultArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

public function getDataPemesananByID()
{
    $id_pemesanan = $this->input->get('id_pemesanan');
    $limit = 1;
    $resultArray = array();
    $result = array();
    $statusHeader = 200;
    $resultArray = array();
    try{
        $query = $this->Pemesanan->getDataPemesananByID($id_pemesanan);
        if($query->num_rows() > 0){
            foreach($query->result() as $dataPemesanan){
                $id1 = str_replace("-","",$dataPemesanan->waktu_pemesanan);
                $id2 = str_replace(" ", "", $id1);
                $id3 = str_replace(":", "", $id2);
                $id4 = $id3 . $dataPemesanan->id_pemesanan;
                $dateNew = date_create($dataPemesanan->waktu_pemesanan);
                $dateNew2 = date_create($dataPemesanan->tgl_pengiriman);
                $waktuPemesanan = date_format($dateNew, 'd/m/Y H:i');
                $tglPengiriman = date_format($dateNew2, 'd/m/Y');
                $idPemesanan = $dataPemesanan->id_pemesanan;
                $JenisPengiriman = $dataPemesanan->tipe_pengiriman;
                $TotalHargaAll = $dataPemesanan->total_harga;
                $BiayaPengiriman = $dataPemesanan->biaya_kirim;

                $IdUsaha = $dataPemesanan->id_usaha;
                $IdPembeli = $dataPemesanan->id_pb;
                $DataUsaha = $this->Usaha->ambil_usaha_by_id($IdUsaha)->row();
                $DataPembeli = $this->Pembeli->detail_pembeli($IdPembeli)->row();
                $DataPembayaran = $this->Pemesanan->getDataPembayaranByIdPemesanan($idPemesanan)->row();
                $queryDetailPesanan = $this->Pemesanan->getDetailPemesanan($idPemesanan);
                $dateNew3 = date_create($DataPembayaran->waktu_pembayaran);
                $waktu_pembayaran_new = date_format($dateNew3, 'd/m/Y H:i');
                    // echo $this->db->last_query();
                $DataDetailPesanan = $queryDetailPesanan->row();
                if(isset($DataDetailPesanan)){
                        // var_dump($DataDetailPesanan);
                    $namaProduk = $DataDetailPesanan->nama_produk;
                    $namaProduk = $namaProduk . ' ' . $DataDetailPesanan->nama_variasi;
                    $hargaProduk = $DataDetailPesanan->harga;
                    $totalProduk = $DataDetailPesanan->jml_produk;
                    $fotoProduk = $DataDetailPesanan->foto_produk;
                    $arrayToDisplay = array('namaProduk' => $namaProduk,
                        'hargaProduk' => $hargaProduk,
                        'totalProduk' => $totalProduk,
                        'fotoProduk' => $fotoProduk);
                    $DaftarProduk = $queryDetailPesanan->result_array();
                    $TotalHargaProduk = 0;
                    $TotalBeratProduk = 0;
                    $TotalProduk = 0;
                    $SubTotal = null;
                    $SubBerat = null;
                    $SubTotalProduk = null;
                    foreach($queryDetailPesanan->result() as $Products){
                        $SubTotal[] = $Products->sub_total;
                        $SubBerat[] = $Products->jml_produk;
                        $SubTotalProduk[] = $Products->jml_produk;
                    }
                    $TotalHargaProduk = array_sum($SubTotal);
                    $TotalBeratProduk = array_sum($SubBerat);
                    $TotalProduk = array_sum($SubTotalProduk);
                    $result = array('ID' => $id4,
                        'idPemesanan' => $idPemesanan,
                        'TotalHargaAll' => $TotalHargaAll,
                        'BiayaPengiriman' => $BiayaPengiriman,
                        'JenisPengiriman' => $JenisPengiriman,
                        'display' => $arrayToDisplay,
                        'AllPurchaseProduk' => $DaftarProduk,
                        'waktuPemesanan' => $waktuPemesanan,
                        'tglPengiriman' => $tglPengiriman,
                        'TotalHargaProduk' => $TotalHargaProduk,
                        'TotalBeratProduk' => $TotalBeratProduk,
                        'TotalProduk' => $TotalProduk,
                        'DataUsaha' => $DataUsaha,
                        'DataPembeli' => $DataPembeli,
                        'DataPembayaran' => $DataPembayaran,
                        'waktu_pembayaran_new' => $waktu_pembayaran_new);
                }
            }
            $resultArray = array('dataPesanan' => $result,
                'responseMessage' => 'success',
                'responseCode' => "00");
        }else{
            $statusHeader = 404;
            $resultArray = array('dataPesanan' => array(),
                'responseMessage' => 'success',
                'responseCode' => "00");
        }
    }catch(Exception $e){
        $statusHeader = 500;
        $resultArray = array('dataPesanan' => null,
            'responseMessage' => 'failed ' + $e->getMessage(),
            'responseCode' => "01");
    }
    $this->output
    ->set_status_header($statusHeader)
    ->set_content_type('Application/json')
    ->set_output(json_encode($resultArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

public function getAllTransaksiPenjual()
{
    $idusaha = $this->input->get('id_usaha');
    $status = (!empty($this->input->get('status')) ? $this->input->get('status') : null);
    $tipePengiriman = (!empty($this->input->get('tipePengiriman')) ? $this->input->get('tipePengiriman') : null);
    $limit = null;
    $orderBy = "tgl_pengiriman ASC";
    if($status=="Siap Dikirim"){
        $orderBy .= ", tipe_pengiriman DESC";
    }
    $typeOrder = "DESC";
    $resultArray = array();
    $result = array();
    $statusHeader = 200;
    try{
        $where = "id_usaha = '$idusaha' ";
        $where .= ($status!==null) ? " AND status_pemesanan = '$status'" : "";
        $where .= ($tipePengiriman!==null) ? " tipe_pengiriman = '$tipePengiriman'" : "";
        if($status=="Terbayar"){
                $where .= " AND tgl_pengiriman != date(now())";}
        $query = $this->Pemesanan->getWhereDataPemesananByIdUsaha($where,$limit, $orderBy);
            // echo $this->db->last_query();
            // exit();
        if($query->num_rows() > 0){
            foreach($query->result() as $dataPemesanan){
                $id1 = str_replace("-","",$dataPemesanan->waktu_pemesanan);
                $id2 = str_replace(" ", "", $id1);
                $id3 = str_replace(":", "", $id2);
                $id4 = $id3 . $dataPemesanan->id_pemesanan;
                $dateNew = date_create($dataPemesanan->waktu_pemesanan);
                $dateNew2 = date_create($dataPemesanan->tgl_pengiriman);
                $waktuPemesanan = date_format($dateNew, 'd/m/Y H:i');
                $tglPengiriman = date_format($dateNew2, 'd/m/Y');
                $idPemesanan = $dataPemesanan->id_pemesanan;
                $JenisPengiriman = $dataPemesanan->tipe_pengiriman;
                $TotalHargaAll = $dataPemesanan->total_harga;
                $BiayaPengiriman = $dataPemesanan->biaya_kirim;

                $IdUsaha = $dataPemesanan->id_usaha;
                $IdPembeli = $dataPemesanan->id_pb;
                $statusPengiriman = $dataPemesanan->status_pemesanan;
                $DataUsaha = $this->Usaha->ambil_usaha_by_id($IdUsaha)->row();
                $DataPembeli = $this->Pembeli->detail_pembeli($IdPembeli)->row();
                $DataPembayaran = $this->Pemesanan->getDataPembayaranByIdPemesanan($idPemesanan);
                if ($DataPembayaran->num_rows() > 0) {
                    $DataPembayaran = $DataPembayaran->row_array();
                    $datePembayaran = date_create($DataPembayaran['waktu_pembayaran']);
                    $newDatePembayaran = date_format($datePembayaran, 'd/m/y H:i');
                    $DataPembayaran['newDatePembayaran'] = $newDatePembayaran;
                }else{
                    $DataPembayaran = array();
                }

                $queryDetailPesanan = $this->Pemesanan->getDetailPemesanan($idPemesanan);
                    // echo $this->db->last_query();
                $DataDetailPesanan = $queryDetailPesanan->row();
                if(isset($DataDetailPesanan)){
                        // var_dump($DataDetailPesanan);
                    $namaProduk = $DataDetailPesanan->nama_produk;
                    $namaProduk = $namaProduk . ' ' . $DataDetailPesanan->nama_variasi;
                    $hargaProduk = $DataDetailPesanan->harga;
                    $totalProduk = $DataDetailPesanan->jml_produk;
                    $fotoProduk = $DataDetailPesanan->foto_produk;
                    $arrayToDisplay = array('namaProduk' => $namaProduk,
                        'hargaProduk' => $hargaProduk,
                        'totalProduk' => $totalProduk,
                        'fotoProduk' => $fotoProduk);
                    $DaftarProduk = $queryDetailPesanan->result_array();
                    $TotalHargaProduk = 0;
                    $TotalBeratProduk = 0;
                    $TotalProduk = 0;
                    $SubTotal = null;
                    $SubBerat = null;
                    $SubTotalProduk = null;
                    foreach($queryDetailPesanan->result() as $Products){
                        $SubTotal[] = $Products->sub_total;
                        $SubBerat[] = $Products->berat_produk;
                        $SubTotalProduk[] = $Products->jml_produk;
                    }
                    $TotalHargaProduk = array_sum($SubTotal);
                    $TotalBeratProduk = array_sum($SubBerat);
                    $TotalProduk = array_sum($SubTotalProduk);
                    $result[] = array('ID' => $id4,
                        'idPemesanan' => $idPemesanan,
                        'TotalHargaAll' => $TotalHargaAll,
                        'BiayaPengiriman' => $BiayaPengiriman,
                        'JenisPengiriman' => $JenisPengiriman,
                        'display' => $arrayToDisplay,
                        'AllPurchaseProduk' => $DaftarProduk,
                        'waktuPemesanan' => $waktuPemesanan,
                        'tglPengiriman' => $tglPengiriman,
                        'TotalHargaProduk' => $TotalHargaProduk,
                        'TotalBeratProduk' => $TotalBeratProduk,
                        'TotalProduk' => $TotalProduk,
                        'DataUsaha' => $DataUsaha,
                        'DataPembeli' => $DataPembeli,
                        'DataPembayaran' => $DataPembayaran,
                        'statusPengiriman' => $statusPengiriman);
                }
            }
            $resultArray = array('dataPesanan' => $result,
                'responseMessage' => 'success',
                'responseCode' => "00");
        }else{
            $statusHeader = 404;
            $resultArray = array('dataPesanan' => array(),
                'responseMessage' => 'failed',
                'responseCode' => "01");
        }
    }catch(Exception $e){
        $resultArray = array('dataPesanan' => null,
            'responseMessage' => 'failed ' + $e->getMessage(),
            'responseCode' => "99");
        $statusHeader = 500;
    }

    $this->output
    ->set_status_header($statusHeader)
    ->set_content_type('application/json', 'utf-8')
    ->set_output(json_encode($resultArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

public function get_transaksi_pengiriman()
{
    $idusaha = $this->input->get('id_usaha');
    $status = (!empty($this->input->get('status')) ? $this->input->get('status') : null);
    $tipePengiriman = (!empty($this->input->get('tipePengiriman')) ? $this->input->get('tipePengiriman') : null);
    $where = "pemesanan.status_pemesanan = '$status' AND pemesanan.id_usaha = '$idusaha' AND pengiriman.status != 'terkirim' ";
    $order = "pengiriman.urutan ASC ";
    $resultArray = array();
    $result = array();
    $statusHeader = 200;
    try {
        $query = $this->Pemesanan->get_pemesanan_pengiriman($where, $order);
            // echo $this->db->last_query();
            // exit();
        if($query->num_rows() > 0){
            foreach($query->result() as $dataPemesanan){
                $id1 = str_replace("-","",$dataPemesanan->waktu_pemesanan);
                $id2 = str_replace(" ", "", $id1);
                $id3 = str_replace(":", "", $id2);
                $id4 = $id3 . $dataPemesanan->id_pemesanan;
                $dateNew = date_create($dataPemesanan->waktu_pemesanan);
                $dateNew2 = date_create($dataPemesanan->tgl_pengiriman);
                $waktuPemesanan = date_format($dateNew, 'd/m/Y H:i');
                $tglPengiriman = date_format($dateNew2, 'd/m/Y');
                $idPemesanan = $dataPemesanan->id_pemesanan;
                $JenisPengiriman = $dataPemesanan->tipe_pengiriman;
                $TotalHargaAll = $dataPemesanan->total_harga;
                $BiayaPengiriman = $dataPemesanan->biaya_kirim;

                $IdUsaha = $dataPemesanan->id_usaha;
                $IdPembeli = $dataPemesanan->id_pb;
                $statusPengiriman = $dataPemesanan->status_pemesanan;
                $DataUsaha = $this->Usaha->ambil_usaha_by_id($IdUsaha)->row();
                $DataPembeli = $this->Pembeli->detail_pembeli($IdPembeli)->row();
                $DataPembayaran = $this->Pemesanan->getDataPembayaranByIdPemesanan($idPemesanan);
                if ($DataPembayaran->num_rows() > 0) {
                    $DataPembayaran = $DataPembayaran->row_array();
                    $datePembayaran = date_create($DataPembayaran['waktu_pembayaran']);
                    $newDatePembayaran = date_format($datePembayaran, 'd/m/y H:i');
                    $DataPembayaran['newDatePembayaran'] = $newDatePembayaran;
                }else{
                    $DataPembayaran = array();
                }
                $data_pengiriman = array('urutan' => $dataPemesanan->urutan, 'status_pengiriman_pesanan' => $dataPemesanan->status, 'penerima' => $dataPemesanan->penerima);
                $queryDetailPesanan = $this->Pemesanan->getDetailPemesanan($idPemesanan);
                    // echo $this->db->last_query();
                $DataDetailPesanan = $queryDetailPesanan->row();
                if(isset($DataDetailPesanan)){
                        // var_dump($DataDetailPesanan);
                    $namaProduk = $DataDetailPesanan->nama_produk;
                    $namaProduk = $namaProduk . ' ' . $DataDetailPesanan->nama_variasi;
                    $hargaProduk = $DataDetailPesanan->harga;
                    $totalProduk = $DataDetailPesanan->jml_produk;
                    $fotoProduk = $DataDetailPesanan->foto_produk;
                    $arrayToDisplay = array('namaProduk' => $namaProduk,
                        'hargaProduk' => $hargaProduk,
                        'totalProduk' => $totalProduk,
                        'fotoProduk' => $fotoProduk);
                    $DaftarProduk = $queryDetailPesanan->result_array();
                    $TotalHargaProduk = 0;
                    $TotalBeratProduk = 0;
                    $TotalProduk = 0;
                    $SubTotal = null;
                    $SubBerat = null;
                    $SubTotalProduk = null;
                    foreach($queryDetailPesanan->result() as $Products){
                        $SubTotal[] = $Products->sub_total;
                        $SubBerat[] = $Products->berat_produk;
                        $SubTotalProduk[] = $Products->jml_produk;
                    }
                    $TotalHargaProduk = array_sum($SubTotal);
                    $TotalBeratProduk = array_sum($SubBerat);
                    $TotalProduk = array_sum($SubTotalProduk);
                    $result[] = array('ID' => $id4,
                        'idPemesanan' => $idPemesanan,
                        'TotalHargaAll' => $TotalHargaAll,
                        'BiayaPengiriman' => $BiayaPengiriman,
                        'JenisPengiriman' => $JenisPengiriman,
                        'display' => $arrayToDisplay,
                        'AllPurchaseProduk' => $DaftarProduk,
                        'waktuPemesanan' => $waktuPemesanan,
                        'tglPengiriman' => $tglPengiriman,
                        'TotalHargaProduk' => $TotalHargaProduk,
                        'TotalBeratProduk' => $TotalBeratProduk,
                        'TotalProduk' => $TotalProduk,
                        'DataUsaha' => $DataUsaha,
                        'DataPembeli' => $DataPembeli,
                        'DataPembayaran' => $DataPembayaran,
                        'statusPengiriman' => $statusPengiriman,
                        'data_pengiriman' => $data_pengiriman);
                }
            }
            $resultArray = array('dataPesanan' => $result,
                'responseMessage' => 'success',
                'responseCode' => "00");
        }else{
            $statusHeader = 404;
            $resultArray = array('dataPesanan' => array(),
                'responseMessage' => 'failed',
                'responseCode' => "01");
        }
    } catch (Exception $e) {
        $resultArray = array('dataPesanan' => null,
            'responseMessage' => 'failed ' + $e->getMessage(),
            'responseCode' => "99");
        $statusHeader = 500;
    }
    $this->output
    ->set_status_header($statusHeader)
    ->set_content_type('application/json', 'utf-8')
    ->set_output(json_encode($resultArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

public function get_pesanan_siap_kirim()
{
    $id_usaha = $this->input->get("id_usaha");
    $response = array();
    $status_header = 500;
    try {
        $SELECT_PEMESANAN = "pemesanan.id_pemesanan, pemesanan.tipe_pengiriman, pembeli.nama_pb, pembeli.alamat_pb, pemesanan.waktu_pemesanan, pemesanan.tipe_pengiriman, pemesanan.total_harga";
        $SET_WHERE_LIST_PEMESANAN = "pemesanan.id_usaha = '$id_usaha' 
        AND pemesanan.status_pemesanan = 'Siap Dikirim' 
        AND pembayaran.verifikasi = '1'";
        $JOIN[] = array('table' => 'data_pembayaran pembayaran', 'on' => 'pembayaran.id_pemesanan = pemesanan.id_pemesanan', 'join'=>'');
        $JOIN[] = array('table' => 'data_pembeli pembeli', 'on' => 'pembeli.id_pb = pemesanan.id_pb', 'join' => '');
        $order = "pemesanan.tipe_pengiriman DESC";
        $data_pemesanan = $this->Pemesanan->get_where($SELECT_PEMESANAN, $SET_WHERE_LIST_PEMESANAN, $JOIN, NULL, $order);
        // echo $this->db->last_query();
        if($data_pemesanan->num_rows() > 0){
            $status_header=200;
            foreach ($data_pemesanan->result() as $key) {
                $id1 = str_replace("-","",$key->waktu_pemesanan);
                $id2 = str_replace(" ", "", $id1);
                $id3 = str_replace(":", "", $id2);
                $id4 = $id3 . $key->id_pemesanan;
                $response['data_pemesanan'][] = array('id_pemesanan' => $key->id_pemesanan, 'no_pesanan' => $id4, 'tipe_pengiriman' => $key->tipe_pengiriman, 'alamat'=>$key->alamat_pb, 'nama_pembeli' => $key->nama_pb, 'date' => date('d - m - Y'), 'detail_pemesanan' => $this->GET_DETAIL_PEMESANAN_WITH_ID($key->id_pemesanan)->result_array());
            }
            $response['status'] = 'sukses';
        }else{
            $status_header = 404;
            $response['status'] = 'gagal';
        }
    } catch (Exception $e) {
        $response['status'] = 'gagal';
    }
        // echo json_encode($response);
    $this->output
    ->set_status_header($status_header)
    ->set_content_type('application/json', 'utf-8')
    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}


public function get_transaksi_today()
{
    $id_usaha = $this->input->get("id_usaha");
    $response = array();
    $status_header = 500;
    try {
        $where = "id_usaha = '$id_usaha'" . 
                " AND status_pemesanan = 'Terbayar' AND tgl_pengiriman = date(now())";
        $limit = null;
        $orderBy = "id_pemesanan ASC";
        $query = $this->Pemesanan->getWhereDataPemesananByIdUsaha($where, $limit, $orderBy);
            // echo $this->db->last_query();
        if($query->num_rows() > 0){
            // $dataPemesanan = $query->row();
            $result = array();
            foreach ($query->result() as $dataPemesanan) {
                $id1 = str_replace("-","",$dataPemesanan->waktu_pemesanan);
                $id2 = str_replace(" ", "", $id1);
                $id3 = str_replace(":", "", $id2);
                $id4 = $id3 . $dataPemesanan->id_pemesanan;
                $dateNew = date_create($dataPemesanan->waktu_pemesanan);
                $dateNew2 = date_create($dataPemesanan->tgl_pengiriman);
                $waktuPemesanan = date_format($dateNew, 'd/m/Y H:i');
                $tglPengiriman = date_format($dateNew2, 'd/m/Y');
                    // $idPemesanan = $dataPemesanan->id_pemesanan;
                $JenisPengiriman = $dataPemesanan->tipe_pengiriman;
                $TotalHargaAll = $dataPemesanan->total_harga;
                $BiayaPengiriman = $dataPemesanan->biaya_kirim;

                $IdUsaha = $dataPemesanan->id_usaha;
                $IdPembeli = $dataPemesanan->id_pb;
                $statusPengiriman = $dataPemesanan->status_pemesanan;
                $DataUsaha = $this->Usaha->ambil_usaha_by_id($IdUsaha)->row();
                $DataPembeli = $this->Pembeli->detail_pembeli($IdPembeli)->row();
                $DataPembayaran = $this->Pemesanan->getDataPembayaranByIdPemesanan($dataPemesanan->id_pemesanan);
                if ($DataPembayaran->num_rows() > 0) {
                    $DataPembayaran = $DataPembayaran->row_array();
                    $datePembayaran = date_create($DataPembayaran['waktu_pembayaran']);
                    $newDatePembayaran = date_format($datePembayaran, 'd/m/y H:i');
                    $DataPembayaran['newDatePembayaran'] = $newDatePembayaran;
                }else{
                    $DataPembayaran = array();
                }

                $queryDetailPesanan = $this->GET_DETAIL_PEMESANAN_WITH_ID($dataPemesanan->id_pemesanan);
                    // echo $this->db->last_query();
                $DataDetailPesanan = $queryDetailPesanan->row();
                if(isset($DataDetailPesanan)){
                        // var_dump($DataDetailPesanan);
                    $namaProduk = $DataDetailPesanan->nama_produk;
                    $namaProduk = $namaProduk . ' ' . $DataDetailPesanan->nama_variasi;
                    $hargaProduk = $DataDetailPesanan->harga;
                    $totalProduk = $DataDetailPesanan->jml_produk;
                    $fotoProduk = $DataDetailPesanan->foto_produk;
                    $arrayToDisplay = array('namaProduk' => $namaProduk,
                        'hargaProduk' => $hargaProduk,
                        'totalProduk' => $totalProduk,
                        'fotoProduk' => $fotoProduk);
                    $DaftarProduk = $queryDetailPesanan->result_array();
                    $TotalHargaProduk = 0;
                    $TotalBeratProduk = 0;
                    $TotalProduk = 0;
                    $SubTotal = null;
                    $SubBerat = null;
                    $SubTotalProduk = null;
                    foreach($queryDetailPesanan->result() as $Products){
                        $SubTotal[] = $Products->sub_total;
                        $SubBerat[] = $Products->berat_produk;
                        $SubTotalProduk[] = $Products->jml_produk;
                    }
                    $TotalHargaProduk = array_sum($SubTotal);
                    $TotalBeratProduk = array_sum($SubBerat);
                    $TotalProduk = array_sum($SubTotalProduk);
                    $result[] = array('ID' => $id4,
                        'idPemesanan' => $dataPemesanan->id_pemesanan,
                        'TotalHargaAll' => $TotalHargaAll,
                        'BiayaPengiriman' => $BiayaPengiriman,
                        'JenisPengiriman' => $JenisPengiriman,
                        'display' => $arrayToDisplay,
                        'AllPurchaseProduk' => $DaftarProduk,
                        'waktuPemesanan' => $waktuPemesanan,
                        'tglPengiriman' => $tglPengiriman,
                        'TotalHargaProduk' => $TotalHargaProduk,
                        'TotalBeratProduk' => $TotalBeratProduk,
                        'TotalProduk' => $TotalProduk,
                        'DataUsaha' => $DataUsaha,
                        'DataPembeli' => $DataPembeli,
                        'DataPembayaran' => $DataPembayaran,
                        'statusPengiriman' => $statusPengiriman);
                }
            }
                // foreach($query->result() as $dataPemesanan){
            
                // }
            $resultArray = array('dataPesanan' => $result,
                'responseMessage' => 'success',
                'responseCode' => "00");
            $response = $resultArray;
            $status_header = 200;
        }else{
            $statusHeader = 404;
            $resultArray = array('dataPesanan' => array(),
                'responseMessage' => 'failed. not found',
                'responseCode' => "01");
        }
    } catch (Exception $e) {
        $response['status'] = 'gagal';
        $response['responseMessage'] = $e->getMessage();
    }
        // echo json_encode($response);
    $this->output
    ->set_status_header($status_header)
    ->set_content_type('application/json', 'utf-8')
    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

private function GET_DETAIL_PEMESANAN_WITH_ID($ID_PESANAN)
{
    $response = array();
    $SELECT_DETAIL = "detail_pemesanan.`id_dp`, detail_pemesanan.`harga`, detail_pemesanan.`jml_produk`, detail_pemesanan.`sub_total`, detail_pemesanan.`id_pemesanan`, detail_pemesanan.`id_produk`, detail_pemesanan.`berat_akhir`, produk.nama_produk, variasi.nama_variasi, produk.foto_produk, produk.berat_produk";
    $SET_WHERE_LIST_DETAIL = "detail_pemesanan.id_pemesanan = '$ID_PESANAN'";
    $JOIN[] = array('table' => 'data_variasi_produk variasi_produk', 'on' => 'variasi_produk.id_variasiproduk = detail_pemesanan.id_produk', 'join' => NULL);
    $JOIN[] = array('table' => 'data_produk produk', 'on' => 'produk.id_produk = variasi_produk.id_produk', 'join' => NULL);
    $JOIN[] = array('table' => 'data_variasi variasi', 'on' => 'variasi.id_variasi = variasi_produk.id_variasi', 'join' => NULL);
    $order = "detail_pemesanan.id_dp ASC";
    $data_detail_pesanan = $this->Pemesanan->get_detail_where($SELECT_DETAIL, $SET_WHERE_LIST_DETAIL, $JOIN, NULL, $order);
    if($data_detail_pesanan->num_rows() > 0){
        $response = $data_detail_pesanan;
    }
    return $response;
}

public function get_produk_to_varify(){
    $ID_PESANAN = $this->input->get("id_pemesanan");
    $RESULT = array();
    $JOIN = array();
    $response = array();
    $status_header = 500;
    try {
        $status_header = 200;
        $response['detail_pemesanan'] = $this->GET_DETAIL_PEMESANAN_WITH_ID($ID_PESANAN)->result_array();
        $response['status'] = 'sukses';
    } catch (Exception $e) {
        $response['detail_pemesanan'] = array();
        $response['status'] = 'gagal ' . $e->getMessage();
    }
    $this->output
    ->set_status_header($status_header)
    ->set_content_type('application/json', 'utf-8')
    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

public function simpan_verifikasi_berat_produk()
{
    $id_pemesanan = $this->input->post('id_pemesanan');
    $berat = $this->input->post('berat');
        // var_dump($this->input->post());
        // $data_detail_update = array();
    $response = array();
    $status_header = 500;
    try {
        $detail_pemesanan = $this->GET_DETAIL_PEMESANAN_WITH_ID($id_pemesanan);
        $affected_rows = 0;
        foreach ($detail_pemesanan->result() as $key) {
            $data_detail_update = array('berat_akhir'=> $berat[$affected_rows]);
            $update_detail_pesanan = $this->Pemesanan->update_detail_pesanan($key->id_dp, $data_detail_update);
            if($update_detail_pesanan){
                $affected_rows++;
            }
        }
        if($affected_rows>0){
            $pesanan = $this->GET_PESANAN($id_pemesanan);
            $data_pesanan = $pesanan;
            $array_update_pesanan = array();
            if($data_pesanan['tipe_pengiriman']=="Ambil di Toko"){
                $array_update_pesanan = array('status_pemesanan' => 'Siap Diambil');
            }else{
                $array_update_pesanan = array('status_pemesanan' => 'Siap Dikirim');
            }
            $where_update = "id_pemesanan = '$id_pemesanan'";
            $do_update_pemesanan = $this->Pemesanan->updatePemesanan($array_update_pesanan, $where_update);
            if($do_update_pemesanan){
                $status_header = 200;
                $response['status'] = 'sukses';
                $response['message'] = 'Berhasil Ubah Berat Akhir';
            }
        }else{
            $status_header = 400;
            $response['status'] = 'gagal';
            $response['message'] = 'Gagal Ubah Berat Akhir';
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = 'Gagal Ubah Berat Akhir Karena Kesalahan Server ' . $e->getMessage();
    }
    $this->output
    ->set_status_header($status_header)
    ->set_content_type('application/json', 'utf-8')
    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

public function getTransaksiByIdPemesanan()
{
    $idPemesanan = $this->input->get('idPemesanan');
    $id_usaha = $this->input->get('id_usaha');
    $type = $this->input->get('type');
    $status = (!empty($this->input->get('status')) ? $this->input->get('status') : null);
    $tipePengiriman = $type;

    $limit = null;
    $orderBy = "id_pemesanan DESC";
    $resultArray = array();
    $result = array();
    $statusHeader = 200;
    try{
        $where = " id_pemesanan = '$idPemesanan'";
        $where .= ($id_usaha) ? " AND id_usaha = '$id_usaha'" : "";
        $where .= ($type) ? " AND tipe_pengiriman = '$type'" : "";
        $where .= ($status!==null) ? " AND status_pemesanan = '$status'" : "";
        // $where .= " AND (tgl_pengiriman != date(now()) AND status_pembayaran!='Terbayar')";

        $query = $this->Pemesanan->getWhereDataPemesananByIdUsaha($where, $limit, $orderBy);
            // echo $this->db->last_query();
            // exit();
        if($query->num_rows() > 0){
            $dataPemesanan = $query->row();
                // foreach($query->result() as $dataPemesanan){
            $id1 = str_replace("-","",$dataPemesanan->waktu_pemesanan);
            $id2 = str_replace(" ", "", $id1);
            $id3 = str_replace(":", "", $id2);
            $id4 = $id3 . $dataPemesanan->id_pemesanan;
            $dateNew = date_create($dataPemesanan->waktu_pemesanan);
            $dateNew2 = date_create($dataPemesanan->tgl_pengiriman);
            $waktuPemesanan = date_format($dateNew, 'd/m/Y H:i');
            $tglPengiriman = date_format($dateNew2, 'd/m/Y');
                // $idPemesanan = $dataPemesanan->id_pemesanan;
            $JenisPengiriman = $dataPemesanan->tipe_pengiriman;
            $TotalHargaAll = $dataPemesanan->total_harga;
            $BiayaPengiriman = $dataPemesanan->biaya_kirim;

            $IdUsaha = $dataPemesanan->id_usaha;
            $IdPembeli = $dataPemesanan->id_pb;
            $statusPengiriman = $dataPemesanan->status_pemesanan;
            $jarak = $dataPemesanan->jarak;
            $DataUsaha = $this->Usaha->ambil_usaha_by_id($IdUsaha)->row();
            $DataPembeli = $this->Pembeli->detail_pembeli($IdPembeli)->row();
            $DataPembayaran = $this->Pemesanan->getDataPembayaranByIdPemesanan($idPemesanan);
            if ($DataPembayaran->num_rows() > 0) {
                $DataPembayaran = $DataPembayaran->row_array();
                $datePembayaran = date_create($DataPembayaran['waktu_pembayaran']);
                $newDatePembayaran = date_format($datePembayaran, 'd/m/Y H:i');
                $DataPembayaran['newDatePembayaran'] = $newDatePembayaran;
            }else{
                $DataPembayaran = array();
            }

            $queryDetailPesanan = $this->Pemesanan->getDetailPemesanan($idPemesanan);
                // echo $this->db->last_query();
            $DataDetailPesanan = $queryDetailPesanan->row();
            if(isset($DataDetailPesanan)){
                    // var_dump($DataDetailPesanan);
                $namaProduk = $DataDetailPesanan->nama_produk;
                $namaProduk = $namaProduk . ' ' . $DataDetailPesanan->nama_variasi;
                $hargaProduk = $DataDetailPesanan->harga;
                $totalProduk = $DataDetailPesanan->jml_produk;
                $fotoProduk = $DataDetailPesanan->foto_produk;
                $arrayToDisplay = array('namaProduk' => $namaProduk,
                    'hargaProduk' => $hargaProduk,
                    'totalProduk' => $totalProduk,
                    'fotoProduk' => $fotoProduk);
                $DaftarProduk = $queryDetailPesanan->result_array();
                $TotalHargaProduk = 0;
                $TotalBeratProduk = 0;
                $TotalProduk = 0;
                $SubTotal = null;
                $SubBerat = null;
                $SubTotalProduk = null;
                foreach($queryDetailPesanan->result() as $Products){
                    $SubTotal[] = $Products->sub_total;
                    $SubBerat[] = $Products->berat_produk;
                    $SubTotalProduk[] = $Products->jml_produk;
                }
                $TotalHargaProduk = array_sum($SubTotal);
                $TotalBeratProduk = array_sum($SubBerat);
                $TotalProduk = array_sum($SubTotalProduk);
                $result = array('ID' => $id4,
                    'idPemesanan' => $idPemesanan,
                    'TotalHargaAll' => $TotalHargaAll,
                    'BiayaPengiriman' => $BiayaPengiriman,
                    'JenisPengiriman' => $JenisPengiriman,
                    'display' => $arrayToDisplay,
                    'AllPurchaseProduk' => $DaftarProduk,
                    'waktuPemesanan' => $waktuPemesanan,
                    'tglPengiriman' => $tglPengiriman,
                    'TotalHargaProduk' => $TotalHargaProduk,
                    'TotalBeratProduk' => $TotalBeratProduk,
                    'TotalProduk' => $TotalProduk,
                    'DataUsaha' => $DataUsaha,
                    'DataPembeli' => $DataPembeli,
                    'DataPembayaran' => $DataPembayaran,
                    'statusPengiriman' => $statusPengiriman,
                    'jarak' => $jarak);
            }
                // }
            $resultArray = array('dataPesanan' => $result,
                'responseMessage' => 'success',
                'responseCode' => "00");
        }else{
            $statusHeader = 404;
            $resultArray = array('dataPesanan' => array(),
                'responseMessage' => 'success',
                'responseCode' => "00");
        }
    }catch(Exception $e){
        $resultArray = array('dataPesanan' => null,
            'responseMessage' => 'failed ' + $e->getMessage(),
            'responseCode' => "01");
        $statusHeader = 500;
    }

    $this->output
    ->set_status_header($statusHeader)
    ->set_content_type('application/json', 'utf-8')
    ->set_output(json_encode($resultArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

public function PemesananSelesai()
{
    $id = $this->input->post('id');
    $data = array('status_pemesanan' => 'Terkirim');
    try {
        $update = $this->Pemesanan->updatePemesanan($data, array('id_pemesanan' => $id));
        $updateAffected  = $this->db->affected_rows();
        if($updateAffected > 0){
            $response = array('id' => $id, 'message' => 'success', 'statusCode' => '00');
            $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }else{
            $response = array('message' => 'failed. Pesanan Tidak Ada', 'statusCode' => '01');
            $this->output
            ->set_status_header(404)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    } catch (Exception $e) {
        $response = array('message' => 'failed.' . $e->getMessage(), 'statusCode' => '99');
        $this->output
        ->set_status_header(500)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }


}

public function HapusPemesananByIdPemesanan()
{
    $idPemesanan = $this->input->post('idPesanan');
    $result = array();
    $statusHeader = 200;
    try {
        $DeletePembayaran = $this->Pemesanan->DeletePembayaran("id_pemesanan =" . $idPemesanan);
            // echo $this->db->last_query();
        if($DeletePembayaran){
            $DeleteDetailPemesanan = $this->Pemesanan->DeleteDetailPemesanan("id_pemesanan = " . $idPemesanan);
                // echo $this->db->last_query();
            if($DeleteDetailPemesanan){
                $DeletePemesanan = $this->Pemesanan->DeletePemesanan("id_pemesanan =", $idPemesanan);
                    // echo $this->db->last_query();
                if($DeletePemesanan){
                    $result = array('responseMessage' => "success", "responseCode" => "00");
                    return $this->output
                    ->set_status_header($statusHeader)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                }else{
                    $statusHeader = 400;
                    $result = array('responseMessage' => "failed", "responseCode" => "01");
                    return $this->output
                    ->set_status_header($statusHeader)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                }
            }else{
                $statusHeader = 400;
                $result = array('responseMessage' => "failed", "responseCode" => "02");
                return $this->output
                ->set_status_header($statusHeader)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }
        }else{
            $statusHeader = 404;
            $result = array('responseMessage' => "failed", "responseCode" => "03");
            return $this->output
            ->set_status_header($statusHeader)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    } catch (Exception $e) {
        return $this->output
        ->set_status_header(500)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode(array("responseMessage"=>"Error"), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }


        // echo json_encode($result, JSON_PRETTY_PRINT);

}

public function verifikasiPembayaranByPenjual()
{
    $id_pemesanan = $this->input->get("idPemesanan");
    $id_pembayaran = $this->input->get("id_pembayaran");
    $data_update = array('verifikasi'=>"1");
        // echo "Masuk";
        // var_dump($this->input->get());
    $array = array();
    $status_header = 500;
    try {
        $get_data = $this->db->get_where("data_pembayaran", "id_pemesanan = $id_pemesanan", 1);
        if($get_data->num_rows() > 0){
            $where = "id_pembayaran = $id_pembayaran";
            $update = $this->db->update("data_pembayaran", $data_update, $where);
            $affected_rows = $this->db->affected_rows();
            if($update){
                $where2 = "id_pemesanan = '$id_pemesanan'";
                $data_update2 = array("status_pemesanan"=>"Terbayar");
                $update_pemesanan = $this->Pemesanan->updatePemesanan($data_update2, $where2);
                if($update_pemesanan > 0){
                    $array = array('status' => 'success', 'message' => 'Verifikasi Berhasil');
                    $status_header = 200;

                }else{
                    $array = array('status' => 'success', 'message' => 'Gagal Ter Verifikasi');
                    $status_header = 400;
                }
            }  else{
                $array = array('status' => 'failed', 'message' => 'Gagal Ter Verifikasi');
                $status_header = 400;
            }  
        }else{
            $array = array('status' => 'failed', 'message' => 'Data Pesanan Belum Pernah Ada');
            $status_header = 404;
        }
    } catch (Exception $e) {
     $array = array('status' => 'failed', 'message' => 'Data Gagal Terbaca Dengan ' . $e->getMessage());
     $status_header = 500;
 }
 $this->output
 ->set_status_header($status_header)
 ->set_content_type('application/json', 'utf-8')
 ->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

public function getDetailPemesanan_HTML()
{
    $idPemesanan = $this->input->post('idPemesanan');
    $statusPemesanan = $this->input->post('statusPemesanan');
    $JenisPengiriman = $this->input->post('JenisPengiriman');
    $JenisPembayaran = $this->input->post('JenisPembayaran');
    $DataDetailPesanan = $this->Pemesanan->getDetailPemesanan($idPemesanan);
    $DataPemesanan = $this->Pemesanan->getDataPemesananByID($idPemesanan);
    $DataPembayaran = $this->Pemesanan->getDetailDataPembayaranByIdPemesanan($idPemesanan);
        // echo $this->db->last_query();
    $id1 = str_replace("-","",$DataPemesanan->row()->waktu_pemesanan);
    $id2 = str_replace(" ", "", $id1);
    $id3 = str_replace(":", "", $id2);
    $id4 = $id3 . $DataPemesanan->row()->id_pemesanan;
    $dateNew = date_create($DataPemesanan->row()->waktu_pemesanan);
    $dateNew2 = date_create($DataPemesanan->row()->tgl_pengiriman);
    $waktuPemesanan = date_format($dateNew, 'd/m/Y H:i');
    $tglPengiriman = date_format($dateNew2, 'd/m/Y');
    $DataPengiriman = $this->Pemesanan->getDetailPengirimanWithKurirKendaraan($idPemesanan)->row();

        // echo $this->db->last_query();
    $dataView = array('DataDetailPesanan' => $DataDetailPesanan, 
        'DataPemesanan' => $DataPemesanan, 
        'DataPembayaran' => $DataPembayaran, 
        'waktuPemesanan' => $waktuPemesanan, 
        'tglPengiriman' => $tglPengiriman, 
        'noPesanan' => $id4,
        'DataPengiriman' => $DataPengiriman);
    $LokasiHalaman = '';
    if($statusPemesanan=="Baru"){
        $LokasiHalaman = "detail-pesanan-baru-all";
    }else if($statusPemesanan=="Terbayar"){
        $LokasiHalaman = "detail-pesanan-terbayar-all";
    }else if($statusPemesanan == "Terkirim"){
      $LokasiHalaman = "detail-pesanan-terkirim-all";
  }
  $dataView['statusPemesanan'] = $statusPemesanan;
  $dataView['JenisPengiriman'] = $JenisPengiriman;
  $dataView['JenisPembayaran'] = $JenisPembayaran;
        //   var_dump($dataView);
  $this->load->view('pembeli/pesanan-saya/'.$LokasiHalaman, $dataView);
}

public function getStrukImage()
{
    $idPemesanan = $this->input->get('idPemesanan');
    $data = $this->db->get_where("data_pembayaran", array('id_pemesanan' => $idPemesanan))->row_array();
    $data['response'] = "success";
    return $this->output
    ->set_status_header(200)
    ->set_content_type('application/json', 'utf-8')
    ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

public function getPemesananWithPembayaran($id_pemesanan)
{
    $dataPemesanan = $this->db->get_where("data_pemesanan", "id_pemesanan = '$id_pemesanan'");
    if($dataPemesanan->num_rows() > 0){
        $where_pembayaran = "id_pemesanan = '$id_pemesanan'";
        $dataPembayaran = $this->Pembayaran->getPembayaran($where_pembayaran);
        $result = array('data_pembayaran' => $dataPembayaran->row_array(), 'data_pemesanan' => $dataPemesanan->row_array());
        $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }else{
        $this->output
        ->set_status_header(400)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode(array('data' => []), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}

public function getPesananPriority()
{
    $pesanan = $this->input->get("pesanan");
    $data_pesanan = array();
    $response = array();
    $status_header = 100;
    try {
        $data = $this->Pemesanan->getPesananPriority($pesanan);
        if($data->num_rows() > 0){
            $urutan = 0;
            foreach ($data->result() as $key) {
                $data_pesanan[$urutan] = $this->GET_PESANAN($key->id_pemesanan);
                $data_pesanan[$urutan]['detail_pembeli'] = $this->Pembeli->detail_pembeli($key->id_pb)->row_array();
                $id1 = str_replace("-","",$key->waktu_pemesanan);
                $id2 = str_replace(" ", "", $id1);
                $id3 = str_replace(":", "", $id2);
                $id4 = $id3 . $key->id_pemesanan;
                $data_pesanan[$urutan]['no_pesanan'] = $id4;
                $urutan++;
            }
        }else{
            $status_header = 404;
            $response['statusMessage'] = 'failed';
            $response['data_pesanan'] = $data_pesanan;
        }
        if(count($data_pesanan) > 0){
            $status_header = 200;
            $response['statusMessage'] = 'success';
            $response['data_pesanan'] = $data_pesanan;
        }else{
            $status_header = 404;
            $response['statusMessage'] = 'failed';
            $response['data_pesanan'] = $data_pesanan;
        }
    } catch (Exception $e) {
       $status_header = 500;
       $response['statusMessage'] = 'error';
       $response['data_pesanan'] = $data_pesanan;
   }
   $this->output
   ->set_status_header($status_header)
   ->set_content_type('application/json', 'utf-8')
   ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

public function getPesananNonPriority()
{
    $pesanan = $this->input->get("pesanan");
        // var_dump($pesanan);
        // echo count($pesanan);
    $data_pesanan = array();
    $response = array();
    $status_header = 100;
    try {
        $data= $this->Pemesanan->getPesananNonPriority($pesanan);
        if($data->num_rows() > 0){
            $urutan = 0;
            foreach ($data->result() as $key) {
                $data_pesanan[$urutan] = $this->GET_PESANAN($key->id_pemesanan);
                $data_pesanan[$urutan]['detail_pembeli'] = $this->Pembeli->detail_pembeli($key->id_pb)->row_array();
                $id1 = str_replace("-","",$key->waktu_pemesanan);
                $id2 = str_replace(" ", "", $id1);
                $id3 = str_replace(":", "", $id2);
                $id4 = $id3 . $key->id_pemesanan;
                $data_pesanan[$urutan]['no_pesanan'] = $id4;
                $urutan++;
            }
        }
        if(count($data_pesanan) > 0){
            $status_header = 200;
            $response['statusMessage'] = 'success';
            $response['data_pesanan'] = $data_pesanan;
        }else{
            $status_header = 404;
            $response['statusMessage'] = 'failed';
            $response['data_pesanan'] = $data_pesanan;
        }
    } catch (Exception $e) {
       $status_header = 500;
       $response['statusMessage'] = 'error';
       $response['data_pesanan'] = $data_pesanan;
   }
   $this->output
   ->set_status_header($status_header)
   ->set_content_type('application/json', 'utf-8')
   ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

private function GET_PESANAN($ID_PESANAN)
{
    $data_pesanan = $this->Pemesanan->getDataPemesananByID($ID_PESANAN);
    if($data_pesanan->num_rows() > 0){
        $response = $data_pesanan->row_array();
        $response['detail_pesanan'] = $this->GET_DETAIL_PEMESANAN_WITH_ID($ID_PESANAN)->row_array();
        return $response;
    }else{
        return false;
    }
}

public function procced_order_to_delivery()
{
    // var_dump($this->input->post());
    $kurir = $this->input->post('kurir');
    $kendaraan = $this->input->post('kendaraan');
    $jam_pengiriman = $this->input->post('jam_pengiriman');
    $pesanan = $this->input->post('priority_pesanan');
    $pesanan = json_decode($pesanan);
    try {
        $data = $this->Pemesanan->getPesananPriority($pesanan);
        $data2 = $this->Pemesanan->getPesananNonPriority($pesanan);
        if($data->num_rows() > 0){
            $urutan = 0;
            foreach ($data->result() as $key) {
                $data_pesanan[$urutan] = $this->GET_PESANAN($key->id_pemesanan);
                $data_pesanan[$urutan]['detail_pembeli'] = $this->Pembeli->detail_pembeli($key->id_pb)->row_array();
                $id1 = str_replace("-","",$key->waktu_pemesanan);
                $id2 = str_replace(" ", "", $id1);
                $id3 = str_replace(":", "", $id2);
                $id4 = $id3 . $key->id_pemesanan;
                $data_pesanan[$urutan]['no_pesanan'] = $id4;
                $urutan++;
            }
            if(count($data_pesanan) > 0){
                $status_header = 200;
                $response['statusMessage'] = 'success';
                $response['data_pesanan'] = $data_pesanan;
            }else{
                $status_header = 404;
                $response['statusMessage'] = 'failed';
            // $response['data_pesanan'] = $data_pesanan;
            }
        }

        if($data2->num_rows() > 0){
        // $urutan = 0;
            foreach ($data2->result() as $key) {
                $data_pesanan[$urutan] = $this->GET_PESANAN($key->id_pemesanan);
                $data_pesanan[$urutan]['detail_pembeli'] = $this->Pembeli->detail_pembeli($key->id_pb)->row_array();
                $id1 = str_replace("-","",$key->waktu_pemesanan);
                $id2 = str_replace(" ", "", $id1);
                $id3 = str_replace(":", "", $id2);
                $id4 = $id3 . $key->id_pemesanan;
                $data_pesanan[$urutan]['no_pesanan'] = $id4;
                $response['data_pesanan'][] = $data_pesanan[$urutan];
                $urutan++;
            }
        }
        if(count($data_pesanan) > 0){
            $status_header = 200;
            $response['statusMessage'] = 'success';
        }else{
            $status_header = 404;
            $response['statusMessage'] = 'failed';
            $response['data_pesanan'] = array();
        }
    } catch (Exception $e) {
        $status_header = 500;
        $response['statusMessage'] = 'failed';
        $response['data_pesanan'] = array();
    }
    $this->output
    ->set_status_header($status_header)
    ->set_content_type('application/json', 'utf-8')
    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

}