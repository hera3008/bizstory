<?
/*
	생성 : 2012.07.02
	위치 : 설정관리 > 접수관리 > 에이전트관리 > 배너관리 - 실행
*/

	include "../common/setting.php";
	include "../common/no_direct.php";
	include "../common/member_chk.php";
	
	print_r($_POST);

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
		$chk_param['require'][] = array("field"=>"content", "msg"=>"내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $param['part_idx'];

	// 등록
		$command    = "insert"; //명령어
		$table      = "agent_banner"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;

		if ($param['view_yn'] == '') $param['view_yn'] = 'Y';

		$data = query_view("select max(ab_idx) as ab_idx from " . $table . "");
		$param["ab_idx"] = ($data["ab_idx"] == "") ? "1" : $data["ab_idx"] + 1;

		$data = query_view("
			select max(sort) as max_sort
			from " . $table . "
			where del_yn = 'N' and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'");
		$param["sort"] = ($data["max_sort"] == "") ? "1" : $data["max_sort"] + 1;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'ab_idx', $where);

	// 파일저장
		global $tmp_path, $comp_banner_path;

		$ab_idx = $param["ab_idx"];

		$file_command    = "update"; //명령어
		$file_table      = "agent_banner"; //테이블명
		$file_conditions = "ab_idx = '" . $ab_idx . "'"; //조건

		$reg_id   = $_SESSION[$sess_str . '_mem_idx'];
		$reg_date = date("Y-m-d H:i:s");

		$new_path1 = $comp_banner_path;
		
		
		$file_num = $_POST['file_upload_num'];

		for ($i = 1; $i <= $file_num; $i++)
		{
			$chk_file_save = $_POST['f_fname'.$i.'_save_name'];
			if ($chk_file_save != '')
			{
				$chk_file_name = $_POST['f_fname'.$i.'_file_name'];
				$chk_file_size = $_POST['f_fname'.$i.'_file_size'];
				$chk_file_type = $_POST['f_fname'.$i.'_file_type'];
				$chk_file_ext  = $_POST['f_fname'.$i.'_file_ext'];

				$chk_file_size = str_replace(',', '', $chk_file_size);
				$new_file_name = $ab_idx . '_' . time() . '.' . $chk_file_ext;

				$old_file = $tmp_path . '/' . $chk_file_save;
				$new_file = $new_path1 . '/' . $new_file_name;

				echo "$old_file, $new_file";

				if (file_exists($old_file))
				{
					if(!copy($old_file, $new_file))
					{
						$str = '{"success_chk" : "N", "error_string" : "저장시 오류가 생겼습니다. <br />다시 확인하고 파일을 올리세요."}';
						exit;
					}

					$file_param['img_fname'] = $chk_file_name;
					$file_param['img_sname'] = $new_file_name;
					$file_param['img_size']  = $chk_file_size;
					$file_param['img_type']  = $chk_file_type;
					$file_param['img_ext']   = $chk_file_ext;

					$query_str = make_sql($file_param, $file_command, $file_table, $file_conditions);
					echo "<pre> $query_str </pre>";
					db_query($query_str);
					query_history($query_str, $file_table, $file_command);

					unlink($old_file);
				}
			}
		}

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$ab_idx   = $_POST['ab_idx'];
		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $param['part_idx'];

	// 수정
		$command    = "update"; //명령어
		$table      = "agent_banner"; //테이블명
		$conditions = "ab_idx = '" . $ab_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param['view_yn'] == '') $param['view_yn'] = 'N';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'ab_idx', $where);

	// 파일저장
		global $tmp_path, $comp_banner_path;

		$file_command    = "update"; //명령어
		$file_table      = "agent_banner"; //테이블명
		$file_conditions = "ab_idx = '" . $ab_idx . "'"; //조건

		$reg_id   = $_SESSION[$sess_str . '_mem_idx'];
		$reg_date = date("Y-m-d H:i:s");

		$new_path1 = $comp_banner_path;
		
		$file_num = $_POST['file_upload_num'];

		for ($i = 1; $i <= $file_num; $i++)
		{
			$chk_file_save = $_POST['f_fname'.$i.'_save_name'];
			if ($chk_file_save != '')
			{

				$chk_file_name = $_POST['f_fname'.$i.'_file_name'];
				$chk_file_size = $_POST['f_fname'.$i.'_file_size'];
				$chk_file_type = $_POST['f_fname'.$i.'_file_type'];
				$chk_file_ext  = $_POST['f_fname'.$i.'_file_ext'];

				$chk_file_size = str_replace(',', '', $chk_file_size);
				$new_file_name = $ab_idx . '_' . time() . '.' . $chk_file_ext;

				$old_file = $tmp_path . '/' . $chk_file_save;
				$new_file = $new_path1 . '/' . $new_file_name;

				if (file_exists($old_file))
				{
					if(!copy($old_file, $new_file))
					{
						$str = '{"success_chk" : "N", "error_string" : "저장시 오류가 생겼습니다. <br />다시 확인하고 파일을 올리세요."}';
						exit;
					}

					$file_param['img_fname'] = $chk_file_name;
					$file_param['img_sname'] = $new_file_name;
					$file_param['img_size']  = $chk_file_size;
					$file_param['img_type']  = $chk_file_type;
					$file_param['img_ext']   = $chk_file_ext;

					$query_str = make_sql($file_param, $file_command, $file_table, $file_conditions);
					db_query($query_str);
					query_history($query_str, $file_table, $file_command);

					unlink($old_file);
				}
			}
		}

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$ab_idx   = $_POST['idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];

		$command    = "update"; //명령어
		$table      = "agent_banner"; //테이블명
		$conditions = "ab_idx = '" . $ab_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'ab_idx', $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$ab_idx     = $_POST['idx'];
		$post_value = $_POST['post_value'];
		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx   = $_POST['code_part'];

		$command    = "update"; //명령어
		$table      = "agent_banner"; //테이블명
		$conditions = "ab_idx = '" . $ab_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($post_value == "Y") $param[$sub_action] = "N";
		else $param[$sub_action] = "Y";

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

		$table = "agent_banner"; //테이블명

		$ab_idx   = $_POST["idx"];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];

		$where = " and ab.ab_idx = '" . $ab_idx . "'";
		$data = agent_banner_data('view', $where);

		$sort_where = " and ab.comp_idx = '" . $comp_idx . "' and ab.part_idx = '" . $part_idx . "' and ab.sort < '" . $data["sort"] . "'";
		$sort_order = "ab.sort desc";
		$prev_data = agent_banner_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where ab_idx = '" . $ab_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where ab_idx = '" . $prev_data["ab_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'ab_idx', $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//아래 정렬 함수
	function sort_down()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "agent_banner"; //테이블명

		$ab_idx   = $_POST["idx"];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];

		$where = " and ab.ab_idx = '" . $ab_idx . "'";
		$data = agent_banner_data('view', $where);

		$sort_where = " and ab.comp_idx = '" . $comp_idx . "' and ab.part_idx = '" . $part_idx . "' and ab.sort > '" . $data["sort"] . "'";
		$sort_order = "ab.sort asc";
		$next_data = agent_banner_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where ab_idx = '" . $ab_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where ab_idx = '" . $next_data["ab_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'ab_idx', $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;
		global $comp_banner_path;

		$comp_idx = $_POST['comp_idx'];
		$ab_idx   = $_POST['idx'];

		$where = " and ab.ab_idx = '" . $ab_idx . "'";
		$data = agent_banner_data('view', $where);

		$command    = "update"; //명령어
		$table      = "agent_banner"; //테이블명
		$conditions = "ab_idx = '" . $ab_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		$param['img_fname'] = '';
		$param['img_sname'] = '';
		$param['img_size']  = '';
		$param['img_type']  = '';
		$param['img_ext']   = '';

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$delete_file = $comp_banner_path . '/' . $data['img_sname'];
		@unlink($delete_file);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
	}
?>