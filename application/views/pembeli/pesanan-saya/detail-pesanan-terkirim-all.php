  <?php
  $datePemesanan = $DataPemesanan->row()->waktu_pemesanan;
  $datePengirimanDiajukan = $DataPemesanan->row()->tgl_pengiriman;
  $datePembayaran = $DataPembayaran->row()->waktu_pembayaran;
  $datePengiriman = $DataPengiriman->waktu_pengiriman;
  $datePengajuanPengiriman = $DataPemesanan->row()->tgl_pengiriman;
  $datePengirimanSelesai = $DataPengiriman->waktu_pengiriman_selesai;
  //create Date
  $newDatePemesanan = date_create($datePemesanan);
  $newDatePengirimanDiajukan = date_create($datePengirimanDiajukan);
  $newDatePembayaran = date_create($datePembayaran);
  $newDatePengiriman = date_create($datePengiriman);
  $newDatePengajuanPengiriman = date_create($datePengajuanPengiriman);
  $newDatePengirimanSelesai = date_create($datePengirimanSelesai);
  //Format Date & Time Pemesanan
  $FormatedDatePemesanan = date_format($newDatePemesanan, 'd M Y');
  $FormatedTimePemesanan = date_format($newDatePemesanan, 'H:i');
  $DisplayWaktuPemesanan = $FormatedDatePemesanan . '&nbsp;' . $FormatedTimePemesanan;
  //Format Date & Time Pembayaran
  $FormatedDatePembayaran = date_format($newDatePembayaran,'d M Y');
  $FormatedTimePembayaran = date_format($newDatePembayaran,"H:i");
  $DisplayWaktuPembayaran = $FormatedDatePembayaran . '&nbsp;' . $FormatedTimePembayaran;
  //Format Date & Time Pengiriman
  $FormatedDatePengiriman = date_format($newDatePengiriman, 'd M Y');
  $FormatedTimePengiriman = date_format($newDatePengiriman, 'H:i');
  $DisplayWaktuPengiriman = $FormatedDatePengiriman . '&nbsp;' . $FormatedTimePengiriman;
  //Format Date & Time Pengajuan Pengirmian
  $FormatedDatePengajuan = date_format($newDatePengajuanPengiriman, 'd M Y');
  $FormatedTimePengajuan = date_format($newDatePengajuanPengiriman, 'H:i');
  $DisplayWaktuPengajuan = $FormatedDatePengajuan . '&nbsp;' . $FormatedTimePengajuan;
  //Format Date & Time Pengiriman Selesai
  $FormatedDatePengirimanSelesai = date_format($newDatePengirimanSelesai, 'd M Y');
  $FormatedTimePengirimanSelesai = date_format($newDatePengirimanSelesai, 'H:i');
  $DisplayWaktuPengirimanSelesai = $FormatedDatePengirimanSelesai . '&nbsp;' . $FormatedTimePengirimanSelesai;
  //Format DAte Pengiriman Diajukan
  $FormatedDatePengirimannDiajukan = date_format($newDatePengirimanDiajukan, 'd M Y');
  $DisplayWaktuPengirimanDiajukan = $FormatedDatePengirimannDiajukan ;

    //Jenis Pengiriman "KIRIM"
  if($JenisPengiriman=="Cepat" || $JenisPengiriman=="Biasa"){
    // JENIS PEMBAYARAN "FULL TRANSFER"
    if($JenisPembayaran=="Full Transfer"){
      ?>
      <div class="grey lighten-4">
        <div class="row" style="margin-bottom: -10px">
          <div class="col s12">
            <div class="card">
              <ul class="collection">
                <li class="collection-item teal-text darken-1"><b>STATUS PEMESANAN<span class="secondary-content badge blue status-pemesanan white-text" style="font-size: 14px; border-radius: 8px;">PESANAN DITERIMA</span></b></li>
              </ul>
            </div>
            <div class="card">
              <ul class="collection">
                <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="secondary-content NoPesanan"></span></b></li>
                <li class="collection-item"><b>Waktu Pemesanan :<span class="secondary-content WaktuPemesanan"><?=$DisplayWaktuPemesanan;?></span></b></li>
                <li class="collection-item"><b>Waktu Pembayaran :<span class="secondary-content WaktuPembayaran"><?=$DisplayWaktuPembayaran?></span></b></li>
                <li class="collection-item"><b>Waktu Pengiriman :<span class="secondary-content WaktuPengiriman"><?=$DisplayWaktuPengiriman?></span></b></li>
                <li class="collection-item"><b>Waktu Pesanan Selesai :<span class="secondary-content WaktuPesananSelesai"><?=$DisplayWaktuPengirimanSelesai?></span></b>
                </ul>
              </div>
              <!-- <h6 class="collection-item" href="#!"><h6 class="nama-toko">Nama Toko (Link Toko)</h6></a> -->
              <div class="card">
                <ul class="collection collection-product"></ul>
              </div>
              <div class="card">
                <ul class="collection">
                  <li class="collection-item"><h5>DETAIL PENGIRIMAN</h5></li>
                  <li class="collection-item"><span class="material-icons teal-text darken-1">location_on</span>&nbsp;Alamat Pengiriman<p class="teal-text darken-1 alamat" style="margin-left:26px;">Jl. Rw Bahagia No. 14</p></li>
                  <li class="collection-item">Jenis Pengiriman :<span class="secondary-content tipe-pengiriman">Biasa</span></li>
                  <li class="collection-item">Tgl Pengiriman yang Diajukan :<span class="secondary-content tanggal-pengiriman-diajukan"><?=$DisplayWaktuPengajuan;?></span></li>
                  <li class="collection-item teal-text darken-1"><b>Total Biaya Pengiriman :<span class="secondary-content biaya-pengiriman">Rp</span>
                    <p class="total-berat" style="margin-top: -5px; margin-bottom: 0px">(xxx ons)</b></li>
                    </ul>
                  </div>
                  <?php if(isset($DataPengiriman)): ?>
                    <div class="card">
                      <ul class="collection">
                        <li class="collection-item avatar kendaraan-kurir">
                          <img src="<?=base_url('foto_usaha/foto_kurir/').$DataPengiriman->foto_kurir?>" alt="Foto Kurir" class="circle">
                          <span class="title nama_kurir">Kurir:</span>
                          <p><span class="teal-text darken-1"><?=$DataPengiriman->nama_kurir?></span><br>
                            <span class="teal-text darken-1"><?=$DataPengiriman->jenis_kendaraan?></span><br>
                            <span class="teal-text darken-1"><?=$DataPengiriman->plat_kendaraan?></span></p>
                            <!-- <a class="secondary-content" href="tel:+<?=$DataPengiriman->telp_kurir?>"><span class="material-icons">phone</span></a> -->
                          </li>
                    <!-- <li class="collection-item">
                    Jenis Kendaraan <span class="secondary-content"><?=$DataPengiriman->jenis_kendaraan?></span>
                    </li>
                    <li class="collection-item">
                    Plat Kendaraan <span class="secondary-content"><?=$DataPengiriman->plat_kendaraan?></span>
                  </li> -->
                </ul>
              </div>
            <?php endif; ?>
            <div class="card">
              <ul class="collection">
                <li class="collection-item"><h5>DETAIL PEMBAYARAN</h5></li>
                <li class="collection-item">Metode Pembayaran :<span class="secondary-content tipe-pembayarann">Bayar Penuh Transfer</span></li>
                <li class="collection-item">Total Harga Produk :<span class="secondary-content total-harga-produk">Rp</span></li>
                <li class="collection-item">Total Biaya Pengiriman :<span class="secondary-content biaya-pengiriman">Rp</span></li>
                <li class="collection-item" style="margin-bottom: -5px"><b>Total Pembayaran :<span class="secondary-content total-pembayaran">Rp</span></b>
                </span>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
    // TO DO: JENIS PEMBAYARAN "TRANSFER CASH"
}else if($JenisPembayaran=="Transfer Cash"){
  ?>
  <div class="container-fluid">
    <div class="row" style="margin-bottom: 0px">
      <div class="col s12">
        <div class="card">
          <ul class="collection">
            <li class="collection-item teal-text darken-1">
              <b>STATUS PEMESANAN<span class="secondary-content badge blue white-text" style="font-size: 14px; border-radius: 8px;">PESANAN DITERIMA</span></b>
            </li>
          </ul>
        </div>
        <div class="card">
          <ul class="collection">
            <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="secondary-content NoPesanan"></span></b></li>
            <li class="collection-item"><b>Waktu Pemesanan :<span class="secondary-content WaktuPemesanan"><?=$DisplayWaktuPemesanan?></span></b></li>
            <li class="collection-item"><b>Waktu Pembayaran :<span class="secondary-content WaktuPembayaran"><?=$DisplayWaktuPembayaran?></span></b></li>
            <li class="collection-item"><b>Waktu Pengiriman :<span class="secondary-content WaktuPengiriman"><?=$DisplayWaktuPengiriman?></span></b></li>
            <li class="collection-item"><b>Waktu Pesanan Selesai :<span class="secondary-content WaktuPesananSelesai"><?=$DisplayWaktuPengirimanSelesai?></span></b>
            </ul>
          </div>
          <div class="card">
            <ul class="collection collection-product">
            </ul>
          </div>
          <div class="card">
            <ul class="collection">
              <li class="collection-item"><h5>DETAIL PENGIRIMAN</h5></li>
              <li class="collection-item">Alamat Pengiriman :<span class="secondary-content alamat"></span></li>
              <li class="collection-item">Jenis Pengiriman :<span class="secondary-content tipepengiriman">Biasa</span></li>
              <li class="collection-item">Tanggal Pengiriman yang Diajukan :<span class="secondary-content tanggal-pengiriman-diajukan"></span></li>
              <li class="collection-item"><b>Total Biaya Pengiriman :<span class="secondary-content biaya-pengiriman">Rp</span>
                <p class="total-berat" style="margin-top: 0px;">(xxx ons)</p></b></li>
              </ul>
            </div>
            <div class="card">
              <ul class="collection">
                <li class="collection-item"><h5>DETAIL PEMBAYARAN</h5></li>
                <li class="collection-item">Metode Pembayaran :<span class="secondary-content tipePengiriman">Transfer dan Tunai</span></li>
                <li class="collection-item">Total Harga Produk :<span class="secondary-content total-harga-produk">Rp</span></li>
                <li class="collection-item">Total Biaya Pengiriman :<span class="secondary-content biaya-pengiriman">Rp</span></li>
                <li class="collection-item teal-text darken-1"><b>Total Pembayaran :<span class="secondary-content total-pembayaran">Rp</span></b></li>
                <li class="collection-item teal-text darken-1"><b>Pembayaran Ke-<?=$urutan?> :<span class="secondary-content pembayaran1">Rp</span></b></li>
                <li class="collection-item teal-text darken-1"><b>Pembayaran Ke-2 :<span class="secondary-content pembayaran2">Rp</span></b></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
}
}else if($JenisPengiriman=="Ambil Di Toko"){
  if($JenisPembayaran=="Full Transfer"){
    ?>
    <div class="container-fluid">
      <div class="row" style="margin-bottom: 0px">
        <div class="col s12">
          <div class="card">
            <ul class="collection">
              <li class="collection-item teal-text darken-1">
                <b>STATUS PEMESANAN<span class="secondary-content badge blue white-text" style="font-size: 15px; border-radius: 8px;">PESANAN DITERIMA</span></b>
              </li>
            </ul>
          </div>
          <div class="card">
            <ul class="collection">
              <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="secondary-content NoPesanan">310120181025</span></b></li>
              <li class="collection-item"><b>Waktu Pemesanan :<span class="secondary-content WaktuPemesanan">31/01/2020 23:00</span></b></li>
              <li class="collection-item"><b>Waktu Pembayaran :<span class="secondary-content WaktuPembayaran">31/01/2020 23:30</span></b>
              </li>
              <li class="collection-item"><b>Waktu Pesanan Selesai :<span class="secondary-content WaktuPesananSelesai"></span></b>
              </ul>
            </div>
            <div class="card">
              <ul class="collection collection-product">
              </ul>
            </div>
            <div class="card">
              <ul class="collection">
                <li class="collection-item"><h6>DETAIL PENGIRIMAN</h6></li>
                <li class="collection-item">Jenis Pengiriman :<span class="secondary-content tipe-pengiriman">Ambil di Toko</span></li>
                <li class="collection-item">Tanggal Pengambilan yang Diajukan :<span class="secondary-content tanggal-pengiriman-diajukan">02/01/2020</span></li>
              </ul>
            </div>
            <div class="card">
              <ul class="collection">
                <li class="collection-header" style="padding-left: 10px"><h6>DETAIL PEMBAYARAN</h6></li>
                <li class="collection-item">Metode Pembayaran :<span class="secondary-content tipe-pembayaran">Bayar Penuh Transfer</span></li>
                <li class="collection-item teal-text darken-1"><b>Total Harga Produk :<span class="secondary-content total-harga-produk">Rp</span></b></li>
                <li class="collection-item teal-text darken-1"><b>Total Pembayaran :<span class="secondary-content total-pembayaran">Rp</span></b></li>
              </ul>
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
                <li class="collection-item teal-text darken-1"><b>STATUS PEMESANAN<span class="secondary-content badge blue accent-2 status-pemesanan white-text"  style="font-size: 14px; border-radius: 8px;">PESANAN DITERIMA</span></b></li>
              </ul>
            </div>
            <div class="card">
              <ul class="collection">
                <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="secondary-content NoPesanan">310120181025</span></b></li>
                <li class="collection-item"><b>Waktu Pemesanan :<span class="secondary-content WaktuPemesanan">31/01/2020 23:00</b></span></li>
                <li class="collection-item"><b>Waktu Pembayaran :<span class="secondary-content WaktuPembayaran">31/01/2020 23:30</b></span></li>
                <li class="collection-item"><b>Waktu Pesanan Selesai :<span class="secondary-content WaktuPesananSelesai">31/01/2020 23:30</b></span></li>
              </ul>
            </div>
            <a class="collection-item" href="#!"><h6 class="nama-toko">Nama Toko (Link Toko)</h6></a>
            <div class="card">
              <ul class="collection collection-product">
              </ul>
            </div>
            <div class="card">
              <ul class="collection">
                <li class="collection-header" style="padding-left: 10px"><h5>DETAIL PENGIRIMAN</h5></li>
                <li class="collection-item">Jenis Pengiriman :<span class="secondary-content tipe-pengiriman">Ambil di Toko</span></li>
                <li class="collection-item">Tanggal Pengambilan yang Diajukan :<span class="secondary-content tanggal-pengiriman-diajukan">02/01/2020</span></li>
              </a>
            </ul>
          </div>
          <div class="card">
            <ul class="collection">
              <li class="collection-header" style="padding-left: 10px"><h5>DETAIL PEMBAYARAN</h5></li>
              <li class="collection-item">Metode Pembayaran :<span class="secondary-content tipe-pembayaran">Bayar Penuh Tunai</span></li>
              <li class="collection-item">Total Harga Produk :<span class="secondary-content total-harga-produk">Rp</span></li>
              <li class="collection-item" style="margin-bottom: -5px"><b>Total Pembayaran :<span class="secondary-content total-pembayaran">Rp</span></b></li>
            </ul>
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
                <li class="collection-item teal-text darken-1"><b>STATUS PEMESANAN<span class="secondary-content badge blue status-pemesanan white-text" style="font-size: 15px; border-radius: 8px;">PESANAN DITERIMA</span></b></li>
              </ul>
            </div>
            <div class="card">
              <ul class="collection">
                <li class="collection-item teal-text darken-1"><b>No. Pesanan :<span class="secondary-content NoPesanan"></span></b></li>
                <li class="collection-item"><b>Waktu Pemesanan :<span class="secondary-content WaktuPemesanan"></span></b></li>
                <li class="collection-item"><b>Waktu Pembayaran :<span class="secondary-content WaktuPembayaran"></span></b></li>
                <li class="collection-item"><b>Waktu Pesanan Selesai :<span class="secondary-content WaktuPesananSelesai"></span></b></li>
              </ul>
            </div>
            <a class="collection-item" href="#!"><h6 class="nama-toko">Nama Toko (Link Toko)</h6></a>
            <div class="card">
              <ul class="collection collection-product">
              </ul>
            </div>
            <div class="card">
              <ul class="collection">
                <li class="collection-item"><h5>DETAIL PENGIRIMAN</h5></li>
                <li class="collection-item">Jenis Pengiriman :<span class="secondary-content tipe-pengiriman"></span></li>
                <li class="collection-item">Tanggal Pengambilan :<span class="secondary-content tanggal-pengiriman"></span><br><span class="teal-text total-berat"></span></li>
              </ul>
            </div>
            <div class="card">
              <ul class="collection">
                <li class="collection-item"><h5>DETAIL PEMBAYARAN</h5></li>
                <li class="collection-item">Metode Pembayaran :<span class="secondary-content tipe-pembayaran"></span></li>
                <li class="collection-item">Total Harga Produk :<span class="secondary-content total-harga-produk"></span></li>
                <li class="collection-item teal-text darken-1"><b>Total Pembayaran :<span class="secondary-content total-pembayaran"></span></b></li>
                <li class="collection-item teal-text darken-1"><b>Pembayaran Ke-1 :<span class="secondary-content pembayaran1"></span></b></li>
                <li class="collection-item teal-text darken-1"><b>Pembayaran Ke-2 :<span class="secondary-content pembayaran2"></span></b></li>
              </ul>
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
      var TotalHargaProduk = DetailPesanan.TotalHargaProduk;
      var DataUsaha = DetailPesanan.DataUsaha;
      var DataPembeli = DetailPesanan.DataPembeli;
      var Alamat = DataPembeli.alamat_pb + ', ' + DataPembeli.kel_pb + ', ' + DataPembeli.kec_pb + ', ' + DataPembeli.kab_pb;
      var nama_usaha = DataUsaha.nama_usaha;
      var IdUsaha = DataUsaha.id_usaha;
      var JenisPengiriman = DetailPesanan.JenisPengiriman;
      var TanggalPengiriman = DetailPesanan.tglPengiriman;
      var TotalProdukPesanan = DetailPesanan.TotalProduk;
      var TotalBeratProduk = DetailPesanan.TotalBeratProduk * 10  * TotalProdukPesanan;
      console.log("TotalBeratProduk : " + TotalBeratProduk);
      var BiayaPengiriman = DetailPesanan.BiayaPengiriman;
      var DataPembayaran = DetailPesanan.DataPembayaran;
      var metode_pembayaran = DataPembayaran.metode_pembayaran;
      // $(".status-pemesanan").attr("data-badge-caption", StatusPemesanan);
      if(storage.StatusPemesanan == "Terkirim"){
       $(".status-pemesanan").html("PESANAN DITERIMA");   
     }
     $(".NoPesanan").html(IDPESANAN);
     $(".WaktuPemesanan").html("<?=$DisplayWaktuPemesanan?>");
     $(".WaktuPembayaran").html("<?=$DisplayWaktuPembayaran?>");
     $(".WaktuPengiriman").html("<?=$DisplayWaktuPengiriman?>");
     var HtmlProduk = '';
     HtmlProduk += '<li class="collection-item"><h6 class="nama-toko">Nama Toko (Link Toko)</h6></li>'+
     '<li class="collection-item"><h5>DAFTAR PRODUK</h5></li>'
     $.each(AllPurchaseProduk, function(i,isi){
      var FotoProduk = base_url+ '/foto_usaha/produk/' + isi.foto_produk;
      var NamaProduk = isi.nama_produk + ' ' + isi.nama_variasi;
      var HargaProduk = isi.harga;
      var TotalProduk = isi.jml_produk;
      HtmlProduk += '<li class="collection-item avatar"><img src="'+FotoProduk+'" alt="" class="circle">'+
      '<span class="title">'+NamaProduk+'</span>'+
      '<p>Rp '+HargaProduk+'<br></p>'+
      '<span class="secondary-content"><b>'+TotalProduk+'&times;</b></span></li>';
    });
     HtmlProduk += '<b><a class="collection-item">Total Harga Produk :<span class="secondary-content total-harga-produk">Rp</span></a></b>';
     $(".collection-product").html(HtmlProduk);
     $(".nama-toko").html("<a href='#!' onclick='GoToDetailUsaha("+IdUsaha+")'>"+nama_usaha+"</a>");
     $(".alamat").html(Alamat);
     $(".tipe-pengiriman").html(JenisPengiriman);
     $(".tanggal-pengiriman-diajukan").html("<?=$DisplayWaktuPengirimanDiajukan;?>");
     $(".total-berat").html(TotalBeratProduk+"&nbsp;Ons");
     $(".biaya-pengiriman").html("Rp " + formatNumber(BiayaPengiriman));
     $(".tipe-pembayaran").html(metode_pembayaran);
     $(".total-harga-produk").html("Rp&nbsp;" + formatNumber(TotalHargaProduk));
     $(".total-pembayaran").html("Rp&nbsp;" + formatNumber(TotalHargaAll));
     $(".pembayaran1").html("Rp&nbsp;" + formatNumber(MinTransfer));
     $(".pembayaran2").html("Rp&nbsp;" + formatNumber(SisaTagihan));
   });



    function formatNumber(num) {
      return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
    }
  </script>

  <!-- <script type="text/javascript" src="../js/imgSlider.js"></script> -->
</body>
</html>