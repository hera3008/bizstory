<?
/*
	생성 : 2012.05.24
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 컨텐츠관리 > 배너관리 > 에이전트배너 - 실행
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
		$chk_param['require'][] = array("field"=>"link_url", "msg"=>"링크주소");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

//등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param = $_POST["param"];

		$command    = "insert"; //명령어
		$table      = "banner_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date('Y-m-d H:i:s');

		if ($param["comp_all"] == "") $param["comp_all"] = "N";
		if ($param["view_yn"] == "") $param["view_yn"] = "Y";

		$data = query_view("select max(bi_idx) as bi_idx from " . $table);
		$param["bi_idx"] = ($data["bi_idx"] == "") ? "1" : $data["bi_idx"] + 1;

		$data = query_view("select max(sort) as max_sort from " . $table . " where del_yn = 'N' and banner_type = '" . $param['banner_type'] . "'");
		$param["sort"] = ($data["max_sort"] == "") ? "1" : $data["max_sort"] + 1;

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $banner_path;

		$bi_idx    = $param['bi_idx'];
		$data_path = $banner_path;
		files_dir($data_path);

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $bi_idx, 'agent_banner');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$param['img_fname' . $i] = $upfile_data[$i]['f_name'];
				$param['img_sname' . $i] = $upfile_data[$i]['s_name'];
				$param['img_size' . $i]  = $upfile_data[$i]['f_size'];
				$param['img_type' . $i]  = $upfile_data[$i]['f_type'];
				$param['img_ext' . $i]   = $upfile_data[$i]['f_ext'];
			}
		}

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and banner_type = '" . $param['banner_type'] . "'";
		data_sort_action($table, 'bi_idx', $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param  = $_POST["param"];
		$bi_idx = $_POST["bi_idx"];

		$command    = "update"; //명령어
		$table      = "banner_info"; //테이블명
		$conditions = "bi_idx = '" . $bi_idx . "'"; //조건

		$param["mod_id"]   = $_SESSION[$sess_str . "_mem_idx"];
		$param["mod_date"] = date("Y-m-d H:i:s");

		if ($param["comp_all"] == "") $param["comp_all"] = "N";
		if ($param["view_yn"] == "") $param["view_yn"] = "Y";

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $banner_path;

		$data_path = $banner_path;
		files_dir($data_path);

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $bi_idx, 'agent_banner');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$param['img_fname' . $i] = $upfile_data[$i]['f_name'];
				$param['img_sname' . $i] = $upfile_data[$i]['s_name'];
				$param['img_size' . $i]  = $upfile_data[$i]['f_size'];
				$param['img_type' . $i]  = $upfile_data[$i]['f_type'];
				$param['img_ext' . $i]   = $upfile_data[$i]['f_ext'];
			}
		}

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and banner_type = '" . $param['banner_type'] . "'";
		data_sort_action($table, 'bi_idx', $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$bi_idx      = $_POST['idx'];
		$banner_type = $_POST['banner_type'];

		$command    = "update"; //명령어
		$table      = "banner_info"; //테이블명
		$conditions = "bi_idx = '" . $bi_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and banner_type = '" . $banner_type . "'";
		data_sort_action($table, 'bi_idx', $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$bi_idx     = $_POST['idx'];
		$post_value = $_POST['post_value'];

		$command    = "update"; //명령어
		$table      = "banner_info"; //테이블명
		$conditions = "bi_idx = '" . $bi_idx . "'"; //조건

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

		$table = "banner_info"; //테이블명

		$bi_idx  = $_POST["idx"];
		$banner_type = $_POST['banner_type'];

		$where = " and bi.bi_idx = '" . $bi_idx . "'";
		$data = banner_info_data('view', $where);

		$sort_where = " and bi.banner_type = '" . $banner_type . "' and bi.sort < '" . $data["sort"] . "'";
		$sort_order = "bi.sort desc";
		$prev_data = banner_info_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where bi_idx = '" . $bi_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where bi_idx = '" . $prev_data["bi_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = " and banner_type = '" . $banner_type . "'";
		data_sort_action($table, 'bi_idx', $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//아래 정렬 함수
	function sort_down()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "banner_info"; //테이블명

		$bi_idx = $_POST["idx"];
		$banner_type = $_POST['banner_type'];

		$where = " and bi.bi_idx = '" . $bi_idx . "'";
		$data = banner_info_data('view', $where);

		$sort_where = " and bi.banner_type = '" . $banner_type . "' and bi.sort > '" . $data["sort"] . "'";
		$sort_order = "bi.sort asc";
		$next_data = banner_info_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where bi_idx = '" . $bi_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where bi_idx = '" . $next_data["bi_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = " and banner_type = '" . $banner_type . "'";
		data_sort_action($table, 'bi_idx', $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address, $banner_path;

		$bi_idx = $_POST['idx'];
		$sort   = $_POST['sort'];

		$command    = "update"; //명령어
		$table      = "banner_info"; //테이블명
		$conditions = "bi_idx = '" . $bi_idx . "'"; //조건

		$data = query_view("select * from " . $table . " where " . $conditions);

		$img_sname = $data['img_sname' . $sort];
		if ($img_sname != "") @unlink($banner_path . '/' . $img_sname);

		$param['img_fname' . $sort] = "";
		$param['img_sname' . $sort] = "";
		$param['img_size' . $sort]  = 0;
		$param['img_type' . $sort]  = "";
		$param['img_ext' . $sort]   = "";

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}
?>