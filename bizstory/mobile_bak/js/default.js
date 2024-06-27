
	var local_dir = '/bizstory/mobile';

//------------------------------------ 권한메세지 - popup
	function check_auth_popup(msg)
	{
		$('#popup_result_msg').dialog('open');
		$("#popup_result_msg").html(msg);
		$("#loading").fadeOut('slow');
		$("#backgroundPopup").fadeOut("slow");
	}

//------------------------------------ 입력여부
	function check_input_value(chk_value)
	{
		if (chk_value == '') return 'No';
		else return 'Yes';
	}

//------------------------------------ 로그아웃
	function login_out()
	{
		$.ajax({
			type: 'post', dataType: 'json', url: local_dir + '/login_out.php',
			data: {},
			beforeSubmit: function(){ $("#loading").fadeIn('slow').fadeOut('slow');},
			success: function(msg) {
				if (msg.success_chk == "Y")
				{
					$.cookie('auto_value', '1', { expires: 7 });
					location.href = local_dir + '/';
				}
				else check_auth_popup(msg.error_string);
			}
		});
	}