<?
/*
	생성 : 2012.12.10
	수정 : 2012.12.10
	위치 : 로그인 - 실행
*/
	include "../bizstory/common/setting.php";
	include "../bizstory/common/no_direct.php";

	foreach ($_COOKIE as $key => $value) unset($_COOKIE[$key]);
	foreach ($_SESSION as $key => $value) unset($_SESSION[$key]);

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
		$chk_param["require"][] = array("field"=>"sole_id",  "kmsg"=>"아이디");
		$chk_param["require"][] = array("field"=>"sole_pwd", "kmsg"=>"비밀번호");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 로그인
	function check_login()
	{
		global $_POST, $sess_str, $local_dir;

		$param = $_POST["param"];
		$param = string_input($param);

		chk_before($param);

		$sole_where = " and sole.sole_id = '" . $param["sole_id"] . "'";
		$sole_data = sole_info_data("view", $sole_where);

		$error_yn = 'Y';
		if ($sole_data["total_num"] == "0")
		{
			$str = '{"success_chk" : "N", "error_string" : "일치하는 아이디가 없습니다. <br />확인 후 다시 입력해주세요.<br />"}';
			echo $str;
			exit;
		}
		else
		{
			$sole_pwd = pass_change($param["sole_pwd"], $sess_str);

			if ($sole_data["sole_pwd"] != $sole_pwd)
			{
				$str = '{"success_chk" : "N", "error_string" : "비밀번호가 일치하지 않습니다. <br />확인 후 다시 입력해주세요.<br />"}';
				echo $str;
				exit;
			}
			else
			{
				if($sole_data["view_yn"] == "N")
				{
					$str = '{"success_chk" : "N", "error_string" : "로그인이 가능하지 않습니다. <br />관리자에게 문의하여주세요.<br />"}';
					echo $str;
					exit;
				}
				else
				{
					$_SESSION[$sess_str . '_sole_idx']  = $sole_data['sole_idx'];
					$_SESSION[$sess_str . '_sole_id']   = $sole_data['sole_id'];
					$_SESSION[$sess_str . '_comp_name'] = $sole_data['comp_name'];

					$str = '{"success_chk" : "Y", "error_string" : ""}';
					echo $str;
					exit;
				}
			}
		}
	}
?>