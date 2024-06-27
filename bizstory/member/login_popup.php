<?
/*
	생성 : 2013.05.23
	수정 : 2013.05.23
	위치 : 로그인 팝업창
*/
	require_once "../common/setting.php";

	$total_url = $local_dir . '/index.php?fmode=' . $fmode . '&smode=' . $smode;
?>
<div class="popup_login_ajax">
	<div class="popup_login_frame">

		<form id="loginform" name="loginform" class="loginform" method="post" action="<?=$this_page;?>" onsubmit="return check_login()">
			<input type="hidden" name="sub_type" id="login_sub_type" value="check_login" />

			<fieldset>
				<legend class="blind">로그인 폼</legend>
				<div class="login_head">
					<div class="login_title">로그인</div>
				</div>
				<div class="login_body">
					<div>
						<label for="login_mem_id" class="label">메일주소</label>
						<input type="text" name="param[mem_id]" id="login_mem_id" size="20" maxlength="100" value="메일주소를 입력하세요." title="메일주소를 입력하세요." class="type_text" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
					</div>
					<div>
						<label for="login_mem_pwd" class="label">비밀번호</label>
						<input type="password" name="param[mem_pwd]" id="login_mem_pwd" size="20" maxlength="20" title="비밀번호를 입력하세요." class="type_text" />
					</div>
					<div>
						<div class="login_left"><input type="checkbox" name="login_mem_id_chk" id="login_mem_id_chk" value="Y" onclick="check_login_save()" /><label for="login_mem_id_chk">아이디 저장</label></div>
						<div class="login_right"><span class="btn_big_green"><input type="submit" value="로그인" /></span></div>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 로그인
	function check_login()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#login_mem_id').val();
		chk_title = $('#login_mem_id').attr('title');
		if (chk_value == '' || chk_value == chk_title)
		{
			chk_total = chk_total + chk_title + '<br />';
			$('#login_mem_id').val(chk_title);
			action_num++;
		}

		chk_value = $('#login_mem_pwd').val();
		chk_title = $('#login_mem_pwd').attr('title');
		if (chk_value == '' || chk_value == chk_title)
		{
			chk_total = chk_total + chk_title + '<br />';
			$('#login_mem_pwd').val('');
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: '/bizstory/member/login_ok.php',
				data: $('#loginform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						check_login_save();
						if (msg.auto_value != '')
						{
							$.cookie('auto_value', msg.auto_value, { expires: 7 });
						}
						location.href = '<?=$total_url;?>';
					}
					else
					{
						check_auth_popup(msg.error_string);
						$("#loading").fadeOut('slow');
					}
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else
		{
			check_auth_popup(chk_total);
		}
		return false;
	}

//------------------------------------ 아이디저장
	function check_login_save()
	{
		var chk_value = $('#login_mem_id').val();
		var chk_title = $('#login_mem_id').attr('title');
		if (chk_value == chk_title) chk_value = '';

		if ($('#login_mem_id_chk').attr('checked') == 'checked' || $('#login_mem_id_chk').attr('checked') == true)
		{
			$.cookie('login_mem_id_save', chk_value, { expires: 7 });
		}
		else $.cookie('login_mem_id_save', null, { expires: 7 });
	}

//------------------------------------ ID Save
	if ($.cookie('login_mem_id_save') == null || $.cookie('login_mem_id_save') == '')
	{
		$('#login_mem_id').val($('#login_mem_id').attr('title'));
	}
	else
	{
		$('#login_mem_id').val($.cookie('login_mem_id_save'));
		$('#login_mem_id_chk').attr('checked', 'checked');
	}
//]]>
</script>