<?
include "../../common/set_info.php";

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

// 로그인
global $sess_str;

$auth_key = "";
$mem_id = "";
$mem_pwd = "";
$str = "";

$auth_key = $_GET['auth_key'];
$mem_id = $_GET['mem_id'];
$mem_pwd = $_GET['mem_pwd'];
$device_update = $_GET['device_update'];
$device_type = $_GET['device_type'];

if ($device_type == "") { $device_type="I"; }

$success_chk = false;

//echo $auth_key . "<BR>" . $mem_id . "<BR>" . $mem_pwd . "<BR>" . $device_update . "<BR>";

$mem_where = " and mem.mem_id = '" . $mem_id . "'";
$mem_data = member_info_data("view", $mem_where);

$error_yn = 'Y';
if ($mem_data["total_num"] == "0")
{
	$str = '{"rescode" : "9998", "resmsg" : "일치하는 아이디가 없습니다."}';
}
else
{
	$mem_pwd = pass_change($mem_pwd , $sess_str);

	if ($mem_data["mem_pwd"] != $mem_pwd)
	{
		$str = '{"rescode" : "9997", "resmsg" : "비밀번호가 일치하지 않습니다."}';
	}
	else
	{
		// 최대관리자
		if ($mem_data['ubstory_yn'] == "Y" && $mem_data['ubstory_level'] == "1")
		{
			// 로그인성공
			//$str = '{"rescode" : "0000", "resmsg" : "로그인성공"}';
			$success_chk = true;
		}
		else
		{
			$comp_where = " and comp.comp_idx = '" . $mem_data['comp_idx'] . "'";
			$comp_data = company_info_data('view', $comp_where);

			if ($comp_data['total_num'] == 0)
			{
				$str = '{"rescode" : "9996", "resmsg" : "업체정보가 없습니다."}';
			}
			else
			{
				if ($comp_data['auth_yn'] == "N")
				{
					$str = '{"rescode" : "9995", "resmsg" : "승인된 업체가 아닙니다."}';
				}
				else
				{
					$start_date = date_replace($comp_data['start_date'], 'Ymd');
					if ($start_date == "") $start_date = "19000101";
					$end_date = date_replace($comp_data['end_date'], 'Ymd');
					if ($end_date == "") $end_date = "99991231";

					if ($start_date > date('Ymd') && $end_date < date('Ymd'))
					{
						$str = '{"rescode" : "9994", "resmsg" : "기간이 만료되었습니다."}';
					}
					else
					{
						if($mem_data["auth_yn"] == "N")
						{
							$str = '{"rescode" : "9993", "resmsg" : "승인된 회원이 아닙니다."}';
						}
						else
						{
							if($mem_data["login_yn"] == "N")
							{
								$str = '{"rescode" : "9992", "resmsg" : "로그인 권한이 없습니다."}';
							}
							else
							{
								// 로그인성공
								//$str = '{"rescode" : "0000", "resmsg" : "로그인성공"}';
								$success_chk = true;
							}
						}
					}
				}
			}
		}

        if ($success_chk == true) {

            $str = '{"rescode" : "0000", "resmsg" : "로그인성공"}';

            // 최종방문일, 카운트 하기
            db_query("
                update member_info set
                      last_date   = '" . date("Y-m-d H:i:s") . "'
                    , total_visit = total_visit + 1
                where mem_idx = '" . $mem_data["mem_idx"] . "'
            ");

    		$auth_where = " push_id = '" . $mem_id . "'";
    		$auth_data = member_active_auth($auth_where);
    
    		if ($auth_data["total_num"] == "0") {
    		    
                db_query("insert into push_member set
                    comp_idx  = '" . $mem_data['comp_idx'] . "',
                    part_idx  = '" . $mem_data['part_idx'] . "',
                    mem_idx   = '" . $mem_data['mem_idx'] . "',
                    push_id   = '" . $mem_id . "',
                    push_pwd  = '" . $mem_pwd . "',
                    push_name = '" . $mem_data['mem_name'] . "',

                    push_device_type      = '" . $device_type . "',
                    push_registration_id  = '" . $device_update . "',
                    push_device_unique_id = '" . $device_unique_id . "',

                    push_enable  = 'Y',
                    push_message = 'Y',
                    push_receipt = 'Y',
                    push_work    = 'Y',
                    push_consult = 'Y',
                    push_notice  = 'Y',

                    reg_date = now(),
                    del_yn   = 'N'");
    		} else if ($auth_data["total_num"] != "0") {
    		    // 로그인처리, 세션처리
                // member_login_action($mem_data, $sess_str, "", $en_key);
                
                // 로그인시 넘어온값 저장
                db_query("
                    update push_member set
                          push_registration_id = '" . $device_update . "'
                        , push_device_type = '" . $device_type . "'
                        , active_auth = '" . $auth_key . "'
                        , mod_date = '" . date("Y-m-d H:i:s") . "'
                        , applogin_state = 'Y'
                    where mem_idx = '" . $mem_data["mem_idx"] . "'
                ");
            }
         
		}
	}
}

echo $str;

?>