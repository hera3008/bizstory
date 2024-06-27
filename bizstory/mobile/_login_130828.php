<?
	include $local_path . "header.php";
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

				<div class="login_logo"><img src="./images/bizstory.png" alt="bizstory" style="width:290px;" /></div>

				<article class="login_form">
					<form id="loginform" name="loginform" method="post" action="index.php">
						<fieldset>
							<legend>로그인 정보 입력</legend>
							<p class="login_words">
								아이디 및 비밀번호가 기억나지 않으세요?<br />
								웹에서 비즈스토리에 연결하시면<br />
								아이디찾기 및 비밀번호 찾기를 하실 수 있습니다.
							</p>
							<section class="login_area">
								<h2>BIZSTORY LOGIN</h2>
								<p>
									<label for="login">Username or email</label>
									<input type="text" name="login" placeholder="  Username or email" required />
								</p>
								<p>
									<label for="password">Password</label>
									<input type="password" name='password' placeholder="  Password" required /> 
								</p>
								<p>
									<input type="submit" name="submit" value="로그인" />
								</p>
							</section>
							<div class="switch_area">
								<div class="float_l">
									<div class="switchL float_l">	
										<input type="checkbox" value="None" id="switchL" name="check" checked />
										<label for="switchL"></label>
									</div>
									<strong style="line-height:26px;padding-left:4px;">자동로그인</strong>
								</div>
								<div class="float_r">
									<div class="switchL float_l">	
										<input type="checkbox" value="None" id="switchL" name="check" checked />
										<label for="switchL"></label>
									</div>
									<strong style="line-height:26px;padding-left:4px;">아이디 저장</strong>
								</div>
							</div>
						</fieldset>
					</form>​
				</article>

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