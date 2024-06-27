<?php
/*
	생성 : 2012.04.26
	수정 : 2013.02.14
	위치 : 안드로이드 - 푸시 등록 모듈
*/
	require_once "./include/class.MySqlDB.php";
	require_once "./include/class.FileLog.php";
	require_once "./include/function.php";

	extract($_GET);

	$pwd = pass_change($pw, 'bizstory');

	$db = new MySqlDB('bizstory');

// 사용자를 조회하여 처리한다.
	$sql = "select * from member_info where del_yn = 'N' and mem_id = '" . $id . "' and mem_pwd = '" . $pwd . "'";
	$_list = $db->__list($sql);
	if (count($_list) > 0)
	{
		$resCode = '{"resCode":[';
		for ($cnt = 0; $cnt < count($_list); $cnt++)
		{
		// url 에 해당 하는 자료가 있을 때의 처리
			$row = $_list[$cnt];

			$comp_idx = $row['comp_idx'];
			$part_idx = $row['part_idx'];
			$mem_idx  = $row['mem_idx'];

			$mem_name = $row['mem_name'];
			$mem_name = str_replace('"', '&quot;', $mem_name);

			if ($cnt == 0) $resCode .= '{';
			else $resCode .= ',{';

			$resCode .= '"code":"200"';
			$resCode .= ',"message":"'.$mem_name.'"';
			$resCode .= '}';
		}
		$resCode .= ']}';

		if ($cmd == "add")
		{
            //자신 이외의 사용자가 동일한 device token id를 갖고 있으면 삭제처리(del_yn='Y')하고 device token id 를 제거한다.
            $sql = "update push_member set
                    del_yn = 'Y'
                    , push_registration_id = ''
                    , mod_date = now()
                where 
                    del_yn = 'N'
                    and push_id <> '" . $id . "'
                    and push_device_type = 'A'
                    and push_registration_id = '" . $registration_id . "'";
                
            $db->__execute($sql); 
            
            // 안드로이드가 아닌 다른 디바이스 정보가 있다면 삭제처리(del_yn='Y')하고 device token id 를 제거한다.
            $sql = "update push_member set
                    del_yn = 'Y'
                    , push_registration_id = ''
                    , mod_date = now()
                where 
                    del_yn = 'N'
                    and push_id = '" . $id . "'
                    and push_device_type <> 'A' ";
            $db->__execute($sql); 
            
			$sql = "select * from push_member where
				del_yn = 'N'
				and push_id = '" . $id . "'
				and push_device_type = 'A'";
			$_list = $db->__list($sql);
			if (count($_list) > 0)
			{
				$sql = "update push_member set
					  push_enable  = 'Y'
					, push_message = '" . $message . "'
					, push_receipt = '" . $receipt . "'
					, push_work    = '" . $work . "'
					, push_consult = '" . $consult . "'
					, push_notice  = '" . $notice . "'
					, push_registration_id  = '" . $registration_id . "'
					, push_device_unique_id = '" . $device_unique_id . "'
					, mod_date = now()
				where
					del_yn = 'N'
					and push_id = '" . $id . "'
					and push_device_type = 'A'";
			}
			else
			{
				$sql = "insert into push_member set
					comp_idx  = '" . $comp_idx . "',
					part_idx  = '" . $part_idx . "',
					mem_idx   = '" . $mem_idx . "',
					push_id   = '" . $id . "',
					push_pwd  = '" . $pwd . "',
					push_name = '" . $mem_name . "',

					push_device_type      = 'A',
					push_registration_id  = '" . $registration_id . "',
					push_device_unique_id = '" . $device_unique_id . "',

					push_enable  = 'Y',
					push_message = '" . $message . "',
					push_receipt = '" . $receipt . "',
					push_work    = '" . $work . "',
					push_consult = '" . $consult . "',
					push_notice  = '" . $notice . "',

					reg_date = now(),
					del_yn   = 'N'";
			}
			$db->__execute($sql);
		}

		if ($cmd == "del")
		{
			$sql = "select * from push_member where del_yn = 'N' push_id = '" . $id . "' and push_pwd = '" . $pwd . "'";
			$_list = $db->__list($sql);
			if (count($_list) > 0)
			{
				$sql = "update push_member set
					  push_enable  = 'N'
					, push_message = '" . $message . "'
					, push_receipt = '" . $receipt . "'
					, push_work    = '" . $work . "'
					, push_consult = '" . $consult . "'
					, push_notice  = '" . $notice . "'
					, push_registration_id  = '" . $registration_id . "'
					, push_device_unique_id = '" . $device_unique_id . "'
					, mod_date = now()
				where
					del_yn = 'N'
					and push_id = '" . $id . "'
					and push_device_type = 'A'";
			}
			$db->__execute($sql);
		}

		if ($cmd == "registration") {
            //자신 이외의 사용자가 동일한 device token id를 갖고 있으면 삭제처리(del_yn='Y')하고 device token id 를 제거한다.
            $sql = "update push_member set
                    del_yn = 'Y'
                    , push_registration_id = ''
                    , mod_date = now()
                where 
                    del_yn = 'N'
                    and push_id <> '" . $id . "'
                    and push_device_type = 'A'
                    and push_registration_id = '" . $registration_id . "'";
                
            $db->__execute($sql); 
            
            
            // 안드로이드가 아닌 다른 디바이스 정보가 있다면 삭제처리(del_yn='Y')하고 device token id 를 제거한다.
            $sql = "update push_member set
                    del_yn = 'Y'
                    , push_registration_id = ''
                    , mod_date = now()
                where 
                    del_yn = 'N'
                    and push_id = '" . $id . "'
                    and push_device_type <> 'A' ";
            $db->__execute($sql); 
            
			$sql = "select * from push_member where
				del_yn = 'N'
				and push_id = '" . $id . "'
				and push_device_type = 'A'";
			$_list = $db->__list($sql);
			if (count($_list) > 0)
			{
				$sql = "update push_member set
					push_registration_id = '$registration_id',
					mod_date = now()
				where
					del_yn = 'N'
					and push_id = '" . $id . "'
					and push_device_type = 'A' ";
				$db->__execute($sql);
			}
		}
	}
	else
	{
		$resCode = '{"resCode" : [{"code" : "500", "message" : "인증실패"}]}';
	}
	echo $resCode;
?>