
	var local_dir = '';
///////////////////////////////////////////////////////////////////////////
//
// Common Ready
//
///////////////////////////////////////////////////////////////////////////
	$(document).ready(function() {
	// Site Html

		var new_height = $(window).height()-30 + 'px';
		$("#layout_table").css({height: new_height});

	// Loading
		$("#loading").fadeIn(400);
		$("#loading").fadeOut(1400);
		$(window).resize(function(){
			$('#loading').css({
				position:'absolute',
				left: ($(window).width() - $('#loading').outerWidth())/2,
				top: ($(window).height() - $('#loading').outerHeight())/2
			});
		});
		$(window).resize();

	// First Selector
		$(".home_pagenavi li:first").css({background: 'none', paddingLeft: '0'});

	// Last Selector
		$(".home_pagenavi li:last a").css({color: '#000000', fontWeight: 'bold'});

	// Fade Link
		$(".animate_over a").css({"opacity" : 0}).hover(function(){
			$(this).stop().animate({"opacity" : 1}, 200); //Change fade-in speed
			}, function(){
			$(this).stop().animate({"opacity" : 0}, 150);//Change fade-out speed
		});

	// Top of Page
		$('.top_btn').hide();
		$(window).scroll(function () {
			try {
				if( $(this).scrollTop() > 100 ) {
					$('.top_btn').fadeIn(300);
				} 
				else {
					$('.top_btn').fadeOut(300);
				}	
			} catch(e) {}
			
		});
		$('.top_btn a').click(function(){
			$('html, body').animate({scrollTop:0}, 500 );
			return false;
		});

	// Ajax 설정
	// async : true, // 비동기
		$.ajaxSetup(
		{
			async       : true,
			processData : true,
			cache       : true,
			timeout     : 50000,
			dataType    : "json",
			type        : "post",
			contentType : "application/x-www-form-urlencoded;charset=UTF-8",
			beforeSubmit: function(){ $("#loading").fadeIn('slow'); },
			complete    : function(){
				$("#loading").fadeOut('slow');
			},
			error       : function(xhr, status, error)
			{
				var error_msg = xhr + "<br />" + status + "<br />" + error + "<br />";
				return false;
			}
		});
	});

	//$.datepicker.setDefaults({ dateFormat: 'yy-mm-dd' });

///////////////////////////////////////////////////////////////////////////
//
// 공통
//
///////////////////////////////////////////////////////////////////////////

//------------------------------------ 글자색깔
	function color_open(f_name)
	{
		var colors = [
			"#ff0000","#ff6c00","#ffaa00","#ffef00","#a6cf00","#009e25","#00b0a2","#0075c8","#3a32c3","#7820b9",
			"#ef007c","#000000","#252525","#464646","#636363","#7d7d7d","#9a9a9a","#ffe8e8","#f7e2d2","#f5eddc",
			"#f5f4e0","#edf2c2","#def7e5","#d9eeec","#c9e0f0","#d6d4eb","#e7dbed","#f1e2ea","#acacac","#c2c2c2",
			"#cccccc","#e1e1e1","#ebebeb","#ffffff","#e97d81","#e19b73","#d1b274","#cfcca2","#cfcca2","#61b977",
			"#53aea8","#518fbb","#6a65bb","#9a54ce","#e573ae","#5a504b","#767b86","#951015","#6e391a","#785c25",
			"#5f5b25","#4c511f","#1c4827","#0d514c","#1b496a","#2b285f","#45245b","#721947","#352e2c","#3c3f45"
		];

		str  = '<table border="0" cellpadding="0" cellspacing="0" class="fontcolortable">';
		str += '<tr>';
		for (i = 0; i < colors.length; i++)
		{
			str += '<td style="background:' + colors[i] + ';" onclick="color_select(\'' + colors[i] + '\', \'' + f_name + '\');"></td>';
			if (i % 10 == 9)
			{
				str += '</tr><tr>';
			}
		}
		str += '</table>';
		$("#fontcolorview").html(str);
	}

//------------------------------------ 글자색깔표시
	function color_select(str1, str2)
	{
		$("#post_code_color").val(str1);
		$("#" + str2).css('color', str1);
		$("#fontcolorview").html('');
	}

//------------------------------------ 글자굵게
	function check_strong(str1, str2)
	{
		$("#" + str2).css('');
		if (str1 == 'Y')
		{
			$("#" + str2).css('font-weight', '900');
		}
		else
		{
			$("#" + str2).css('font-weight', '400');
		}
	}

//------------------------------------ 굵게표시
	function title_blod()
	{
		document.all.subject.style.fontWeight = "900";

		if (document.all.font_bold.value == "Y")
		{
			document.all.font_bold.value = "N";
		}
		else
		{
			document.all.font_bold.value = "Y";
		}
	}

//------------------------------------ 입력여부
	function check_input_value(chk_value)
	{
		if (chk_value == '') return 'No';
		else return 'Yes';
	}

//------------------------------------ 공백여부
	function check_empty_value(chk_value)
	{
		var chk_num = 0;
		for(var i = 0; i < chk_value.length; i++)
		{
			var chr = chk_value.substr(i, 1);
			if(chr == " ") chk_num++;
		}
		if (chk_num > 0) return 'No';
		else return 'Yes';
	}

//------------------------------------ 특수문자처리
	function special_char(str)
	{
		//var re = /[~!@\#$%^&*\()\-=+_'\]\[\\|;?.\`,/<>"]/gi;
		var re = /[~!@\#$%^&*\-=+_'\\|;?.\`,<>"]/gi;
		var str_val = '';

		if (re.test(str))
		{
			str_val = '특수문자는 입력하실수 없습니다.';
		}
		return str_val;
	}

//------------------------------------ 특수문자처리
	function special_char2(str)
	{
		var re = /[~!@\#$%^&*=+'\\|;?\`,/<>"]/gi;

		if (re.test(str)) return 'No';
		else return 'Yes';
	}

//------------------------------------ 권한메세지 - popup
	function check_auth_popup(str)
	{
		var msg = '';
		if (str == 'insert') msg = '등록권한이 없습니다.';
		else if (str == 'modify') msg = '수정권한이 없습니다.';
		else if (str == 'delete') msg = '삭제권한이 없습니다.';
		else if (str == 'view') msg = '보기권한이 없습니다.';
		else if (str == 'down') msg = '다운로드권한이 없습니다.';
		else if (str == 'print') msg = '인쇄권한이 없습니다.';
		else
		{
			if (str == '') msg = '권한이 없습니다.';
			else msg = str;
		}

		try {
			$("#popup_result_msg").html(msg);
			$('#popup_result_msg').dialog('open');
		} catch(e) {
			alert(msg.replaceAll('<br />', '\n'));
		}
	}

//------------------------------------ 목록
	function list_data()
	{
		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "post", dataType: 'html', url: link_list,
			data: $('#listform').serialize(),
			success: function(msg) {
				try {
					$('#data_list').html(msg);
					var hash = location.hash;
					var hash_info = hash.replace( /^#/, '' );
					
					if (hash_info.indexOf('v') >= 0)
					{
						hash = hash_info.substr(hash_info.indexOf('v'));
					}
					
					document.location.href = "#p" + unix_timestamp() + '&' + hash;
				} catch(e) {
					//var typeDataList = document.getElementById('data_list');
					//alert(typeof typeDataList);			
				}
				
			},
			complete: function(){
				$("#backgroundPopup").fadeOut("slow");
				$("#loading").fadeOut('slow');
			}
		});
	}
	
	function unix_timestamp() {
		return Math.floor(new Date().getTime() / 1000);
	}

//------------------------------------ 페이지이동
	function page_move(str)
	{
		var total_page = $('#new_total_page').val();
		var page_num   = $('#page_page_num').val();

		if (str == 'first')
		{
			$('#page_page_num').val(1);
		}
		else if (str == 'last')
		{
			$('#page_page_num').val(total_page);
		}
		else if (str == 'prev')
		{
			page_num = parseInt(page_num) - 1;
			if (page_num < 1) page_num = 1;
			$('#page_page_num').val(page_num);
		}
		else if (str == 'next')
		{
			page_num = parseInt(page_num) + 1;
			if (page_num > total_page) page_num = total_page;
			$('#page_page_num').val(page_num);
		}
		else if (str == 'all')
		{
			$('#page_page_num').val(1);
			$('#page_page_size').append('<option value="1000">1000</option>');
			$('#page_page_size').val(1000);
		}
		else
		{
			$('#page_page_num').val(str);
		}
		list_data();
	}

//------------------------------------ 삭제하기
	function check_delete(idx)
	{
		if (confirm("선택하신 데이타를 삭제하시겠습니까?"))
		{
			check_code_data('delete', '', idx, '');
			view_close();
		}
	}

//------------------------------------ 목록 처리
	function check_code_data(sub_type, sub_action, idx, post_value)
	{
		$('#list_sub_type').val(sub_type);
		$('#list_sub_action').val(sub_action);
		$('#list_idx').val(idx);
		$('#list_post_value').val(post_value);
		
		if (arguments.length >=5) {
			$('#list_mi_idx').val(arguments[4]);
		}

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "post", dataType: 'json', url: link_ok,
			data: $('#listform').serialize(),
			success: function(msg) {
				if (msg.success_chk == "Y")
				{
					if (msg.error_string != '')
					{
						check_auth_popup(msg.error_string);
					}
					
					//폴더번호가 넘어오면 해당 폴더를 삭제한다.
					if (msg.fi_idx != '' && msg.fi_idx !== 'undefined' && msg.fi_idx !== undefined) {

						$.ajax({
							type: 'get', dataType: 'jsonp', url: msg.url + '/folder_ok.php', jsonp : 'callback',
							data: { 'sub_type' : 'folder_delete', 'fi_idx' : msg.fi_idx, 'mem_idx' : msg.mem_idx },
							success: function(msg) {
								if (msg.success_chk != "Y")
								{
									check_auth_popup(msg.error_string);
								} else {
									list_data();	
								}
							}
						});
					} else {
						list_data();	
					}
					
				}
				else
				{
					check_auth_popup(msg.error_string);
				}
			}
		});
	}

//------------------------------------ 팝업등록폼 열기
	function popupform_open(idx)
	{
		$("#popup_notice_view").hide();
		$('#list_idx').val(idx);
		$.ajax({
			type: "get", dataType: 'html', url: link_form,
			data: $('#listform').serialize(),
			success  : function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 100;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':maskHeight}).fadeIn("slow");
				$("#data_form").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}
//------------------------------------ 팝업등록폼 닫기
	function popupform_close()
	{
		try {
			work_insert_close();
		} catch(e) {}
		$("#data_form").slideUp("slow");
		$("#backgroundPopup").fadeOut("slow");
	}
//------------------------------------ 팝업등록폼 닫기
	function popupform_close2()
	{
		$("#data_form2").slideUp("slow");
		$("#backgroundPopup").fadeOut("slow");
		$("#backgroundPopup2").fadeOut("slow");
	}
	
	function popup_file_close2()
	{
		popupform_close();
		$("#data_form2").slideUp("slow");
		$("#backgroundPopup2").fadeOut("slow");
	}

//------------------------------------ 등록, 수정 열기
	function open_data_form(idx)
	{
		$('#list_idx').val(idx);
		$.ajax({
			type: "post", dataType: 'html', url: link_form,
			data: $('#listform').serialize(),
			success: function(msg) {
				$('html, body').animate({scrollTop:0}, 500 );
				$("#data_view").slideUp("slow");
				$("#data_view").slideDown("slow");
				$("#data_view").html(msg);
				$('#data_list').html('');
			}
		});
	}

//------------------------------------ 인쇄 열기
	function open_list_print(idx, print_url)
	{
		$('#list_idx').val(idx);
		f = document.listform;
		f.target = 'print';
		f.action = print_url;
		window.open('', 'print', 'width=1000, height=800, toolbar=no, top=30, left=30, resizable=yes, scrollbars=yes');
		f.submit();
	}

//------------------------------------ 다운로드문서
	function open_data_down(idx, down_url)
	{
		$('#list_idx').val(idx);
		f = document.listform;
		f.target = 'down';
		f.action = down_url;
		window.open('', 'down', 'width=1000, height=800, toolbar=no, top=30, left=30, resizable=yes, scrollbars=yes');
		f.submit();
	}

//------------------------------------ 등록, 수정 닫기
	function close_data_form()
	{
		$("#data_view").slideUp("slow");
		$("#data_view").html('');
		list_data();
	}

//------------------------------------ 상세보기 열기
	function view_open(idx)
	{
		$('#list_idx').val(idx);
		$.ajax({
			type: "get", dataType: 'html', url: link_view,
			data: $('#listform').serialize(),
			success: function(msg) {
				$('html, body').animate({scrollTop:0}, 500 );
				$("#data_view").slideUp("slow");
				$("#data_view").slideDown("slow");
				$("#data_view").html(msg);
			}
		});
	}
//------------------------------------ 상세보기 닫기
	function view_close()
	{
		$("#data_view").slideUp("slow");
	}

//------------------------------------ 직원레이어
	function staff_layer_open(idx)
	{
		$.ajax({
			type: "post", dataType: 'html', url: local_dir + '/bizstory/include/member_info.php',
			data: {'idx':idx},
			success: function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 100;
				var maskWidth  = $(window).width();

				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':maskHeight}).fadeIn("slow");
				$("#data_form").slideDown("slow");
				$(".popupform").css({"top": "80px", "width": "350px", "max-width": "350px"});
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}
//------------------------------------ 직원 레이어
	function show_staff(obj)
	{
		if(document.getElementById(obj).className == "none")
		{
			document.getElementById(obj).className = "mem_show";

			$.ajax({
				type: "post", dataType: 'html', url: local_dir + '/bizstory/include/member_info.php',
				data: {'chk_idx':obj},
				success: function(msg) {
					$('#' + obj).html(msg);
				}
			});

			return false;
		}
		if(document.getElementById(obj).className == "mem_show")
		{
			document.getElementById(obj).className = "none";
			return false;
		}
	}

//------------------------------------ 즐겨찾기 열기
	function bookmark_open()
	{
		$.ajax({
			type: "post", dataType: 'html', url: local_dir + '/bizstory/include/bookmark.php',
			data: '',
			success  : function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 200;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':maskHeight}).fadeIn("slow");
				$("#data_form").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}
//------------------------------------ 즐겨찾기 닫기
	function bookmark_close()
	{
		$("#data_form").slideUp("slow");
		$("#backgroundPopup").fadeOut("slow");
	}

//------------------------------------ 로그아웃
	function login_out()
	{
		$.ajax({
			type: 'post', dataType: 'json', url: local_dir + '/bizstory/member/logout.php',
			data: {},
			success: function(msg) {
				if (msg.success_chk == "Y")
				{
					//$.cookie('auto_value', '1', { expires: 7 });
					//alert($.cookie('auto_value'));
					location.href = local_dir + '/';
				}
				else check_auth_popup(msg.error_string);
			}
		});
	}

//------------------------------------ 팝업로그인 열기
	function popupform_login(fmode, smode)
	{
		$.ajax({
			type: "post", dataType: 'html', url: local_dir + '/bizstory/member/login_popup.php',
			data: {'fmode':fmode, 'smode':smode},
			success  : function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 100;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':maskHeight}).fadeIn("slow");
				$("#data_form").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 팝업창 쪽지보내기
	function popup_msg(idx)
	{
		$.ajax({
			type: "post", dataType: 'html', url: local_dir + '/bizstory/myinfo/popup_msg.php',
			data: {'receive_idx':idx},
			success: function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 200;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':maskHeight}).fadeIn("slow");
				$("#data_form").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 팝업창 SMS보내기
	function popup_sms(idx)
	{
		$.ajax({
			type: "post", dataType: 'html', url: local_dir + '/bizstory/myinfo/popup_sms.php',
			data: {'receive_idx':idx},
			success: function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 200;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':maskHeight}).fadeIn("slow");
				$("#data_form").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

///////////////////////////////////////////////////////////////////////////
//
// 지사별 정보관련 관련
//
///////////////////////////////////////////////////////////////////////////

//------------------------------------ 지사별 목록
	function move_part(part_idx)
	{
		var chk_id = '';
		$('#list_code_part').val(part_idx);
		$('#part_menu a').removeClass('select');
		$('#part_' + part_idx).addClass('select');

		if ($.find('#work_code_part') != '')
		{
			$('#work_code_part').val(part_idx);
		}

	// 거래처분류
		chk_id = document.getElementById("search_shgroup");
		if(chk_id) part_information(part_idx, 'client_group', 'search_shgroup', $('#search_shgroup').val(), 'select');

	// 직원그룹
		chk_id = document.getElementById("search_shgroup");
		if(chk_id) part_information(part_idx, 'staff_group', 'search_shgroup', $('#search_shgroup').val(), 'select');

	// 접수분류
		chk_id = document.getElementById("search_shclass");
		if(chk_id) part_information(part_idx, 'receipt_class', 'search_shclass', $('#search_shclass').val(), 'select');

	// 접수상태
		chk_id = document.getElementById("search_shstatus");
		if(chk_id) part_information(part_idx, 'receipt_status', 'search_shstatus', $('#search_shstatus').val(), 'select');

	// 접수담당자
		chk_id = document.getElementById("search_shstaff");
		if(chk_id) part_information(part_idx, 'staff_info', 'search_shstaff', $('#search_shstaff').val(), 'select');

	// 업무분류
		chk_id = document.getElementById("search_shwclass");
		if(chk_id) part_information(part_idx, 'work_class', 'search_shwclass', $('#search_shwclass').val(), 'select');

	// 업무상태
		chk_id = document.getElementById("search_shwstatus");
		if(chk_id) part_information(part_idx, 'work_status', 'search_shwstatus', $('#search_shwstatus').val(), 'select');

	// 업무담당자
		chk_id = document.getElementById("search_smember");
		if(chk_id) part_information(part_idx, 'staff_info', 'search_smember', $('#search_smember').val(), 'select_allno');

	// 상담분류
		chk_id = document.getElementById("search_sconsclass");
		if(chk_id) part_information(part_idx, 'consult_class', 'search_sconsclass', $('#search_sconsclass').val(), 'select');

	// 에이전트
		chk_id = document.getElementById("list_code_agent");
		if(chk_id) agent_type(part_idx, '');

	// 파일관리
		var fmode = $('#list_fmode').val();
		var smode = $('#list_smode').val();

		if (fmode == 'filecenter' && smode == 'filemanager')
		{
			chk_id = document.getElementById("list_up_idx");
			if(chk_id)
			{
				$('#list_up_idx').val('');
				$('#list_up_level').val('');
				list_left_data();
			}
		}

		view_close();
		list_data();
	}

//------------------------------------ 지사별 목록
	function part_information(code_part, select_class, field_id, field_value, select_type)
	{
		if (code_part == "") code_part = $('#post_part_idx').val();
		var shsgroup = $('#search_shsgroup').val();

		$.ajax({
			type: "post", dataType : "json", url: local_dir + '/bizstory/comp_set/part_information.php',
			data: {
				"code_part" : code_part,
				"select_class" : select_class,
				"field_value" : field_value,
				"select_type" : select_type,
				"shsgroup" : shsgroup
			},
			success  : function(msg) {
				$('#' + field_id).empty();
				if (select_type == 'select')
				{
					$('#' + field_id).append('<option value="all">' + $('#' + field_id).attr('title') + '</option>');
				}
				else if (select_type == 'select_allno')
				{
					$('#' + field_id).append('<option value="">' + $('#' + field_id).attr('title') + '</option>');
				}
				else
				{
					$('#' + field_id).append('<option value="">' + $('#' + field_id).attr('title') + '</option>');
				}

				if (msg.success_chk == "Y")
				{
					$.each(msg.result_data, function() {

						var empty_str = '';
						for (var ii = 2; ii <= this.menu_dpeth; ii++)
						{
							empty_str = empty_str + '&nbsp;&nbsp;&nbsp;';
						}

						if (this.selected == 'Y')
						{
							$('#' + field_id).append('<option value= ' + this.idx + ' selected="selected">' + empty_str + this.name + '</option>');
						}
						else
						{
							$('#' + field_id).append('<option value= ' + this.idx + '>' + empty_str + this.name + '</option>');
						}
					});
				}
				else
				{
					if (msg.result_data != '')
					{
						check_auth_popup(msg.result_data);
					}
				}
			}
		});
	}

//------------------------------------ 지사별 접수분류 - 단계별 표시
	function select_receipt_class(code_part, field_id, field_name, field_value, select_type, view_id)
	{
		if (code_part == "") code_part = $('#post_part_idx').val();

		$.ajax({
			type:"post", dataType:"html", url:local_dir + '/bizstory/comp_set/select_receipt_class.php',
			data: {
				"code_part" : code_part,
				"field_id" : field_id, "field_name" : field_name, "field_value" : field_value,
				"select_type" : select_type
			},
			success : function(msg) {
				if (view_id == '') view_id = 'receipt_class_view';
				$('#' + view_id).html(msg);
			}
		});
	}

//------------------------------------ 거래처 접수창으로 이동
	function client_receipt_move(client_idx)
	{
		$.ajax({
			type:"post", dataType:"json", url: local_dir + '/bizstory/work/cms_login.php',
			data: {"client_idx": client_idx},
			success : function(msg) {
				if (msg.success_chk == "Y")
				{
					window.open(local_dir + '/cms/receipt.php', '_blank');
				}
				else check_auth_popup(msg.error_string);
			}
		});
	}

//------------------------------------ 거래처 프로젝트창으로 이동
	function client_board_move(client_idx, bs_idx)
	{
		$.ajax({
			type:"post", dataType:"json", url: local_dir + '/bizstory/work/cms_login.php',
			data: {"client_idx": client_idx},
			success : function(msg) {
				if (msg.success_chk == "Y")
				{
					window.open(local_dir + '/cms/board_project/board.php?bs_idx=' + bs_idx, '_blank');
				}
				else check_auth_popup(msg.error_string);
			}
		});
	}



























//------------------------------------ 상담검색
	function check_total_search()
	{
		var stext       = $('#total_search_keyword').val();
		var stext_title = $('#total_search_keyword').attr('title');
		if (stext == stext_title) stext = '';

		chk_msg = check_input_value(stext_title);
		if (chk_msg == 'No')
		{
			check_auth_popup(chk_total);
		}
		else
		{
			return true;
		}
		return false;
	}

//------------------------------------ 새창띄우기
	function new_open(URL, WinName, WinWidth, WinHeight, ScrollYN)
	{
		var NewWin = window.open(URL, WinName, 'toolbar=no, top=100, left=100, width=' + WinWidth + ', height=' + WinHeight + ', resizable=yes, scrollbars=' + ScrollYN);
		NewWin.focus();
	}

//------------------------------------ 길이확인
	function check_length_value(chk_value, min_len, max_len)
	{
		if (chk_value.length < min_len || chk_value.length > max_len) return 'No';
		else return 'Yes';
	}

//------------------------------------ 숫자만 안됨
	function check_num_no(chk_value)
	{
		if (chk_value != '')
		{
			if (!isNaN(chk_value)) return 'No';
			else return 'Yes';
		}
		else return 'Yes';
	}

//------------------------------------ 숫자만 입력
	function check_num(chk_value)
	{
		if (chk_value != '')
		{
			if (isNaN(chk_value)) return 'No';
			else return 'Yes';
		}
		else return 'Yes';
	}

//------------------------------------ 첫자는 영어만
	function check_first_eng(chk_value)
	{
		var chk_num = 0;
		for(var i = 0; i < chk_value.length; i++)
		{
			var chr = chk_value.substr(i, 1);
			if(i == 0 && (chr >= "0" && chr <= "9")) chk_num++;
		}
		if (chk_num > 0) return 'No';
		else return 'Yes';
	}

//------------------------------------ 영문, 숫자만
	function check_eng_num(chk_value)
	{
		var chk_num = 0;
		for(var i = 0; i < chk_value.length; i++)
		{
			var chr = chk_value.substr(i, 1);
			if((chr < "0" || chr > "9") && (chr < "a" || chr > "z") && (chr < "A" || chr > "Z")) chk_num++;
		}
		if (chk_num > 0) return 'No';
		else return 'Yes';
	}

//------------------------------------ 일치여부
	function check_value_same(chk_value1, chk_value2)
	{
		if (chk_value1 == '' || chk_value2 == '')
		{
			return 'Yes';
		}
		else
		{
			if (chk_value1 == chk_value2) return 'Yes';
			else return 'No';
		}
	}

//------------------------------------ 메일주소넣기
	function email_input(str1, str2)
	{
		document.getElementById(str1).value = document.getElementById(str2).value;
	}

//------------------------------------ 권한메세지
	function check_auth(str)
	{
		$("#popup_notice_view").hide();

		var msg = '';
		if (str == 'insert') msg = '등록권한이 없습니다.';
		else if (str == 'modify') msg = '수정권한이 없습니다.';
		else if (str == 'delete') msg = '삭제권한이 없습니다.';
		else if (str == 'view') msg = '보기권한이 없습니다.';
		else
		{
			if (str != '') msg = str;
			else msg = '권한이 없습니다.';
		}

		$("#popup_notice_view").show();
		$("#popup_notice_memo").html(msg);
		$("#backgroundPopup").fadeOut("slow");
	}

//------------------------------------ 팝업답변폼 열기
	function popupform_open_reply(idx)
	{
		$("#popup_notice_view").hide();
		$('#list_idx').val(idx);
		$('#list_sub_type').val('replyform');
		$.ajax({
			type: "get", dataType : 'html', url: link_form,
			data: $('#listform').serialize(),
			success: function(msg) {
				var maskHeight = $(document).height();
				var maskWidth = $(window).width();
				$("#data_form").slideDown("slow");
				$("#backgroundPopup").css({"opacity": "0.7",'width':maskWidth,'height':maskHeight}).fadeIn("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			},
			complete: function(){
				$("#loading").fadeOut('slow');
			}
		});
	}

//------------------------------------ 팝업서브창 열기
	function popupsub_open(org_idx, url_link)
	{
		$("#popup_notice_view").hide();
		$('#list_org_idx').val(org_idx);
		$.ajax({
			type: "get", dataType: 'html', url: url_link,
			data: $('#listform').serialize(),
			success  : function(msg) {
				var maskHeight = $(document).height() + 1000;
				var maskWidth  = $(window).width();
				$("#data_form").slideDown("slow");
				$("#backgroundPopup").css({"opacity": "0.7",'width':maskWidth,'height':maskHeight}).fadeIn("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 팝업서브창 등록
	function popupsub_form(org_idx, idx, url_link)
	{
		$("#popup_notice_view").hide();
		$('#list_org_idx').val(org_idx);
		$('#list_idx').val(idx);
		$.ajax({
			type: "post", dataType: 'html', url: url_link,
			data: $('#listform').serialize(),
			success: function(msg) {
				var maskHeight = $(document).height() + 500;
				var maskWidth  = $(window).width();
				$("#data_form").slideDown("slow");
				$("#loading").fadeIn('slow').fadeOut('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':maskWidth,'height':maskHeight}).fadeIn("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 업로드파일 확장자확인
	function check_file_ext()
	{
		var chk_total = '';
		var file_idx = $('#post_f_name_num').val();
			file_idx = parseInt(file_idx);
		var regex = /\.(perl|pl|cgi|php|php3|php4|inc|sql|ini|asp|asx|jsp|java|class|html|htm|phtml|js|dll)$/i; // 업로드 안되는 파일
		for (var i = 1; i <= file_idx; i++)
		{
			if($('#post_f_name' + i).val().match(regex))
			{
				chk_total = chk_total + i + '번째 업로드 안되는 파일입니다.<br />';
			}
		}

		return chk_total;
	}

//------------------------------------ 파일삭제
	function file_delete(idx, org_idx)
	{
		$("#popup_notice_view").hide();
		$('#list_idx').val(idx);
		$('#list_org_idx').val(org_idx);
		$('#list_sub_type').val('file_delete');
		
		$.ajax({
			type: 'post', dataType: 'json', url: link_ok,
			data: $('#listform').serialize(),
			success: function(msg) {
				if (msg.success_chk == "Y") popupform_open(org_idx);
				else check_auth_popup(msg.error_string);
			}
		});
	}

//------------------------------------ 파일삭제
	function check_file_delete(idx)
	{
		$("#popup_notice_view").hide();
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: {'sub_type':'file_delete', 'idx':idx},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						popupform_open(msg.org_idx);
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		return false;
	}

//------------------------------------ 폼에서 파일삭제
	function form_file_delete(idx)
	{
		$("#popup_notice_view").hide();
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			$.ajax({
				type: 'post', dataType: 'xml', url: link_ok,
				data: {'sub_type':'file_delete', 'idx':idx},
				success: function(msg) {

					var success_chk = $(msg).find('success_chk').text();
					var sort = $(msg).find('sort').text();
					var file_view = $(msg).find('file_view').text();
					var file_size = $(msg).find('file_size').text();
					if (success_chk == "Y")
					{
						check_auth_popup('정상적으로 처리되었습니다.');
						$('#form_file_view' + sort).html(file_view);
						file_setting('file_fname' + sort, '/data/tmp', '10485760', 'upload_fname' + sort, 'work', '');
					}
					else check_auth_popup('정상적으로 처리가 되지 않았습니다.');
				}
			});
		}
		return false;
	}

//------------------------------------ 콤마넣기
	function number_format(num)
	{
		num = num.split(',').join('');
		var arr = num.split('.');
		var num = new Array();
		for (i = 0; i <= arr[0].length - 1; i++)
		{
			num[i] = arr[0].substr(arr[0].length - 1 - i, 1);
			if (i % 3 == 0 && i != 0) num[i] += ',';
		}
		num = num.reverse().join('');
		if (!arr[1]) chknum = num;
		else chknum = num + '.' + arr[1];

		return chknum;
	}

//------------------------------------ 콤마넣기 - 폼태그로
	function input_comma(chknum)
	{
		num = chknum.value;
		num = num.split(',').join('');
		var arr = num.split('.');
		var num = new Array();
		for (i = 0; i <= arr[0].length - 1; i++)
		{
			num[i] = arr[0].substr(arr[0].length - 1 - i, 1);
			if (i % 3 == 0 && i != 0) num[i] += ',';
		}
		num = num.reverse().join('');
		if (!arr[1]) chknum.value = num;
		else chknum.value = num + '.' + arr[1];
	}

//------------------------------------ 체크박스 수 chk_checkbox_num('memidx')
	function chk_checkbox_num(val)
	{
		var obj = document.getElementsByTagName("input");
		var act = 0;
		var i = 0;
		var str = new Array;

		while(obj[i])
		{
			if(obj[i].type == "checkbox" && obj[i].disabled == false)
			{
				if (obj[i].getAttribute("id") != null)
				{
					str = obj[i].getAttribute("id").split("_");
				}
				else
				{
					str[0] = "";
				}
				if(str[0] == val && obj[i].checked == true)
				{
					act++;
				}
			}
			i++;
		}
		return act;
	}

//------------------------------------ 체크박스 모두체크 해제 check_all('memidx', this)
	function check_all(val, chk)
	{
		var obj = document.getElementsByTagName("input");
		var act;
		var i = 0;
		var str = new Array;

		if(chk.checked == true) act = true;
		else act = false;

		while(obj[i])
		{
			if (obj[i].getAttribute("id") != null)
			{
				str = obj[i].getAttribute("id").split("_");
			}
			else
			{
				str[0] = "";
			}

			if(obj[i].type == "checkbox" && obj[i].disabled == false && str[0] == val)
			{
				obj[i].checked = act;
			}
			i++;
		}
	}

//------------------------------------ 체크박스 모두체크 해제 check_all('memidx', this)
	function check_all2(val, chk, tye)
	{
		var obj = document.getElementsByTagName("input");
		var act;
		var i = 0;
		var str = new Array;
		var str2 = new Array;
		if (tye == '') tye = '1';

		if(chk.checked == true) act = true;
		else act = false;

		while(obj[i])
		{
			if (obj[i].type == "checkbox")
			{
				if (obj[i].getAttribute("id") != null)
				{
					str  = obj[i].getAttribute("id").split("_");
					str2 = obj[i].getAttribute("id").split("_");
					if (tye == '1')
					{
						str2 = str[0].split("-");
					}
				}
				else
				{
					str[0]  = "";
					str2[0] = "";
				}

				if(obj[i].disabled == false && str2[0] == val)
				{
					obj[i].checked = act;
				}
			}
			i++;
		}
	}

//------------------------------------ 자동 Tab 이동
	var isNN = (navigator.appName.indexOf("Netscape")!=-1);

	function autoTab(input,len, e)
	{
		var keyCode = (isNN) ? e.which : e.keyCode; 
		var filter = (isNN) ? [0,8,9] : [0,8,9,16,17,18,37,38,39,40,46];
		if(input.value.length >= len && !containsElement(filter,keyCode))
		{
			input.value = input.value.slice(0, len);
			input.form[(getIndex(input)+1) % input.form.length].focus();
		}
		return true;
	}
	function containsElement(arr, ele)
	{
		var found = false, index = 0;
		while(!found && index < arr.length)
			if(arr[index] == ele)
				found = true;
			else
				index++;
		return found;
	}
	function getIndex(input)
	{
		var index = -1, i = 0, found = false;
		while (i < input.form.length && index == -1)
		if (input.form[i] == input)index = i;
		else i++;
		return index;
	}

///////////////////////////////////////////////////////////////////////////
//
// 다른페이지일 경우
//
///////////////////////////////////////////////////////////////////////////

//------------------------------------ 다른페이지 열기
	function other_page_open(idx, url)
	{
		$('#list_idx').val(idx);
		$.ajax({
			type: "post", dataType: 'html', url: url,
			data: $('#listform').serialize(),
			success: function(msg) {
				var maskHeight = $(document).height() + 1000;
				var maskWidth = $(window).width();
				$("#data_form").slideDown("slow");
				$("#backgroundPopup").css({"opacity": "0.7",'width':maskWidth,'height':maskHeight}).fadeIn("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 다른페이지 목록처리
	function other_check_code(sub_type, sub_action, idx, post_value, url)
	{
		$('#other_sub_type').val(sub_type);
		$('#other_sub_action').val(sub_action);
		$('#other_idx').val(idx);
		$('#other_post_value').val(post_value);

		$.ajax({
			type: "post", dataType: 'json', url: link_ok,
			data: $('#otherform').serialize(),
			success: function(msg) {
				if (msg.success_chk == "Y") other_page_open(msg.idx, url);
				else check_auth_popup(msg.error_string);
			}
		});
	}

///////////////////////////////////////////////////////////////////////////
//
// 목록, 검색 관련 관련
//
///////////////////////////////////////////////////////////////////////////

//------------------------------------ 페이지이동 - 댓글
	function page_move_comment(str, func_name)
	{
		var total_page = $('#' + func_name + '_new_total_page').val();
		var page_num   = $('#' + func_name + '_page_page_num').val();

		if (str == 'first')
		{
			$('#' + func_name + '_page_page_num').val(1);
		}
		else if (str == 'last')
		{
			$('#' + func_name + '_page_page_num').val(total_page);
		}
		else if (str == 'prev')
		{
			page_num = parseInt(page_num) - 1;
			if (page_num < 1) page_num = 1;
			$('#' + func_name + '_page_page_num').val(page_num);
		}
		else if (str == 'next')
		{
			page_num = parseInt(page_num) + 1;
			if (page_num > total_page) page_num = total_page;
			$('#' + func_name + '_page_page_num').val(page_num);
		}
		else if (str == 'all')
		{
			$('#' + func_name + '_page_page_num').val(1);
			$('#' + func_name + '_page_page_size').append('<option value="1000">1000</option>');
			$('#' + func_name + '_page_page_size').val(1000);
		}
		else
		{
			$('#' + func_name + '_page_page_num').val(str);
		}
		page_move_check(func_name);
	}
	function page_move_check(str)
	{
		if (str == 'report')
		{
			report_list_data();
		}
		else if (str == 'comment')
		{
			comment_list_data();
		}
		else if (str == 'memo')
		{
			memo_list_data();
		}
	}

//------------------------------------ 정렬이동
	function order_move(str1, str2)
	{
		document.listform.sorder1.value = str1;
		document.listform.sorder2.value = str2;
		list_data();
	}

//------------------------------------ 액셀
	function list_excel()
	{
		f = document.searchform;
		f.action = link_excel;
		f.submit();
	}

//------------------------------------ 인쇄
	function list_print()
	{
		f = document.searchform;
		f.target = 'print';
		f.action = link_print;
		window.open('', 'print', 'width=800, height=800, toolbar=no, top=30, left=30, resizable=yes, scrollbars=yes');
		f.submit();
	}

//------------------------------------ 선택인쇄
	function list_print_detail()
	{
		f = document.listform;
		f.target = 'print';
		f.action = link_print_detail;
		window.open('', 'print', 'width=1000, height=800, toolbar=no, top=30, left=30, resizable=yes, scrollbars=yes');
		f.submit();
	}

//------------------------------------ 보기인쇄
	function view_print()
	{
		f = document.viewform;
		$('#view_print_type').val($('#print_print_type').val());
		f.target = 'print';
		f.action = link_print_view;
		window.open('', 'print', 'width=800, height=800, toolbar=no, top=30, left=30, resizable=yes, scrollbars=yes');
		f.submit();
	}

///////////////////////////////////////////////////////////////////////////
//
// 쿠키 관련
//
///////////////////////////////////////////////////////////////////////////

// 값에 대한 쿠키생성
	function check_cookies(tag_id)
	{
		var chk_value = $('#' + tag_id).val();
		$.cookie(tag_id + '_save', chk_value);
	}

// 생성된 쿠키값넣기, 삭제
	function cookies_value_make(chk_str, cookie_type)
	{
		if (document.cookie && document.cookie != '')
		{
			var cookies = document.cookie.split(';');
			for (var i = 0; i < cookies.length; i++)
			{
				var cookie = jQuery.trim(cookies[i]);
				var cookie_arr = cookie.split('=');
				var tag_name = cookie_arr[0].substring(0, cookie_arr[0].length-5);

				var start_len = chk_str.length;
				var tag_start = tag_name.substring(0, start_len);

				if (tag_start == chk_str)
				{
					var cookie_value = decodeURIComponent(cookie_arr[1]);

					if (cookie_type == "insert")
					{
						$('#' + tag_name).val(cookie_value);
					}
					else
					{
						$.cookie(cookie_arr[0], null);
					}
				}
			}
		}
	}

///////////////////////////////////////////////////////////////////////////
//
// 등록, 수정
//
///////////////////////////////////////////////////////////////////////////
//------------------------------------ 등록, 수정
	function data_form_open(idx)
	{
		$("#popup_notice_view").hide();
		$('#list_idx').val(idx);
		if (idx == '') $('#list_sub_type').val('postform');
		else $('#list_sub_type').val('modifyform');
		location.href = '?' + $('#listform').serialize();
	}

//------------------------------------ 기타팝업창
	function data_form_sub_open(idx1, idx2, link_url)
	{
		$("#popup_notice_view").hide();
		$('#list_idx').val(idx1);
		$('#list_idx_sub').val(idx2);
		$.ajax({
			type: "post", dataType: 'html', url: link_url,
			data: $('#listform').serialize(),
			success: function(msg) {
				$('html, body').animate({scrollTop:0}, 500 );
				$("#data_view").slideUp("slow");
				$("#data_view").slideDown("slow");
				$("#data_view").html(msg);
			}
		});
	}

//------------------------------------ 기타팝업창 닫기
	function data_form_sub_close()
	{
		$("#popup_notice_view").hide();
		$("#data_view").slideUp("slow");
	}

//------------------------------------ 에이전트타입보여주기
	function agent_type(code_part, code_agent)
	{
		$.ajax({
			type: "post", dataType: 'html', url: link_agent,
			data: {"part_idx" : code_part, "agent_type" : code_agent},
			success : function(msg) {
				$('#agent_menu').html(msg);
			}
		});

		$('#list_code_part').val(code_part);
		$('#list_code_agent').val(code_agent);
		$('#agent_menu a').removeClass('select');
		$('#agent_type_' + code_agent).addClass('select');

		list_data();
	}

///////////////////////////////////////////////////////////////////////////
//
// 첨부파일 미리보기
//
///////////////////////////////////////////////////////////////////////////
//------------------------------------ 문서 미리보기1
	function file_preview(f_class, f_idx)
	{
		$.ajax({
			async : false,
			type: "post", dataType: 'html', url: '/bizstory/include/file_preview.php',
			data: { 'f_class' : f_class, 'f_idx' : f_idx },
			success: function(msg) {
				$("#preview_file_result").html(msg);
			}
		});
	}

//------------------------------------ 문서 미리보기 결과
//success -> 변환 상태에 대한 성공여부를 나타낸다. ‘Y' 성공 / 'N' 실패
//index_url -> 변환된 결과를 인덱스한 html 파일의 url 값을 나타낸다.
//image_url -> 변환한 이미지에 저장되어 있는 위치의 url 값을 나타낸다.
//page_count -> 총 생성되는 전체 이미지 수를 나타낸다.
/*
	function file_preview_result(agent_code, user_id, pre_idx, file_name)
	{
		$("#loading2").fadeIn('slow');
		$(window).resize(function(){
			$('#loading2').css({
				position:'absolute',
				left: ($(window).width() - $('#loading2').outerWidth())/2,
				top: ($(window).height() - $('#loading2').outerHeight())/2
			});
		});
		$(window).resize();

		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$('html, body').animate({scrollTop:0}, 500);
		$.ajax({
			type: 'get', dataType: 'jsonp', url: 'http://view.ubstory.net/convert_result_hcms.php', jsonp : 'callback',
			data: { 'job_id' : 'demo', 'agent_code' : agent_code, 'user_id' : user_id, 'key' : pre_idx },
			beforeSend: function(){ $("#loading2").fadeIn('slow'); },
			success: function(msg) {
				console.log(msg);
				if (msg.success == 'Y')
				{
					if (msg.image_format == 'html') {
						
					} else {
						file_preview_html(msg.image_format, msg.image_url, msg.page_count, file_name, msg.file_code);
					}
				}
				else
				{
					alert(msg.err_desc + '(' + msg.err_code + ')');
				}
			},
			complete: function(){
				$("#loading2").fadeOut('slow');
				$("#backgroundPopup").fadeOut("slow");
			}
		});
	}
*/
	function file_preview_result(agent_code, user_id, pre_idx, file_name)
	{
		$("#loading2").fadeIn('slow');
		$(window).resize(function(){
			$('#loading2').css({
				position:'absolute',
				left: ($(window).width() - $('#loading2').outerWidth())/2,
				top: ($(window).height() - $('#loading2').outerHeight())/2
			});
		});
		$(window).resize();

		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$('html, body').animate({scrollTop:0}, 500);
		$.ajax({
			type: 'get', dataType: 'jsonp', url: 'http://view.ubstory.net/convert_result_hcms.php', jsonp : 'callback',
			data: { 'job_id' : 'demo', 'agent_code' : agent_code, 'user_id' : user_id, 'key' : pre_idx },
			beforeSend: function(){ $("#loading2").fadeIn('slow'); },
			success: function(msg) {

				if (msg.success == 'Y')
				{
					if (msg.image_format == 'html') {
						var url = msg.image_url + "/" + msg.file_code;
						alert(url);
						var view_popup = window.open('/bizstory/common/popup/html_preview.php?url=' + encodeURIComponent(url) + "&file_name=" + encodeURIComponent(file_name), "", "");
						
					} else {
						file_preview_html(msg.image_format, msg.image_url, msg.page_count, file_name, msg.file_code);
					}
				}
				else
				{
					alert(msg.err_desc + '(' + msg.err_code + ')');
				}
			},
			complete: function(){
				$("#loading2").fadeOut('slow');
				$("#backgroundPopup").fadeOut("slow");
			}
		});
	}
//------------------------------------ 문서 미리보기 페이지구성
	//function file_preview_html(index_url, image_url, page_count, file_name)
	function file_preview_html(image_format, image_url, page_count, file_name, file_code)
	{
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "get", dataType: 'html', url: '/bizstory/include/file_preview_html.php',
			data: {
				'image_format' : image_format,
				'image_url' : image_url,
				'page_count' : page_count,
				'file_name' : file_name,
				'file_code' : file_code },
			beforeSend: function(){ $("#loading2").fadeIn('slow'); },
			success: function(msg) {
				$('#popup_file_preview').html(msg);
				$('#document_preview a').lightBox();
				if (page_count > 0)
				{
					$('#image_1').click();
				}
				else
				{
					alert('아직 제공되지 않습니다.');
				}
			},
			complete: function(){
				$("#loading2").fadeOut('slow');
				$("#backgroundPopup").fadeOut("slow");
			}
		});
	}

//------------------------------------ 이미지 미리보기
	function file_preview_images(f_class, idx, file_num)
	{
	// Loading - document
		$("#loading2").fadeIn('slow');
		$(window).resize(function(){
			$('#loading2').css({
				position:'absolute',
				left: ($(window).width() - $('#loading2').outerWidth())/2,
				top: ($(window).height() - $('#loading2').outerHeight())/2
			});
		});
		$(window).resize();

		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "get", dataType: 'html', url: '/bizstory/include/file_preview_images.php',
			data: { 'idx' : idx, 'f_class' : f_class },
			beforeSend: function(){ $("#loading2").fadeIn('slow'); },
			success: function(msg) {
				$('#popup_file_preview').html(msg);
				//alert(msg);
				$('#images_preview a').lightBox();
				if (file_num > 0)
				{
					$('#img_image_1').click();
				}
				else
				{
					alert('이미지파일이 없습니다.');
				}
			},
			complete: function(){
				$("#loading2").fadeOut('slow');
				$("#backgroundPopup").fadeOut("slow");
			}
		});
	}

//------------------------------------ 문서 미리보기 - 변환하지 않는
	function file_preview_other(f_class, idx, file_ext)
	{
	// Loading - document
		$("#loading2").fadeIn('slow');
		$(window).resize(function(){
			$('#loading2').css({
				position:'absolute',
				left: ($(window).width() - $('#loading2').outerWidth())/2,
				top: ($(window).height() - $('#loading2').outerHeight())/2
			});
		});
		$(window).resize();

		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "get", dataType: 'html', url: '/bizstory/include/file_preview_other.php',
			data: { 'idx' : idx, 'f_class' : f_class, 'file_ext' : file_ext },
			beforeSend: function(){ $("#loading2").fadeIn('slow'); },
			success: function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskWidth  = $(window).width();
				$("#data_form").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			},
			complete: function(){
				$("#loading2").fadeOut('slow');
			}
		});
	}


/*-----뷰스토리 문서 미리보기 -----------------------------------------------------------------------------------------------------------------------------------------------*/
	// file_url : 파일경로
	// file_name: 파일명 - 파일경로에서 파일명을 추출할 수 없는 경우 파일명을 입력해주십시오.
	function previewAjax(file_url, file_name) 
	{
		var apiKey = "WBEWGHWSA1L1M7A1DOF4XG";
		var cc = "biz";
		var fileUrl = encodeURIComponent(file_url);
		var fileName = encodeURIComponent(file_name);
		window.open("https://viewstory.net/previewAjax.do?apikey={0}&cc={1}&url={2}&fileName={3}".format(apiKey, cc, fileUrl, fileName),"a", "width=1200, height=1000, left=100, top=50");
	}

	function preListen(file_url, file_name) 
	{
		var apiKey = "WBEWGHWSA1L1M7A1DOF4XG";
		var cc = "biz";
		var fileUrl = encodeURIComponent(file_url);
		var fileName = encodeURIComponent(file_name);
		window.open("https://viewstory.net/voiceOverAjax.do?apikey={0}&cc={1}&url={2}&fileName={3}".format(apiKey, cc, fileUrl, fileName), "a", "width=1200, height=1000, left=100, top=50");
	}
	String.prototype.format = function() {
		var formatted = this;
		for( var arg in arguments ) {
			formatted = formatted.replace("{" +arg + "}", arguments[arg]);
		}
		   return formatted;
	};

/*-----뷰스토리 문서 미리보기 -----------------------------------------------------------------------------------------------------------------------------------------------*/





//------------------------------------ 다른페이지 열기
	function other_open_data_form(url, idx, idx_sub)
	{
		$('#list_idx').val(idx);
		$('#list_idx_sub').val(idx_sub);
		$.ajax({
			type: "post", dataType: 'html', url: url,
			data: $('#listform').serialize(),
			success: function(msg) {
				var maskHeight = $(document).height() + 1000;
				var maskWidth = $(window).width();
				$("#data_form").slideDown("slow");
				$("#backgroundPopup").css({"opacity": "0.7",'width':maskWidth,'height':maskHeight}).fadeIn("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 다른페이지 목록처리
	function other_open_check_code(sub_type, sub_action, idx, post_value, url, url_ok)
	{
		$('#other_sub_type').val(sub_type);
		$('#other_sub_action').val(sub_action);
		$('#other_idx').val(idx);
		$('#other_post_value').val(post_value);

		$.ajax({
			type: "post", dataType: 'json', url: url_ok,
			data: $('#otherform').serialize(),
			success: function(msg) {
				if (msg.success_chk == "Y") other_open_data_form(url, msg.idx, '');
				else check_auth_popup(msg.error_string);
			}
		});
	}
	
	
    function part_charge_chk(idx)
    {
        if ($("#part_charge_view_" + idx).is(':visible')) {
            $("#part_charge_btn_" + idx).html(' <img src="../../common/images/icon/icon_p.png" alt="펼치기" /> ');
        } else {
            $("#part_charge_btn_" + idx).html(' <img src="../../common/images/icon/icon_m.png" alt="접기" /> ');
        }
        $("#part_charge_view_" + idx).toggle();

    }
    

//----------------------------------- 150514 추가
// right 레이어팝업
$(window).resize(function() {
	
	if($(window).width()<601){
		$("#inchargeTog").css("display","none");
		
	}else if($(window).width()<966){
		$("#inchargeTog").css("display","block");
		
		
	}else if($(window).width()<1251){
		$("#inchargeTog").css("display","block");
		
	}else if($(window).width()>1250){
		$("#inchargeTog").css("display","block");
	}
	
});

function inchargeToggleRight(){
	$( "#inchargeTog" ).animate({
	    right: "-302px"
	  }, 500
	);
	$("#inchargeToggleCon").attr("onclick","inchargeToggleLeft()");
}


function inchargeToggleLeft(){
	$( "#inchargeTog" ).animate({
	    right: "0"
	  }, 500
	);
	$("#inchargeToggleCon").attr("onclick","inchargeToggleRight()");
}