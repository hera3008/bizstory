<?
/*
	생성 : 2013.04.04
	위치 : 업무관리 > 프로젝트관리 > 보기 - 상태실행
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

//기초값 검사
	function chk_before($param, $chk_type = 'json')
	{
	//필수검사
		$chk_param['require'][] = array("field"=>"deadline_date", "msg"=>"기한");
		$chk_param['require'][] = array("field"=>"charge_idx", "msg"=>"담당자");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 반려로 변경
    function status70()
    {
        global $_POST, $_SESSION, $sess_str;

        $pro_idx  = $_POST['pro_idx'];
        $comp_idx = $_SESSION[$sess_str . '_comp_idx'];
        $part_idx = $_POST['code_part'];
        $contents = $_POST['status_contents'];

        $command    = "update"; //명령어
        $table      = "project_info"; //테이블명
        $conditions = "pro_idx = '" . $pro_idx . "'"; //조건

        $param['mod_id']     = $_SESSION[$sess_str . '_mem_idx'];
        $param['mod_date']   = date("Y-m-d H:i:s");
        $param["pro_status"] = 'PS70'; 
        
        chk_before($param);

        $query_str = make_sql($param, $command, $table, $conditions);
        db_query($query_str);
        query_history($query_str, $table, $command);
        
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // 히스토리수정
        $uhi_command    = "update"; //명령어
        $uhi_table      = "project_status_history"; //테이블명
        $uhi_conditions = "pro_idx = '" . $pro_idx . "' and status = 'PS90' and status_type = 'new'"; //조건

        $uhi_param['mod_id']      = $_SESSION[$sess_str . '_mem_idx'];
        $uhi_param['mod_date']    = date('Y-m-d H:i:s');
        $uhi_param['status_type'] = 'old';

        $query_str = make_sql($uhi_param, $uhi_command, $uhi_table, $uhi_conditions);
        db_query($query_str);
        query_history($query_str, $uhi_table, $uhi_command);

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // 히스토리저장
        $hi_command    = "insert"; //명령어
        $hi_table      = "project_status_history"; //테이블명
        $hi_conditions = ""; //조건

        $hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
        $hi_param['reg_date']    = date('Y-m-d H:i:s');
        $hi_param['comp_idx']    = $comp_idx;
        $hi_param['part_idx']    = $part_idx;
        $hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
        $hi_param['pro_idx']     = $pro_idx;
        $hi_param['status']      = $param["pro_status"];
        $hi_param["contents"]    = $contents; // 반려사유
        $hi_param['status_date'] = date('Y-m-d H:i:s');
        $hi_param['status_memo'] = '프로젝트가 반려되었습니다.';

        $query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
        db_query($query_str);
        query_history($query_str, $hi_table, $hi_command);

        $str = '{"success_chk" : "Y", "error_string" : ""}';
        echo $str;
        exit;
    }

// 완료로 변경
	function status90()
	{
		global $_POST, $_SESSION, $sess_str;

		$pro_idx  = $_POST['pro_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];
        $force_yn = $_POST['force_yn'];
        $contents = $_POST['status_contents'];
        
		$command    = "update"; //명령어
		$table      = "project_info"; //테이블명
		$conditions = "pro_idx = '" . $pro_idx . "'"; //조건

		$param['mod_id']     = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date']   = date("Y-m-d H:i:s");
		$param["pro_status"] = 'PS90'; // 완료
		
		$param["end_date"]   = date("Y-m-d H:i:s"); // 업무완료

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "project_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['pro_idx']     = $pro_idx;
		$hi_param['status']      = $param["pro_status"];
        $hi_param['force_yn']    = $force_yn;
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		        
        if ($force_yn == 'Y') {
            $hi_param['status_memo'] = '프로젝트가 강제완료되었습니다.';
            
            if ($contents == '') {
                $hi_param["contents"]    = '프로젝트가 강제 종료 되었습니다.'; 
            } else {
                $hi_param["contents"]    = $contents; 
            }
        } else {
            $hi_param['status_memo'] = '프로젝트가 완료되었습니다.';
        }

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}
?>