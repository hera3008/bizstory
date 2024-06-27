
	var local_dir = '';
///////////////////////////////////////////////////////////////////////////
//
// Common Ready
//
///////////////////////////////////////////////////////////////////////////
	$(document).ready(function() {
	// Site Html
		$("#footer").html('<address><em>Copyright &copy;</em><strong>BIZSTORY</strong><span>All Rights Reserved.</span></address>');

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

	// Radio CheckBox Style
		$('input[type="checkbox"]').ezMark();
		$('input[type="radio"]').ezMark();

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
			if( $(this).scrollTop() > 100 ) {
				$('.top_btn').fadeIn(300);
			} 
			else {
				$('.top_btn').fadeOut(300);
			}
		});
		$('.top_btn a').click(function(){
			$('html, body').animate({scrollTop:0}, 500 );
			return false;
		});

	// Ajax 설정
		$.ajaxSetup(
		{
			async       : true,
			processData : true,
			cache       : true,
			timeout     : 5000,
			dataType    : "json",
			type        : "post",
			contentType : "application/x-www-form-urlencoded;charset=UTF-8",
			error       : function(xhr, status, error)
			{
				var error_msg = xhr + "<br />" + status + "<br />" + error + "<br />";
				return false;
			}
		});
	});

///////////////////////////////////////////////////////////////////////////
//
// 공통
//
///////////////////////////////////////////////////////////////////////////

//------------------------------------ 새창띄우기
	function new_open(URL, WinName, WinWidth, WinHeight, ScrollYN)
	{
		var NewWin = window.open(URL, WinName, 'toolbar=no, top=100, left=100, width=' + WinWidth + ', height=' + WinHeight + ', resizable=yes, scrollbars=' + ScrollYN);
		NewWin.focus();
	}

//------------------------------------ 입력여부
	function check_input_value(chk_value)
	{
		if (chk_value == '') return 'No';
		else return 'Yes';
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
		else
		{
			if (str != '') msg = str;
			else msg = '권한이 없습니다.';
		}

		$("#popup_notice_view").show();
		$("#popup_notice_memo").html(msg);
		$("#backgroundPopup").fadeOut("slow");
	}

//------------------------------------ 권한메세지 - popup
	function check_auth_popup(str)
	{
		var msg = '';
		if (str == 'insert') msg = '등록권한이 없습니다.';
		else if (str == 'modify') msg = '수정권한이 없습니다.';
		else if (str == 'delete') msg = '삭제권한이 없습니다.';
		else if (str == 'view') msg = '보기권한이 없습니다.';
		else
		{
			if (str == '') msg = '권한이 없습니다.';
			else msg = str;
		}

		$('#popup_result_msg').dialog('open');
		$("#popup_result_msg").html(msg);
		$("#backgroundPopup").fadeOut("slow");
	}

//------------------------------------ 목록 처리
	function check_code_data(sub_type, sub_action, idx, post_value)
	{
		$("#popup_notice_view").hide();

		$('#list_sub_type').val(sub_type)
		$('#list_sub_action').val(sub_action);
		$('#list_idx').val(idx);
		$('#list_post_value').val(post_value);

		$.ajax({
			type     : "post",
			url      : link_ok,
			data     : $('#listform').serialize(),
			success  : function(msg) {
				if (msg.success_chk == "Y") list_data();
				else
				{
					$("#popup_notice_view").show();
					$("#popup_notice_memo").html('' + msg.error_string);
				}
			}
		});
	}

//------------------------------------ 삭제하기
	function check_delete(idx)
	{
		if (confirm("선택하신 데이타를 삭제하시겠습니까?"))
		{
			view_close();
			check_code_data('delete', '', idx, '');
		}
	}

//------------------------------------ 팝업답변폼 열기
	function popupform_open_reply(idx)
	{
		$("#popup_notice_view").hide();
		$('#list_idx').val(idx);
		$('#list_sub_type').val('replyform');
		$.ajax({
			type     : "get",
			dataType : 'html',
			url      : link_form,
			data     : $('#listform').serialize(),
			beforeSubmit: function(){
				$("#loading").fadeIn('slow').fadeOut('slow');
			},
			success  : function(msg) {
				var maskHeight = $(document).height();
				var maskWidth = $(window).width();
				$("#data_form").slideDown("slow");
				$("#backgroundPopup").css({"opacity": "0.7",'width':maskWidth,'height':maskHeight}).fadeIn("slow");
				var winW = $(window).width();
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', winW/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 팝업등록폼 닫기
	function popupform_close()
	{
		$("#popup_notice_view").hide();
		$("#data_form").slideUp("slow");
		$("#backgroundPopup").fadeOut("slow");
	}

//------------------------------------ 팝업상세보기 닫기
	function view_close()
	{
		$("#popup_notice_view").hide();
		$("#data_view").slideUp("slow");
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

//------------------------------------ 업로드파일 확장자확인
	function file_delete(idx, org_idx)
	{
		$("#popup_notice_view").hide();
		$('#list_idx').val(idx);
		$('#list_org_idx').val(org_idx);
		$('#list_sub_type').val('file_delete');
		
		$.ajax({
			type    : 'post', dataType: 'json', url: link_ok,
			data    : $('#listform').serialize(),
			success : function(msg) {
				if (msg.success_chk == "Y") popupform_open(org_idx);
				else check_auth_popup(msg.error_string);
			}
		});
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

///////////////////////////////////////////////////////////////////////////
//
// 목록, 검색 관련 관련
//
///////////////////////////////////////////////////////////////////////////

//------------------------------------ 목록
	function list_data()
	{
		$("#popup_notice_view").hide();
		$.ajax({
			type    : "post", dataType : 'html', url : link_list,
			data    : $('#listform').serialize(),
			beforeSubmit: function(){
				$("#loading").fadeIn('slow').fadeOut('slow');
			},
			success : function(msg) {
				$('#data_list').html(msg);
			}
		});
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
		f = document.listform;
		f.action = link_excel;
		f.submit();
	}

//------------------------------------ 인쇄
	function list_print()
	{
		f = document.listform;
		f.target = 'print';
		f.action = link_print;
		window.open('', 'print', 'width=800, height=600, toolbar=no, top=30, left=30, resizable=yes, scrollbars=yes');
		f.submit();
	}

//------------------------------------ 선택인쇄
	function list_print_detail()
	{
		f = document.listform;
		f.target = 'print';
		f.action = link_print_detail;
		window.open('', 'print', 'width=800, height=600, toolbar=no, top=30, left=30, resizable=yes, scrollbars=yes');
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

//------------------------------------ 지사별 접수분류 - 단계별 표시
	function select_receipt_class(code_part, field_id, field_name, field_value, select_type)
	{
		$.ajax({
			type:"get", dataType:"html", url:local_dir + '/bizstory/comp_set/select_receipt_class.php',
			data     : {
				"code_part" : code_part,
				"field_id" : field_id, "field_name" : field_name, "field_value" : field_value,
				"select_type" : select_type
			},
			success : function(msg) {
				$('#receipt_class_view').html(msg);
			}
		});
	}

//------------------------------------ 팝업등록폼 열기
	function popupform_open(idx)
	{
		$("#popup_notice_view").hide();
		$('#list_idx').val(idx);
		$.ajax({
			type:"get", dataType:'html', url:link_form,
			data: $('#listform').serialize(),
			beforeSubmit: function(){
				$("#loading").fadeIn('slow').fadeOut('slow');
			},
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

//------------------------------------ 팝업상세보기 열기
	function view_open(idx)
	{
		$("#popup_notice_view").hide();
		$('#list_idx').val(idx);
		$.ajax({
			type:"get", dataType:'html', url:link_view,
			data: $('#listform').serialize(),
			beforeSubmit: function(){
				$("#loading").fadeIn('slow').fadeOut('slow');
			},
			success: function(msg) {
				$("#data_view").slideUp("slow");
				$("#data_view").slideDown("slow");
				$("#data_view").html(msg);
			}
		});
	}

//------------------------------------ 지사별관련 정보
	function part_information(code_part, select_class, field_id, field_value, select_type)
	{
		$.ajax({
			type: "get", dataType: "json", url: local_dir + '/bizstory/comp_set/part_information.php',
			data: {
				"code_part" : code_part,
				"select_class" : select_class,
				"field_value" : field_value,
				"select_type" : select_type
			},
			success  : function(msg) {
				$('#' + field_id).empty();
				if (select_type == 'select')
				{
					$('#' + field_id).append('<option value="all">' + $('#' + field_id).attr('title') + '</option>');
				}
				else
				{
					$('#' + field_id).append('<option value="">' + $('#' + field_id).attr('title') + '</option>');
				}
				if (msg.success_chk == "Y")
				{
					$.each(msg.result_data, function() {

						var empty_str = ''
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
			}
		});
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
		location.href = local_dir + '/cms/receipt.php?' + $('#listform').serialize();
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
			type: 'get', dataType: 'jsonp', url: 'http://121.88.4.88:8080/convert_result.php',
			jsonp : 'callback',
			data: { 'job_id' : 'demo', 'agent_code' : agent_code, 'user_id' : user_id, 'key' : pre_idx },
			beforeSend: function(){ $("#loading2").fadeIn('slow'); },
			success: function(msg) {
				if (msg.success == 'Y')
				{
					file_preview_html(msg.index_url, msg.image_url, msg.page_count, file_name);
				}
				else
				{
					alert(msg.msg);
				}
			},
			complete: function(){
				$("#loading2").fadeOut('slow');
				$("#backgroundPopup").fadeOut("slow");
			}
		});
	}

//------------------------------------ 문서 미리보기 페이지구성
	function file_preview_html(index_url, image_url, page_count, file_name)
	{
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "get", dataType: 'html', url: '/bizstory/include/file_preview_html.php',
			data: {
				'index_url' : index_url,
				'image_url' : image_url,
				'page_count' : page_count,
				'file_name' : file_name },
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
				var maskHeight = $(document).height() + 1000;
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