
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
				//$("#backgroundPopup").fadeOut("slow");
			},
			error       : function(xhr, status, error)
			{
				var error_msg = xhr + "<br />" + status + "<br />" + error + "<br />";
				//alert(error_msg);
				//check_auth_popup(error_msg);
				return false;
			}
		});
	});

///////////////////////////////////////////////////////////////////////////
//
// 공통
//
///////////////////////////////////////////////////////////////////////////
//------------------------------------ 로그아웃
	function login_out()
	{
		$.ajax({
			type: 'post', dataType: 'json', url: local_dir + '/sole/logout.php',
			data: {},
			success: function(msg) {
				if (msg.success_chk == "Y")
				{
					$.cookie('auto_value', '1', { expires: 7 });
					location.href = local_dir + '/sole/';
				}
				else check_auth_popup(msg.error_string);
			}
		});
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
		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "post", dataType: 'html', url: link_list,
			data: $('#listform').serialize(),
			success: function(msg) {
				$('#data_list').html(msg);
			},
			complete: function(){
				$("#loading").fadeOut('slow');
				$("#backgroundPopup").fadeOut("slow");
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