<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];

// 메뉴단계구하기
	$depth_data = query_view("
		select max(menu_depth) as max_depth from code_receipt_class
		where del_yn = 'N' and comp_idx = '" . $code_comp . "' and part_idx = '" . $code_part . "' limit 1");
	if($depth_data["max_depth"] == "") $max_depth = 1;
	else $max_depth = $depth_data["max_depth"];

// 선택된 현 메뉴
	if ($field_value == '') // 값이 없을 경우 기본값이 나오도록 한다.
	{
		$default_where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.default_yn = 'Y'";
		$default_data = code_receipt_class_data("view", $default_where);

		$field_value = $default_data['code_idx'];
	}
	$where = " and code.code_idx = '" . $field_value . "'";
	$data = code_receipt_class_data("view", $where);

	$this_up_code_idx = $data["up_code_idx"] . "," . $data["code_idx"];
	$up_code_idx_str = explode(",", $this_up_code_idx);

	for($i = 1; $i <= $max_depth; $i++)
	{
?>
	<select id="<?=$field_id;?>_<?=$i;?>" name="<?=$field_name;?>_<?=$i;?>" <? if ($i != $menu_depth - 1) { ?>onchange="select_receipt_class('<?=$code_part;?>', '<?=$field_id;?>', '<?=$field_name;?>', this.value, '<?=$select_type;?>', '<?=$view_id;?>')"<? } ?>>
<?
		if ($select_type == 'select') echo '<option value="">', $i, '차 전체분류</option>';
		else echo '<option value="">', $i, '차 메뉴선택</option>';

		$where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.menu_depth = " . $i . "";
		if($i == 1) $where .= " and ifnull(code.up_code_idx, '') = ''";
		else
		{
			$ii = $i - 1;
			$where .= " and concat(code.up_code_idx, ',') like '%," . $up_code_idx_str[$ii] . ",%'";
		}
		$menu_list = code_receipt_class_data("list", $where, "", "", "");

		foreach($menu_list as $k => $menu_data)
		{
			if (is_array($menu_data))
			{
				if ($sel_depth == $i) $chk_idx = $field_value;
				else $chk_idx = $up_code_idx_str[$i];
?>
		<option value="<?=$menu_data["code_idx"];?>" <?=selected($chk_idx, $menu_data["code_idx"]);?>><?=$menu_data["code_name"];?></option>
<?
			}
		}
?>
	</select>
<?
	}
?>