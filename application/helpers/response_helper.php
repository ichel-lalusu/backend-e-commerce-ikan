<?php
if(!function_exists("response")){
	function response(int $status_header, array $data=array())
	{
		$CI =& get_instance();
		return $CI->output
            ->set_status_header($status_header)
            ->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		exit();
	}
}
if(!function_exists('cek_pb')){
	function cek_pb($id_pb="")
	{
		if($id_pb!==""){
			$CI =& get_instance();
			$data = $CI->db->select("id_pb")->where("id_pb", $id_pb)->get("data_pembeli");
			$result = ($data->num_rows() > 0) ? TRUE : FALSE;
			return $result;
		}else{
			return FALSE;
		}
	}
}
if(!function_exists('cek_pj')){
	function cek_pj($id_pj="")
	{
		if($id_pj!==""){
			$CI =& get_instance();
			$data = $CI->db->select("id_pj")->where("id_pj", $id_pj)->get("data_penjual");
			$result = ($data->num_rows() > 0) ? TRUE : FALSE;
			return $result;
		}else{
			return FALSE;
		}
	}
}
if(!function_exists('cek_username')){
	function cek_username($username)
	{
		if($username!==""){
			$CI =& get_instance();
			$data = $CI->db->select("useranme")->where("username", $username)->get("data_pengguna");
			$result = ($data->num_rows() > 0) ? TRUE : FALSE;
			return $result;
		}
	}
}
function unauthorize_user(){
	$CI =& get_instance();
	response(401, array('message' => "Unauthorize. User Not Found"));
}
