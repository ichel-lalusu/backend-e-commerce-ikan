<?php
$this->load->view('admin/'.'template/head');
$url_API = "http://localhost/backendikan/";
?>

<body class="hold-transition sidebar-mini sidebar-collapse">
  <!-- Site wrapper -->
  <div class="wrapper">
    <?php
    $this->load->view('admin/'.'template/nav');
    ?>
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 style="text-transform: uppercase">DATA <?= ucwords($menu) ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
                <li class="breadcrumb-item active"><span>Penjual</span></li>
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
            <h3 class="card-title">Data <?= ucwords($menu) ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                <i class="fas fa-times"></i></button>
            </div>
          </div>
          <div class="card-body">
            <a href="<?= base_url('admin/'.'penjual/add') ?>" class="btn btn-sm btn-info m-l-10 mb-3 float-right"><i class="fa fa-plus"></i> TAMBAH DATA PENJUAL</a>
            <div class="table-responsive">
              <table class="table table-hover table-bordered table-striped dataTable dtr-inline">
                <thead class="thead-colored thead-teal" style="text-align: center">
                  <tr>
                    <th class="text-center">No</th>
                    <th style="width: 10%">Nama</th>
                    <th class="text-center">Foto</th>
                    <th class="text-center">No. KTP</th>
                    <th class="text-center">Foto KTP</th>
                    <th class="text-center">Jenis Kelamin</th>
                    <th class="text-center" style="width: 10%">Tgl Lahir</th>
                    <th class="text-center" style="width: 13%">Alamat</th>
                    <th class="text-center" style="width: 8%">No. Hp</th>
                    <th class="text-center">Jenis Petani</th>
                    <th width="12%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  foreach ($data_penjual->result() as $data_pj) {
                  ?>
                    <tr>
                      <td><?= $no; ?></td>
                      <td>
                        <p><?= $data_pj->nama_pj ?></p>
                      </td>
                      <td class="text-center">
                        <?php
                        if ($data_pj->foto_pj != "" || $data_pj->foto_pj != null) {
                        ?>
                          <img width="100" src="<?= base_url() . 'foto_penjual/' . $data_pj->foto_pj ?>" alt="Foto Penjual <?= ucwords($data_pj->foto_pj) ?>" class="img-circle">
                        <?php
                        }
                        ?>
                      </td>
                      <td>
                        <p><?= $data_pj->noktp_pj ?></p>
                      </td>
                      <td class="text-center">
                        <?php
                        if ($data_pj->fotoktp_pj != "" || $data_pj->fotoktp_pj != null) {
                        ?>
                          <img height="100" src="<?= base_url() . 'foto_ktp_penjual/' . $data_pj->fotoktp_pj ?>" alt="Foto KTPenjual <?= ucwords($data_pj->fotoktp_pj) ?>">
                        <?php
                        }
                        ?>
                      </td>
                      <td class="text-center">
                        <p><?= $data_pj->jk_pj ?></p>
                      </td>
                      <td class="text-center">
                        <p><?= $data_pj->tgllahir_pj ?></p>
                      </td>
                      <td>
                        <p><?= $data_pj->alamat_pj ?></p>
                      </td>
                      <td class="text-center">
                        <p><?= $data_pj->telp_pj ?></p>
                      </td>
                      <td class="text-center">Air <?= $data_pj->jenis_petani ?>
                      </td>
                      <td class="text-center">
                        <?php $data_usaha = $this->usaha->get_usaha_by_id_penjual($data_pj->id_pj); ?>
                        <a href="<?= base_url('admin/'.$menu . '/edit/' . $data_pj->id_pj); ?>" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalDelete<?= $data_pj->id_pj ?>"><i class="fa fa-trash"></i></button>
                        <a href="<?= base_url('admin/'.$menu . '/detail/' . $data_pj->id_pj); ?>" class="btn btn-sm btn-primary">Detail</a>
                        <?php if($data_usaha->num_rows() > 0): $data_row_usaha = $data_usaha->row(); ?>
                        <a href="<?= base_url('admin/'.'usaha/transaksi/' . $data_row_usaha->id_usaha); ?>" class="btn btn-sm mt-2 btn-sm" style="background-color: orange;">Data Transaksi</a>
                        <?php endif; ?>
                        <div class="modal fade" id="modalDelete<?= $data_pj->id_pj ?>">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">Konfirmasi Hapus </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <p>Apakah Anda yakin menghapus Penjual <?= $data_pj->nama_pj ?>?</p>
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Tidak</button>
                                <a href="<?= base_url('admin/'.'penjual/delete/' . $data_pj->id_pj) ?>" type="button" class="btn btn-primary float-left">Hapus</a>
                              </div>
                            </div>
                            <!-- /.modal-content -->
                          </div>
                          <!-- /.modal-dialog -->
                        </div>
                        <!-- /.modal -->
                      </td>

                    </tr>
                  <?php
                    $no++;
                  }
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

      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php
    $this->load->view('admin/'.'template/footerjs');
    ?>
    <script>
      $(document).ready(()=>{
        $(".dataTable").DataTable();
      });
      function deletePenjual(id) {
        var confirmation = confirm(
          'Apakah Anda Yakin Menghapus data dari "' + id + '"?'
        );
        if (confirmation) {
          return window.location.href =
            "<?= base_url('admin/'.$menu . '/delete/'); ?>" + id;
        }
      }
    </script>
    <!-- CUSTOME JAVASCRIPT HERE -->
</body>

</html>