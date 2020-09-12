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
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Data <?= ucwords($menu) ?></h3>
          </div>
          <div class="card-body">
            <a href="<?= base_url('admin/Pembeli/add') ?>" class="btn btn-sm btn-info m-l-10 mb-3 float-right"><i class="fas fa-plus"></i> TAMBAH DATA PEMBELI</a>
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
                <tbody id="data-pembeli">

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
      $(document).ready(() => {
        load_data_pembeli();
      });

      function deletePenjual(id) {
        var confirmation = confirm(
          'Apakah Anda Yakin Menghapus data dari "' + id + '"?'
        );
        if (confirmation) {
          return window.location.href =
            "<?= base_url('admin/' . $menu . '/delete/'); ?>" + id;
        }
      }

      function load_data_pembeli() {
        var data_tabel_pembeli = ``;
        fetch('<?= base_url('api/pembeli') ?>').then(response => response.json()).then(data => {
          var $no = 1;
          // console.log(data);
          data.forEach(el => {
            // console.log(el.nama_pb);
            let nama_pb = el.nama_pb;
            let foto_pb = (el.foto_pb != "" || el.foto_pb != null) ? `<?= base_url('foto_pembeli/') ?>${el.foto_pb}` : '';
            let jk_pb = el.jk_pb,
              tgl_lahir = el.tgllahir_pb,
              no_telp = el.telp_pb,
              alamat = el.alamat_pb,
              kelurahan = el.kel_pb,
              kecamatan = el.kec_pb,
              kabupaten = el.kab_pb,
              id_pb = el.id_pb;
            let url_detail = `<?= base_url('admin/' . $menu . '/detail/') ?>${id_pb}`;
            let url_edit = `<?= base_url('admin/' . $menu . '/edit/') ?>${id_pb}`;
            let url_delete = `<?= base_url('admin/' . $menu . '/delete/') ?>${id_pb}`;
            let url_pemesanan = `<?= base_url('admin/Pembeli/pesanan_pembeli/') ?>${id_pb}`;
            let latitude = el.latitude_pb,
              longutude = el.longitude_pb;
            data_tabel_pembeli += `
            <tr>
                      <td>${$no}</td>
                      <td>
                        <p>${nama_pb}</p>
                      </td>
                      <td class="text-center">
                      <img width="100" height="125" src="${foto_pb}" alt="Foto Pembeli ${nama_pb.toUpperCase()}" class="img-circle">
                      </td>
                      <td>
                        <p>${jk_pb}</p>
                      </td>
                      <td>${tgl_lahir}</td>
                      <td>${no_telp}</td>
                      <td>${alamat},${kelurahan},&nbsp;${kecamatan},&nbsp;${kabupaten},&nbsp;Yogyakarta
                      </td>
                      <td>${longutude}, ${latitude}</td>
                      <td class="text-center">
                        <a href="${url_detail}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-info">Detail</a>
                        <a href="${url_edit}" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalDelete${id_pb}"><i class="fa fa-trash"></i></button>
                        <a href="${url_pemesanan}" class="btn btn-sm mt-2 btn-sm" style="background-color: orange;">Data Pesanan</a>
                        <div class="modal fade" id="modalDelete${id_pb}">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">Konfirmasi Hapus </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <p>Apakah Anda yakin menghapus Pembeli ${nama_pb}?</p>
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Tidak</button>
                                <a href="${url_delete}" type="button" class="btn btn-primary float-left">Hapus</a>
                              </div>
                            </div>
                            <!-- /.modal-content -->
                          </div>
                          <!-- /.modal-dialog -->
                        </div>
                        <!-- /.modal -->
                      </td>
                    </tr>`;
          });
          document.getElementById("data-pembeli").innerHTML = data_tabel_pembeli;
          $(".dataTable").DataTable();
        });
      }
    </script>
    <!-- CUSTOME JAVASCRIPT HERE -->
</body>

</html>