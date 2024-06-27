<?
/*
	생성 : 2013.01.29
	수정 : 2013.04.01
	위치 : 파일센터 > 타입설정 - 실행
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
		$chk_param['require'][] = array("field"=>"code_name", "msg"=>"설정명");

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
		$table      = "filecenter_code_type"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = time();
		$param['comp_idx'] = $comp_idx;

		if ($param['view_yn'] == '') $param['view_yn'] = '1';

		$data = query_view("
			select max(sort) as max_sort
			from " . $table . "
			where del_yn = 0 and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'");
		$param["sort"] = ($data["max_sort"] == "") ? "1" : $data["max_sort"] + 1;

		$up_code_idxArr = "";
		for ($i = 1; $i < $param["menu_depth"]; $i++)
		{
			$up_code_idxArr .= "," . $param["menu" . $i];
			unset($param["menu" . $i]);
		}
		$param["up_code_idx"] = $up_code_idxArr;

		chk_before($param);
        
        //중복체크
        $query = "select * 
            from filecenter_code_type 
            where comp_idx='" . $comp_idx . "' and part_idx='" . $part_idx . "' 
            and up_code_idx='" . $param['up_code_idx'] . "' 
            and code_name = '" . $param['code_name'] . "'
            and del_yn=0";
        $chk_data = query_view($query);
        
        if ($chk_data['total_num'] == 0) {
                
            $query_str = make_sql($param, $command, $table, $conditions);
            db_query($query_str);
            query_history($query_str, $table, $command);
    
            $where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
            data_level_depth_action($table, "code_idx", "up_code_idx", $where);
            data_level_sort_action($table, "code_idx", "up_code_idx", $where);
            
            $return_arr['success_chk'] = "Y";
            $return_arr['error_string'] = "";
            
        } else {
            $return_arr['success_chk'] = "N";
            $return_arr['error_string'] = "중복된 데이터가 존재합니다.";
        }

		echo json_encode($return_arr);
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$code_idx = $_POST['code_idx'];
		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $param['part_idx'];

	// 수정
		$command    = "update"; //명령어
		$table      = "filecenter_code_type"; //테이블명
		$conditions = "code_idx = '" . $code_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = time();

		if ($param['view_yn'] == '') $param['view_yn'] = '0';

		$up_code_idxArr = "";
		for ($i = 1; $i < $param["menu_depth"]; $i++)
		{
			$up_code_idxArr .= "," . $param["menu" . $i];
			unset($param["menu" . $i]);
		}
		$param["up_code_idx"] = $up_code_idxArr;
        //중복체크
        $query = "select * 
            from filecenter_code_type 
            where comp_idx='" . $comp_idx . "' and part_idx='" . $part_idx . "' 
            and up_code_idx='" . $param['up_code_idx'] . "' 
            and code_name = '" . $param['code_name'] . "'
            and del_yn=0";
        $chk_data = query_view($query);

        if ($chk_data['total_num'] == 0) {
    	// 변경전의 데이타를 가지고 온다.
    		$where = " and code.code_idx = '" . $code_idx . "'";
    		$old_data = filecenter_code_type_data("view", $where);
    
    		chk_before($param);
    
    		$query_str = make_sql($param, $command, $table, $conditions);
    		db_query($query_str);
    		query_history($query_str, $table, $command);
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    	// 상위가 다를경우
    		if ($old_data["up_code_idx"] != $param["up_code_idx"])
    		{
    			$dif_depth = $old_data["menu_depth"] - $param["menu_depth"];
    
    		// 자기 하위것들 같이 움직이기
    			$old_up_code_idx = $old_data["up_code_idx"] . "," . $old_data["code_idx"];
    			$new_up_code_idx = $param["up_code_idx"] . "," . $code_idx;
    
    			$where = " and concat(code.up_code_idx, ',') like '%" . $old_up_code_idx . ",%'";
    			$down_list = filecenter_code_type_data("list", $where, "", "", "");
    			foreach ($down_list as $k => $down_data)
    			{
    				if (is_array($down_data))
    				{
    					$down_conditions = "code_idx = '" . $down_data["code_idx"] . "'";
    
    					$down_param["menu_depth"]  = $down_data["menu_depth"] - $dif_depth;
    					$down_param["up_code_idx"] = $new_up_code_idx;
    					$down_param["up_code_idx"] = str_replace($old_up_code_idx, $new_up_code_idx, $down_data["up_code_idx"]);
    
    					$query_str = make_sql($down_param, $command, $table, $down_conditions);
    					db_query($query_str);
    					query_history($query_str, $table, $command);
    				}
    			}
    		}
    
    		chk_before($param);
    
    		$query_str = make_sql($param, $command, $table, $conditions);
    		db_query($query_str);
    		query_history($query_str, $table, $command);
    
    		$where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
    		data_level_depth_action($table, "code_idx", "up_code_idx", $where);
    		data_level_sort_action($table, "code_idx", "up_code_idx", $where);
                
            $return_arr['success_chk'] = "Y";
            $return_arr['error_string'] = "";
            
        } else {
            $return_arr['success_chk'] = "N";
            $return_arr['error_string'] = "중복된 데이터가 존재합니다.";
        }

        echo json_encode($return_arr);
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$code_idx = $_POST['idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];

		$command    = "update"; //명령어
		$table      = "filecenter_code_type"; //테이블명
		$conditions = "code_idx = '" . $code_idx . "'"; //조건

		$param['del_yn']   = "1";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = time();

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_level_depth_action($table, "code_idx", "up_code_idx", $where);
		data_level_sort_action($table, "code_idx", "up_code_idx", $where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$code_idx   = $_POST['idx'];
		$post_value = $_POST['post_value'];
		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx   = $_POST['code_part'];

		$command    = "update"; //명령어
		$table      = "filecenter_code_type"; //테이블명
		$conditions = "code_idx = '" . $code_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = time();

		if ($post_value == "1") $param[$sub_action] = "0";
		else $param[$sub_action] = "1";

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":"", "idx":"' . $code_idx . '"}';
		echo $str;
		exit;
	}

// 폴더권한 함수
	function auth_dir()
	{
		global $_POST, $_SESSION, $sess_str;

		$comp_idx   = $_POST['comp_idx'];
		$part_idx   = $_POST['part_idx'];
		$code_idx   = $_POST['code_idx'];
		$mem_idx    = $_POST['idx'];
		$post_value = $_POST['post_value'];
		$sub_action = $_POST['sub_action'];

        
        if ($post_value == "1") {
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
                $param['dir_write'] = '0';
                $param['dir_delete'] = '0';                        
            }
            else if ($sub_action == 'dir_write')
            {
                $param['dir_delete'] = '0';
            }
            
        }
        else
        {
            $param[$sub_action] = "1";
            
            if ($sub_action == 'dir_delete')
            {
                $param['dir_write']  = "1";
                $param['dir_view']  = "1";
                $param['dir_read']  = "1";
            }
            else if ($sub_action == 'dir_write')
            {
                $param['dir_view']  = "1";
                $param['dir_read']  = "1";
            }
            else if ($sub_action == 'dir_read')
            {
                $param['dir_view']  = "1";
            }
        }


    // 데이타 확인
        $where = " and codea.comp_idx = '" . $comp_idx . "' and codea.mem_idx = '" . $mem_idx . "' and codea.code_idx = '" . $code_idx . "'";
        $data = filecenter_code_type_auth_data('page', $where);
        
	// 데이타가 없을 경우 등록
		if ($data['total_num'] == 0)
		{
			$command    = "insert"; //명령어
			$table      = "filecenter_code_type_auth"; //테이블명
			$conditions = ""; //조건

			$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['reg_date'] = time();

			$param['comp_idx'] = $comp_idx;
			$param['part_idx'] = $part_idx;
			$param['mem_idx']  = $mem_idx;
			$param['code_idx'] = $code_idx;
		}
		else
		{
			$command    = "update"; //명령어
			$table      = "filecenter_code_type_auth"; //테이블명
			$conditions = "comp_idx = '" . $comp_idx . "' and mem_idx = '" . $mem_idx . "' and code_idx = '" . $code_idx . "'"; //조건

			$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['mod_date'] = time();
			$param['part_idx'] = $part_idx;
		}

		$query_str = make_sql($param, $command, $table, $conditions);
		/*
		print_r($query_str);
		exit;
		*/
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "idx" : "' . $code_idx . '"}';
		echo $str;
		exit;
	}

    function auth_entrust() {
        global $_POST, $_SESSION, $sess_str;
        
        $post_value = $_POST['post_value'];
        $comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
        $source_mem_idx = $_POST['source_mem_idx'];
        $target_mem_idx = $_POST['target_mem_idx'];
        $mem_idx    = $_SESSION[$sess_str . '_mem_idx'];
        
        $query_str = "
            update filecenter_code_type_auth f1
            join filecenter_code_type_auth f2 on f1.comp_idx=f2.comp_idx and f1.code_idx=f2.code_idx
            set f1.dir_view = (case f1.dir_view when 1 then f1.dir_view else ifnull(f2.dir_view, 0) end)
            , f1.dir_read = (case f1.dir_read when 1 then f1.dir_read else ifnull(f2.dir_read, 0) end)
            , f1.dir_write = (case f1.dir_write when 1 then f1.dir_write else ifnull(f2.dir_write, 0) end)
            , f1.dir_delete = (case f1.dir_delete when 1 then f1.dir_delete else ifnull(f2.dir_delete, 0) end)
            where f1.comp_idx=" . $comp_idx . "
            and f1.mem_idx in (" . $target_mem_idx . ")
            and f2.mem_idx=" . $source_mem_idx . "
            and f1.del_yn=0
            and f2.del_yn=0
        ";
        //echo $query_str;
        //echo "<br>";
        db_query($query_str);
                
        $query_str = "
            insert into filecenter_code_type_auth 
            (comp_idx, part_idx, code_idx, mem_idx, dir_view, dir_read, dir_write, dir_delete,reg_id,reg_date)
            select
              f.comp_idx,f.part_idx,f.code_idx,m.mem_idx,f.dir_view,f.dir_read,f.dir_write,f.dir_delete,'" . $mem_idx . "', " . time() . "
            from filecenter_code_type_auth f
            join member_info m on f.comp_idx=m.comp_idx
            where f.comp_idx=" . $comp_idx . " 
            and m.mem_idx in (" . $target_mem_idx . ")
            and f.mem_idx=" . $source_mem_idx . "
            and f.del_yn=0
            and m.del_yn='N'
            and not exists(select '1' from filecenter_code_type_auth where code_idx=f.code_idx and mem_idx=m.mem_idx and del_yn=0)
            order by f.codea_idx asc
        ";
        //echo $query_str;
        //echo "<BR>";
        //exit;
        db_query($query_str);
                
        $query_str = "
            update filecenter_auth f1
            join filecenter_auth f2 on f1.comp_idx=f2.comp_idx and f1.fi_idx=f2.fi_idx
            set f1.dir_view = (case f1.dir_view when 1 then f1.dir_view else ifnull(f2.dir_view, 0) end)
            , f1.dir_read = (case f1.dir_read when 1 then f1.dir_read else ifnull(f2.dir_read, 0) end)
            , f1.dir_write = (case f1.dir_write when 1 then f1.dir_write else ifnull(f2.dir_write, 0) end)
            , f1.dir_delete = (case f1.dir_delete when 1 then f1.dir_delete else ifnull(f2.dir_delete, 0) end)
            where f1.comp_idx=" . $comp_idx . "
            and f1.mem_idx in (" . $target_mem_idx . ")
            and f2.mem_idx=" . $source_mem_idx . "
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