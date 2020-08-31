<?php
/**
 * 
 */
class Kendaraan extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("Model_kendaraan");
	}

	public function index()
	{
		try {
			if($this->input->get('id_usaha')){
				return $this->get_by_id_usaha();
			}
			if($this->input->get('id_kendaraan')){
				return $this->get_by_id_kendaraan();
			}
			$Model = $this->Model_kendaraan;
			$data = $Model->getAll();
			if($data->num_rows() > 0){
				$data = $data->result_array();
				response(200, $data);
			}else{
				response(404, array('message' => 'Not Found'));
			}
		} catch (Exception $e) {
			response(500, array('message' => $e->getMessage()));
		}
	}

	public function get_by_id_usaha()
	{
		try {
			$kendaraan = $this->Model_kendaraan;
			$id_usaha = $this->input->get("id_usaha", TRUE);
			$data = $kendaraan->getWhere("id_usaha=$id_usaha");
			if($data->num_rows() > 0){
				$data = $data->row();
				response(200, $data);
			}else{
				response(404, array('message' => "Not Found"));
			}
		} catch (Exception $e) {
			response(500, array('message' => "Server Error"));
		}
	}

	public function get_by_id_kendaraan()
	{
		try {
			$kendaraan = $this->Model_kendaraan;
			$id_kendaraan = $this->input->get("id_kendaraan", TRUE);
			$data = $kendaraan->getWhere("id_kendaraan=$id_kendaraan");
			if($data->num_rows() > 0){
				$data = $data->row();
				response(200, $data);
			}else{
				response(404, array('message' => "Not Found"));
			}
		} catch (Exception $e) {
			response(500, array('message' => "Server Error"));
		}
	}

	public function create()
	{
		if($this->input->post()){
			$post = $this->input->post();
			$model = $this->Model_kendaraan();
		}
	}
}