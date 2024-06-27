<?
include "../../common/set_info.php";

// 로그인
global $sess_str;

$auth_key = "";
$mem_id = "";
$str = "";

$auth_key = $_GET['auth_key'];
$mem_id = $_GET['mem_id'];
$device_update = $_GET['device_update'];

//echo $auth_key . "<BR>" . $mem_id . "<BR>" . $device_update . "<BR>";
//exit;

$auth_where = " push_id = '" . $mem_id . "'";
$auth_data = member_active_auth($auth_where);

if ($auth_data["total_num"] == "0") {
	$str = '{"rescode" : "9999", "resmsg" : "서버오류입니다."}';	//auth값이 틀릴때
}
else {
	if ($auth_data["applogin_state"] == "N") {
		$str = '{"rescode" : "9990", "resmsg" : "로그아웃 상태입니다."}';
	}
	else {

		if ($auth_data["active_auth"] != $auth_key) {
			$str = '{"rescode" : "9991", "resmsg" : "잘못된 접속입니다."}';
		}
		else {
			
			$mem_where = " and mem.mem_id = '" . $mem_id . "'";
			$mem_data = member_info_data("view", $mem_where);

			$error_yn = 'Y';
			if ($mem_data["total_num"] == "0")
			{
				$str = '{"rescode" : "9998", "resmsg" : "일치하는 아이디가 없습니다."}';
			}
			else
			{

				// 최대관리자
				if ($mem_data['ubstory_yn'] == "Y" && $mem_data['ubstory_level'] == "1")
				{
					// 로그인성공
					$str = '{"rescode" : "0000", "resmsg" : "로그인성공"}';
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
										$str = '{"rescode" : "0000", "resmsg" : "로그인성공"}';
									}
								}
							}
						}
					}
				}


				// 디바이스 토큰 업데이트
				if ($str == '{"rescode" : "0000", "resmsg" : "로그인성공"}') {

					// 로그인처리, 세션처리
					// member_login_action($mem_data, $sess_str, "", $en_key);

					// 최종방문일, 카운트 하기
					db_query("
						update member_info set
							  last_date   = '" . date("Y-m-d H:i:s") . "'
							, total_visit = total_visit + 1
						where mem_idx = '" . $mem_data["mem_idx"] . "'
					");

					// 로그인시 넘어온값 저장
					db_query("
						update push_member set
							  push_registration_id = '" . $device_update . "'
							, mod_date = '" . date("Y-m-d H:i:s") . "'
						where mem_idx = '" . $mem_data["mem_idx"] . "'
					");
				}
			}
		}
	}
}

echo $str;

?>