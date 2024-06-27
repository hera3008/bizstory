<?
/*
	생성 : 2013.01.29
	수정 : 2013.04.02
	위치 : 파일센터 > 파일관리 - 실행
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
		$chk_param['require'][] = array("field"=>"file_name", "msg"=>"명");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 폴더수정 함수
	function folder_modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

		$fi_idx = $_POST['fi_idx'];
		$param  = $_POST['param'];

	// 값확인
		if ($param['file_name'] == '')
		{
			echo '{"success_chk" : "N", "error_string" : "폴더명을 입력하세요."}';
			exit;
		}

	// 변경전 파일
		$old_where = " and fi.fi_idx = '" . $fi_idx . "'";
		$old_data = filecenter_info_data('view', $old_where);
		$old_file_name = $old_data['file_name'];
		$old_dir_depth = $old_data['dir_depth'];

	// 중복확인
		$chk_where = "
			and fi.comp_idx = '" . $comp_idx . "' and fi.part_idx = '" . $part_idx . "'
			and fi.dir_file = 'folder'
			and fi.up_fi_idx = '" . $old_data['up_fi_idx'] . "' and fi.file_name = '" . $param['file_name'] . "'";
		$chk_data = filecenter_info_data('page', $chk_where);
		if ($chk_data['total_num'] > 0)
		{
			echo '{"success_chk" : "N", "error_string" : "중복된 폴더명입니다.<br />다시 입력하세요."}';
			exit;
		}

	// 수정
		$command    = "update"; //명령어
		$table      = "filecenter_info"; //테이블명
		$conditions = "fi_idx = '" . $fi_idx . "'"; //조건

		$param['mod_id']   = $mem_idx;
		$param['mod_date'] = time();

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 관련폴더들 다 변경할 것
		$chk_where = "
			and fi.comp_idx = '" . $comp_idx . "' and fi.part_idx = '" . $part_idx . "'
			and concat(',', fi.up_fi_idx, ',') like '%," . $fi_idx . ",%'";
		$chk_order = "fi.dir_file desc, fi.file_path asc, fi.file_name asc";
		$chk_list = filecenter_info_data('list', $chk_where, $chk_order, '', '');
		if ($chk_list['total_num'] > 0)
		{
			foreach ($chk_list as $chk_k => $chk_data)
			{
				if (is_array($chk_data))
				{
					$old_file_path = $chk_data['file_path'];
					$old_file_path_arr = explode('/', $old_file_path);
					$new_file_path = '';
					foreach ($old_file_path_arr as $file_k => $file_v)
					{
						if ($file_k > 0)
						{
							if ($file_k == $old_dir_depth)
							{
								$new_file_path .= '/' . $param['file_name'];
							}
							else
							{
								$new_file_path .= '/' . $file_v;
							}
						}
					}
					$dir_update = "
						update " . $table . " set
							file_path = '" . $new_file_path . "',
							mod_id    = '" . $param['mod_id'] . "',
							mod_date  = '" . $param['mod_date'] . "'
						where fi_idx = '" . $chk_data['fi_idx'] . "'";
					db_query($dir_update);
					query_history($dir_update, $table, 'update');
				}
			}
		}

	// 이력등록
		$hi_command    = "insert"; //명령어
		$hi_table      = "filecenter_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['comp_idx']     = $comp_idx;
		$hi_param['part_idx']     = $part_idx;
		$hi_param['fi_idx']       = $fi_idx;
		$hi_param['dir_file']     = 'folder';
		$hi_param['old_subject']  = $old_file_name;
		$hi_param['new_subject']  = $param['file_name'];
		$hi_param['reg_type']     = 'update';
		$hi_param['history_memo'] = '폴더명을 ' . $old_file_name . ' 에서 ' . $param['file_name'] . ' 으로 수정했습니다.';
		$hi_param['reg_id']       = $mem_idx;
		$hi_param['reg_date']     = time();

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//------------------------------------------------------------------------------------------------------------------------------------------------ 파일관련
// 파일수정 함수
	function file_modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

		$fi_idx  = $_POST['fi_idx'];
		$param   = $_POST['param'];
		$ex_name = $_POST['file_ex_name'];

		$param['file_name'] = str_replace(' ', '_', $param['file_name']);
		$param['file_name'] = $param['file_name'] . '.' . $ex_name;

	// 수정
		$command    = "update"; //명령어
		$table      = "filecenter_info"; //테이블명
		$conditions = "fi_idx = '" . $fi_idx . "'"; //조건

		$param['mod_id']   = $mem_idx;
		$param['mod_date'] = time();

	// 변경전 파일
		$old_where = " and fi.fi_idx = '" . $fi_idx . "'";
		$old_data = filecenter_info_data('view', $old_where);
		$old_file_name = $old_data['file_name'];

	// 중복확인
		$chk_where = "
			and fi.comp_idx = '" . $comp_idx . "' and fi.part_idx = '" . $part_idx . "'
			and fi.dir_file = 'file'
			and fi.up_fi_idx = '" . $old_data['up_fi_idx'] . "' and fi.file_name = '" . $param['file_name'] . "'";
		$chk_data = filecenter_info_data('page', $chk_where);
		if ($chk_data['total_num'] > 0)
		{
			echo '{"success_chk" : "N", "error_string" : "중복된 파일명입니다.<br />다시 입력하세요."}';
			exit;
		}

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 이력등록
		$hi_command    = "insert"; //명령어
		$hi_table      = "filecenter_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']       = $mem_idx;
		$hi_param['reg_date']     = time();
		$hi_param['comp_idx']     = $comp_idx;
		$hi_param['part_idx']     = $part_idx;
		$hi_param['fi_idx']       = $fi_idx;
		$hi_param['dir_file']     = 'file';
		$hi_param['old_subject']  = $old_file_name;
		$hi_param['new_subject']  = $param['file_name'];
		$hi_param['reg_type']     = 'update';
		$hi_param['history_memo'] = '파일명을 ' . $old_file_name . ' 에서 ' . $param['file_name'] . ' 으로 수정했습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 이력의 비고 수정
	function history_modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$mem_idx = $_SESSION[$sess_str . '_mem_idx'];
		$fh_idx  = $_POST['fh_idx'];
		$param   = $_POST['param'];

		$command    = "update"; //명령어
		$table      = "filecenter_history"; //테이블명
		$conditions = "fh_idx = '" . $fh_idx . "'"; //조건

		$param['mod_id']   = $mem_idx;
		$param['mod_date'] = time();

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 이력의 비고 삭제
	function history_delete()
	{
		global $_POST, $_SESSION, $sess_str;

		$mem_idx = $_SESSION[$sess_str . '_mem_idx'];
		$fh_idx  = $_POST['fh_idx'];

		$command    = "update"; //명령어
		$table      = "filecenter_history"; //테이블명
		$conditions = "fh_idx = '" . $fh_idx . "'"; //조건

		$param['contents'] = '';
		$param['mod_id']   = $mem_idx;
		$param['mod_date'] = time();

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
    
    function auth_empowerment()
    {
        global $_POST, $_SESSION, $sess_str;
        
        $comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
        $mem_idx = $_SESSION[$sess_str . '_mem_idx'];
        $target_mem_idx = $_POST['target_mem_idx'];
        $fi_idx  = $_POST['fi_idx'];
        $dir_view = $_POST['dir_view'];
        $dir_read = $_POST['dir_read'];
        $dir_write = $_POST['dir_write'];
        $dir_delete = $_POST['dir_delete'];
        
        $query_str = "
            update filecenter_auth f1
            set f1.dir_view = '" . $dir_view . "'
            , f1.dir_read = '" . $dir_read . "'
            , f1.dir_write = '" . $dir_write . "'
            , f1.dir_delete = '" . $dir_delete . "'
            where f1.comp_idx=" . $comp_idx . "
            and f1.fi_idx=" . $fi_idx . " 
            and f1.mem_idx in (" . $target_mem_idx . ")
            and f1.del_yn='N'
        ";
        //echo $query_str;
        //echo "<br>";
        db_query($query_str);
        $query_str = "
            update filecenter_auth fa
            join filecenter_info f1 on fa.comp_idx=f1.comp_idx and fa.fi_idx=f1.fi_idx
            join filecenter_info f2 on f1.comp_idx=f2.comp_idx and f1.up_fi_idx like concat(f2.up_fi_idx, ',', f2.fi_idx, '%')
            set fa.dir_view = '" . $dir_view . "'
            , fa.dir_read = '" . $dir_read . "'
            , fa.dir_write = '" . $dir_write . "'
            , fa.dir_delete = '" . $dir_delete . "'
            where f2.comp_idx=" . $comp_idx . "
            and f2.fi_idx=" . $fi_idx . " 
            and fa.mem_idx in (" . $target_mem_idx . ")
            and fa.del_yn=0
            and f1.del_yn=0
            and f2.del_yn=0
        ";
        //echo $query_str;
        //echo "<br>";
        db_query($query_str);
        
        $query_str = "
            insert into filecenter_auth 
            (comp_idx,part_idx,fi_idx,mem_idx,dir_view,dir_read,dir_write,dir_delete,reg_id,reg_date)
            select
              m.comp_idx,m.part_idx, '" . $fi_idx . "',m.mem_idx,'" . $dir_view . "','" . $dir_read . "','" . $dir_write . "','" . $dir_delete . "','" . $mem_idx . "', " . time() . "
            from member_info m
            where m.comp_idx=" . $comp_idx . " 
            and m.mem_idx in (" . $target_mem_idx . ")
            and m.del_yn='N'
            and not exists(select '1' from filecenter_auth where fi_idx='" . $fi_idx . "' and mem_idx=m.mem_idx and del_yn=0)
            order by m.mem_idx asc
        ";
        //echo $query_str;
        //echo "<BR>";
        //exit;
        db_query($query_str);

        $query_str = "
            insert into filecenter_auth 
            (comp_idx,part_idx,fi_idx,mem_idx,dir_view,dir_read,dir_write,dir_delete,reg_id,reg_date)
            select
              m.comp_idx,m.part_idx, f1.fi_idx,m.mem_idx,'" . $dir_view . "','" . $dir_read . "','" . $dir_write . "','" . $dir_delete . "','" . $mem_idx . "', " . time() . "
            from filecenter_info f1
            join filecenter_info f2 on f1.comp_idx=f2.comp_idx and f1.up_fi_idx like concat(f2.up_fi_idx, ',', f2.fi_idx, '%')
            join member_info m on f1.comp_idx=m.comp_idx
            where m.comp_idx=" . $comp_idx . " 
            and m.mem_idx in (" . $target_mem_idx . ")
            and m.del_yn='N'
            and f2.fi_idx=" . $fi_idx . " 
            and not exists(select '1' from filecenter_auth where fi_idx=f1.fi_idx and mem_idx=m.mem_idx and del_yn=0)
            and f1.del_yn=0
            and f2.del_yn=0
            order by f1.fi_idx asc
        ";
        //echo $query_str;
        //echo "<BR>";
        //exit;
        db_query($query_str);


        $str = '{"success_chk" : "Y", "idx" : "' . $code_idx . '"}';
        echo $str;
        exit;
    }

?>