<?php
if(!function_exists("response")){
	function response($status_header, $data)
	{
		$this->output
            ->set_status_header($status_header)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
}