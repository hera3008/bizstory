
	<div id="footer">
		<div class="navi">
			<div>
				<?=$bottom_btn;?>
			</div>
			<?=$address;?>
		</div>
	</div>
	<div id="popup_result_msg" title="처리결과"></div>

<script type="text/javascript">
//<![CDATA[
	$("#popup_result_msg").dialog({
		autoOpen: false, width: 300, modal: true,
		buttons: {
			"확인": function() {$(this).dialog("close");}
		}
	});
//]]>
</script>
<!--//
		<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/index.php\'" class="icon4"><span>홈</span></a>
		<a href="javascript:void(0)" onclick="check_auth_popup(\'준비중입니다\')" class="icon2"><span>글작성</span></a>
		<a href="javascript:void(0)" onclick="login_out();" class="icon1"><span class="leave_type">로그아웃</span></a>
		<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/set_up.php\'" class="icon3"><span>설정</span></a>
//-->