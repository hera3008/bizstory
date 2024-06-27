//<![CDATA[
// Mobile UserAgent
	var uAgent = navigator.userAgent.toLowerCase();

// Hide AddressBar
	if (window.addEventListener != null) {
		window.addEventListener('load', function(){
			setTimeout(scrollTo, 0, 0, 1);
		}, false);
	}

	// ScrollTo
	if (document.URL.indexOf("#")<=0) {
		window.addEventListener('load', function(){
			setTimeout(scrollTo, 0, 0, 1);
		}, false);
	}

	//document.write("<script type=\"text/javascript\" src=\"./js/iscroll.4.1.9.js\"></script>"); // iScroll
	//document.write("<script type=\"text/javascript\" src=\"./js/default.js\"></script>"); // default
	//document.write("<script type=\"text/javascript\" src=\"./js/modernizr.custom.js\"></script>"); // 모달팝업관련
	document.write("<script type=\"text/javascript\" src=\"./js/common_lib.js\"></script>"); // common_lib
	
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
	function login_out() {
		$.ajax({
			type: 'post', dataType: 'json', url: './login_out.php',
			data: {},
			success: function(msg) {
				if (msg.success_chk == "Y")
				{
					$.cookie('auto_value', '1', { expires: 7 });
					location.href = './';
				}
				else check_auth_popup(msg.error_string);
			}
		});
	}

	function login_out2() {
		if( /Android/i.test(navigator.userAgent)) {
			// 안드로이드 (app:인터페이스명, login_out:메서드명)
		//	app.login_out();
		} else if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
			// iOS 아이폰, 아이패드, 아이팟 (포스트백메세지이름:loginOut, 메세지Body:logoutBtn)
			var messageToPost = {'ButtonId':'logoutBtn'};
			window.webkit.messageHandlers.logout.postMessage(messageToPost);
		} else {
			// 그 외 디바이스
		}
	}

//------------------------------------ 업무 검색관련


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
		page_move_check(func_name)
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

//]]>
