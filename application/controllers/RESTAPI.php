<?php
/**
 * 
 */
use chriskacerguis\RestServer\RestController;
class RESTAPI extends RestController
{
	
	function __construct()
	{
		# code...
		parent::__construct();
	}

	public function index_get()
	{
	    $response['message']='Hai from response';
	// tampilkan response
	    $this->response($response, 200);
	}

	public function index_post()
	{
		$nama = $this->post('nama');
		$response = array();
		if(empty($nama)||$nama=null||$nama==""){
			$status_header = 404;
		}else{
			$status_header = 200;
			$response['nama'] = $nama;
		}
		$this->response($response, $status_header);
	}

	public function index_put()
	{
		# code...
	}
}