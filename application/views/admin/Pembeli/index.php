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
              <h1 style="text-transform: uppercase">DATA <?= ucwords($menu) ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
                <li class="breadcrumb-item active"><span>Pembeli</span></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card">
          <div class="card-header" style="background-color: maroon; color: white">
            <h3 class="card-title">Data <?= ucwords($menu) ?></h3>
          </div>
          <div class="card-body">
            <a href="<?= base_url('admin/Pembeli/add') ?>" class="btn btn-sm btn-info m-l-10 mb-3 float-right"><i class="fas fa-plus"></i> TAMBAH DATA PENJUAL</a>
            <div class="table-responsive">
              <table class="table table-hover table-bordered table-striped dataTable dtr-inline">
                <thead class="thead-colored thead-teal" style="text-align: center">
                  <tr>
                    <th class="text-center">No</th>
                    <th style="width: 10%">Nama</th>
                    <th class="text-center">Foto</th>
                    <th class="text-center">Jenis Kelamin</th>
                    <th class="text-center" style="width: 10%">Tgl Lahir</th>
                    <th class="text-center" style="width: 8%">No. Hp</th>
                    <th class="text-center" style="width: 13%">Alamat</th>
                    <th class="text-center">Longitude Latitude</th>
                    <th width="12%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  foreach ($data_pembeli->result() as $pembeli) {
                  ?>
                    <tr>
                      <td><?= $no; ?></td>
                      <td>
                        <p><?= $pembeli->nama_pb ?></p>
                      </td>
                      <td class="text-center">
                        <?php
                        if ($pembeli->foto_pb != "" || $pembeli->foto_pb != null) {
                        ?>
                          <img width="100" height="125" src="<?= base_url() . 'foto_pembeli/' . $pembeli->foto_pb ?>" alt="Foto Pembeli <?= ucwords($pembeli->nama_pb) ?>" class="img-circle">
                        <?php
                        }
                        ?>
                      </td>
                      <td>
                        <p><?= $pembeli->jk_pb ?></p>
                      </td>
                      <td>
                        <?=$pembeli->tgllahir_pb?>
                      </td>
                      <td>
                        <?=$pembeli->telp_pb?>
                      </td>
                      <td>
                        <?=$pembeli->alamat_pb; ?>, <?= $pembeli->kel_pb . ',&nbsp;' . $pembeli->kec_pb . ',&nbsp;' . $pembeli->kab_pb . ',&nbsp;' . 'Yogyakarta'?>
                      </td>
                      <td>
                        <?= $pembeli->longitude_pb . ',&nbsp;' . $pembeli->latitude_pb; ?>
                      </td>
                      <td class="text-center">
                        <a href="<?= base_url('admin/'.$menu . '/edit/' . $pembeli->id_pb); ?>" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalDelete<?= $pembeli->id_pb ?>"><i class="fa fa-trash"></i></button>
                        <a href="<?= base_url('admin/Pembeli/detail_pesanan/' . $pembeli->id_pb); ?>" class="btn btn-sm mt-2 btn-sm" style="background-color: orange;">Data Pesanan</a>
                        <div class="modal fade" id="modalDelete<?= $pembeli->id_pb ?>">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">Konfirmasi Hapus </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <p>Apakah Anda yakin menghapus Pembeli <?= $pembeli->nama_pb ?>?</p>
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Tidak</button>
                                <a href="<?= base_url('admin/Pembeli/delete/' . $data_pj->id_pb) ?>" type="button" class="btn btn-primary float-left">Hapus</a>
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
          <!-- <div class="card-footer">
            Footer
          </div> -->
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