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
                            <h1><a class="btn btn-secondary" href="<?= base_url('admin/Usaha/detail/' . $id_usaha) ?>" title="Back" data-title="Back"><i class="fa fa-chevron-left"></i>
                                </a> Ubah Data Kurir</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url("admin/Usaha/detail/" . $id_usaha) ?>">Usaha</a></li>
                                <li class="breadcrumb-item active"><span>Ubah Kurir</span></li>
                            </ol>
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
                            <div class="card-title">FORM UBAH KURIR
                            </div>
                        </div>
                        <form novalidate="" enctype="multipart/form-data" action="<?= base_url('admin/Kurir/simpan_ubah_kurir') ?>" method="post">
                        <input type="hidden" name="id_usaha" value="<?=$id_usaha?>">
                        <input type="hidden" name="id_kurir" value="<?=$kurir->id_kurir?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="nama_kurir">Nama Kurir:</label>
                                                    <input class="form-control" name="nama_kurir" type="text" id="nama_kurir" required="" autocomplete="off" autofocus="" placeholder="Nama Kurir/Pengantar" value="<?=$kurir->nama_kurir?>">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label" for="telp_kurir">No. Telp:</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">+62</span>
                                                        </div>
                                                        <input class="form-control" name="telp_kurir" type="num" id="telp_kurir" required="" placeholder="No Telp Kurir/Pengantar. Ex: +62.." autocomplete="off" maxlength="" value="<?=$kurir->nama_kurir?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="jk_kurir">Jenis Kelamin Kurir:</label>
                                                    <div>
                                                        <label for="L" class="btn btn-default btn-sm">
                                                            <input type="radio" name="jk_kurir" id="L" value="Laki-laki" <?=($kurir->jk_kurir=="Laki-laki") ? 'checked' : '';?> > Laki-laki
                                                        </label>
                                                        <label for="P" class="btn btn-default btn-sm">
                                                            <input type="radio" name="jk_kurir" id="P" value="Perempuan" <?=($kurir->jk_kurir=="Perempuan") ? 'checked' : '';?> > Perempuan
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label" for="foto_kurir">Foto Kurir:</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <?php if($kurir->foto_kurir!==""||!empty($kurir->foto_kurir)): ?>
                                                            <img src="<?=base_url('foto_kurir');?>" alt="Foto Kurir" width="100%" height="75%">
                                                            <?php else: ?>
                                                                <img src="" alt="Foto Kurir" width="100%" height="75%">
                                                            <?php endif; ?>
                                                            <input type="file" class="custom-file-input" id="foto_kurir" name="foto_kurir">
                                                            <label class="custom-file-label" for="foto_kurir">Pilih Foto Kurir</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="float-right">
                                    <a class="btn btn-danger" href="<?= base_url('admin/penjual') ?>">BATAL</a>
                                    <button class="btn btn-success" type="submit">SIMPAN</button>
                                </div>
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