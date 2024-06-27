<?
/*
	수정 : 2012.11.22
	위치 : 회계업무 > 운영비관리 - 등록/수정 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$chk_ai_idx = $ai_idx;

	$chk_where = " and ai.ai_idx = '" . $ai_idx . "'";
	$chk_data = account_info_data("view", $chk_where);
?>
	<table class="tinytable write" summary="운영비관리를 등록합니다.">
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
					<input type="checkbox" name="param[charge_yn]" id="post_charge_yn" title="수수로포함여부 선택하세요." value="Y" <?=checked($data['charge_yn'], 'Y');?>/> 수수료포함
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
					<span class="btn_big_green"><input type="button" value="등록" onclick="check_form('post')" /></span>
				</div>
			</td>
		</tr>
	</tbody>
	</table>
<?
	if ($ai_code != '' && $ai_code > 0)
	{
		$sub_where = " and ai.ai_code = '" . $ai_code . "'";
		$sub_list = account_info_data("list", $sub_where, '', '', '');

		$sub_num = 1;
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
					$data['gubun_code'] = $sub_data['gubun_code'];
					$data['class_code'] = $sub_data['class_code'];
					$data['ci_idx']     = $sub_data['ci_idx'];
?>
	<table class="tinytable write" summary="운영비관리를 수정합니다." style="border: 2px solid #333333;">
	<caption>운영비수정</caption>
	<colgroup>
		<col width="80px" />
		<col />
		<col width="80px" />
		<col />
		<col width="80px" />
		<col />
	</colgroup>
	<tbody>
		<tr>
			<th><label for="modify_account_type">종류</label></th>
			<td>
				<div class="left">
					<?=code_select($set_account_type, "modify_param[account_type]", "modify_account_type", $sub_data['account_type'], '종류선택', '종류선택', 'onchange="check_type(\'' . $sub_data['part_idx'] . '\', \'' . $ai_idx . '\')"');?>
				</div>
			</td>
			<th><label for="modify_gubun_code">구분</label></th>
			<td>
				<div class="left">
					<ul>
						<li>
							<select id="modify_gubun_code" name="modify_param[gubun_code]" title="구분선택" onchange="check_gubun('<?=$sub_data['part_idx'];?>', '<?=$ai_idx;?>')">
								<option value="">구분선택</option>
							</select>
						</li>
						<li>
							<span id="account_gubun_list"></span>
						</li>
					</ul>
				</div>
			</td>
			<th><label for="modify_ci_idx">거래처</label></th>
			<td>
				<div class="left">
					<select name="modify_param[ci_idx]" id="modify_ci_idx" title="거래처를 선택하세요.">
						<option value="">거래처를 선택하세요</option>
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<th><label for="modify_account_date">날짜</label></th>
			<td>
				<div class="left">
					<input type="text" name="modify_param[account_date]" id="modify_account_date" class="type_text datepicker" title="날짜를 입력하세요." size="10" value="<?=$sub_data['account_date'];?>" />
				</div>
			</td>
			<th><label for="modify_account_price">금액</label></th>
			<td>
				<div class="left">
					<input type="text" name="modify_param[account_price]" id="modify_account_price" class="type_text" title="금액을 입력하세요." size="12" value="<?=$sub_data['account_price'];?>" />
					<input type="checkbox" name="modify_param[charge_yn]" id="modify_charge_yn" title="수수로포함여부 선택하세요." value="Y" <?=checked($data['charge_yn'], 'Y');?>/> 수수료포함
				</div>

			</td>
			<th><label for="modify_class_code">계정과목</label></th>
			<td>
				<div class="left">
					<select name="modify_param[class_code]" id="modify_class_code" title="계정과목을 선택하세요.">
						<option value="">계정과목을 선택하세요</option>
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<th><label for="modify_content">적요</label></th>
			<td colspan="5">
				<div class="left">
					<input type="text" name="modify_param[content]" id="modify_content" class="type_text" title="적요를 입력하세요." size="50" value="<?=$sub_data['content'];?>" />
					<span class="btn_big_blue"><input type="button" value="수정" onclick="check_form('modify')" /></span>
				</div>
			</td>
		</tr>
	</tbody>
	</table>
<?
				}
				else
				{
					if ($sub_data['gubun_code'] == 'card') // 카드일 경우
					{
						$add_content = '-' . $sub_data['card_code_name'] . '(' . $sub_data['card_mem_name'] . ': ' . $sub_data['card_num'] . ')';
					}
					else if ($sub_data['gubun_code'] == 'bank') // 계좌이체일 경우
					{
						$add_content = '-' . $sub_data['bank_code_name'] . '(' . $sub_data['bank_num'] . ')';
					}
?>
	<div class="section">
		<div class="fl">
			<?=$sub_num;?>.
			<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a>
			<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con_red"><span>삭제</span></a>
		</div>
	</div>
	<table class="tinytable write" summary="등록한 운영비내역입니다.">
	<caption>운영비내역</caption>
	<colgroup>
		<col width="80px" />
		<col />
		<col width="80px" />
		<col />
		<col width="80px" />
		<col />
	</colgroup>
	<tbody>
		<tr>
			<th>종류</th>
			<td>
				<div class="left"><?=$set_account_type[$sub_data['account_type']];?></div>
			</td>
			<th>구분</th>
			<td>
				<div class="left"><?=$sub_data['gubun_code_name'];?> <?=$add_content;?></div>
			</td>
			<th>거래처</th>
			<td>
				<div class="left"><?=$sub_data['client_name'];?></div>
			</td>
		</tr>
		<tr>
			<th>날짜</th>
			<td>
				<div class="left"><?=$sub_data['account_date'];?></div>
			</td>
			<th>금액</th>
			<td>
				<div class="left">
					<?=number_format($sub_data['account_price']);?>
					수수료포함 : <?=$sub_data['charge_yn'];?>
				</div>
			</td>
			<th>계정과목</th>
			<td>
				<div class="left"><?=$sub_data['class_code_name'];?>(<?=$sub_data['class_code_value'];?>)</div>
			</td>
		</tr>
		<tr>
			<th>적요</th>
			<td colspan="5">
				<div class="left"><?=$sub_data['content'];?></div>
			</td>
		</tr>
	</tbody>
	</table>
<?
				}
				$sub_num++;
			}
		}
	}
?>
<script type="text/javascript">
//<![CDATA[
	$(".datepicker").datepicker();
	part_information('<?=$chk_data['part_idx'];?>', 'account_class', 'post_class_code', '<?=$data['class_code'];?>', ''); // 등록시
	part_information('<?=$chk_data['part_idx'];?>', 'client_info', 'post_ci_idx', '<?=$data['ci_idx'];?>', ''); // 등록시
<?
	if ($chk_ai_idx != '')
	{
?>
	part_information('<?=$chk_data['part_idx'];?>', 'account_gubun', 'modify_gubun_code', '<?=$data['gubun_code'];?>', ''); // 수정시
	part_information('<?=$chk_data['part_idx'];?>', 'account_class', 'modify_class_code', '<?=$data['class_code'];?>', ''); // 수정시
	part_information('<?=$chk_data['part_idx'];?>', 'client_info', 'modify_ci_idx', '<?=$data['ci_idx'];?>', ''); // 수정시
<?
	}
?>

//------------------------------------ 등록, 수정
	function check_form(str)
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#' + str + '_account_type').val(); // 종류
		chk_title = $('#' + str + '_account_type').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#' + str + '_gubun_code').val(); // 구분
		chk_title = $('#' + str + '_gubun_code').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#' + str + '_account_date').val(); // 날짜
		chk_title = $('#' + str + '_account_date').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#' + str + '_account_price').val(); // 금액
		chk_title = $('#' + str + '_account_price').attr('title');
		if (chk_value == '')
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