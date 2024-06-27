
//------------------------------------ 아이디확인
	function check_mem_id(is_msg)
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
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 아이디 중복체크
	function double_id_chk(is_msg)
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
							if(is_msg=='On') check_auth_popup('사용 가능한 아이디입니다.');
						}
						else
						{
							if(is_msg=='On') check_auth_popup('이미 사용중인 아이디입니다.');
						}
					}
					else
					{
						if(is_msg=='On') check_auth_popup(msg.error_string);
					}
				}
			});
			return false;
		}
	}

//------------------------------------ 비밀번호
	function check_mem_pwd(is_msg)
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
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 비밀번호확인
	function check_mem_pwd2(is_msg)
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
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 이메일 - 업체
	function check_comp_email(is_msg)
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
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 이메일
	function check_mem_email(is_msg)
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
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 이메일 중복체크
	function double_email_chk(is_msg)
	{
		var chk_value = $('#post_mem_email1').val() + '@' + $('#post_mem_email2').val();
		
		var chk_msg = check_mem_email('On');
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
							if(is_msg=='On') check_auth_popup('사용 가능한 이메일입니다.');
						}
						else
						{
							$('#post_mem_email_chk').val('N');
							if(is_msg=='On') check_auth_popup('이미 사용중인 이메일입니다.');
						}
					}
					else
					{
						if(is_msg=='On') check_auth_popup(msg.error_string);
					}
				}
			});
			return false;
		}
	}

//------------------------------------ 전화번호
	function check_tel_num(is_msg)
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_tel_num2').val();
		chk_title = $('#post_tel_num2').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_value = $('#post_tel_num3').val();
		chk_title = $('#post_tel_num3').attr('title');
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
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 핸드폰번호
	function check_hp_num(is_msg)
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
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 상호명
	function check_comp_name(is_msg)
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
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 대표자명
	function check_boss_name(is_msg)
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
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 이름
	function check_mem_name(is_msg)
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
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 사업자등록번호확인
	function check_comp_num(is_msg)
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
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 사업자등록번호 중복체크
	function double_comp_num_chk(is_msg)
	{
		var chk_msg = '', chk_total = '';
		var chk_value = $('#post_comp_num1').val() + $('#post_comp_num2').val() + $('#post_comp_num3').val();

		chk_msg = check_comp_num('On');
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
							if(is_msg=='On') check_auth_popup('사용 가능한 사업자등록번호입니다.');
						}
						else
						{
							$('#post_comp_num_chk').val('N');
							if(is_msg=='On') check_auth_popup('이미 사용중인 사업자등록번호입니다.');
						}
					}
					else
					{
						if(is_msg=='On') check_auth_popup(msg.error_string);
					}
				}
			});
			return false;
		}
	}

//------------------------------------ 업종
	function check_upjong(is_msg)
	{
		var chk_msg = ''; var chk_total = '';
		var chk_value = $('#post_upjong').val();
		var chk_title = $('#post_upjong').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		if (chk_total == '') return 'Yes';
		else
		{
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 업태
	function check_uptae(is_msg)
	{
		var chk_msg = ''; var chk_total = '';
		var chk_value = $('#post_uptae').val();
		var chk_title = $('#post_uptae').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		if (chk_total == '') return 'Yes';
		else
		{
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 우편번호
	function check_zipcode(is_msg)
	{
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_zip_code').val();
		chk_title = $('#post_zip_code').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_msg = check_num(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '우편번호는 숫자만 입력하세요. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 주소
	function check_address(is_msg)
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
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}



/////////////////////////////////////////////////////
//
// 2024.03.07 비즈스토리 개편
// 김소령
//
////////////////////////////////////////////////////
	var org_data_list = new Array();
	var sch_data_list = new Array();


// 학교 검색 
// sc_code : 도교육청 코드
	function school_info_data(sc_code, ogr_code, schul_knd_sc_nm)
	{	
		let link_url = local_dir+"/bizstory/member/school_info.php";
		let param = {'sub_type':'view', 'sc_code':sc_code, 'ogr_code' : ogr_code, 'schul_knd_sc_nm':schul_knd_sc_nm};
       
		$.ajax({
			method : 'post', dataType: 'json', url: link_url,
			data : param,
			success : function(msg) {

                if(msg.success_chk == "N")
                {
                    Swal.fire({
                        text: "검색된 학교가 없습니다.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "확인",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
                else
                {	
					let org_list = msg.data[0];
                    let sch_list = msg.data[1];
					let org_opt_list ="<option value=''>관할지청선택</option>";
                    let schul_opt_list ="<option value=''>학교선택</option>";
					
					org_data_list = new Array();
					sch_data_list = new Array();

					let org_code = '';
						
                    //console.log(org_list);
                    for(var i=0; i<org_list.length; i++)
                    {
						org_code = org_list[i]['org_code'];
						org_data_list[i] = org_code;
						org_opt_list +="<option value='"+org_code+"'>"+org_list[i]['ogr_name']+"</option>";
					}
					
					let schul_code = '';
					for(var i=0; i<sch_list.length; i++)
                    {
						schul_code = sch_list[i]['schul_code'];
                        schul_opt_list +="<option value='"+schul_code+"'>"+sch_list[i]['schul_name'] +"</option>";
						
						sch_data_list[schul_code] = {
							'sc_code'	: sch_list[i]['sc_code'],
							'sc_name'	: sch_list[i]['sc_name'],
							'org_code'	: sch_list[i]['org_code'],
							'ogr_name'	: sch_list[i]['ogr_name'],
							'schul_name' : sch_list[i]['schul_name'],
							'tel_num'	: sch_list[i]['tel_num'],
							'fax_num'	: sch_list[i]['fax_num'],
							'zip_code'	: sch_list[i]['zip_code'],
							'address1'	: sch_list[i]['address1'],
							'address2'	: sch_list[i]['address2'],
							'home_page' : sch_list[i]['home_page']
						};
						
                    }
					
					if(ogr_code == '') $('#post_org_code').empty().append(org_opt_list);
					$('#post_schul_code').empty().append(schul_opt_list);
                   
                }

                //console.log(org_data_list);
			},
            error: function (data, status, err) {
                console.log(err);
            },
			complete: function(msg){
                console.log('complete');
            }
		});
	}

//------------------------------------- 학교선택
	function set_schul_data(schul_code)
	{
		if(!schul_code) return false;

		const home_page = sch_data_list[schul_code]['home_page'];
		let domin = '';
		let mb_id = '';

		if (home_page.indexOf('www') != -1) {
		  domin = home_page.split('.');
		  mb_id = domin[1];
		} else {
		  domin = (home_page.split('//'))[1].split('.');
		  mb_id = domin[0] + "_" + domin[1];
		}
		$('#post_mem_id').val(mb_id);

		let tel_num = sch_data_list[schul_code]['tel_num'].split('-');
		$('#post_tel_num1').val(tel_num[0]);
		$('#post_tel_num2').val(tel_num[1]);
		$('#post_tel_num3').val(tel_num[2]);
		
		$('#post_sc_name').val(sch_data_list[schul_code]['sc_name']);
		$('#post_org_code').val(sch_data_list[schul_code]['org_code']);
		$('#post_ogr_name').val(sch_data_list[schul_code]['ogr_name']);
		$('#post_schul_name').val(sch_data_list[schul_code]['schul_name']);
		$('#post_home_page').val(sch_data_list[schul_code]['home_page']);
		$('#post_zip_code').val(sch_data_list[schul_code]['zip_code']);
		$('#post_address1').val(sch_data_list[schul_code]['address1']);
		$('#post_address2').val(sch_data_list[schul_code]['address2']);

		return true;

	}

//------------------------------------ 도교육청
	function check_sc_code(is_msg)
	{
		var chk_msg = ''; var chk_total = '';
		var chk_value = $('#post_sc_code').val();
		var chk_title = $('#post_sc_code').attr('title');
		
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		if (chk_total == '') return 'Yes';
		else
		{
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 학교
	function check_schul_code(is_msg)
	{
		var chk_msg = ''; var chk_total = '';
		var chk_value = $('#post_schul_code').val();
		var chk_title = $('#post_schul_code').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		if (chk_total == '') return 'Yes';
		else
		{
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 신청자
	function check_charge_name(is_msg)
	{
		var chk_msg = ''; var chk_total = '';
		var chk_value = $('#post_charge_name').val();
		var chk_title = $('#post_charge_name').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		if (chk_total == '') return 'Yes';
		else
		{
			if(is_msg=='On') check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 분류
function check_comp_class(is_msg)
{
	var chk_msg = ''; var chk_total = '';
	var chk_value = $('#post_comp_class').val();
	var chk_title = $('#post_comp_class').attr('title');

	chk_msg = check_input_value(chk_value);
	if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

	chk_msg = check_empty_value(chk_value);
	if (chk_msg == 'No') chk_total = chk_total + '분류를 선택하세요. <br />';

	if (chk_total == '') return 'Yes';
	else
	{
		if(is_msg=='On') check_auth_popup(chk_total);
		return 'No';
	}
}

//------------------------------------ 교육청
function check_comp_class(is_msg)
{
	var chk_msg = ''; var chk_total = '';
	var chk_value = $('#post_comp_class').val();
	var chk_title = $('#post_comp_class').attr('title');

	chk_msg = check_input_value(chk_value);
	if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

	chk_msg = check_empty_value(chk_value);
	if (chk_msg == 'No') chk_total = chk_total + '분류를 선택하세요. <br />';

	if (chk_total == '') return 'Yes';
	else
	{
		if(is_msg=='On') check_auth_popup(chk_total);
		return 'No';
	}
}