<?
// 지사통합을 할 경우 이 메뉴가 보이도록
	$set_part_yn = $company_set_data['part_yn'];

	if ($_SESSION[$sess_str . '_ubstory_level'] <= '21' || $set_part_yn == 'Y')
	{
		$part_where = "and ci.comp_idx = '" . $code_comp . "'";
		$client_list = client_info_data('list', $part_where, 'ci.client_name asc', '', '');
		//print_r($client_list);
		if ($client_list['total_num'] > 0)
		{
?>
	<div class="tabarea" id="client_menu">
		<p>거래처명</p>
		<div class="tabarea_client">
		<select id="ci_idxs" name="states[]" multiple="multiple" style="width:100%; height: 100px;" onclick="check_ci_idxs()">
<?
			foreach ($client_list as $k => $client_data)
			{
				if (is_array($client_data))
				{
					if ($code_client == '') $code_client = $client_data['ci_idx'];
					if ($code_client == $client_data['ci_idx']) $class_str = ' selected="selected"';
					else $class_str = '';
?>
			<option value="<?=$client_data['ci_idx'];?>" <?=$class_str?>><?=$client_data['client_name'];?></option>
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