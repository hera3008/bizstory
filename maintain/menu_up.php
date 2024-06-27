<?
/*
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 설정관리 > 메뉴관리 - 상위
*/
	require_once "../common/setting.php";
	require_once "../common/member_chk.php";
	require_once "../common/no_direct.php";

	$where = " and mi.mi_idx = '" . $mi_idx . "'";
	$data = menu_info_data("view", $where);

	$this_up_mi_idx = $data["up_mi_idx"] . "," . $data["mi_idx"];
	$up_mi_idx_str = explode(",", $this_up_mi_idx);

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
				$where = " and mi.menu_depth = 1 and ifnull(mi.up_mi_idx, '') = ''";
				$menu_list = menu_info_data("list", $where, "", "", "");
			}
			else
			{
				$ii = $i - 1;
				$where = " and mi.menu_depth = " . $i . " and concat(mi.up_mi_idx, ',') like '%," . $up_mi_idx_str[$ii] . ",%'";
				$menu_list = menu_info_data("list", $where, "", "", "");
			}

			foreach($menu_list as $k => $menu_data)
			{
				if (is_array($menu_data))
				{
					if ($sel_depth == $i) $chk_idx = $mi_idx;
					else $chk_idx = $up_mi_idx_str[$i];
?>
		<option value="<?=$menu_data["mi_idx"];?>" <?=selected($chk_idx, $menu_data["mi_idx"]);?>><?=$menu_data["menu_name"];?></option>
<?
				}
			}
?>
	</select>
<?
		}
	}
?>