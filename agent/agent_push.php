<?php
/*
	생성 : 2012.04.26
	위치 : 에이전트 - 푸시
*/
	//require_once "./include/class.FileLog.php";

	//$log_file = "log/_trace_agent_push.".date("Ymd-H").".log";
	//$log = new FileLog("agent_push");
	//$log->log_file = $log_file;
	//$log->logging = true;

	//$log->blank_line();
	//$log->log("** ".date("Ymd-His"));
	//$log->log("client_code=$client_code, MacAddress=$macaddress");

	//include "../bizstory/common/setting.php";
	//include $local_path . "/agent/include/agent_chk.php";
/*
	거래처 idx, 맥주소를 가지고 값을 가지고 온다.
*/
	//$history_where = "
	//	and ri.ci_idx = '" . $client_idx . "' and ri.macaddress = '" . $macaddress . "'
	//	and rsh.read_yn = 'N'
	//	and (code.code_value = '2' or code.code_value = '99')";
	//$history_list = receipt_status_history_data('list', $history_where, 'rsh.reg_date desc', '', '');

	//$log->log("total_num=".$history_list[0]['rsh_idx']);

	//$rsh_idx = $history_list[0]['rsh_idx'];
	//$rsh_idx = 0;

	//echo $rsh_idx;

?>