<?php
$this->load->view('admin/template/head');
$url_API = "http://localhost/backendikan/";
?>

<body class="hold-transition sidebar-mini sidebar-collapse">
  <!-- Site wrapper -->
  <div class="wrapper">
    <?php
    $this->load->view('admin/template/nav');
    ?>
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">

              <h1 style="text-transform: uppercase"><a class="btn btn-secondary btn-sm text-white" href="<?=base_url('Usaha/transaksi/').$id_usaha?>"><i class="fas fa-chevron-left"></i></a> <?= ucwords($page) ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?=base_url('Penjual')?>">Penjual</a></li>
                <li class="breadcrumb-item"><a href="<?=base_url('Usaha/transaksi/').$id_usaha?>">Transaksi</a></li>
                <li class="breadcrumb-item active"><span>Detail</span></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="invoice p-3 mb-3">
                <!-- title row -->
                <div class="row">
                  <div class="col-12">
                    <h4>
                      <?php
                      $TIPE_PENGIRIMAN = $data_pesanan->tipe_pengiriman;
                      $METODE_PEMBAYARAN = $data_pembayaran->metode_pembayaran;

                      $badge_type="";
                      $STATUS_PEMBAYARAN = $data_pembayaran->status_pembayaran;
                      $STATUS_VERIFIKASI = $data_pembayaran->verifikasi;
                      $STATUS_PEMESANAN = $data_pesanan->status_pemesanan;
                      if($STATUS_PEMESANAN=="Baru"&&$STATUS_VERIFIKASI==0){
                        $badge_type = "badge-danger";
                      }elseif ($STATUS_PEMESANAN=="Terbayar"&&$STATUS_VERIFIKASI==0) {
                        $badge_type = "badge-warning";
                      }elseif($STATUS_PEMESANAN=="Terbayar"&&$STATUS_VERIFIKASI==1){
                        $badge_type = "badge-primary";
                      }elseif($STATUS_PEMESANAN=="Terkirim"){
                        $badge_type = "badge-success";
                      }
                      ?>
                      <span class="badge <?=$badge_type?>"><?=$STATUS_PEMESANAN;?></span>
                      <small class="float-right">Tanggal: <?=str_replace("-", "/", $TANGGAL)?></small>
                    </h4>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- info row -->
                <div class="row invoice-info">
                  <div class="col-sm-4 invoice-col">
                    From
                    <address>
                      <strong id="nama_usaha"><?=$data_usaha->nama_usaha?></strong><br>
                      <?=$data_usaha->alamat_usaha?>,<br>
                      <?=$data_usaha->kel . ",&nbsp;" . $data_usaha->kec . ",&nbsp;" . $data_usaha->kab . ",&nbsp" . "Yogyakarta"?><br>
                      Nomor: <?=$data_penjual->telp_pj?><br>
                    </address>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 invoice-col">
                    To
                    <address>
                      <strong id="nama_pembeli"><?=$data_pembeli->nama_pb?></strong><br>
                      <?=$data_pembeli->alamat_pb;?><br>
                      <?=$data_pembeli->kel_pb . ',&nbsp;' . $data_pembeli->kec_pb . ',&nbsp;' . $data_pembeli->kab_pb . ',&nbsp;' . 'Yogyakarta'?><br>
                      Nomor: <?=$data_pembeli->telp_pb;?><br>
                    </address>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 invoice-col">
                    <b>Invoice #<?=$no_pesanan?></b><br>
                    <br>
                    <!-- <b>Pemesanan:</b> <?=str_replace("-", "/", $TANGGAL)?><br> -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- Table row -->
                <div class="row">
                  <div class="col-12 table-responsive">
                    <table class="table table-striped">
                      <thead>
                      <tr>
                        <th width="20%">Total Produk</th>
                        <th>Produk</th>
                        <th>Variasi Produk</th>
                        <th>Subtotal</th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php
                        if($data_detail_pesanan->num_rows() > 0){
                          foreach ($data_detail_pesanan->result() as $key) {
                            $where = "data_variasi_produk.id_variasiproduk = '$key->id_produk'";
                            $join[0] = array('table' => 'data_produk', 'on' => "data_variasi_produk.id_produk = data_produk.id_produk", 'join' => '');
                            $join[1] = array('table' => 'data_variasi', 'on' => "data_variasi_produk.id_variasi = data_variasi.id_variasi", 'join' => '');
                            $get_detail_variation = $this->produk->get_variation_product("*", $where, $join, NULL, NULL, 1);
                            // echo $this->db->last_query();
                            if($get_detail_variation->num_rows() > 0){
                              $detail_produk = $get_detail_variation->row();
                              $SUBTOTAL = $detail_produk->harga * ($key->jml_produk/10);
                            ?>
                            <tr>
                              <td><?=$key->jml_produk/10 . ' Kg'?></td>
                              <td><?=$detail_produk->nama_produk?></td>
                              <td><?=$detail_produk->nama_variasi;?></td>
                              <td><?='Rp ' . number_format($SUBTOTAL,0, ",", ".")?></td>
                            </tr>
                            <?php
                            }
                          }
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->

                <div class="row">
                  <!-- accepted payments column -->
                  <div class="col-6">
                    <p class="lead">Metode Pembayaran:</p>
                    <h5 id="metode_pembayaran"><?=$data_pembayaran->metode_pembayaran?></h5>
                    <p class="lead">Tipe Pengiriman:</p>
                    <h5 id="metode_pengiriman"><?=$TIPE_PENGIRIMAN?></h5>
                  </div>
                  <!-- /.col -->
                  <div class="col-6">

                    <div class="table-responsive">
                      <table class="table">
                        <tbody>
                          <tr>
                            <th style="width:50%">Total Harga Produk:</th>
                            <td id="total_harga_produk"><?='Rp ' . number_format($TOTAL_HARGA_PESANAN, 0, ",", ".")?></td>
                          </tr>
                        <!-- <tr>
                          <th>Tax (9.3%)</th>
                          <td>$10.34</td>
                        </tr> -->
                        <tr>
                          <th>Biaya Kirim:</th>
                          <td id="biaya_kirim"><?='Rp ' . number_format($data_pesanan->biaya_kirim, 0, ",", ".")?></td>
                        </tr>
                        <?php
                        if($METODE_PEMBAYARAN=="Full Transfer" || $METODE_PEMBAYARAN == "Full Cash"){
                        ?>
                        <tr>
                          <th>Total:</th>
                          <td><?='Rp ' . number_format($data_pesanan->total_harga, 0, ",", ".")?>
                            <?php if ($STATUS_PEMBAYARAN=="Lunas"&&$STATUS_VERIFIKASI==0) {
                              ?>
                              &nbsp;<span class="badge badge-warning" style="font-size: small;">Menunggu Verifikasi</span>
                              <?php
                            } ?>
                          </td>
                        </tr>
                        <?php
                        if($STATUS_PEMBAYARAN=="Lunas"&&$STATUS_VERIFIKASI==1){
                            ?>
                            <tr>
                              <td>&nbsp;</td>
                              <td>
                                <span class="badge badge-success" style="font-size: small;">Terbayar</span>
                              </td>
                            </tr>
                            <?php
                          }
                        }elseif ($METODE_PEMBAYARAN == "Transfer Cash"){
                          ?>
                          <tr>
                            <th>Tagihan 1:</th>
                            <td><?='Rp ' . number_format($data_pesanan->total_harga*0.3, 0, ",", ".")?>
                            <?php if($STATUS_PEMBAYARAN=="DP"&&$STATUS_VERIFIKASI==1){
                              ?>
                              <span class="badge badge-success" style="font-size: small;">Terbayar</span>
                              <?php
                            }elseif ($STATUS_PEMBAYARAN=="DP"&&$STATUS_VERIFIKASI==0) {
                              ?>
                              &nbsp;<span class="badge badge-warning" style="font-size: small;">Menunggu Verifikasi</span>
                              <?php
                            } ?></td>
                          </tr>
                          <tr>
                            <th>Tagihan 2:</th>
                            <td><?='Rp ' . number_format($data_pesanan->total_harga-($data_pesanan->total_harga*0.3), 0, ",", ".")?></td>
                          </tr>
                          <?php
                          if($STATUS_PEMBAYARAN=="Lunas"&&$STATUS_VERIFIKASI==1){
                            ?>
                            <tr>
                              <td>&nbsp;</td>
                              <td><span class="badge badge-success" style="font-size: small;">Terbayar</span></td>
                            </tr>
                            <?php
                          }
                        }
                        ?>
                      </tbody>
                    </table>
                    </div>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- this row will not appear when printing -->
                <div class="row ">
                  <div class="col-12">
                    <a href="<?=base_url('admin/Pemesanan/lacak')?>?id_pesanan=<?=$id_pemesanan?>&menu=penjual" target="_blank" class="btn btn-primary float-right">Lacak</a>
                    <!-- <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                      Payment
                    </button>
                    <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                      <i class="fas fa-download"></i> Generate PDF
                    </button> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card -->

      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php
    $this->load->view('admin/template/footerjs');
    ?>
    <script type="text/javascript">
    </script>
    <!-- CUSTOME JAVASCRIPT HERE -->
</body>

</html>