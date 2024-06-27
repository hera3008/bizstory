<?
/*
	수정 : 2012.11.22
	위치 : 회계업무 > 운영비관리 - 등록/수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$ai_idx    = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;assdate=' . $send_assdate . '&amp;asedate=' . $send_asedate;
	$f_search  = $f_search . '&amp;astype=' . $send_astype . '&amp;asgubun=' . $send_asgubun . '&amp;asclass=' . $send_asclass . '&amp;asbank=' . $send_asbank . '&amp;ascard=' . $send_ascards . '&amp;asclient=' . $send_asclient;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="assdate"  value="' . $send_assdate . '" />
		<input type="hidden" name="asedate"  value="' . $send_asedate . '" />
		<input type="hidden" name="astype"   value="' . $send_astype . '" />
		<input type="hidden" name="asgubun"  value="' . $send_asgubun . '" />
		<input type="hidden" name="asclass"  value="' . $send_asclass . '" />
		<input type="hidden" name="asbank"   value="' . $send_asbank . '" />
		<input type="hidden" name="ascard"   value="' . $send_ascard . '" />
		<input type="hidden" name="asclient" value="' . $send_asclient . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' && $ai_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $ai_idx != '') // 수정권한
	{
		$form_chk   = 'Y';
		$form_title = '수정';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>';
		exit;
	}

	if ($form_chk == 'Y')
	{
		$where = " and ai.ai_idx = '" . $ai_idx . "'";
		$data = account_info_data("view", $where);

		if ($data['part_idx'] == '') $data['part_idx'] = $code_part;
		if ($data['account_type'] == '') $data['account_type'] = 'OUT';
		if ($ai_idx == '') $sub_type = 'post';
		else $sub_type = 'modify';
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" name="ai_idx"   id="post_ai_idx"   value="<?=$ai_idx;?>" />
			<input type="hidden" name="ai_code"  id="post_ai_code"  value="<?=$ai_code;?>" />
			<input type="hidden" name="sub_type" id="post_sub_type" value="<?=$sub_type;?>" />

			<fieldset>
				<legend class="blind">운영비관리 폼</legend>
				<table class="tinytable write" summary="운영비관리를 등록/수정합니다.">
				<caption>운영비관리</caption>
				<colgroup>
					<col width="80px" />
					<col />
					<col width="80px" />
					<col />
					<col width="80px" />
					<col />
				</colgroup>
				<tbody>
	<?
	// 등록시
		if ($ai_idx == '')
		{
	?>
					<tr>
						<th><label for="post_account_type">종류</label></th>
						<td>
							<div class="left">
								<?=code_select($set_account_type, "param[account_type]", "post_account_type", $data['account_type'], '종류선택', '종류선택', 'onchange="check_type(\'' . $data['part_idx'] . '\', \'' . $ai_idx . '\')"');?>
							</div>
						</td>
						<th><label for="post_gubun_code">구분</label></th>
						<td>
							<div class="left">
								<ul>
									<li>
										<select id="post_gubun_code" name="param[gubun_code]" title="구분선택" onchange="check_gubun('<?=$data['part_idx'];?>', '<?=$ai_idx;?>', '')">
											<option value="">구분선택</option>
										</select>
									</li>
									<li>
										<span id="account_gubun_list"></span>
									</li>
								</ul>
							</div>

						</td>
						<th><label for="post_part_idx">지사</label></th>
						<td>
							<div class="left">
								<?=company_part_select($data['part_idx'], '');?>
							</div>
						</td>
					</tr>
	<?
		}
	// 수정시
		else
		{
	?>
					<tr>
						<th><label for="modify_account_type">종류</label></th>
						<td>
							<div class="left">
								<?=code_select($set_account_type, "modify_param[account_type]", "modify_account_type", $data['account_type'], '종류선택', '종류선택', 'onchange="check_type(\'' . $data['part_idx'] . '\', \'' . $ai_idx . '\')"');?>
							</div>
						</td>
						<th><label for="modify_gubun_code">구분</label></th>
						<td>
							<div class="left">
								<ul>
									<li>
										<select id="modify_gubun_code" name="modify_param[gubun_code]" title="구분선택" onchange="check_gubun('<?=$data['part_idx'];?>', '<?=$ai_idx;?>', '')">
											<option value="">구분선택</option>
										</select>
										<input type="hidden" name="chk_gubun_code" id="chk_gubun_code" value="<?=$data['gubun_code'];?>" />
									</li>
									<li>
										<span id="account_gubun_list"></span>
									</li>
								</ul>
							</div>
						</td>
						<th><label for="modify_part_idx">지사</label></th>
						<td>
							<div class="left">
								<?=company_part_select($data['part_idx'], '');?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="modify_account_date">날짜</label></th>
						<td>
							<div class="left">
								<input type="text" name="modify_param[account_date]" id="modify_account_date" class="type_text datepicker" title="날짜를 입력하세요." size="10" value="<?=$data['account_date'];?>" />
							</div>
						</td>
						<th><label for="modify_account_price">금액</label></th>
						<td>
							<div class="left">
								<input type="text" name="modify_param[account_price]" id="modify_account_price" class="type_text" title="금액을 입력하세요." size="12" value="<?=$data['account_price'];?>" />
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
						<td colspan="3">
							<div class="left">
								<input type="text" name="modify_param[content]" id="modify_content" class="type_text" title="적요를 입력하세요." size="50" value="<?=$data['content'];?>" />
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
	<?
		}
	?>
				</tbody>
				</table>

				<div id="account_info_list"></div>

				<div class="section">
					<div class="fr">
				<?
					if ($ai_idx == '') {
				?>
						<span class="btn_big_violet"><input type="button" value="목록" onclick="close_data_form()" /></span>
				<?
					} else {
				?>
						<span class="btn_big_blue"><input type="button" value="수정" onclick="check_modify_form()" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>
				<?
					}
				?>
					</div>
				</div>

			</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 수정
	function check_modify_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#modify_account_type').val(); // 종류
		chk_title = $('#modify_account_type').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#modify_gubun_code').val(); // 구분
		chk_title = $('#modify_gubun_code').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#modify_account_date').val(); // 날짜
		chk_title = $('#modify_account_date').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#modify_account_price').val(); // 금액
		chk_title = $('#modify_account_price').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#modify_class_code').val(); // 계정
		chk_title = $('#modify_class_code').attr('title');
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
						close_data_form();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 추가등록값
	function account_list(idx)
	{
		$("#post_ai_idx").val(idx);
		if (idx == '') $("#post_sub_type").val('post');
		else $("#post_sub_type").val('modify');

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/account/account_form_sub.php',
			data: $('#postform').serialize(),
			success: function(msg) {
				$('#account_info_list').html(msg);
			},
			complete: function(){
				$("#loading").fadeOut('slow');
				$("#backgroundPopup").fadeOut("slow");
			}
		});
	}

//------------------------------------ 구분선택
	function check_gubun(part_idx, ai_idx, gubun_code)
	{
<?
	if ($ai_idx == '')
	{
?>
		var chk_value = $('#post_gubun_code').val();
<?
	}
	else
	{
?>
		if (gubun_code == '')
		{
			var chk_value = $('#modify_gubun_code').val();
		}
		else
		{
			var chk_value = gubun_code;
		}
<?
	}
?>
		$("#account_gubun_list").css({"display": "none"});

		if (chk_value == 'card') // 카드일 경우
		{
			$.ajax({
				type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/account/account_card.php',
				data: {'part_idx':part_idx, 'ai_idx':ai_idx},
				success: function(msg) {
					$("#account_gubun_list").css({"display": "block"});
					$("#account_gubun_list").html(msg);
				}
			});
		}
		else if (chk_value == 'bank') // 계좌이체일 경우
		{
			$.ajax({
				type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/account/account_bank.php',
				data: {'part_idx':part_idx, 'ai_idx':ai_idx},
				success: function(msg) {
					$("#account_gubun_list").css({"display": "block"});
					$("#account_gubun_list").html(msg);
				}
			});
		}
	}

<?
	if ($ai_idx == '')
	{
?>
	account_list('');
	part_information('<?=$data['part_idx'];?>', 'account_gubun', 'post_gubun_code', '<?=$data['gubun_code'];?>', '');
<?
	}
	else
	{
?>
	$(".datepicker").datepicker();
	part_information('<?=$data['part_idx'];?>', 'account_gubun', 'modify_gubun_code', '<?=$data['gubun_code'];?>', ''); // 수정시
	part_information('<?=$data['part_idx'];?>', 'account_class', 'modify_class_code', '<?=$data['class_code'];?>', ''); // 수정시
	part_information('<?=$data['part_idx'];?>', 'client_info', 'modify_ci_idx', '<?=$data['ci_idx'];?>', ''); // 수정시
	check_gubun('<?=$data['part_idx'];?>', '<?=$ai_idx;?>', '<?=$data['gubun_code'];?>');
<?
	}
?>
//]]>
</script>
<?
	}
?>