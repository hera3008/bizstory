<?
/*
	생성 : 2013.02.05
	수정 : 2013.02.05
	위치 : 업무폴더 > 프로젝트관리 - 보기 - 업무 - 등록/수정폼 - 승인자지정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$set_part_yn      = $comp_set_data['part_yn'];
	$set_part_work_yn = $comp_set_data['part_work_yn'];

	$project_class_where = " and proc.proc_idx = '" . $proc_idx . "'";
	$project_class_data = project_class_data('view', $project_class_where);

	$charge_idx         = $project_class_data['charge_idx'];
	$project_charge_arr = explode(',', $charge_idx);
?>
ㆍ승인자 지정
<select name="chk_apply_idx" id="workchk_apply_idx" title="승인자 지정을 하세요." onchange="popup_apply_select()">
	<option value="">승인자 지정을 하세요.</option>
<?
	foreach ($project_charge_arr as $k => $v)
	{
		$mem_where = " and mem.mem_idx = '" . $v . "'";
		$mem_data = member_info_data('view', $mem_where);
?>
	<option value="<?=$mem_data['mem_idx'];?>" <?=selected($mem_data['mem_idx'], $apply_idx);?>>[<?=$mem_data['part_name'];?>] <?=$mem_data['mem_name'];?></option>
<?
	}
?>
</select>

<script type="text/javascript">
//<![CDATA[
// 담당자 - 선택
	function popup_apply_select()
	{
		var apply_idx = $('#workchk_apply_idx').val();
		$('#workpost_apply_idx').val(apply_idx);
		charge_member_list('<?=$work_type;?>');
	}
//]]>
</script>