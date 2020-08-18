<?php
/**
 * 
 */
class Pemesanan extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata("username")){
			redirect(base_url('admin/User/login'));
		}
		$this->load->model("admin/Model_pemesanan");
		$this->load->model("admin/Model_usaha", "usaha");
	    $this->load->model("admin/Model_pembeli", "Pembeli");
	    $this->load->model("admin/Model_kurir", "Kurir");
	    $this->load->model("admin/Model_pengiriman", "Pengiriman");
		$this->load->model("admin/Model_penjual", "Penjual");
	    $this->load->model("admin/Model_produk", "produk");
	    $this->load->model("admin/Modal_Pembayaran", "Pembayaran");
	    $this->url_API = "http://localhost/backendikan/";
		// $this->load->model("")
	}

	// public function Detail($id_pemesanan)
	// {
	// 	# code...
	// }

	public function lacak()
	{
		$id_pesanan = $this->input->get("id_pesanan");
		if(!empty($this->input->get("menu"))){
			$menu = $this->input->get("menu");
		}else{
			exit("Tidak Diizinkan");
		}
		
	}

	public function detail($menu, $id_pemesanan)
	{
		// echo $menu;
		if($menu=="Usaha"||$menu=="Pembeli"){
			$this->detail_pesanan($menu, $id_pemesanan);
		}elseif($menu=="Pembeli"){
			$this->detail_for_pembeli($id_pemesanan);
		}else{
			exit();
		}
	}

	private function detail_pesanan($menu, $id_pemesanan)
	{
		$where = "id_pemesanan = '$id_pemesanan'";
		$select_pesanan = "`id_pemesanan`, `waktu_pemesanan`, `tipe_pengiriman`, `tgl_pengiriman`, `jarak`, `biaya_kirim`, `total_harga`, `status_pemesanan`, `id_pb`, `id_usaha`";
		$data_pesanan = $this->Model_pemesanan->get_where($select_pesanan, $where, NULL, NULL, 1)->row();

		$select_detail_pesanan = "`id_dp`, `harga`, `jml_produk`, `sub_total`, `id_pemesanan`, `id_produk`, `berat_akhir`";
		$data_detail_pesanan = $this->Model_pemesanan->get_detail_where($select_detail_pesanan, $where);

		$select_pembayaran = "`id_pembayaran`, `metode_pembayaran`, `expiredDate`, `waktu_pembayaran`, `kode_bank`, `no_rekening_pb`, `nama_rekening_pb`, `struk_pembayaran`, `status_pembayaran`, `id_pemesanan`, `verifikasi`";
		$data_pembayaran = $this->Pembayaran->get_where($select_pembayaran, $where, NULL, NULL, 1)->row();
		
	    $select_sub_total_produk_all= "SUM(harga*(jml_produk/10)) AS TOTAL_HARGA_PRODUK";
	    $TOTAL_HARGA_PESANAN = $this->Model_pemesanan->get_detail_where($select_sub_total_produk_all, $where)->row()->TOTAL_HARGA_PRODUK;

	    $select_tanggal = "DATE(waktu_pemesanan) as tanggal";
		$data_tanggal = $this->Model_pemesanan->get_where($select_tanggal, $where, NULL, NULL, 1);
		if($data_tanggal->num_rows() > 0){
			$TANGGAL = $data_tanggal->row()->tanggal;
		}

	    $id_usaha = $data_pesanan->id_usaha;
	    $where_usaha = "id_usaha = '$id_usaha'";
	    $id_pb = $data_pesanan->id_pb;
	    $where_pembeli = "id_pb = '$id_pb'";

		// $data_penjual = $this->Penjual->get_where("*", $where_penjual, NULL, NULL, 1)->row();
		$select_usaha = " `id_usaha`, `nama_usaha`, `foto_usaha`, `alamat_usaha`, `jamBuka`, `jamTutup`, `jml_kapal`, `kapasitas_kapal`, `jml_kolam`, `kab`, `kec`, `kel`, `longitude`, `latitude`, `id_pj`";
		$data_usaha = $this->usaha->get_where($select_usaha, $where_usaha, NULL, NULL, 1)->row();
		
		$select_penjual = " `id_pj`, `nama_pj`, `foto_pj`, `noktp_pj`, `fotoktp_pj`, `jk_pj`, `tgllahir_pj`, `alamat_pj`, `telp_pj`, `jenis_petani`";
	    $id_penjual  =$data_usaha->id_pj;
	    $where_penjual = "id_pj = '$id_penjual'";
	    $data_penjual = $this->Penjual->get_where($select_penjual, $where_penjual, NULL, NULL, 1)->row();
	    
	    $data_pembeli = $this->Pembeli->get_where($where_pembeli)->row();

	    $id1 = str_replace("-","",$data_pesanan->waktu_pemesanan);
	    $id2 = str_replace(" ", "", $id1);
	    $id3 = str_replace(":", "", $id2);
	    $id4 = $id3 . $id_pemesanan;

	    $data_page = array(
	                'title' => 'Detail Transaksi ', 
	                'menu' => 'penjual', 
	                'url_API' => $this->url_API, 
	                'page' => 'Detal Transaksi', 
	                'id_pemesanan' => $id_pemesanan,
	                'id_usaha' => $id_usaha,
	                'data_pesanan' => $data_pesanan,
	                'data_detail_pesanan' => $data_detail_pesanan,
	                'TOTAL_HARGA_PESANAN' => $TOTAL_HARGA_PESANAN,
	                'data_pembeli' => $data_pembeli,
	                'no_pesanan' => $id4,
	                'data_usaha' => $data_usaha,
	                'TANGGAL'=>$TANGGAL,
	                'data_pembayaran' => $data_pembayaran,
	                'data_penjual' => $data_penjual);
	    if($menu=="Usaha")
		    $this->load->view("admin/Penjual/Transaksi/detail-transaksi", $data_page);
	 	elseif($menu=="Pembeli")
	 		$this->load->view("admin/Pembeli/Pesanan/detail-Pesanan", $data_page);
	}

	private function detail_for_pembeli($id_pemesanan)
	{
		# code...
	}
	
	public function pesanan(String $type='')
	{
		if($type=="selesai"){
			return $this->pesanan_selesai();
		}elseif ($type=="on_delivery") {
			return $this->pesanan_on_delivery();
		}else{
			redirect(base_url('admin'));
		}
	}

	protected function pesanan_selesai()
	{
		# code...
	}

	protected function pesanan_on_delivery()
	{
		# code...
	}

}