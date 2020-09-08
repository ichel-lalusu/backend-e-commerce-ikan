<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


// PRODUK API's ROUTE
$route['api/produk']['get'] 								= "Produk/getProdukDashboard";
$route['api/produk/add']['post'] 							= "Produk/prosesinput_produk";
$route['api/produk/update']['post'] 						= "Produk/prosesupdate_produk";
$route['api/produk/variasi/update']['post'] 				= "Produk/updateVariasiProdukV3";
$route['api/produk/aktifkan']['post'] 						= "Produk/aktifkan_produk";
$route['api/produk/non-aktifkan']['post'] 					= "Produk/hapus_data_produk";
$route['api/produk/search']['get'] 							= "Produk/cariProdukLike";
$route['api/produk/filtered']['get']						= "Produk/ambil_produk_penjual_by_id";
$route['api/produk/detail']['get']							= "Produk/detail_produk";
$route['api/produk/variasi']['get']							= "Produk/ambil_variasi_produk";
$route['api/produk/all-variasi']['get']						= "Produk/getAllVariasi";

// USER API's ROUTE
$route['api/user/signup/penjual']['post'] 					= "Penjual/prosessignuppenjual";
$route['api/user/signup/usaha']['post'] 					= "Penjual/prosessignupusaha";
$route['api/user/signup/pembeli']['post'] 					= "Pembeli/prosessignuppembeli";
$route['api/user/signup/rollback']['post'] 					= "Penjual/rollback";
$route['api/user/login']['post'] 							= "User/proseslogin";
$route['api/user/login-kurir']['post']						= "User/login_kurir";
$route['api/user/penjual/lokasi']['get']					= "Penjual/ambil_data_lokasi_penjual";
$route['api/user/penjual/kelompok-tani']['get'] 			= "Penjual/ambil_data_tani";

$route['api/user/penjual/kelompok-tani/update']['post'] 	= "Penjual/simpan_kel_tani";
$route['api/user/penjual/jam-pengiriman']['get'] 			= "Penjual/ambil_jam_pengiriman_usaha";
$route['api/user/penjual/jam-pengiriman/create']['post'] 	= "Penjual/simpan_jam_pengiriman_usaha";
$route['api/user/penjual/jam-pengiriman/detail']['get'] 	= "Penjual/ambil_jam_pengiriman_usaha_by_id";
$route['api/user/penjual/jam-pengiriman/update']['post'] 	= "Penjual/ubah_jam_pengiriman_usaha";
$route['api/user/penjual/jam-pengiriman/delete']['post'] 	= "Penjual/hapus_jam_pengiriman_usaha";
$route['api/user/penjual/detail']['get'] 					= "Penjual/ambil_data_profile";
$route['api/user/penjual/$id']['get']						= "Penjual/detail/$1";
$route['api/user/penjual/usaha/detail']['get']	 			= "Penjual/detail_usaha";
$route['api/user/penjual/usaha/detail-by-akun']['get']		= "Penjual/ambil_data_usaha_with_pj";
$route['api/user/penjual/update']['post']					= "Penjual/prosesUpdatePenjual";
$route['api/user/penjual/usaha/update']['post']				= "Penjual/prosesupdateprofileusaha";
$route['api/user/kurir']['get']								= "Kurir/ambilKurirByUsaha";
$route['api/user/kurir/detail']['get']						= "Kurir/ambilDetailKurir";
$route['api/user/kurir/create']['post']						= "Kurir/simpanKurir";
$route['api/user/kurir/update']['post']						= "Kurir/ubahKurir";
$route['api/user/kurir/delete']['post']						= "Kurir/hapusKurir";
$route['api/user/pembeli']['get'] 							= "Pembeli/detail_pembeli";
$route['api/user/pembeli/(:num)']['get']						= "Pembeli/detail/$1";
$route['api/user/pembeli/update-alamat']['post'] 			= "Pembeli/updateAlamat";
$route['api/user/pembeli/update']['post']					= "Pembeli/prosesupdatepembeli";
$route['api/user/penjual/kendaraan']['get']					= "Penjual/getKendaraanUsaha";
$route['api/user/penjual/kendaraan/create']['post']			= "Penjual/simpanKendaraanUsaha";
$route['api/user/penjual/kendaraan/detail']['get']			= "Penjual/get_detail_kendaraan_usaha";
$route['api/user/penjual/kendaraan/update']['post']			= "Penjual/UpdateKendaraanUsaha";
$route['api/user/penjual/kendaraan/delete']['post']			= "Penjual/hapusKendaraan";


// REKENING API's ROUTE
$route['api/rekening']['get']	 							= "Rekening/ambil_rekening";
$route['api/rekening-usaha']['get']							= "Rekening/ambil_rekening_usaha";
$route['api/rekening/detail']['get'] 						= "Rekening/ambil_rekening_by_id";
$route['api/rekening/add']['post'] 							= "Rekening/simpan_rekening";
$route['api/rekening/update']['post'] 						= "Rekening/ubah_rekening";
$route['api/rekening/delete']['post'] 						= "Rekening/hapus_rekening";
$route['api/rekening/bank']['get']							= "Rekening/ambil_data_bank";

// 
$route['api/payment/upload']['post'] = "Pembayaran/ProsesUnggahBuktiPembayaran_Pemesanan";
$route['api/payment/rekening/detail/html']['post'] = "Pembayaran/pembayaran_ambil_rekening_html";
$route['api/payment/struk']['get'] = "Pemesanan/getStrukImage";
$route['api/payment/verify']['get'] = "Pemesanan/verifikasiPembayaranByPenjual";

// PEMBELI API's ROUTE


// PESANAN API's ROUTE
$route['api/pesanan/create']['post'] = "Pemesanan/simpanPemesanan";
$route['api/pesanan/penjual']['get'] = "Pemesanan/getAllTransaksiPenjual";
$route['api/pesanan/pembeli']['get'] = "Pemesanan/getAllPemesananByAkun";
$route['api/pesanan/produk-to-delivery']['get'] = "Pemesanan/get_pesanan_siap_kirim";
$route['api/pesanan/produk-to-verify']['get'] = "Pemesanan/get_produk_to_varify";
$route['api/pesanan/verify-produk-weight']['post'] = "Pemesanan/simpan_verifikasi_berat_produk";
$route['api/pesanan/detail-transaksi-by-id']['get'] = "Pemesanan/getTransaksiByIdPemesanan";
$route['api/pesanan/detail-pesanan-by-id']['get'] = "Pemesanan/getDataPemesananByID";
$route['api/pesanan/detail-in-html']['post'] = "Pemesanan/getDetailPemesanan_HTML";
$route['api/pesanan/detail-with-payment/(:num)'] = "Pemesanan/getPemesananWithPembayaran/$1";
$route['api/pesanan/complete']['post']						= "Pemesanan/PemesananSelesai";
$route['api/pesanan/pesanan-priority']['post']					="Pemesanan/getPesananPriority";
$route['api/pesanan/pesanan-non-priority']['post']			= "Pemesanan/getPesananNonPriority";

$route['api/pengiriman']['get']				= "Pengiriman/get_pengiriman";
$route['api/track']['get']			= "Pengiriman/track_pengiriman_pesanan";

$route['api/keranjang']['get']					= "Keranjang";
$route['api/keranjang']['post']					= "Keranjang/simpan_keranjang";
$route['api/keranjang/update']['post']			= "Keranjang/ubah_keranjang";
$route['api/keranjang/delete']['get']			= "Keranjang/delete_keranjang";



$route['admin'] = "admin/Admin";
$route['admin/Usaha/detail_transaksi/(:num)'] = 'admin/Pemesanan/detail/Usaha/$1';
$route['admin/Pembeli/detail_pesanan/(:num)'] = 'admin/Pemesanan/detail/Pembeli/$1';

$route['admin/Pemesanan/(:any)'] = 'admin/Pemesanan/$1';

$route['api/kendaraan']		= "Kendaraan/index";
$route['api/penjual']['get']		= "Penjual/all_penjual";
