<?php
/*----------------------------------------------------------------------------------------------*/
/*
	생성 : 2012.05.24
	위치 : ios apns push
*/
/*----------------------------------------------------------------------------------------------*/

class MySqlDB_apns {

	private $Host     = 'localhost';
	private $User     = 'root';
	private $Pass     = 'uBpass4862';
	private $Database = 'bizstory';

	private $resource  = null;
	private $resultSet = array();
	private $record    = array();
	private $insertId  = 0;

	private $pconnect = false;
	public  $autoFree = true;

	private $charset = 'set names utf8';
//	private $charset = 'set names euckr';

	function __construct($select_db='bizstory'){

		/*
		global $_DB;
		if(!$_DB){
		//	include CONFIG_PATH . 'config.db.php';
			include 'config.db.php';
		}

		$DB = $_DB[$select_db];
		if($DB && is_array($DB)){
			$this->Host = $DB['host'];
			$this->User = $DB['account'];
			$this->Pass = $DB['password'];
			$this->Database = $DB['database'];
		}else{
			die('설정파일이 존재하지 않거나 설정 내용이 없습니다.');
		}
		*/
	}

	private function connect(){
		if($this->pconnect){
			$this->resource = @mysql_pconnect($this->Host, $this->User, $this->Pass);
		}else{
			$this->resource = mysql_connect($this->Host, $this->User, $this->Pass);
		}

		if(!is_resource($this->resource)){
			$this->disconnect();
			if(ITC){
				$msg = 'Host : '.$this->Host."<BR>\n".' User : '.$this->User."<BR>\n".' Pass : '.$this->Pass."<BR>\n".' DB에 접속할 수 없습니다.';
			}else{
				$msg = 'DB에 접속할 수 없습니다.';
			}
			die($msg);###################################################################################################################################
			return null;
		}
		@mysql_query($this->charset);
		if(!mysql_select_db($this->Database, $this->resource)){
			$this->disconnect();
			die('Database : '.$this->Database.' 는 존재하지 않거나 사용권한이 없습니다.');
			return null;
		}
		return $this->resource;
	}

	private function disconnect(){
		if($this->resource) @mysql_close($this->resource);
		$this->resource = null;
	}

	private function free($resultSet=''){//리소스 삭제
		if($resultSet){
			@mysql_free_result($resultSet);
		}else{
			@mysql_free_result($this->resultSet);
			$this->resultSet = null;
		}
	}

	public function __row($sql){
		$this->connect();
		$this->resultSet = mysql_query($sql,$this->resource);
		$errno = mysql_errno($this->resource);
		if(!$errno){
			$size = @mysql_num_rows($this->resultSet);
			if($size>0){
				$this->record = $this->__fetch();
			}else{
				$this->record = null;
			}
			$this->free();
			$this->disconnect();
			return $this->record;
		}else{
			$error = mysql_error($this->resource);
			$this->disconnect();
			die($errno.' : '.$error);
		}
	}

	public function __list($sql){
		$this->connect();
		$this->resultSet = mysql_query($sql,$this->resource);

		$errno = mysql_errno($this->resource);
		if(!$errno){
			$size = @mysql_num_rows($this->resultSet);
			if($size>0){
				for($i=0;$i<$size;$i++){
					$list[] = mysql_fetch_array($this->resultSet);
				}
			}
			$this->disconnect();
			$this->free($this->resultSet);
			return $list;
		}else{
			$error = mysql_error($this->resource);
			$this->disconnect();
			die($errno.' : '.$error);
		}
	}

	public function __fetch($resultSet='', $mode = 'array'){
		if(!$resultSet){
			if(!$this->resultSet){
				die('no resultSet');
			}else{
				$resultSet = $this->resultSet;
			}
		}else{
			$this->resultSet = $resultSet;
		}

		if($mode == 'array'){
			$this->record = @mysql_fetch_array($this->resultSet);
		}else if($mode == 'assoc'){
			$this->record = mysql_Fetch_assoc($this->resultSet);
		}else if($mode == 'row'){
			$this->record = mysql_fetch_row($this->resultSet);
		}else if($mode == 'object'){
			$this->record = mysql_fetch_object($this->resultSet);
		}

		$check = is_array($this->record);
		if(!$check && $this->autoFree){
			$this->free($resultSet);
			$this->free();
			$this->disconnect();
		}

		return $this->record;
	}

	public function __execute($sql){
		$this->connect();
		$result = mysql_query($sql,$this->resource);
		$errno = mysql_errno($this->resource);
		if(!$errno){
			if(strpos(strtolower(trim($sql)),'insert')!==false){
				if($result){
					$this->insertId = mysql_insert_id($this->resource);
				}else{
					$this->insertId = 0;
				}
			}
			$this->disconnect();
			return $result;
		}else{
			$error = mysql_error($this->resource);
			$this->disconnect();
			die($errno.' : '.$error);
		}
	}

	public function __query($sql){
		$this->connect();
		$r = mysql_query($sql,$this->resource);
		$errno = mysql_errno($this->resource);
		if(!$errno){
			$this->resultSet = $r;
			$this->disconnect();
			return $this->resultSet;
		}else{
			$error = mysql_error($this->resource);
			$this->disconnect();
			die($errno.' : '.$error);
		}
	}

	public function getInsertId(){
		return $this->insertId;
	}

	public function __close(){
		$this->free();
		$this->disconnect();
	}
}//End of Class DB

/*----------------------------------------------------------------------------------------------*/

class FileLog_apns {

	var $module_name;
	var $log_file;
	var $logging;

	function FileLog_apns( $module_name = '' ) {
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

/*----------------------------------------------------------------------------------------------*/

class APNS {

	var $sender = "bizstorycokr@gmail.com";
	var $log;
	var $error = "";

	function APNS( $module_name = '', $logging = true ) {
		$module_name = trim($module_name);
		$log_file = $_SERVER['DOCUMENT_ROOT'] . "/bizstory/push/log/" . $module_name . "." . date("Ymd") . ".log";
		$this->log = new FileLog_apns($module_name);
		$this->log->log_file = $log_file;
		$this->log->logging = $logging;

		$this->log->blank_line();
		$this->log->log("** ".date("Ymd-His"));
	}

	// push 사용자 등록
	function register()
	{
	}

	function unregister()
	{
	}

	function push_send($sender, $registration_id, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message)
	{
		$log = $this->log;
		$db = new MySqlDB_apns('bizstory');
		$service_type = "ios";				// 서비스 구분 (android, ios)
		$send = 0;							// 전송여부 상태
		$state = "0";						// 전송결과 (1:성공, 0:실패)
		$state_comment = "";
		$debug = "";
		$result = 0;

		if ($sender == "") $sender = "bizstorycokr@gmail.com";
		if ($comp_idx == "") $comp_idx = 0;
		if ($part_idx == "") $part_idx = 0;
		if ($mem_idx == "") $mem_idx = 0;

		$log->log("sender=$sender, comp_idx=$comp_idx, part_idx=$part_idx, mem_idx=$mem_idx, receiver=$receiver, msg_type=$msg_type, message=$message");

		// 수신자 정보를 확인한다.
		$sql = "select * from push_member where push_id = '".$receiver."' and push_device_type = 'I' and push_registration_id = '$registration_id' and del_yn = 'N'";
		$log->log("sql = ".$sql);
		$_list = $db->__list($sql);
		if (count($_list) > 0)
		{
			$debug .= "수신자 확인 OK";

			$row = $_list[0];

			$comp_idx = $row['comp_idx'];
			$part_idx = $row['part_idx'];
			$mem_idx = $row['mem_idx'];

			$receiver_name = $row['push_name'];
			$registration_id = $row['push_registration_id'];
		//	$log->log("[".$row['push_id']."] registration_id = ".$registration_id.", message = ".$row['push_message'].", receipt = ".$row['push_receipt'].", work = ".$row['push_work'].", notice = ".$row['push_notice']);
			$log->log("[".$row['push_id']."] message = ".$row['push_message'].", receipt = ".$row['push_receipt'].", work = ".$row['push_work'].", notice = ".$row['push_notice']);

			// 메시지 타입에 대한 전송설정 상태를 확인한다.
			switch ($msg_type)
			{
				case "message":
					if ($row['push_message'] == 'Y')
						$send = 1;
					else
					{
						$log->log($receiver." [".$receiver_name."] 쪽지알림 사용하지 않음");

						$state = "0";
						$state_comment = "쪽지알림 사용하지 않음";
					}
					break;
				case "receipt":
					if ($row['push_receipt'] == 'Y')
						$send = 1;
					else
					{
						$log->log($receiver." [".$receiver_name."] 접수알림 사용하지 않음");

						$state = "0";
						$state_comment = "접수알림 사용하지 않음";
					}
					break;
				case "work":
					if ($row['push_work'] == 'Y')
						$send = 1;
					else
					{
						$log->log($receiver." [".$receiver_name."] 업무알림 사용하지 않음");

						$state = "0";
						$state_comment = "업무알림 사용하지 않음";
					}
					break;
				case "notice":
					if ($row['push_notice'] == 'Y')
						$send = 1;
					else
					{
						$log->log($receiver." [".$receiver_name."] 공지알림 사용하지 않음");

						$state = "0";
						$state_comment = "공지알림 사용하지 않음";
					}
					break;
				default:
					$log->log($receiver." [".$receiver_name."] '".$msg_type."' 등록되지 않은 알림타입");

					$state = "0";
					$state_comment = "등록되지 않은 알림타입";
					break;
			}

			if ($row['push_enable'] != 'Y')
			{
				$log->log($receiver." [".$row['push_name']."] 알림설정 사용하지 않음");
				$send = 0;
				$state = "0";
				$state_comment = "알림설정 사용하지 않음";
			}
		}
		else
		{
			$log->log("등록되지 않은 사용자");
			$state = "0";
			$state_comment = "등록되지 않은 사용자";
		}

		if ($send)
			$debug .= " => 전송데이터 확인 OK";
		else
			$debug .= " => ".$state_comment;


		$log->log($debug);

		// push 전송기록 등록
		$send_key = date(YmdHis).rand(100000, 999999);

		$sql = "insert into push_history (send_key, comp_idx, part_idx, mem_idx, receiver_id, receiver_name, service_type, msg_type, message, state, state_comment, request_time) "
			 . "values ( '$send_key', $comp_idx, $part_idx, $mem_idx, '$receiver', '$receiver_name', '$service_type', '$msg_type', '$message', '$state', '$state_comment', now())";
		$log->log("sql = ".$sql);
		$db->__execute($sql);

		if ($send == 1)
		{
		//	$deviceToken = '67813b2...af1424b'; // 디바이스토큰ID
		//	$message = 'Message received from eye'; // 전송할 메시지

			// 개발용
			//$apnsHost = 'gateway.sandbox.push.apple.com';
			$local_path = "/www/data/bizstory";

			//$apnsCert = $local_path . '/bizstory/push/cer/apns_bizstory_final.pem';
			$certPass = "arachi76";

			// 실서비스용
			$apnsHost = 'gateway.push.apple.com';
			$apnsCert = $local_path . '/bizstory/push/cer/apns-production.pem';

			$log->log("apnsCert = ".$apnsCert);
			$apnsPort = 2195;

			$payload = array('aps' => array('alert' => $message, 'badge' => 0, 'sound' => 'default'));
			$payload = json_encode($payload);
			$log->log("payload = ".$payload);

			$streamContext = stream_context_create();
			stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
		//	stream_context_set_option($streamContext, 'ssl', 'passphrase', $certPass);

			$apns = stream_socket_client('ssl://'.$apnsHost.':'.$apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
			//$apns = stream_socket_client("ssl://gateway.sandbox.push.apple.com:2195", $err, $errstr, 60, STREAM_CLIENT_CONNECT, $streamContext);

			if($apns)
			{
				$apnsMessage = chr(0).chr(0).chr(32).pack('H*', str_replace(' ', '', $registration_id)).chr(0).chr(strlen($payload)).$payload;
				fwrite($apns, $apnsMessage);
				fclose($apns);
				$log->log("apnsMessage = ".$apnsMessage);

				$debug .= " => apns 전송 OK";

				$sql = "update push_history set state = '1', state_comment = '전송 OK', send_time = now() where send_key = '$send_key'";
				$log->log("sql = ".$sql);
				$db->__execute($sql);

				$result = 1;
			}

		}

		return $result;
	}

	// 암호화변환
	function pass_change($str, $sess_str)
	{
		$sess_length = strlen($sess_str);
		$str_length  = strlen($str);

		$total_str = "";

		if ($sess_length > $str_length)
		{
			$chk_length = $sess_length;
		}
		else
		{
			$chk_length = $str_length;
		}

		for ($i = 0; $i < $chk_length; $i++)
		{
			$sess_char = substr($sess_str, $i, 1);
			$str_char  = substr($str, $i, 1);
			$total_str .= $sess_char . $str_char;
		}

		$total_str = $total_str . $sess_str;
		$total_str = md5($total_str);
		$total_str = $sess_str . $total_str;
		$total_str = md5($total_str);
		$str = $total_str;

		return $str;
	}

}

/*----------------------------------------------------------------------------------------------*/