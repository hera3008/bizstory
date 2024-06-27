<?
// 거래처정보
	if ($client_code == '')
	{
		if ($_SESSION['agent_client_code'] != '') $client_code = $_SESSION['agent_client_code'];
	}

	$client_where = " and ci.client_code = '" . $client_code . "'";
	$client_data = client_info_data('view', $client_where);

// 업체정보
	$company_where = " and comp.comp_idx = '" . $client_data['comp_idx'] . "'";
	$company_data = company_info_data('view', $company_where);

	$comp_set_where = " and cs.comp_idx = '" . $client_data['comp_idx'] . "'";
	$comp_set_data = company_set_data('view', $comp_set_where);

	$client_comp    = $client_data['comp_idx'];
	$client_part    = $client_data['part_idx'];
	$client_idx     = $client_data['ci_idx'];
	$client_name    = $client_data['client_name'];
	$client_code    = $client_data['client_code'];
	$client_agent   = $client_data['agent_type'];
	$client_ccg_idx = $client_data['ccg_idx'];

	if ($client_agent == 'B' || $client_agent == 'C') {}
	else $client_agent = 'A';

	$receipt_path = $comp_path . '/' . $client_comp . '/receipt'; // 접수
	$receipt_dir  = $comp_dir  . '/' . $client_comp . '/receipt';

	$banner_path = $comp_path . '/' . $client_comp . '/banner'; // 배너
	$banner_dir  = $comp_dir  . '/' . $client_comp . '/banner';

	$company_path = $comp_path . '/' . $client_comp . '/company'; // 업체
	$company_dir  = $comp_dir  . '/' . $client_comp . '/company';

	$bnotice_path = $comp_path . '/' . $client_comp . '/bnotice'; // 알림게시판
	$bnotice_dir  = $comp_dir  . '/' . $client_comp . '/bnotice';

	$staff_path = $comp_path . '/' . $client_comp . '/member'; // 직원
	$staff_dir  = $comp_dir  . '/' . $client_comp . '/member';

	$consult_path = $comp_path . '/' . $client_comp . '/consult'; // 상담
	$consult_dir  = $comp_dir  . '/' . $client_comp . '/consult';

	$tbanner_path = $local_path . '/data/banner'; // 총관리 배너
	$tbanner_dir  = $local_dir  . '/data/banner';

	$upload_file_num_max   = $comp_set_data['receipt_file_num']; // 최대파일수
	$upload_file_size_max1 = $comp_set_data['receipt_file_max'] * 1024 * 1024; // 최대파일크기
	$upload_file_size_max2 = $comp_set_data['receipt_file_max'];

	if ($macaddress == '') $macaddress = $_SESSION['agent_macaddress'];

	if ($_SESSION['agent_client_idx'] == '')
	{
		$_SESSION['agent_client_comp']  = $client_comp;
		$_SESSION['agent_client_part']  = $client_part;
		$_SESSION['agent_client_idx']   = $client_idx;
		$_SESSION['agent_client_code']  = $client_code;
		$_SESSION['agent_client_name']  = $client_name;
		$_SESSION['agent_client_agent'] = $client_agent;
		$_SESSION['agent_macaddress']   = $macaddress;
	}
	else
	{
		$_SESSION['agent_client_comp']  = $client_comp;
		$_SESSION['agent_client_part']  = $client_part;
		$_SESSION['agent_client_idx']   = $client_idx;
		$_SESSION['agent_client_code']  = $client_code;
		$_SESSION['agent_client_name']  = $client_name;
		$_SESSION['agent_client_agent'] = $client_agent;
		$_SESSION['agent_macaddress']   = $macaddress;
	}

// 로고파일
	$company_where = " and cf.comp_idx = '" . $client_comp . "' and cf.sort = '1'";
	$logo_data = company_file_data('view', $company_where);

	if ($logo_data['total_num'] == 0) $comp_logo_img = '';
	else $comp_logo_img = '<img src="' . $company_dir . '/' . $logo_data['img_sname'] . '" width="180px" height="50px" alt="' . $company_data['comp_name'] . '" />';

///////////////////////////////////////////////////////////////////////////////
	$bizstory_view = "N";
// 거래처등록여부
	if ($_SESSION['agent_client_comp'] == 0)
	{
		$bizstory_view = "N";
		$error_string  = '
			거래처코드값이 없습니다.<br />
			이 페이지를 사용할 수 없습니다.';
	}
	else
	{
		if ($client_data['total_num'] > 0)
		{
		// 사용여부
			if ($client_data['view_yn'] == 'Y')
			{
			// IP허용여부
				if ($client_data['ip_yn'] == 'N')
				{
					$bizstory_view = "Y";
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
						$bizstory_view = "Y";
					}
					else
					{
						$bizstory_view = "N";
						$error_string  = '
							허용된 IP만 가능합니다.<br />
							현 IP는 ' . $ip_address . '입니다.<br /><br />
							담당자에게 문의하세요.<br />
							연락처 : ' . $company_data['tel_num'] . '<br />';
					}
				}
			}
			else
			{
				$bizstory_view = "N";
				$error_string  = '
					사용가능한 거래처가 아닙니다. <br /><br />
					담당자에게 문의하세요.<br />
					연락처 : ' . $company_data['tel_num'] . '<br />';
			}
		}
		else
		{
			$bizstory_view = "N";
			$error_string  = '
				잘못된 거래처코드이거나<br />
				등록된 거래처코드가 아닙니다. <br /><br />
				담당자에게 문의하세요.<br />
				연락처 : ' . $company_data['tel_num'] . '<br />';
		}
	}
?>