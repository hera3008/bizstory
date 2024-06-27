<?
// 웹페이지 로그아웃
$session_time = 60 * 60 * 6; // 6hours
ini_set("session.cache_expire", $session_time);
ini_set("session.gc_maxlifetime", $session_time);

session_start();

header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
header("Content-type: text/html; charset=UTF-8");
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");

foreach ($_COOKIE as $key => $value)
{
	unset($_COOKIE[$key]);
}

foreach ($_SESSION as $key => $value)
{
	unset($_SESSION[$key]);
}

// 어플 로그아웃
include "../../common/set_info.php";


$auth_key = "";
$mem_id = "";

$auth_key = $_GET['auth_key'];
$mem_id = $_GET['mem_id'];


$error_yn = 'Y';

$auth_where = " push_id = '" . $mem_id . "'";
$auth_data = member_active_auth($auth_where);

if ($auth_data["total_num"] == "0") {
	$str = '{"rescode" : "9999", "resmsg" : "서버오류입니다."}';
}
else {
	if ($auth_data["active_auth"] == $auth_key) {
		// 로그아웃 처리
		db_query("
			update push_member set
				  active_auth = null
				, applogin_state = 'N'
				, applogout_date = '" . date("Y-m-d H:i:s") . "'
			where push_id = '" . $mem_id . "'
		");

		$str = '{"rescode" : "0000", "resmsg" : "로그아웃완료"}';
	}
	else {
		$str = '{"rescode" : "9991", "resmsg" : "잘못된 접속입니다."}';
	}
}


echo $str;

?>