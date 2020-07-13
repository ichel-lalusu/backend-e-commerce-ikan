<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjual extends CI_Controller {

    function __construct()
    {
      parent::__construct();
      if(!$this->session->userdata("username")){
        redirect(base_url('admin/User/login'));
    }
    $this->load->model("admin/Model_Penjual");
    $this->load->model("admin/Model_usaha", "usaha");
    $this->load->model("admin/Model_produk", "produk");
    $this->url_API = "http://localhost/backendikan/";
    $this->menu = "Penjual";
}

public function index()
{
    $menu = "Penjual";
    $data_penjual = $this->Model_Penjual->get_all();
    $data_page = array('title' => 'Data Penjual', 'data_penjual' => $data_penjual, 'menu' => 'penjual');
    $this->load->view('admin/'.$menu.'/index', $data_page);
}

public function add(){
    $menu = "Penjual";
    $url_API = $this->url_API;
    $data_page = array('title' => 'Add Penjual', 'menu' => 'penjual', 'url_API' => $url_API);
    $this->load->view('admin/'.$menu."/add", $data_page);
}

public function prosesadd(){
    $nama_pj        = $this->input->post('nama_pj');
    $noktp_pj       = $this->input->post('noktp_pj');
    $jk_pj          = $this->input->post('jk_pj');
    $tgllahir_pj    = $this->input->post('tgllahir_pj');
    $alamat_pj      = $this->input->post('alamat_pj');
    $telp_pj        = $this->input->post('telp_pj');
    $jenis_petani   = $this->input->post('jenis_petani');

    // var_dump($_FILES);
    $data_add = array('nama_pj' => $nama_pj, 
        'noktp_pj' => $noktp_pj, 
        'jk_pj' => $jk_pj, 
        'tgllahir_pj' => $tgllahir_pj, 
        'alamat_pj' => $alamat_pj, 
        'telp_pj' => $telp_pj, 
        'jenis_petani' => $jenis_petani);
    if (is_array($_FILES)) {
        if (is_uploaded_file($_FILES['foto_pj']['tmp_name'])) {
            $sourcePath2 = $_FILES['foto_pj']['tmp_name'];
            $foto_pj = date('dmYHis') . $_FILES['foto_pj']['name'];
            $targetPath2 = "./foto_penjual/" . $foto_pj;
            move_uploaded_file($sourcePath2, $targetPath2);
            $data_add['foto_pj'] = $foto_pj;
        } if (is_uploaded_file($_FILES['fotoktp_pj']['tmp_name'])) {
            $sourcePath2 = $_FILES['fotoktp_pj']['tmp_name'];
            $fotoktp_pj = date('dmYHis') . $_FILES['fotoktp_pj']['name'];
            $targetPath2 = "./foto_ktp_penjual/" . $fotoktp_pj;
            move_uploaded_file($sourcePath2, $targetPath2);
            $data_add['fotoktp_pj'] = $fotoktp_pj;
        }
    }
    $insert = $this->Model_Penjual->create($data_add);
    /*if($insert) {
        $last_id_pj = $this->db->insert_id();
        $query2 = "INSERT INTO data_usaha VALUES (null, '$nama_toko', '$foto_toko', '$alamat_toko', '$jml_kapal', '$kapasitas_kapal', '$jml_kolam', '$kab_toko', '$kec_toko', '$kel_toko', '0', '0', '$last_id_pj')";
        $insert2 = $this->db->query($query2);
        if($insert2){
            $query3 = "INSERT INTO data_pengguna VALUES (null, '$username', '$password', '$last_id_pj', 'penjual')";
            $insert3 = $this->db->query($query3);
            if($insert3){
                $status = 'berhasil';
            }else{
                $status = 'gagal3 . ' . $this->db->_error_message();
            }
        }else{
            $status = 'gagal2 . ' . $this->db->_error_message();
        }
    } else {
        $status = 'gagal1';
    }*/

    // $response = array(
    //     'status' => $status
    // );

    // echo json_encode($response);
    if($insert){
        $last_id_pj = $this->db->insert_id();
        $this->session->set_flashdata('success', "Berhasil Tambah Data Penjual");
        redirect(base_url('admin/'.$this->menu));
    }else{
        $this->session->set_flashdata('error', "Gagal Tambah Data Penjual");
        redirect(base_url('admin/'.$this->menu.'/add'));
    }
}

public function edit($id){
    $menu = "Penjual";
    $url_API = $this->url_API;
        // if($this->session->has_userdata('username')){
    $data_penjual = $this->Model_Penjual->get_seller_by($id)->row();
            // echo $this->db->last_query();
            // var_dump($data_penjual);
            // exit();
    $data_page = array('data_penjual' => $data_penjual, 'title' => 'Edit Penjual', 'menu' => 'penjual', 'url_API' => $url_API);
    $this->load->view('admin/'.$menu."/edit", $data_page);
        // }
}


public function update(){
    $id = $this->input->post('id');
    //VALIDASI BACA INPUTAN DATA SEPERTI PROSES INSERT
    $nama_pj        = $this->input->post('nama_pj');
    $noktp_pj       = $this->input->post('noktp_pj');
    $jk_pj          = $this->input->post('jk_pj');
    $tgllahir_pj    = $this->input->post('tgllahir_pj');
    $alamat_pj      = $this->input->post('alamat_pj');
    $telp_pj        = $this->input->post('telp_pj');
    $jenis_petani   = $this->input->post('jenis_petani');
    $hiddenfoto_pj  = $this->input->post('hiddenfoto_pj');
    $hiddenfotoktp_pj  = $this->input->post('hiddenfotoktp_pj');

    $data_update = array('nama_pj' => $nama_pj, 
        'noktp_pj' => $noktp_pj, 
        'jk_pj' => $jk_pj, 
        'tgllahir_pj' => $tgllahir_pj, 
        'alamat_pj' => $alamat_pj, 
        'telp_pj' => $telp_pj, 
        'jenis_petani' => $jenis_petani);

    $where = "id_pj = '$id'";
    $error = array();
    // $data_penjual_now = $this->db->get_where("data_penjual", $where, 1)->row();
    // if(is_array($_FILES)){
    //     if(is_uploaded_file($_FILES['foto_pj']['tmp_name'])){
    //         $sourcePath2 = $_FILES['foto_pj']['tmp_name'];
    //         $foto_penjual = date("dmYHis") . $_FILES['foto_pj']['name'];
    //         $targetPath2 = "../backendikan/foto_penjual/" . $foto_penjual;
    //         move_uploaded_file($sourcePath2, $targetPath2);
    //         $kondisi_foto_pj = true;
    //         if($foto_penjual!==""||$foto_penjual!==null)
    //         {
    //             $data_update['foto_pj'] = $foto_penjual;
    //             unlink("../backendikan/foto_penjual/".$data_penjual_now->foto_pj);
    //         }
    //         // echo "Source path foto penjual : " . $sourcePath2;
    //     }
    //     if(is_uploaded_file($_FILES['fotoktp_pj']['tmp_name'])){
    //         $sourcePath2 = $_FILES['fotoktp_pj']['tmp_name'];
    //         $foto_ktp_penjual = date("HisdmY") . $_FILES['fotoktp_pj']['name'];
    //         $targetPath2 = "../backendikan/foto_ktp_penjual/" . $foto_ktp_penjual;
    //         move_uploaded_file($sourcePath2, $targetPath2);
    //         $kondisi_foto_ktp = true;
    //         if($foto_ktp_penjual!=="" || $foto_ktp_penjual!==null)
    //         {
    //             $data_update['fotoktp_pj'] = $foto_ktp_penjual;
    //             unlink("../backendikan/foto_ktp_penjual/".$data_penjual_now->fotoktp_pj);
    //         }
    //         // echo "Source path foto ktp penjual : " . $sourcePath2;
    //     }
    // }

    // CHECK FOTO_PJ IS FILE
    if($_FILES['foto_pj'])
    {
        // NEW NAME
        $file_name                      = date('dmYHis') . $_FILES['foto_pj']['name'];
        // CONFIG
        $config['upload_path']          = './foto_penjual/';
        $config['allowed_types']        = 'gif|jpg|jpeg|png';
        $config['max_size']             = 20480;
        $config['max_width']            = 5000;
        $config['max_height']           = 5000;
        $config['file_name']            = $file_name;
        // UPLOAD LIBRARY WITH CONFIG
        $this->load->library('upload', $config);
        $data_pj = $this->db->get_where("data_penjual", "id_pj = '$id'", 1)->row();
        // echo $this->db->last_query();
        // UPLOAD
        if ( ! $this->upload->do_upload('foto_pj')){
            $dataFoto = array('error' => $this->upload->display_errors());
            $error['foto_pj'] = $this->upload->display_errors();
            // var_dump($dataFoto);
        }else{
            // UPLOAD IS TRUE CONDITION IS HERE
            if($data_pj->foto_pj!=="" || !empty($data_pj->foto_pj)){
                if(unlink('./foto_penjual/'.$data_pj->foto_pj)){

                }
            }
            // RESIZE PHOTO PROCESS HERE
            $dataFoto = array('upload_data' => $this->upload->data());
            $config = array();
            $config['image_library']='gd2';
            $config['source_image']=base_url().'./foto_penjual/'.$dataFoto['upload_data']['file_name'];
            $config['create_thumb']= FALSE;
            $config['maintain_ratio']= FALSE;
            $config['quality']= '50%';
            $config['width']= 600;
            $config['height']= 400;
            $config['new_image']= base_url().'./foto_penjual/'.$dataFoto['upload_data']['file_name'];
            $this->load->library('image_lib', $config);
            if($this->image_lib->resize()){

            }else{
                $error['foto_pj'] = $this->image_lib->display_errors();
            }
            // var_dump($dataFoto);
            // exit();
            $foto_pembeli       =   $dataFoto['upload_data']['file_name'];
            // ADD foto_pj TO DATA_UPDATE
            $data_update['foto_pj'] = $foto_pembeli;
        }
    }


    if(!empty($_FILES['fotoktp_pj']))
    {
        $path = "./foto_ktp_penjual/";
        $file_name_KTP = basename( $_FILES['fotoktp_pj']['name']);
        $path = $path . $file_name_KTP;

        if(move_uploaded_file($_FILES['fotoktp_pj']['tmp_name'], $path)) {
            $config = array();
            $config['image_library']='gd2';
            $config['source_image']=$path;
            $config['create_thumb']= FALSE;
            $config['maintain_ratio']= FALSE;
            $config['quality']= '50%';
            $config['width']= 840;
            $config['height']= 640;
            $config['new_image']= $path;
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
            $data_update['fotoktp_pj'] = $file_name_KTP;
            $data_pj = $this->db->get_where("data_penjual", "id_pj = '$id'", 1)->row();
            if($data_pj->fotoktp_pj!=="" || !empty($data_pj->fotoktp_pj)){
                if(unlink('./foto_ktp_penjual/'.$data_pj->fotoktp_pj)){

                }
            }
        } else{
            $dataFoto = $_FILES['fotoktp_pj']['error'];
        }
    }
    // var_dump($_FILES);
    // $isFoto_pj = false;
    // $isFotoktp_pj = false;
    // if (is_array($_FILES)) {
    //     if (is_uploaded_file($_FILES['foto_pj']['tmp_name'])) {
    //         $sourcePath2 = $_FILES['foto_pj']['tmp_name'];
    //         $foto_pj = date('dmYHis') . $_FILES['foto_pj']['name'];
    //         $targetPath2 = "../backendikan/foto_penjual/" . $foto_pj;
    //         move_uploaded_file($sourcePath2, $targetPath2);
    //         $isFoto_pj = true;
        // } 
    // }

    // if(!$isFoto_pj){
    //     $foto_pj = $hiddenfoto_pj;
    // }
    // if(!$isFotoktp_pj){
    //     $fotoktp_pj = $hiddenfotoktp_pj;
    // }

    
    $where = array('id_pj' => $id);
    $update = $this->Model_Penjual->update($data_update, $where);

    if($update){
        $this->session->set_flashdata('success', "Berhasil Update Data Penjual");
        redirect(base_url('admin/'.$this->menu));
    }else{
        $this->session->set_flashdata('error', "Gagal Update Data Penjual " . json_encode($error));
        redirect(base_url('admin/'.$this->menu.'/edit/'.$id));
    }
}

public function detail($id){
    $menu = "Penjual";
    // if($this->session->has_userdata('username')){
    $data_penjual = $this->Model_Penjual->get_seller_by($id)->row();
    // var_dump($data_penjual);
    $data_usaha = $this->usaha->get_usaha_by_id_penjual($id)->row();
    // echo $this->db->last_query();
    // exit();
    $data_page = array('data_penjual' => $data_penjual, 'data_usaha' => $data_usaha, 'title' => 'Detail Penjual ' . $data_penjual->nama_pj, 'menu' => 'penjual', 'url_API' => $this->url_API);
    $this->load->view('admin/'.$menu."/detail", $data_page);
    
}

//SUPAYA MEMBEDAKAN MANA API MANA CONTROLLER BIASA
public function GET_PRODUK_USAHA()
{
    $id_usaha = $this->input->post('id_usaha');
    $logged_user = $this->input->post('logged_user');

}

public function delete($id)
{
    // $id_jampengiriman = $this->input->post('id_jampengiriman');
    $delete_usaha = $this->usaha->delete($id);

    $proses = $this->Model_Penjual->delete($id);
    if($proses){
        //jika true
        $this->session->set_flashdata('success', 'Berhasil Hapus Data Penjual');
        redirect(base_url('admin/'.$this->menu));
    }else{
        //jika false
        $this->session->set_flashdata('error', 'Gagal Hapus Data Penjual');
        redirect(base_url('admin/'.$this->menu));
    }

    // $respons = array('status' => $status);
    // header("Content-type: application/json");
    // echo json_encode($respons);

}
}