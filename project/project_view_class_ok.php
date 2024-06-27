<?
/*
	생성 : 2012.12.26
	수정 : 2012.12.26
	위치 : 업무폴더 > 프로젝트관리 - 보기 - 작업 - 실행
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
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"작업명");
		$chk_param['require'][] = array("field"=>"deadline_date", "msg"=>"기한");
		$chk_param['require'][] = array("field"=>"charge_idx", "msg"=>"담당자");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];
		$pro_idx  = $_POST['pro_idx'];

		$command    = "insert"; //명령어
		$table      = "project_class"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $mem_idx;
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['pro_idx']  = $pro_idx;

		$chk_data = query_view("select max(proc_idx) as proc_idx from " . $table);
		$param['proc_idx'] = ($chk_data['proc_idx'] == '') ? '1' : $chk_data['proc_idx'] + 1;
        
        $data = query_view("select max(sort) as max_sort from " . $table . " where comp_idx='" . $comp_idx . "' and part_idx='" . $part_idx . "' and pro_idx='" . $pro_idx . "' and del_yn='N' ");
        $param['sort'] = ($data['max_sort'] == '') ? '1' : $data['max_sort'] + 1;

	// 기한, 담당자가 없을 경우 업무대기로 설정
		if ($param['deadline_date'] == '' || $param['charge_idx'] == '')
		{
			$param["class_status"] = 'PS01'; // 업무대기
		}
		else
		{
			$param["class_status"] = 'PS02'; // 업무진행
		}

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "project_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $mem_idx;
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $mem_idx;
		$hi_param['pro_idx']     = $pro_idx;
		$hi_param['proc_idx']    = $param['proc_idx'];
		$hi_param['status']      = '';
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '프로젝트 작업이 등록되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 작업 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$pro_idx  = $_POST['pro_idx'];
		$proc_idx = $_POST['proc_idx'];
		$old_deadline_date = $_POST['old_deadline_date'];
		$old_charge_idx    = $_POST['old_charge_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

		$command    = "update"; //명령어
		$table      = "project_class"; //테이블명
		$conditions = "proc_idx='" . $proc_idx . "'"; //조건

		$param["mod_id"]   = $mem_idx;
		$param["mod_date"] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$total_history = '';
	// 기한
		if ($param['deadline_date'] != $old_deadline_date)
		{
			$total_history .= '프로젝트 작업 기한 ' . $old_deadline_date . '(에)서 ' . $param['deadline_date'] . '(으)로 변경되었습니다. ';
		}
	// 담당자
		if ($param['charge_idx'] != $old_charge_idx)
		{
		// 담당자명 구하기 - 예전
			$old_charge_arr = explode(',', $old_charge_idx);
			$old_charge = '';
			foreach ($old_charge_arr as $old_k => $old_v)
			{
				$mem_where = " and mem.mem_idx = '" . $old_v . "'";
				$mem_data = member_info_data('view', $mem_where);
				$old_charge .= $mem_data['mem_name'];
				if ($old_k < count($old_charge_arr)-1)
				{
					$old_charge .= ', ';
				}
			}

		// 담당자명 구하기 - 새로
			$new_charge_idx = $param['charge_idx'];
			$new_charge_arr = explode(',', $new_charge_idx);
			$new_charge = '';
			foreach ($new_charge_arr as $new_k => $new_v)
			{
				$mem_where = " and mem.mem_idx = '" . $new_v . "'";
				$mem_data = member_info_data('view', $mem_where);
				$new_charge .= $mem_data['mem_name'];
				if ($new_k < count($new_charge_arr)-1)
				{
					$new_charge .= ', ';
				}
			}

			$total_history .= '프로젝트 작업 담당자 ' . $old_charge . '(에)서 ' . $new_charge . '(으)로 변경되었습니다. ';
		}

		if ($total_history != '')
		{
			$hi_command    = "insert"; //명령어
			$hi_table      = "project_status_history"; //테이블명
			$hi_conditions = ""; //조건

			$hi_param['reg_id']      = $mem_idx;
			$hi_param['reg_date']    = date('Y-m-d H:i:s');
			$hi_param['comp_idx']    = $comp_idx;
			$hi_param['part_idx']    = $part_idx;
			$hi_param['mem_idx']     = $mem_idx;
			$hi_param['pro_idx']     = $pro_idx;
			$hi_param['proc_idx']    = $proc_idx;
			$hi_param['status']      = '';
			$hi_param['status_date'] = date('Y-m-d H:i:s');
			$hi_param['status_memo'] = $total_history;

			$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
			db_query($query_str);
			query_history($query_str, $hi_table, $hi_command);
		}

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 작업 삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$proc_idx = $_POST['proc_idx'];

		$command    = "update"; //명령어
		$table      = "project_class"; //테이블명
		$conditions = "proc_idx = '" . $proc_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $mem_idx;
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//위 정렬 함수
    function sort_up()
    {
        global $_POST, $_SESSION, $sess_str;

        $table = "project_class"; //테이블명

        $proc_idx = $_POST['idx'];
        $comp_idx = $_SESSION[$sess_str . '_comp_idx'];
        $part_idx = $_SESSION[$sess_str . '_part_idx'];
        $mem_idx  = $_SESSION[$sess_str . '_mem_idx'];
        $pro_idx  = $_POST['pro_idx'];

        $project_class_where = " and proc.proc_idx = '" . $proc_idx . "'";
        $data = project_class_data('view', $project_class_where);
        
        $sort_where = " and proc.comp_idx = '" . $comp_idx . "' and proc.part_idx = '" . $part_idx . "' and proc.pro_idx = '" . $pro_idx . "' and proc.sort < '" . $data["sort"] . "'";
        $sort_order = "proc.sort desc";
        $prev_data = project_class_data('view', $sort_where, $sort_order);

        $sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where proc_idx = '" . $proc_idx . "'";
        db_query($sql);
        query_history($sql, $table, "update");

        $sql = "update " . $table . " set sort = '" . $data["sort"] . "' where proc_idx = '" . $prev_data["proc_idx"] . "'";
        db_query($sql);
        query_history($sql, $table, "update");

        $sort_where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "' and pro_idx = '" . $pro_idx . "' ";
        data_sort_action($table, 'proc_idx', $sort_where);

        $str = '{"success_chk" : "Y", "error_string":""}';
        echo $str;
        exit;
    }

//아래 정렬 함수
    function sort_down()
    {
        global $_POST, $_SESSION, $sess_str;

        $table = "project_class"; //테이블명

        $proc_idx = $_POST['idx'];
        $comp_idx = $_SESSION[$sess_str . '_comp_idx'];
        $part_idx = $_SESSION[$sess_str . '_part_idx'];
        $mem_idx  = $_SESSION[$sess_str . '_mem_idx'];
        $pro_idx  = $_POST['pro_idx'];

        $where = " and proc.proc_idx = '" . $proc_idx . "'";
        $data = project_class_data('view', $where);

        $sort_where = " and proc.comp_idx = '" . $comp_idx . "' and proc.part_idx = '" . $part_idx . "' and proc.pro_idx = '" . $pro_idx . "' and proc.sort > '" . $data["sort"] . "'";
        $sort_order = "proc.sort asc";
        $next_data = project_class_data('view', $sort_where, $sort_order);

        $sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where proc_idx = '" . $proc_idx . "'";
        db_query($sql);
        query_history($sql, $table, "update");

        $sql = "update " . $table . " set sort = '" . $data["sort"] . "' where proc_idx = '" . $next_data["proc_idx"] . "'";
        db_query($sql);
        query_history($sql, $table, "update");

        $sort_where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "' and pro_idx = '" . $pro_idx . "' ";
        data_sort_action($table, 'proc_idx', $sort_where);

        $str = '{"success_chk" : "Y", "error_string":""}';
        echo $str;
        exit;
    }
?>