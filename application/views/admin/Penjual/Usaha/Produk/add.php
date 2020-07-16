<?php
$this->load->view('admin/template/head');
?>

<style type="text/css">
    #map {
        height: 400px;
        /* The height is 400 pixels */
        width: 100%;
        /* The width is the width of the web page */
    }
</style>

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
                            <h1><a class="btn btn-secondary btn-sm" href="<?= base_url("admin/usaha/detail/" . $id_usaha) ?>" title="Back" data-title="Back"><i class="fa fa-chevron-left"></i>
                                </a> Tambah Data Produk</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- <div class="row"> -->
                    <div class="card card-success">
                        <div class="card-header">
                            <div class="card-title">Tambah Data Produk
                            </div>
                        </div>
                        <form role="form" action="<?= base_url('admin/Produk/add') ?>" method="post" novalidate="" enctype="multipart/form-data">
                            <input type="hidden" name="kategori" value="Tawar">
                            <input type="hidden" name="id_usaha" value="<?= $id_usaha; ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php if ($this->session->flashdata("error")) : ?>
                                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                <strong>Gagal</strong> <?= $this->session->flashdata("error") ?>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card" style="padding: 24px">
                                            <div class="form-group">
                                                <label for="nama_produk" class="form-control-label">Nama Produk</label>
                                                <div>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"></span>
                                                        <input type="text" required="" id="nama_produk" class="form-control" placeholder="Nama Produk" name="nama_produk" autofocus autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="foto_produk">Foto Produk</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div class="custom-file form-control">
                                                            <input type="file" id="foto_produk" class="custom-file-input" name="foto_produk">
                                                            <label class="custom-file-label">Pilih Foto</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="berat_produk" class="form-control-label">Berat Produk</label>
                                                <div class="input-group">
                                                    <input type="number" required="" id="berat_produk" class="form-control" placeholder="Berat Produk" name="berat_produk">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">&nbsp Ons</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="min_pemesanan" class="form-control-label">Minimal Pemesanan</label>
                                                <div class="input-group">
                                                    <input type="number" required="" id="min_pemesanan" class="form-control" placeholder="Minimal Pemesanan" name="min_pemesanan">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">&nbsp Ons</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card" style="padding: 24px">
                                            <b class="text-center">Variasi Produk</b>
                                            <hr>
                                            <div class="card" style="padding: 24px">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th width="30%">Variasi</th>
                                                            <th>Harga</th>
                                                            <th>Stok</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($data_variasi->result() as $key) :
                                                        ?>
                                                            <tr id="variasi-<?= $key->id_variasi; ?>" class="bg-light">
                                                                <td><?= $key->nama_variasi; ?></td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Rp</span>
                                                                        </div>
                                                                        <input type="number" disabled required="" id="harga-<?= $key->id_variasi ?>" class="form-control" placeholder="Berat Produk" name="harga[]" value="0">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input type="number" disabled required="" id="stok-<?= $key->id_variasi ?>" class="form-control" placeholder="Stok <?= $key->nama_variasi ?>" name="stok[]" value="0">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">&nbsp Ons</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input type="checkbox" name="status[]" id="status-<?= $key->id_variasi ?>" value="<?= $key->id_variasi ?>">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="float-right">
                                    <button class="btn btn-danger" onclick="history.back()" type="button" data-toggle="tooltip" data-title="BATAL">BATAL</button>
                                    <button class="btn btn-success" type="submit" data-toggle="tooltip" data-title="SIMPAN">SIMPAN</button>
                                </div>
                                <!-- <button class="btn btn-outline-danger" type="button" onclick="window.history.back();">Batal</button> -->
                            </div>
                        </form>
                    </div>
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
                var m = 0;
                $("input:checkbox").change(function() {
                    // console.log($(this));
                    console.log($(this).prop("checked"));
                    console.log($(this).val());
                    var valueCheckedbox = $(this).val();
                    var idVariasi = $("#variasi-" + valueCheckedbox);
                    var variasiInit  = $("#variasi-" + valueCheckedbox);
                    if ($(this).prop("checked")) {
                        // console.log(idVariasi);
                        variasiInit.removeClass("bg-light");
                        variasiInit.find("#harga-" + valueCheckedbox).removeAttr("disabled");
                        variasiInit.find("#stok-" + valueCheckedbox).removeAttr("disabled");
                    } else {
                        variasiInit.addClass("bg-light");
                        variasiInit.find("#harga-" + valueCheckedbox).attr("disabled", true);
                        variasiInit.find("#stok-" + valueCheckedbox).attr("disabled", true);
                    }
                });
            });
            bsCustomFileInput.init();
            //Timepicker
            $('#timepicker, #timepicker2').datetimepicker({
                toolbarPlacement: 'bottom',
                widgetPositioning: {
                    // vertical: 'top',
                },
                format: 'LT',
                dayViewHeaderFormat: 'HH:mm'
            });
        </script>
</body>

</html>