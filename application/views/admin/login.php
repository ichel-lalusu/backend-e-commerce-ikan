<?php
$title = "Login Admin Ikan";
$data = array('title' => $title);
$this->load->view('admin/template/head', $data);
?>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="<?=base_url('admin/')?>"><b>Admin</b>IKAN</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Silahkan Login Terlebih Dahulu</p>

      <form action="<?=base_url('admin/User/prosesLogin')?>" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Username" minlength="3" name="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
  
  <!-- CUSTOME JAVASCRIPT HERE -->
  </body>
  </html>
