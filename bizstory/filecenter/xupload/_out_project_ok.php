<?
/*
	생성 : 2013.02.06
	수정 : 2013.02.20
	위치 : 파일센터 > 프로젝트 - 실행
*/
	include "../../common/setting.php";
	include "../../common/no_direct.php";
	include "../../common/member_chk.php";

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

// 폴더등록 함수
	function folder_post()
	{
		global $_POST, $_SESSION, $sess_str;

		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];
		$pro_idx  = $_POST['pro_idx'];
		$add_name = $_POST['add_name'];

	// 프로젝트내용
		$pro_where = " and pro.pro_idx = '" . $pro_idx . "'";
		$pro_data = project_info_data('view', $pro_where);

		$comp_idx     = $pro_data['comp_idx'];
		$part_idx     = $pro_data['part_idx'];
		$project_code = $pro_data['project_code'];

	// 상위폴더내용
		$dir_where = " and fi.comp_idx = '" . $comp_idx . "' and fi.part_idx = '" . $part_idx . "' and fi.dir_file = 'folder' and fi.dir_depth = '1' and fi.file_name = 'project'";
		$dir_data = filecenter_info_data('view', $dir_where);
		$first_up_idx = $dir_data['fi_idx'];
		$first_level  = $dir_data['dir_depth'];

	// 폴더가 있는지 확인
		$chk_where = " and fi.comp_idx = '" . $comp_idx . "' and fi.part_idx = '" . $part_idx . "' and fi.dir_file = 'folder' and fi.up_fi_idx = '" . $first_up_idx . "' and fi.file_name = '" . $project_code . "'";
		$chk_data = filecenter_info_data('view', $chk_where);

		if ($chk_data['total_num'] == 0) // 없을 경우 등록
		{
		// 등록
			$command    = "insert"; //명령어
			$table      = "filecenter_info"; //테이블명
			$conditions = ""; //조건

			$param['reg_id']    = $mem_idx;
			$param['reg_date']  = time();
			$param['comp_idx']  = $comp_idx;
			$param['part_idx']  = $part_idx;
			$param['dir_file']  = 'folder';
			$param['up_fi_idx'] = $dir_data['fi_idx'];
			$param['pro_idx']   = $pro_idx;
			$param['file_name'] = $project_code;
			$param['dir_depth'] = $dir_data['dir_depth'] + 1;
			$param['file_path'] = $dir_data['file_path'] . '/' . $dir_data['file_name'];
			$param['set_type']  = 'fix';

			$chk_data = query_view("select max(fi_idx) as fi_idx from " . $table);
			$param['fi_idx'] = ($chk_data['fi_idx'] == '') ? '1' : $chk_data['fi_idx'] + 1;

			$query_str = make_sql($param, $command, $table, $conditions);
			db_query($query_str);
			query_history($query_str, $table, $command);

		// 이력등록
			$hi_command    = "insert"; //명령어
			$hi_table      = "filecenter_history"; //테이블명
			$hi_conditions = ""; //조건

			$hi_param['reg_id']      = $mem_idx;
			$hi_param['reg_date']    = time();
			$hi_param['comp_idx']    = $comp_idx;
			$hi_param['part_idx']    = $part_idx;
			$hi_param['fi_idx']      = $param['fi_idx'];
			$hi_param['dir_file']    = $param['dir_file'];
			$hi_param['old_subject'] = '';
			$hi_param['new_subject'] = $param['file_name'];
			$hi_param['reg_type']    = 'insert';

			$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
			db_query($query_str);
			query_history($query_str, $hi_table, $hi_command);

			$file_path = $param['file_path'];
			$file_name = $param['file_name'];
			$up_idx    = $param['fi_idx'];
			$up_level  = $first_level;
		}
		else
		{
			$file_path = $chk_data['file_path'];
			$file_name = $chk_data['file_name'];
			$up_idx    = $chk_data['fi_idx'];
			$up_level  = $chk_data['dir_depth'];
		}

		$str = '{
	"success_chk" : "Y",
	"error_string":"",
	"file_path":"' . $file_path . '",
	"file_name":"' . $file_name . '",
	"add_name":"' . $add_name . '",
	"up_idx":"' . $up_idx . '",
	"up_level":"' . $up_level . '"
}';
		echo $str;
		exit;
	}
?>