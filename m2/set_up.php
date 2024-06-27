<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include  "./header.php";
	
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


<div id="page">
	<div id="header">
		<a class="back" href="javascript:history.go(-1)">BACK</a>
		<h1><a href="./" class="logo"><img src="./images/h_logo_x2.png" width="123" height="50" alt="비즈스토리"></a></h1>
		<p class="logout">
			<a href="#"><img src="./images/ico_logoutx2.png" alt="로그아웃" height="18px"></a>
		</p>
	</div>
	<div id="content">
		<div id="wrapper">
			<div id="scroller">
				
				<p class="alarm">알람을 켜고 끌수 있습니다.</p>
				<section class="setting_area">
					<ul class="setting">
						<li>
							<label for="chk_alarm"><strong>알람켜기</strong></label>
							<div class="switchL float_r">	
								<input type="checkbox" name="chk_alarm" id="chk_alarm" value="Y" <?=checked($push_enable, 'Y');?> onclick="javascript:alarm_setting();" />
								<label for="switchL"></label>
							</div>
						</li>
						<li>
							<strong>쪽지알람</strong>
							<div class="switchL float_r">	
								<input type="checkbox" name="chk_message" id="chk_message" value="Y" <?=checked($push_message, 'Y');?> onclick="javascript:alarm_setting();" />
								<label for="switchL"></label>
							</div>
						</li>
						<li>
							<strong>접수알람</strong>
							<div class="switchL float_r">	
								<input type="checkbox" name="chk_receipt" id="chk_receipt" value="Y" <?=checked($push_receipt, 'Y');?> onclick="javascript:alarm_setting();" />
								<label for="switchL"></label>
							</div>
						</li>
						<li>
							<strong>업무알람</strong>
							<div class="switchL float_r">	
								<input type="checkbox" name="chk_work" id="chk_work" value="Y" <?=checked($push_work, 'Y');?> onclick="javascript:alarm_setting();" />
								<label for="switchL"></label>
							</div>
						</li>
						<li>
							<strong>상담알람</strong>
							<div class="switchL float_r">	
								<input type="checkbox" name="chk_consult" id="chk_consult" value="Y" <?=checked($push_consult, 'Y');?> onclick="javascript:alarm_setting();" />
								<label for="switchL"></label>
							</div>
						</li>
						<li>
							<strong>단문자알람</strong>
							<div class="switchL float_r">	
								<input type="checkbox" name="chk_sms" id="chk_sms" value="Y" <?=checked($push_sms, 'Y');?> onclick="javascript:alarm_setting();" />
								<label for="switchL"></label>
							</div>
						</li>
						<li class="end">
							<strong>공지알람</strong>
							<div class="switchL float_r">	
								<input type="checkbox" name="chk_notice" id="chk_notice" value="Y" <?=checked($push_notice, 'Y');?> onclick="javascript:alarm_setting();" />
								<label for="switchL"></label>
							</div>
							<!-- div class="ez-checkbox">
								<input type="checkbox" name="chk_notice" id="chk_notice" value="Y" <?=checked($push_notice, 'Y');?> onclick="javascript:alarm_setting();" class="ez-hide">
							</div -->
						</li>
					</ul>
				</section>

			</div>
		</div>
	</div>
	
	<!-- script>
		// 라디로 체크박스 관련
		$(document).ready(
			function(){
				$('input[type=radio]').ezMark();
				$('input[type=checkbox]').ezMark();
			}
		);
	</script -->
<?
	include "./footer.php";
?>
