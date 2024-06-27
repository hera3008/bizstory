<?
	include "../common/set_info.php";
	include "./process/no_direct.php";

	//자동 로그인에 관련된 쿠키&세션값 외에는 초기화 한다.
	foreach ($_COOKIE as $key => $value) {
		if ($key != "auto_login" || $key != "login_mem_id_save") {
			unset($_COOKIE[$key]);
		}
	}
	foreach ($_SESSION as $key => $value) {
		if ($sess_str . '_auto_login' != $key) 
		{
			unset($_SESSION[$key]);
		}
	}
	
	$returnArray = array();

	if($sub_type == "")
	{
		$returnArray = array('success_chk'=>'N', 'error_string'=>'sub_type 명이 필요합니다.');
	}
	else 
	{
		if(function_exists($sub_type)) {
			$returnArray = call_user_func($sub_type);			
		} else {
			$returnArray = array('success_chk'=>'N', "error_string"=>"sub_type method 가 없습니다.");
		}
		
	}
	
	echo json_encode($returnArray);
	
	db_close();
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
		global $_POST, $sess_str, $local_dir;

		$param = $_POST["param"];
		$param = string_input($param);
		$mem_id = "";
		$mem_pwd = "";
		$success_chk = "N";
		$error_string = "";
		
		//자동로그인 값이 빈값이지 않으면 자동로그인 처리
		if ($_SESSION[$sess_str . '_auto_login']  != "" && $_SESSION[$sess_str . '_auto_login']  != null) {
			
			$login_tmp = decrypt($_SESSION[$sess_str . '_auto_login'], $en_key);
			
			$login_data = explode('!@#', $login_tmp);
			
			$mem_id = $login_data[1];
			$mem_pwd = $login_data[2];
			$auto_login = "Y";
			
		} else {

			chk_before($param);
			
			$mem_id = $param['mem_id'];
			$mem_pwd = $param['mem_pwd'];
			$auto_login = $param["auto_login"];
		}

		$mem_where = " and mem.mem_id = '" . $mem_id . "'";
		$mem_data = member_info_data("view", $mem_where);

		$error_yn = 'Y';
		if ($mem_data["total_num"] == "0")
		{
			//$str = '{"success_chk" : "N", "error_string" : "일치하는 아이디가 없습니다. \\n확인 후 다시 입력해주세요."}';
			$error_string = "일치하는 아이디가 없습니다. \\n확인 후 다시 입력해주세요.";
		}
		else
		{
			$mem_pwd = pass_change($mem_pwd , $sess_str);

			if ($mem_data["mem_pwd"] != $mem_pwd)
			{
				//$str = '{"success_chk" : "N", "error_string" : "비밀번호가 일치하지 않습니다. \\n확인 후 다시 입력해주세요."}';
				$error_string = "비밀번호가 일치하지 않습니다. \\n확인 후 다시 입력해주세요.";
			}
			else
			{
			// 최대관리자
				if ($mem_data['ubstory_yn'] == "Y" && $mem_data['ubstory_level'] == "1")
				{
					member_login_action($mem_data, $sess_str, $auto_login, $en_key);

				// 최종방문일, 카운트 하기
					db_query("
						update member_info set
							  last_date   = '" . date("Y-m-d H:i:s") . "'
							, total_visit = total_visit + 1
						where
							mem_idx = '" . $mem_data["mem_idx"] . "'
					");
					//query_history($update_query, 'member_info', 'update');

					$success_chk = "Y";
				}
				else
				{
					$comp_where = " and comp.comp_idx = '" . $mem_data['comp_idx'] . "'";
					$comp_data = company_info_data('view', $comp_where);

					if ($comp_data['total_num'] == 0)
					{
						$error_string = "업체정보가 없습니다.";
					}
					else
					{
						if ($comp_data['auth_yn'] == "N")
						{
							$error_string = "승인된 업체가 아닙니다. \\n관리자에게 문의하여주세요.";
						}
						else
						{
							$start_date = date_replace($comp_data['start_date'], 'Ymd');
							if ($start_date == "") $start_date = "19000101";
							$end_date = date_replace($comp_data['end_date'], 'Ymd');
							if ($end_date == "") $end_date = "99991231";

							if ($start_date > date('Ymd') && $end_date < date('Ymd'))
							{
								$error_string = "기간이 만료되었습니다. \\n관리자에게 문의하여주세요.";
							}
							else
							{
								if($mem_data["auth_yn"] == "N")
								{
									$error_string = "승인된 회원이 아닙니다. \\n관리자에게 문의하여주세요.";
								}
								else
								{
									if($mem_data["login_yn"] == "N")
									{
										$error_string = "로그인권한이 없습니다. \\n관리자에게 문의하여주세요.";
									}
									else
									{
										member_login_action($mem_data, $sess_str, $auto_login, $en_key);

										global $_SESSION;
										$auto_value = $_SESSION[$sess_str . '_auto_login'];

									// 최종방문일, 카운트 하기
										db_query("
											update member_info set
												  last_date   = '" . date("Y-m-d H:i:s") . "'
												, total_visit = total_visit + 1
											where
												mem_idx = '" . $mem_data["mem_idx"] . "'
										");
										//query_history($update_query, 'member_info', 'update');

										$success_chk = "Y";
									}
								}
							}
						}
					}
				}
			}
		}

		return array(
			'success_chk'=>$success_chk,
			'error_string'=>$error_string,
			'auto_value'=>$auto_value
		);
	}
?>