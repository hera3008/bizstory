<?
	include "../common/set_info.php";

	// 앱에서 넘겨주는 정보
	$mem_id		= $_GET["mem_id"];
	$auth_key	= $_GET["auth_key"];

//	echo $auth_key . " auth_key<BR />";
//	echo $mem_id . " mem_id<BR />";
//	exit;

	$mem_where = " and mem.mem_id = '" . $mem_id . "'";
	$mem_data = member_info_data("view", $mem_where);

	$success_chk = "N";
	$error_string = "";

	if ($mem_data["total_num"] == "0")
	{
		$error_string = "일치하는 아이디가 없습니다.";
	}
	else
	{
		// 로그인처리, 세션처리
		member_login_action($mem_data, $sess_str, "Y", $en_key);

		// 최종방문일, 카운트 하기
		db_query("
			update member_info set
				  last_date   = '" . date("Y-m-d H:i:s") . "'
				, total_visit = total_visit + 1
			where
				mem_idx = '" . $mem_data["mem_idx"] . "'
		");

		$success_chk = "Y";
	}

	$returnArray = array('success_chk'=>$success_chk, 'error_string'=>$error_string);
	echo json_encode($returnArray);

	db_close();
	exit;
?>
