<?
	include "../common/setting.php";
	//include "../common/no_direct.php";

	foreach ($_COOKIE as $key => $value) unset($_COOKIE[$key]);
	foreach ($_SESSION as $key => $value) unset($_SESSION[$key]);
	
	$_PARAM = $_POST;

	$_PARAM = array('param'=>array('mem_id' => 'arachi76@naver.com', 'mem_pwd' => 4862));
	$sub_type = 'check_login';

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
		$chk_param["require"][] = array("field"=>"mem_id",  "kmsg"=>"아이디");
		$chk_param["require"][] = array("field"=>"mem_pwd", "kmsg"=>"비밀번호");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 로그인
	function check_login()
	{
		global $_PARAM, $sess_str, $local_dir, $mybrowser_val_val;

		$param = $_PARAM["param"];		
		$param = string_input($param);
		
		chk_before($param);
		
		$mem_where = " and mem.mem_id = '" . $param["mem_id"] . "'";
		$mem_data = member_info_data("view", $mem_where);

		
		
		$error_yn = 'Y';
		if ($mem_data["total_num"] == "0")
		{
			$str = '{"success_chk" : "N", "error_string" : "일치하는 아이디가 없습니다. <br />확인 후 다시 입력해주세요.<br />"}';
			echo $str;
			exit;
		}
		else
		{
			$mem_pwd = pass_change($param["mem_pwd"], $sess_str);
			
			if ($mem_data["mem_pwd"] != $mem_pwd)
			{
				$str = '{"success_chk" : "N", "error_string" : "비밀번호가 일치하지 않습니다. <br />확인 후 다시 입력해주세요.<br />"}';
				echo $str;
				exit;
			}
			else
			{
			// 최대관리자
				if ($mem_data['ubstory_yn'] == "Y" && $mem_data['ubstory_level'] == "1")
				{
					member_login_action($mem_data, $sess_str);

				// 최종방문일, 카운트 하기
					$update_query = "
						update member_info set
							  last_date   = '" . date("Y-m-d H:i:s") . "'
							, total_visit = total_visit + 1
                            , browser_info = '" . $mybrowser_val_val . "'
						where
							mem_idx = '" . $mem_data["mem_idx"] . "'
					";
					db_query($update_query);
					query_history($update_query, 'member_info', 'update');

					$str = '{"success_chk" : "Y", "error_string" : ""}';
					echo $str;
					exit;
				}
				else
				{
					$comp_where = " and comp.comp_idx = '" . $mem_data['comp_idx'] . "'";
					$comp_data = company_info_data('view', $comp_where);
					
					if ($comp_data['total_num'] == 0)
					{
						$str = '{"success_chk" : "N", "error_string" : "업체정보가 없습니다.<br />"}';
						echo $str;
						exit;
					}
					else
					{
						if ($comp_data['auth_yn'] == "N" || $comp_data['view_yn'] == "N")
						{
							$str = '{"success_chk" : "N", "error_string" : "승인된 업체가 아닙니다. <br />관리자에게 문의하여주세요.<br />"}';
							echo $str;
							exit;
						}
						else
						{
							$start_date = date_replace($comp_data['start_date'], 'Ymd');
							if ($start_date == "") $start_date = "19000101";
							$end_date = date_replace($comp_data['end_date'], 'Ymd');
							if ($end_date == "") $end_date = "99991231";

							if ($start_date > date('Ymd') && $end_date < date('Ymd'))
							{
								$str = '{"success_chk" : "N", "error_string" : "기간이 만료되었습니다. <br />관리자에게 문의하여주세요.<br />"}';
								echo $str;
								exit;
							}
							else
							{
								if($mem_data["auth_yn"] == "N")
								{
									$str = '{"success_chk" : "N", "error_string" : "승인된 회원이 아닙니다. <br />관리자에게 문의하여주세요.<br />"}';
									echo $str;
									exit;
								}
								else
								{
									if($mem_data["login_yn"] == "N")
									{
										$str = '{"success_chk" : "N", "error_string" : "로그인권한이 없습니다. <br />관리자에게 문의하여주세요.<br />"}';
										echo $str;
										exit;
									}
									else
									{
										member_login_action($mem_data, $sess_str);
										
										$auto_value = $_SESSION[$sess_str . '_auto_login'];
										
									// 최종방문일, 카운트 하기
										$update_query = "
											update member_info set
												  last_date   = '" . date("Y-m-d H:i:s") . "'
												, total_visit = total_visit + 1
												, browser_info = '" . $mybrowser_val_val . "'
											where
												mem_idx = '" . $mem_data["mem_idx"] . "'
										";

										db_query($update_query);
										query_history($update_query, 'member_info', 'update');

										$str = '{"success_chk" : "Y", "error_string" : "", "auto_value" : "' . $auto_value . '"}';
										echo $str;
										exit;
									}
								}
							}
						}
					}
				}
			}
		}
	}

// 자동로그인
	function login_auto()
	{
		global $_PARAM, $sess_str, $local_dir, $mybrowser_val_val;

		$sess_chk   = $sess_str . $sess_str . $sess_str . $sess_str . $sess_str;
		$member_chk = $_PARAM["cookie_value"];
		$member_chk = base64_decode($member_chk);
		$chk_value  = str_replace($sess_str, '', $member_chk);
		$chk_len = strlen($member_chk);
		$total_chk = '';
		for ($i = 0; $i < $chk_len; $i++)
		{
			$chk_char = substr($chk_value, $i, 1);
			if ($i % 2 == 1)
			{
				$total_chk .= $chk_char;
			}
		}
		$total_arr = explode('!@#', $total_chk);

		$mem_where = " and mem.mem_idx = '" . $total_arr[1] . "'";
		$mem_data = member_info_data("view", $mem_where);

		$error_yn = 'Y';
		if ($mem_data["total_num"] == "0")
		{
			$str = '{"success_chk" : "Y"}';
			echo $str;
			exit;
		}
		else
		{
		// 최대관리자
			if ($mem_data['ubstory_yn'] == "Y" && $mem_data['ubstory_level'] == "1")
			{
				member_login_action($mem_data, $sess_str);

			// 최종방문일, 카운트 하기
				$update_query = "
					update member_info set
						  last_date   = '" . date("Y-m-d H:i:s") . "'
						, total_visit = total_visit + 1
						, browser_info = '" . $mybrowser_val_val . "'
					where
						mem_idx = '" . $mem_data["mem_idx"] . "'
				";
				db_query($update_query);
				query_history($update_query, 'member_info', 'update');

				$str = '{"success_chk" : "Y"}';
				echo $str;
				exit;
			}
			else
			{
				$comp_where = " and comp.comp_idx = '" . $mem_data['comp_idx'] . "'";
				$comp_data = company_info_data('view', $comp_where);

				if ($comp_data['total_num'] == 0)
				{
					$str = '{"success_chk" : "Y"}';
					echo $str;
					exit;
				}
				else
				{
					if ($comp_data['auth_yn'] == "N")
					{
						$str = '{"success_chk" : "Y"}';
						echo $str;
						exit;
					}
					else
					{
						$start_date = date_replace($comp_data['start_date'], 'Ymd');
						if ($start_date == "") $start_date = "19000101";
						$end_date = date_replace($comp_data['end_date'], 'Ymd');
						if ($end_date == "") $end_date = "99991231";

						if ($start_date > date('Ymd') && $end_date < date('Ymd'))
						{
							$str = '{"success_chk" : "Y"}';
							echo $str;
							exit;
						}
						else
						{
							if($mem_data["auth_yn"] == "N")
							{
								$str = '{"success_chk" : "Y"}';
								echo $str;
								exit;
							}
							else
							{
								if($mem_data["login_yn"] == "N")
								{
									$str = '{"success_chk" : "Y"}';
									echo $str;
									exit;
								}
								else
								{
									member_login_action($mem_data, $sess_str);

									global $_SESSION;
									$auto_value = $_SESSION[$sess_str . '_auto_login'];

								// 최종방문일, 카운트 하기
									$update_query = "
										update member_info set
											  last_date   = '" . date("Y-m-d H:i:s") . "'
											, total_visit = total_visit + 1
											, browser_info = '" . $mybrowser_val_val . "'
										where
											mem_idx = '" . $mem_data["mem_idx"] . "'
									";
									db_query($update_query);
									query_history($update_query, 'member_info', 'update');

									$str = '{"success_chk" : "Y", "auto_value" : "' . $auto_value . '"}';
									echo $str;
									exit;
								}
							}
						}
					}
				}
			}
		}
	}
?>