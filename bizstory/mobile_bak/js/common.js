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

// Jquery
	document.write('<script type="text/javascript" src="./js/jquery-1.6.2.min.js"></script>');
	document.write('<script type="text/javascript" src="./js/jquery-ui-1.8.14.js"></script>');

	document.write("<script type=\"text/javascript\" src=\"/common/js/jquery.cookie.js\"></script>"); // Jquery Cookie
	document.write("<script type=\"text/javascript\" src=\"./js/jquery.ready.js\"></script>"); // Ready
	document.write("<script type=\"text/javascript\" src=\"./js/iscroll.4.2.2.js\"></script>"); // iScroll

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
//]]>