<?php
$this->load->view('admin/template/head');
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
              <h1><?= ucwords($menu) ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <?php if ($this->session->flashdata("success")) : ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses</strong> <?= $this->session->flashdata("success") ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>
        <div class="row">
          <!-- PESANAN SELESAI -->
          <div class="col-md-3">
            <div class="small-box bg-info">
              <div class="inner">
                <h3 id="data-pesanan-selesai"></h3>
                <p>Pesanan Selesai</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-drafts"></i>
              </div>
              <a href="#" id="url-pesanan-selesai" class="small-box-footer">Lihat Detail <i class="fas fa-angle-right"></i></a>
            </div>
          </div>

          <!-- TOTAL PENJUAL -->
          <div class="col-md-3">
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="data-penjual"></h3>
                <p>Total Penjual Saat Ini</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-person"></i>
              </div>
              <a href="#" id="url-penjual" class="small-box-footer">Lihat Detail <i class="fas fa-angle-right"></i></a>
            </div>
          </div>

          <div class="col-md-3">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 id="data-pembeli"></h3>
                <p>Total Pembeli Saat Ini</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-person"></i>
              </div>
              <a href="#" id="url-pembeli" class="small-box-footer">Lihat Detail <i class="fas fa-angle-right"></i></a>
            </div>
          </div>

          <div class="col-md-3">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 id="data-pesanan-on-delivery"></h3>
                <p>Pesanan Dalam Pengiriman</p>
              </div>
              <div class="icon">
                <i class="ion ion-log-out"></i>
              </div>
              <a href="#" id="url-pesanan-on-delivery" class="small-box-footer">Lihat Detail <i class="fas fa-angle-right"></i></a>
            </div>
          </div>
        </div>
        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><?= ucwords($menu) ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                <i class="fas fa-times"></i></button>
            </div>
          </div>
          <div class="card-body">

          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            Footer
          </div>
          <!-- /.card-footer-->
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
      $(document).ready(() => {
        $(".alert").fadeOut(1000);
        var data_user = {
          data_user: JSON.parse(localStorage.data_user)
        };
        $.post('<?= base_url('admin/admin/data_dashborad_admin') ?>', data_user).then(success_load_data_dashboard);
      });

      function success_load_data_dashboard(data, status) {
        if (status == "success") {
          var data_dashboard = data.data;
          $("title").text(data_dashboard.title);
          var data_pesanan_selesai = data_dashboard.data_pesanan_selesai.total;
          var data_pembeli = data_dashboard.data_pembeli;
          var data_penjual = data_dashboard.data_penjual;
          var data_pesanan_on_delivery = data_dashboard.data_pesanan_on_delivery;
          $("#data-pesanan-selesai").text(data_pesanan_selesai);
          $("#data-penjual").text(data_penjual.length);
          $("#data-pembeli").text(data_pembeli.length);
          $("#data-pesanan-on-delivery").text(data_pesanan_on_delivery);
          if (data_pesanan_selesai.total > 0) {
            $("#url-pesanan-selesai").click(() => {
              location.href = data_dashboard.url_pesanan_selesai;
            });
          }
          if(data_pesanan_on_delivery > 0) {
            $("#url-pesanan-on-delivery").click(() => {
              location.href = data_dashboard.url_pesanan_on_delivery;
            });
          }
          if(data_penjual.length > 0){
            $("#url-penjual").click(() => {
              location.href = data_dashboard.url_penjual;
            });
          }
          if(data_pembeli.length > 0){
            $("#url-pembeli").click(() => {
              location.href = data_dashboard.url_pembeli;
            });
          }
        }
      }
    </script>
    <!-- CUSTOME JAVASCRIPT HERE -->
  </div>
</body>

</html>