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
              <h1 style="text-transform: uppercase"><a class="btn btn-secondary btn-sm" href="<?=base_url('Penjual')?>"><span class="fas fa-chevron-left"></span></a> DATA <?= ucwords($page) ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/Pembeli') ?>">Pembeli</a></li>
                <li class="breadcrumb-item active"><span>Pesanan</span></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Tabel Data <?=$page?></h3>
          </div>
          <div class="card-body">
            <!-- <a href="<?= base_url('penjual/add') ?>" class="btn btn-sm btn-info m-l-10 mb-3 float-right"><i class="fa fa-plus"></i> TAMBAH DATA PENJUAL</a> -->
            <div class="table-responsive-lg">
              <table class="table table-hover table-bordered table-striped dataTable dtr-inline" id="table-init">
                <thead class="thead-colored thead-teal" style="text-align: center">
                  <tr>
                    <th class="text-center" width="10%">No Pesanan</th>
                    <th class="text-center">Waktu Pesanan</th>
                    <th class="text-center" width="15%">Nama Usaha</th>
                    <th class="text-center">Total Pembayaran</th>
                    <th class="text-center">Status Pemesanan</th>
                    <th class="text-center">Status Pembayaran</th>
                    <th class="text-center" style="width: 10%">Status Pengiriman</th>
                    <th width="12%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if($data_transaksi->num_rows() > 0):
                    foreach ($data_transaksi->result() as $transaksi) {
                       $id1 = str_replace("-","",$transaksi->waktu_pemesanan);
                        $id2 = str_replace(" ", "", $id1);
                        $id3 = str_replace(":", "", $id2);
                        $id4 = $id3 . $transaksi->id_pemesanan;

                        $selectP = "status_pembayaran, verifikasi";
                        $from = "data_pembayaran";
                        $where = " WHERE id_pemesanan = '$transaksi->id_pemesanan' ";
                        $order = " ORDER BY id_pembayaran DESC ";
                        $limit = " LIMIT 1";
                        $data_pembayaran = $this->Pemesanan->get_custom($selectP, $from, $where, '', '', $order, $limit)->row();

                        $wherePengiriman = "id_pemesanan = '$transaksi->id_pemesanan'";
                        $data_pengiriman = $this->Pengiriman->get_where($wherePengiriman);
                    ?>
                      <tr>
                        <td id="no-pesanan-<?=$transaksi->id_pemesanan?>"><?= $id4; ?></td>
                        <td class="text-center" id="waktu-pemesanan-<?=$transaksi->id_pemesanan?>">
                          <p><?= $transaksi->waktu_pemesanan ?></p>
                        </td>
                        <td>
                          <?php
                            $data_usaha = $this->Model_penjual->ambil_usaha_by_id($transaksi->id_usaha);
                            if($data_usaha->num_rows() > 0):
                              $usaha = $data_usaha->row();
                              echo ucwords($usaha->nama_usaha);
                            else:
                              echo "";
                            endif;
                          ?>
                        </td>
                        <td class="text-right">
                          <?php echo 'Rp ' . number_format($transaksi->biaya_kirim + $transaksi->total_harga, 0, ",", "."); ?>
                        </td>
                        <td class="text-center" id="status-pemesanan-<?=$transaksi->id_pemesanan?>">
                          <?php
                          if(($transaksi->status_pemesanan == "Baru" || $transaksi->status_pemesanan == "Terbayar") && $data_pembayaran->verifikasi=="0"){
                          ?>
                          <span class="badge badge-danger">Baru</span>
                          <?php
                          }elseif($transaksi->status_pemesanan == "Terbayar" && $data_pembayaran->verifikasi=="1"){
                          ?>
                          <span class="badge badge-warning">Terbayar</span>
                          <?php
                          }elseif($transaksi->status_pemesanan == "Terkirim" && $data_pembayaran->verifikasi=="1"){
                          ?>
                          <span class="badge badage-success">Selesai</span>
                          <?php
                          }
                          ?>
                        </td>
                        <td class="text-center">
                          <?php
                          if(empty($data_pembayaran->status_pembayaran)){
                            echo "-";
                          }else{
                            echo $data_pembayaran->status_pembayaran;
                          }
                          
                          ?>
                        </td>
                        <td class="text-center">
                          <?php
                          
                          if($data_pengiriman->num_rows() > 0){
                            $pengiriman = $data_pengiriman->row();
                            echo $pengiriman->status_pengiriman;
                          }else{
                            echo "-";
                          }
                          ?>
                        </td>
                        <td class="text-center">
                          
                          <a href="<?=base_url('admin/Pembeli/detail_pesanan/')?><?=$transaksi->id_pemesanan?>" class="btn btn-sm btn-primary">Detail</a>
                          <?php
                          if($data_pengiriman->num_rows() > 0){
                            $pengiriman = $data_pengiriman->row();
                            if($pengiriman->status_pengiriman == "proses"){
                            ?>
                            <a class="btn btn-primary btn-sm" href="<?=base_url('admin/Pengiriman/track/'.$pengiriman->id_pengiriman)?>">Lacak Pengiriman</a>
                            <?php
                            }else{
                            ?>
                            <a class="btn btn-secondary btn-sm disabled" href="#!">Pengiriman Selesai</a>
                            <?php
                            }
                          }
                          ?>
                        </td>

                      </tr>
                    <?php
                    }

                  else:
                  ?>
                  <tr>
                    <td colspan="8" class="text-center"><b>Data Kosong</b></td>
                  </tr>
                  <?php
                  endif;
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            Footer
          </div>
          <!-- /.card-footer-->
        </div>
        <!-- /.card -->
        <div class="modal fade" id="modal-default">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="modal-title-detail">Title</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                
                <div class="row">
                  <div class="col-lg-12">
                    <ul class="list-group mb-1">
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        STATUS PEMESANAN
                          <span id="statusPemesanan"></span>
                      </li>
                    </ul>
                    <ul class="list-group mb-3">
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        No. Pesanan
                        <span class="badge badge-primary" id="noPesanan"></span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        Waktu Pemesanan
                        <span class="badge badge-primary" id="waktu_pemesanan"></span>
                      </li>
                    </ul>
                    <ul class="list-group mb-1">
                      <li class="list-group-item" id="namaUsaha">
                        
                      </li>
                    </ul>
                    <ul class="list-group mb-1" id="TitleProduk">
                      
                    </ul>
                    <div id="listProduk" class="row"></div>
                    <ul class="list-group mb-1" id="totalHargaProduk"></ul>
                  </div>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php
    $this->load->view('admin/template/footerjs');
    ?>
    <!-- CUSTOME JAVASCRIPT HERE -->
    <script type="text/javascript">
      $(document).ready(()=>{
        $("#table-init").DataTable();
      });
      function openModalDetail(id_pemesanan) {
        var no_pesanan = $("#no-pesanan-" + id_pemesanan).text();
        var stats_pemesanan = $("#status-pemesanan-" + id_pemesanan).html();
        var waktu_pemesanan = $("#waktu-pemesanan-" + id_pemesanan).children("p").text();
        $("#modal-title-detail").text("Detail Transaksi - " + no_pesanan);
        $("#statusPemesanan").html(stats_pemesanan);
        document.getElementById("noPesanan").innerText = no_pesanan;
        document.getElementById("waktu_pemesanan").innerHTML = waktu_pemesanan;
        $.ajax({
          url: '<?=$url_API?>Pemesanan/getDataPemesananByID',
          type: "GET",
          data: {id_pemesanan: id_pemesanan},
          dataType: "JSON",
          async: false,
          success: function(e){
            if(e.responseMessage == "success"){
              var dataPesanan = e.dataPesanan
              var allProduk = dataPesanan.AllPurchaseProduk;
              var DataUsaha = dataPesanan.DataUsaha;
              document.getElementById("namaUsaha").innerHTML = DataUsaha.nama_usaha;
              // EXTRACT ALL PRODUCT PURCHASED
              var htmlTitleProduk = '<li class="list-group-item"><b>DAFTAR PRODUK</b></li>';
              document.getElementById("TitleProduk").innerHTML = htmlTitleProduk;
              var subTotal = 0;
              var htmlProduk = '<div class="col-lg-12">';
              $.each(allProduk, function(k, v){
                subTotal = v.sub_total;
                htmlProduk += '<div class="card"><div class="col-lg-12 post">'+
                      '<div class="user-block">'+
                        '<img class="img-circle img-bordered-sm" src="<?=$url_API?>foto_usaha/produk/'+v.foto_produk+'" alt="user image">'+
                        '<span class="username">'+
                          '<a href="#">'+v.nama_produk+'</a>'+
                          '<a href="#" class="float-right">'+v.jml_produk+' Ons</a>'+
                        '</span>'+
                        '<span class="text-warning description">Rp '+formatNumber(v.harga)+'</span>'+
                      '</div>'+
                    '</div></div>';
              });
              htmlProduk += '</div>';
              var totalHargaProduk = '<li class="list-group-item d-flex justify-content-between align-items-center">';
              totalHargaProduk += 'TOTAL HARGA PRODUK: ';
              totalHargaProduk += '<span>Rp '+formatNumber(subTotal)+'</span>';
              totalHargaProduk += '</li>';
              document.getElementById("listProduk").innerHTML = htmlProduk;
              document.getElementById("totalHargaProduk").innerHTML = totalHargaProduk;
            }
          }
        }).always(function(){
          $("#modal-default").modal("show");
        });
      }
    </script>
</body>

</html>