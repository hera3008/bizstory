<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$where = " and cu.cu_idx = '" . $cu_idx . "'";
	$data = client_user_data("view", $where);

	$mem_email = $data['mem_email'];
	$mem_email_arr = explode('@', $mem_email);
	$data['mem_email1'] = $mem_email_arr[0];
	$data['mem_email2'] = $mem_email_arr[1];

	if ($data["login_yn"] == '') $data["login_yn"] = 'Y';
?>
<div class="new_report">

	<div class="info_frame">
		<span>수정할 경우만 비밀번호를 입력하세요.</span>
	</div>

	<form id="cuserform" name="cuserform" action="<?=$this_page;?>" method="post" onsubmit="return check_cuser_form('<?=$cu_idx;?>')">
		<input type="hidden" name="ci_idx" value="<?=$ci_idx;?>" />

		<fieldset>
			<legend class="blind">거래처사용자 폼</legend>
			<table class="tinytable write" summary="거래처사용자를 등록/수정합니다.">
			<caption>거래처사용자</caption>
			<colgroup>
				<col width="100px" />
				<col />
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="cuser_mem_id">아이디</label></th>
					<td>
						<div class="left">
			<?
				if ($cu_idx == "") {
			?>
							<input type="text" name="param[mem_id]" id="cuser_mem_id" value="<?=$data['mem_id'];?>" title="아이디를 입력하세요." class="type_text" />
							<input type="hidden" name="cuser_mem_id_chk" id="cuser_mem_id_chk" value="N" title="아이디 중복확인을 하세요." />
							<strong class="btn_sml" onclick="double_client_id_chk();"><span>중복확인</span></strong>
			<?
				} else {
			?>
							<?=$data['mem_id'];?>
			<?
				}
			?>
						</div>
					</td>
					<th><label for="cuser_mem_pwd">비밀번호</label></th>
					<td>
						<div class="left">
							<input type="password" name="param[mem_pwd]" id="cuser_mem_pwd" value="" title="비밀번호를 입력하세요." class="type_text" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="cuser_mem_name">이름</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[mem_name]" id="cuser_mem_name" value="<?=$data['mem_name'];?>" title="이름을 입력하세요." class="type_text" />
						</div>
					</td>
					<th><label for="cuser_tel_num">연락처</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[tel_num]" id="cuser_tel_num" value="<?=$data['tel_num'];?>" title="연락처를 입력하세요." class="type_text" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="cuser_mem_email1">이메일</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[mem_email1]" id="cuser_mem_email1" class="type_text" title="이메일 아이디를 입력하세요." size="12" value="<?=$data['mem_email1'];?>" />
							@
							<input type="text" name="param[mem_email2]" id="cuser_mem_email2" class="type_text" title="이메일 주소를 입력하세요." size="20" value="<?=$data['mem_email2'];?>" />
							<?=code_select($set_email_domain, 'cuser_mem_email3', 'cuser_mem_email3', $data['mem_email2'], '이메일 선택하세요', '이메일 선택하세요', '', '', 'onchange="email_input(\'cuser_mem_email2\', \'cuser_mem_email3\');"');?>
						</div>
					</td>
				</tr>
				<tr>
					<th>로그인여부</th>
					<td colspan="3">
						<div class="left">
							<?=code_radio($set_use, "param[login_yn]", "cuser_login_yn", $data["login_yn"]);?>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
			<?
				if ($cu_idx == "") {
			?>
					<span class="btn_big_green"><input type="submit" value="등록" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="cuser_insert_form('close')" /></span>

					<input type="hidden" name="sub_type" value="post" />
			<?
				} else {
			?>
					<span class="btn_big_blue"><input type="submit" value="수정" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="cuser_modify_form('close', '<?=$cu_idx;?>')" /></span>

					<input type="hidden" name="sub_type" value="modify" />
					<input type="hidden" name="cu_idx"   value="<?=$cu_idx;?>" />
			<?
				}
			?>
				</div>
			</div>

		</fieldset>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 아이디 중복체크
	function double_client_id_chk()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		$('#cuser_mem_id_chk').val('N');

		chk_value = $('#cuser_mem_id').val();
		chk_title = $('#cuser_mem_id').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type    : "get", dataType: 'json', url: cuser_ok,
				data    : {"sub_type" : "double_id", "mem_id" : chk_value},
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						if (msg.double_chk == "N")
						{
							$('#cuser_mem_id_chk').val('Y');
							check_auth_popup('사용 가능한 아이디입니다.');
						}
						else check_auth_popup('이미 사용중인 아이디입니다.');
					}
					else check_auth_popup(msg.error_string);
				}
			});
			return false;
		}
		else check_auth_popup(chk_total);
	}

//------------------------------------ 사용자등록/수정
	function check_cuser_form(idx)
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		if (idx == '')
		{
			chk_value = $('#cuser_mem_id').val();
			chk_title = $('#cuser_mem_id').attr('title');
			chk_msg = check_input_value(chk_value);
			if (chk_msg == 'No')
			{
				chk_total = chk_total + chk_title + '<br />';
				action_num++;
			}

			chk_value = $('#cuser_mem_id_chk').val();
			chk_title = $('#cuser_mem_id_chk').attr('title');
			if (chk_value == 'N')
			{
				chk_total = chk_total + chk_title + '<br />';
				action_num++;
			}

			chk_value = $('#cuser_mem_pwd').val();
			chk_title = $('#cuser_mem_pwd').attr('title');
			chk_msg = check_input_value(chk_value);
			if (chk_msg == 'No')
			{
				chk_total = chk_total + chk_title + '<br />';
				action_num++;
			}
		}

		if (action_num == 0)
		{
			$.ajax({
				type: "post", dataType: 'json', url: cuser_ok,
				data: $('#cuserform').serialize(),
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#cuser_total_value').html(msg.total_num);
	<?
		if ($cu_idx == '') {
	?>
						cuser_insert_form('close')
	<?
		} else {
	?>
						cuser_modify_form('close','')
	<?
		}
	?>
						cuser_list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}
//]]>
</script>