<?
/*
	생성 : 2012.05.08
	수정 : 2012.08.08
	위치 : 업무폴더 > 나의업무 > 업무 - 승인자지정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$set_part_yn = $comp_set_data['part_yn'];
	$set_part_work_yn = $comp_set_data['part_work_yn'];

	$charge_idx_arr = explode(',', $charge_idx);
	$charge_where = '';
	foreach ($charge_idx_arr as $charge_k => $charge_v)
	{
		$charge_where .= " and mem.mem_idx != '" . $charge_v . "'";
	}

	if ($wi_idx == '')
	{
		$disabled = '';
	}
	else
	{
		if ($old_work_type != 'WT03')
		{
			$disabled = '';
		}
		else
		{
			$disabled = ' disabled="disabled"';
		}
	}
?>
ㆍ승인자 지정
<?
	if ($disabled == '')
	{
?>
<select name="chk_apply_idx" id="chk_apply_idx" title="승인자 지정을 하세요." onchange="popup_apply_select()">
<?
	}
	else
	{
?>
<input type="hidden" name="chk_apply_idx" id="chk_apply_idx" title="승인자 지정을 하세요." value="<?=$apply_idx;?>" />
<select name="chk_apply_idx1" id="chk_apply_idx1" title="승인자 지정을 하세요." onchange="popup_apply_select()"<?=$disabled;?>>
<?
	}
?>
	<option value="">승인자 지정을 하세요.</option>
<?
// 지사별
	$sub_where = " and part.comp_idx = '" . $code_comp . "' and part.view_yn = 'Y'";
	if ($set_part_work_yn == 'Y')
	{ }
	else
	{
		if ($set_part_yn == 'N') $sub_where .= " and part.part_idx = '" . $code_part . "'";
	}
	$part_list = company_part_data('list', $sub_where, '', '', '');
	foreach ($part_list as $part_k => $part_data)
	{
		if (is_array($part_data))
		{
?>
	<option value=""><?=$part_data['part_name'];?></option>
<?
		// 지사별 직원
			$sub_where2 = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $part_data['part_idx'] . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
			$sub_order2 = "cpd.sort asc, mem.mem_name asc";
			$mem_list = member_info_data('list', $sub_where2, $sub_order2, '', '');
			foreach ($mem_list as $mem_k => $mem_data)
			{
				if (is_array($mem_data))
				{
?>
	<option value="<?=$mem_data['mem_idx'];?>" <?=selected($mem_data['mem_idx'], $apply_idx);?>>&nbsp;&nbsp;&nbsp;&nbsp;<?=$mem_data['mem_name'];?></option>
<?
				}
			}
		}
	}
?>
</select>

<script type="text/javascript">
//<![CDATA[
// 담당자 - 선택
	function popup_apply_select()
	{
		var apply_idx = $('#chk_apply_idx').val();
		$('#post_apply_idx').val(apply_idx);
		charge_member_list('<?=$work_type;?>', '<?=$wi_idx;?>');
	}
//]]>
</script>