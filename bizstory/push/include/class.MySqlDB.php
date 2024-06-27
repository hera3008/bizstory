<?php
class MySqlDB {
	private $Host     = '183.111.148.115';
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

function __row($dbName='', $sql){
	$db = new MyDB($dbName);
	$result = $db->__row($sql);
	$db->__close();
	return $result;
}

function __list($dbName='', $sql){
	$db = new MyDB($dbName);
	$result = $db->__list($sql);
	$db->__close();
	return $result;
}

function __execute($dbName='', $sql){
	$db = new MyDB($dbName);
	$result = $db->__execute($sql);
	$db->__close();
	return $result;
}

function __query($dbName='', $sql){
	$db = new MyDB($dbName);
	$result = $db->__execute($sql);
	$db->__close();
	return $result;
}

?>
