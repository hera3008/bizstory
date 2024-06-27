<?
/*
	생성 : 2013.05.20
	수정 : 2013.05.20
	위치 : 설정폴더(관리자) > 업체관리 > 삭제업체 - 실행
*/
	include "../common/setting.php";
	include "../common/no_direct.php";
	include "../common/member_chk.php";

	if($sub_type == "")
	{
		$str = '{"success_chk" : "N", "error_string" : "sub_type 명이 필요합니다."}';
		echo $str;
		exit;
	}

	if(!function_exists($sub_type))
	{
		$str = '{"success_chk" : "N", "error_string" : "sub_type method 가 없습니다."}';
		echo $str;
		exit;
	}
	call_user_func($sub_type);
	exit;

//복구 함수
	function return_comp()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$comp_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "company_info"; //테이블명
		$conditions = "comp_idx = '" . $comp_idx . "'"; //조건

		$param['del_yn']   = "N";
		$param['del_ip']   = NULL;
		$param['del_id']   = NULL;
		$param['del_date'] = NULL;

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
?>