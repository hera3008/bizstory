<?
// 지사통합을 할 경우 이 메뉴가 보이도록
	$set_part_yn = $company_set_data['part_yn'];

	if ($_SESSION[$sess_str . '_ubstory_level'] <= '21' || $set_part_yn == 'Y')
	{
		$staff_where = "and csg.comp_idx = '" . $code_comp . "' ";
		$staff_list = company_staff_group_data('list', $staff_where, '', '', '');
		//print_r($staff_list);
		if ($staff_list['total_num'] > 0)
		{
?>
	<div class="tabarea" id="staff_menu">
		<p>부서명</p>
		<div class="tabarea_staff">
		<select id="csg_idxs" name="states[]" multiple="multiple" style="width:100%; height: 100px;" onclick="check_csg_idxs()">
<?
			foreach ($staff_list as $k => $staff_data)
			{
				if (is_array($staff_data))
				{
					if ($code_staff == '') $code_staff = $staff_data['csg_idx'];
					if ($code_staff == $staff_data['csg_idx']) $class_str = ' selected="selected"';
					else $class_str = '';
?>
			<option value="<?=$staff_data['csg_idx'];?>" <?=$class_str;?>><?=$staff_data['group_name'];?></option>
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