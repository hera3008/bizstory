<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 거래처관리 > 거래처분류 - 상위분류
*/
	require_once "../common/setting.php";
	require_once "../common/member_chk.php";
	require_once "../common/no_direct.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];

	$where = " and ccg.ccg_idx = '" . $ccg_idx . "'";
	$data = company_client_group_data("view", $where);

	$this_up_ccg_idx = $data["up_ccg_idx"] . "," . $data["ccg_idx"];
	$up_ccg_idx_str = explode(",", $this_up_ccg_idx);

	if ($menu_depth == 1)
	{
		echo '선택한 상위메뉴가 없습니다.';
	}
	else
	{
		for($i = 1; $i < $menu_depth; $i++)
		{
?>
	<select name="param[menu<?=$i;?>]" id="chk_menu<?=$i;?>" <? if ($i != $menu_depth - 1) { ?>onchange="down_menu_change(<?=$menu_depth;?>, <?=$i;?>, this.value)"<? } ?>>
		<option value=""><?=$i;?>차 메뉴선택</option>
<?
			if($i == 1)
			{
				$where = " and ccg.comp_idx = '" . $code_comp . "' and ccg.part_idx = '" . $code_part . "' and ccg.menu_depth = 1 and ifnull(ccg.up_ccg_idx, '') = ''";
				$menu_list = company_client_group_data("list", $where, "", "", "");
			}
			else
			{
				$ii = $i - 1;
				$where = " and ccg.comp_idx = '" . $code_comp . "' and ccg.part_idx = '" . $code_part . "' and ccg.menu_depth = " . $i . " and concat(ccg.up_ccg_idx, ',') like '%," . $up_ccg_idx_str[$ii] . ",%'";
				$menu_list = company_client_group_data("list", $where, "", "", "");
			}

			foreach($menu_list as $k => $menu_data)
			{
				if (is_array($menu_data))
				{
					if ($sel_depth == $i) $chk_idx = $ccg_idx;
					else $chk_idx = $up_ccg_idx_str[$i];
?>
		<option value="<?=$menu_data["ccg_idx"];?>" <?=selected($chk_idx, $menu_data["ccg_idx"]);?>><?=$menu_data["group_name"];?></option>
<?
				}
			}
?>
	</select>
<?
		}
	}
?>