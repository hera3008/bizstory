<?
/*
	생성 : 2012.07.03
	수정 : 2012.10.31
	위치 : 설정관리 > 에이전트관리 > 아이콘관리 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_agent = search_agent_type($code_part, $code_agent);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$where = " and abu.comp_idx = '" . $code_comp . "' and abu.part_idx = '" . $code_part . "' and abu.agent_type = '" . $code_agent . "'";
	$list = agent_button_data('list', $where, '', '', '');

// 2. 알림게시판
	$sub_where1 = "
		and abu.comp_idx = '" . $code_comp . "' and abu.part_idx = '" . $code_part . "'
		and abu.agent_type = '" . $code_agent . "' and abu.btn_type = '2'";
	$sub_data1 = agent_button_data('page', $sub_where1);
	$sub_total1 = $sub_data1['total_num'];

// 3. 상담게시판
	$sub_where2 = "
		and abu.comp_idx = '" . $code_comp . "' and abu.part_idx = '" . $code_part . "'
		and abu.agent_type = '" . $code_agent . "' and abu.btn_type = '3'";
	$sub_data2 = agent_button_data('page', $sub_where2);
	$sub_total2 = $sub_data2['total_num'];
?>
<div class="info_text">
	<ul>
		<li>링크주소 입력시 "http://", "ftp://"등 같이 입력하세요.</li>
	</ul>
</div>

<table class="tinytable view">
<colgroup>
	<col width="60px" />
	<col width="150px" />
	<col />
</colgroup>
<thead>
	<tr>
		<th class="nosort"><h3>번호</h3></th>
		<th class="nosort"><h3>아이콘명</h3></th>
		<th class="nosort"><h3>링크주소</h3></th>
	</tr>
</thead>
<tbody>
<?
	if ($list['total_num'] == 0)
	{
		$sub_type = 'post';
		foreach ($set_agent_button as $k => $v)
		{
?>
	<tr>
		<td><?=$k;?></td>
		<td>
			<input type="text" name="btn_name_<?=$k;?>" id="btn_name_<?=$k;?>" value="<?=$v;?>" class="type_text" title="아이콘명 입력하세요." />
		</td>
		<td>
	<?
		if ($k > 1)
		{
	?>
			<div class="left">
				<select name="btn_type_<?=$k;?>" id="btn_type_<?=$k;?>" onchange="type_change(this.value, '<?=$k;?>');">
					<option value="">아이콘타입 선택</option>
			<?
				foreach ($set_agent_button_type as $type_k => $type_v)
				{
			?>
					<option value="<?=$type_k;?>"><?=$type_v;?></option>
			<?
				}
			?>
				</select>
				<input type="text" name="link_url_<?=$k;?>" id="link_url_<?=$k;?>" value="" class="type_text no_input" size="50" title="링크주소 입력하세요." disabled="disabled" />
			</div>
	<?
		}
	?>
		</td>
	</tr>
<?
		}
	}
	else
	{
		$sub_type = 'modify';

		foreach ($list as $k => $data)
		{
			if (is_array($data))
			{
				$sort = $data['sort'];
				if ($data['btn_type'] == '5')
				{
					$class_str    = '';
					$disabled_str = '';
				}
				else
				{
					$class_str    = ' agent_disabled';
					$disabled_str = 'disabled="disabled"';
				}
?>
	<tr>
		<td><?=$sort;?></td>
		<td>
			<input type="text" name="btn_name_<?=$sort;?>" id="btn_name_<?=$sort;?>" value="<?=$data['btn_name'];?>" class="type_text" title="아이콘명 입력하세요." />
			<input type="hidden" name="abu_idx_<?=$sort;?>" id="abu_idx_<?=$sort;?>" value="<?=$data['abu_idx'];?>" />
		</td>
		<td>
	<?
		if ($sort > 1)
		{
	?>
			<div class="left">
				<select name="btn_type_<?=$sort;?>" id="btn_type_<?=$sort;?>" onchange="type_change(this.value, '<?=$sort;?>');">
					<option value="">아이콘타입 선택</option>
					<option value="<?=$data['btn_type'];?>" selected="selected"><?=$set_agent_button_type[$data['btn_type']];?></option>
			<?
				foreach ($set_agent_button_type as $type_k => $type_v)
				{
					if ($type_k != $data['btn_type'])
					{
						if ($type_k == '2' && $sub_total1 > 0 || $type_k == '3' && $sub_total2 > 0)
						{

						}
						else
						{

			?>
					<option value="<?=$type_k;?>"><?=$type_v;?></option>
			<?
						}
					}
				}
			?>
				</select>
				<input type="text" name="link_url_<?=$sort;?>" id="link_url_<?=$sort;?>" value="<?=$data['link_url'];?>" class="type_text<?=$class_str;?>" size="50" title="링크주소 입력하세요." <?=$disabled_str;?> />
			</div>
	<?
		}
	?>
		</td>
	</tr>
<?
			}
		}
	}
?>
</tbody>
</table>

<div class="section">
	<span class="btn_big_blue"><input type="submit" value="수정" /></span>
	<span class="btn_big_gray"><input type="button" value="취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

	<input type="hidden" name="sub_type" value="<?=$sub_type;?>" />
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#listform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						agent_type('<?=$code_part;?>', '<?=$code_agent;?>');
					<?
						$f_default1 = str_replace('&amp;', '&', $f_default);
					?>
						//location.href = '?<?=$f_default1;?>';
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 타입변경
	function type_change(str, idx)
	{
		if (str == '5') // 링크아이콘일 경우만
		{
			$("#link_url_" + idx).attr('disabled',false);
			$("#link_url_" + idx).css('background','');
		}
		else
		{
			$("#link_url_" + idx).attr('disabled',true);
			$("#link_url_" + idx).css('background','#CCCCCC');
		}
	}
//]]>
</script>
