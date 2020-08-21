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
                        <div class="col-md-6">
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle" src="<?= base_url('assets/ico/maleAva.png') ?>" alt="User profile picture">
                                    </div>
                                    <h3 class="profile-username text-center"></h3>
                                    <a href="<?= base_url('admin/Pembeli/edit/' . $id) ?>" class="btn btn-sm btn-primary float-right"><b><i class="fas fa-edit"></i> Ubah</b></a>
                                    <!-- <p class="text-muted text-center">Software Engineer</p> -->

                                    <!-- <ul class="list-group list-group-unbordered mb-3">
                                          <li class="list-group-item">
                                            <b>Followers</b> <a class="float-right">1,322</a>
                                          </li>
                                          <li class="list-group-item">
                                            <b>Following</b> <a class="float-right">543</a>
                                          </li>
                                          <li class="list-group-item">
                                            <b>Friends</b> <a class="float-right">13,287</a>
                                          </li>
                                        </ul> -->

                                    <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Tentang Pembeli</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <strong><i class="fas fa-phone-alt mr-1"></i> No Telp:</strong>

                                    <p class="text-muted" id="phone"></p>
                                    <hr>
                                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat:</strong>
                                    <p class="text-muted" id="alamat"></p>
                                    <hr>
                                    <strong><i class="fas fa-user-tag mr-1"></i> Jenis Kelamin:</strong>
                                    <p class="text-muted" id="jk-pb"></p>
                                    <hr>
                                    <strong><i class="far fa-map mr-1"></i> Map:</strong>
                                    <div id="map"></div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-info card-outline">
                                <div class="card-header">
                                    <h4>Sejarah Pemesanan</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No Pesanan</th>
                                                <th>Tanggal</th>
                                                <th>Produk</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
                if (localStorage.status_update_form == "success") {
                    Toast.fire({
                        icon: 'success',
                        text: 'Berhasil ubah pembeli'
                    });
                    localStorage.removeItem("status_update_form");
                }
            });

            function on_success_data_pembeli(response, status) {
                if (status == "success") {
                    console.log("response : ", response);
                    let data_pembeli = response.detail_pembeli;
                    console.log("data pembeli", data_pembeli);
                    let nama_pb = data_pembeli.nama_pb;
                    let no_telp = data_pembeli.telp_pb;
                    let html_telp = `<a tel="${no_telp}">${no_telp}</a>`;
                    let jk_pb = data_pembeli.jk_pb;
                    let default_img_if_foto_not_found = (jk_pb == "Laki-laki") ? "<?= base_url('assets/ico/maleAva.png') ?>" : "<?= base_url('assets/ico/femaleAva.png') ?>";
                    let foto_pb = (data_pembeli.foto_pb !== "") ? '<?= base_url('foto_pembeli/') ?>' + data_pembeli.foto_pb : default_img_if_foto_not_found;
                    let alamat_pb = (data_pembeli.alamat_pb !== "") ? data_pembeli.alamat_pb : "";
                    let kel_pb = (data_pembeli.kel_pb !== "") ? `, ${data_pembeli.kel_pb}` : "";
                    let kec_pb = (data_pembeli.kec_pb !== "") ? `, ${data_pembeli.kec_pb}` : "";
                    let kab_pb = (data_pembeli.kab_pb !== "") ? `, ${data_pembeli.kab_pb}` : "";
                    let alamat_lengkap = alamat_pb + kel_pb + kec_pb + kab_pb;
                    let lat = parseFloat(data_pembeli.latitude_pb);
                    let lg = parseFloat(data_pembeli.longitude_pb);
                    $(".profile-username").html(nama_pb);
                    $("#name-detail").text(nama_pb);
                    $(".profile-user-img").attr("src", foto_pb);
                    $("#phone").html(html_telp);
                    $("#alamat").text(alamat_lengkap);
                    $("#jk-pb").text(jk_pb);
                    initMap(lat, lg);
                }
            }
            let map;

            function initMap(lat, lng) {
                let position_now = {
                    lat,
                    lng
                };
                map = new google.maps.Map(document.getElementById("map"), {
                    center: position_now,
                    zoom: 15
                });
                var marker = new google.maps.Marker({
                    position: position_now,
                    map: map
                });
            }
        </script>
</body>

</html>