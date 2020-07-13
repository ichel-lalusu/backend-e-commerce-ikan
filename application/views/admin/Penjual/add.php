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
              <h1><a class="btn btn-secondary" href="<?= base_url('admin/penjual') ?>" title="Back" data-title="Back"><i class="fa fa-chevron-left"></i>
                </a> Tambah Data Penjual</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- <div class="row"> -->
          <div class="card card-primary">
            <div class="card-header">
              <div class="card-title">Tambah Data Penjual
              </div>
            </div>
            <!-- <form role="form" action="<?= base_url($menu . '/update') ?>" method="post" novalidate="" enctype="multipart/form-data"> -->
            <form novalidate="" enctype="multipart/form-data" action="<?= base_url('admin/'.$menu . '/prosesadd') ?>" method="post">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="card" style="padding: 24px">
                      <!-- KONDISI ERROR -->
                      <?php
                      if ($this->session->flashdata('error')) {
                      ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                          <strong><?= $this->session->flashdata('error'); ?></strong>
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                      <?php
                      }
                      ?>
                      <div class="form-group">
                        <label class="form-control-label">Nama Lengkap </label>
                        <div>
                          <div class="input-group">
                            <input type="text" id="nama_pj" name="nama_pj" required class="form-control input-sm" placeholder="Contoh: Riselda Rahma Annisa Lalusu">
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="customFile">Foto Profil</label>
                        <div class="custom-file">
                          <input type="file" id="foto_pj" class="custom-file-input" name="foto_pj">
                          <label class="custom-file-label" for="foto_pj">Pilih Foto</label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-control-label">No. KTP</label>
                        <div>
                          <input type="number" id="noktp_pj" name="noktp_pj" required class="form-control input-sm" placeholder="(16 digit)">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="foto_ktp" class="form-control-label">Foto KTP</label>
                        <div class="custom-file">
                          <div class="custom-file form-control">Pilih Foto
                            <input type="file" id="fotoktp_pj" class="custom-file-input" name="fotoktp_pj">
                            <label class="custom-file-label" for="fotoktp_pj">Pilih Foto</label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-control-label">Jenis Kelamin</label>
                        <div>
                          <label class="rdiobox">
                            <input name="jk_pj" value="Laki-laki" type="radio" required>
                            <span>Laki-laki</span>
                          </label>
                          <label class="rdiobox" required>
                            <input name="jk_pj" value="Perempuan" type="radio" required>
                            <span>Perempuan</span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="card" style="padding: 24px">
                      <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <div class="input-group">
                          <div class="input-group-prepend form_datetime1">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                          </div>
                          <input type="date" required="" class="form-control" name="tgllahir_pj" id="tgllahir_pj">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-control-label">Alamat Lengkap</label>
                        <div>
                          <textarea rows="5" required="" class="form-control" id="alamat_pj" name="alamat_pj" placeholder="Alamat Lengkap"></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Telepon</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                          </div>
                          <input type="text" class="form-control" id="telp_pj" name="telp_pj" required type="number" placeholder="Contoh: 085261641500">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-control-label">Jenis Petani</label>
                        <div>
                          <select required class="form-control" name="jenis_petani" id="jenis_petani">
                            <option disabled="" selected="" label="Pilih Jenis Petani" value="">Pilih Jenis Petani</option>
                            <option value="tawar">Petani Air Tawar</option>
                            <option value="laut">Petani Air Laut</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <button class="btn btn-success float-right" type="submit">SIMPAN</button>
                <!-- <button class="btn btn-outline-danger" type="button" onclick="window.history.back();">Batal</button> -->
            </form>
          </div>
        </div>
    </div>
  </div>
  </section>

  <?php
  $this->load->view('admin/template/footerjs');
  ?>
  <!-- CUSTOME JAVASCRIPT HERE -->
  <script type="text/javascript">
    $(document).ready(function() {
      bsCustomFileInput.init();
    });
  </script>
</body>

</html>