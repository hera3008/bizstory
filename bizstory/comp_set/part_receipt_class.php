<?
	require_once "../common/setting.php";
	//require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "'";
	$menu_list = code_receipt_class_data('list', $where, '', '', '');
	foreach($menu_list as $k => $menu_data)
	{
		if (is_array($menu_data))
		{
			$menu_depth = $menu_data["menu_depth"];
			$up_code_idx = $menu_data["up_code_idx"];
			if ($up_code_idx == '') $up_code_idx = 0;
			$sort = $menu_data["sort"];

			$result_data[$menu_depth][$up_code_idx][$sort]['code_idx']    = $menu_data["code_idx"];
			$result_data[$menu_depth][$up_code_idx][$sort]['up_code_idx'] = $menu_data["up_code_idx"];
			$result_data[$menu_depth][$up_code_idx][$sort]['code_name']   = $menu_data["code_name"];

			$result_list[$menu_depth][$up_code_idx]['code_idx'][]    = $menu_data["code_idx"];
			$result_list[$menu_depth][$up_code_idx]['up_code_idx'][] = $menu_data["up_code_idx"];
			$result_list[$menu_depth][$up_code_idx]['code_name'][]   = $menu_data["code_name"];
		}
	}

	echo '<pre>';
	//echo print_r($result_data);
	echo '</pre>';

	echo '<pre>';
	//echo print_r($result_list);
	echo '</pre>';

	if (is_array($result_data))
	{
		$json_str = '{
	"success_chk":"Y",
	"result_data":
		[';
		foreach($result_data as $k => $v)
		{
			//echo $v, '<br />';
		}
		$json_str .= '
		]
}';
	}

	echo '<pre>';
	echo $json_str;
	echo '</pre>';






	$depth_data = query_view("
		select max(menu_depth) as max_depth
		from code_receipt_class
		where del_yn = 'N' and comp_idx = '" . $code_comp . "' and part_idx = '" . $code_part . "'");
	if($depth_data["max_depth"] == "") $max_depth = 1;
	else $max_depth = $depth_data["max_depth"];

	echo 'max_depth -> ' . $max_depth . '<br />';

	for($i = 1; $i <= $max_depth; $i++)
	{
	echo 'up_code_idx -> ' . $up_code_idx . '<br />';
		$up_code_idx_str = explode(",", $up_code_idx);

		$where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.menu_depth = '" . $i . "'";
		if($i > 1)
		{
			$ii = $i - 1;
			$where .= " and code.up_code_idx = '" . $up_code_idx . "'";
		}
		echo 'where -> ', $where, '<br />';
		$menu_list = code_receipt_class_data('list', $where, '', '', '');

		$menu_chk = 1;
		foreach($menu_list as $k => $menu_data)
		{
			if (is_array($menu_data))
			{
				if ($sel_depth == $i) $chk_idx = $code_idx;
				else $chk_idx = $up_code_idx_str[$i];

				$result_data[$i][$menu_chk]['code_idx']    = $menu_data["code_idx"];
				$result_data[$i][$menu_chk]['up_code_idx'] = $menu_data["up_code_idx"];
				$result_data[$i][$menu_chk]['code_name']   = $menu_data["code_name"];

				$up_code_idx = $menu_data["up_code_idx"] . "," . $menu_data["code_idx"];
				$menu_chk++;
			}
		}
	}

	echo '<pre>';
	echo print_r($result_data);
	echo '</pre>';

	$part_info = new part_information();
	$part_info->code_comp = $code_comp;
	$part_info->code_part = $code_part;
	$data_list = $part_info->part_receipt_class();

	if ($data_list['total_num'] > 0)
	{
		$json_str = '{
	"success_chk":"Y",
	"result_data":
		[';

		$num_chk = 1;
		foreach ($data_list as $k => $data_data)
		{
			if (is_array($data_data))
			{
				$selected = selected($field_value, $data_data['code_idx']);

				$json_str .= '
			{
				"idx"         : "' . $data_data['code_idx'] . '",
				"name"        : "' . $data_data['code_name'] . '",
				"selected"    : "' . $selected . '",
				"menu_dpeth"  : ' . $data_data['menu_depth'] . ',
				"menu_num"    : ' . $data_data['menu_num'] . '';

				if ($data_data['menu_num'] > 0)
				{
					$json_str .= ',
				"result_data" : [

				]';
				}
				else
				{
					$json_str .= ',
				"result_data" : ""';
				}

				$json_str .= '
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
		$json_str = '{"success_chk":"N", "result_data":"", "selected":""}';
	}

	echo '<pre>';
	echo $json_str;
	echo '</pre>';
?>