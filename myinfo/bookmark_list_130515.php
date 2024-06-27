<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$where = " and mam.comp_idx = '" . $code_comp . "' and mam.mem_idx = '" . $code_mem . "'
		and mac.view_yn = 'Y'
		and (mam.yn_list = 'Y' or mam.yn_int = 'Y' or mam.yn_mod = 'Y' or mam.yn_del = 'Y' or mam.yn_view = 'Y' or mam.yn_print = 'Y' or mam.yn_down = 'Y')";
	$orderby = "mi.sort asc";
	$list = menu_auth_member_data('list', $where, $orderby, '', '');
?>
<table class="tinytable view">
	<colgroup>
		<col width="80px" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>즐겨찾기</h3></th>
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
			// 메뉴명
				$menu_where = " and mc.comp_idx = '" . $data['comp_idx'] . "' and mc.part_idx = '" . $data['part_idx'] . "' and mc.mi_idx = '" . $data['mi_idx'] . "'";
				$menu_data = menu_company_data('view', $menu_where);

				$menu_name = $menu_data['menu_name'];
				if ($menu_name == '') $menu_name = $data['menu_name'];

			// 즐겨찾기
				$book_where = " and mb.comp_idx = '" . $data['comp_idx'] . "' and mb.mi_idx = '" . $data['mi_idx'] . "' and mb.mem_idx = '" . $code_mem . "'";
				$book_data = member_bookmark_data('view', $book_where);

				$view_yn = $book_data["view_yn"];
				if ($view_yn == '') $view_yn = 'N';

				if ($auth_menu['mod'] == "Y")
				{
					$btn_view = "check_code_data('check_yn', 'view_yn', '" . $data['mi_idx'] . "', '" . $view_yn . "')";
				}
				else
				{
					$btn_view = "check_auth_popup('modify')";
				}

				if ($view_yn == 'Y') $menu_name = '<strong style="font-size:15px;color:#3300ff;">' . $menu_name . '</strong>';

				unset($menu_data);
				unset($book_data);
?>
		<tr>
			<td>
	<?
		if ($data['menu_num'] == 0)
		{
	?>
				<img src="bizstory/images/icon/<?=$view_yn;?>.gif" alt="<?=$view_yn;?>" class="pointer" onclick="<?=$btn_view;?>" />
	<?
		}
	?>
			</td>
			<td>
				<div class="left depth_<?=$data["menu_depth"];?>"><?=$menu_name;?></div>
			</td>
		</tr>
<?
				$i++;
			}
		}
	}

	unset($data);
	unset($list);
?>
	</tbody>
</table>