<?php

class FileLog {
	var $module_name;
	var $log_file;
	var $logging;

	function FileLog( $module_name = '' ) {
		$this->module_name = $module_name;
		if(strlen($this->module_name) > 0)
			$this->module_name .= ": ";
		else
			$this->module_name = "";
		$this->logging = false;
	}

	function log( $data )
	{
		if($this->logging == true) {
			$handle = fopen($this->log_file, "a+");
			fwrite($handle, $this->module_name.$data."\r\n");
			fclose($handle);
		}
	}

	function blank_line()
	{
		if($this->logging == true) {
			$handle = fopen($this->log_file, "a+");
			fwrite($handle, "\r\n");
			fclose($handle);
		}
	}
}

?>
