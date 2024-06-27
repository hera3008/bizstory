<?
	If ($_SESSION[$sess_str . "_mem_idx"] == "")
	{
		header("Location: " . $mobile_dir . '/login.php');
		exit;
	}
	else
	{
	// 권한체크
		if ($_SESSION[$sess_str . '_ubstory_level'] == "1") // 최고권한
		{
			$auth_menu['list']  = "Y";
			$auth_menu['view']  = "Y";
			$auth_menu['int']   = "Y";
			$auth_menu['mod']   = "Y";
			$auth_menu['del']   = "Y";
			$auth_menu['print'] = "Y";
			$auth_menu['down']  = "Y";
		}
		else
		{
			$auth_menu['list']  = $_SESSION[$sess_str . "_url"][$pidx]["yn_list"];
			$auth_menu['view']  = $_SESSION[$sess_str . "_url"][$pidx]["yn_view"];
			$auth_menu['int']   = $_SESSION[$sess_str . "_url"][$pidx]["yn_int"];
			$auth_menu['mod']   = $_SESSION[$sess_str . "_url"][$pidx]["yn_mod"];
			$auth_menu['del']   = $_SESSION[$sess_str . "_url"][$pidx]["yn_del"];
			$auth_menu['print'] = $_SESSION[$sess_str . "_url"][$pidx]["yn_print"];
			$auth_menu['down']  = $_SESSION[$sess_str . "_url"][$pidx]["yn_down"];
		}

	// 해당 페이지 목록여부확인
		If ($auth_menu['list'] != "Y" && $pidx != "")
		{
			$string_url = $mobile_dir . "/";
			//error_page('no_authority', $string_url);
			exit;
		}
	}
?>