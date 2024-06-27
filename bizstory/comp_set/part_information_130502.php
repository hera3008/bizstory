<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";

	$part_where = "and part.part_idx = '" . $code_part . "'";
	$part_data = company_part_data('view', $part_where);
	$code_comp = $part_data['comp_idx'];

	$part_info = new part_information();
	$part_info->code_comp = $code_comp;
	$part_info->code_part = $code_part;
	$part_info->shsgroup = $shsgroup;

	if ($select_class == 'client_info') // �ŷ�ó
	{
		$data_list = $part_info->part_client_info();
		$data_key   = 'ci_idx';
		$data_value = 'client_name';
	}
	else if ($select_class == 'client_group') // �ŷ�ó�з�
	{
		$data_list = $part_info->part_client_group();
		$data_key   = 'ccg_idx';
		$data_value = 'group_name';
	}
	else if ($select_class == 'receipt_status') // ��������
	{
		$data_list = $part_info->part_receipt_status();
		$data_key   = 'code_value';
		$data_value = 'code_name';
	}
	else if ($select_class == 'receipt_class') // ��������
	{
		$data_list = $part_info->part_receipt_class();
		$data_key   = 'code_idx';
		$data_value = 'code_name';
	}
	else if ($select_class == 'staff_group') // �����׷�
	{
		$data_list = $part_info->part_staff_group();
		$data_key   = 'csg_idx';
		$data_value = 'group_name';
	}
	else if ($select_class == 'part_duty') // ��å
	{
		$data_list = $part_info->part_duty();
		$data_key   = 'cpd_idx';
		$data_value = 'duty_name';
	}
	else if ($select_class == 'staff_info') // ����
	{
		$data_list = $part_info->part_staff_info();
		$data_key   = 'mem_idx';
		$data_value = 'mem_name';
	}
	else if ($select_class == 'staff_info_group') // �����׷캰 ����
	{
		$data_list = $part_info->part_staff_info_group();
		$data_key   = 'mem_idx';
		$data_value = 'mem_name';
	}
	else if ($select_class == 'work_status') // ��������
	{
		$data_list = $part_info->part_work_status();
		$data_key   = 'code_value';
		$data_value = 'code_name';
	}
	else if ($select_class == 'work_class') // ��������
	{
		$data_list = $part_info->part_work_class();
		$data_key   = 'code_idx';
		$data_value = 'code_name';
	}
	else if ($select_class == 'consult_class') // ���з�
	{
		$data_list = $part_info->part_consult_class();
		$data_key   = 'code_idx';
		$data_value = 'code_name';
	}
	else if ($select_class == 'bnotice_class') // �˸��з�
	{
		$data_list = $part_info->part_bnotice_class();
		$data_key   = 'code_idx';
		$data_value = 'code_name';
	}
	else if ($select_class == 'account_class') // ��������
	{
		$data_list = $part_info->part_account_class();
		$data_key    = 'code_idx';
		$data_value  = 'code_name';
		$data_value2 = 'code_value';
	}
	else if ($select_class == 'account_gubun') // ����
	{
		$data_list = $part_info->part_account_gubun();
		$data_key   = 'code_value';
		$data_value = 'code_name';
	}
	else if ($select_class == 'account_bank') // �������
	{
		$data_list = $part_info->part_account_bank();
		$data_key    = 'code_idx';
		$data_value  = 'code_name';
		$data_value2 = 'bank_num';
	}
	else if ($select_class == 'account_card') // ī�����
	{
		$data_list = $part_info->part_account_card();
		$data_key    = 'code_idx';
		$data_value  = 'code_name';
		$data_value2 = 'card_num';
	}
	else if ($select_class == 'report_class') // ���˺�����Ÿ��
	{
		$data_list = $part_info->part_report_class();
		$data_key   = 'code_idx';
		$data_value = 'code_name';
	}







	else if ($select_class == 'pro_idx') // ������Ʈ
	{
		$data_list = $part_info->project_info();
		$data_key   = 'pro_idx';
		$data_value = 'subject';
	}
	else if ($select_class == 'sche_class') // ��������
	{
		$data_list = $part_info->part_sche_class();
		$data_key   = 'code_idx';
		$data_value = 'code_name';
	}
	else if ($select_class == 'dili_status') // ��ٺλ���
	{
		$data_list = $part_info->part_dili_status();
		$data_key   = 'code_idx';
		$data_value = 'code_name';
	}
	else if ($select_class == 'dili_status2') // ��ٺλ���2
	{
		$data_list = $part_info->part_dili_status2();
		$data_key   = 'code_idx';
		$data_value = 'code_name';
	}

// select Data
	if ($data_list['total_num'] > 0)
	{
		$json_str = '{
	"success_chk":"Y",
	"result_data":
		[';

		if ($select_class == 'receipt_status') // ���������� ���
		{
				$json_str .= '
			{
				"idx":"end_no",
				"name":"' . han_utf('��ó��') . '",
				"selected":"' . $selected . '",
				"menu_dpeth":1
			},';
		}

		$num_chk = 1;
		foreach ($data_list as $k => $data_data)
		{
			if (is_array($data_data))
			{
				if ($data_data['menu_depth'] == '') $data_data['menu_depth'] = 0;

				$selected = 'N';
				if ($field_value == $data_data[$data_key])
				{
					$selected = 'Y';
				}
				else if ($data_data['default_yn'] == 'Y' && $select_type != 'select')
				{
					$selected = 'Y';
				}

				if ($data_value2 != '')
				{
					if ($data_data[$data_value2] != '')
					{
						$chk_value = $data_data[$data_value] . '(' . $data_data[$data_value2] . ')';
					}
					else
					{
						$chk_value = $data_data[$data_value];
					}
				}
				else
				{
					$chk_value = $data_data[$data_value];
				}

				$json_str .= '
			{
				"idx":"' . $data_data[$data_key] . '",
				"name":"' . $chk_value . '",
				"selected":"' . $selected . '",
				"menu_dpeth":' . $data_data['menu_depth'] . '
			}';
				if ($num_chk != $data_list['total_num'])
				{
					$json_str .= ',';
				}
				$num_chk++;
			}
		}
		$json_str .= '
		]
}';
	}
	else
	{
		$json_str = '{"success_chk":"N", "result_data":""}';
	}

	echo $json_str;
?>