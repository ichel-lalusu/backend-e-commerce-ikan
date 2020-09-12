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
              <h1><a class="btn btn-secondary btn-sm" href="<?= base_url('admin/penjual') ?>" title="Back" data-title="Back"><i class="fa fa-chevron-left"></i>
                </a> DETAIL PENJUAL -
                <?= ucwords($data_penjual->nama_pj); ?>
              </h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url("admin/penjual") ?>">Penjual</a></li>
                <li class="breadcrumb-item active"><span>Detail Penjual</span></li>
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
        <!-- Default box -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">DETAIL PENJUAL -
              <?= ucwords($data_penjual->nama_pj); ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                <i class="fas fa-times"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="card" style="padding: 24px">
              <div class="pd-x-30 pd">
                <div class="row">
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="row">
                      <label class="label-control col-sm-6">Nama Lengkap :</label>
                      <p class="col-sm-6"><?= $data_penjual->nama_pj ?></p>
                    </div>
                    <div class="row">
                      <label class="label-control col-sm-6">Foto Profil :</label>
                      <p class="col-sm-6"><img width="100" src="<?= base_url() . 'foto_penjual/' . $data_penjual->foto_pj ?>" alt="Foto Penjual <?= ucwords($data_penjual->foto_pj) ?>" class="img-circle"></p>
                    </div>
                    <div class="row">
                      <label class="label-control col-sm-6">No. KTP :</label>
                      <p class="col-sm-6"><?= $data_penjual->noktp_pj; ?></p>
                    </div>
                    <div class="row">
                      <label class="label-control col-sm-6">Jenis Kelamin :</label>
                      <p class="col-sm-6"><?= $data_penjual->jk_pj; ?></p>
                    </div>
                    <div class="row">
                      <label class="label-control col-sm-6">Tanggal Lahir :</label>
                      <p class="col-sm-6"><?= $data_penjual->tgllahir_pj; ?></p>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="row">
                      <label class="label-control col-sm-6">Alamat :</label>
                      <p class="col-sm-6"><?= $data_penjual->alamat_pj; ?></p>
                    </div>
                    <div class="row">
                      <label class="label-control col-sm-6">Foto KTP :</label>
                      <p class="col-sm-6"><img height="75%" width="100%" src="<?= base_url() . 'foto_ktp_penjual/' . $data_penjual->fotoktp_pj ?>" alt="Foto KTP <?= ucwords($data_penjual->fotoktp_pj) ?>"></p>
                    </div>
                    <div class="row">
                      <label class="label-control col-sm-6">No. Telp :</label>
                      <p class="col-sm-6"><?= $data_penjual->telp_pj; ?></p>
                    </div>
                    <div class="row">
                      <label class="label-control col-sm-6">Jenis Petani :</label>
                      <p class="col-sm-6">Air <?= $data_penjual->jenis_petani; ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php if ($data_usaha) :
          $nama_usaha = "- " . $data_usaha->nama_usaha;
        else :
          $nama_usaha = '';
        endif;
        ?>
        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">DATA USAHA <?= $nama_usaha ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="table-responsive">
            <?php if (!$data_usaha) : ?>
              <div class="card-body">
                <div class="pd-x-30 text-center">
                  <a class="btn btn-primary" href="<?= base_url('admin/usaha/add_usaha/' . $id_pj) ?>">TAMBAH USAHA</a>
                </div>
              </div>
            <?php endif; ?>
            <?php if ($data_usaha) : ?>
              <div class="card-body">
                <div class="pd-x-30 mg-t-10">
                  <table class="table table-hover table-bordered table-striped">
                    <thead class="thead-colored thead-teal" style="text-align: center">
                      <tr>
                        <th width="15%">Nama Usaha</th>
                        <th>Foto</th>
                        <th width="15%">Alamat</th>
                        <th width="10%">Jam Operasional</th>
                        <!-- <th>Jumlah dan Kapasitas Kapal</th> -->
                        <th>Jumlah Kolam</th>
                        <th>Longitude, Latitude</th>
                        <th width="12%">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                      ?>
                      <tr>
                        <td>
                          <p><?= $data_usaha->nama_usaha ?></p>
                        </td>
                        <td>
                          <?php
                          if ($data_usaha->foto_usaha != "" || $data_usaha->foto_usaha != null) {
                          ?>
                            <center><img height="150" src="<?= base_url() . 'foto_usaha/' . $data_usaha->foto_usaha ?>" alt="Foto Penjual <?= ucwords($data_usaha->foto_usaha) ?>"></center>
                          <?php
                          }
                          ?>
                        </td>
                        <td>
                          <p><?= $data_usaha->alamat_usaha ?>, <?= $data_usaha->kel ?>, <?= $data_usaha->kec ?>, <?= $data_usaha->kab ?></p>
                        </td>
                        <td class="text-center">
                          <p><?= substr($data_usaha->jamBuka, 0, 5) ?> - <?= substr($data_usaha->jamTutup, 0, 5) ?></p>
                        </td>

                        <td class="text-center">
                          <p><?= $data_usaha->jml_kolam ?></p>
                        </td>
                        <td class="text-center">
                          <p><?= $data_usaha->longitude ?>, <?= $data_usaha->latitude ?></p>
                        </td>
                        <td class="text-center">
                          <a href="<?= base_url('admin/usaha/edit/' . $data_usaha->id_usaha); ?>" class="btn btn-success m-1"><i class="fa fa-edit"></i></a>
                          <button class="btn btn-danger m-1" onclick="confirm<?= $data_usaha->id_usaha ?>()"><i class="fa fa-trash"></i></button>

                          <script type="text/javascript">
                            function confirm<?= $data_usaha->id_usaha ?>() {
                              var confirmation = confirm(
                                'Apakah Anda Yakin Menghapus semua data dari "<?= $data_usaha->nama_usaha ?>"?'
                              );
                              if (confirmation) {
                                return window.location.href =
                                  "<?= base_url('admin/' . $menu . '/delete/' . $data_usaha->id_usaha); ?>";
                              }
                            }
                            //DONE
                          </script>
                          <a href="<?= base_url('admin/Usaha/detail/' . $data_usaha->id_usaha); ?>" class="btn btn-primary m-1">Detail</a>
                        </td>
                      </tr>
                      <?php

                      // $no++;
                      // }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- /.card-footer-->
        <!-- /.card -->

      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->



    <?php
    $this->load->view('admin/template/footerjs');
    ?>
    <!-- CUSTOME JAVASCRIPT HERE -->
</body>

</html>