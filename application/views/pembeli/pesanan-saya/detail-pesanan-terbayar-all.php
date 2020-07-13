<?php
     $datePemesanan = $DataPemesanan->row()->waktu_pemesanan;
     $datePengirimanDiajukan = $DataPemesanan->row()->tgl_pengiriman;
     $datePembayaran = $DataPembayaran->row()->waktu_pembayaran;
     
     //create Date
     $newDatePemesanan = date_create($datePemesanan);
     $newDatePembayaran = date_create($datePembayaran);
     $newDatePnegirimanDiajukan = date_create($datePengirimanDiajukan);
     //Format Date & Time Pemesanan
     $FormatedDatePemesanan = date_format($newDatePemesanan, 'd M Y');
     $FormatedTimePemesanan = date_format($newDatePemesanan, 'H:i');
     $DisplayWaktuPemesanan = $FormatedDatePemesanan . '&nbsp;' . $FormatedTimePemesanan;
     //Format Date & Time Pembayaran
     $FormatedDatePembayaran = date_format($newDatePembayaran,'d M Y');
     $FormatedTimePembayaran = date_format($newDatePembayaran,"H:i");
     $DisplayWaktuPembayaran = $FormatedDatePembayaran . '&nbsp;' . $FormatedTimePembayaran;
     //Format Date & Time Pengiriman Diajukan
    //  if($DataPengiriman!==NULL){
        $FormatedDatePengirimannDiajukan = date_format($newDatePnegirimanDiajukan, 'd M Y');
        $DisplayWaktuPengirimanDiajukan = $FormatedDatePengirimannDiajukan ;
    //  }
     
    if($JenisPengiriman=="Cepat" || $JenisPengiriman=="Biasa"){
        // echo "Masuk \"Kirim\"";
        if($JenisPembayaran=="Full Transfer"){
        ?>
        <div class="container-fluid">
            <div class="row" style="margin-bottom: -10px">
            <div class="col s12">
                <div class="card">
                <ul class="collection">
                    <li class="collection-item teal-text darken-1">
                    <b>STATUS PEMESANAN<span class="secondary-content badge green white-text" style="font-size: 15px; border-radius: 8px;">DIKEMAS</span></b>
                    </li>
                </ul>
                </div>
                
                <div class="card">
                <ul class="collection">
                    <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="secondary-content NoPesanan">Loading</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Waktu Pemesanan :<span class="secondary-content WaktuPemesanan">Loading</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Waktu Pembayaran :<span class="secondary-content WaktuPembayaran">31/01/2020 23:30</span></b></li>
                </ul>
                </div>
                
                <div class="card">
                <ul class="collection collection-product">Loading..</ul>
                </div>

                <div class="card">
                <ul class="collection">
                    <li class="collection-item"><h5>DETAIL PENGIRIMAN</h5></li>
                    <li class="collection-item">Alamat Pengiriman :<span class="secondary-content alamat">Jl. Rw Bahagia No. 14</span></li>
                    <li class="collection-item">Jenis Pengiriman :<span class="secondary-content">Biasa</span></li>
                    <li class="collection-item">Tanggal Pengiriman yang Diajukan :<span class="secondary-content tanggal-pengiriman-diajukan">02/01/2020</span></li>
                    <li class="collection-item teal-text darken-1"><b>Total Biaya Pengiriman :<span class="secondary-content biaya-pengiriman">Rp</span>
                    <p class="total-berat" style="margin-top: 0px; margin-bottom: 0px">(xxx ons)</p></b></li>
                    <li class="collection-item"><button class="waves-effect waves-light btn blue" type="button" style="width: 100%">Lacak Pengiriman</button></li>
                </ul>
                </div>

                <div class="card">
                <ul class="collection">
                    <li class="collection-item"><h5>DETAIL PEMBAYARAN</h5></li>
                    <li class="collection-item">Metode Pembayaran :<span class="badge">Bayar Penuh Transfer</span></li>
                    <li class="collection-item teal-text darken-1"><b>Total Harga Produk :<span class="badge total-harga-produk teal-text darken-1">Rp</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Total Biaya Pengiriman :<span class="badge biaya-pengiriman teal-text darken-1">Rp</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Total Pembayaran :<span class="badge total-pembayaran teal-text darken-1">Rp</span></b></li>
                </ul>
                </div>

                <div class="card">
                <div class="card-content red darken-1 white-text">
                    <p class="flow-text center-align" style="font-size: medium">
                        Harap menunggu pesanan dikirimkan ke alamat Anda</p>
                </div>
                </div>
            </div>
            </div>
        </div>
        <?php
        }else if($JenisPembayaran=="Transfer Cash"){
        ?>
        <div class="container-fluid">
            <div class="row" style="margin-bottom: 0px">
            <div class="col s12">
                <div class="card">
                <ul class="collection">
                    <li class="collection-item teal-text darken-1">
                    <b>STATUS PEMESANAN<span class="secondary-content badge green status-pemesanan white-text" style="font-size: 15px; border-radius: 8px;">DIKEMAS</span></b>
                    </li>
                </ul>
                </div>

                <div class="card">
                <ul class="collection">
                    <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="secondary-content NoPesanan">310120181025</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Waktu Pemesanan :<span class="secondary-content WaktuPemesanan">31/01/2020 23:00</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Waktu Pembayaran :<span class="secondary-content WaktuPembayaran">31/01/2020 23:00</span></b></li>
                </ul>
                </div>

                <div class="card">
                <ul class="collection collection-product"></ul>
                </div>

                <div class="card">
                <ul class="collection">
                    <li class="collection-item"><h5>DETAIL PENGIRIMAN</h5></li>
                    <li class="collection-item">Alamat Pengiriman :<span class="secondary-content alamat">Jl. Rw Bahagia No. 14</span></li>
                    <li class="collection-item">Jenis Pengiriman :<span class="secondary-content tipe-pengiriman">Biasa</span></li>
                    <li class="collection-item">Tanggal Pengiriman yang Diajukan :<span class="secondary-content tanggal-pengiriman-diajukan">02/01/2020</span></li>
                    <li class="collection-item teal-text darken-1"><b>Total Biaya Pengiriman :<span class="secondary-content biaya-pengiriman">Rp</span>
                    <p class="total-berat" style="margin-top: 0px; margin-bottom: 0p">(xxx onxs)</p></b></li>
                    <li class="collection-item"><button class="waves-effect waves-light btn blue" type="button" style="width: 100%">Lacak Pengiriman</button></li>
                </ul>
                </div>

                <div class="card">
                <ul class="collection">
                    <li class="collection-item"><h5>DETAIL PEMBAYARAN</h5></li>
                    <li class="collection-item">Metode Pembayaran :<span class="secondary-content">Transfer dan Tunai</span></li>
                    <li class="collection-item teal-text darken-1"><b>Total Harga Produk :<span class="secondary-content total-harga-produk">Rp</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Total Biaya Pengiriman :<span class="secondary-content biaya-pengiriman">Rp</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Total Pembayaran :<span class="secondary-content total-pembayaran">Rp</span></b></li>
                </ul>
                </div>

                <div class="card">
                    <ul class="collection">
                    <li class="collection-item teal-text darken-1"><b>Pembayaran Awal :<span class="secondary-content minimal-transfer">Rp</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Sisa Tagihan :<span class="secondary-content sisa-tagihan">Rp</span></b></li>
                    </ul>
                </div>

                <div class="card">
                <div class="collection red darken-1 white-text">
                    <p class="flow-text center-align" style="font-size: medium">
                    Silahkan melakukan pembayaran sebesar<br><b><a class="waves-effect waves-light modal-trigger white-text" href="#modal2">SISA TAGIHAN</a></b><br>
                    secara langsung ke 
                    <b>KURIR</b></p>
                </div>
                </div>

                <div id="modal2" class="modal bottom-sheet">
                <div class="modal-content">
                    <h6 class="modal-title center-align">Sisa Tagihan:</h6>
                    <h5 class="sisa-tagihan center-align red-text"></h5>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-close waves-effect waves-light btn red accent-2">Tutup</a>
                </div>
                </div>
            </div>
            </div>
        </div>
        <?php
        }
    }else if($JenisPengiriman=="Ambil di Toko"){
      if($JenisPembayaran=="Full Transfer"){
        ?>
        <div class="container-fluid">
            <div class="row" style="margin-bottom: 0px">
            <div class="col s12">
                <div class="card">
                <ul class="collection">
                    <li class="collection-item teal-text darken-1">
                    <b>STATUS PEMESANAN<span class="secondary-content badge green white-text" style="font-size: 15px; border-radius: 8px;">DIKEMAS</span></b>
                    </li>
                </ul>
                </div>

                <div class="card">
                <ul class="collection">
                    <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="secondary-content NoPesanan">310120181025</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Waktu Pemesanan :<span class="secondary-content WaktuPemesanan">31/01/2020 23:00</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Waktu Pembayaran :<span class="secondary-content WaktuPembayaran">31/01/2020 23:30</span></b></li>
                </ul>
                </div>

                <div class="card">
                <ul class="collection collection-product"></ul>
                </div>

                <div class="card">
                <ul class="collection">
                    <li class="collection-item"><h5>DETAIL PENGIRIMAN</h5></li>
                    <li class="collection-item">Jenis Pengiriman :<span class="secondary-content">Ambil di Toko</span></li>
                    <li class="collection-item">Tanggal Pengambilan :<span class="secondary-content tanggal-pengiriman-diajukan">02/01/2020</span></li>
                </ul>
                </div>

                <div class="card">
                <ul class="collection">
                    <li class="collection-item"><h5>DETAIL PEMBAYARAN</h5></li>
                    <li class="collection-item">Metode Pembayaran :<span class="secondary-content">Bayar Penuh Transfer</span></li>
                    <li class="collection-item teal-text darken-1"><b>Total Harga Produk :<span class="secondary-content total-harga-produk">Rp</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Total Pembayaran :<span class="secondary-content total-pembayaran">Rp</span></b></li>
                </ul>
                </div>

                <div class="card">
                <div class="card-content red darken-1 white-text">
                    <p class="flow-text center-align" style="font-size: medium">
                        Silahkan ambil produk sesuai
                        <b><a class="waves-effect waves-light modal-trigger white-text" href="#modal1">TANGGAL PENGAMBILAN</a></b></p>
                </div>
                </div>

                <!-- Modal Structure -->
                <div id="modal1" class="modal bottom-sheet">
                <div class="modal-content">
                    <h6 class="modal-title center-align">Tanggal Pengambilan:</h6>
                    <h5 class="tanggal-pengiriman-diajukan center-align red-text"></h5>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-close waves-effect waves-light btn red accent-2">Tutup</a>
                </div>
                </div>
            </div>
            </div>
        </div>
        <?php
      }else if($JenisPembayaran=="Full Cash"){
        ?>
        <div class="container-fluid">
            <div class="row" style="margin-bottom: -30px">
            <div class="col s12">
                <div class="card">
                <ul class="collection">
                    <li class="collection-item teal-text darken-1">
                    <b>STATUS PEMESANAN <span class="secondary-content badge green white-text" style="font-size: 15px; border-radius: 8px">DIKEMAS</span></b>
                    </li>
                </ul>
                </div>
                
                <div class="card">
                <ul class="collection">
                    <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="secondary-content NoPesanan">310120181025</span></b></li>
                    <li class="collection-item">Waktu Pemesanan :<span class="secondary-content WaktuPemesanan">31/01/2020 23:00</span></li>
                    <li class="collection-item">Waktu Pembayaran :<span class="secondary-content WaktuPembayaran">31/01/2020 23:30</span></li>
                </ul>
                </div>

                <!-- <a class="collection-item" href="#!"><h6 class="nama-toko">Nama Toko (Link Toko)</h6></a> -->
                <div class="card">
                <ul class="collection collection-product"></ul>
                </div>
                <div class="row">
                <div class="col s12">
                    <div class="card">
                        <div class="collection">
                        <ul class="collection-header" style="padding-left: 10px"><h5>DETAIL PENGIRIMAN</h5></ul>
                        <ul class="collection-item">Jenis Pengiriman :<span class="badge tipe-pengiriman">Ambil di Toko</span></ul>
                        <ul class="collection-item">Tanggal Pengambilan :<span class="badge tanggal-pengiriman-diajukan">02/01/2020</span></ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: -30px; margin-bottom: -10px">
                <div class="col s12">
                <div class="card">
                    <ul class="collection">
                    <li class="collection-item"><h5>DETAIL PEMBAYARAN</h5></ul>
                    <li class="collection-item">Metode Pembayaran :<span class="badge tipe-pembayaran">Bayar Penuh Tunai</span></ul>
                    <li class="collection-item teal-text darken-1"><b>Total Harga Produk :<span class="badge total-harga-produk">Rp</span></b></li>
                    <li><b><a class="collection-item" style="margin-bottom: -5px">Total Pembayaran :<span class="badge total-pembayaran">Rp</span></b>
                    </a>  
                    </li>
                </div>
                </div>
            </div>
            </div>
            <div class="card">
                <div class="card-content red darken-1 white-text">
                    <p class="flow-text center-align" style="font-size: medium">
                        Silahkan ambil produk sesuai
                        <b><a class="waves-effect waves-light modal-trigger white-text" href="#modal1">TANGGAL PENGAMBILAN</a></b>
                        dan melakukan pembayaran sebesar <b><a class="waves-effect waves-light modal-trigger white-text" href="#modal2">SISA TAGIHAN</a></b>
                        secara langsung ke 
                        <b>PENJUAL</b></p>
                </div>
                </div>

                <!-- Modal Structure -->
                <div id="modal1" class="modal bottom-sheet">
                <div class="modal-content">
                    <h6 class="modal-title center-align">Tanggal Pengambilan:</h6>
                    <h5 class="tanggal-pengiriman-diajukan center-align red-text"></h5>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-close waves-effect waves-light btn red accent-2">Tutup</a>
                </div>
                </div>

                <div id="modal2" class="modal bottom-sheet">
                <div class="modal-content">
                    <h6 class="modal-title center-align">Sisa Tagihan:</h6>
                    <h5 class="sisa-tagihan center-align red-text"></h5>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-close waves-effect waves-light btn red accent-2">Tutup</a>
                </div>
                </div>
        </div>
        <?php
      }else if($JenisPembayaran=="Transfer Cash"){
        ?>
        <div class="container-fluid">
            <div class="row" style="margin-bottom: -30px">
            <div class="col s12">
                <div class="card">
                <ul class="collection">
                    <li class="collection-item teal-text darken-1"><b>STATUS PEMESANAN<span class="secondary-content badge green white-text"  style="font-size: 15px; border-radius: 8px;">DIKEMAS</span></b></li>
                </ul>
                </div>

                <div class="card">
                <ul class="collection">
                    <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="secondary-content NoPesanan">310120181025</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Waktu Pemesanan :<span class="secondary-content WaktuPemesanan">31/01/2020 23:00</span></b></li>
                </ul>
                </div>

                <div class="card">
                <ul class="collection collection-product">
                </div>

                <div class="card">
                <ul class="collection">
                    <li class="collection-item"><h5>DETAIL PENGIRIMAN</h5></li>
                    <li class="collection-item">Jenis Pengiriman :<span class="secondary-content">Ambil di Toko</span></li>
                    <li class="collection-item">Tanggal Pengambilan :<span class="secondary-content tanggal-pengiriman-diajukan">02/01/2020</span></li>
                </ul>
                </div>

                <div class="card">
                <ul class="collection">
                    <li class="collection-item"><h5>DETAIL PEMBAYARAN</h5></li>
                    <li class="collection-item">Metode Pembayaran :<span class="secondary-content">Transfer dan Tunai</span></li>
                    <li class="collection-item teal-text darken-1"><b>Total Harga Produk :<span class="secondary-content total-harga-produk">Rp</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Total Pembayaran :<span class="secondary-content total-pembayaran">Rp</span></b></li>
                </ul>
                </div>
                
                <div class="card">
                <ul class="collection">
                    <li class="collection-item teal-text darken-1"><b>Pembayaran Awal :<span class="secondary-content minimal-transfer">Rp</span></b></li>
                    <li class="collection-item teal-text darken-1"><b>Sisa Tagihan :<span class="secondary-content sisa-tagihan">Rp</span></b></li>
                </ul>
                </div>

                <div class="card">
                <div class="card-content red darken-1 white-text">
                    <p class="flow-text center-align" style="font-size: medium">
                        Silahkan ambil produk sesuai
                        <b><a class="waves-effect waves-light modal-trigger white-text" href="#modal1">TANGGAL PENGAMBILAN</a></b>
                        dan melakukan pembayaran sebesar <b><a class="waves-effect waves-light modal-trigger white-text" href="#modal2">SISA TAGIHAN</a></b>
                        secara langsung ke 
                        <b>PENJUAL</b></p>
                </div>
                </div>

                <!-- Modal Structure -->
                <div id="modal1" class="modal bottom-sheet">
                <div class="modal-content">
                    <h6 class="modal-title center-align">Tanggal Pengambilan:</h6>
                    <h5 class="tanggal-pengiriman-diajukan center-align red-text"></h5>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-close waves-effect waves-light btn red accent-2">Tutup</a>
                </div>
                </div>

                <div id="modal2" class="modal bottom-sheet">
                <div class="modal-content">
                    <h6 class="modal-title center-align">Sisa Tagihan:</h6>
                    <h5 class="sisa-tagihan center-align red-text"></h5>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-close waves-effect waves-light btn red accent-2">Tutup</a>
                </div>
                </div>
            </div>
        </div>
        </div>
        <?php
      }
    }else{
        echo "Failed";
    }
?>
<script type="text/javascript">
  $('.modal').modal();
  var DetailPesanan = storage.getItem("DetailPesanan");
  DetailPesanan = JSON.parse(DetailPesanan);
  $(document).ready(function(){
    var StatusPemesanan = storage.getItem("status");
    var IDPESANAN = DetailPesanan.ID;
    var TotalHargaAll = DetailPesanan.TotalHargaAll;
    var MinTransfer = TotalHargaAll * (30/100);
    var SisaTagihan = TotalHargaAll - MinTransfer;
    var BiayaPengiriman = DetailPesanan.BiayaPengiriman;
    var AllPurchaseProduk = DetailPesanan.AllPurchaseProduk;
    var waktuPemesanan = DetailPesanan.waktuPemesanan;
    var tglPengiriman = DetailPesanan.tglPengiriman;
    var TotalHargaProduk = DetailPesanan.TotalHargaProduk/10;
    var DataUsaha = DetailPesanan.DataUsaha;
    var DataPembeli = DetailPesanan.DataPembeli;
    var Alamat = DataPembeli.alamat_pb;
    var nama_usaha = DataUsaha.nama_usaha;
    var IdUsaha = DataUsaha.id_usaha;
    var JenisPengiriman = DetailPesanan.JenisPengiriman;
    var TanggalPengiriman = DetailPesanan.tglPengiriman;
    var TotalProdukPesanan = DetailPesanan.TotalProduk;
    var TotalBeratProduk = DetailPesanan.TotalBeratProduk/10;
    console.log("TotalBeratProduk : " + TotalBeratProduk);
    var BiayaPengiriman = DetailPesanan.BiayaPengiriman;
    var DataPembayaran = DetailPesanan.DataPembayaran;
    var metode_pembayaran = DataPembayaran.metode_pembayaran;
    if(storage.StatusPemesanan == "Terbayar"){
     $(".status-pemesanan").html("DIKEMAS");   
    }
    // $(".status-pemesanan").html(StatusPemesanan);
    $(".NoPesanan").html(IDPESANAN);
    $(".WaktuPemesanan").html("<?=$DisplayWaktuPemesanan?>");
    $(".WaktuPembayaran").html("<?=$DisplayWaktuPembayaran?>");
    
    var HtmlProduk = '';
    HtmlProduk = '<li class="collection-item"><a href=""><h6 class="nama-toko"></h6></a></li>'+
                  '<li class="collection-item"><h5>DAFTAR PRODUK</h5></li>';
    $.each(AllPurchaseProduk, function(i,isi){
      var FotoProduk = base_url+ '/foto_usaha/produk/' + isi.foto_produk;
      var NamaProduk = isi.nama_produk + ' ' + isi.nama_variasi;
      var HargaProduk = isi.harga;
      var TotalProduk = isi.jml_produk;
      HtmlProduk += '<li class="collection-item avatar"><img src="'+FotoProduk+'" alt="" class="circle">'+
              '<span class="title">'+NamaProduk+'</span>'+
              '<p class="orange-text"><b>Rp '+HargaProduk+'</b><br></p>'+
              '<span class="secondary-content">'+TotalProduk/10+'&nbsp Kg</span></li>';
    });
    HtmlProduk += '<li class="collection-item teal-text darken-1"><b>Total Harga Produk: <span class="secondary-content total-harga-produk"></span></b></li>';
    $(".collection-product").html(HtmlProduk);
    $(".nama-toko").html("<a href='#!' onclick='GoToDetailUsaha("+IdUsaha+")'>"+nama_usaha+"</a>");
    $(".alamat").html(Alamat);
    $(".tipe-pengiriman").html(JenisPengiriman);
    $(".tanggal-pengiriman-diajukan").html("<?=$DisplayWaktuPengirimanDiajukan;?>");
    $(".total-berat").html(TotalBeratProduk+"&nbsp; Kg");
    $(".biaya-pengiriman").html("Rp " + formatNumber(BiayaPengiriman));
    $(".tipe-pembayaran").html(metode_pembayaran);
    $(".total-harga-produk").html("Rp&nbsp;" + formatNumber(TotalHargaProduk));
    $(".total-pembayaran").html("Rp&nbsp;" + formatNumber(TotalHargaAll));
    $(".minimal-transfer").html("Rp&nbsp;" + formatNumber(MinTransfer));
    $(".sisa-tagihan").html("Rp&nbsp;" + formatNumber(SisaTagihan));
  });

  function LanjutkanPembayaran(){
    console.log("Lanjutkan Pembayaran");
    window.location.href = "pembayaran.html";
  }

  function BatalkanPesanan(){
    console.log("Batalkan Pesanan");
  }

  function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
  }
</script>