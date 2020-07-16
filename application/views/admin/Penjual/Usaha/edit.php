<?php
$this->load->view('admin/template/head');
?>
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.js"></script> -->
<script async defer src="http://maps.google.com/maps/api/js?key=AIzaSyAuoJ8tWSNs6owWkZsFI_Ssw4N_QOV__YM"></script>

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
                            <h1><a class="btn btn-secondary btn-sm" href="#!" onclick="location.replace('<?= base_url("admin/penjual/detail/" . $data_usaha->id_pj) ?>');" title="Back" data-title="Back"><i class="fa fa-chevron-left"></i>
                                </a> Edit Data Usaha</h1>
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
                            <div class="card-title">Edit Data Usaha
                            </div>
                        </div>
                        <form role="form" action="<?= base_url('admin/' . $menu . '/update') ?>" method="post" novalidate="" enctype="multipart/form-data">
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
                                            <input type="hidden" name="id" value="<?= $data_usaha->id_usaha ?>">
                                            <div class="form-group">
                                                <label for="nama_usaha" class="form-control-label">Nama Lengkap</label>
                                                <div>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"></span>
                                                        <input type="text" required="" id="nama_usaha" class="form-control" placeholder="Nama Usaha" name="nama_usaha" value="<?= $data_usaha->nama_usaha ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="foto_usaha">Foto Usaha</label>
                                                <div>
                                                    <?php
                                                    if ($data_usaha->foto_usaha != "" || $data_usaha->foto_usaha != null) {
                                                    ?>
                                                        <center><img height="75%" width="100%" src="<?= $url_API . 'foto_usaha/' . $data_usaha->foto_usaha ?>" alt="Foto Usaha"></center>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="input-group mt-1">
                                                    <div class="custom-file">
                                                        <div class="custom-file form-control">
                                                            <input type="file" id="foto_usaha" class="custom-file-input" name="foto_usaha">
                                                            <label class="custom-file-label">Pilih Foto</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label">Alamat Usaha</label>
                                                <div>
                                                    <textarea rows="2" required="" name="alamat_usaha" class="form-control" placeholder="Alamat Usaha"><?= $data_usaha->alamat_usaha ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="bootstrap-timepicker">
                                                        <div class="form-group">
                                                            <label>Jam Buka</label>
                                                            <div class="input-group date" id="timepicker" data-target-input="nearest">
                                                                <input type="text" name="jamBuka" value="<?= $data_usaha->jamBuka ?>" class="form-control datetimepicker-input" data-target="#timepicker" />
                                                                <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="bootstrap-timepicker">
                                                        <div class="form-group">
                                                            <label>Jam Tutup</label>
                                                            <div class="input-group date" id="timepicker2" data-target-input="nearest">
                                                                <input type="text" name="jamTutup" value="<?= $data_usaha->jamTutup ?>" class="form-control datetimepicker-input" data-target="#timepicker2" />
                                                                <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ($data_usaha->jenis_petani == "Laut") : ?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="jml_kapal" class="form-control-label">Jumlah Kapal</label>
                                                            <div>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"></span>
                                                                    <input type="number" required="" id="jml_kapal" class="form-control" placeholder="" name="jml_kapal" value="<?= $data_usaha->jml_kapal ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="kapasitas_kapal" class="form-control-label">Kapasitas Kapal</label>
                                                            <div>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"></span>
                                                                    <input type="number" required="" id="kapasitas_kapal" class="form-control" placeholder="" name="kapasitas_kapal" value="<?= $data_usaha->kapasitas_kapal ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($data_usaha->jenis_petani == "Tawar") : ?>
                                                <div class="row" style="">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="jml_kolam" class="form-control-label">Jumlah Kolam</label>
                                                            <div>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"></span>
                                                                    <input type="number" required="" id="jml_kolam" class="form-control" placeholder="" name="jml_kolam" value="<?= $data_usaha->jml_kolam ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card" style="padding: 24px">
                                            <div class="form-group">
                                                <label for="kab" class="form-control-label">Kota / Kabupaten</label>
                                                <div>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"></span>
                                                        <input type="text" required="" id="kab" class="form-control" placeholder="" name="kab" value="<?= $data_usaha->kab ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kec" class="form-control-label">Kecamatan</label>
                                                <div>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"></span>
                                                        <input type="text" required="" id="kec" class="form-control" placeholder="" name="kec" value="<?= $data_usaha->kec ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kel" class="form-control-label">Kelurahan</label>
                                                <div>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"></span>
                                                        <input type="text" required="" id="kel" class="form-control" placeholder="" name="kel" value="<?= $data_usaha->kel ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="" id="map"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="longitude" class="form-control-label">Longitude</label>
                                                        <div>
                                                            <div class="input-group">
                                                                <span class="input-group-addon"></span>
                                                                <input type="text" required="" id="longitude" class="form-control" placeholder="" name="longitude" value="<?= $data_usaha->longitude ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="latitude" class="form-control-label">Latitude</label>
                                                        <div>
                                                            <div class="input-group">
                                                                <span class="input-group-addon"></span>
                                                                <input type="text" required="" id="latitude" class="form-control" placeholder="" name="latitude" value="<?= $data_usaha->latitude ?>">
                                                            </div>
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
                                    <a class="btn btn-danger" href="<?= base_url('admin/penjual/detail/' . $data_usaha->id_pj); ?>">BATAL</a>
                                    <button class="btn btn-success" type="submit">SIMPAN</button>
                                </div>
                                <!-- <button class="btn btn-outline-danger" type="button" onclick="window.history.back();">Batal</button> -->
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        <!-- </div> -->

        <!-- <script async defer src="http://maps.google.com/maps/api/js?key=AIzaSyAuoJ8tWSNs6owWkZsFI_Ssw4N_QOV__YM"> -->
        </script>
        <?php
        $this->load->view('admin/template/footerjs');
        ?>

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
            $(document).ready(function() {
                var latitude = $("[name=latitude]").val();
                var longitude = $("[name=longitude]").val();
                initMap(latitude, longitude);
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