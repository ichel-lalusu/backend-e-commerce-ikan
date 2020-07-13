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
                <h3><?=$data_pesanan_selesai->total?></h3>
                <p>Pesanan Selesai</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-drafts"></i>
              </div>
              <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-angle-right"></i></a>
            </div>
          </div>

          <!-- TOTAL PENJUAL -->
          <div class="col-md-3">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?=$data_penjual->num_rows()?></h3>
                <p>Total Penjual Saat Ini</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-person"></i>
              </div>
              <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-angle-right"></i></a>
            </div>
          </div>

          <div class="col-md-3">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?=$data_pembeli->num_rows()?></h3>
                <p>Total Pembeli Saat Ini</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-person"></i>
              </div>
              <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-angle-right"></i></a>
            </div>
          </div>

          <div class="col-md-3">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?=(isset($data_pesanan_on_delivery->total)) ? $data_pesanan_on_delivery->total : 0;?></h3>
                <p>Pesanan Dalam Pengiriman</p>
              </div>
              <div class="icon">
                <i class="ion ion-log-out"></i>
              </div>
              <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-angle-right"></i></a>
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
      $(document).ready(()=>{
        $(".alert").fadeOut(1000);
      });
    </script>
    <!-- CUSTOME JAVASCRIPT HERE -->
  </div>
</body>

</html>