<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usaha extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata("data")) {
        $data_session = $this->session->userdata('data');
        if ($data_session['usergroup'] !== "admin") {
            redirect(base_url('admin/User/login'));
        }
    }
    $this->load->model("admin/Model_usaha", "usaha");
    $this->load->model("admin/Model_pembeli", "Pembeli");
    // $this->load->model("admin/Model_kurir", "Kurir");
    $this->load->model("admin/Model_pengiriman", "Pengiriman");
    $this->load->model("admin/Model_pemesanan", "Pemesanan");
    $this->load->model("admin/Model_penjual", "Penjual");
    $this->load->model("admin/Model_produk", "produk");
    $this->load->model("admin/Modal_Pembayaran", "Pembayaran");
    $this->load->model("Model_kurir");
    $this->load->model("Model_kendaraan");
    $this->url_API =  "http://" . $_SERVER['HTTP_HOST'] . "/backendikan/";
    $this->menu = "Penjual";
  }

  public function index()
  {
    $menu = "Penjual";
    $data_usaha = $this->usaha->get_all_usaha();
    $data_page = array('title' => 'Data Usaha', 'data_usaha' => $data_usaha, 'menu' => 'penjual');
    $this->load->view($menu . '/index', $data_page);
  }

  public function add_usaha($id_pj)
  {
    $menu = "Penjual";
    $data_usaha = $this->usaha->get_all_usaha();
    $data_page = array('title' => 'Tambah Data Usaha', 'menu' => 'penjual', 'id_pj' => $id_pj);
    $this->load->view('admin/' . $menu . '/usaha/add', $data_page);
  }

  public function edit($id)
  {
    $url_API = $this->url_API;
    $data_usaha = $this->usaha->getAllDetailShopByid($id)->row();
    $data_page = array('title' => 'Data Usaha', 'data_usaha' => $data_usaha, 'menu' => 'usaha', 'url_API' => $url_API);
    $this->load->view("admin/Penjual/Usaha/edit", $data_page);
  }

  

  public function update()
  {
    # code...
    $data = $this->input->post();
    // var_dump($data);
    $id = $this->input->post('id');
    $where = "id_usaha = '$id'";
    if (is_array($_FILES)) {
      if (is_uploaded_file($_FILES['foto_usaha']['tmp_name'])) {
        $sourcePath2 = $_FILES['foto_usaha']['tmp_name'];
        $foto_usaha = date('dmYHis') . $_FILES['foto_usaha']['name'];
        $targetPath2 = "./foto_usaha/" . $foto_usaha;
        move_uploaded_file($sourcePath2, $targetPath2);
        $data['foto_usaha'] = $foto_usaha;
      }
    }
    unset($data['id']);
    unset($data['hiddenfoto_pj']);
    $update = $this->usaha->update_shop($data, $where);
    if ($update) {
      $data_usaha = $this->usaha->getAllDetailShopByid($id)->row();
      $this->session->set_flashdata("success", "Berhasil Edit Data Usaha");
      redirect(base_url('admin/Penjual/detail/' . $data_usaha->id_pj));
    } else {
      $error = $this->db->error();
      $this->session->set_flashdata("error", "Gagal Edit Data Usaha " . $error['message']);
      redirect(base_url('admin/Penjual/edit/' . $id));
    }
  }

  public function transaksi($id_usaha)
  {
    $where = " id_usaha = '$id_usaha'";
    $order = " id_pemesanan DESC, waktu_pemesanan DESC";
    $select_pemesanan = "`id_pemesanan`, `waktu_pemesanan`, `tipe_pengiriman`, `tgl_pengiriman`, `jarak`, `biaya_kirim`, `total_harga`, `status_pemesanan`, `id_pb`, `id_usaha`";
    $dataPemesanan = $this->Pemesanan->get_where($select_pemesanan, $where, NULL, $order, NULL);
    $page = "Transaksi";
    $data = array('title' => 'Data Transaki', 'menu' => 'penjual', 'page' => $page, 'id_usaha' => $id_usaha, 'data_transaksi' => $dataPemesanan);
    $this->load->view("admin/Penjual/" . $page . "/index", $data);
  }

  public function detail($id)
  {
    $menu = "Penjual";
    $where = "id_usaha = '$id'";
    $data_shop = $this->usaha->get_where("*", $where)->row();
    $id_usaha = $data_shop->id_usaha;
    $data_produk = $this->produk->ambil_produk_penjual($id);
    $data_kurir = $this->Model_kurir->get_kurir_usaha($id);
    $data_kendaraan = $this->Model_kendaraan->getKendaraanUsaha($id);
    $data_page = array(
      'data_usaha' => $data_shop,
      'title' => 'Detail Usaha ' . $data_shop->nama_usaha,
      'menu' => 'penjual', 
      'data_produk' => $data_produk,
      'id_usaha' => $id, 
      'data_kurir' => $data_kurir,
      'data_kendaraan' => $data_kendaraan
    );
    $this->load->view('admin/' . $menu . "/usaha/detail", $data_page);
  }

  public function add_kendaraan($id_usaha)
  {
    if (!$this->session->userdata("username")) {
    } else {
      $menu = "Penjual";
      $where = "id_usaha = '$id_usaha'";
      $data_shop = $this->usaha->get_where("*", $where)->row();
      $id_usaha = $data_shop->id_usaha;
      $data_page = array(
        'title' => 'Tambah Data Kendaraan ' . $data_shop->nama_usaha,
        'menu' => 'penjual',
        'id_usaha' => $id_usaha
      );
      $this->load->view('admin/' . $menu . "/Usaha/Kendaraan/add_kendaraan", $data_page);
    }
  }

  public function simpan_kendaraan()
  {
    $jenis_kendaraan = $this->input->post('jenis_kendaraan');
    $plat_kendaraan = $this->input->post('plat_kendaraan');
    $kapasitas_kendaraan = $this->input->post('kapasitas_kendaraan');
    $id_usaha = $this->input->post('id_usaha');
    $array_insert = array('jenis_kendaraan' => $jenis_kendaraan, 'plat_kendaraan' => $plat_kendaraan, 'kapasitas_kendaraan' => $kapasitas_kendaraan, 'id_usaha' => $id_usaha);
    $INSERT = $this->Model_kendaraan->createKendaraan($array_insert);
    // $insert = $this->db->insert("data_kendaraan", $array_insert);
    if ($this->db->affected_rows() > 0) {
      $this->session->set_flashdata("success", "Berhasil Tambahkan Data Kendaraan");
      redirect(base_url('admin/Usaha/detail/' . $id_usaha));
    } else {
      $this->session->set_flashdata("error", "Gagal Tambahkan Data Kendaraan");
      redirect(base_url('admin/Usaha/add_kendaraan/' . $id_usaha));
    }
  }

  public function edit_kendaraan($id_kendaraan, $id_usaha)
  {
    $data = $this->detail_kendaraan($id_kendaraan);
    if ($data->num_rows() > 0) :
      $menu = "Penjual";
      $data_page = array(
        'title' => 'Ubah Data Kendaraan ' . $data->row()->jenis_kendaraan,
        'menu' => 'penjual',
        'id_usaha' => $id_usaha,
        'data' => $data->row()
      );
      $this->load->view('admin/' . $menu . "/Usaha/Kendaraan/edit_kendaraan", $data_page);
    else :
      redirect(base_url('admin/Usaha/detail/' . $id_usaha));
    endif;
  }

  private function detail_kendaraan($id_kendaraan)
  {
    $data = $this->Model_kendaraan->get_detail_kendaraan($id_kendaraan);
    return $data;
  }

  public function update_kendaraan()
  {
    $id_kendaraan = $this->input->post('id_kendaraan');
    $jenis_kendaraan =  $this->input->post('jenis_kendaraan');
    $plat_kendaraan = $this->input->post('plat_kendaraan');
    $kapasitas_kendaraan = $this->input->post('kapasitas_kendaraan');
    $id_usaha = $this->input->post('id_usaha');

    $where = 'id_kendaraan = ' . $id_kendaraan;
    $data = array('jenis_kendaraan' => $jenis_kendaraan, 'plat_kendaraan' => $plat_kendaraan, 'kapasitas_kendaraan' => $kapasitas_kendaraan);
    $update = $this->kendaraan->updateKendaraan($data, $where);
    if ($update) {
      $this->session->set_flashdata("success", "Berhasil Ubah Data Kendaraan");
      redirect(base_url('admin/Usaha/detail/' . $id_usaha));
    } else {
      $this->session->set_flashdata("error", "Gagal Ubah Data Kendaraan");
      redirect(base_url('admin/Usaha/edit_kendaraan/' . $id_kendaraan . '/' . $id_usaha));
    }
  }

  public function delete_kendaraan($id_kendaraan, $id_usaha)
  {
    $delete = $this->Model_kendaraan->delete_kendaraan($id_kendaraan);
    if ($delete) {
      $this->session->set_flashdata("success", "Berhasil Hapus Data Kendaraan");
      redirect(base_url('admin/Usaha/detail/' . $id_usaha));
    } else {
      $this->session->set_flashdata("error", "Gagal Hapus Data Kendaraan");
      redirect(base_url('admin/Usaha/detail/' . $id_usaha));
    }
  }

  // public function detail_transaksi($id_pemesanan)
  // {

  // }
}
