<?
	//require_once "./include/class.FileLog.php";

	//$log_file = "log/_trace_agent.".date("Ymd").".log";
	//$log = new FileLog("agent");
	//$log->log_file = $log_file;
	//$log->logging = true;

	//$log->blank_line();
	//$log->log("** ".date("Ymd-His"));
	//$log->log("client_code=".$_REQUEST['client_code'].", macaddress=".$_REQUEST['macaddress']);

	include "../bizstory/common/setting.php";

	foreach ($_COOKIE as $key => $value) unset($_COOKIE[$key]);
	foreach ($_SESSION as $key => $value) unset($_SESSION[$key]);

	$client_where = " and ci.client_code = '" . $client_code . "'";
	$client_data = client_info_data('view', $client_where);

	$comp_where = " and comp.comp_idx = '" . $client_data['comp_idx'] . "'";
	$comp_data = company_info_data('view', $comp_where);

// 에이전트 사용여부 확인
	$bizstory_auth = "N";
	if ($client_data['total_num'] > 0) // 거래처등록여부
	{
		if ($client_data['view_yn'] == 'Y') // 사용여부
		{
			if ($client_data['ip_yn'] == 'N') // IP허용여부
			{
				$bizstory_auth = "Y";
			}
			else
			{
				$client_ip = $client_data['ip_info'];
				$client_ip_arr = explode(',', $client_ip);

				$ip_ok = 0;
				foreach ($client_ip_arr as $ip_k => $ip_v)
				{
					if ($ip_v == $ip_address) $ip_ok++;
				}

				if ($ip_ok > 0)
				{
					$bizstory_auth = "Y";
				}
				else
				{
					$bizstory_auth = "N";
					$error_string  = '
						허용된 IP만 가능합니다.<br />
						현 IP는 ' . $ip_address . '입니다.<br /><br />
						담당자에게 문의하세요.<br />
						연락처 : ' . $comp_data['tel_num'] . '<br />';
				}
			}
		}
		else
		{
			$bizstory_auth = "N";
			$error_string  = '
				사용가능한 거래처가 아닙니다. <br /><br />
				담당자에게 문의하세요.<br />
				연락처 : ' . $comp_data['tel_num'] . '<br />';
		}
	}
	else
	{
		$bizstory_auth = "N";
		$error_string  = '
			잘못된 거래처코드이거나<br />
			등록된 거래처코드가 아닙니다. <br /><br />
			담당자에게 문의하세요.<br />
			연락처 : ' . $comp_data['tel_num'] . '<br />';
	}

///////////////////////////////////////////////////////////////////////////////////////////
	include $local_path . "/agent/include/agent_chk.php";

	if ($bizstory_view == "Y")
	{
		$_SESSION['agent_client_comp'] = $client_comp;
		$_SESSION['agent_client_idx']  = $client_idx;
		$_SESSION['agent_client_code'] = $client_code;
		$_SESSION['agent_client_name'] = $client_name;
		$_SESSION['agent_agent_type']  = $client_agent;
		$_SESSION['agent_macaddress']  = $macaddress;

		$_COOKIE['agent_client_comp'] = $client_comp;
		$_COOKIE['agent_client_idx']  = $client_idx;
		$_COOKIE['agent_client_code'] = $client_code;
		$_COOKIE['agent_client_name'] = $client_name;
		$_COOKIE['agent_agent_type']  = $client_agent;
		$_COOKIE['agent_macaddress']  = $macaddress;

		if ($client_agent == 'A') $url_str = 'agent_A.php';
		else if ($client_agent == 'B') $url_str = 'agent_B.php';
		else if ($client_agent == 'C') $url_str = 'agent_C.php';
		else $url_str = 'agent_A.php';
	}
	else
	{
		$url_str = 'agent_error.php';
	}
?>
<form id="agentform" name="agentform" method="post" action="<?=$url_str;?>">
	<input type="hidden" id="agent_client_comp"   name="client_comp"  value="<?=$client_comp;?>" />
	<input type="hidden" id="agent_client_idx"    name="client_idx"   value="<?=$client_idx;?>" />
	<input type="hidden" id="agent_client_code"   name="client_code"  value="<?=$client_code;?>" />
	<input type="hidden" id="agent_client_name"   name="client_name"  value="<?=$client_name;?>" />
	<input type="hidden" id="agent_client_agent"  name="client_agent" value="<?=$client_agent;?>" />
	<input type="hidden" id="agent_macaddress"    name="macaddress"   value="<?=$macaddress;?>" />
</form>
<script type="text/javascript">
//<![CDATA[
	document.agentform.submit();
//]]>
</script>
