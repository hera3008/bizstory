<?
// 지사통합을 할 경우 이 메뉴가 보이도록
	$set_part_yn = $company_set_data['part_yn'];

	if ($_SESSION[$sess_str . '_ubstory_level'] <= '21' || $set_part_yn == 'Y')
	{
		$member_where = "and mem.comp_idx = '" . $code_comp . "' and mem.del_yn = 'N' ";
		$member_list = member_info_data('list', $member_where, '', '', '');
		//print_r($member_list);
		if ($member_list['total_num'] > 0)
		{
?>
	<div class="tabarea" id="member_menu">
		<p>담당자명</p>
		<div class="tabarea_member">
		<select id="mem_idxs" name="states[]" multiple="multiple" style="width:100%; height: 100px;" onclick="check_mem_idxs()">
<?
			foreach ($member_list as $k => $member_data)
			{
				if (is_array($member_data))
				{
					if ($code_member == '') $code_member = $member_data['mem_idx'];
					if ($code_member == $member_data['mem_idx']) $class_str = ' selected="selected"';
					else $class_str = '';
?>
			<option value="<?=$member_data['mem_idx'];?>" <?=$class_str;?>><?=$member_data['mem_name'];?></option>
<?
				}
			}
?>
		</select>
		</div>
	</div>
<?
		}
	}
?>