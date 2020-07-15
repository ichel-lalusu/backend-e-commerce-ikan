<?php

use phpDocumentor\Reflection\Types\Array_;

class Produk extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Model_produk");
    }

    public function add_produk(int $id_usaha)
    {
        $menu = "Penjual";
        $data_variasi = $this->Model_produk->ambil_data_variasi();
        $data_page = array('title' => 'Tambah Data Produk', 'menu' => 'penjual', 'id_usaha' => $id_usaha, 'data_variasi' => $data_variasi);
        $this->load->view('admin/Penjual/Usaha/Produk/add', $data_page);
    }

    public function add()
    {
        $nama_produk = $this->input->post('nama_produk');
        $kategori = $this->input->post('kategori');

        $berat_produk = $this->input->post('berat_produk');
        $min_pemesanan = $this->input->post('min_pemesanan');
        $id_usaha = $this->input->post('id_usaha');
        // var_dump($this->input->post());
        // exit();
        // SETTING UPLOAD FOTO
        $file_name                        = date('dmYHis') . $_FILES['foto_produk']['name'];
        // var_dump($_FILES);
        $config['upload_path']          = './foto_usaha/produk/';
        $config['allowed_types']        = 'gif|jpg|jpeg|png';
        $config['max_size']             = 20480;
        $config['max_width']            = 5000;
        $config['max_height']           = 5000;
        $config['file_name']            = $file_name;
        // var_dump($config);
        // exit();
        $array_produk = array(
            'nama_produk' => $nama_produk,
            'kategori' => $kategori,
            'berat_produk' => $berat_produk,
            'min_pemesanan' => $min_pemesanan,
            'id_usaha' => $id_usaha
        );
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto_produk')) {
            $dataFoto = array('error' => $this->upload->display_errors());
            var_dump($dataFoto);
        } else {
            $dataFoto = array('upload_data' => $this->upload->data());
            $config = array();
            $config['image_library'] = 'gd2';
            $config['source_image'] = './foto_usaha/produk/' . $dataFoto['upload_data']['file_name'];
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = FALSE;
            $config['quality'] = '50%';
            $config['width'] = 600;
            $config['height'] = 400;
            $config['new_image'] = './foto_usaha/produk/' . $dataFoto['upload_data']['file_name'];
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
            // var_dump($dataFoto);
            // exit();
            $foto_produk        =   $dataFoto['upload_data']['file_name'];
            $array_produk['foto_produk'] = $foto_produk;
        }

        // END UPLOAD FOTO

        $insert_produk = $this->Model_produk->insert_produk($array_produk);
        if ($insert_produk) {
            $id_produk = $this->db->insert_id();
            $insert = $this->add_variasi($id_produk);
            // echo $this->db->last_query();
            // exit();
            if ($insert) {
                $responseMessage = 'Berhasil Menambahkan Produk Dengan Variasi';
                $this->session->set_flashdata("success", $responseMessage);
                redirect(base_url("admin/Usaha/detail/" . $id_usaha));
                // $response = array('status' => 'berhasil', 'responseMessage' => $responseMessage, 'id_produk' => $id_produk);
            } else {
                $responseMessage = 'Berhasil Menambahkan Produk, Tetapi Gagal Menambahkan Variasi';
                $this->session->set_flashdata("success", $responseMessage);
                redirect(base_url("admin/Usaha/detail/" . $id_usaha));
                // echo $status;
                // exit();
            }
        } else {
            $responseMessage = 'Gagal Menginput Produk';
            $this->session->set_flashdata("success", $responseMessage);
            redirect(base_url("admin/Produk/add_produk/" . $id_usaha));
        }

        // $this->output
        //     ->set_status_header($status)
        //     ->set_content_type('application/json', 'utf-8')
        //     ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    protected function add_variasi(Int $id_produk)
    {
        $status = $this->input->post('status');
        $harga = $this->input->post('harga');
        $stok = $this->input->post('stok');
        if (count($status) > 0) {
            $array_variasi_in = array();
            for ($i = 0; $i < count($status); $i++) {
                $array_variasi_in[$i]['id_produk'] = $id_produk;
                $array_variasi_in[$i]['id_variasi'] = $status[$i];
                $array_variasi_in[$i]['harga'] = $harga[$i];
                $array_variasi_in[$i]['stok'] = $stok[$i];
                $array_variasi_in[$i]['status_vp'] = 'aktif';
            }
            $insert = $this->db->insert_batch("data_variasi_produk", $array_variasi_in);
            // echo $this->db->last_query();
            // exit();
            if($insert && count($status) < 3){
                $this->add_non_variasi($id_produk, $status);
            }elseif(count($status===3)){
                return true;
            }
        } else {
            $this->add_non_variasi($id_produk);
        }
    }

    protected function add_non_variasi(Int $id_produk, Array $variasi_in = null)
    {
        if ($variasi_in !== null) {
            $this->db->select("id_variasi")->where_not_in("id_variasi", $variasi_in);
            $data_variasi = $this->db->get("data_variasi");
            foreach ($data_variasi->result() as $key) {
                $insert_data_variasi_produk = array(
                    'id_produk' => $id_produk,
                    'id_variasi' => $key->id_variasi,
                    'harga' => '0',
                    'stok' => '0',
                    'status_vp' => 'tidak aktif'
                );
                $insert = $this->db->insert("data_variasi_produk", $insert_data_variasi_produk);
                // echo $this->db->last_query();
                // exit();
                if (!$insert) {return false;}
            }
            return true;
        } else {
            $data_variasi = $this->Model_produk->ambil_data_variasi();
            foreach ($data_variasi->result() as $key) {
                $insert_data_variasi_produk = array(
                    'id_produk' => $id_produk,
                    'id_variasi' => $key->id_variasi,
                    'harga' => '0',
                    'stok' => '0',
                    'status_vp' => 'tidak aktif'
                );
                $insert = $this->db->insert("data_variasi_produk", $insert_data_variasi_produk);
                // echo $this->db->last_query();
                // exit();
                if (!$insert) {return false;}
            }
            return true;
        }
    }

    public function edit_produk(Int $id_usaha, Int $id_produk)
    {
        $data_produk = $this->Model_produk->get_produk($id_produk)->row();
        $data_variasi = $this->Model_produk->ambil_data_variasi();
        $data_page = array('title' => 'Data Usaha', 'menu' => 'usaha', 'data_produk' => $data_produk, 'id' => $id_produk, 'id_usaha' => $id_usaha, 'data_variasi' => $data_variasi);
        $this->load->view("admin/Penjual/Usaha/Produk/edit", $data_page);
    }

    public function editV2($type, $id)
    {
        $url_API = $this->url_API;
        $data_usaha = $this->usaha->getAllDetailShopByid($id)->row();
        if ($type == "produk") {
            $where = "id_produk = '$id'";
            $data_produk = $this->db->get_where('data_produk', $where)->row();
            $data_page = array('title' => 'Data Usaha', 'menu' => 'usaha', 'url_API' => $url_API, 'data_produk' => $data_produk, 'id' => $id);
            $this->load->view("admin/Penjual/Usaha/Produk/edit", $data_page);
        }
    }

    public function update_produk()
    {
        $nama_produk = $this->input->post('nama_produk');
        $kategori = $this->input->post('kategori');

        $berat_produk = $this->input->post('berat_produk');
        $min_pemesanan = $this->input->post('min_pemesanan');
        $id_usaha = $this->input->post('id_usaha');
        $id_produk = $this->input->post('id_produk');
        // var_dump($this->input->post());
        // exit();
        // SETTING UPLOAD FOTO
        if(isset($_FILES['foto_produk'])):
        $file_name                        = date('dmYHis') . $_FILES['foto_produk']['name'];
        // var_dump($_FILES);
        $config['upload_path']          = './foto_usaha/produk/';
        $config['allowed_types']        = 'gif|jpg|jpeg|png';
        $config['max_size']             = 20480;
        $config['max_width']            = 5000;
        $config['max_height']           = 5000;
        $config['file_name']            = $file_name;
        // var_dump($config);
        // exit();
        $array_produk = array(
            'nama_produk' => $nama_produk,
            'kategori' => $kategori,
            'berat_produk' => $berat_produk,
            'min_pemesanan' => $min_pemesanan
        );
        $this->load->library('upload', $config);
        endif;
        if (!$this->upload->do_upload('foto_produk')) {
            $dataFoto = array('error' => $this->upload->display_errors());
            // var_dump($dataFoto);
        } else {
            $dataFoto = array('upload_data' => $this->upload->data());
            $config = array();
            $config['image_library'] = 'gd2';
            $config['source_image'] = './foto_usaha/produk/' . $dataFoto['upload_data']['file_name'];
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = FALSE;
            $config['quality'] = '50%';
            $config['width'] = 600;
            $config['height'] = 400;
            $config['new_image'] = './foto_usaha/produk/' . $dataFoto['upload_data']['file_name'];
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
            // var_dump($dataFoto);
            // exit();
            $foto_produk        =   $dataFoto['upload_data']['file_name'];
            $array_produk['foto_produk'] = $foto_produk;
        }

        // END UPLOAD FOTO

        $insert_produk = $this->Model_produk->ubah_produk($array_produk, $id_produk);
        if ($insert_produk) {
            $status = $this->input->post('status');
            $update = $this->update_variasi($id_produk, $status);
            // echo $this->db->last_query();
            // exit();
            if ($update) {
                $responseMessage = 'Berhasil Ubah Produk ' . $nama_produk;
                $this->session->set_flashdata("success", $responseMessage);
                redirect(base_url("admin/Usaha/detail/" . $id_usaha));
                // $response = array('status' => 'berhasil', 'responseMessage' => $responseMessage, 'id_produk' => $id_produk);
            } else {
                $responseMessage = 'Berhasil Ubah, Tetapi Gagal Menambahkan Variasi';
                $this->session->set_flashdata("success", $responseMessage);
                redirect(base_url("admin/Produk/edit_produk/" . $id_usaha . "/" . $id_produk));
                // echo $status;
                // exit();
            }
        } else {
            $responseMessage = 'Gagal Ubah Produk ' . $nama_produk;
            $this->session->set_flashdata("success", $responseMessage);
            redirect(base_url("admin/Produk/edit_produk/" . $id_usaha . "/" . $id_produk));
        }
    }

    protected function update_variasi(Int $id_produk, Array $variasi_in=null)
    {
        $disablingVariasi = $this->db->update("data_variasi_produk", array('status_vp' => 'tidak aktif'), "id_produk = '$id_produk'");
        if($variasi_in!==null){
            $harga = $this->input->post('harga');
            var_dump($harga);
            $stok = $this->input->post('stok');
            for ($i=0; $i < count($variasi_in); $i++) { 
                $where = "id_produk = '$id_produk' AND id_variasi = '$variasi_in[$i]'";
                $arrayData = array('harga' => $harga[$i], 'stok' => $stok[$i], 'status_vp' => 'aktif');
                $update = $this->db->update("data_variasi_produk", $arrayData, $where);
                $this->db->reset_query();
                if(!$update){
                    return false;
                }
            }
            return true;
        }else{
            return true;
        }
    }

    public function aktifkan_produk($id_usaha, $id_produk)
    {
        try {
            $data_produk = $this->Model_produk->get_produk($id_produk)->row();
            $data = array('status_p' => 'aktif');
            $update = $this->Model_produk->ubah_produk($data, $id_produk);
            if ($update) {
                $this->session->set_flashdata("success", "Berhasil Aktifkan Produk " . $data_produk->nama_produk);
                redirect(base_url('admin/Usaha/detail/' . $id_usaha));
            } else {
                $this->session->set_flashdata("error", "Gagal Aktifkan Produk " . $data_produk->nama_produk);
                redirect(base_url('admin/Usaha/detail/' . $id_usaha));
            }
        } catch (Exception $th) {
            $this->session->set_flashdata("error", "Gagal Aktifkan Produk " . $data_produk->nama_produk . " karena " . $th->getMessage());
            redirect(base_url('admin/Usaha/detail/' . $id_usaha));
        }
    }

    public function matikan_produk($id_usaha, $id_produk)
    {
        try {
            $data_produk = $this->Model_produk->get_produk($id_produk)->row();
            $data = array('status_p' => 'tidak aktif');
            $update = $this->Model_produk->ubah_produk($data, $id_produk);
            if ($update) {
                $this->session->set_flashdata("success", "Berhasil Aktifkan Produk " . $data_produk->nama_produk);
                redirect(base_url('admin/Usaha/detail/' . $id_usaha));
            } else {
                $this->session->set_flashdata("error", "Gagal Aktifkan Produk " . $data_produk->nama_produk);
                redirect(base_url('admin/Usaha/detail/' . $id_usaha));
            }
        } catch (Exception $th) {
            $this->session->set_flashdata("error", "Gagal Aktifkan Produk " . $data_produk->nama_produk . " karena " . $th->getMessage());
            redirect(base_url('admin/Usaha/detail/' . $id_usaha));
        }
    }
}
