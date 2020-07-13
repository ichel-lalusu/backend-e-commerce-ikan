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
              <h1><a class="btn btn-secondary btn-sm" href="<?= base_url('penjual') ?>" title="Back" data-title="Back"><i class="fa fa-chevron-left"></i>
                </a> Edit Data Penjual</h1>
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
              <div class="card-title">Edit Data Penjual
              </div>
            </div>
            <form role="form" action="<?= base_url('admin/'.$menu . '/update') ?>" method="post" novalidate="" enctype="multipart/form-data">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="card" style="padding: 24px">
                      <input type="hidden" name="id" value="<?= $data_penjual->id_pj ?>">
                      <input type="hidden" name="hiddenfoto_pj" value="<?= $data_penjual->foto_pj ?>">
                      <input type="hidden" name="hiddenfotoktp_pj" value="<?= $data_penjual->fotoktp_pj ?>">
                      <div class="form-group">
                        <label for="nama_penjual" class="form-control-label">Nama Lengkap</label>
                        <div>
                          <div class="input-group">
                            <span class="input-group-addon"></span>
                            <input type="text" required="" id="nama_pj" class="form-control" placeholder="Nama Lengkap" name="nama_pj" value="<?= $data_penjual->nama_pj ?>">
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="foto_penjual">Foto Profil</label>
                        <div>
                          <?php
                          if ($data_penjual->foto_pj != "" || $data_penjual->foto_pj != null) {
                          ?>
                            <center><img height="110 !important" src="<?= $url_API . 'foto_penjual/' . $data_penjual->foto_pj ?>" alt="Foto Penjual <?= ucwords($data_penjual->foto_pj) ?>" class="img-circle"></center>
                          <?php
                          }
                          ?>
                        </div>
                        <div class="input-group">
                          <div class="custom-file">
                            <div class="custom-file form-control">
                              <input type="file" id="foto_penjual" class="custom-file-input" name="foto_pj">
                              <label class="custom-file-label">Pilih Foto</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="noktp_penjual" class="form-control-label">No. KTP:</label>
                        <input type="text" class="form-control" name="noktp_pj" id="noktp_penjual" placeholder="No. KTP Penjual" value="<?= $data_penjual->noktp_pj; ?>">
                      </div>
                      <div class="form-group">
                        <label for="foto_ktp">Foto KTP</label>
                        <div>
                          <?php
                          if ($data_penjual->fotoktp_pj != "" || $data_penjual->fotoktp_pj != null) {
                          ?>
                            <center><img height="150 !important" src="<?= $url_API . 'foto_ktp_penjual/' . $data_penjual->fotoktp_pj ?>" alt="KTP <?= ucwords($data_penjual->fotoktp_pj) ?>"></center>
                          <?php
                          }
                          ?>
                        </div>
                        <div class="input-group">
                          <div class="custom-file">
                            <div class="custom-file form-control">
                              <input type="file" id="fotoktp_pj" class="custom-file-input" name="fotoktp_pj">
                              <label class="custom-file-label">Pilih Foto</label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="card" style="padding: 24px">
                      <div class="form-group">
                        <label for="jk_penjual" class="form-control-label">Jenis Kelamin</label>
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="customRadio1" name="jk_pj" value="Laki-laki" <?= ($data_penjual->jk_pj == "Laki-laki") ? "checked" : ""; ?>>
                          <label for="customRadio1" class="custom-control-label">Laki-laki</label>
                        </div>
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="customRadio2" name="jk_pj" value="Perempuan" <?= ($data_penjual->jk_pj == "Perempuan") ? "checked" : ""; ?>>
                          <label for="customRadio2" class="custom-control-label">Perempuan</label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <div class="input-group">
                          <div class="input-group-prepend form_datetime1">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                          </div>
                          <input type="date" required="" class="form-control" name="tgllahir_pj" value="<?= $data_penjual->tgllahir_pj ?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-control-label">Alamat</label>
                        <div>
                          <textarea rows="8" required="" name="alamat_pj" class="form-control" placeholder="Alamat Penjual"><?= $data_penjual->alamat_pj ?></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Telepon</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                          </div>
                          <input type="number" inputmode="tel" class="form-control" id="phoneMask" name="telp_pj" value="<?= $data_penjual->telp_pj ?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-control-label">Jenis Petani </label>
                        <div>
                          <select required class="form-control" name="jenis_petani" id="jenis_petani">
                            <option disabled="" selected="" label="Pilih Jenis Petani" value="">Pilih Jenis Petani</option>
                            <option value="Tawar" <?= ($data_penjual->jenis_petani == "Tawar") ? "selected" : ""; ?>>Petani Air Tawar</option>
                            <option value="Laut" <?= ($data_penjual->jenis_petani == "Laut") ? "selected" : ""; ?>>Petani Air Laut</option>
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
          <!-- </div> -->
        </div>
      </section>
    </div>
    <!-- </div> -->

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