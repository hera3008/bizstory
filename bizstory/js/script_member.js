
//------------------------------------ 아이디확인
	function check_mem_id()
	{
		var chk_msg = ''; var chk_total = '';
		var chk_value = $('#post_mem_id').val();
		var chk_title = $('#post_mem_id').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_msg = check_num_no(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '아이디는 숫자만 사용할 수 없습니다. <br />';

		chk_msg = check_first_eng(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '아이디 첫자는 숫자를 사용할 수 없습니다. <br />';

		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '아이디는 공백없이 입력하세요. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 아이디 중복체크
	function double_id_chk()
	{
		$('#post_mem_id_chk').val('N');
		var chk_value = $('#post_mem_id').val();

		var chk_msg = check_mem_id();
		if (chk_msg == 'No') return false;
		else
		{
			$.ajax({
				type    : "post", dataType: 'json', url: link_ok,
				data    : {"sub_type" : "double_id", "mem_id" : chk_value},
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						if (msg.double_chk == "N")
						{
							$('#post_mem_id_chk').val('Y');
							check_auth_popup('사용 가능한 아이디입니다.');
						}
						else check_auth_popup('이미 사용중인 아이디입니다.');
					}
					else check_auth_popup(msg.error_string);
				}
			});
			return false;
		}
	}

//------------------------------------ 비밀번호
	function check_mem_pwd()
	{
		var chk_msg = '', chk_total = '';
		var chk_value = $('#post_mem_pwd').val();
		var chk_title = $('#post_mem_pwd').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_msg = check_length_value(chk_value, 4, 20);
		if (chk_msg == 'No') chk_total = chk_total + '비밀번호는 4~20자까지 입력하세요. <br />';

		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '비밀번호는 공백없이 입력하세요. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 비밀번호확인
	function check_mem_pwd2()
	{
		var chk_msg = '', chk_total = '';
		var chk_value = $('#post_mem_pwd2').val();
		var chk_title = $('#post_mem_pwd2').attr('title');
		var chk_value1 = $('#post_mem_pwd').val();

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_msg = check_length_value(chk_value, 4, 20);
		if (chk_msg == 'No') chk_total = chk_total + '비밀번호는 4~20자까지 입력하세요. <br />';

		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '비밀번호는 공백없이 입력하세요. <br />';

		chk_msg = check_value_same(chk_value, chk_value1);
		if (chk_msg == 'No') chk_total = chk_total + '비밀번호가 일치하지 않습니다. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 이메일 - 업체
	function check_comp_email()
	{
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_comp_email1').val();
		chk_title = $('#post_comp_email1').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_value = $('#post_comp_email2').val();
		chk_title = $('#post_comp_email2').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_value = $('#post_comp_email1').val() + $('#post_comp_email2').val();
		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '이메일은 공백없이 입력하세요. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 이메일
	function check_mem_email()
	{
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_mem_email1').val();
		chk_title = $('#post_mem_email1').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_value = $('#post_mem_email2').val();
		chk_title = $('#post_mem_email2').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_value = $('#post_mem_email1').val() + $('#post_mem_email2').val();
		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '이메일은 공백없이 입력하세요. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 이메일 중복체크
	function double_email_chk()
	{
		var chk_value = $('#post_mem_email1').val() + '@' + $('#post_mem_email2').val();

		var chk_msg = check_mem_email();
		if (chk_msg == 'No') return false;
		else
		{
			$.ajax({
				type    : "post", dataType: 'json', url: link_ok,
				data    : {"sub_type":"double_email", "mem_email":chk_value},
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						if (msg.double_chk == "N")
						{
							$('#post_mem_email_chk').val('Y');
							check_auth_popup('사용 가능한 이메일입니다.');
						}
						else
						{
							$('#post_mem_email_chk').val('N');
							check_auth_popup('이미 사용중인 이메일입니다.');
						}
					}
					else check_auth_popup(msg.error_string);
				}
			});
			return false;
		}
	}

//------------------------------------ 전화번호
	function check_tel_num()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_tel_num2').val();
		chk_title = $('#post_tel_num2').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_value = $('#post_tel_num3').val();
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_value = $('#post_tel_num2').val() + $('#post_tel_num3').val();
		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '전화번호는 공백없이 입력하세요. <br />';

		chk_msg = check_num(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '전화번호는 숫자만 입력하세요. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 핸드폰번호
	function check_hp_num()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_hp_num1').val();
		chk_title = $('#post_hp_num1').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_value = $('#post_hp_num2').val();
		chk_title = $('#post_hp_num2').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') action_num++;

		chk_value = $('#post_hp_num3').val();
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') action_num++;

		if (action_num > 0) chk_total = chk_total + chk_title + '<br />';

		chk_value = $('#post_hp_num1').val() + $('#post_hp_num2').val() + $('#post_hp_num3').val();
		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '핸드폰번호는 공백없이 입력하세요. <br />';

		chk_msg = check_num(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '핸드폰번호는 숫자만 입력하세요. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 상호명
	function check_comp_name()
	{
		var chk_msg = ''; var chk_total = '';
		var chk_value = $('#post_comp_name').val();
		var chk_title = $('#post_comp_name').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '상호명은 공백없이 입력하세요. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 대표자명
	function check_boss_name()
	{
		var chk_msg = ''; var chk_total = '';
		var chk_value = $('#post_boss_name').val();
		var chk_title = $('#post_boss_name').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '대표자명은 공백없이 입력하세요. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 이름
	function check_mem_name()
	{
		var chk_msg = ''; var chk_total = '';
		var chk_value = $('#post_mem_name').val();
		var chk_title = $('#post_mem_name').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '이름은 공백없이 입력하세요. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 사업자등록번호확인
	function check_comp_num()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_comp_num1').val();
		chk_title = $('#post_comp_num1').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') action_num++;

		chk_value = $('#post_comp_num2').val();
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') action_num++;

		chk_value = $('#post_comp_num3').val();
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') action_num++;

		if (action_num > 0) chk_total = chk_total + chk_title + '<br />';

		chk_value = $('#post_comp_num1').val() + $('#post_comp_num2').val() + $('#post_comp_num3').val();
		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '사업자등록번호는 공백없이 입력하세요. <br />';

		chk_msg = check_num(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '사업자등록번호는 숫자만 입력하세요. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 사업자등록번호 중복체크
	function double_comp_num_chk()
	{
		var chk_msg = '', chk_total = '';
		var chk_value = $('#post_comp_num1').val() + $('#post_comp_num2').val() + $('#post_comp_num3').val();

		chk_msg = check_comp_num();
		if (chk_msg == 'No') return false;
		else
		{
			$.ajax({
				type    : "post", dataType: 'json', url: link_ok,
				data    : {"sub_type" : "double_comp_num", "comp_num" : chk_value},
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						if (msg.double_chk == "N")
						{
							$('#post_comp_num_chk').val('Y');
							check_auth_popup('사용 가능한 사업자등록번호입니다.');
						}
						else
						{
							$('#post_comp_num_chk').val('N');
							check_auth_popup('이미 사용중인 사업자등록번호입니다.');
						}
					}
					else check_auth_popup(msg.error_string);
				}
			});
			return false;
		}
	}

//------------------------------------ 업종
	function check_upjong()
	{
		var chk_msg = ''; var chk_total = '';
		var chk_value = $('#post_upjong').val();
		var chk_title = $('#post_upjong').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 업태
	function check_uptae()
	{
		var chk_msg = ''; var chk_total = '';
		var chk_value = $('#post_uptae').val();
		var chk_title = $('#post_uptae').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 우편번호
	function check_zipcode()
	{
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_zip_code1').val();
		chk_title = $('#post_zip_code1').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_value = $('#post_zip_code2').val();
		chk_title = $('#post_zip_code2').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_value = $('#post_zip_code1').val() + $('#post_zip_code2').val();
		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '우편번호는 공백없이 입력하세요. <br />';

		chk_msg = check_num(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '우편번호는 숫자만 입력하세요. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 주소
	function check_address()
	{
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_address1').val();
		chk_title = $('#post_address1').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_value = $('#post_address2').val();
		chk_title = $('#post_address2').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}