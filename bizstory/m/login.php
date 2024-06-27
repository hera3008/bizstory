<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include  "./header.php";

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
	
	db_close();
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

				<article class="login_form login_formarea">
					<form id="loginform" name="loginform" method="post" action="index.php">
					<input type="hidden" name="sub_type" id="login_sub_type" value="check_login" />
					<input type="hidden" name="move_url" id="login_move_url" value="<?=$move_url;?>" />
						<fieldset>
							<legend>로그인 정보 입력</legend>
							<p class="login_words">
								아이디 및 비밀번호가 기억나지 않으세요?<br />
								웹에서 비즈스토리에 연결하시면<br />
								아이디 및 비밀번호 찾기를 하실 수 있습니다.
							</p>
							<section class="login_area">
								<h2>BIZSTORY LOGIN</h2>
								<p>
									<label for="login">Username or email</label>
									<input type="text" name="param[mem_id]" id="login_mem_id" placeholder="  Username or email" required />
								</p>
								<p>
									<label for="password">Password</label>
									<input type="password" name='param[mem_pwd]' id="login_mem_pwd" placeholder="  Password" required /> 
								</p>
								<p>
									<input type="submit" name="submit" value="로그인" />
								</p>
							</section>
						</fieldset>
					</form>​
				</article>
				
				
				<div class="switch_area">
					<div class="float_l">
						<div class="switchL float_l">	
							<input type="checkbox" value="Y" id="auto_login" name="param[auto_login]"  />
							<label for="switchL"></label>
						</div>
						<strong style="line-height:26px;padding-left:4px;">자동로그인</strong>
					</div>
					<div class="float_r">
						<div class="switchL float_l">	
							<input type="checkbox" value="Y" id="login_id_saved" name="param[login_id_saved]"  />
							<label for="switchL"></label>
						</div>
						<strong style="line-height:26px;padding-left:4px;">아이디 저장</strong>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	var link_ok = '<?=$mobile_dir;?>/login_ok.php';
	var oDialog = null;
	var tempDlg = '<div class="u_dsc u_dsc_n" style="background-color:#fff;border:2px solid #777777">'
				+ '<h2 class="uc_h"><strong class="uc_st_n">[MESSAGE]</h2>'
				+ '<div class="uc_area">'
				+ '<a href="#" class="u_btn dialog-confirm" onclick="return false">확인</a> '
				+ '</div>'
				+ '</div>';
	
	function alertMsg(msg) {
		//var title = "비즈스토리";
		//var message = tempDlg.split('[MESSAGE]').join(msg);
		alert(msg);		
	}
	
	jQuery(function() {
		$.cookie('isLogin', "", { expires: -1 });
		var login_mem_id_save = $.cookie("login_mem_id_save");
		
		//------------------------------------ ID Save
		if (login_mem_id_save === null || login_mem_id_save === '' || login_mem_id_save === "null")
		{
			jQuery('#login_mem_id').val('');
		}
		else
		{
			jQuery('#login_mem_id').val(login_mem_id_save);
			jQuery('#login_id_saved').prop('checked', true);
		}

		
		jQuery("#loginform").submit(function() {
			check_login();
			return false;
		});
		
	});

//------------------------------------ 로그인
	function check_login()
	{
		var chk_total = '';
		var chk_value = '';

		chk_value = jQuery('#login_mem_id').val();
		if (chk_value == '')
		{
			alertMsg("아이디를 입력하세요.");
			return false;
		}

		chk_value = jQuery('#login_mem_pwd').val();
		if (chk_value == '')
		{
			alertMsg("비밀번호를 입력하세요.");
			return false;
		}

		jQuery("#loading").fadeIn('slow');
		jQuery("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		jQuery.ajax({
			async : false,
			type: 'post', dataType: 'json', url: link_ok,
			data: jQuery('#loginform').serialize(),
			success: function(msg) {
				if (msg.success_chk == "Y")
				{
					check_login_save();
					if (msg.auto_value != '')
					{
						$.cookie('auto_value', msg.auto_value, { expires: 7 });
					}

					// 안드로이드 / iOS 앱에 id/pw 전달
					callApp_setId(jQuery('#login_mem_id').val(), jQuery('#login_mem_pwd').val());
					//callAppleApk();

					location.href = '<?=$mobile_dir;?>/index.php?mlogin&pId='+jQuery('#login_mem_id').val()+'&pPw='+jQuery('#login_mem_pwd').val();
				}
				else {
					alertMsg(msg.error_string);
				}
			},
			complete: function(){
				jQuery("#backgroundPopup").fadeOut("slow");
			}
		});

		return false;
	}

//------------------------------------ 아이디저장
	function check_login_save()
	{
		var chk_value = jQuery('#login_mem_id').val();

		if (jQuery('#login_id_saved').is(":checked") == true)
		{
			$.cookie('login_mem_id_save', chk_value, { expires: 7 });
			//var cookie = $Cookie();
			//cookie.set("login_mem_id_save", chk_value, 7);
		}
		else 
		{
			$.cookie('login_mem_id_save', null, { expires: 7 });
			//var cookie = $Cookie();
			//cookie.set("login_mem_id_save", null, 7);
		}
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