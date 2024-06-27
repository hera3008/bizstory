<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	if ($rr_idx == '')
	{
		$where = " and ci.ci_idx = '" . $ci_idx . "'";
		$data = client_info_data("view", $where);

	// 거래처정보
		$chk_ci_idx      = $data['ci_idx'];
		$chk_client_name = $data['client_name'];
		$client_tel_num  = $data['tel_num'];

		$charge_info = $data['charge_info'];
		$charge_info_arr = explode('||', $charge_info);
		if (is_array($charge_info_arr))
		{
			$info_str = explode('/', $charge_info_arr[0]);

			$chk_client_charge  = $info_str[0];
			$chk_client_telnum  = $info_str[1];
			$chk_client_telnum2 = $info_str[3];
			$chk_client_email   = $info_str[2];

			if ($chk_client_telnum == '') $chk_client_telnum = $client_tel_num;
			if ($chk_client_telnum == '') $chk_client_telnum = $chk_client_telnum2;
			if ($chk_client_telnum == '') $chk_client_hpnum = '';

			if ($chk_client_telnum != '')
			{
				$chk_num = substr($chk_client_telnum, 0, 3);
				if ($chk_num == '010' || $chk_num == '016' || $chk_num == '017' || $chk_num == '018' || $chk_num == '019')
				{
					$chk_client_hpnum = $chk_client_telnum;
				}
				else
				{
					$chk_num = substr($chk_client_telnum2, 0, 3);
					if ($chk_num == '010' || $chk_num == '016' || $chk_num == '017' || $chk_num == '018' || $chk_num == '019')
					{
						$chk_client_hpnum = $chk_client_telnum2;
					}
				}
			}
		}

		$address = $data['address'];
		$address = str_replace('||', ' ', $address);
		$chk_client_address = $address;

	// 점검자정보
		$chk_report_charge = $data['mem_name'];
		$chk_report_email  = $data['charge_email'];
		$chk_part_idx      = $data['part_idx'];

		$part_where = " and part.part_idx = '" . $chk_part_idx . "'";
		$part_data  = company_part_data('view', $part_where);

		$part_tel_num = $part_data['tel_num']; // 점검자전화번호 - 회사전화번호
		$tel_num_str = substr($part_tel_num, 0, 1);
		if ($part_tel_num == '-' || $part_tel_num == '--')
		{
			$chk_report_telnum = '';
		}
		else if ($tel_num_str == '-')
		{
			$chk_report_telnum = substr($part_tel_num, 1, strlen($part_tel_num));
		}
		else
		{
			$chk_report_telnum = $part_tel_num;
		}
	}
	else
	{
		$where = " and rr.rr_idx = '" . $rr_idx . "'";
		$data = receipt_report_data('view', $where);

		$chk_ci_idx         = $data['ci_idx'];
		$chk_client_name    = $data['client_name'];
		$chk_client_charge  = $data['client_charge'];
		$chk_client_telnum  = $data['client_telnum'];
		$chk_client_hpnum   = $data['client_hpnum'];
		$chk_client_email   = $data['client_email'];
		$chk_client_address = $data['client_address'];
		$chk_report_charge  = $data['report_charge'];
		$chk_report_telnum  = $data['report_telnum'];
		$chk_report_email   = $data['report_email'];
	}

	$str = '{
	  "ci_idx"         : "' . $chk_ci_idx . '"
	, "client_name"    : "' . $chk_client_name . '"
	, "client_charge"  : "' . $chk_client_charge . '"
	, "client_telnum"  : "' . $chk_client_telnum . '"
	, "client_hpnum"   : "' . $chk_client_hpnum . '"
	, "client_email"   : "' . $chk_client_email . '"
	, "client_address" : "' . $chk_client_address . '"
	, "report_charge"  : "' . $chk_report_charge . '"
	, "report_telnum"  : "' . $chk_report_telnum . '"
	, "report_email"   : "' . $chk_report_email . '"
}';
	echo $str;
?>