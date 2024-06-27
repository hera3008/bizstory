<?
	If ($_SESSION[$sess_str . "_mem_idx"] == "")
	{
		$site_url = str_replace('www.', '', $site_url);
		$domain_where = " and di.domain = '" . $site_url . "'";
		$domain_data = domain_info_data('view', $domain_where);
		if ($domain_data['total_num'] == 0)
		{
			$login_url = $local_dir . '/bizstory/member/login.php?move_url=' . $move_url;
		}
		else
		{
			if ($domain_data['domain'] == '')
			{
				$login_url = $local_dir . '/bizstory/member/login.php?move_url=' . $move_url;
			}
			else
			{
				if ($domain_data['login_type'] == 'A')
				{
					$login_url = $local_dir . '/bizstory/member/login.php?move_url=' . $move_url;
				}
				else
				{
					if (file_exists($local_path . "/bizstory/member/login_" . $domain_data['login_type'] . ".php") == true)
					{
						$login_url = $local_dir . '/bizstory/member/login_' . $domain_data['login_type'] . '.php?move_url=' . $move_url;
					}
					else
					{
						$login_url = $local_dir . '/bizstory/member/login.php?move_url=' . $move_url;
					}
				}
			}
		}

		if ($page_chk == 'json')
		{
			$string_url = $local_dir . '/bizstory/member/login_popup.php?move_url=' . $move_url;
			$str = '{"success_chk":"no_login", "error_string":"로그인을 하세요.", "fmode":"' . $fmode . '", "smode":"' . $smode . '"}';
			echo $str;
			exit;
		}
		else if ($page_chk == 'html')
		{
			header("Location: " . $login_url);
			exit;
		}

		header("Location: " . $login_url);
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

			$bbs_auth_yn = 'Y';
		}
		else
		{
			$menu_where = " and mi.mode_folder = '" . $fmode . "' and mi.mode_file = '" . $smode . "' and mam.mem_idx = '" . $_SESSION[$sess_str . "_mem_idx"] . "'";
			$menu_data = menu_auth_member_data("view", $menu_where);

			$pidx = $menu_data['menu_code'];

			$auth_menu['list']  = $menu_data["yn_list"];
			$auth_menu['view']  = $menu_data["yn_view"];
			$auth_menu['int']   = $menu_data["yn_int"];
			$auth_menu['mod']   = $menu_data["yn_mod"];
			$auth_menu['del']   = $menu_data["yn_del"];
			$auth_menu['print'] = $menu_data["yn_print"];
			$auth_menu['down']  = $menu_data["yn_down"];

			$bbs_auth_yn = 'N';

			if ($_SESSION[$sess_str . "_comp_idx"] == '1' && ($_SESSION[$sess_str . "_mem_idx"] == '2' || $_SESSION[$sess_str . "_mem_idx"] == '8'))
			{
				$auth_menu['list']  = "Y";
				$auth_menu['view']  = "Y";
				$auth_menu['int']   = "Y";
				$auth_menu['mod']   = "Y";
				$auth_menu['del']   = "Y";
				$auth_menu['print'] = "Y";
				$auth_menu['down']  = "Y";

				$bbs_auth_yn = 'Y';
			}
		}

		$sess_comp = $_SESSION[$sess_str . '_comp_idx'];
		$sess_part = search_company_part($code_part);

	// 현 메뉴명 가지고 오기
		$navi_where = " and mi.mode_folder = '" . $fmode . "' and mi.mode_file = '" . $smode . "'";
		$navi_data = menu_info_data("view", $navi_where);

	// 업체별로 메뉴명 가지고 오기
		$sub_where = " and mc.comp_idx = '" . $sess_comp . "' and mc.part_idx = '" . $sess_part . "' and mc.mi_idx = '" . $navi_data['mi_idx'] . "'";
		$sub_data = menu_company_data('view', $sub_where);

		$page_menu_name = $sub_data['menu_name'];
		if ($page_menu_name == '') $page_menu_name = $navi_data['menu_name'];


	// 해당 페이지 목록여부확인
		If ($auth_menu['list'] != "Y" && $pidx != "")
		{
			$string_url = $local_dir . "/";
			error_page('no_authority', $string_url);
			exit;
		}
	}
?>