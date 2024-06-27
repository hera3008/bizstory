<?
/*
	생성 : 2013.01.29
	수정 : 2013.01.29
	위치 : 파일센터 > 타입설정 - 상위
*/
	require_once "../common/setting.php";
	require_once "../common/member_chk.php";
	require_once "../common/no_direct.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];

	$where = " and code.code_idx = '" . $code_idx . "'";
	$data = filecenter_code_type_data("view", $where);

	$this_up_code_idx = $data["up_code_idx"] . "," . $data["code_idx"];
	$up_code_idx_str = explode(",", $this_up_code_idx);

	if ($menu_depth == 1)
	{
		echo '선택한 상위가 없습니다.';
	}
	else
	{
		for($i = 1; $i < $menu_depth; $i++)
		{
			$where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.menu_depth = '" . $i . "'";
?>
	<select name="param[menu<?=$i;?>]" id="chk_menu<?=$i;?>" <? if ($i != $menu_depth - 1) { ?>onchange="down_change(<?=$menu_depth;?>, <?=$i;?>, this.value)"<? } ?>>
		<option value=""><?=$i;?>차 선택</option>
<?
			if($i == 1)
			{
				$where .= " and ifnull(code.up_code_idx, '') = ''";
			}
			else
			{
				$ii = $i - 1;
				$where .= " and concat(code.up_code_idx, ',') like '%," . $up_code_idx_str[$ii] . ",%'";
			}
			$menu_list = filecenter_code_type_data("list", $where, "", "", "");

			foreach($menu_list as $k => $menu_data)
			{
				if (is_array($menu_data))
				{
					if ($sel_depth == $i) $chk_idx = $code_idx;
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
	}
?>