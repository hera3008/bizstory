<?
/*
	수정 : 2012.11.22
	위치 : 회계업무 > 운영비관리 - 등록/수정 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$chk_ai_idx = $ai_idx;

	$chk_where = " and ai.ai_idx = '" . $ai_idx . "'";
	$chk_data = account_info_data("view", $chk_where);
?>
	<table class="tinytable write" summary="운영비관리를 등록/수정합니다.">
	<caption>운영비관리</caption>
	<colgroup>
		<col width="80px" />
		<col />
		<col width="80px" />
		<col />
	</colgroup>
	<tbody>
		<tr>
			<th><label for="post_account_date">날짜</label></th>
			<td>
				<div class="left">
					<input type="text" name="param[account_date]" id="post_account_date" class="type_text datepicker" title="날짜를 입력하세요." size="10" value="<?=date('Y-m-d');?>" />
				</div>
			</td>
			<th><label for="post_account_price">금액</label></th>
			<td>
				<div class="left">
					<input type="text" name="param[account_price]" id="post_account_price" class="type_text" title="금액을 입력하세요." size="12" value="<?=$data['account_price'];?>" />
				</div>
			</td>
		</tr>
		<tr>
			<th><label for="post_class_code">계정과목</label></th>
			<td>
				<div class="left">
					<select name="param[class_code]" id="post_class_code" title="계정과목을 선택하세요.">
						<option value="">계정과목을 선택하세요</option>
					</select>
				</div>
			</td>
			<th><label for="post_ci_idx">거래처</label></th>
			<td>
				<div class="left">
					<select name="param[ci_idx]" id="post_ci_idx" title="거래처를 선택하세요.">
						<option value="">거래처를 선택하세요</option>
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<th><label for="post_content">적요</label></th>
			<td colspan="3">
				<div class="left">
					<input type="text" name="param[content]" id="post_content" class="type_text" title="적요를 입력하세요." size="50" value="<?=$data['content'];?>" />
					<span class="btn_big"><input type="button" value="등록" onclick="check_form()" /></span>
				</div>
			</td>
		</tr>
	</tbody>
	</table>








	<table class="tinytable">
		<colgroup>
			<col width="130px" />
			<col width="150px" />
			<col width="150px" />
			<col width="150px" />
			<col />
			<col width="110px" />
		</colgroup>
		<thead>
			<tr>
				<th class="nosort"><h3>날짜</h3></th>
				<th class="nosort"><h3>금액</h3></th>
				<th class="nosort"><h3>거래처</h3></th>
				<th class="nosort"><h3>계정과목</h3></th>
				<th class="nosort"><h3>적요</h3></th>
				<th class="nosort"><h3>관리</h3></th>
			</tr>
		</thead>
		<tbody>
<?
	$sub_where = " and ai.ai_code = '" . $ai_code . "'";
	$sub_list = account_info_data("list", $sub_where, '', '', '');
	foreach ($sub_list as $sub_k => $sub_data)
	{
		if (is_array($sub_data))
		{
			$ai_idx = $sub_data['ai_idx'];

			if ($auth_menu['mod'] == "Y")
			{
				$btn_modify = "account_list('" . $ai_idx . "')";
			}
			else
			{
				$btn_modify = "check_auth_popup('modify')";
			}

			if ($auth_menu['del'] == "Y") $btn_delete = "account_delete('" . $ai_idx . "')";
			else $btn_delete = "check_auth_popup('delete');";

			if ($chk_ai_idx == $ai_idx)
			{
				$data['class_code'] = $sub_data['class_code'];
				$data['ci_idx']     = $sub_data['ci_idx'];
?>
			<tr>
				<td><input type="text" name="param[account_date]" id="post_account_date" class="type_text datepicker" title="날짜를 입력하세요." size="10" value="<?=$sub_data["account_date"];?>" /></td>
				<td><input type="text" name="param[account_price]" id="post_account_price" class="type_text" title="금액을 입력하세요." size="12" value="<?=$sub_data['account_price'];?>" /></td>
				<td>
					<select name="param[ci_idx]" id="post_ci_idx" title="거래처를 선택하세요.">
						<option value="">거래처를 선택하세요</option>
					</select>
				</td>
				<td>
					<select name="param[class_code]" id="post_class_code" title="계정과목을 선택하세요.">
						<option value="">계정과목을 선택하세요</option>
					</select>
				</td>
				<td><div class="left"><input type="text" name="param[content]" id="post_content" class="type_text" title="적요를 입력하세요." size="30" value="<?=$sub_data['content'];?>" /></div></td>
				<td>
					<a href="javascript:void(0);" onclick="check_form()" class="btn_con"><span>수정</span></a>
				</td>
			</tr>
<?
			}
			else
			{
?>
			<tr>
				<td><span class="eng"><?=$sub_data["account_date"];?></span></td>
				<td><span class="eng right"><?=number_format($sub_data["account_price"]);?>원</span></td>
				<td><?=$sub_data["client_name"];?></td>
				<td><?=$sub_data["class_code_name"];?>(<?=$sub_data["class_code_value"];?>)</td>
				<td><div class="left"><?=$sub_data["content"];?></div></td>
				<td>
					<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con"><span>수정</span></a>
					<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con"><span>삭제</span></a>
				</td>
			</tr>
<?
			}
		}
	}
?>
		</tbody>
	</table>

<script type="text/javascript">
//<![CDATA[
	$(".datepicker").datepicker();
	part_information('<?=$chk_data['part_idx'];?>', 'account_class', 'post_class_code', '<?=$data['class_code'];?>', '');
	part_information('<?=$chk_data['part_idx'];?>', 'client_info', 'post_ci_idx', '<?=$data['ci_idx'];?>', '');

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_account_type').val(); // 종류
		chk_title = $('#post_account_type').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_gubun_code').val(); // 구분
		chk_title = $('#post_gubun_code').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_account_date').val(); // 날짜
		chk_title = $('#post_account_date').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_account_price').val(); // 금액
		chk_title = $('#post_account_price').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$("#post_ai_code").val(msg.ai_code);
						account_list('');
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 삭제하기
	function account_delete(idx)
	{
		if (confirm("선택하신 데이타를 삭제하시겠습니까?"))
		{
			$('#list_sub_type').val('delete')
			$('#list_idx').val(idx);

			$("#loading").fadeIn('slow');
			$.ajax({
				type: "post", dataType: 'json', url: link_ok,
				data: $('#listform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						account_list('');
					}
					else
					{
						check_auth_popup(msg.error_string);
					}
				}
			});

		}
	}
//]]>
</script>