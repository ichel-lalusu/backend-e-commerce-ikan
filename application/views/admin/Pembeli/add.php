<?php
$this->load->view('admin/template/head');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/css/bootstrap-datepicker.min.css') ?>">
<style>
    #map {
        width: 100%;
        height: 400px;
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
                            <h1>Tambah Data Pembeli</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url("admin/Pembeli") ?>">Pembeli</a></li>
                                <li class="breadcrumb-item active"><span>Tambah Pembeli</span></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- <div class="row"> -->
                    <form novalidate="" enctype="multipart/form-data" action="<?= base_url('admin/Pembeli/create') ?>" method="post" name="form-pembeli">
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="card-title">FORM AKUN PEMBELI</div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="username">Username:</label>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" minlength="3" required autocomplete="off" autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="*******" minlength="5" required autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="card-title">FORM DETAIL PEMBELI</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="nama_lengkap">Nama Pembeli:</label>
                                            <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" required placeholder="Nama Lengkap" autocomplete="off">
                                            <input type="hidden" name="usertype" value="pembeli">
                                        </div>
                                        <div class="form-group">
                                            <label for="foto_pb">Foto Pembeli:</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="foto_pb">
                                                    <label class="custom-file-label" for="foto_pb">Pilih Foto</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="jk_pb">Jenis Kelamin:</label>
                                            <div>
                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-primary">
                                                        <input type="radio" name="jk_pb" id="jk_pb1" autocomplete="off" value="Laki-laki"> Laki-laki
                                                    </label>
                                                    <label class="btn btn-primary">
                                                        <input type="radio" name="jk_pb" id="jk_pb2" autocomplete="off" value="Perempuan"> Perempuan
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="telp_pb">No Telp:</label>
                                            <input type="number" placeholder="08XXXXXXXX" class="form-control" id="telp_pb" name="telp_pb" required autocomplete="off" minlength="10" maxlength="13">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tgl Lahir</label>
                                            <input type="text" class="form-control" id="tgllahir_pb" name="tgllahir_pb" require placeholder="Tanggal Lahir" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="alamat">Alamat:</label>
                                            <input type="text" name="alamat" id="alamat" cols="10" rows="1" class="form-control" placeholder="Alamat" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="kec_pb">Kecamatan</label>
                                            <input type="text" class="form-control" id="kec_pb" name="kec_pb" placeholder="Kecamatan" required autocomplete="off" minlength="3">
                                        </div>
                                        <div class="form-group">
                                            <label for="kab_pb">Kabupaten</label>
                                            <input type="text" class="form-control" id="kab_pb" name="kab_pb" placeholder="Kabupaten" required autocomplete="off" minlength="3">
                                        </div>
                                        <div class="form-group">
                                            <label for="kel_pb">Kelurahan</label>
                                            <input type="text" class="form-control" id="kel_pb" name="kel_pb" placeholder="Kelurahan" required autocomplete="off" minlength="3">
                                        </div>
                                        <div class="form-group">
                                            <!-- <label for="">Map</label> -->
                                            <!-- <div id="map"></div> -->
                                            <input type="hidden" name="latitude" value="0">
                                            <input type="hidden" name="longitude" value="0">
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-right">
                                <a class="btn btn-danger" href="<?= base_url('admin/Pembeli') ?>">BATAL</a>
                                <button class="btn btn-success" type="button" onclick="simpan_pembeli()">SIMPAN</button>
                            </div>
                            <!-- <button class="btn btn-outline-danger" type="button" onclick="window.history.back();">Batal</button> -->
                    </form>
                </div>
        </div>
    </div>
    <!-- </div> -->
    </section>

    <?php
    $this->load->view('admin/template/footerjs');
    ?>
    <!-- <script async defer src="http://maps.google.com/maps/api/js?key=AIzaSyAuoJ8tWSNs6owWkZsFI_Ssw4N_QOV__YM"></script> -->
    <script src="<?= base_url('assets/plugins/datepicker/js/bootstrap-datepicker.min.js') ?>"></script>
    <!-- CUSTOME JAVASCRIPT HERE -->
    <script type="text/javascript">
        var firstLt, firstLg;
        $(document).ready(function() {
            // current_location();
            bsCustomFileInput.init();
            $('#tgllahir_pb').datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                toggleActive: true
            });
        });

        function simpan_pembeli() {
            const form_pembeli = new FormData($("form")[0]);
            const file_foto_pb = document.getElementById("foto_pb");
            form_pembeli.append("foto_pb", file_foto_pb.files[0]);
            fetch("<?= base_url('api/signup') ?>", {
                    method: "POST",
                    mode: "cors",
                    body: form_pembeli
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status == "berhasil") {
                        toastup("success", "Sukses Menyimpan");
                    } else {
                        toastup("failed", data.message);
                    }
                });
        }

        function toastup(status = "failed", message = "Gagal menyimpan", timer = 1500) {
            Swal.fire({
                position: 'top-end',
                icon: status,
                title: message,
                showConfirmButton: false,
                timer: timer
            });
        }

        // function current_location() {
        //     navigator.geolocation.getCurrentPosition(onSuccess, onError);
        // }

        // function onSuccess(position) {
        //     var element = document.getElementById('map');
        //     var marker;
        //     var posisilat;
        //     var posisilng;
        //     firstLt = position.coords.latitude;
        //     firstLg = position.coords.longitude;
        //     initMap(firstLt, firstLg);
        //     /*if(function_exists('initMap')){
        //       initMap(position.coords.latitude, position.coords.longitude);  
        //     }*/

        // };

        // function onError(error) {
        //     alert('code: ' + error.code + '\n' +
        //         'message: ' + error.message + '\n');
        // }

        // function taruhMarker(peta, posisiTitik) {
        //     if (marker) {
        //         // pindahkan marker
        //         marker.setPosition(posisiTitik);
        //     } else {
        //         // buat marker baru
        //         marker = new google.maps.Marker({
        //             position: posisiTitik,
        //             map: peta
        //         });
        //     }
        //     posisilat = posisiTitik.lat();
        //     posisilng = posisiTitik.lng();
        //     console.log("Posisi marker: " + posisilat + "," + posisilng);
        //     $("body").find("input[name='latitude_pb']").val(posisilat);
        //     //$("#latitude_pb").val(posisilat);
        //     //$("#longitude_pb").val(posisilng);
        //     $("body").find("input[name='longitude_pb']").val(posisilng);
        // }

        // function initMap(lat, lng) {
        //     $("body").find("input[name='latitude']").val(lat);
        //     $("body").find("input[name='longitude']").val(lng);
        //     var propertiPeta = {
        //         center: new google.maps.LatLng(lat, lng), //nentuin titik pusat nya dimana (awal map kebuka, bukan posisi marker)
        //         zoom: 17, //semakin banyak semakin dekat min 1 maksimal
        //         mapTypeId: google.maps.MapTypeId.ROADMAP //roadmap, satelite, hybrid, terrain
        //     };
        //     var point = new google.maps.LatLng(lat, lng);
        //     var peta = new google.maps.Map(document.getElementById("map"), propertiPeta); //utama bikin map
        //     marker = new google.maps.Marker({
        //         position: point,
        //         map: peta
        //         //icon
        //     });
        //     google.maps.event.addListener(peta, 'click', function(event) {
        //         taruhMarker(this, event.latLng);
        //     });
        // }
    </script>
</body>

</html>