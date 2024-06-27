<?
/*
	생성 : 2013.05.21
	수정 : 2013.05.21
	위치 : 업무관리 > 나의 업무 > 업무
*/
	require_once "../bizstory/common/setting.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = $_SESSION[$sess_str . '_part_idx'];
?>
<div class="sub_layout_box">
<?
	$navi_where = " and mi.mode_folder = '" . $fmode . "' and mi.mode_file = '" . $smode . "'";
	$navi_data = menu_info_data("view", $navi_where);

// 업체별로 메뉴명 가지고 오기
	$sub_where = " and mc.comp_idx = '" . $code_comp . "' and mc.part_idx = '" . $code_part . "' and mc.mi_idx = '" . $navi_data['mi_idx'] . "'";
	$sub_data = menu_company_data('view', $sub_where);

	$navi_name = $sub_data['menu_name'];
	if ($navi_name == '') $navi_name = $navi_data['menu_name'];
?>
	<div class="home_pagenavi">
		<h2>
			<?=$navi_name;?>
		</h2>
	</div>
</div>
work_work