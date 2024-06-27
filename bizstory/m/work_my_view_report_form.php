<?
	require_once "../common/setting.php";
	require_once $local_path . "/bizstory/m/process/mobile_setting.php";
	require_once $mobile_path . "/process/member_chk.php";
	require_once $mobile_path . "/process/no_direct.php";
?>
<div class="new_report">
	<form name="reportform" id="reportform" method="post" action="<?=$this_page;?>" onsubmit="return check_report_form()">
		<input type="hidden" name="wi_idx" value="<?=$wi_idx;?>" />
		<div class="form">
			<textarea name="param[remark]" id="reportpost_remark" cols="30" rows="10" title="업무보고내용을 입력하세요." class="type_text"><?=$data['remark'];?></textarea>
		</div>
		<div class="action">
			<span class="btn_big"><input type="submit" value="등록" /></span>
			<span class="btn_big"><input type="button" value="취소" onclick="report_insert_form('close', 'insert')" /></span>

			<input type="hidden" name="sub_type" value="post" />
		</div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 업무보고 등록/수정
	function check_report_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#reportpost_remark').val(); // 내용
		chk_title = $('#reportpost_remark').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: "post", dataType: 'json', url: '/bizstory/mobile/work_my_view_report_ok.php',
				data: $('#reportform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#report_total_value').html(msg.total_num);
						report_insert_form('close');
						report_list_data();
					}
					else check_auth_popup(msg.error_string);
				},
				complete: function(){
					$("#loading").fadeOut('slow');
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}
//]]>
</script>