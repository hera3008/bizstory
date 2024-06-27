<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$where = " and mac.comp_idx = '" . $code_comp . "' and mac.view_yn = 'Y'";
	$orderby = "mi.sort asc";
	$list = menu_auth_company_data('list', $where, $orderby, '', '');
?>
<div class="info_text">
	<ul>
		<li>설정을 하시면 직원등록시 기본으로 메뉴가 설정이 됩니다.</li>
	</ul>
</div>

<table class="tinytable view">
	<colgroup>
		<col width="80px" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>기본값</h3></th>
			<th class="nosort"><h3>메뉴명</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="2">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		$i = 1;
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
			// 기본값
				$default_where = " and mc.comp_idx = '" . $data['comp_idx'] . "' and mc.part_idx = '" . $code_part . "' and mc.mi_idx = '" . $data['mi_idx'] . "'";
				$default_data = menu_company_data('view', $default_where);
				if ($default_data['total_num'] == 0)
				{
					$default_data['default_yn'] = $data['default_yn'];
				}

				if ($auth_menu['mod'] == "Y")
				{
					$btn_default = "check_code_data('check_yn', 'default_yn', '" . $data['mi_idx'] . "', '" . $default_data["default_yn"] . "')";
				}
				else
				{
					$btn_default = "check_auth_popup('modify')";
				}

				$sub_where = " and mc.comp_idx = '" . $data['comp_idx'] . "' and mc.part_idx = '" . $code_part . "' and mc.mi_idx = '" . $data['mi_idx'] . "'";
				$sub_data = menu_company_data('view', $sub_where);

				$menu_name = $sub_data['menu_name'];
				if ($menu_name == '') $menu_name = $data['menu_name'];
?>
		<tr>
			<td><img src="bizstory/images/icon/<?=$default_data['default_yn'];?>.gif" alt="<?=$default_data['default_yn'];?>" class="pointer" onclick="<?=$btn_default;?>" /></td>
			<td>
				<div class="left depth_<?=$data["menu_depth"];?>"><?=$menu_name;?></div>
			</td>
		</tr>
<?
				$i++;
				unset($default_data);
				unset($sub_data);
			}
		}
	}
	unset($data);
	unset($list);
?>
	</tbody>
</table>