<?php
/*
	생성 : 2012.04.26
	위치 : 안드로이드 - 푸시 등록 모듈
*/

require_once "./include/class.MySqlDB.php";
require_once "./include/class.FileLog.php";
require_once "./include/function.php";

//$log_file = "log/ios.".date("Ymd-H").".log";
$log_file = "log/ios.".date("Ymd").".log";
$log = new FileLog("ios");
$log->log_file = $log_file;
$log->logging = true;

$log->blank_line();
$log->log("** ".date("Ymd-His"));
$log->log("cmd=$cmd, id=$id, pw=$pw, registration_id=$registration_id, device_unique_id=$device_unique_id");
$log->log("message=$message, receipt=$receipt, work=$work, notice=$notice");

$log->log("resCode = ".$resCode);
echo $resCode;

$pwd = pass_change($pw, 'bizstory');

$db = new MySqlDB('bizstory');

// 사용자를 조회하여 처리한다.
$sql = "select * from member_info where mem_id = '".$id."' and mem_pwd = '".$pwd."' and del_yn = 'N'";
$log->log("sql = ".$sql);

$_list = $db->__list($sql);

if (count($_list) > 0)
{
	$resCode = '{resCode:[';

	for ($cnt = 0; $cnt < count($_list); $cnt++)
	{
		// url 에 해당 하는 자료가 있을 때의 처리
		$row = $_list[$cnt];

		$comp_idx = $row['comp_idx'];
		$part_idx = $row['part_idx'];
		$mem_idx  = $row['mem_idx'];

		$mem_name = $row['mem_name'];
		$mem_name = str_replace('"', '&quot;', $mem_name);

		if ($cnt == 0)
			$resCode .= '{';
		else
			$resCode .= ',{';

		$resCode .= 'code:"200"';
		$resCode .= ',message:"'.$mem_name.'"';

		$resCode .= '}';

		$log->log("인증성공");
	}
	$resCode .= ']});';

	if ($cmd == "add") {
		$sql = "select * from push_member where push_id = '".$id."' and push_pwd = '".$pwd."'";
		$log->log("sql = ".$sql);
		$_list = $db->__list($sql);
		if (count($_list) > 0)
		{
			$sql = "update push_member set
				  push_enable  = 'Y'
				, push_message = '$message'
				, push_receipt = '$receipt'
				, push_work    = '$work'
				, push_notice  = '$notice'
				, push_consult = '$consult'
				, push_device_unique_id = '$device_unique_id'
				, mod_date = now()
			where push_id = '" . $id . "'";
		}
		else
		{
			$sql = "insert into push_member (comp_idx, part_idx, mem_idx, push_id, push_pwd, push_name, push_enable, push_registration_id, "
				 . "push_message, push_receipt, push_work, push_notice, push_consult, "
				 . "push_device_unique_id, reg_date, del_yn) "
				 . "values ('$comp_idx','$part_idx','$mem_idx','$id','$pwd','$mem_name','Y','$registration_id','$message','$receipt','$work','$notice','$consult','$device_unique_id',now(),'N')";
		}
		$log->log("sql = ".$sql);
		$db->__execute($sql);
	}

	if ($cmd == "del")
	{
		$sql = "select * from push_member where push_id = '".$id."' and push_pwd = '".$pwd."'";
		$log->log("sql = ".$sql);
		$_list = $db->__list($sql);
		if (count($_list) > 0)
		{
			$sql = "update push_member set
				  push_enable  = 'N'
				, push_message = '$message'
				, push_receipt = '$receipt'
				, push_work    = '$work'
				, push_notice  = '$notice'
				, push_consult = '$consult'
				, push_device_unique_id = '$device_unique_id'
				, mod_date = now()
			where push_id = '".$id."'";
		}
		$log->log("sql = ".$sql);
		$db->__execute($sql);
	}

	if ($cmd == "registration")
	{
		$sql = "select * from push_member where push_id = '".$id."' and push_pwd = '".$pwd."'";
		$log->log("sql = ".$sql);
		$_list = $db->__list($sql);
		if (count($_list) > 0)
		{
			$sql = "update push_member set push_registration_id = '$registration_id', mod_date = now() where push_id = '".$id."'";
		}
		$log->log("sql = ".$sql);
		$db->__execute($sql);
	}
}
else
{
	$resCode = '{resCode : [{"code" : "500", "message" : "인증실패"}]}';
	$log->log("인증실패");
}

$log->log("resCode = ".$resCode);
echo $resCode;

?>
