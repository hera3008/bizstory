<?
	include "../common/setting.php";
	include $local_path . "/include/header.php";
?>
<script type="text/javascript" src="<?=$local_dir;?>/common/js/jquery.cycle.js"></script>
<title>BIZSTORY 서비스신청</title>
</head>

<body id="login_layout">
	<div id="loading">로딩중입니다...</div>
	<div id="popup_result_msg" title="처리결과"></div>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="popup_demoform" name="popup_demoform" class="joinform" method="post" action="<?=$this_page;?>">
			<input type="hidden" name="sub_type" id="post_sub_type" value="reg_post" />

		<fieldset>
			<legend class="blind">데모신청 폼</legend>
			<table class="tinytable write" summary="데모신청을 위한 상호명, 담당자, 연락처, 이메일을 입력합니다.">
			<caption>데모신청</caption>
			<colgroup>
				<col width="125px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_comp_name">상호명</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[comp_name]" id="post_comp_name" class="type_text" title="상호명을 입력하세요." size="20" maxlength="50" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_charge_name">담당자</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[charge_name]" id="post_charge_name" class="type_text" title="담당자를 입력하세요." size="20" maxlength="20" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_tel_num1">연락처</label></th>
					<td>
						<div class="left">
							<?=code_select($set_telephone, 'param[tel_num1]', 'post_tel_num1', '', '전화번호 앞자리를 선택하세요.', '없음', '', '');?>
							-
							<input type="text" name="param[tel_num2]" id="post_tel_num2" class="type_text" title="전화번호를 입력하세요." size="4" maxlength="4" />
							-
							<input type="text" name="param[tel_num3]" id="post_tel_num3" class="type_text" title="전화번호를 입력하세요." size="4" maxlength="4" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_mem_email1">이메일</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[mem_email1]" id="post_mem_email1" class="type_text" title="이메일 아이디를 입력하세요." size="12" maxlength="50" />
							@
							<input type="text" name="param[mem_email2]" id="post_mem_email2" class="type_text" title="이메일 주소를 입력하세요." size="20" maxlength="50" />
							<?=code_select($set_email_domain, 'post_mem_email3', 'post_mem_email3', '', '이메일 선택하세요', '이메일 선택하세요', '', '', 'onchange="email_input(\'post_mem_email2\', \'post_mem_email3\');"');?>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<strong class="btn_sml" onclick="check_demo();"><span>데모신청</span></strong>
					<strong class="btn_sml" onclick="window.close();"><span>데모신청취소</span></strong>
				</div>
			</div>
		</fieldset>
		</form>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
	var link_ok = '<?=$local_dir;?>/bizstory/member/popup_demo_ok.php';

	function check_demo()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_comp_name').val(); // 상호명
		chk_title = $('#post_comp_name').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_charge_name').val(); // 담당자
		chk_title = $('#post_charge_name').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_tel_num2').val() + $('#post_tel_num3').val(); // 연락처
		chk_title = $('#post_tel_num2').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_mem_email1').val(); // 이메일
		chk_title = $('#post_mem_email1').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_mem_email2').val(); // 이메일
		chk_title = $('#post_mem_email2').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		$('#post_sub_type').val('reg_post');
		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type : 'post', dataType: 'json', url: link_ok,
				data : $('#popup_demoform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$("#loading").fadeOut('slow');
						check_auth_popup('정상적으로 데모신청이 되었습니다.');
						window.close();
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
		else check_auth_popup(chk_total);
	}

//------------------------------------ 에러부분
	$("#popup_result_msg").dialog({
		autoOpen: false, width: 350, modal: true,
		buttons: {
			"확인": function() { $(this).dialog("close"); }
		}
	});

//------------------------------------ 배경클릭시
	$("#backgroundPopup").click(function(){popupform_close()}); // 등록폼 닫기
//]]>
</script>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/script_member.js" charset="utf-8"></script>
</body>
</html>