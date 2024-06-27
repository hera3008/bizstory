<?
	include "../../bizstory/common/setting.php";
	include $local_path . "/cms/include/client_chk.php";
	include $local_path . "/cms/include/no_direct.php";

	$set_where = " and bs.bs_idx = '" . $bs_idx . "'";
	$set_board = pro_board_set_data("view", $set_where);
	$set_board['name_db'] = 'pro_board_biz_' . $set_board['comp_idx'];

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
		$chk_param['require'][] = array("field"=>"writer", "msg"=>"작성자");
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"제목");
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

//입력처리 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;
		global $set_board, $ip_address;

		$param    = $_POST["param"];
		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$ci_idx   = $_POST['ci_idx'];

		$command    = "insert"; //명령어
		$table      = $set_board["name_db"]; //테이블명
		$conditions = ""; //조건

		$param["reg_id"]   = $_SESSION[$sess_str . "_mem_idx"];
		$param["reg_date"] = date("Y-m-d H:i:s");

		$param["comp_idx"] = $_POST["comp_idx"];
		$param["part_idx"] = $_POST["part_idx"];
		$param["ci_idx"]   = $_POST["ci_idx"];
		$param["bs_idx"]   = $_POST["bs_idx"];
		$param["mem_idx"]  = $_SESSION[$sess_str . "_mem_idx"];
		$param["ip_addr"]  = $ip_address;

	// 가장 큰수 구하기(일련번호)
		$data = query_view("select max(b_idx) as b_idx from " . $table);
		$param["b_idx"] = ($data["b_idx"] == "") ? "1" : $data["b_idx"] + 1;

		if ($param["writer"]    == "") $param["writer"]    = $_SESSION[$sess_str . "_mem_name"];
		if ($param["secret_yn"] == "") $param["secret_yn"] = "N";

		chk_before($param);

		db_query(make_sql($param, $command, $table, $conditions));
		pro_board_link_insert($set_board, $param["bs_idx"], $param["b_idx"]);

	// 일반글일 경우
		if ($param["gno"] == "")
		{
			$param["gno"] = $param["b_idx"];

			$data = query_view("select order_idx from " . $table . " order by order_idx desc limit 0, 1");

			if ($data["total_num"] == 0) $order_idx = 1;
			else
			{
				$order_idx     = $data["order_idx"] + 1;
				$param["tgno"] = 0;
			}
		}
	// 답변글일 경우
		else
		{
			$data = query_view("select tgno, order_idx from " . $table . " where b_idx ='" . $param["gno"] . "'");

			$order_idx = $data["order_idx"];

			db_query("
				update " . $table . " set
					order_idx = order_idx + 1
				where
					order_idx >='" . $order_idx . "'
			");

			$param["tgno"] = $data["tgno"] + 1;
		}
		$re_sql = "
			update " . $table . " SET
				gno       = '" . $param["gno"] . "',
				tgno      = '" . $param["tgno"] . "',
				order_idx = '" . $order_idx . "'
			where
				b_idx = '" . $param["b_idx"] . "'
		";
		db_query($re_sql);

	/*********************************************************************************************/
	// 파일저장
		global $tmp_path, $comp_pro_board_path;

		$bs_idx   = $param['bs_idx'];
		$b_idx    = $param['b_idx'];
		$pro_path1 = $comp_pro_board_path . '/' . $bs_idx;
		$pro_path  = $pro_path1 . '/' . $b_idx;
		files_dir($pro_path1);
		files_dir($pro_path);

		$file_command    = "insert"; //명령어
		$file_table      = "pro_board_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['reg_id'];
		$reg_date = $param['reg_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $pro_path, $_POST, $b_idx, 'pro_board');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and bf.bs_idx = '" . $bs_idx . "' and bf.b_idx = '" . $b_idx . "' and bf.sort ='" . $i . "'";
				$file_data = pro_board_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, ci_idx, bs_idx, b_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($ci_idx) . "', '" . string_input($bs_idx) . "', '" . string_input($b_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
					$file_chk++;
				}
				else
				{
					$query_update = "update " . $file_table . " set
							img_fname = '" . string_input($chk_file_name) . "',
							img_sname = '" . string_input($new_file_name) . "',
							img_size  = '" . string_input($chk_file_size) . "',
							img_type  = '" . string_input($chk_file_type) . "',
							img_ext   = '" . string_input($chk_file_ext) . "',
							mod_id    = '" . string_input($reg_id) . "',
							mod_date  = '" . string_input($reg_date) . "'
						where
							del_yn = 'N' and bs_idx = '" . $bs_idx . "' and b_idx = '" . $b_idx . "' and sort ='" . $i . "'";
					db_query($query_update);
				}
			}
		}
		if ($query_str != '')
		{
			db_query($query_str);
			query_history($query_str, $file_table, $file_command);
		}

	/*********************************************************************************************/
	// 공지글일 경우
		$notice_yn = $_POST["notice_yn"];
		if ($notice_yn == "") $notice_yn = "N"; // 공지글
		if ($notice_yn == "Y")
		{
			$data = query_view("select max(sort) as notice_sort from pro_board_notice where bs_idx = '" . $bs_idx . "' and del_yn = 'N'");
			$notice_sort = ($data["notice_sort"] == "") ? "1" : $data["notice_sort"] + 1;

			$notice_sql = "
				insert into pro_board_notice set
					comp_idx = '" . $comp_idx . "',
					part_idx = '" . $part_idx . "',
					ci_idx   = '" . $ci_idx . "',
					bs_idx   = '" . $param["bs_idx"] . "',
					b_idx    = '" . $param["b_idx"] . "',
					sort     = '" . $notice_sort . "',

					del_yn   = 'N',
					reg_id   = '" . $param["reg_id"] . "',
					reg_date = '" . $param["reg_date"] . "'
			";
			db_query($notice_sql);
		}

		$str = '{"success_chk" : "Y", "idx_num" : "' . $param['b_idx'] . '"}';
		echo $str;
		exit;
	}

	//수정처리 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;
		global $set_board, $ip_address;

		$param    = $_POST["param"];
		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$ci_idx   = $_POST['ci_idx'];
		$bs_idx   = $_POST['bs_idx'];
		$b_idx    = $_POST['b_idx'];

		$command    = "update"; //명령어
		$table      = $set_board["name_db"]; //테이블명
		$conditions = "b_idx = '" . $b_idx . "'"; //조건

		$param["mod_id"]   = $_SESSION[$sess_str . "_mem_idx"];
		$param["mod_date"] = date("Y-m-d H:i:s");

		$param["mem_idx"]  = $_SESSION[$sess_str . "_mem_idx"];
		$param["ip_addr"]  = $ip_address;

		if ($param["writer"]    == "") $param["writer"]    = $_SESSION[$sess_str . "_mem_name"];
		if ($param["secret_yn"] == "") $param["secret_yn"] = "N";

		chk_before($param);

		db_query(make_sql($param, $command, $table, $conditions));
		pro_board_link_insert($set_board, $bs_idx, $b_idx);

	/*********************************************************************************************/
	// 파일저장
		global $tmp_path, $comp_pro_board_path;

		$pro_path1 = $comp_pro_board_path . '/' . $bs_idx;
		$pro_path  = $pro_path1 . '/' . $b_idx;
		files_dir($pro_path1);
		files_dir($pro_path);

		$file_command    = "insert"; //명령어
		$file_table      = "pro_board_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['mod_id'];
		$reg_date = $param['mod_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $pro_path, $_POST, $b_idx, 'pro_board');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and bf.bs_idx = '" . $bs_idx . "' and bf.b_idx = '" . $b_idx . "' and bf.sort ='" . $i . "'";
				$file_data = pro_board_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, ci_idx, bs_idx, b_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($ci_idx) . "', '" . string_input($bs_idx) . "', '" . string_input($b_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
					$file_chk++;
				}
				else
				{
					$query_update = "update " . $file_table . " set
							img_fname = '" . string_input($chk_file_name) . "',
							img_sname = '" . string_input($new_file_name) . "',
							img_size  = '" . string_input($chk_file_size) . "',
							img_type  = '" . string_input($chk_file_type) . "',
							img_ext   = '" . string_input($chk_file_ext) . "',
							mod_id    = '" . string_input($reg_id) . "',
							mod_date  = '" . string_input($reg_date) . "'
						where
							del_yn = 'N' and bs_idx = '" . $bs_idx . "' and b_idx = '" . $b_idx . "' and sort ='" . $i . "'";
					db_query($query_update);
				}
			}
		}
		if ($query_str != '')
		{
			db_query($query_str);
			query_history($query_str, $file_table, $file_command);
		}
		
	/*********************************************************************************************/
		// 공지글일 경우
		// 수정시 notice_yn, notice_all이 N일 경우 삭제할것.
		// Y일 경우 기존거랑 다르면 등록, 같으면 통과

		$notice_yn     = $_POST["notice_yn"];
		$old_notice_yn = $_POST["old_notice_yn"];
		$bn_idx        = $_POST["bn_idx"];

		if ($notice_yn     == "") $notice_yn     = "N";
		if ($old_notice_yn == "") $old_notice_yn = "N";

	// 기존글 확인
		$notice_where = " and bn.bs_idx = '" . $bs_idx . "' and bn.b_idx = '" . $b_idx . "'";
		$notice_data = pro_board_notice_data("view", $set_board["name_db"], $notice_where);

		if ($notice_data["total_num"] > 0)
		{
			if ($notice_yn == "N")
			{
				$notice_sql = "
					update bbs_notice SET
						del_yn   = 'Y',
						del_ip   = '" . $ip_address . "',
						del_id   = '" . $param["mod_id"] . "',
						del_date = '" . $param["mod_date"] . "'
					where
						bn_idx = '" . $bn_idx . "'
				";
				db_query($notice_sql);
			}
		}
		else
		{
			if (($notice_yn == "Y" && $old_notice_yn == "N"))
			{
				$data = query_view("select max(sort) as notice_sort from pro_board_notice where del_yn = 'N' and bs_idx = '" . $bs_idx . "'");
				$param["notice_sort"] = ($data["notice_sort"] == "") ? '1' : $data["notice_sort"] + 1;

				$notice_sql = "
					insert into bbs_notice SET
						comp_idx = '" . $comp_idx . "',
						part_idx = '" . $part_idx . "',
						ci_idx   = '" . $ci_idx . "',
						bs_idx   = '" . $bs_idx . "',
						b_idx    = '" . $b_idx . "',
						sort     = '" . $param["notice_sort"] . "',

						del_yn   = 'N',
						reg_id   = '" . $param["mod_id"] . "',
						reg_date = '" . $param["mod_date"] . "'
				";
				db_query($notice_sql);
			}
		}

		$str = '{"success_chk" : "Y", "idx_num" : "' . $wi_idx . '"}';
		echo $str;
		exit;
	}

// 삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str;
		global $set_board, $ip_address;

		$param = $_POST["param"];
		$b_idx = $_POST["b_idx"];

		$command    = "update"; //명령어
		$table      = $set_board["name_db"]; //테이블명
		$conditions = "b_idx = '" . $b_idx . "'"; //조건
		
		$param["del_yn"]   = "Y";
		$param['del_ip']   = $ip_address;
		$param["del_id"]   = $_SESSION[$sess_str . "_mem_idx"];
		$param["del_date"] = date("Y-m-d H:i:s");

		db_query(make_sql($param, $command, $table, $conditions));

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str;
		global $set_board, $ip_address;

		$bf_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "pro_board_file"; //테이블명
		$conditions = "bf_idx = '" . $bf_idx . "'"; //조건

		$data = query_view("select * from " . $table . " where " . $conditions);

		$img_sname = $data["img_sname"];
		if ($img_sname != "") @unlink($set_board['bbs_path'] . '/' . $data['b_idx'] . '/' . $img_sname);
		
		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}

//선택삭제
	function select_delete()
	{
		global $_POST, $_SESSION, $sess_str;
		global $set_board, $ip_address;

		$chk_b_idx = $_REQUEST["chk_b_idx"];
		$param     = $_REQUEST["param"];

		$command = "update"; //명령어
		$table   = $set_board["name_db"]; //테이블명

		$param["del_yn"]   = "Y";
		$param['del_ip']   = $ip_address;
		$param["del_id"]   = $_SESSION[$sess_str . "_mem_idx"];
		$param["del_date"] = date("Y-m-d H:i:s");

		foreach ($chk_b_idx as $k => $b_idx)
		{
			if($b_idx != "")
			{
				$conditions = "b_idx='" . $b_idx . "'"; //조건
				db_query(make_sql($param, $command, $table, $conditions));
			}
		}

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}
?>