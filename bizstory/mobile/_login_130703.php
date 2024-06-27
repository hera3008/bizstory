<?
	include "../common/setting.php";
	include $local_path . "/bizstory/m/process/mobile_setting.php";
	include $mobile_path . "/header.php";

	$move_url = urldecode($move_url);
	$move_url_arr = explode('&', $move_url);
	$total_url = '';
	foreach ($move_url_arr as $k => $v)
	{
		if ($k == 0)
		{
			$total_url = $v;
		}
		else
		{
			$total_url .= '&' . $v;
		}
	}
?>

<div id="page" id="login">
	<div id="header">
		<h1><a href="./" class="logo"><img src="./images/h_logo_x2.png" width="123" height="50" alt="비즈스토리"></a></h1>
		<!-- p class="logout">
			<a href="#"><img src="./images/ico_logoutx2.png" alt="로그아웃" height="18px"></a>
		</p-->
	</div>

	<div id="content">
		<div id="wrapper" class="login">
			<div id="scroller">

				<section class="login_notice">
					<div>
						<h2>NOTICE</h2>
						<ul>
							<li><a href="./">공지내용11111111111</a></li>
							<li><a href="./">공지내용222222222222222</a></li>
							<li><a href="./">공지내용11111111111</a></li>
						</ul>
					</div>
				</section>

				<article class="login_section">
					<form id="loginform" name="loginform" method="post" action="<?=$this_page;?>">
						<input type="hidden" name="sub_type" id="login_sub_type" value="check_login" />
						<input type="hidden" name="move_url" id="login_move_url" value="<?=$move_url;?>" />
					<fieldset>
					<legend class="invisible">로그인 정보 입력</legend>
					<div class="login_area">
						<section>
							<p>아이디 및 비밀번호가 기억나지 않으세요?<br />웹에서 비즈스토리에 연결하시면 아이디찾기 및 비밀번호 찾기를 하실 수 있습니다.</p>
							<ul class="login_attr">
								<li id="yidli" class="clearfix">
									<label for="login_mem_id">아이디</label>
									<div class="input_box"><input type="text" id="login_mem_id" name="param[mem_id]" autocomplete="off" autocorrect="off" placeholder="아이디를 입력하세요." value="" aria-required="true" required /></div>
								</li>
								<li class="borderli"></li>
								<li id="passwdli" class="clearfix">
									<label for="login_mem_pwd">비밀번호</label>
									<div class="input_box">
										<input type="password" id="login_mem_pwd" name="param[mem_pwd]" autocomplete="off" autocorrect="off" placeholder="비밀번호를 입력하세요" aria-required="true" required />
									</div>
								</li>
							</ul>
							<div class="ubtn">
								<p class="float_l"><input type="checkbox" name="login_auto_check" id="login_auto_check" tabindex="3" title="자동로그인"  checked="checked"  />자동로그인<!-- label for="login_auto_check">자동로그인</label --></p>
								<p class="float_r"><input type="checkbox" name="login_id_saved" id="login_id_saved" tabindex="4" class="disabled" title="아이디 저장" onclick="check_login_save()" />
								아이디 저장<!-- label for="login_id_check">아이디저장</label --></p>
							</div>
						</section>
					</div>
					</fieldset>
					</form>
				</article>
				<section class="login_btn">
					<div class="btn1 ta_c">
						<a href="index.php">로그인</a>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
	var link_ok = '<?=$mobile_dir;?>/login_ok.php';

	$("#popup_result_msg").dialog({
		autoOpen: false, width: 350, modal: true,
		buttons: {
			"확인": function() {$(this).dialog("close");}
		}
	});
	
	$(function() {
	   $.cookie('isLogin', "", { expires: -1 }); 
	});

//------------------------------------ 로그인
	function check_login()
	{
		var action_num = 0;
		var chk_total = '';
		var chk_value = '';

		chk_value = $('#login_mem_id').val();
		if (chk_value == '')
		{
			chk_total = chk_total + '아이디를 입력하세요.<br />';
			action_num++;
		}

		chk_value = $('#login_mem_pwd').val();
		if (chk_value == '')
		{
			chk_total = chk_total + '비밀번호를 입력하세요.<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				async : false,
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#loginform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						check_login_save();
						if (msg.auto_value != '')
						{
							$.cookie('auto_value', msg.auto_value, { expires: 7 });
						}

						// 안드로이드 / iOS 앱에 id/pw 전달
						callApp_setId($('#login_mem_id').val(), $('#login_mem_pwd').val());
						//callAppleApk();
						
						
						location.href = '<?=$mobile_dir;?>/index.php?mlogin&pId='+$('#login_mem_id').val()+'&pPw='+$('#login_mem_pwd').val();
					}
					else check_auth_popup(msg.error_string);
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
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

		if ($('#login_id_saved').attr('checked') == 'checked' || $('#login_id_saved').attr('checked') == true)
		{
			$.cookie('login_mem_id_save', chk_value, { expires: 7 });
		}
		else $.cookie('login_mem_id_save', null, { expires: 7 });
	}

//------------------------------------ ID Save
	if ($.cookie('login_mem_id_save') == null || $.cookie('login_mem_id_save') == '')
	{
		$('#login_mem_id').val('');
	}
	else
	{
		$('#login_mem_id').val($.cookie('login_mem_id_save'));
		$('#login_id_saved').attr('checked', 'checked');
	}

//------------------------------------ 로그인
	function sleep(milliseconds)
	{
		var start = new Date().getTime();
		var cur = start;
		while (cur - start < milliseconds)
		{
			cur = new Date().getTime();
		}
	}

	function callAndroidApk()
	{
		window.android.test();
		window.android.testParams("hi~~");
	}

	function callAndroidApk_setId(id, pw)
	{
		window.android.setId(id, pw);
	}

	function callAppleApk()
	{
		var uAgent = navigator.userAgent.toLowerCase();
		if (uAgent.indexOf("iphone") != -1 || uAgent.indexOf("ipod") != -1)
		{
			window.location = "iOS://callTest?hi~~";
		}
	}

	function callApp_setId(id, pw)
	{
		var uAgent = navigator.userAgent.toLowerCase();
		console.log("agent = "+uAgent);
		if (uAgent.indexOf("android") != -1)
		{
			window.android.setId(id, pw);
		}
		else if (uAgent.indexOf("iphone") != -1 || uAgent.indexOf("ipod") != -1)
		{
			window.location = "ios://loginBizstory";
		}
		else
		{ }
	}
//]]>
</script>
</body>
</html>