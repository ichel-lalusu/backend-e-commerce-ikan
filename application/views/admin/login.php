<?php
$title = "Login Admin Ikan";
$data = array('title' => $title);
$this->load->view('admin/template/head', $data);
?>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="<?= base_url('admin/') ?>"><b>Admin</b>IKAN</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Silahkan Login Terlebih Dahulu</p>

        <form action="<?= base_url('admin/User/prosesLogin') ?>" method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Username" minlength="3" name="username" id="username" autocomplete="off">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="password" id="password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- /.col -->
            <div class="col-12">
              <button type="button" class="btn btn-primary btn-block" id="login">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- bs-custom-file-input -->
  <script src="<?= base_url('assets') ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

  <!-- DataTables -->
  <script src="<?= base_url('assets') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= base_url('assets') ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <!-- InputMask -->
  <script src="<?= base_url('assets') ?>/plugins/moment/moment.min.js"></script>
  <script src="<?= base_url('assets') ?>/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
  <script src="<?= base_url('assets') ?>/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="<?= base_url('assets') ?>/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js"></script>
  <script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="<?= base_url('assets') ?>/dist/js/demo.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.21/datatables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript">
    var API_URL = location.origin + "/backendikan/";
    // $('[data-toggle="tooltip"]').tooltip();
    function formatNumber(num) {
      return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
    }

    $(document).ready(function() {
      $("#login").click(login);
    });

    function login() {
      event.preventDefault();
      var username = $('#username').val();
      var password = $('#password').val();

      const ULR_LOGIN = '<?= base_url('api/user/login') ?>';
      var data = {
        username: username,
        password: password
      };
      $.post(ULR_LOGIN, data).then(success_login);
    }

    var success_login = function(response, status) {
      console.log('muncul di console');
      console.log(status);
      console.log(response);
      var data_response = response.data;
      console.log(data_response);
      if (data_response.status == "berhasil" && status=="success") {
        // alert('Anda berhasil masuk');
        var data_user = {
                        id_akun: data_response.id_akun, 
                        username: data_response.username,
                        usergroup: data_response.usergroup,
                        sukses_login: '1'
                        };
        window.localStorage.setItem('data_user', JSON.stringify(data_user));

        if (data_user.usergroup == "admin") {
          console.log("ADMIN LOG");
          console.log("data_response.usergroup => " + data_response.usergroup);
          window.location.replace("<?= base_url('admin') ?>");
        }
      }
    };
  </script>
  <!-- CUSTOME JAVASCRIPT HERE -->
</body>

</html>