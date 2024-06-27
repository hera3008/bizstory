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
	$push_sms     = $data['push_sms'];     // 푸쉬-단문자

	unset($data);
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
		var sms          = 'N';

		var alarm_checked = $('input[id="chk_alarm"]').is(':checked');
		if (alarm_checked == true) alarm_enable = 'Y';
		else alarm_enable = 'N';

		var message_checked = $('input[id="chk_message"]').is(':checked');
		if (message_checked == true) message = 'Y';
		else message = 'N';

		var receipt_checked = $('input[id="chk_receipt"]').is(':checked');
		if (receipt_checked == true) receipt = 'Y';
		else receipt = 'N';

		var work_checked = $('input[id="chk_work"]').is(':checked');
		if (work_checked == true) work = 'Y';
		else work = 'N';

		var consult_checked = $('input[id="chk_consult"]').is(':checked');
		if (consult_checked == true) consult = 'Y';
		else consult = 'N';

		var sms_checked = $('input[id="chk_sms"]').is(':checked');
		if (sms_checked == true) sms = 'Y';
		else sms = 'N';

		var notice_checked = $('input[id="chk_notice"]').is(':checked');
		if (notice_checked == true) notice = 'Y';
		else notice = 'N';

		$.ajax({
			type: "post", dataType: 'json', url: 'set_up_ok.php',
			data: {
				  'alarma':alarm_enable
				, 'message':message
				, 'receipt':receipt
				, 'work':work
				, 'consult':consult
				, 'notice':notice
				, 'sms':sms
			},
			success: function(msg) {
				if (msg.success_chk == "Y")
				{
					//check_auth_popup('저장되었습니다.');
				}
			}
		});
	}
//]]>
</script>

<div id="bbs_list" class="homebox full sub list">

	<!-- Toolbar -->
	<div class="toolbar">
		<h1>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/index.php'">알람설정</a>
		</h1>
		<?=$btn_logout;?>
	</div>
	<!-- //Toolbar -->

	<!-- Contents -->
	<div id="wrapper">
		<div id="scroller">
<?
// 메뉴를 사용하는 사용자만 알람이 나오도록
	$menu_where = "
		and mam.comp_idx = '" . $code_comp . "' and mam.mem_idx = '" . $code_mem . "' and mam.yn_list = 'Y'
		and mac.del_yn = 'N' and mac.view_yn = 'Y'
		and (
			   (mi.mode_folder = 'work'    and (mi.mode_file = 'msg_receive' or mi.mode_file = 'msg_send' or mi.mode_file = 'msg_store'))
			or (mi.mode_folder = 'receipt' and mi.mode_file = 'receipt')
			or (mi.mode_folder = 'work'    and mi.mode_file = 'work')
			or (mi.mode_folder = 'consult' and mi.mode_file = 'my_consult')
		)
	";
	$menu_list = menu_auth_member_data('list', $menu_where, '', '', '');
	foreach ($menu_list as $k => $data)
	{
		if (is_array($data))
		{
			$fmode = $data['mode_folder'];
			$smode = $data['mode_file'];

			if ($fmode == 'work' && ($smode == 'msg_receive' || $smode == 'msg_send' || $smode == 'msg_store'))
			{
				$alarm_data['message'] = 'Y';
			}

			if ($fmode == 'receipt' && $smode == 'receipt') $alarm_data['receipt'] = 'Y';

			if ($fmode == 'work' && $smode == 'work') $alarm_data['work'] = 'Y';

			if ($fmode == 'consult' && $smode == 'my_consult') $alarm_data['consult'] = 'Y';
		}
	}
	unset($data);
	unset($menu_list);
?>
			<table border="1" cellspacing="0" class="board-list" summary="쪽지, 접수, 업무, 공지, 상담, 알람켜기 등">
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
						<th><label for="chk_alarm"><strong>알람 켜기</strong></label></th>
						<td><span class="toggle"><input type="checkbox" name="chk_alarm" id="chk_alarm" value="Y" <?=checked($push_enable, 'Y');?> onclick="javascript:alarm_setting();" /></span></td>
					</tr>
		<?
			if ($alarm_data['message'] == 'Y')
			{
		?>
					<tr>
						<th><label for="chk_message"><strong>쪽지 알람</strong></label></th>
						<td><span class="toggle"><input type="checkbox" name="chk_message" id="chk_message" value="Y" <?=checked($push_message, 'Y');?> onclick="javascript:alarm_setting();" /></span></td>
					</tr>
		<?
			}
		?>
		<?
			if ($alarm_data['receipt'] == 'Y')
			{
		?>
					<tr>
						<th><label for="chk_receipt"><strong>접수 알람</strong></label></th>
						<td><span class="toggle"><input type="checkbox" name="chk_receipt" id="chk_receipt" value="Y" <?=checked($push_receipt, 'Y');?> onclick="javascript:alarm_setting();" /></span></td>
					</tr>
		<?
			}
		?>
		<?
			if ($alarm_data['work'] == 'Y')
			{
		?>
					<tr>
						<th><label for="chk_work"><strong>업무 알람</strong></label></th>
						<td><span class="toggle"><input type="checkbox" name="chk_work" id="chk_work" value="Y" <?=checked($push_work, 'Y');?> onclick="javascript:alarm_setting();" /></span></td>
					</tr>
		<?
			}
		?>
		<?
			if ($alarm_data['consult'] == 'Y')
			{
		?>
					<tr>
						<th><label for="chk_consult"><strong>상담 알람</strong></label></th>
						<td><span class="toggle"><input type="checkbox" name="chk_consult" id="chk_consult" value="Y" <?=checked($push_consult, 'Y');?> onclick="javascript:alarm_setting();" /></span></td>
					</tr>
		<?
			}
		?>
					<tr>
						<th><label for="chk_sms"><strong>단문자 알람</strong></label></th>
						<td><span class="toggle"><input type="checkbox" name="chk_sms" id="chk_sms" value="Y" <?=checked($push_sms, 'Y');?> onclick="javascript:alarm_setting();" /></span></td>
					</tr>
					<tr>
						<th><label for="chk_notice"><strong>공지 알람</strong></label></th>
						<td><span class="toggle"><input type="checkbox" name="chk_notice" id="chk_notice" value="Y" <?=checked($push_notice, 'Y');?> onclick="javascript:alarm_setting();" /></span></td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>
	<?
		unset($alarm_data);

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