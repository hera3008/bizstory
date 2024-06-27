<?
	include "../bizstory/common/setting.php";
	include $local_path . "/cms/include/header.php";

	$move_url = urldecode($move_url);
	$move_url_arr = explode('&', $move_url);
	$total_url = '';
	foreach ($move_url_arr as $k => $v)
	{
		if ($k == 0)
		{
			$total_url = $v;
		}
		else if ($k == 1)
		{
			$total_url .= '?' . $v;
		}
		else
		{
			$total_url .= '&' . $v;
		}
	}
?>
<script type="text/javascript" src="<?=$local_dir;?>/common/js/jquery.ticker.js"></script>
<script type="text/javascript" src="<?=$local_dir;?>/common/js/jquery.cycle.js"></script>
<title>Login</title>
</head>

<body id="login_layout">

	<div id="loading">로딩중입니다...</div>
	<div id="header">
		<a href="<?=$local_dir;?>/" title="홈으로 이동 합니다." class="logo">
			<img src="<?=$local_dir;?>/cms/images/common/logo.jpg" width="198px" height="39px" alt="home" />
		</a>
	</div>

	<div id="popup_result_msg" title="처리결과"></div>

	<div class="popupform" title="팝업등록폼">
		<div id="data_form" title="등록폼"></div>
	</div>

	<table id="layout_table">
	<colgroup>
		<col />
	</colgroup>
		<tr>
			<td id="container">

				<div id="login_frame">
					<div class="login_box">

						<div class="login_logo">
							<p><strong>나만의 비즈니스홈</strong> - 쉽고 빠르게 접수하고 신속하게 처리되는 <span class="engName"></span> 시스템 언제든지 신청해주시면 신속정확하게 처리해 드리겠습니다.</p>
						</div>

						<div class="login_capture"></div>

						<form id="loginform" name="loginform" class="loginform" method="post" action="<?=$this_page;?>" onsubmit="return check_login()">
							<input type="hidden" name="sub_type" id="login_sub_type" value="check_login" />
							<input type="hidden" name="move_url" id="login_move_url" value="<?=$move_url;?>" />
							<fieldset>
								<legend class="blind">로그인 폼</legend>
								<div class="login_head">
									<div class="login_title">
										<strong></strong> 로그인
									</div>
								</div>
								<div class="login_body">
									<div>
										<label for="login_mem_id" class="label">아이디</label>
										<input type="text" name="param[mem_id]" id="login_mem_id" size="20" maxlength="100" value="아이디를 입력하세요." title="아이디를 입력하세요." class="type_text" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
									</div>
									<div>
										<label for="login_mem_pwd" class="label">비밀번호</label>
										<input type="password" name="param[mem_pwd]" id="login_mem_pwd" size="20" maxlength="20" title="비밀번호를 입력하세요." class="type_text" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
									</div>
									<div>
										<div class="login_left"><input type="checkbox" name="login_mem_id_chk" id="login_mem_id_chk" value="Y" onclick="check_login_save()" /><label for="login_mem_id_chk">아이디 저장</label></div>
										<div class="login_right"><span class="btn_big"><input type="submit" value="로그인" /></span></div>
									</div>
								</div>
							</fieldset>
						</form>

						<!-- Ticker -->
						<div id="login_notice" class="ticker">
							<div class="ticker_frame">
								<div id="ticker-wrapper" class="no-js">
									<ul id="js-news" class="js-hidden">
										<li class="news-item"><a href="./">업무 시스템 홈페이지 개편에 따른 일부 장애 안내 입니다.</a></li>
										<li class="news-item"><a href="./">업무 시스템 굳소프트 인증을 받고 현재 출시준비중입니다.</a></li>
										<li class="news-item"><a href="./">금일 오후 부터 시스템 업그레이드로 인한 서비스 일시중지 안내입니다.</a></li>
										<li class="news-item"><a href="./">홈빌더 홈스토리를 유비스토리에서 출시 예정입니다.</a></li>
									</ul>
								</div>
							</div>
						</div>

						<!-- Ad Banner -->
						<div id="login_banner" class="ad_banner">
							<div id="ad_banner">
								<div>
									<a href="./" title="배너제목"><img src="<?=$local_dir;?>/data/ad_banner01.gif" alt="배너내 텍스트 내용" width="257px" height="125px" /></a>
								</div>
								<div>
									<a href="./" title="배너제목"><img src="<?=$local_dir;?>/data/ad_banner02.gif" alt="배너내 텍스트 내용" width="257px" height="125px" /></a>
								</div>
								<div>
									<a href="./" title="배너제목"><img src="<?=$local_dir;?>/data/ad_banner03.gif" alt="배너내 텍스트 내용" width="257px" height="125px" /></a>
								</div>
							</div>
						</div>

					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="login_footer">
				<div id="footer"></div>
			</td>
		</tr>
	</table>
	<div id="backgroundPopup"></div>
	<div class="top_btn"><a href="javascript:void(0);" title="Scroll to top"></a></div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 로그인
	function check_login()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#login_mem_id').val();
		chk_title = $('#login_mem_id').attr('title');
		if (chk_value == '' || chk_value == chk_title)
		{
			chk_total = chk_total + chk_title + '<br />';
			$('#login_mem_id').val('아이디를 입력하세요.');
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
			$.ajax({
				type   : 'post', dataType: 'json', url: '<?=$local_dir;?>/cms/login_ok.php',
				data   : $('#loginform').serialize(),
				beforeSubmit: function(){ $("#loading").fadeIn('slow').fadeOut('slow');},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						check_login_save();
						location.href = msg.move_url;
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
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

//------------------------------------

	$(document).ready(function() {
	// Animate
		$(".login_logo", this).stop().animate({top:'46px'},{queue:false,duration:800});
		$(".loginform", this).stop().animate({top:'280px'},{queue:false,duration:800});
		$(".login_capture", this).stop().animate({right:'0px'},{queue:false,duration:800});

	// SlideShow
		$('#ad_banner').cycle({
			fx: 'scrollUp',
			speed: 500, timeout:3000,
			pager: '#ad_counter'
		});
		$("#backgroundPopup").click(function(){popupform_close()}); // 등록폼 닫기

	// 에러부분
		$("#popup_result_msg").dialog({
			autoOpen: false,
			width: 350,
			modal: true,
			buttons: {
				"확인": function() {
					$(this).dialog("close");
				}
			}
		});
	});
//]]>
</script>
</body>
</html>