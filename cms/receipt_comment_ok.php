<?
	include "../bizstory/common/setting.php";
	include $local_path . "/cms/include/client_chk.php";
	include $local_path . "/cms/include/no_direct.php";

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
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

//입력처리 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$param    = $_POST['param'];
		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$ri_idx   = $_POST['ri_idx'];
		$list_i   = $_POST['list_i'];

		$command    = "insert"; //명령어
		$table      = "receipt_comment"; //테이블명
		$conditions = ""; //조건

		$data = query_view("select max(rc_idx) as rc_idx from " . $table);
		$param["rc_idx"] = ($data["rc_idx"] == "") ? '1' : $data["rc_idx"] + 1;

	// 거래처사용자정보
		$sub_where = " and cu.cu_idx = '" . $_SESSION[$sess_str . '_cu_idx'] . "'";
		$sub_data = client_user_data('view', $sub_where);
		if ($param['writer'] == "") $param['writer'] = $sub_data['mem_name'];

		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['ri_idx']   = $ri_idx;
		$param['ip_addr']  = $ip_address;
		$param['reg_id']   = $param['writer'];
		$param['reg_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$reply_rc_idx = $_POST['rc_idx'];
		$reply_gno    = $_POST['gno'];
		$reply_tgno   = $_POST['tgno'];

	// 일반글일 경우
		if ($reply_rc_idx == "")
		{
			$param["gno"] = $param["rc_idx"];
			$data = query_view("select order_idx from " . $table . " order by order_idx desc limit 0, 1");

			if ($data["total_num"] == 0) $order_idx = 1;
			else
			{
				$order_idx = $data["order_idx"] + 1;
				$param["tgno"] = 0;
			}
		}
	// 답변글일 경우
		else
		{
			if ($reply_gno != $reply_rc_idx)
			{
				$param["gno"] = $reply_gno . ', ' . $reply_rc_idx;
			}
			else
			{
				$param["gno"] = $reply_rc_idx;
			}
			$data = query_view("select tgno, order_idx from " . $table . " where rc_idx ='" . $reply_rc_idx . "' order by tgno desc limit 0, 1");
			$order_idx = $data["order_idx"];
			$param["tgno"] = $data["tgno"] + 1;

			db_query("
				update " . $table . " set
					order_idx = order_idx + 1
				where
					order_idx >='" . $order_idx . "'
			");
		}

		db_query("
			update " . $table . " SET
				gno       = '" . $param["gno"] . "',
				tgno      = '" . $param["tgno"] . "',
				order_idx = '" . $order_idx . "'
			where
				rc_idx = '" . $param["rc_idx"] . "'
		");

		$str = '{"success_chk" : "Y", "comment_div" : "comment_view_' . $list_i . '"}';
		echo $str;
		exit;
	}
?>