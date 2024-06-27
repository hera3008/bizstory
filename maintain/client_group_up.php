<?
/*
	위치 : 총관리자 > 거래처분류 > 거래처분류 - 상위분류
    생성 : 2024.03.18 김소령
    내용 : 교육기관 등록시 거래처 분류 자동 생성을 위한 기초 값 설정 
*/
	require_once "../common/setting.php";
	require_once "../common/member_chk.php";
	require_once "../common/no_direct.php";

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
    <select name="param[menu<?=$i;?>]" id="chk_menu<?=$i;?>" title="<?=$i?>차 분류를 선택해 주세요"
		data-hide-search="true" data-placeholder="<?=$i?>차 분류를 선택해 주세요" aria-label="<?=$i?>차 분류를 선택해 주세요" class="form-select form-select-sm mb-2"
		<? if ($i != $menu_depth - 1) { ?>onchange="down_menu_change(<?=$menu_depth;?>, <?=$i;?>, this.value)"<? } ?>>
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
		<option value="<?=$menu_data["ccg_idx"];?>" data-group-code="<?=$menu_data["group_code"];?>" <?=selected($chk_idx, $menu_data["ccg_idx"]);?>><?=$menu_data["group_name"];?></option>
<?
				}
			}
?>
	</select>
<?
		}
	}
?>