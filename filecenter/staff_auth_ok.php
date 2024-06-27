<?
/*
	생성 : 2013.02.04
	수정 : 2013.04.03
	위치 : 파일센터 > 권한설정 - 실행
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

// 폴더권한 함수
	function auth_menu()
	{
		global $_POST, $_SESSION, $sess_str;

		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx   = $_POST['code_part'];
		$mem_idx    = $_POST['mem_idx'];
		$fi_idx     = $_POST['idx'];
		$post_value = $_POST['post_value'];
		$sub_action = $_POST['sub_action'];
		$up_idx     = $_POST['up_idx'];

	// 해당상위값도 권한을 설정한다.
		$up_where = " and fi.fi_idx = '" . $fi_idx . "'";
		$up_data = filecenter_info_data('view', $up_where);

		$up_command    = "insert"; //명령어
		$up_table      = "filecenter_auth"; //테이블명
		$up_conditions = ""; //조건

		$up_param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$up_param['reg_date'] = time();

		$up_param['comp_idx'] = $comp_idx;
		$up_param['part_idx'] = $part_idx;
		$up_param['mem_idx']  = $mem_idx;

        if ($post_value == "1") {
            $up_param[$sub_action] = "0";
            $param[$sub_action] = "0";
            
            if ($sub_action == 'dir_view')
            {
                $up_param['dir_read'] = '0';
                $up_param['dir_write'] = '0';
                $up_param['dir_delete'] = '0';
                
                $param['dir_read'] = '0';
                $param['dir_write'] = '0';
                $param['dir_delete'] = '0';
            }
            else if ($sub_action == 'dir_read')
            {
                $up_param['dir_write'] = '0';
                $up_param['dir_delete'] = '0';
                
                $param['dir_write'] = '0';
                $param['dir_delete'] = '0';                        
            }
            else if ($sub_action == 'dir_write')
            {
                $up_param['dir_delete'] = '0';
                
                $param['dir_delete'] = '0';
            }
            
        }
        else
        {
            $up_param[$sub_action] = "1";
            $param[$sub_action] = "1";
            
            if ($sub_action == 'dir_delete')
            {
                $up_param['dir_write']  = "1";
                $up_param['dir_view']  = "1";
                $up_param['dir_read']  = "1";
                
                $param['dir_write']  = "1";
                $param['dir_view']  = "1";
                $param['dir_read']  = "1";
            }
            else if ($sub_action == 'dir_write')
            {
                $up_param['dir_view']  = "1";
                $up_param['dir_read']  = "1";
                
                $param['dir_view']  = "1";
                $param['dir_read']  = "1";
            }
            else if ($sub_action == 'dir_read')
            {
                $up_param['dir_view']  = "1";
                
                $param['dir_view']  = "1";
            }
        }

		$chk_up_idx = $up_data['up_fi_idx'];
		$chk_up_arr = explode(',', $chk_up_idx);
		foreach ($chk_up_arr as $chk_k => $chk_v)
		{
		// 권한 확인
			$chk_where = " and fa.del_yn=0 and fa.comp_idx = '" . $comp_idx . "' and fa.mem_idx = '" . $mem_idx . "' and fa.fi_idx = '" . $chk_v . "'";
			$chk_data = filecenter_auth_data('page', $chk_where);

		// 데이타가 없을 경우 등록
			if ($chk_data['total_num'] == 0)
			{
				$up_param['fi_idx'] = $chk_v;

				$query_str = make_sql($up_param, $up_command, $up_table, $up_conditions);
				db_query($query_str);
				query_history($query_str, $up_table, $up_command);
			}
		}

	// 선택한 폴더에 대한 권한
	// 권한 확인
		$chk_where = " and fa.del_yn=0 and fa.comp_idx = '" . $comp_idx . "' and fa.mem_idx = '" . $mem_idx . "' and fa.fi_idx = '" . $fi_idx . "'";
		$chk_data = filecenter_auth_data('page', $chk_where);

	// 데이타가 없을 경우 등록
		if ($chk_data['total_num'] == 0)
		{
			$command    = "insert"; //명령어
			$table      = "filecenter_auth"; //테이블명
			$conditions = ""; //조건

			$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['reg_date'] = time();

			$param['comp_idx'] = $comp_idx;
			$param['part_idx'] = $part_idx;
			$param['mem_idx']  = $mem_idx;
			$param['fi_idx']   = $fi_idx;
		}
		else
		{
			$command    = "update"; //명령어
			$table      = "filecenter_auth"; //테이블명
			$conditions = "comp_idx = '" . $comp_idx . "' and mem_idx = '" . $mem_idx . "' and fi_idx = '" . $fi_idx . "'"; //조건

			$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['mod_date'] = time();
			$param['part_idx'] = $part_idx;
		}

		$query_str = make_sql($param, $command, $table, $conditions);
		//echo $query_str;
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "idx" : "' . $mem_idx . '", "up_idx" : "' . $fi_idx . '"}';
		echo $str;
		exit;
	}


    function auth_entrust() {
        global $_POST, $_SESSION, $sess_str;
        //print_r($_POST);
        $comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
        $source_mem_idx = $_POST['source_mem_idx'];
        $target_mem_idx = $_POST['target_mem_idx'];
        $mem_idx    = $_SESSION[$sess_str . '_mem_idx'];
        
        $query_str = "
            update filecenter_auth f1
            join filecenter_auth f2 on f1.comp_idx=f2.comp_idx and f1.fi_idx=f2.fi_idx
            set f1.dir_view = (case f1.dir_view when 1 then f1.dir_view else f2.dir_view end)
            , f1.dir_read = (case f1.dir_read when 1 then f1.dir_read else f2.dir_read end)
            , f1.dir_write = (case f1.dir_write when 1 then f1.dir_write else f2.dir_write end)
            , f1.dir_delete = (case f1.dir_delete when 1 then f1.dir_delete else f2.dir_delete end)
            where f1.comp_idx=" . $comp_idx . "
            and f1.mem_idx in (" . $target_mem_idx . ")
            and f2.mem_idx=" . $source_mem_idx . "
            and f2.del_yn='N'
        ";
        //echo $query_str;
        //echo "<br>";
        db_query($query_str);
                
        $query_str = "
            insert into filecenter_auth 
            (comp_idx,part_idx,fi_idx,mem_idx,dir_view,dir_read,dir_write,dir_delete,reg_id,reg_date)
            select
              f.comp_idx,f.part_idx,f.fi_idx,m.mem_idx,f.dir_view,f.dir_read,f.dir_write,f.dir_delete,'" . $mem_idx . "', " . time() . "
            from filecenter_auth f
            join member_info m on f.comp_idx=m.comp_idx
            where f.comp_idx=" . $comp_idx . " 
            and m.mem_idx in (" . $target_mem_idx . ")
            and f.mem_idx=" . $source_mem_idx . "
            and f.del_yn=0
            and m.del_yn='N'
            and not exists(select '1' from filecenter_auth where fi_idx=f.fi_idx and mem_idx=m.mem_idx and del_yn=0)
            order by f.fi_idx asc
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