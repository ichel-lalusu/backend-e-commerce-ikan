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
                            <h1><a class="btn btn-secondary btn-sm" href="<?= base_url("admin/usaha/detail/" . $id_usaha) ?>" title="Back" data-title="Back"><i class="fa fa-chevron-left"></i>
                                </a> Edit Data Produk</h1>
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
                            <div class="card-title">Edit Data Produk - <?= $data_produk->nama_produk ?>
                            </div>
                        </div>
                        <form role="form" action="<?= base_url('admin/Produk/update_produk') ?>" method="post" novalidate="" enctype="multipart/form-data">
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
                                            <input type="hidden" name="id_produk" value="<?= $data_produk->id_produk ?>">
                                            <input type="hidden" name="id_usaha" value="<?=$id_usaha;?>">
                                            <div class="form-group">
                                                <label for="nama_produk" class="form-control-label">Nama Produk</label>
                                                <div>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"></span>
                                                        <input type="text" required="" id="nama_produk" class="form-control" placeholder="Nama Produk" name="nama_produk" value="<?= $data_produk->nama_produk ?>">
                                                        <input type="hidden" name="kategori" value="<?= $data_produk->kategori ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="foto_produk">Foto Produk</label>
                                                <div>
                                                    <?php
                                                    if ($data_produk->foto_produk != "" || $data_produk->foto_produk != null) {
                                                    ?>
                                                        <center><img height="130" class="mb-2" src="<?= base_url() . 'foto_usaha/produk/' . $data_produk->foto_produk ?>" alt="Foto Produk"></center>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div class="custom-file form-control">
                                                            <input type="file" id="foto_produk" class="custom-file-input" name="foto_produk">
                                                            <label class="custom-file-label">Pilih Foto</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="berat_produk" class="form-control-label">Berat Produk</label>
                                                    <div class="input-group">
                                                        <input type="number" required="" id="berat_produk" class="form-control" placeholder="Berat Produk" name="berat_produk" value="<?= $data_produk->berat_produk ?>">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">&nbsp Ons</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="min_pemesanan" class="form-control-label">Minimal Pemesanan</label>
                                                    <div class="input-group">
                                                        <input type="number" required="" id="min_pemesanan" class="form-control" placeholder="Minimal Pemesanan" name="min_pemesanan" value="<?= $data_produk->min_pemesanan ?>">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">&nbsp Ons</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card" style="padding: 24px">
                                            <div class="row" style="margin-top: 40px">
                                                <div class="col s12">
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
                                                                <?php foreach ($data_variasi->result() as $key) :
                                                                    $this->db->reset_query();
                                                                    $this->db->where("id_produk", $data_produk->id_produk);
                                                                    $this->db->where("id_variasi", $key->id_variasi);
                                                                    $this->db->select("`id_variasiproduk`, `id_produk`, `id_variasi`, `harga`, `stok`, `status_vp`");
                                                                    $data_variasi_produk = $this->db->get("data_variasi_produk");
                                                                    if ($data_variasi_produk->num_rows() > 0) :
                                                                        $result_data_variasi_produk = $data_variasi_produk->row();
                                                                        $harga = $result_data_variasi_produk->harga;
                                                                        $stok = $result_data_variasi_produk->stok;
                                                                        $status_vp = $result_data_variasi_produk->status_vp;
                                                                        // echo $status_vp;
                                                                    endif;
                                                                    $result_data_variasi_produk = $data_variasi_produk->row();
                                                                    $bg_row = ($result_data_variasi_produk->status_vp=="aktif") ? '' : 'bg-light';
                                                                    $disabled_input = ($result_data_variasi_produk->status_vp=="aktif") ? '' : 'disabled';
                                                                    $check_status = ($result_data_variasi_produk->status_vp=="aktif") ? 'checked' : '';
                                                                ?>
                                                                    <tr id="variasi-<?=$key->id_variasi?>" class="<?=$bg_row?>">
                                                                        <td><?= $key->nama_variasi ?></td>
                                                                        <input type="hidden" name="jenis_variasi[]" value="<?=$key->id_variasi?>">
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">Rp&nbsp;</span>
                                                                                </div>
                                                                                <input <?=$disabled_input?> type="number" required="" id="harga-<?= $key->id_variasi ?>" class="form-control" placeholder="Harga" name="harga[]" value="<?= $harga ?>" autocomplete="off">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <input <?=$disabled_input?> type="number" required="" id="stok-<?= $key->id_variasi ?>" class="form-control" placeholder="Stok" name="stok[]" value="<?= $stok ?>" autocomplete="off">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">&nbsp;Ons</span>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <input <?=$check_status?> type="checkbox" name="status[]" id="status-<?= $key->id_variasi ?>" value="<?= $key->id_variasi ?>" autocomplete="off">
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
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
            </section>
        </div>
        <!-- </div> -->

        <?php
        $this->load->view('admin/template/footerjs');
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
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
        </script>
</body>

</html>