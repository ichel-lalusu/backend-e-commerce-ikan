<?php
$this->load->view('admin/template/head');
?>
<style type="text/css">
    #map {
        height: 400px !important;
        width: 100% !important;
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
                            <h1> Detail Pembeli</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url("admin/Pembeli") ?>">Pembeli</a></li>
                                <li class="breadcrumb-item active"><span id="name-detail"></span></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- <div class="row"> -->
                    <div class="row">

                        <div class="col-md-12">
                            <form action="#!" name="ubah_pembeli" onsubmit="simpan_form()">
                                <input type="hidden" name="idAkun">
                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <img class="profile-user-img img-fluid img-circle" src="<?= base_url('assets/ico/maleAva.png') ?>" alt="User profile picture">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputFile">Foto Pembeli</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="exampleInputFile" name="foto_pb">
                                                    <label class="custom-file-label" for="exampleInputFile">Pilih Foto</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_pb">Nama Pembeli:</label>
                                            <input type="text" name="nama_pb" class="form-control" id="nama_pb" placeholder="Masukkan Nama">
                                        </div>
                                        <!-- <h3 class="profile-username text-center"></h3> -->
                                        <!-- <a href="<?= base_url('admin/Pembeli/edit/' . $id) ?>" class="btn btn-sm btn-primary float-right"><b><i class="fas fa-edit"></i> Ubah</b></a> -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Detail Pembeli</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="telp_pb">No Telp:</label>
                                            <input class="form-control" type="tel" name="telp_pb" id="telp_pb" maxlength="12" min="0" placeholder="Masukkan No. Telp Pembeli">
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat_pb">Alamat: </label>
                                            <textarea class="form-control" name="alamat_pb" id="alamat_pb" cols="10" rows="4" placeholder="Masukkan Alamat Pembeli"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="kel_pb">Kelurahan: </label>
                                            <input class="form-control" type="text" name="kel_pb" id="kel_pb" placeholder="Masukkan Kelurahan">
                                        </div>
                                        <div class="form-group">
                                            <label for="kec_pb">Kecamatan: </label>
                                            <input class="form-control" type="text" id="kec_pb" name="kec_pb" placeholder="Masukkan Kecamatan">
                                        </div>
                                        <div class="form-group">
                                            <label for="kab_pb">Kabupaten: </label>
                                            <input class="form-control" type="text" id="kab_pb" name="kab_pb" placeholder="MasukkanKabupaten">
                                        </div>
                                        <div class="form-group">
                                            <label for="jk_pb">Jenis Kelamin:</label>
                                            <div>
                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-primary" id="jk_1">
                                                        <input type="radio" name="jk_pb" id="option1" value="Laki-laki" autocomplete="off"> Laki-laki
                                                    </label>
                                                    <label class="btn btn-primary" id="jk_2">
                                                        <input type="radio" name="jk_pb" id="option2" value="Perempuan" autocomplete="off"> Perempuan
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="latitude">
                                        <input type="hidden" name="longitude">
                                        <div class="form-group">
                                            <strong><i class="far fa-map mr-1"></i> Map:</strong>
                                            <div id="map"></div>
                                        </div>

                                        <div class="form-group">
                                            <button id="simpan" class="btn btn-primary btn-block" type="button">Simpan</button>
                                            <a href="<?=base_url('admin/pembeli')?>" class="btn btn-secondary btn-block">Batal</a>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php
        $this->load->view('admin/template/footerjs');
        ?>
        <!-- CUSTOME JAVASCRIPT HERE -->
        <script src="http://maps.google.com/maps/api/js?key=AIzaSyAuoJ8tWSNs6owWkZsFI_Ssw4N_QOV__YM"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $.getJSON('<?= base_url('api/user/pembeli/' . $id); ?>').then(on_success_data_pembeli);
                document.querySelector("#simpan").addEventListener("click", simpan_form, false);
            });

            function on_success_data_pembeli(response, status) {
                if (status == "success") {
                    console.log("response : ", response);
                    let data_pembeli = response.detail_pembeli;
                    console.log("data pembeli", data_pembeli);
                    let nama_pb = data_pembeli.nama_pb;
                    let no_telp = data_pembeli.telp_pb;
                    let jk_pb = data_pembeli.jk_pb;
                    let default_img_if_foto_not_found = (jk_pb == "Laki-laki") ? "<?= base_url('assets/ico/maleAva.png') ?>" : "<?= base_url('assets/ico/femaleAva.png') ?>";
                    let foto_pb = (data_pembeli.foto_pb !== "") ? '<?= base_url('foto_pembeli/') ?>' + data_pembeli.foto_pb : default_img_if_foto_not_found;
                    let alamat_pb = (data_pembeli.alamat_pb !== "") ? data_pembeli.alamat_pb : "";
                    let kel_pb = (data_pembeli.kel_pb !== "") ? `${data_pembeli.kel_pb}` : "";
                    let kec_pb = (data_pembeli.kec_pb !== "") ? `${data_pembeli.kec_pb}` : "";
                    let kab_pb = (data_pembeli.kab_pb !== "") ? `${data_pembeli.kab_pb}` : "";
                    let alamat_lengkap = alamat_pb + kel_pb + kec_pb + kab_pb;
                    let lat = parseFloat(data_pembeli.latitude_pb);
                    let lg = parseFloat(data_pembeli.longitude_pb);
                    $(".profile-username").html(nama_pb);
                    $("#nama_pb").val(nama_pb);
                    $(".profile-user-img").attr("src", foto_pb);
                    $("#telp_pb").val(no_telp);
                    $("#alamat_pb").val(alamat_pb);
                    $("#kel_pb").val(kel_pb);
                    $("#kec_pb").val(kec_pb);
                    $("#kab_pb").val(kab_pb);
                    $("[name=jk_pb][value=" + jk_pb + "]").prop("checked", true);
                    if (jk_pb == "Laki-laki") {
                        $("#jk_1").addClass("active");
                        $("#option1:checked");
                    } else {
                        $("#jk_2").addClass("active");
                        $("#option2:checked");
                    }
                    $("[name=latitude]").val(lat);
                    $("[name=longitude]").val(lg);
                    $("[name=idAkun]").val(parseInt(data_pembeli.id_pb));
                    initMap(lat, lg);
                }
            }
            let map;

            function initMap(lat, lng) {
                $("body").find("input[name='latitude']").val(lat);
                $("body").find("input[name='longitude']").val(lng);
                var propertiPeta = {
                    center: new google.maps.LatLng(lat, lng), //nentuin titik pusat nya dimana (awal map kebuka, bukan posisi marker)
                    zoom: 15, //semakin banyak semakin dekat min 1 maksimal
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

            function simpan_form() {
                var formData = new FormData($("form")[0]);
                $.ajax({
                    url: '<?= base_url('api/user/pembeli/update') ?>',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    async: false,
                    success: success_submit_form,
                    error: failed_submit_form,
                })
            }

            function success_submit_form(response, status) {
                if (status == "success") {
                    localStorage.setItem("status_update_form", "success");
                    location.replace("<?= base_url('admin/Pembeli/detail/' . $id) ?>");
                } else {
                    Toast.fire({
                        type: 'error',
                        title: 'Gagal ubah pembeli'
                    });
                }
            }

            function failed_submit_form(error) {
                Toast.fire({
                    icon: 'error',
                    text: 'Gagal ubah pembeli'
                });
            }
        </script>
</body>

</html>