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
              <h1><a class="btn btn-secondary btn-sm" href="<?= base_url("admin/penjual/detail/" . $data_usaha->id_pj) ?>" title="Back" data-title="Back"><i class="fa fa-chevron-left"></i>
                </a> DETAIL USAHA -
                <?= ucwords($data_usaha->nama_usaha); ?>
              </h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url("admin/Usaha") ?>">Usaha</a></li>
                <li class="breadcrumb-item active"><span>Detail Usaha</span></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="row">
          <div class="col-md-12">
            <!-- <?php if ($this->session->flashdata("success")) : ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> <?= $this->session->flashdata("success") ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php endif; ?> -->
          </div>
        </div>
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">DATA USAHA -
              <?= ucwords($data_usaha->nama_usaha); ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="pd-x-30 pd">
              <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                  <div class="row">
                    <label class="label-control col-sm-6">Nama Usaha :</label>
                    <p class="col-sm-6"><?= $data_usaha->nama_usaha ?></p>
                  </div>
                  <div class="row">
                    <label class="label-control col-sm-6">Foto Usaha :</label>
                    <p class="col-sm-6"><img width="200" src="<?= base_url() . 'foto_usaha/' . $data_usaha->foto_usaha ?>" alt="Foto Penjual <?= ucwords($data_usaha->foto_usaha) ?>"></p>
                  </div>
                  <div class="row">
                    <label class="label-control col-sm-6">Alamat Usaha :</label>
                    <p class="col-sm-6"><?= $data_usaha->alamat_usaha; ?></p>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                  <div class="row">
                    <label class="label-control col-sm-6">Jumlah Kolam :</label>
                    <p class="col-sm-6"><?= $data_usaha->jml_kolam; ?></p>
                  </div>
                  <div class="row">
                    <label class="label-control col-sm-6">Kabupaten :</label>
                    <p class="col-sm-6"><?= $data_usaha->kab; ?></p>
                  </div>
                  <div class="row">
                    <label class="label-control col-sm-6">Kecamatan :</label>
                    <p class="col-sm-6"><?= $data_usaha->kec; ?></p>
                  </div>
                  <div class="row">
                    <label class="label-control col-sm-6">Kelurahan:</label>
                    <p class="col-sm-6"><?= $data_usaha->kel; ?></p>
                  </div>
                  <div class="row">
                    <label class="label-control col-sm-6">Longitude:</label>
                    <p class="col-sm-6"><?= $data_usaha->longitude; ?></p>
                  </div>
                  <div class="row">
                    <label class="label-control col-sm-6">Latitude:</label>
                    <p class="col-sm-6"><?= $data_usaha->latitude; ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">DATA PRODUK</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <h4 class="tx-gray-800 mg-t-50">DATA PRODUK
              <a href="<?= base_url('admin/Produk/add_produk/' . $id_usaha) ?>" class="btn btn-info m-l-10 float-right"><i class="fa fa-plus"></i> TAMBAH DATA PRODUK</a></h4>
            <div class="mt-4 pd-x-30">
              <div class="row">
                <!-- <div class="pd-x-30 mg-t-10"> -->
                <div class="table-responsive">
                  <table class="table table-bordered table-condensed table-hover table-striped">
                    <thead class="thead-colored thead-teal">
                      <tr>
                        <th class="text-center">Nama Produk</th>
                        <th class="text-center">Gambar Produk</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Variasi</th>
                        <th class="text-center">Berat</th>
                        <th class="text-center">Min. Pemesanan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if ($data_produk->num_rows() > 0) {
                        foreach ($data_produk->result() as $produk) {
                          $harga = "";
                          if ($produk->minprice == $produk->maxprice) {
                            $harga = 'Rp ' . number_format($produk->minprice, 0, '', '.');
                          } else {
                            $harga = 'Rp ' . number_format($produk->minprice, 0, '', '.') . " ~ " . 'Rp ' . number_format($produk->maxprice, 0, '', '.');
                          }
                          $ID = $id_usaha . "/" . $produk->id_produk;
                      ?>
                          <tr>
                            <td><?= $produk->nama_produk ?></td>
                            <td class="text-center">
                              <img src="<?= base_url() . 'foto_usaha/produk/' . $produk->foto_produk ?>" alt="<?= $produk->nama_produk ?>" width="100" height="100">
                            </td>
                            <td class="text-center text-success" style="font-size: 16px"><?= $harga ?></td>
                            <td>
                              <ol>
                                <?php
                                $where = "id_produk = '$produk->id_produk' AND status_vp = 'aktif'";
                                $sql = $this->db->query("SELECT * FROM data_variasi_produk a JOIN data_variasi b ON a.id_variasi = b.id_variasi WHERE $where");
                                foreach ($sql->result() as $variasi) {
                                ?>
                                  <li><?= $variasi->nama_variasi; ?></li>
                                <?php
                                }
                                ?>
                              </ol>
                            </td>
                            <td class="text-center"><?= $produk->berat_produk . ' Ons' ?></td>
                            <td class="text-center"><?= $produk->min_pemesanan . ' Ons' ?></td>
                            <td class="text-center">
                              <?php
                              if ($produk->status_p == "aktif") :
                                echo "<p class='text-success'>" . ucwords($produk->status_p) . "</p>";
                              else :
                                echo "<p class='text-info'>" . ucwords($produk->status_p) . "</p>";
                              endif;
                              ?>
                            </td>
                            <td class="text-center">
                              <a href="<?= base_url('admin/Produk/edit_produk/' . $ID) ?>" class="btn btn-primary my-1"><i class="fas fa-edit"></i></a>
                              <?php if ($produk->status_p == "aktif") : ?>
                                <button class="btn btn-danger my-1" data-toggle="modal" title="Non Aktifkan Produk <?= ucwords($produk->nama_produk) ?>" data-target="#modalDisableProduk<?= $produk->id_produk ?>" onclick=""><i class="fa fa-exclamation-circle"></i></button>
                              <?php else : ?>
                                <button class="btn btn-success my-1" data-toggle="modal" title="Aktifkan Produk <?= ucwords($produk->nama_produk) ?>" data-target="#modalAktifProduk<?= $produk->id_produk ?>" onclick=""><i class="fa fa-check-circle"></i></button>
                              <?php endif; ?>
                            </td>
                          </tr>
                          <div id="modalDisableProduk<?= $produk->id_produk ?>" class="modal fade">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">Non-Aktifkan Produk</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  Nonaktifkan produk <?= ucwords($produk->nama_produk) . '?'; ?>
                                </div>
                                <div class="modal-footer">
                                  <div class="float-right">
                                    <a href="<?= base_url('admin/Produk/matikan_produk/' . $ID) ?>" class="btn btn-danger">Non Aktifkan</a>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                  </div>
                                </div>
                              </div>

                            </div>
                          </div>
                          <div id="modalAktifProduk<?= $produk->id_produk ?>" class="modal fade">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">Aktifkan Produk</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  Aktifkan produk <?= ucwords($produk->nama_produk) . '?'; ?>
                                </div>
                                <div class="modal-footer">
                                  <div class="float-right">
                                    <a href="<?= base_url('admin/Produk/aktifkan_produk/' . $ID) ?>" class="btn btn-success">Aktifkan</a>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                  </div>
                                </div>
                              </div>

                            </div>
                          </div>
                      <?php
                        }
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
                <!-- </div> -->
              </div>
            </div>
          </div>
          <div class="card-footer">
            Footer
          </div>
        </div>
        <!-- /.card-body -->

        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">DATA KURIR</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <h4 class="tx-gray-800 mg-t-50">DATA KURIR
              <a href="<?= base_url('admin/Kurir/add_kurir/' . $id_usaha) ?>" class="btn btn-info m-l-10 float-right"><i class="fa fa-plus"></i> TAMBAH DATA KURIR</a></h4>
            <div class="mt-4 pd-x-30">
              <div class="row">
                <table class="table table-bordered table-hover" id="data-kurir">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Nama Kurir</th>
                      <th>Foto</th>
                      <th>Jenis Kelamin</th>
                      <th>No. HP</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody id="data-body-kurir">
                    <?php
                    if ($data_kurir->num_rows() > 0) :
                      $urutan = 1;
                      foreach ($data_kurir->result() as $key) :
                        $id_kurir = $key->id_kurir;
                        $foto_kurir = base_url() . "foto_kurir/" . $key->foto_kurir;
                    ?>
                        <tr>
                          <td class="text-center"><?= $urutan ?></td>
                          <td><?= $key->nama_kurir ?></td>
                          <td class="text-center"><img src="<?= $foto_kurir ?>" alt="" class="circle responsive-img" style="width: 56px; height: 56px"></td>
                          <td class="text-center"><?= $key->jk_kurir ?></td>
                          <td class="text-center"><?= $key->telp_kurir ?></td>
                          <td class="text-center"><a class="btn btn-success" href="<?= base_url('admin/Kurir/ubah_kurir/') . $key->id_kurir ?>/<?= $id_usaha; ?>"><span class="fa fa-edit"></span></a>&nbsp;
                            <a href="#modalDeleteKurir<?= $key->id_kurir;?>" data-toggle="modal" class="btn btn-danger"><span class="fa fa-trash"></span></a></td>
                        </tr>
                        <div id="modalDeleteKurir<?=  $key->id_kurir ?>" class="modal fade">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">Hapus Kurir</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">×</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                Hapus Kurir <?= ucwords($key->nama_kurir) . '?'; ?>
                              </div>
                              <div class="modal-footer">
                                <div class="float-right">
                                  <a href="<?= base_url('admin/Kurir/delete_kurir/'.$key->id_kurir . "/" . $id_usaha) ?>" class="btn btn-danger">Hapus</a>
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                </div>
                              </div>
                            </div>

                          </div>
                        </div>
                    <?php
                        $urutan++;
                      endforeach;
                    endif;
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">DATA KENDARAAN</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <h4 class="tx-gray-800 mg-t-50">DATA KENDARAAN
              <a href="<?= base_url('admin/Usaha/add_kendaraan/' . $id_usaha) ?>" class="btn btn-info m-l-10 float-right"><i class="fa fa-plus"></i> TAMBAH DATA KENDARAAN</a></h4>
            <div class="mt-4 pd-x-30">
              <div class="row">
                <table class="table table-bordered table-hover" id="data-kurir">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Jenis Kendaraan</th>
                      <th>No. Polisi</th>
                      <th>Kapasitas Kendaraan</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody id="data-body-kurir">
                    <?php
                    if ($data_kendaraan->num_rows() > 0) :
                      $urutan = 1;
                      foreach ($data_kendaraan->result() as $key) :
                        $id_kendaraan = $key->id_kendaraan;
                    ?>
                        <tr>
                          <td class="text-center"><?= $urutan ?></td>
                          <td><?= $key->jenis_kendaraan ?></td>
                          <td class="text-center"><?= $key->plat_kendaraan ?></td>
                          <td class="text-center"><?= $key->kapasitas_kendaraan ?></td>
                          <td class="text-center"><a class="btn btn-success" href="<?= base_url('admin/Usaha/edit_kendaraan/') . $key->id_kendaraan ?>/<?= $id_usaha ?>"><span class="fa fa-edit"></span></a>&nbsp;
                            <a href="<?= base_url('admin/Usaha/delete_kendaraan/') . $key->id_kendaraan ?>/<?= $id_usaha ?>" class="btn btn-danger"><span class="fa fa-trash"></span></a></td>
                        </tr>
                    <?php
                        $urutan++;
                      endforeach;
                    endif;
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- /.card-footer-->
    </div>
    <!-- /.card -->


    <!-- /.content -->
    <?php
    $this->load->view('admin/template/footerjs');
    ?>
  </div>
  <script type="text/javascript">
    $(document).ready(function() {
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });
      <?php if ($this->session->flashdata("success")) : ?>
        Toast.fire({
          type: 'success',
          title: '<?= $this->session->flashdata("success"); ?>'
        });
      <?php endif; ?>
      <?php if ($this->session->flashdata("error")) : ?>
        Toast.fire({
          type: 'error',
          title: '<?= $this->session->flashdata("error"); ?>'
        });
      <?php endif; ?>
    });
  </script>
  <!-- /.content-wrapper -->




  <!-- CUSTOME JAVASCRIPT HERE -->
</body>

</html>