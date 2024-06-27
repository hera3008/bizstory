<?
/*
	수정 : 2012.11.01
	수정 : 2013.03.22
	위치 : 설정관리 > 코드관리 > 회계설정 > 회계계정 - 상위
*/
	require_once "../common/setting.php";
	require_once "../common/member_chk.php";
	$page_chk = 'html';
	require_once "../common/no_direct.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$where = " and code.code_idx = '" . $code_idx . "'";
	$data = code_account_class_data("view", $where);

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
?>
	<select name="param[menu<?=$i;?>]" id="chk_menu<?=$i;?>" <? if ($i != $menu_depth - 1) { ?>onchange="down_menu_change(<?=$menu_depth;?>, <?=$i;?>, this.value)"<? } ?>>
		<option value=""><?=$i;?>차 선택</option>
<?
			if($i == 1)
			{
				$where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.menu_depth = 1 and ifnull(code.up_code_idx, '') = ''";
				$menu_list = code_account_class_data("list", $where, "", "", "");
			}
			else
			{
				$ii = $i - 1;
				$where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.menu_depth = " . $i . " and concat(code.up_code_idx, ',') like '%," . $up_code_idx_str[$ii] . ",%'";
				$menu_list = code_account_class_data("list", $where, "", "", "");
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