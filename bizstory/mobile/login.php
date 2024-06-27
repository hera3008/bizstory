<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
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

<div id="logout" class="homebox index">
	<!-- Toolbar -->
	<div class="toolbar">
		<?=$btn_location;?>
		<h1>
			<a href="javascript:window.location.href='<?=$mobile_dir;?>/index.php'"><?=$mobile_eng;?></a>
		</h1>
		<?=$btn_reload;?>
	</div>

	<!-- Contents -->
	<div id="wrapper">
		<div id="scroller">
			<!-- Notice -->
			<div class="notice">
				<ul>
					<li>
						<div class="key"></div>
						<em>아이디 및 비밀번호가 기억나지 않으세요? 웹에서 <a href="<?=$mobile_site;?>" target="_blank"><?=$mobile_name;?></a>에 연결하시면 아이디찾기 및 비밀번호 찾기를 하실 수 있습니다.</em>
					</li>
				</ul>
			</div>

			<!-- loginform -->
			<form id="loginform" name="loginform" class="basic" method="post" action="<?=$this_page;?>">
				<input type="hidden" name="sub_type" id="login_sub_type" value="check_login" />
				<input type="hidden" name="move_url" id="login_move_url" value="<?=$move_url;?>" />
				<ul>
					<li>
						<label for="login_mem_id">아이디</label>
						<input type="text" name="param[mem_id]" placeholder="아이디를 입력하세요." id="login_mem_id" value="" />
					</li>
					<li>
						<label for="login_mem_pwd">비밀번호</label>
						<input type="password" name="param[mem_pwd]" placeholder="비밀번호를 입력하세요." id="login_mem_pwd" value="" />
					</li>
					<li>
						<label for="login_auto_check" class="small">아이디저장</label>
						<span class="toggle"><input type="checkbox" name="login_mem_id_chk" id="login_mem_id_chk" value="Y" onclick="check_login_save()" /></span>
					</li>
				</ul>
			</form>

			<!-- Button -->
			<div class="contents_pd">
				<div class="barmenu body">
					<a href="javascript:void(0);" onclick="check_login();" class="loop">회원 로그인<em></em></a>
				</div>
			</div>
		</div>
	</div>
	<div id="footer">
		<?=$address;?>
	</div>
</div>
<div id="popup_result_msg" title="처리결과"></div>

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

		if ($('#login_mem_id_chk').attr('checked') == 'checked' || $('#login_mem_id_chk').attr('checked') == true)
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
		$('#login_mem_id_chk').attr('checked', 'checked');
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