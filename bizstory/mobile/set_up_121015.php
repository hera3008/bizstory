<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";
	include $mobile_path . "/header.php";

	$query = "
		select
			*
		from
			push_member
		where
			del_yn = 'N'
			and comp_idx = '" . $code_comp . "'
			and mem_idx = '" . $code_mem . "'
	";
	$data = query_view($query);

	$push_enable  = $data['push_enable'];  // 푸쉬 사용여부
	$push_message = $data['push_message']; // 푸쉬-쪽지
	$push_receipt = $data['push_receipt']; // 푸쉬-접수
	$push_work    = $data['push_work'];    // 푸쉬-업무
	$push_notice  = $data['push_notice'];  // 푸쉬-공지
	$push_consult = $data['push_consult']; // 푸쉬-상담
?>

<script type="text/javascript">
//<![CDATA[
	function alarm_setting()
	{
		var alarm_enable = 'N';
		var message      = 'N';
		var receipt      = 'N';
		var work         = 'N';
		var notice       = 'N';
		var consult      = 'N';

		var alarm_checked1 = $('input[id="chk_alarm_enable1"]').is(':checked');
		var alarm_checked2 = $('input[id="chk_alarm_enable2"]').is(':checked');
		if (alarm_checked1 == true) alarm_enable = 'Y';
		if (alarm_checked2 == true) alarm_enable = 'N';

		var message_checked = $('input[id="chk_message"]').is(':checked');
		if (message_checked == true) message = 'Y';

		var receipt_checked = $('input[id="chk_receipt"]').is(':checked');
		if (receipt_checked == true) receipt = 'Y';

		var work_checked = $('input[id="chk_work"]').is(':checked');
		if (work_checked == true) work = 'Y';

		var notice_checked = $('input[id="chk_notice"]').is(':checked');
		if (notice_checked == true) notice = 'Y';

		//var consult_checked = $('input[id="chk_consult"]').is(':checked');
		//if (consult_checked == true) consult = 'Y';

		// Mobile UserAgent
		var uAgent = navigator.userAgent.toLowerCase();

		if (uAgent.indexOf("android") != -1)
		{
			window.android.setAlarm(alarm_enable, message, receipt, work, notice);
		}
		else if (uAgent.indexOf("iphone") != -1 || uAgent.indexOf("ipod") != -1)
		{
			window.location = "iOS://pushRegistration?"
							+ alarm_enable
							+ "&"+message
							+ "&"+receipt
							+ "&"+work
							+ "&"+notice;
		}
	}
//]]>
</script>

<div id="bbs_list" class="homebox full sub list">

	<!-- Toolbar -->
	<div class="toolbar">
		<h1>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/index.php'">설정</a>
		</h1>
		<?=$btn_logout;?>
	</div>
	<!-- //Toolbar -->

	<!-- Contents -->
	<div id="wrapper">
		<div id="scroller">

			<table border="1" cellspacing="0" class="board-list" summary="쪽지, 접수, 업무, 공지, 알람켜기, 알람끄기 등">
			<caption>알람설정</caption>
				<tbody>
					<tr>
						<td class="subject" colspan="2">
							<p>
								<strong>
									알람을 켜고 끌수 있습니다.
								</strong>
							</p>
						</td>
					</tr>
					<tr>
						<th><label for="chk_message"><strong>쪽지 알람</strong></label></th>
						<td><span class="toggle"><input type="checkbox" name="chk_message" id="chk_message" value="Y" <?=checked($push_message, 'Y');?> onclick="javascript:alarm_setting();" /></span></td>
					</tr>
					<tr>
						<th><label for="chk_receipt"><strong>접수 알람</strong></label></th>
						<td><span class="toggle"><input type="checkbox" name="chk_receipt" id="chk_receipt" value="Y" <?=checked($push_receipt, 'Y');?> onclick="javascript:alarm_setting();" /></span></td>
					</tr>
					<tr>
						<th><label for="chk_work"><strong>업무 알람</strong></label></th>
						<td><span class="toggle"><input type="checkbox" name="chk_work" id="chk_work" value="Y" <?=checked($push_work, 'Y');?> onclick="javascript:alarm_setting();" /></span></td>
					</tr>
					<tr>
						<th><label for="chk_notice"><strong>공지 알람</strong></label></th>
						<td><span class="toggle"><input type="checkbox" name="chk_notice" id="chk_notice" value="Y" <?=checked($push_notice, 'Y');?> onclick="javascript:alarm_setting();" /></span></td>
					</tr>
					<!--//
					<tr>
						<th><label for="chk_consult"><strong>상담 알람</strong></label></th>
						<td><span class="toggle"><input type="checkbox" name="chk_consult" id="chk_consult" value="Y" <?=checked($push_consult, 'Y');?> onclick="javascript:alarm_setting();" /></span></td>
					</tr>
					//-->
					<tr>
						<th><label for="chk_alarm_enable1"><strong>알람 켜기</strong></label></th>
						<td><span class="toggle"><input type="checkbox" name="chk_alarm_enable" id="chk_alarm_enable1" value="Y" <?=checked($push_enable, 'Y');?> onclick="javascript:alarm_setting();" /></span></td>
					</tr>
					<tr>
						<th><label for="chk_alarm_disable2"><strong>알람 끄기</strong></label></th>
						<td><span class="toggle"><input type="checkbox" name="chk_alarm_disable" id="chk_alarm_disable2" value="N" <?=checked($push_enable, 'N');?> onclick="javascript:alarm_setting();" /></span></td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>
	<?
		$bottom_btn = '
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/index.php\'" class="icon4"><span>홈</span></a>
			<a href="javascript:void(0)" onclick="check_auth_popup(\'준비중입니다\');" class="icon2"><span>일정</span></a>
			<a href="javascript:void(0)" onclick="check_auth_popup(\'준비중입니다\');" class="icon1"><span class="leave_type">나의정보</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/set_up.php\'" class="icon3"><span>설정</span></a>
		';
	?>
	<? include $mobile_path . "/footer.php"; ?>
</div>
</body>
</html>