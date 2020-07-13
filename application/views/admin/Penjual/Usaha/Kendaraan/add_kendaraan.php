<?php
$this->load->view('admin/template/head');
?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.js"></script>
<!-- <script async defer src="http://maps.google.com/maps/api/js?key=AIzaSyAuoJ8tWSNs6owWkZsFI_Ssw4N_QOV__YM"></script> -->

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
                            <h1><a class="btn btn-secondary btn-sm" href="<?= base_url("admin/Usaha/detail/" . $id_usaha) ?>" title="Back" data-title="Back"><i class="fa fa-chevron-left"></i>
                            </a> Tambah Data Kendaraan</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- <div class="row"> -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <div class="card-title">TAMBAH DATA KENDARAAN
                                        </div>
                                    </div>
                                    <form role="form" action="<?= base_url('admin/Usaha/simpan_kendaraan') ?>" method="post">
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
                                                <div class="col-md-12">
                                                    <input type="hidden" name="id_usaha" value="<?=$id_usaha?>">
                                                    <div class="form-group">
                                                        <label for="nama_produk" class="form-control-label">Jenis Kendaraan</label>
                                                        <div>
                                                            <div class="input-group">
                                                                <span class="input-group-addon"></span>
                                                                <input type="text" required="" id="jenis_kendaraan" class="form-control" placeholder="Masukkan Jenis Kendaraan" name="jenis_kendaraan">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nama_produk" class="form-control-label">Plat Kendaraan</label>
                                                        <div>
                                                            <div class="input-group">
                                                                <span class="input-group-addon"></span>
                                                                <input type="text" required="" id="plat_kendaraan" class="form-control" placeholder="Masukkan Plat Kendaraan" name="plat_kendaraan" maxlength="8">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nama_produk" class="form-control-label">Kapasitas Kendaraan</label>
                                                        <div>
                                                            <div class="input-group">
                                                                <span class="input-group-addon"></span>
                                                                <input type="number" required="" id="kapasitas_kendaraan" class="form-control" placeholder="Masukkan Kapasitas Kendaraan" name="kapasitas_kendaraan" maxlength="8">
                                                            </div>
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
                        </div>
                    </div>
                </section>
            </div>
            <!-- </div> -->

            <?php
            $this->load->view('admin/template/footerjs');
            ?>
        </div>
    </body>

    </html>