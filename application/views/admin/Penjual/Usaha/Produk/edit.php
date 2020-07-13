<?php
$this->load->view('template/head');
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
        $this->load->view('template/nav');
        ?>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><a class="btn btn-secondary btn-sm" href="<?= base_url("penjual/detail/usaha/" . $data_produk->id_usaha) ?>" title="Back" data-title="Back"><i class="fa fa-chevron-left"></i>
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
                        <form role="form" action="<?= base_url($menu . '/update') ?>" method="post" novalidate="" enctype="multipart/form-data">
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
                                            <input type="hidden" name="id" value="<?= $data_produk->id_produk ?>">
                                            <input type="hidden" name="hiddenfoto_pj" value="<?= $data_produk->foto_produk ?>">
                                            <div class="form-group">
                                                <label for="nama_produk" class="form-control-label">Nama Produk</label>
                                                <div>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"></span>
                                                        <input type="text" required="" id="nama_produk" class="form-control" placeholder="Nama Produk" name="nama_produk" value="<?= $data_produk->nama_produk ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kategori" class="form-control-label">Kategori</label>
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" id="customRadio1" name="kategori" value="tawar" disabled <?= ($data_produk->kategori == "tawar") ? "checked" : ""; ?>>
                                                    <label for="customRadio1" class="custom-control-label">Air Tawar</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" id="customRadio2" name="kategori" value="laut" disabled <?= ($data_produk->kategori == "laut") ? "checked" : ""; ?>>
                                                    <label for="customRadio2" class="custom-control-label">Air Laut</label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="foto_produk">Foto Produk</label>
                                                <div>
                                                    <?php
                                                    if ($data_produk->foto_produk != "" || $data_produk->foto_produk != null) {
                                                    ?>
                                                        <center><img height="130 !important" src="<?= $url_API . 'foto_usaha/produk/' . $data_produk->foto_produk ?>" alt="Foto Produk"></center>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div class="custom-file form-control">
                                                            <input type="file" id="foto_usaha" class="custom-file-input" name="foto_usaha">
                                                            <label class="custom-file-label">Pilih Foto</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card" style="padding: 24px">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="berat_produk" class="form-control-label">Berat Produk</label>
                                                        <div class="input-group">
                                                            <input type="number" required="" id="berat_produk" class="form-control" placeholder="Berat Produk" name="berat_produk" value="<?= $data_produk->berat_produk ?>">
                                                            <span class="input-group-addon">&nbsp Ons</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="min_pemesanan" class="form-control-label">Minimal Pemesanan</label>
                                                        <div class="input-group">
                                                            <input type="number" required="" id="min_pemesanan" class="form-control" placeholder="Minimal Pemesanan" name="min_pemesanan" value="<?= $data_produk->min_pemesanan ?>">
                                                            <span class="input-group-addon">&nbsp Ons</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="row">
                                                <div class="col s12">
                                                    <div class="form-group">
                                                        <label for="nama_variasi" class="form-control-label">Variasi</label>
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" type="checkbox" id="nama_variasi" value="1">
                                                            <label for="nama_variasi" class="custom-control-label">Mentah Utuh</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" type="checkbox" id="nama_variasi" value="2">
                                                            <label for="nama_variasi" class="custom-control-label">Mentah Potong</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" type="checkbox" id="nama_variasi" value="3">
                                                            <label for="nama_variasi" class="custom-control-label">Hidup</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="row" style="margin-top: 40px">
                                                <div class="col s12">
                                                    <div class="card" style="padding: 24px">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Variasi</th>
                                                                    <th>Harga</th>
                                                                    <th>Stok</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Mentah Utuh</td>
                                                                    <td>Rp</td>
                                                                    <td>ons</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Mentah Potong</td>
                                                                    <td>Rp</td>
                                                                    <td>ons</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Hidup</td>
                                                                    <td>Rp</td>
                                                                    <td>ons</td>
                                                                </tr>
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
        $this->load->view('template/footerjs');
        ?>
        <script async defer src="http://maps.google.com/maps/api/js?key=AIzaSyAuoJ8tWSNs6owWkZsFI_Ssw4N_QOV__YM">
        </script>
        <script type="text/javascript">
            var firstLt, firstLg;
            first();

            function first() {
                var onSuccess = function(position) {
                    var element = document.getElementById('map');
                    var marker;
                    var posisilat;
                    var posisilng;
                    firstLt = parseFloat('<?= $data_usaha->latitude ?>');
                    firstLg = parseFloat('<?= $data_usaha->longitude ?>');
                    initMap(firstLt, firstLg);
                    /*if(function_exists('initMap')){
                      initMap(position.coords.latitude, position.coords.longitude);  
                    }*/

                };
                navigator.geolocation.getCurrentPosition(onSuccess, onError);
            }

            // onError Callback receives a PositionError object
            //
            function onError(error) {
                alert('code: ' + error.code + '\n' +
                    'message: ' + error.message + '\n');
            }
        </script>

        <script type="text/javascript">
            function taruhMarker(peta, posisiTitik) {
                if (marker) {
                    // pindahkan marker
                    marker.setPosition(posisiTitik);
                } else {
                    // buat marker baru
                    marker = new google.maps.Marker({
                        position: posisiTitik,
                        map: peta
                    });
                }
                posisilat = posisiTitik.lat();
                posisilng = posisiTitik.lng();
                console.log("Posisi marker: " + posisilat + "," + posisilng);
                $("body").find("input[name='latitude']").val(posisilat);
                //$("#latitude_pb").val(posisilat);
                //$("#longitude_pb").val(posisilng);
                $("body").find("input[name='longitude']").val(posisilng);
            }


            function initMap(lat, lng) {
                $("body").find("input[name='latitude']").val(lat);
                $("body").find("input[name='longitude']").val(lng);
                var propertiPeta = {
                    center: new google.maps.LatLng(lat, lng), //nentuin titik pusat nya dimana (awal map kebuka, bukan posisi marker)
                    zoom: 17, //semakin banyak semakin dekat min 1 maksimal
                    mapTypeId: google.maps.MapTypeId.ROADMAP //roadmap, satelite, hybrid, terrain
                };
                var point = new google.maps.LatLng(lat, lng);
                var peta = new google.maps.Map(document.getElementById("map"), propertiPeta); //utama bikin map
                marker = new google.maps.Marker({
                    position: point,
                    map: peta
                    //icon
                });
                google.maps.event.addListener(peta, 'click', function(event) {
                    taruhMarker(this, event.latLng);
                });
            }
        </script>

        <!-- CUSTOME JAVASCRIPT HERE -->
        <script type="text/javascript">
            $(document).ready(function() {

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