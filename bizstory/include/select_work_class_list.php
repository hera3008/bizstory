<?
/*
	생성 : 2012.04.23
	수정 : 2012.05.07
	위치 : 업무폴더 > 나의업무 > 업무 - 분류선택 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$field_value_arr = explode(',', $field_value);

	$where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.view_yn = 'Y'";
	$list = code_work_class_data('list', $where, '', '', '');

	if ($list['total_num'] > 0)
	{
?>
	<div class="work_class_list">
		<ul>
<?
		foreach ($list as $k => $data)
		{
			if (is_array($data))
			{
				$chk_str = '';
				foreach ($field_value_arr as $field_k => $field_v)
				{
					if ($field_v == $data['code_idx'])
					{
						$chk_str = ' checked="checked"';
						break;
					}
				}
?>
			<li>
				<label for="codeidx_<?=$k;?>">
					<input type="radio" name="check_code_idx" id="codeidx_<?=$k;?>" value="<?=$data['code_idx'];?>"<?=$chk_str;?> />
					<input type="hidden" name="check_code_name" id="check_code_name_<?=$k;?>" value="<?=$data['code_name'];?>" />
					<?=$data['code_name'];?>
				</label>
			</li>
<?
			}
		}
?>
		</ul>
	</div>
<?
	}
?>