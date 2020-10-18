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
        $this->load->model("Model_pengiriman");
        $this->load->library('encryption');
        $this->encryption->initialize(array('driver' => 'mcrypt'));
        date_default_timezone_set("Asia/Bangkok");
        // $this->load->helper("Response");
    }

    protected $urutan_pemesanan = 0;
    protected $urutan_pengiriman = 1;
    protected $StatusPemesananBaru = "Baru";

    public function pesanan_user()
    {
        $id_akun = $this->input->post('id_akun');
        $data_pembeli = $this->Pembeli->detail_pembeli($id_akun);
        if ($data_pembeli->num_rows() > 0) {
            response(200, $data_pembeli->row_array());
        } else {
            response(400, array());
        }
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
        $array_update = array(
            'alamat_pb' => $alamatLengkap,
            'kab_pb' => $kotaKabupaten,
            'kec_pb' => $kecamatan,
            'kel_pb' => $kelurahan,
            'longitude_pb' => $longitude,
            'latitude_pb' => $latitude
        );
        try {
            $updateAlamat = $this->Pembeli->updateAlamat($array_update, $id_akun);
            $result = array();
            $result['responseMessage'] = "success";
            response(200, $result);
        } catch (Exception $e) {
            $result['responseMessage'] = "failed with " . $e->getMessage();
            response(500, $result);
        }
    }

    public function simpanPemesanan()
    {
        $this->load->model("Model_keranjang");
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
        $statusHeader = 201;
        $result = array();
        try {
            $jarak = $this->input->post('jarak');
            $dataPemesanan = array(
                'waktu_pemesanan' => $tglPemesanan,
                'tipe_pengiriman' => $tipePengiriman,
                'tgl_pengiriman' => $tglPengiriman,
                'biaya_kirim' => intval($totalBiayaPengiriman),
                'total_harga' => intval($totalPembayaran),
                'status_pemesanan' => $this->StatusPemesananBaru,
                'id_pb' => $id_akun,
                'id_usaha' => $id_usaha,
                'jarak' => $jarak
            );
            $isPemesanan = $this->Pemesanan->createPemesanan($dataPemesanan);
            if ($isPemesanan) {
                $dataPemesanan = $this->Pemesanan->getDataPemesananByIdUser($id_akun, 1, 'id_pemesanan', 'DESC');
                $id_pemesanan = $dataPemesanan->row()->id_pemesanan;
                $data = array();
                $totalProduk = count($produk);
                for ($i = 0; $i < $totalProduk; $i++) {
                    $arrayProduk = $produk[$i];
                    $harga_produk = intval($arrayProduk['harga_produk']);
                    $jml_produk = intval($arrayProduk['jml_produk']);
                    $subtotal = intval($harga_produk * $jml_produk);
                    $id_produk = $arrayProduk['id_variasi_produk'];
                    $catatan = $arrayProduk['catatan'];
                    $data[] = array(
                        'harga' => $harga_produk,
                        'jml_produk' => $jml_produk,
                        'sub_total' => $subtotal,
                        'id_pemesanan' => $id_pemesanan,
                        'id_produk' => $id_produk,
                        'catatan' => $catatan
                    );
                }
                $isDetailPemesanan = $this->Pemesanan->createDetailPemesanan_batch($data);
                if ($isDetailPemesanan) {
                    //PEMBAYARAN
                    $dataPembayaran = array(
                        'metode_pembayaran' => $metodePembayaran,
                        'expiredDate' => $expiredDate,
                        'id_pemesanan' => $id_pemesanan
                    );
                    $isPembayaran = $this->Pembayaran->createPembayaran($dataPembayaran);
                    $delete_keranjang = $this->Model_keranjang->delete_keranjang_by_id_usaha($id_usaha, $id_akun);
                    if ($isPembayaran && $delete_keranjang) {
                        $id_detail_pemesanan = $this->Pemesanan->get_selected_detail_pemesanan("id_dp", "id_pemesanan = " . $id_pemesanan)->result_array();
                        $id_pembayaran = $this->Pembayaran->get_selected_pembayaran("id_pembayaran", "id_pemesanan = " . $id_pemesanan)->row()->id_pembayaran;
                        $result = array(
                            'responseMessage' => 'success',
                            'listPemesanan' => $produk,
                            'responseCode' => '00',
                            'id_pemesanan' => $id_pemesanan,
                            'id_detail_pemesanan' => $id_detail_pemesanan,
                            'id_pembayaran' => $id_pembayaran
                        );
                    } else {
                        $statusHeader = 404;
                        $result = array('responseMessage' => 'failed ', 'listPemesanan' => null, 'responseCode' => '03');
                    }
                } else {
                    $statusHeader = 409;
                    $result = array('responseMessage' => 'failed ', 'listPemesanan' => null, 'responseCode' => '02');
                }
            } else {
                $statusHeader = 400;
                $result = array('responseMessage' => 'failed ', 'listPemesanan' => null, 'responseCode' => '01');
            }
        } catch (Exception $e) {
            $errorMessage =  $e->getMessage();
            $result = array('responseMessage' => 'failed ' . $errorMessage, 'listPemesanan' => null, 'responseCode' => '01');
            $statusHeader = 500;
        }
        response($statusHeader, $result);
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
        $statusHeader = 200;
        try {
            $data = $this->Pemesanan->getDetailPemesanan($id_pemesanan);
            if ($data->num_rows() > 0) {
                $result = array('responseMessage' => 'success', 'responseCode' => '00', 'data' => $data->result_array());
            } else {
                $statusHeader = 404;
                $result = array('responseMessage' => 'failed', 'responseCode' => '02', 'status' => null);
            }
        } catch (Exception $e) {
            $statusHeader = 500;
            $result = array('responseMessage' => 'failed', 'responseCode' => '01', 'status' => null);
        }
        response($statusHeader, $result);
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
        try {
            $query = $this->Pemesanan->getDataPemesananByIdUserAndStatus($idAkun, $status, $limit, $orderBy, $typeOrder);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $dataPemesanan) {
                    $id1 = str_replace("-", "", $dataPemesanan->waktu_pemesanan);
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
                    if (isset($DataDetailPesanan)) {
                        // var_dump($DataDetailPesanan);
                        $namaProduk = $DataDetailPesanan->nama_produk;
                        $namaProduk = $namaProduk . ' ' . $DataDetailPesanan->nama_variasi;
                        $hargaProduk = $DataDetailPesanan->harga;
                        $totalProduk = $DataDetailPesanan->jml_produk;
                        $fotoProduk = $DataDetailPesanan->foto_produk;
                        $arrayToDisplay = array(
                            'namaProduk' => $namaProduk,
                            'hargaProduk' => $hargaProduk,
                            'totalProduk' => $totalProduk,
                            'fotoProduk' => $fotoProduk
                        );
                        $DaftarProduk = $queryDetailPesanan->result_array();
                        $TotalHargaProduk = 0;
                        $TotalBeratProduk = 0;
                        $TotalProduk = 0;
                        $SubTotal = null;
                        $SubBerat = null;
                        $SubTotalProduk = null;
                        foreach ($queryDetailPesanan->result() as $Products) {
                            $SubTotal[] = $Products->sub_total;
                            $SubBerat[] = $Products->jml_produk;
                            $SubTotalProduk[] = $Products->jml_produk;
                        }
                        $TotalHargaProduk = array_sum($SubTotal);
                        $TotalBeratProduk = array_sum($SubBerat);
                        $TotalProduk = array_sum($SubTotalProduk);
                        $result[] = array(
                            'ID' => $id4,
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
                            'jarak' => $jarak
                        );
                    }
                }
                $resultArray = array(
                    'dataPesanan' => $result,
                    'responseMessage' => 'success',
                    'responseCode' => "00"
                );
            } else {
                $statusHeader = 404;
                $resultArray = array(
                    'dataPesanan' => array(),
                    'responseMessage' => 'success',
                    'responseCode' => "00"
                );
            }
        } catch (Exception $e) {
            $statusHeader = 500;
            $resultArray = array(
                'dataPesanan' => null,
                'responseMessage' => 'failed ' + $e->getMessage(),
                'responseCode' => "01"
            );
        }
        response($statusHeader, $resultArray);
    }

    public function getDataPemesananByID()
    {
        $id_pemesanan = $this->input->get('id_pemesanan');
        $limit = 1;
        $resultArray = array();
        $result = array();
        $statusHeader = 200;
        $resultArray = array();
        try {
            $query = $this->Pemesanan->getDataPemesananByID($id_pemesanan);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $dataPemesanan) {
                    $id1 = str_replace("-", "", $dataPemesanan->waktu_pemesanan);
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
                    if (isset($DataDetailPesanan)) {
                        // var_dump($DataDetailPesanan);
                        $namaProduk = $DataDetailPesanan->nama_produk;
                        $namaProduk = $namaProduk . ' ' . $DataDetailPesanan->nama_variasi;
                        $hargaProduk = $DataDetailPesanan->harga;
                        $totalProduk = $DataDetailPesanan->jml_produk;
                        $fotoProduk = $DataDetailPesanan->foto_produk;
                        $arrayToDisplay = array(
                            'namaProduk' => $namaProduk,
                            'hargaProduk' => $hargaProduk,
                            'totalProduk' => $totalProduk,
                            'fotoProduk' => $fotoProduk
                        );
                        $DaftarProduk = $queryDetailPesanan->result_array();
                        $TotalHargaProduk = 0;
                        $TotalBeratProduk = 0;
                        $TotalProduk = 0;
                        $SubTotal = null;
                        $SubBerat = null;
                        $SubTotalProduk = null;
                        foreach ($queryDetailPesanan->result() as $Products) {
                            $SubTotal[] = $Products->sub_total;
                            $SubBerat[] = $Products->jml_produk;
                            $SubTotalProduk[] = $Products->jml_produk;
                        }
                        $TotalHargaProduk = array_sum($SubTotal);
                        $TotalBeratProduk = array_sum($SubBerat);
                        $TotalProduk = array_sum($SubTotalProduk);
                        $result = array(
                            'ID' => $id4,
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
                            'waktu_pembayaran_new' => $waktu_pembayaran_new
                        );
                    }
                }
                $resultArray = array(
                    'dataPesanan' => $result,
                    'responseMessage' => 'success',
                    'responseCode' => "00"
                );
            } else {
                $statusHeader = 404;
                $resultArray = array(
                    'dataPesanan' => array(),
                    'responseMessage' => 'success',
                    'responseCode' => "00"
                );
            }
        } catch (Exception $e) {
            $statusHeader = 500;
            $resultArray = array(
                'dataPesanan' => null,
                'responseMessage' => 'failed ' + $e->getMessage(),
                'responseCode' => "01"
            );
        }
        response($statusHeader, $resultArray);
    }

    public function getAllTransaksiPenjual()
    {
        $idusaha = $this->input->get('id_usaha');
        $status = (!empty($this->input->get('status')) ? $this->input->get('status') : null);
        $tipePengiriman = (!empty($this->input->get('tipePengiriman')) ? $this->input->get('tipePengiriman') : null);
        $Pengiriman = new Model_pengiriman();
        $limit = null;
        $orderBy = "tgl_pengiriman ASC";
        if ($status == "Siap Dikirim") {
            $orderBy .= ", tipe_pengiriman DESC";
        }
        $typeOrder = "DESC";
        $resultArray = array();
        $result = array();
        $statusHeader = 200;
        try {
            $where = "id_usaha = '$idusaha' ";
            $where .= ($status !== null) ? " AND status_pemesanan = '$status'" : "";
            $where .= ($tipePengiriman !== null) ? " tipe_pengiriman = '$tipePengiriman'" : "";
            if ($status == "Terbayar") {
                $where .= " AND tgl_pengiriman != date(now())";
            }
            $query = $this->Pemesanan->getWhereDataPemesananByIdUsaha($where, $limit, $orderBy);
            // echo $this->db->last_query();
            // exit();
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $dataPemesanan) {
                    $id1 = str_replace("-", "", $dataPemesanan->waktu_pemesanan);
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
                    } else {
                        $DataPembayaran = array();
                    }

                    $queryDetailPesanan = $this->Pemesanan->getDetailPemesanan($idPemesanan);
                    // echo $this->db->last_query();
                    $DataDetailPesanan = $queryDetailPesanan->row();
                    if (isset($DataDetailPesanan)) {
                        // var_dump($DataDetailPesanan);
                        $namaProduk = $DataDetailPesanan->nama_produk;
                        $namaProduk = $namaProduk . ' ' . $DataDetailPesanan->nama_variasi;
                        $hargaProduk = $DataDetailPesanan->harga;
                        $totalProduk = $DataDetailPesanan->jml_produk;
                        $fotoProduk = $DataDetailPesanan->foto_produk;
                        $arrayToDisplay = array(
                            'namaProduk' => $namaProduk,
                            'hargaProduk' => $hargaProduk,
                            'totalProduk' => $totalProduk,
                            'fotoProduk' => $fotoProduk
                        );
                        $DaftarProduk = $queryDetailPesanan->result_array();
                        $TotalHargaProduk = 0;
                        $TotalBeratProduk = 0;
                        $TotalProduk = 0;
                        $SubTotal = null;
                        $SubBerat = null;
                        $SubTotalProduk = null;
                        foreach ($queryDetailPesanan->result() as $Products) {
                            $SubTotal[] = $Products->sub_total;
                            $SubBerat[] = $Products->berat_produk;
                            $SubTotalProduk[] = $Products->jml_produk;
                        }
                        $TotalHargaProduk = array_sum($SubTotal);
                        $TotalBeratProduk = array_sum($SubBerat);
                        $TotalProduk = array_sum($SubTotalProduk);
                        $data_pengiriman_pemesanan = $Pengiriman->get_detail_by_id_pemesanan($idPemesanan);
                        $detail_pengiriman = array();
                        if ($data_pengiriman_pemesanan->num_rows() > 0) {
                            $result_pengiriman = $data_pengiriman_pemesanan->row();
                            $detail_pengiriman = array(
                                'id_pengiriman' => $result_pengiriman->id_pengiriman,
                                'id_detail_pengiriman' => $result_pengiriman->id_detail_pengiriman,
                                'urutan' => $result_pengiriman->urutan,
                                'status' => $result_pengiriman->status
                            );
                        }
                        $result[] = array(
                            'ID' => $id4,
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
                            'detail_pengiriman' => $detail_pengiriman
                        );
                    }
                }
                $resultArray = array(
                    'dataPesanan' => $result,
                    'responseMessage' => 'success',
                    'responseCode' => "00"
                );
            } else {
                $statusHeader = 404;
                $resultArray = array(
                    'dataPesanan' => array(),
                    'responseMessage' => 'failed',
                    'responseCode' => "01"
                );
            }
        } catch (Exception $e) {
            $resultArray = array(
                'dataPesanan' => null,
                'responseMessage' => 'failed ' + $e->getMessage(),
                'responseCode' => "99"
            );
            $statusHeader = 500;
        }
        response($statusHeader, $resultArray);
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
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $dataPemesanan) {
                    $id1 = str_replace("-", "", $dataPemesanan->waktu_pemesanan);
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
                    } else {
                        $DataPembayaran = array();
                    }
                    $data_pengiriman = array('urutan' => $dataPemesanan->urutan, 'status_pengiriman_pesanan' => $dataPemesanan->status, 'penerima' => $dataPemesanan->penerima);
                    $queryDetailPesanan = $this->Pemesanan->getDetailPemesanan($idPemesanan);
                    // echo $this->db->last_query();
                    $DataDetailPesanan = $queryDetailPesanan->row();
                    if (isset($DataDetailPesanan)) {
                        // var_dump($DataDetailPesanan);
                        $namaProduk = $DataDetailPesanan->nama_produk;
                        $namaProduk = $namaProduk . ' ' . $DataDetailPesanan->nama_variasi;
                        $hargaProduk = $DataDetailPesanan->harga;
                        $totalProduk = $DataDetailPesanan->jml_produk;
                        $fotoProduk = $DataDetailPesanan->foto_produk;
                        $arrayToDisplay = array(
                            'namaProduk' => $namaProduk,
                            'hargaProduk' => $hargaProduk,
                            'totalProduk' => $totalProduk,
                            'fotoProduk' => $fotoProduk
                        );
                        $DaftarProduk = $queryDetailPesanan->result_array();
                        $TotalHargaProduk = 0;
                        $TotalBeratProduk = 0;
                        $TotalProduk = 0;
                        $SubTotal = null;
                        $SubBerat = null;
                        $SubTotalProduk = null;
                        foreach ($queryDetailPesanan->result() as $Products) {
                            $SubTotal[] = $Products->sub_total;
                            $SubBerat[] = $Products->berat_produk;
                            $SubTotalProduk[] = $Products->jml_produk;
                        }
                        $TotalHargaProduk = array_sum($SubTotal);
                        $TotalBeratProduk = array_sum($SubBerat);
                        $TotalProduk = array_sum($SubTotalProduk);
                        $result[] = array(
                            'ID' => $id4,
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
                            'data_pengiriman' => $data_pengiriman
                        );
                    }
                }
                $resultArray = array(
                    'dataPesanan' => $result,
                    'responseMessage' => 'success',
                    'responseCode' => "00"
                );
            } else {
                $statusHeader = 404;
                $resultArray = array(
                    'dataPesanan' => array(),
                    'responseMessage' => 'failed',
                    'responseCode' => "01"
                );
            }
        } catch (Exception $e) {
            $resultArray = array(
                'dataPesanan' => null,
                'responseMessage' => 'failed ' + $e->getMessage(),
                'responseCode' => "99"
            );
            $statusHeader = 500;
        }
        response($statusHeader, $resultArray);
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
            $JOIN[] = array('table' => 'data_pembayaran pembayaran', 'on' => 'pembayaran.id_pemesanan = pemesanan.id_pemesanan', 'join' => '');
            $JOIN[] = array('table' => 'data_pembeli pembeli', 'on' => 'pembeli.id_pb = pemesanan.id_pb', 'join' => '');
            $order = "pemesanan.tipe_pengiriman DESC";
            $data_pemesanan = $this->Pemesanan->get_where($SELECT_PEMESANAN, $SET_WHERE_LIST_PEMESANAN, $JOIN, NULL, $order);
            // echo $this->db->last_query();
            if ($data_pemesanan->num_rows() > 0) {
                $status_header = 200;
                foreach ($data_pemesanan->result() as $key) {
                    $id1 = str_replace("-", "", $key->waktu_pemesanan);
                    $id2 = str_replace(" ", "", $id1);
                    $id3 = str_replace(":", "", $id2);
                    $id4 = $id3 . $key->id_pemesanan;
                    $response['data_pemesanan'][] = array('id_pemesanan' => $key->id_pemesanan, 'no_pesanan' => $id4, 'tipe_pengiriman' => $key->tipe_pengiriman, 'alamat' => $key->alamat_pb, 'nama_pembeli' => $key->nama_pb, 'date' => date('d - m - Y'), 'detail_pemesanan' => $this->GET_DETAIL_PEMESANAN_WITH_ID($key->id_pemesanan)->result_array());
                }
                $response['status'] = 'sukses';
            } else {
                $status_header = 404;
                $response['status'] = 'gagal';
            }
        } catch (Exception $e) {
            $response['status'] = 'gagal';
        }
        response($status_header, $response);
    }


    public function get_transaksi_today()
    {
        $id_usaha = $this->input->get("id_usaha", TRUE);
        $response = array();
        $status_header = 500;
        $getDateToday = date("Y-m-d");
        try {
            // $where = array('id_usaha' => $id_usaha, 'status_pemesanan' => "Terbayar", 'tgl_pengirmian' => $getDateToday);
            //  "id_usaha = '$id_usaha'" . 
            // " AND status_pemesanan = 'Terbayar' AND tgl_pengiriman = '$getDateToday'";
            $limit = null;
            $orderBy = "id_pemesanan ASC";
            $query = $this->Pemesanan->pemesananUsahaHariIni($id_usaha, $getDateToday, $limit, $orderBy);
            // echo $this->db->last_query();
            if ($query->num_rows() > 0) {
                // $dataPemesanan = $query->row();
                $result = array();
                foreach ($query->result() as $dataPemesanan) {
                    $id1 = str_replace("-", "", $dataPemesanan->waktu_pemesanan);
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
                    } else {
                        $DataPembayaran = array();
                    }

                    $queryDetailPesanan = $this->GET_DETAIL_PEMESANAN_WITH_ID($dataPemesanan->id_pemesanan);
                    // echo $this->db->last_query();
                    $DataDetailPesanan = $queryDetailPesanan->row();
                    if (isset($DataDetailPesanan)) {
                        // var_dump($DataDetailPesanan);
                        $namaProduk = $DataDetailPesanan->nama_produk;
                        $namaProduk = $namaProduk . ' ' . $DataDetailPesanan->nama_variasi;
                        $hargaProduk = $DataDetailPesanan->harga;
                        $totalProduk = $DataDetailPesanan->jml_produk;
                        $fotoProduk = $DataDetailPesanan->foto_produk;
                        $arrayToDisplay = array(
                            'namaProduk' => $namaProduk,
                            'hargaProduk' => $hargaProduk,
                            'totalProduk' => $totalProduk,
                            'fotoProduk' => $fotoProduk
                        );
                        $DaftarProduk = $queryDetailPesanan->result_array();
                        $TotalHargaProduk = 0;
                        $TotalBeratProduk = 0;
                        $TotalProduk = 0;
                        $SubTotal = null;
                        $SubBerat = null;
                        $SubTotalProduk = null;
                        foreach ($queryDetailPesanan->result() as $Products) {
                            $SubTotal[] = $Products->sub_total;
                            $SubBerat[] = $Products->berat_produk;
                            $SubTotalProduk[] = $Products->jml_produk;
                        }
                        $TotalHargaProduk = array_sum($SubTotal);
                        $TotalBeratProduk = array_sum($SubBerat);
                        $TotalProduk = array_sum($SubTotalProduk);
                        $result[] = array(
                            'ID' => $id4,
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
                            'statusPengiriman' => $statusPengiriman
                        );
                    }
                }
                // foreach($query->result() as $dataPemesanan){

                // }
                $resultArray = array(
                    'dataPesanan' => $result,
                    'responseMessage' => 'success',
                    'responseCode' => "00"
                );
                $response = $resultArray;
                $status_header = 200;
            } else {
                $statusHeader = 404;
                $resultArray = array(
                    'dataPesanan' => array(),
                    'responseMessage' => 'failed. not found',
                    'responseCode' => "01"
                );
            }
        } catch (Exception $e) {
            $response['status'] = 'gagal';
            $response['responseMessage'] = $e->getMessage();
        }
        response($status_header, $response);
    }

    private function GET_DETAIL_PEMESANAN_WITH_ID($ID_PESANAN)
    {
        $response = array();
        $SELECT_DETAIL = "detail_pemesanan.`id_dp`, detail_pemesanan.`harga`, detail_pemesanan.`jml_produk`, detail_pemesanan.`sub_total`, detail_pemesanan.`id_pemesanan`, detail_pemesanan.`id_produk`, detail_pemesanan.`berat_akhir`, produk.nama_produk, variasi.nama_variasi, produk.foto_produk, produk.berat_produk, detail_pemesanan.catatan";
        $SET_WHERE_LIST_DETAIL = "detail_pemesanan.id_pemesanan = '$ID_PESANAN'";
        $JOIN[] = array('table' => 'data_variasi_produk variasi_produk', 'on' => 'variasi_produk.id_variasiproduk = detail_pemesanan.id_produk', 'join' => NULL);
        $JOIN[] = array('table' => 'data_produk produk', 'on' => 'produk.id_produk = variasi_produk.id_produk', 'join' => NULL);
        $JOIN[] = array('table' => 'data_variasi variasi', 'on' => 'variasi.id_variasi = variasi_produk.id_variasi', 'join' => NULL);
        $order = "detail_pemesanan.id_dp ASC";
        $data_detail_pesanan = $this->Pemesanan->get_detail_where($SELECT_DETAIL, $SET_WHERE_LIST_DETAIL, $JOIN, NULL, $order);
        if ($data_detail_pesanan->num_rows() > 0) {
            $response = $data_detail_pesanan;
        }
        return $response;
    }

    public function get_produk_to_varify()
    {
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
        response($status_header, $response);
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
                $data_detail_update = array('berat_akhir' => $berat[$affected_rows] / 10);
                $update_detail_pesanan = $this->Pemesanan->update_detail_pesanan($key->id_dp, $data_detail_update);
                if ($update_detail_pesanan) {
                    $affected_rows++;
                }
            }
            if ($affected_rows > 0) {
                $pesanan = $this->GET_PESANAN($id_pemesanan);
                $data_pesanan = $pesanan;
                $array_update_pesanan = array();
                if ($data_pesanan['tipe_pengiriman'] == "Ambil di Toko") {
                    $array_update_pesanan = array('status_pemesanan' => 'Siap Diambil');
                } else {
                    $array_update_pesanan = array('status_pemesanan' => 'Siap Dikirim');
                }
                $where_update = "id_pemesanan = '$id_pemesanan'";
                $do_update_pemesanan = $this->Pemesanan->updatePemesanan($array_update_pesanan, $where_update);
                if ($do_update_pemesanan) {
                    $status_header = 200;
                    $response['status'] = 'sukses';
                    $response['message'] = 'Berhasil Ubah Berat Akhir';
                }
            } else {
                $status_header = 400;
                $response['status'] = 'gagal';
                $response['message'] = 'Gagal Ubah Berat Akhir';
            }
        } catch (Exception $e) {
            $response['status'] = 'error';
            $response['message'] = 'Gagal Ubah Berat Akhir Karena Kesalahan Server ' . $e->getMessage();
        }
        response($status_header, $response);
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
        try {
            $where = " id_pemesanan = '$idPemesanan'";
            $where .= ($id_usaha) ? " AND id_usaha = '$id_usaha'" : "";
            $where .= ($type) ? " AND tipe_pengiriman = '$type'" : "";
            $where .= ($status !== null) ? " AND status_pemesanan = '$status'" : "";
            // $where .= " AND (tgl_pengiriman != date(now()) AND status_pembayaran!='Terbayar')";

            $query = $this->Pemesanan->getWhereDataPemesananByIdUsaha($where, $limit, $orderBy);
            // echo $this->db->last_query();
            // exit();
            if ($query->num_rows() > 0) {
                $dataPemesanan = $query->row();
                // foreach($query->result() as $dataPemesanan){
                $id1 = str_replace("-", "", $dataPemesanan->waktu_pemesanan);
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
                } else {
                    $DataPembayaran = array();
                }

                $queryDetailPesanan = $this->Pemesanan->getDetailPemesanan($idPemesanan);
                // echo $this->db->last_query();
                $DataDetailPesanan = $queryDetailPesanan->row();
                if (isset($DataDetailPesanan)) {
                    // var_dump($DataDetailPesanan);
                    $namaProduk = $DataDetailPesanan->nama_produk;
                    $namaProduk = $namaProduk . ' ' . $DataDetailPesanan->nama_variasi;
                    $hargaProduk = $DataDetailPesanan->harga;
                    $totalProduk = $DataDetailPesanan->jml_produk;
                    $fotoProduk = $DataDetailPesanan->foto_produk;
                    $arrayToDisplay = array(
                        'namaProduk' => $namaProduk,
                        'hargaProduk' => $hargaProduk,
                        'totalProduk' => $totalProduk,
                        'fotoProduk' => $fotoProduk
                    );
                    $DaftarProduk = $queryDetailPesanan->result_array();
                    $TotalHargaProduk = 0;
                    $TotalBeratProduk = 0;
                    $TotalProduk = 0;
                    $SubTotal = null;
                    $SubBerat = null;
                    $SubTotalProduk = null;
                    foreach ($queryDetailPesanan->result() as $Products) {
                        $SubTotal[] = $Products->sub_total;
                        $SubBerat[] = $Products->berat_produk;
                        $SubTotalProduk[] = $Products->jml_produk;
                    }
                    $TotalHargaProduk = array_sum($SubTotal);
                    $TotalBeratProduk = array_sum($SubBerat);
                    $TotalProduk = array_sum($SubTotalProduk);
                    $result = array(
                        'ID' => $id4,
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
                        'jarak' => $jarak
                    );
                }
                // }
                $resultArray = array(
                    'dataPesanan' => $result,
                    'responseMessage' => 'success',
                    'responseCode' => "00"
                );
            } else {
                $statusHeader = 404;
                $resultArray = array(
                    'dataPesanan' => array(),
                    'responseMessage' => 'success',
                    'responseCode' => "00"
                );
            }
        } catch (Exception $e) {
            $resultArray = array(
                'dataPesanan' => null,
                'responseMessage' => 'failed ' + $e->getMessage(),
                'responseCode' => "01"
            );
            $statusHeader = 500;
        }
        response($statusHeader, $resultArray);
    }

    public function PemesananSelesai()
    {
        $id = $this->input->post('id');
        $data = array('status_pemesanan' => 'Terkirim');
        try {
            $update = $this->Pemesanan->updatePemesanan($data, array('id_pemesanan' => $id));
            $updateAffected  = $this->db->affected_rows();
            if ($updateAffected > 0) {
                $response = array('id' => $id, 'message' => 'success', 'statusCode' => '00');
                response(200, $response);
            } else {
                $response = array('message' => 'failed. Pesanan Tidak Ada', 'statusCode' => '01');
                response(400, $response);
            }
        } catch (Exception $e) {
            $response = array('message' => 'failed.' . $e->getMessage(), 'statusCode' => '99');
            response(500, $response);
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
            if ($DeletePembayaran) {
                $DeleteDetailPemesanan = $this->Pemesanan->DeleteDetailPemesanan("id_pemesanan = " . $idPemesanan);
                // echo $this->db->last_query();
                if ($DeleteDetailPemesanan) {
                    $DeletePemesanan = $this->Pemesanan->DeletePemesanan("id_pemesanan =", $idPemesanan);
                    // echo $this->db->last_query();
                    if ($DeletePemesanan) {
                        $result = array('responseMessage' => "success", "responseCode" => "00");
                        response($statusHeader, $result);
                    } else {
                        $statusHeader = 400;
                        $result = array('responseMessage' => "failed", "responseCode" => "01");
                        response($statusHeader, $result);
                    }
                } else {
                    $statusHeader = 400;
                    $result = array('responseMessage' => "failed", "responseCode" => "02");
                    response($statusHeader, $result);
                }
            } else {
                $statusHeader = 404;
                $result = array('responseMessage' => "failed", "responseCode" => "03");
                response($statusHeader, $result);
            }
        } catch (Exception $e) {
            $statusHeader = 500;
            $result = array("responseMessage" => "Error");
            response($statusHeader, $result);
        }
    }

    public function verifikasiPembayaranByPenjual()
    {
        $id_pemesanan = intval($this->input->post("idPemesanan", TRUE));
        $id_pembayaran = intval($this->input->post("id_pembayaran", TRUE));
        // var_dump($this->input->post());
        $array = array();
        try {
            $wherePemesanan = array("id_pemesanan"=> $id_pemesanan);
            $get_data = $this->db->get_where("data_pembayaran", $wherePemesanan, 1);
            // echo $this->db->last_query();
            // echo '<br>';
            if ($get_data->num_rows() > 0) {
                
                $data_update = array('verifikasi' => '1');
                $whereUpdate = array("id_pembayaran" => $id_pembayaran);
                $update = $this->db->update("data_pembayaran", $data_update, $whereUpdate);
                if ($update) {
                    // echo $this->db->last_query();
                    // echo '<br>';
                    $data_update2 = array("status_pemesanan" => "Terbayar");
                    $update_pemesanan = $this->Pemesanan->updatePemesanan($data_update2, $wherePemesanan);
                    if ($update_pemesanan > 0) {
                        // echo $this->db->last_query();
                        // echo '<br>';
                        $updateStokProdukPemesanan = $this->UpdateStokProduk($id_pemesanan);
                        $array = array('status' => 'success', 'message' => 'Verifikasi Berhasil', 'respon_update_stok' => $updateStokProdukPemesanan, 'id_pemesanan' => $id_pemesanan);
                    } else {
                        $array = array('status' => 'failed', 'message' => 'Gagal Ter Verifikasi');
                        response(400, $array);
                    }
                } else {
                    $array = array('status' => 'failed', 'message' => 'Gagal Ter Verifikasi');
                    response(400, $array);
                }
            } else {
                $array = array('status' => 'failed', 'message' => 'Data Pesanan Belum Pernah Ada');
                response(400, $array);
            }
        } catch (Exception $e) {
            $array = array('status' => 'failed', 'message' => 'Data Gagal Terbaca Dengan ' . $e->getMessage());
            response(500, $array);
        }
        // exit();
        response(200, $array);
    }

    protected function UpdateStokProduk(int $id_pemesanan)
    {
        try {
            //code...
            $DataDetail = $this->Pemesanan->getDetailPemesananByIdPemesanan($id_pemesanan);
            $counter_update = 0;
            $id_terupdate = array();
            $produkTerupdate = array();
            if ($DataDetail->num_rows() > 0) {
                foreach ($DataDetail->result() as $key) {
                    # code...
                    $jml_yang_dipesan = intval($key->jml_produk)*10;
                    $id_variasi_produk = intval($key->id_var);
                    $stokYangTersedia = intval($key->stok);
                    $sisaStok = $stokYangTersedia - $jml_yang_dipesan;
                    if ($stokYangTersedia == 0 && $stokYangTersedia < $jml_yang_dipesan) {
                        $produkTerupdate[] = $this->returnCekStokProdukKosong($id_variasi_produk);
                    } else {
                        // UPDATE STOK BY MODEL SQL PRODUK
                        $setData = array("stok" => $sisaStok);
                        $update_stok = $this->Produk->updateStokProdukFromPemesanan($id_variasi_produk, $setData);
                        if ($update_stok) {
                            // echo $this->db->last_query();
                            // echo '<br>';
                            $produkTerupdate[] = array(
                                'id_variasi_produk' => $id_variasi_produk,
                                'status' => "success",
                                'message' => "Berhasil update stok yang tersedia",
                                'stok' => $sisaStok
                            );
                            $id_terupdate[] = $id_variasi_produk;
                            $counter_update++;
                        }
                    }
                }
                if ($counter_update > 0) {
                    return array(
                        'status' => 'success',
                        'produkTerupdate' => $produkTerupdate,
                        'message' => "Update stok produk sukses",
                        'affected_rows' => $counter_update,
                        'id_terupdate' => $id_terupdate,
                        'id_pemesanan' => $id_pemesanan
                    );
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            response(500, array('status' => "failed", "message" => "Update stok gagal karena " . $th->getMessage()));
        }
    }

    protected function returnCekStokProdukKosong($id_variasi_produk = null)
    {
        return array(
            'id_variasi_produk' => $id_variasi_produk,
            'status' => "Failed",
            'message' => "Stok yang tersedia tidak mencukupi dari yang dipesan"
        );
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
        $id1 = str_replace("-", "", $DataPemesanan->row()->waktu_pemesanan);
        $id2 = str_replace(" ", "", $id1);
        $id3 = str_replace(":", "", $id2);
        $id4 = $id3 . $DataPemesanan->row()->id_pemesanan;
        $dateNew = date_create($DataPemesanan->row()->waktu_pemesanan);
        $dateNew2 = date_create($DataPemesanan->row()->tgl_pengiriman);
        $waktuPemesanan = date_format($dateNew, 'd/m/Y H:i');
        $tglPengiriman = date_format($dateNew2, 'd/m/Y');
        $DataPengiriman = $this->Pemesanan->getDetailPengirimanWithKurirKendaraan($idPemesanan)->row();

        // echo $this->db->last_query();
        $dataView = array(
            'DataDetailPesanan' => $DataDetailPesanan,
            'DataPemesanan' => $DataPemesanan,
            'DataPembayaran' => $DataPembayaran,
            'waktuPemesanan' => $waktuPemesanan,
            'tglPengiriman' => $tglPengiriman,
            'noPesanan' => $id4,
            'DataPengiriman' => $DataPengiriman
        );
        $LokasiHalaman = '';
        if ($statusPemesanan == "Baru") {
            $LokasiHalaman = "detail-pesanan-baru-all";
        } else if ($statusPemesanan == "Terbayar") {
            $LokasiHalaman = "detail-pesanan-terbayar-all";
        } else if ($statusPemesanan == "Terkirim") {
            $LokasiHalaman = "detail-pesanan-terkirim-all";
        }
        $dataView['statusPemesanan'] = $statusPemesanan;
        $dataView['JenisPengiriman'] = $JenisPengiriman;
        $dataView['JenisPembayaran'] = $JenisPembayaran;
        //   var_dump($dataView);
        $this->load->view('pembeli/pesanan-saya/' . $LokasiHalaman, $dataView);
    }

    public function getStrukImage()
    {
        $idPemesanan = $this->input->get('idPemesanan');
        try {
            $data_pembayaran = $this->db->get_where("data_pembayaran", array('id_pemesanan' => $idPemesanan), 1);
            if ($data_pembayaran->num_rows() > 0) {
                $data = $data_pembayaran->row();
                $image = base_url("foto_struk/" . $data->struk_pembayaran);
                $result['struk_pembayaran'] = $image;
                $result['response'] = "success";
                if ($data->struk_pembayaran !== "" || !empty($data->struk_pembayaran)) {
                    response(200, $result);
                } else {
                    response(404, array('response' => "failed", 'struk_pembayaran' => null));
                }
            } else {
                response(404, array('response' => "failed. data pemesanan not found", 'struk_pembayaran' => null));
            }
        } catch (Exception $e) {
            response(500, array('response' => "error " . $e->getMessage(), 'struk_pembayaran' => null));
        }
    }

    public function getPemesananWithPembayaran($id_pemesanan)
    {
        $dataPemesanan = $this->db->get_where("data_pemesanan", "id_pemesanan = '$id_pemesanan'");
        if ($dataPemesanan->num_rows() > 0) {
            $where_pembayaran = "id_pemesanan = '$id_pemesanan'";
            $dataPembayaran = $this->Pembayaran->getPembayaran($where_pembayaran);
            $result = array('data_pembayaran' => $dataPembayaran->row_array(), 'data_pemesanan' => $dataPemesanan->row_array());
            response(200, $result);
        } else {
            response(404, array('data' => []));
        }
    }

    public function getPesananPriority()
    {
        $pesanan = $this->input->get("pesanan");
        $data_pesanan = array();
        $data_pesanan_lain = array();
        $response = array();
        $status_header = 100;
        try {
            $data = $this->Pemesanan->getPesananPriority($pesanan);
            // echo $this->db->last_query();
            $data_lain = $this->Pemesanan->getPesananNonPriority($pesanan);
            if ($data->num_rows() > 0 || $data_lain->num_rows() > 0) {
                // PROSES LOAD PRIORITY PESANAN
                if ($data->num_rows() > 0) {
                    $urutan = 0;
                    foreach ($data->result() as $key) {
                        $data_pesanan[$urutan] = $this->GET_PESANAN($key->id_pemesanan);
                        $data_pesanan[$urutan]['detail_pembeli'] = $this->Pembeli->detail_pembeli($key->id_pb)->row_array();
                        $id1 = str_replace("-", "", $key->waktu_pemesanan);
                        $id2 = str_replace(" ", "", $id1);
                        $id3 = str_replace(":", "", $id2);
                        $id4 = $id3 . $key->id_pemesanan;
                        $data_pesanan[$urutan]['no_pesanan'] = $id4;
                        $urutan++;
                    }
                }
                // PROSES LOAD NON PRIORITY PESANAN
                if ($data_lain->num_rows() > 0) {
                    $urutan = 0;
                    foreach ($data_lain->result() as $key) {
                        $data_pesanan_lain[$urutan] = $this->GET_PESANAN($key->id_pemesanan);
                        $data_pesanan_lain[$urutan]['detail_pembeli'] = $this->Pembeli->detail_pembeli($key->id_pb)->row_array();
                        $id1 = str_replace("-", "", $key->waktu_pemesanan);
                        $id2 = str_replace(" ", "", $id1);
                        $id3 = str_replace(":", "", $id2);
                        $id4 = $id3 . $key->id_pemesanan;
                        $data_pesanan_lain[$urutan]['no_pesanan'] = $id4;
                        $urutan++;
                    }
                }

                $status_header = 200;
                $response['statusMessage'] = 'success';
                $response['data_pesanan_priority'] = $data_pesanan;
                $response['data_pesanan_non_priority'] = $data_pesanan_lain;
            } else {
                $status_header = 404;
                $response['statusMessage'] = 'failed';
                $response['data_pesanan_priority'] = $data_pesanan;
                $response['data_pesanan_non_priority'] = $data_pesanan_lain;
            }
        } catch (Exception $e) {
            $status_header = 500;
            $response['statusMessage'] = 'error';
            $response['data_pesanan_priority'] = $data_pesanan;
        }
        response($status_header, $response);
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
            $data = $this->Pemesanan->getPesananNonPriority($pesanan);
            if ($data->num_rows() > 0) {
                $urutan = 0;
                foreach ($data->result() as $key) {
                    $data_pesanan[$urutan] = $this->GET_PESANAN($key->id_pemesanan);
                    $data_pesanan[$urutan]['detail_pembeli'] = $this->Pembeli->detail_pembeli($key->id_pb)->row_array();
                    $id1 = str_replace("-", "", $key->waktu_pemesanan);
                    $id2 = str_replace(" ", "", $id1);
                    $id3 = str_replace(":", "", $id2);
                    $id4 = $id3 . $key->id_pemesanan;
                    $data_pesanan[$urutan]['no_pesanan'] = $id4;
                    $urutan++;
                }
            }
            if (count($data_pesanan) > 0) {
                $status_header = 200;
                $response['statusMessage'] = 'success';
                $response['data_pesanan'] = $data_pesanan;
            } else {
                $status_header = 404;
                $response['statusMessage'] = 'failed';
                $response['data_pesanan'] = $data_pesanan;
            }
        } catch (Exception $e) {
            $status_header = 500;
            $response['statusMessage'] = 'error';
            $response['data_pesanan'] = $data_pesanan;
        }
        response($status_header, $response);
    }

    private function GET_PESANAN($ID_PESANAN)
    {
        $data_pesanan = $this->Pemesanan->getDataPemesananByID($ID_PESANAN);
        if ($data_pesanan->num_rows() > 0) {
            $response = $data_pesanan->row_array();
            $response['detail_pesanan'] = $this->GET_DETAIL_PEMESANAN_WITH_ID($ID_PESANAN)->row_array();
            return $response;
        } else {
            return false;
        }
    }

    public function procced_order_to_delivery()
    {
        // var_dump($this->input->post());
        $kurir = $this->input->post('id_kurir', TRUE);
        $kendaraan = $this->input->post('id_kendaraan', TRUE);
        $pesanan = $this->input->post('id_pemesanan', TRUE);
        $id_penjual = $this->input->post('id_pj', TRUE);
        $pesanan = json_decode($pesanan);
        try {
            // $data_penjual = $this->Usaha->data_profile($id_penjual);
            // if($data_penjual->num_rows() > 0){
            //     response(401, array('statusMessage' => "authentication is needed"));
            // }
            $data = $this->Pemesanan->getPesananPriority($pesanan);
            $data2 = $this->Pemesanan->getPesananNonPriority($pesanan);
            $data_pengiriman = array(
                'waktu_pengiriman' => date('Y-m-d H:i:s'),
                'id_pj' => $id_penjual,
                'id_kurir' => $kurir,
                'id_kendaraan' => $kendaraan
            );
            $setUpdatePemesanan = array("status_pemesanan"=> "Pengiriman");
            $updatePemesananDalamPengiriman = $this->Pemesanan->update_pemesanan_in_batch($pesanan, $setUpdatePemesanan);
            $insert_pengiriman = $this->Model_pengiriman->insert_pengiriman($data_pengiriman);
            if ($insert_pengiriman && $updatePemesananDalamPengiriman) {
                $id_pengiriman = $this->db->insert_id();
                if ($data->num_rows() > 0) {
                    $response['data_pengiriman'] = $this->save_detail_pengiriman($data, $id_pengiriman);
                    if (count($response) > 0) {
                        $status_header = 200;
                        $response['statusMessage'] = 'success';
                        // $response['data_pesanan'] = $data_pesanan;
                    } else {
                        $status_header = 404;
                        $response['statusMessage'] = 'failed';
                        // $response['data_pesanan'] = $data_pesanan;
                    }
                }

                if ($data2->num_rows() > 0) {
                    // $urutan = 0;
                    $response['data_pengiriman'] = $this->save_detail_pengiriman($data2, $id_pengiriman);
                }
                $response['id_pengiriman'] = intval($id_pengiriman);
            }
            if (count($response) > 0) {
                $status_header = 200;
                $response['statusMessage'] = 'success';
            } else {
                $status_header = 404;
                $response['statusMessage'] = 'failed';
                $response['data_pesanan'] = array();
            }
        } catch (Exception $e) {
            $status_header = 500;
            $response['statusMessage'] = 'failed';
            $response['data_pesanan'] = array();
        }
        response($status_header, $response);
    }

    protected function save_detail_pengiriman($data, $id_pengiriman)
    {
        $data_pesanan = array();
        foreach ($data->result() as $key) {
            $status_pengiriman = ($this->urutan_pengiriman == 1) ? "pengantaran" : "menunggu";
            $this->Pemesanan->detail_pemesan($key->id_pemesanan);
            $nama_penerima = $this->Pemesanan->nama_pemesan;
            $detail_pengiriman = array(
                'id_pengiriman' => $id_pengiriman,
                'id_pemesanan' => $key->id_pemesanan,
                'urutan' => $this->urutan_pengiriman,
                'status' => $status_pengiriman,
                'penerima' => $nama_penerima
            );
            $insert_detail_pengiriman = $this->Model_pengiriman->insert_detail_pengiriman($detail_pengiriman);
            if ($insert_detail_pengiriman) {
                $data_pesanan[$this->urutan_pemesanan] = array('data_pemesanan' => $this->GET_PESANAN($key->id_pemesanan), 'detail_pengiriman' => $detail_pengiriman);
                $this->urutan_pemesanan++;
                $this->urutan_pengiriman++;
            }
        }
        return $data_pesanan;
    }

    public function konfirmasi_pesanan_diambil()
    {
        $id_pesanan = intval($this->input->post('id_pesanan'));
        $PESANAN = new Model_pemesanan();
        try {
            //code...
            // $where_array = array('pemesanan.id_pemesanan' => $id_pesanan);
            $detail_pesanan = $PESANAN->getDetailPemesanan($id_pesanan, null);
            if ($detail_pesanan->num_rows() > 0) {
                $array_update_pesanan = array('status_pemesanan' => "Terkirim");
                $do_update = $PESANAN->update_pemesanan($id_pesanan, $array_update_pesanan);
                if ($do_update) {
                    response(200, array('status' => 'success', 'message' => "Berhasil update pesanan", 'id_pesanan' => $id_pesanan));
                } else {
                    response(400, array('status' => 'failed', 'message' => "Gagal update pesanan", 'id_pesanan' => $id_pesanan));
                }
            } else {
                response(404, array('status' => 'failed', 'message' => "Pesanan tidak ditemuka"));
            }
        } catch (\Throwable $th) {
            //throw $th;
            response(500, array('status' => 'failed', 'message' => "Error dengan " . $th->getMessage(), 'id_pesanan' => $id_pesanan));
        }
    }
}
