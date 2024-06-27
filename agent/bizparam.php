<?php
	//include "./include/class.FileLog.php";

	//$log_file = "log/_trace_bizparam.".date("Ymd").".log";
	//$log = new FileLog("bizparam");
	//$log->log_file = $log_file;
	//$log->logging = true;

	//$log->blank_line();
	//$log->log("** ".date("Ymd-His"));
	//$log->log("client_code=".$_REQUEST['client_code'].", agent_pwd=".$_REQUEST['agent_pwd'].", macaddress=".$_REQUEST['macaddress'].", ver_info=".$_REQUEST['ver_info'].", cpu_info=".$_REQUEST['cpu_info'].", hw_info=".$_REQUEST['hw_info'].", sw_info=".$_REQUEST['sw_info'].", local_ip=".$_REQUEST['local_ip'].", gateway=".$_REQUEST['gateway']);

	include "../bizstory/common/setting.php";
/*
	거래처코드 - client_code
	맥어드레스 - macaddress
	식별자 - agent_idx
	암호 - agent_pwd = bizstory4862
	cpu_info

	pass_pwd     -> agent_pwd
	ci_code      -> client_code
	ver_info     -> ver_info
	cpu_info     -> cpu_info
	hw_info      -> hw_info
	sw_info      -> sw_info
	local_ip     -> local_ip
	gateway      -> gateway
	request_name -> request_name
	tel_num      -> tel_num
*/
	$str_data = "
		agent_pwd   => " . $agent_pwd . "|
		client_code => " . $client_code . "|
		macaddress  => " . $macaddress . "|
		ver_info    => " . $ver_info . "|
		cpu_info    => " . utf_han($cpu_info) . "|
		hw_info     => " . utf_han($hw_info) . "|
		sw_info     => " . utf_han($sw_info) . "|
		local_ip    => " . $_SERVER['REMOTE_ADDR'] . "|
		gateway     => " . $gateway . "|
	";

//	if ($agent_pwd != "bizstory4862")
//	{
//		echo "암호가 틀리다.<br>";
//	}
//	else
//	{
	// 거래처정보
		$client_where = " and ci.client_code = '" . $client_code . "'";
		$client_data = client_info_data('view', $client_where);

	// CPU정보 체크
		$cpu_where = " and ac.cpu_info = '" . $cpu_info . "'";
		$cpu_data = agent_cpu_data('page', $cpu_where);

		$_SESSION['agent_macaddress'] = $macaddress;

	// 없는 경우 insert 한다.
		if ($cpu_data['total_num'] == 0)
		{
		//----------------------------------------------------------------------------
			$cpu_command    = "insert"; //명령어
			$cpu_table      = "agent_cpu"; //테이블명
			$cpu_conditions = ""; //조건

			$cpu_param['reg_date'] = date("Y-m-d H:i:s");
			$cpu_param['comp_idx'] = $client_data['comp_idx'];
			$cpu_param['part_idx'] = $client_data['part_idx'];
			$cpu_param['cpu_info'] = $cpu_info;

			db_query(make_sql($cpu_param, $cpu_command, $cpu_table, $cpu_conditions));

		//----------------------------------------------------------------------------
			$data_command    = "insert"; //명령어
			$data_table      = "agent_data"; //테이블명
			$data_conditions = ""; //조건

			$data_param['comp_idx']    = $client_data['comp_idx'];
			$data_param['ci_idx']      = $client_data['ci_idx'];
			$data_param['client_code'] = $client_data['client_code'];
			$data_param['ver_info']    = $ver_info;
			$data_param['cpu_info']    = $cpu_info;
			$data_param['hw_info']     = $hw_info;
			$data_param['sw_info']     = $sw_info;
			$data_param['local_ip']    = $local_ip;
			$data_param['gateway']     = $gateway;
			$data_param['macaddress']  = $macaddress;
			$data_param['reg_id']      = $client_code;
			$data_param['reg_date']    = date("Y-m-d H:i:s");
			$data_param['mod_data']    = $str_data;

			db_query(make_sql($data_param, $data_command, $data_table, $data_conditions));

			echo "새로 CPU 정보를 저장했습니다.<br>";
		}
	// 있는 경우 update 한다.
		else
		{
		//----------------------------------------------------------------------------
			$cpu_command    = "update"; //명령어
			$cpu_table      = "agent_cpu"; //테이블명
			$cpu_conditions = "cpu_info='" . $cpu_info . "'"; //조건

			$cpu_param['mod_date'] = date("Y-m-d H:i:s");

			db_query(make_sql($cpu_param, $cpu_command, $cpu_table, $cpu_conditions));

		//----------------------------------------------------------------------------
			$data_command    = "update"; //명령어
			$data_table      = "agent_data"; //테이블명
			$data_conditions = "cpu_info='" . $cpu_info . "'"; //조건

			$data_param['mod_id']   = $client_data['client_code'];
			$data_param['mod_date'] = date("Y-m-d H:i:s");
			$data_param['mod_data'] = $str_data;

			$data_param['comp_idx']    = $client_data['comp_idx'];
			$data_param['ci_idx']      = $client_data['ci_idx'];
			$data_param['client_code'] = $client_data['client_code'];

			if ($ver_info != "") $data_param['ver_info'] = $ver_info;
			if ($cpu_info != "") $data_param['cpu_info'] = $cpu_info;
			if ($hw_info  != "") $data_param['hw_info']  = $hw_info;
			if ($sw_info  != "") $data_param['sw_info']  = $sw_info;
			if ($local_ip != "") $data_param['local_ip'] = $local_ip;
			if ($gateway  != "") $data_param['gateway']  = $gateway;

			db_query(make_sql($data_param, $data_command, $data_table, $data_conditions));

			echo "기존에 저장된 CPU 정보입니다.<br>";
		}
//	}
?>