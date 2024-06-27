
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
					console.log(msg);
					if (msg.success_chk == "Y")
					{
						check_login_save();
						if (msg.auto_value != '')
						{
							$.cookie('auto_value', msg.auto_value, { expires: 7 });
						}
						location.href = total_url;
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

//------------------------------------ 서비스신청, 아이디찾기, 비밀번호찾기
	function login_popup_view(move_url)
	{
		$.ajax({
			type : 'get', dataType: 'html', url: move_url,
			data : $('#popup_joinform').serialize(),
			success : function(msg) {
				var maskHeight = $(document).height();
				var maskWidth = $(window).width();
				$("#data_form").slideDown("slow");
				$("#loading").fadeIn('slow').fadeOut('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':maskWidth,'height':maskHeight}).fadeIn("slow");
				var winW = $(window).width();
				var winH = $(window).height();
				//$('.popupform').css('top',  "80px");				
				//$('.popupform').css('top', (winH/2-($('.popupform').outerHeight()/2) + $(window).scrollTop()) + "px");
				$('.popupform').css('top', (winH/2-(300) + $(window).scrollTop()) + "px");
				$('.popupform').css('left', (winW/2-($('.popupform').width()/2)) + "px");
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 서비스신청
	function check_regist()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

	// 약관동의
		chk_value = $('#agree_check').is(':checked');
		chk_title = $('#agree_check').attr('title');
		if (chk_value == false)
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_msg = check_mem_email(); // 이메일
		if (chk_msg == 'No')
		{
			chk_total = chk_total + '이메일을 입력해 해주세요.<br />';
			action_num++;
		}
		if ($('#post_mem_email_chk').val() == 'N') // 이메일중복확인
		{
			chk_total = chk_total + '이메일중복확인을 해주세요.<br />';
			action_num++;
		}

		chk_msg = check_tel_num(); // 전화번호
		if (chk_msg == 'No')
		{
			chk_total = chk_total + '전화번호를 입력해 해주세요.<br />';
			action_num++;
		}

		chk_msg = check_hp_num(); // 핸드폰번호
		if (chk_msg == 'No')
		{
			chk_total = chk_total + '핸드폰번호를 입력해 해주세요.<br />';
			action_num++;
		}

		chk_msg = check_comp_name(); // 상호명
		if (chk_msg == 'No')
		{
			chk_total = chk_total + '상호명을 입력해 해주세요.<br />';
			action_num++;
		}

		chk_msg = check_boss_name(); // 대표자명
		if (chk_msg == 'No')
		{
			chk_total = chk_total + '대표자명을 입력해 해주세요.<br />';
			action_num++;
		}

		chk_msg = check_comp_num(); // 사업자등록번호
		if (chk_msg == 'No')
		{
			chk_total = chk_total + '사업자등록번호를 입력해 해주세요.<br />';
			action_num++;
		}
		if ($('#post_comp_num_chk').val() == 'N') // 사업자등록번호중복확인
		{
			chk_total = chk_total + '사업자등록번호중복확인을 해주세요.<br />';
			action_num++;
		}

		chk_msg = check_upjong(); // 업종
		if (chk_msg == 'No')
		{
			chk_total = chk_total + '업종을 입력해 해주세요.<br />';
			action_num++;
		}

		chk_msg = check_uptae(); // 업태
		if (chk_msg == 'No')
		{
			chk_total = chk_total + '업태를 입력해 해주세요.<br />';
			action_num++;
		}

		chk_msg = check_zipcode(); // 우편번호
		if (chk_msg == 'No')
		{
			chk_total = chk_total + '우편번호를 입력해 해주세요.<br />';
			action_num++;
		}

		chk_msg = check_address(); // 주소
		if (chk_msg == 'No')
		{
			chk_total = chk_total + '주소를 입력해 해주세요.<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$('#post_sub_type').val('reg_post');

			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type : 'post', dataType: 'json', url: '/bizstory/member/demo_ok.php',
				data : $('#popup_joinform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$("#loading").fadeOut('slow');
						check_auth_popup('서비스가 정상적으로 신청되었습니다.<br />승인을 기다리세요.');
						popupform_close();
					}
					else
					{
						$("#loading").fadeOut('slow');
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
	}

//------------------------------------ 아이디찾기
	function check_id_find()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_msg = check_comp_num(); // 사업자등록번호
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		chk_msg = check_mem_name(); // 이름
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		chk_msg = check_hp_num(); // 핸드폰번호
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		if (action_num == 0)
		{
			$.ajax({
				type : 'post', dataType: 'json', url: '/bizstory/member/regist_ok.php',
				data : $('#popup_idform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						check_auth_popup(msg.message);
						popupform_close();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 비밀번호찾기
	function check_pass_find()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_msg = check_mem_id(); // 아이디
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		chk_msg = check_comp_num(); // 사업자등록번호
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		chk_msg = check_mem_name(); // 이름
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		chk_msg = check_hp_num(); // 핸드폰번호
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		if (action_num == 0)
		{
			$.ajax({
				type : 'post', dataType: 'json', url: '/bizstory/member/regist_ok.php',
				data : $('#popup_passform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						login_popup_view('/bizstory/member/find_pwd.php?mem_idx=' + msg.message);
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 비밀번호재설정
	function check_pass_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_msg = check_mem_pwd(); // 비밀번호
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}
		chk_msg = check_mem_pwd2(); // 비밀번호 재설정
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		if (action_num == 0)
		{
			$.ajax({
				type : 'post', dataType: 'json', url: '/bizstory/member/regist_ok.php',
				data : $('#popup_passform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						check_auth_popup(msg.message);
						popupform_close();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
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

//------------------------------------ 에러부분
	$("#popup_result_msg").dialog({
		autoOpen: false, width: 350, modal: true,
		buttons: {
			"확인": function() { $(this).dialog("close"); }
		}
	});

//------------------------------------ 배경클릭시
	$("#backgroundPopup").click(function(){popupform_close()}); // 등록폼 닫기