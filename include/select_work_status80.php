<?
/*
	생성 : 2012.04.25
	위치 : 업무폴더 > 나의업무 > 업무 - 상태 - 업무보류
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
?>
<div class="pstatus_box">
	<div class="pstatus_top">
		<p class="count">업무를 보류시키겠습니까?</p>
	</div>

	<form id="status80form" name="status80form" action="<?=$this_page;?>" method="post">
		<input type="hidden" id="status80_comp_idx" name="comp_idx" value="<?=$code_comp;?>" />
		<input type="hidden" id="status80_part_idx" name="part_idx" value="<?=$code_part;?>" />
		<input type="hidden" id="status80_wi_idx"   name="wi_idx"   value="<?=$wi_idx;?>" />
		<input type="hidden" id="status80_sub_type" name="sub_type" value="status80" />

		<div class="pstatus">
			<div class="pstatus_info">
				<span class="user">보류사유</span>
				<span class="date">
					<input type="text" name="status_contents" id="status_contents" class="type_text" title="보류사유를 입력하세요." size="40" />
				</span>
			</div>

			<div class="pstatus_wrap">
				<div class="pstatus_data">
					<div class="user_edit">
						보류된 업무는 다시 업루를 진행 시킬 수 있습니다.
					</div>
				</div>
			</div>
			<div class="popup_button">
				<a href="javascript:void(0);" onclick="form_workstatus();" class="btn_big"><span>확인</span></a>
				<a href="javascript:void(0);" onclick="popup_work_close();" class="btn_big"><span>닫기</span></a>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
// 저장
	function form_workstatus()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#status_contents').val(); // 보류사유
		chk_title = $('#status_contents').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: '<?=$local_dir;?>/bizstory/include/select_work_status_ok.php',
				data: $('#status80form').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						popup_work_close();
						list_data();
						view_open($('#status80_wi_idx').val());
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}
//]]>
</script>