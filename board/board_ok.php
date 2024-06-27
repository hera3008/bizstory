<?
/*
	생성 : 2012.06.07
	수정 : 2012.09.10
	위치 : 게시판폴더
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
		$chk_param['require'][] = array("field"=>"bc_idx", "msg"=>"말머리");
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"제목");
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

//입력처리 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$param      = $_POST['param'];
		$table_name = $_POST['set_table_name'];

		$bs_idx   = $_POST['bs_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

		$command    = "insert"; //명령어
		$table      = $table_name; //테이블명
		$conditions = ""; //조건

		$param["reg_id"]   = $mem_idx;
		$param["reg_date"] = date("Y-m-d H:i:s"); // 실제등록일

		$param["bs_idx"]   = $bs_idx;
		$param["comp_idx"] = $comp_idx;
		$param["part_idx"] = $part_idx;
		$param["mem_idx"]  = $mem_idx;
		$param["ip_addr"]  = $ip_address;

	// 가장 큰수 구하기(일련번호)
		$data = query_view("select max(b_idx) as b_idx from " . $table);
		$param["b_idx"] = ($data["b_idx"] == "") ? "1" : $data["b_idx"] + 1;

		if ($param["writer"]    == "") $param["writer"]    = $_SESSION[$sess_str . "_mem_name"];
		if ($param["secret_yn"] == "") $param["secret_yn"] = "N"; // 비밀글

		chk_before($param);

		db_query(make_sql($param, $command, $table, $conditions)); // 게시물 등록
		board_link_insert($bs_idx, $param['b_idx']); // 링크등록

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

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 공지글일 경우
		$notice_yn = $_POST["notice_yn"];
		if ($notice_yn == "") $notice_yn = "N"; // 공지글
		if ($notice_yn == "Y")
		{
			$data = query_view("select max(sort) as notice_sort from board_notice where bs_idx = '" . $bs_idx . "' and del_yn = 'N'");
			$notice_sort = ($data["notice_sort"] == "") ? "1" : $data["notice_sort"] + 1;

			$notice_sql = "
				insert into board_notice set
					comp_idx = '" . $param["comp_idx"] . "',
					part_idx = '" . $param["part_idx"] . "',
					bs_idx   = '" . $param["bs_idx"] . "',
					b_idx    = '" . $param["b_idx"] . "',
					sort     = '" . $notice_sort . "',

					reg_id   = '" . $param["reg_id"] . "',
					reg_date = '" . $param["reg_date"] . "'
			";
			db_query($notice_sql);
		}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_board_path;

		$b_idx = $param['b_idx'];
		$data_path1 = $comp_board_path . '/' . $bs_idx;
		files_dir($data_path1);

		$data_path = $data_path1 . '/' . $b_idx;
		files_dir($data_path);

		$file_command    = "insert"; //명령어
		$file_table      = "board_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['reg_id'];
		$reg_date = $param['reg_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $b_idx, 'board');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and bf.bs_idx = '" . $bs_idx . "' and bf.b_idx = '" . $b_idx . "' and bf.sort ='" . $i . "'";
				$file_data = board_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, bs_idx, b_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($bs_idx) . "', '" . string_input($b_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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

		$str = '{"success_chk" : "Y", "idx_num" : "' . $param['b_idx'] . '", "f_class":"bbs", "f_idx":"' . $bs_idx . '", "f_idx2":"' . $b_idx . '"}';
		echo $str;
		exit;
	}

	//수정처리 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$param      = $_POST['param'];
		$table_name = $_POST['set_table_name'];

		$b_idx    = $_POST['b_idx'];
		$bs_idx   = $_POST['bs_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

		$command    = "update"; //명령어
		$table      = $table_name; //테이블명
		$conditions = "b_idx = '" . $b_idx . "'"; //조건

		$param["mod_id"]   = $mem_idx;
		$param["mod_date"] = date("Y-m-d H:i:s");
		$param["mem_idx"]  = $mem_idx;
		$param["ip_addr"]  = $ip_address;

		if ($param["secret_yn"] == "") $param["secret_yn"] = "N"; // 비밀글

		chk_before($param);

		db_query(make_sql($param, $command, $table, $conditions));
		board_link_insert($bs_idx, $b_idx); // 링크등록

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
		$notice_data = board_notice_data("view", $table, $notice_where);

		if ($notice_data["total_num"] > 0)
		{
			if ($notice_yn == "N")
			{
				$notice_sql = "
					update board_notice SET
						del_yn   = 'Y',
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
			// 정렬을 구한다.
				$data = query_view("select max(sort) as notice_sort from board_notice where bs_idx = '" . $bs_idx . "' and del_yn = 'N'");
				$param["notice_sort"] = ($data["notice_sort"] == "") ? '1' : $data["notice_sort"] + 1;

				$notice_sql = "
					insert into board_notice SET
						comp_idx = '" . $comp_idx . "',
						part_idx = '" . $part_idx . "',
						bs_idx   = '" . $bs_idx . "',
						b_idx    = '" . $b_idx . "',
						sort     = '" . $param["notice_sort"] . "',

						reg_id   = '" . $param["mod_id"] . "',
						reg_date = '" . $param["mod_date"] . "'
				";
				db_query($notice_sql);
			}
		}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_board_path;

		$data_path1 = $comp_board_path . '/' . $bs_idx;
		files_dir($data_path1);

		$data_path = $data_path1 . '/' . $b_idx;
		files_dir($data_path);

		$file_command    = "insert"; //명령어
		$file_table      = "board_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['mod_id'];
		$reg_date = $param['mod_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $b_idx, 'board');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and bf.bs_idx = '" . $bs_idx . "' and bf.b_idx = '" . $b_idx . "' and bf.sort ='" . $i . "'";
				$file_data = board_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, bs_idx, b_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($bs_idx) . "', '" . string_input($b_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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

		$str = '{"success_chk" : "Y", "idx_num" : "' . $b_idx . '", "f_class":"bbs", "f_idx":"' . $bs_idx . '", "f_idx2":"' . $b_idx . '"}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$param      = $_POST['param'];
		$table_name = $_POST['set_table_name'];
		$b_idx      = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = $table_name; //테이블명
		$conditions = "b_idx = '" . $b_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//선택복사, 이동
	function select_copy_move()
	{
		global $_POST, $_SESSION, $field_str, $this_page, $site_info, $sess_str;
		global $set_bbs, $now_ip;

		$sub_action    = $_POST["sub_action"];
		$chk_b_idx     = $_POST["chk_b_idx"];
		$bs_idx        = $_POST["bs_idx"];
		$select_bs_idx = $_POST["select_bs_idx"];

	// 이동, 복사할 게시판설정
		$set_where = " and bs.bs_idx = '" . $select_bs_idx . "'";
		$set_new_bbs = bbs_set_data("view", $set_where);

		$command    = "insert"; //명령어
		$table      = "board_" . $set_new_bbs["name_db"]; //테이블명
		$conditions = ""; //조건

		foreach ($chk_b_idx as $k => $b_idx)
		{
			if($b_idx != "")
			{
				$where    = " and b.b_idx = '" . $b_idx . "'";
				$bbs_data = bbs_info_data("view", $set_bbs["name_db"], $where, "", "", "", 2);

				$param["bs_idx"]        = $select_bs_idx;
				$param["writer"]        = $bbs_data["writer"];
				$param["subject"]       = $bbs_data["subject"];
				$param["editor_text"]   = $bbs_data["editor_text"];
				$param["remark"]        = $bbs_data["remark"];
				$param["search_str"]    = $bbs_data["search_str"];
				$param["pwd"]           = $bbs_data["pwd"];
				$param["views"]         = $bbs_data["views"];
				$param["recom"]         = $bbs_data["recom"];
				$param["scrap"]         = $bbs_data["scrap"];
				$param["secret"]        = $bbs_data["secret"];
				$param["mem_idx"]       = $bbs_data["mem_idx"];
				$param["ip_addr"]       = $bbs_data["ip_addr"];
				$param["write_date"]    = $bbs_data["write_date"];
				$param["sdate"]         = $bbs_data["sdate"];
				$param["edate"]         = $bbs_data["edate"];
				$param["sche_sdate"]    = $bbs_data["sche_sdate"];
				$param["sche_edate"]    = $bbs_data["sche_edate"];
				$param["sche_stime"]    = $bbs_data["sche_stime"];
				$param["sche_etime"]    = $bbs_data["sche_etime"];
				$param["del_reason"]    = $bbs_data["del_reason"];
				$param["del_screen_yn"] = $bbs_data["del_screen_yn"];

				$param["reg_id"]   = $bbs_data["reg_id"];
				$param["reg_date"] = $bbs_data["reg_date"];

			// 가장 큰수 구하기(일련번호)
				$data = query_view("select max(b_idx) as b_idx from " . $table);
				$param["b_idx"] = ($data["b_idx"] == "") ? "1" : $data["b_idx"] + 1;
				$new_b_idx = $param["b_idx"];

				db_query(make_sql($param, $command, $table, $conditions));

			// 업데이트
				$gno = $param["b_idx"];
				$data = query_view("select order_idx from " . $table . " order by order_idx desc limit 0, 1");
				if ($data["total_num"] == 0) $order_idx = 1;
				else
				{
					$order_idx = $data["order_idx"] + 1;
					$tgno      = 0;
				}
				$re_sql = "
					update " . $table . " SET
						gno       = '" . $gno . "',
						tgno      = '" . $tgno . "',
						order_idx = '" . $order_idx . "'
					where
						b_idx = '" . $param["b_idx"] . "'
				";
				db_query($re_sql);
				unset($param);

			// 파일
				$file_where = " and bf.bs_idx = '" . $bs_idx . "' and bf.b_idx = '" . $b_idx . "'";
				$file_list  = bbs_file_data("list", $file_where, "", "", "");
				if ($file_list["total_num"] > 0)
				{
					foreach($file_list as $file_k => $file_data)
					{
						if (is_array($file_data))
						{
							if (!is_dir($set_new_bbs["bbs_path"]))
							{
								@mkdir($set_new_bbs["bbs_path"], 0777);
							}

							if (!is_dir($set_new_bbs["bbs_path"] . "/" . $new_b_idx))
							{
								@mkdir($set_new_bbs["bbs_path"] . "/" . $new_b_idx, 0777);
							}

							$old_file_path = $set_bbs["bbs_path"] . "/" . $file_data["b_idx"] . "/" . $file_data["img_sname"];
							$new_file_path = $set_new_bbs["bbs_path"] . "/" . $new_b_idx . "/" . $file_data["img_sname"];

						// 이동일 경우
							if ($sub_action == "move_yn")
							{
								$file_sql = "
									update bbs_file set
										  bs_idx = '" . $select_bs_idx . "'
										, b_idx  = '" . $new_b_idx . "'
									where
										bf_idx = '" . $file_data["bf_idx"] . "'
								";
								db_query($file_sql);

								@copy($old_file_path, $new_file_path);
								@unlink($old_file_path);
							}
						// 복사일 경우
							else
							{
								$file_sql = "
									insert into bbs_file set
										  bs_idx     = '" . $select_bs_idx . "'
										, b_idx      = '" . $new_b_idx . "'
										, sort       = '" . $file_data["sort"] . "'
										, img_fname  = '" . $file_data["img_fname"] . "'
										, img_sname  = '" . $file_data["img_sname"] . "'
										, img_size   = '" . $file_data["img_size"] . "'
										, img_type   = '" . $file_data["img_type"] . "'
										, img_ext    = '" . $file_data["img_ext"] . "'
										, img_str    = '" . $file_data["img_str"] . "'
										, file_class = '" . $file_data["file_class"] . "'
										, reg_id     = '" . $file_data["reg_id"] . "'
										, reg_date   = '" . date("Y-m-d H:i:s") . "'
								";
								db_query($file_sql);

								@copy($old_file_path, $new_file_path);
							}
						}
					}
				}

			// 링크
				$link_where = " and bl.bs_idx = '" . $bs_idx . "' and bl.b_idx = '" . $b_idx . "'";
				$link_list  = bbs_link_data("list", $link_where, "", "", "");
				if ($link_list["total_num"] > 0)
				{
					foreach($link_list as $link_k => $link_data)
					{
						if (is_array($link_data))
						{
						// 이동일 경우
							if ($sub_action == "move_yn")
							{
								$link_sql = "
									update bbs_link set
										  bs_idx = '" . $select_bs_idx . "'
										, b_idx  = '" . $new_b_idx . "'
									where
										bl_idx = '" . $link_data["bl_idx"] . "'
								";
							}
						// 복사일 경우
							else
							{
								$link_sql = "
									insert into bbs_link set
										  bs_idx      = '" . $select_bs_idx . "'
										, b_idx       = '" . $new_b_idx . "'
										, sort        = '" . $link_data["sort"] . "'
										, link_url    = '" . $link_data["link_url"] . "'
										, link_target = '" . $link_data["link_target"] . "'
										, reg_id      = '" . $link_data["reg_id"] . "'
										, reg_date    = '" . date("Y-m-d H:i:s") . "'
								";
							}
							db_query($link_sql);
						}
					}
				}

			// 기존 게시물 삭제 - 이동일 경우
				if ($sub_action == "move_yn")
				{
					$del_sql = "delete from board_" . $set_bbs["name_db"] . " where b_idx ='" . $b_idx . "'";
					//db_query($del_sql);
				}
			}
		}

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//선택삭제
	function select_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;
		global $ip_address;

		$set_where = " and bs.bs_idx = '" . $bs_idx . "'";
		$set_bbs = board_set_data("view", $set_where);

		$chk_b_idx = $_POST["chk_b_idx"];
		$param     = $_POST["param"];

		$command = "update"; //명령어
		$table   = "board_" . $set_bbs["name_db"]; //테이블명

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

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}
?>