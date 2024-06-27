<?
/*
	생성 : 2012.11.02
	위치 : 설정폴더 > 업체관리 > 업체분류 - 상위
*/
	require_once "../common/setting.php";
	require_once "../common/member_chk.php";
	require_once "../common/no_direct.php";

	$where = " and code.code_idx = '" . $code_idx . "'";
	$data = company_class_data("view", $where);

	$this_up_code_idx = $data["up_code_idx"] . "," . $data["code_idx"];
	$up_code_idx_str = explode(",", $this_up_code_idx);

	if ($menu_depth == 1)
	{
		echo '선택한 상위분류가 없습니다.';
	}
	else
	{
		for($i = 1; $i < $menu_depth; $i++)
		{
?>
	<select name="param[menu<?=$i;?>]" id="chk_menu<?=$i;?>" <? if ($i != $menu_depth - 1) { ?>onchange="down_menu_change(<?=$menu_depth;?>, <?=$i;?>, this.value)"<? } ?>>
		<option value=""><?=$i;?>차 분류선택</option>
<?
			if($i == 1)
			{
				$where = " and code.menu_depth = 1 and ifnull(code.up_code_idx, '') = ''";
				$menu_list = company_class_data("list", $where, "", "", "");
			}
			else
			{
				$ii = $i - 1;
				$where = " and code.menu_depth = " . $i . " and concat(code.up_code_idx, ',') like '%," . $up_code_idx_str[$ii] . ",%'";
				$menu_list = company_class_data("list", $where, "", "", "");
			}

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