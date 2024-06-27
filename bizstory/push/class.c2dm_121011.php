<?php
/*----------------------------------------------------------------------------------------------*/
/*
	생성 : 2012.05.24
	위치 : 안드로이드 c2dm push
*/
/*----------------------------------------------------------------------------------------------*/

class MySqlDB_c2dm {

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

class FileLog_c2dm {

	var $module_name;
	var $log_file;
	var $logging;

	function FileLog_c2dm( $module_name = '' ) {
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

class C2DM {

	var $sender = "bizstorycokr@gmail.com";
	var $log;
	var $error = "";

	function C2DM( $module_name = '', $logging = true ) {
		$module_name = trim($module_name);
		$log_file = $_SERVER['DOCUMENT_ROOT'] . "/bizstory/push/log/" . $module_name . "." . date("Ymd") . ".log";
		$this->log = new FileLog_c2dm($module_name);
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

	// google 계정 등록하는 함수
	function googleAuthenticate($username, $password, $source="Company-AppName-Version", $service="ac2dm")
	{
		session_start();
		if( isset($_SESSION['google_auth_id']) && $_SESSION['google_auth_id'] != null)
		{
			return $_SESSION['google_auth_id'];
		}

		// get an authorization token
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://www.google.com/accounts/ClientLogin");
		$post_fields = "accountType=" . urlencode('HOSTED_OR_GOOGLE')
						. "&Email=" . urlencode($username)
						. "&Passwd=" . urlencode($password)
						. "&source=" . urlencode($source)
						. "&service=" . urlencode($service);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// for debugging the request
		//curl_setopt($ch, CURLINFO_HEADER_OUT, true); // for debugging the request

		$response = curl_exec($ch);

		//var_dump(curl_getinfo($ch)); //for debugging the request
		//var_dump($response);

		curl_close($ch);

		if (strpos($response, '200 OK') === false) {
			return false;
		}

		// find the auth code
		preg_match("/(Auth=)([\w|-]+)/", $response, $matches);

		if (!$matches[2]) {
			return false;
		}

		$_SESSION['google_auth_id'] = $matches[2];
		return $matches[2];
	}

	// curl을 이용해서 메세지를 보내는 함수
	function sendMessageToPhone($authCode, $deviceRegistrationId, $msgType, $messageText) {

		$headers = array('Authorization: GoogleLogin auth=' . $authCode);
		$data = array(
					'registration_id' => $deviceRegistrationId,
					'collapse_key' => $msgType,
				//	'data.message' => $messageText //TODO Add more params with just simple data instead
					'data.msg' => $messageText //TODO Add more params with just simple data instead
		);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://android.apis.google.com/c2dm/send");
		if ($headers)
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($ch);

		curl_close($ch);

		return $response;
	}

	function push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message)
	{
		$log = $this->log;
		$db = new MySqlDB_c2dm('bizstory');
		$service_type = "android";			// 서비스 구분 (android, ios)
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
		$sql = "select * from push_member where push_id = '".$receiver."' and push_device_type = 'A' and del_yn = 'N'";
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
				case "reg_ok": // 업체가 신청을 하면 알려준다.
					$send = 1;
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
			// c2dm auth_token 확인
			$sql = "select * from push_c2dm_info where push_id = '".$sender."' and push_enable = 'Y'";
			$log->log("sql = ".$sql);
			$_list = $db->__list($sql);
			if (count($_list) > 0)
			{
				$row = $_list[0];
				$auth_token = $row['push_auth_token'];

				$debug .= " => 서버 인증토큰 OK";

				if ($auth_token == "")
				{
					$auth_token = googleAuthenticate("bizstorycokr@gmail.com", "biz12345");
					$log->log("신규 auth_token = ".$auth_token);

					if ($auth_token)
					{
						$sql = "update push_c2dm_info set push_auth_token = '".$auth_token."', mod_date = now() where push_id = '".$sender."'";
					}
					$log->log("sql = ".$sql);
					$db->__execute($sql);

					$debug .= " => 서버토큰 등록 OK";
				}

				if ($auth_token)
				{
				//	$log->log("auth_token = ".$auth_token);
				//	$log->log("registration_id = ".$registration_id);
					$log->log("msg_type = ".$msg_type);
					$log->log("message = ".$message);

					// c2dm 전송
					$response = $this->sendMessageToPhone($auth_token, $registration_id, $send_key, $message);
					$log->log("sendMessageToPhone = ".$response);

					// c2dm 메시지 전송결과 확인
					if (strpos($response, 'id=') === false) {
						$log->log("c2dm 전송 에러");
					//	$response = $res;

						if (strpos($response, 'Error 401')) {
							$log->log("Error 401 수신");

							// 서버인증 토큰을 새로 만들어서 재전송한다.
							$auth_token = googleAuthenticate("bizstorycokr@gmail.com", "biz12345");
							$log->log("신규 auth_token = ".$auth_token);

							if ($auth_token)
							{
								$sql = "update push_c2dm_info set push_auth_token = '".$auth_token."', mod_date = now() where push_id = '".$sender."'";
							}
							$log->log("sql = ".$sql);
							$db->__execute($sql);

							$debug .= " => 서버토큰 재등록 OK";
						}
						$response = $this->sendMessageToPhone($auth_token, $registration_id, $msg_type, $message);
						$log->log("sendMessageToPhone = ".$response);
						if (strpos($response, 'id=') === false) {
							$debug .= " => c2dm 재전송 실패";

							$sql = "update push_history set state = '0', state_comment = '전송 실패', send_time = now() where send_key = '$send_key'";
							$log->log("sql = ".$sql);
							$db->__execute($sql);
						}
						else {
							$debug .= " => c2dm 재전송 OK";

							$sql = "update push_history set state = '1', state_comment = '전송 OK', send_time = now() where send_key = '$send_key'";
							$log->log("sql = ".$sql);
							$db->__execute($sql);

							$result = 1;
						}
					}
					else {
						$debug .= " => c2dm 전송 OK";

						$sql = "update push_history set state = '1', state_comment = '전송 OK', send_time = now() where send_key = '$send_key'";
						$log->log("sql = ".$sql);
						$db->__execute($sql);

						$result = 1;
					}

					$debug .= " => ** END **";
				}
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
?>