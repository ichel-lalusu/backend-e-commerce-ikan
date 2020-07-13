<?php
    $datePemesanan = $DataPemesanan->row()->waktu_pemesanan;
    //create Date
    $newDatePemesanan = date_create($datePemesanan);
    //Format Date & Time Pemesanan
    $FormatedDatePemesanan = date_format($newDatePemesanan, 'd M Y');
    $FormatedTimePemesanan = date_format($newDatePemesanan, 'H:i');
    $DisplayWaktuPemesanan = $FormatedDatePemesanan . '&nbsp;' . $FormatedTimePemesanan;
//}
    $PembayaranData = $DataPembayaran->row();
    // var_dump($PembayaranData);
  if($JenisPengiriman=="Cepat" || $JenisPengiriman=="Biasa"){
      if($JenisPembayaran=="Full Transfer"){
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col s12">
                    <div class="card">
                        <ul class="collection" id="collection-status-pemesanan">
                            
                        </ul>
                    </div>

                    <div class="card">
                        <ul class="collection">
                            <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="secondary-content NoPesanan"></span></b></li>
                            <li class="collection-item teal-text darken-1"><b>Waktu Pemesanan :<span class="secondary-content WaktuPemesanan"></span></b></li>
                        </ul>
                    </div>

                    <div class="card">
                        <ul class="collection collection-product">Loading..</ul>
                    </div>

                    <div class="card">
                        <ul class="collection">
                            <li class="collection-item"><h5>DETAIL PENGIRIMAN</h5></li>
                            <li class="collection-item">Alamat Pengiriman :<span class="secondary-content alamat"></span></li>
                            <li class="collection-item">Jenis Pengiriman :<span class="secondary-content tipe-pengiriman"></span></li>
                            <li class="collection-item">Tanggal Pengiriman :<span class="secondary-content tanggal-pengiriman"></span></li>
                            <li class="collection-item teal-text darken-1"><b>Total Biaya Pengiriman :<span class="secondary-content biaya-pengiriman"></span></b><br><span class="total-berat"></span></li>
                        </ul>
                    </div>

                    <div class="card">
                        <ul class="collection">
                            <li class="collection-item"><h5>DETAIL PEMBAYARAN</h5></li>
                            <li class="collection-item">Metode Pembayaran :<span class="secondary-content">Bayar Penuh Transfer</span></li>
                            <li class="collection-item">Total Harga Produk :<span class="secondary-content total-harga-produk"></span></li>
                            <li class="collection-item">Total Biaya Pengiriman :<span class="secondary-content biaya-pengiriman"></span></li>
                            <li class="collection-item teal-text darken-1"><b>Total Pembayaran :<span class="secondary-content total-pembayaran"></span></b></li>
                        </ul>
                    </div>
                    <?php
                    if($PembayaranData->status_pembayaran=="Lunas" && $PembayaranData->verifikasi=="0"){
                    ?>
                    <button class="waves-effect waves-light btn red accent-2 modal-trigger disabled" data-target="modal1" type="button" style="width: 100%; margin-top: 8px;">Batalkan Pesanan</button>
                    <?php
                    }else{
                    ?>
                    <button class="waves-effect waves-light btn blue" type="button" style="width: 100%" onclick="LanjutkanPembayaran()">Lanjutkan Ke Pembayaran</button>
                    <button class="waves-effect waves-light btn red accent-2 modal-trigger" data-target="modal1" type="button" style="width: 100%; margin-top: 8px;">Batalkan Pesanan</button>
                    <?php
                    }
                    ?>
                    

                    <div id="modal1" class="modal large">
                        <div class="modal-content">
                            <h5 class="modal-title">Pembatalan Pesanan</h5>
                            <p>Apakah Anda Yakin Ingin Membatalkan Pesanan ini?</p>
                        </div>
                        <div class="modal-footer">
                            <a href="#!" class="modal-close waves-effect waves-red btn transparent black-text" onclick="BatalkanPesanan()">Ya! Batalkan</a>
                            <a href="#!" class="modal-close waves-effect waves-teal btn teal darken-1">Tidak</a>
                        </div>
                    </div>
                    <!-- <div id="modal2" class="modal bottom-sheet">
                        <div class="modal-content" style="height: 540px;">
                            <h5 class="center-align">Pembatalan Pemesanan</h5>
                            <div class="center-align" style="margin-top: 128px;">
                                <i class="material-icons teal-text darken-1" style="font-size: 80px;">check_circle</i>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div id="modal3" class="modal bottom-sheet">
                        <div class="modal-content" style="height: 540px;">
                            <h5 class="center-align">Pembatalan Pemesanan</h5>
                            <div class="center-align" style="margin-top: 128px;">
                                <i class="material-icons red-text darken-1" style="font-size: 80px;">close</i>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        <?php
    }else if($JenisPembayaran=="Transfer Cash"){
        ?>
        <div class="container-fluid">
            <div class="row" style="margin-bottom: 8px;">
                <div class="col s12">
                    <div class="card">
                        <ul class="collection" id="collection-status-pemesanan">
                            <li class="collection-item">
                                <b class="teal-text darken-1">STATUS PEMESANAN<span class="secondary-content badge white-text status-pemesanan" style="text-transform: uppercase; font-size: 15px; border-radius: 8px;"></span></b>
                            </li>
                        </ul>
                    </div>
                    <div class="card">
                        <ul class="collection">
                            <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="secondary-content NoPesanan">310120181025</span></b></li>
                            <li class="collection-item teal-text darken-1"><b>Waktu Pemesanan :<span class="secondary-content WaktuPemesanan">31/01/2020 23:00</span></b></li>
                        </ul>
                    </div>
                    <div class="card">
                        <ul class="collection collection-product"></ul>
                    </div>
                    <div class="card">
                        <ul class="collection">
                            <li class="collection-item"><h5>DETAIL PENGIRIMAN</h5></li>
                            <li class="collection-item">Alamat Pengiriman :<span class="secondary-content alamat"></span></li>
                            <li class="collection-item">Jenis Pengiriman :<span class="secondary-content tipe-pengiriman">Biasa</span></li>
                            <li class="collection-item">Tanggal Pengiriman :<span class="secondary-content tanggal-pengiriman">02/01/2020</span></li>
                            <li class="collection-item teal-text darken-1"><b>Total Biaya Pengiriman :<span class="secondary-content biaya-pengiriman">Rp</span></b><br><span class="total-berat"></span></li>
                            <!-- <li class="collection-item" style="">(xxx ons)</p></b> -->
                                <!-- </a> -->
                            </ul>
                        </div>
                        <div class="card">
                            <ul class="collection">
                                <li class="collection-item"><h5>DETAIL PEMBAYARAN</h5></li>
                                <li class="collection-item">Metode Pembayaran :<span class="secondary-content">Transfer dan Tunai</span></li>
                                <li class="collection-item">Total Harga Produk :<span class="secondary-content total-harga-produk">Rp</span></li>
                                <li class="collection-item">Total Biaya Pengiriman :<span class="secondary-content biaya-pengiriman">Rp</span></li>
                                <li class="collection-item teal-text darken-1"><b>Total Pembayaran :<span class="secondary-content total-pembayaran">Rp</span></b></li>
                            </ul>
                        </div>
                        <div class="card">
                            <ul class="collection">
                                <li class="collection-item teal-text darken-1"><b>Pembayaran Awal :<span class="secondary-content minimal-transfer">Rp</span></b></li>
                                <li class="collection-item teal-text darken-1"><b>Sisa Tagihan :<span class="secondary-content sisa-tagihan">Rp</span></b></li>
                            </ul>
                        </div>
                        <?php
                        if($PembayaranData->status_pembayaran=="DP" && $PembayaranData->verifikasi=="0"){
                        ?>
                        <button class="waves-effect waves-light btn red accent-2 modal-trigger disabled" data-target="modal1" type="button" style="width: 100%; margin-top: 8px;">Batalkan Pesanan</button>
                        <?php
                        }else{
                        ?>
                        <button class="waves-effect waves-light btn blue" type="button" style="width: 100%" onclick="LanjutkanPembayaran()">Lanjutkan Ke Pembayaran</button>
                        <button class="waves-effect waves-light btn red accent-2 modal-trigger" data-target="modal1" type="button" style="width: 100%; margin-top: 8px;">Batalkan Pesanan</button>
                        <?php
                        }
                        ?>
                        
                    </div>
                    <div id="modal1" class="modal large">
                        <div class="modal-content">
                            <h5 class="modal-title">Pembatalan Pesanan</h5>
                            <p>Apakah Anda Yakin Ingin Membatalkan Pesanan ini?</p>
                        </div>
                        <div class="modal-footer">
                            <a href="#!" class="modal-close waves-effect waves-red btn transparent black-text" onclick="BatalkanPesanan()">Ya! Batalkan</a>
                            <a href="#!" class="modal-close waves-effect waves-teal btn green accent-5">Tidak</a>
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
            <div class="row" style="margin-bottom: 8px">
                <div class="col s12">
                    <div class="card">
                        <ul class="collection" id="collection-status-pemesanan">
                            <li class="collection-item teal-text darken-1">
                                <b>STATUS PEMESANAN<span class="secondary-content badge white-text status-pemesanan" style="font-size: 15px; border-radius: 8px;">BARU</span></b>
                            </li>
                        </ul>
                    </div>

                    <div class="card">
                        <ul class="collection">
                            <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="secondary-content NoPesanan">310120181025</span></b></li>
                            <li class="collection-item teal-text darken-1"><b>Waktu Pemesanan :<span class="secondary-content WaktuPemesanan">31/01/2020 23:00</span></b></li>
                        </ul>
                    </div>

                    <div class="card">
                        <ul class="collection collection-product"></ul>
                    </div>

                    <div class="card">
                        <ul class="collection">
                            <li class="collection-item"><h5>DETAIL PENGIRIMAN</h5></li>
                            <li class="collection-item">Jenis Pengiriman :<span class="secondary-content">Ambil di Toko</span></li>
                            <li class="collection-item">Tanggal Pengambilan :<span class="secondary-content tanggal-pengiriman">02/01/2020</span></li>
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
                    <?php
                    if($PembayaranData->status_pembayaran=="Lunas" && $PembayaranData->verifikasi=="0"){
                    ?>
                    <button class="waves-effect waves-light btn red accent-2 modal-trigger disabled" data-target="modal1" type="button" style="width: 100%; margin-top: 8px;">Batalkan Pesanan</button>
                    <?php
                    }else{
                    ?>
                    <button class="waves-effect waves-light btn blue" type="button" style="width: 100%" onclick="LanjutkanPembayaran()">Lanjutkan Ke Pembayaran</button>
                    <button class="waves-effect waves-light btn red accent-2 modal-trigger" data-target="modal1" type="button" style="width: 100%; margin-top: 8px;">Batalkan Pesanan</button>
                    <?php
                    }
                    ?>
                    
                    <div id="modal1" class="modal small">
                        <div class="modal-content">
                            <h5 class="modal-title">Pembatalan Pesanan</h5>
                            <p>Apakah Anda Yakin Ingin Membatalkan Pesanan ini?</p>
                        </div>
                        <div class="modal-footer">
                            <button class="modal-close waves-effect waves-red btn transparent black-text" onclick="BatalkanPesanan()">Ya! Batalkan</button>
                            <button class="modal-close waves-effect waves-teal btn green accent-5">Tidak</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }else if($JenisPembayaran=="Full Cash"){
      ?>
      <div class="container-fluid">
        <div class="row" style="margin-bottom: 0px">
            <div class="col s12">
                <div class="card">
                    <ul class="collection" id="collection-status-pemesanan">
                        <li class="collection-item teal-text darken-1"><b>STATUS PEMESANAN<span class="secondary-content badge status-pemesanan white-text" style="text-transform: uppercase; font-size: 15px; border-radius: 8px;"></span></b></li>
                    </ul>
                </div>

                <div class="card">
                    <ul class="collection">
                        <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="secondary-content NoPesanan">310120181025</span></b></li>
                        <li class="collection-item teal-text darken-1"><b>Waktu Pemesanan :<span class="secondary-content WaktuPemesanan">31/01/2020 23:00</span></b></li>
                    </ul>
                </div>

                <div class="card">
                    <ul class="collection collection-product"></ul>
                </div>

                <div class="card">
                    <ul class="collection">
                        <li class="collection-item"><h5>DETAIL PENGIRIMAN</h5></li>
                        <li class="collection-item">Jenis Pengiriman :<span class="secondary-content tipe-pengiriman">Ambil di Toko</span></li>
                        <li class="collection-item">Tanggal Pengambilan :<span class="secondary-content tanggal-pengiriman">02/01/2020</span></li>
                    </ul>
                </div>

                <div class="card">
                    <ul class="collection">
                        <li class="collection-item"><h5>DETAIL PEMBAYARAN</h5></li>
                        <li class="collection-item">Metode Pembayaran :<span class="secondary-content">Bayar Penuh Tunai</span></li>
                        <li class="collection-item">Total Harga Produk :<span class="secondary-content total-harga-produk">Rp</span></li>
                        <li class="collection-item teal-text darken-1"><b>Total Pembayaran :<span class="secondary-content total-pembayaran">Rp</span></b></li>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-content red darken-1 white-text">
                        <p class="flow-text center" style="font-size: medium">Silahkan ambil produk sesuai <b><a class="waves-effect waves-light modal-trigger white-text" href="#modal1">TANGGAL PENGAMBILAN</a></b> dan melakukan pembayaran sebesar <b><a class="waves-effect waves-light modal-trigger white-text" href="#modal2">TOTAL PEMBAYARAN</a></b> secara langsung ke <b>Penjual</b></p>
                    </div>
                </div>

                <!-- Modal Structure -->
                <div id="modal1" class="modal bottom-sheet">
                    <div class="modal-content">
                        <h6 class="modal-title center-align">Tanggal Pengambilan:</h6>
                        <h5 class="tanggal-pengiriman center-align red-text"></h5>
                    </div>
                    <div class="modal-footer">
                        <a href="#!" class="modal-close waves-effect waves-light btn red accent-2">Tutup</a>
                    </div>
                </div>
                <div id="modal2" class="modal bottom-sheet">
                    <div class="modal-content">
                        <h6 class="modal-title center-align">Total Pembayaran:</h6>
                        <h5 class="total-pembayaran center-align red-text"></h5>
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
            <div class="row">
                <div class="col s12">
                    <div class="card">
                        <ul class="collection" id="collection-status-pemesanan">
                            <li class="collection-item teal-text darken-1"><b>STATUS PEMESANAN<span class="secondary-content badge status-pemesanan white-text" style="text-transform: uppercase; font-size: 15px; border-radius: 8px;"></span></b></li>
                        </ul>
                    </div>
                    <div class="card">
                        <ul class="collection">
                            <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="badge NoPesanan teal-text darken-4"></span></b></li>
                            <li class="collection-item teal-text darken-1"><b>Waktu Pemesanan :<span class="badge WaktuPemesanan teal-text darken-4"></span></b></li>
                        </ul>
                    </div>

                    <div class="card">
                        <ul class="collection collection-product"></ul>
                    </div>

                    <div class="card">
                        <ul class="collection">
                            <li class="collection-item"><h5>DETAIL PENGIRIMAN</h5></li>
                            <li class="collection-item">Jenis Pengiriman :<span class="secondary-content tipe-pengiriman"></span></li>
                            <li class="collection-item">Tanggal Pengambilan :<span class="secondary-content tanggal-pengiriman"></span></li>
                        </ul>
                    </div>
                    <div class="card">
                        <ul class="collection">
                            <li class="collection-item"><h5>DETAIL PEMBAYARAN</h5></li>
                            <li class="collection-item">Metode Pembayaran :<span class="secondary-content">Transfer dan Tunai</span></li>
                            <li class="collection-item">Total Harga Produk :<span class="secondary-content total-harga-produk"></span></li>
                            <li class="collection-item teal-text darken-1"><b>Total Pembayaran :<span class="secondary-content total-pembayaran"></span></b></li>
                        </ul>
                    </div>
                    <div class="card pembayaranDP">
                        <ul class="collection">
                            <li class="collection-item teal-text darken-1"><b>Pembayaran Awal :<span class="secondary-content minimal-transfer">Rp</span></b></li>
                            <li class="collection-item teal-text darken-1"><b>Sisa Tagihan :<span class="secondary-content sisa-tagihan">Rp</span></b></li>
                        </ul>
                    </div>
                    <?php
                    if($PembayaranData->status_pembayaran=="DP" && $PembayaranData->verifikasi=="0"){
                    ?>
                    <button class="waves-effect waves-light btn red accent-2 modal-trigger disabled" data-target="modal1" type="button" style="width: 100%; margin-top: 8px;">Batalkan Pesanan</button>
                    <?php
                    }else{
                    ?>
                    <button class="waves-effect waves-light btn blue" type="button" style="width: 100%" onclick="LanjutkanPembayaran()">Lanjutkan Ke Pembayaran</button>
                    <button class="waves-effect waves-light btn red accent-2 modal-trigger" data-target="modal1" type="button" style="width: 100%; margin-top: 8px;">Batalkan Pesanan</button>
                    <?php
                    }
                    ?>

                    <div id="modal1" class="modal small">
                        <div class="modal-content">
                            <h5 class="modal-title">Pembatalan Pesanan</h5>
                            <p>Apakah Anda Yakin Ingin Membatalkan Pesanan ini?</p>
                        </div>
                        <div class="modal-footer">
                            <button class="modal-close waves-effect waves-red btn transparent black-text" onclick="BatalkanPesanan()">Ya! Batalkan</button>
                            <button class="modal-close waves-effect waves-teal btn green accent-5">Tidak</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
<script type="text/javascript">
  $('.modal').modal();
  var DetailPesanan = storage.getItem("DetailPesanan");
  DetailPesanan = JSON.parse(DetailPesanan);
  var JenisPengiriman, metode_pembayaran;
  $(document).ready(function(){
    
    
    var IDPESANAN = DetailPesanan.ID;
    var TotalHargaAll = DetailPesanan.TotalHargaAll;
    var MinTransfer = TotalHargaAll * (30/100);
    var SisaTagihan = TotalHargaAll - MinTransfer;
    var BiayaPengiriman = DetailPesanan.BiayaPengiriman;
    var AllPurchaseProduk = DetailPesanan.AllPurchaseProduk;
    var waktuPemesanan = DetailPesanan.waktuPemesanan;
    var tglPengiriman = DetailPesanan.tglPengiriman;
    var TotalHargaProduk = DetailPesanan.TotalHargaProduk;
    var DataUsaha = DetailPesanan.DataUsaha;
    var DataPembeli = DetailPesanan.DataPembeli;
    var Alamat = DataPembeli.alamat_pb;
    var nama_usaha = DataUsaha.nama_usaha;
    var IdUsaha = DataUsaha.id_usaha;
    JenisPengiriman = DetailPesanan.JenisPengiriman;
    var TanggalPengiriman = DetailPesanan.tglPengiriman;
    var TotalProdukPesanan = DetailPesanan.TotalProduk;
    var TotalBeratProduk = DetailPesanan.TotalBeratProduk;
    console.log("TotalBeratProduk : " + TotalBeratProduk);
    var BiayaPengiriman = DetailPesanan.BiayaPengiriman;
    var DataPembayaran = DetailPesanan.DataPembayaran;
    metode_pembayaran = DataPembayaran.metode_pembayaran;

    var statusSekarang;
    var classStatusPesanan;
    if((DetailPesanan.DataPembayaran.status_pembayaran=="Lunas" || DetailPesanan.DataPembayaran.status_pembayaran=="DP") && DetailPesanan.DataPembayaran.verifikasi=="0"){
        statusSekarang = '<li class="collection-item orange white-text center" style="line-height: 10px">Menunggu Konfirmasi Pembayaran</li>';
    }else{
        
        statusSekarang = '<li class="collection-item teal-text darken-1"><b>STATUS PEMESANAN<span class="secondary-content red accent-2 badge status-pemesanan white-text" style="text-transform: uppercase; font-size: 15px; border-radius: 8px;">'+storage.getItem("status")+'</span></b></li>';;
        // classStatusPesanan = 'red accent-2';
    }
    var StatusPemesanan = statusSekarang;
    $("#collection-status-pemesanan").html(StatusPemesanan);
    $(".NoPesanan").html(IDPESANAN);
    $(".WaktuPemesanan").html("<?=$DisplayWaktuPemesanan?>");
    
    var HtmlProduk = '';
    HtmlProduk = '<li class="collection-item"><a href=""><h6 class="nama-toko"></h6></a></li>'+
    '<li class="collection-item"><h5>DAFTAR PRODUK</h5></li>';
    $.each(AllPurchaseProduk, function(i,isi){
      var FotoProduk = base_url+ '/foto_usaha/produk/' + isi.foto_produk;
      var NamaProduk = isi.nama_produk + ' ' + isi.nama_variasi;
      var HargaProduk = isi.harga;
      var TotalProduk = isi.jml_produk/10;
      HtmlProduk += '<li class="collection-item avatar"><img src="'+FotoProduk+'" alt="" class="circle">'+
      '<span class="title">'+NamaProduk+'</span>'+
      '<p class="orange-text"><b>Rp '+HargaProduk+'</b><br></p>'+
      '<span class="secondary-content">'+TotalProduk+'&nbsp Kg</span></li>';
  });
    HtmlProduk += '<li class="collection-item teal-text darken-1"><b>Total Harga Produk: <span class="secondary-content total-harga-produk"></span></b></li>';
    $(".collection-product").html(HtmlProduk);
    $(".nama-toko").html("<a href='#!' onclick='GoToDetailUsaha("+IdUsaha+")'>"+nama_usaha+"</a>");
    $(".alamat").html(Alamat);
    $(".tipe-pengiriman").html(JenisPengiriman);
    $(".tanggal-pengiriman").html(TanggalPengiriman);
    $(".total-berat").html(TotalBeratProduk/10+"&nbsp;Kg");
    $(".biaya-pengiriman").html("Rp " + formatNumber(BiayaPengiriman));
    $(".tipe-pembayaran").html(metode_pembayaran);
    $(".total-harga-produk").html("Rp&nbsp;" + formatNumber(TotalHargaProduk/10));
    $(".total-pembayaran").html("Rp&nbsp;" + formatNumber(TotalHargaAll));
    $(".minimal-transfer").html("Rp&nbsp;" + formatNumber(MinTransfer));
    $(".sisa-tagihan").html("Rp&nbsp;" + formatNumber(SisaTagihan));
});

  function LanjutkanPembayaran(){
    console.log("Lanjutkan Pembayaran");
    if(metode_pembayaran=="Full Transfer"){
        setTimeout(function(){ window.location.href="pembayaran-fulltransfer.html"});
    }else if(metode_pembayaran=="Transfer Cash"){
        if(JenisPengiriman=="Biasa" || JenisPengiriman=="Cepat"){
            setTimeout(function(){window.location.href="pembayaran-dp-kirim.html"});
        } else if (JenisPengiriman=="Ambil di Toko") {
            setTimeout(function(){ window.location.href="pembayaran-dp-ambil.html"});
        }
    }else if(metode_pembayaran=="Full Cash"){
        setTimeout(function(){ window.location.href="pesanan-saya.html"});
    }
}

function BatalkanPesanan(){
    console.log("Batalkan Pesanan");
    var idPesanan = DetailPesanan.idPemesanan;
    var data = {"idPesanan" : idPesanan};
    console.log(data);
    $.ajax({
      url: base_url + "Pemesanan/HapusPemesananByIdPemesanan",
      data: data,
      type: "POST",
      dataType: "JSON",
      success: function(result){
        if(result.responseMessage=="success"){
            M.toast({html:"Berhasil menghapus Pesanan"});
            storage.setItem("DetailPesanan","");
            setTimeout(function(){ window.location.href="pesanan-saya.html" }, 3000);
        }else{

        }
    }
});
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
}
</script>

<!-- <script type="text/javascript" src="../js/imgSlider.js"></script> -->
</body>
</html>