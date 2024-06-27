<?
/*
	생성 : 2012.05.25
	위치 : 업무폴더 > 나의업무 > 업무 - 상태 - 요청대기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$work_info = new work_info();
	$work_info->wi_idx = $wi_idx;
	$work_info->data_path = $comp_work_path;
	$work_info->data_dir = $comp_work_dir;

	$data = $work_info->work_info_view();
	$deadline_list = deadline_date();
?>
<div class="pstatus_box">
	<div class="pstatus_top">
		<p class="count">완료요청을 하시겠습니까?</p>
	</div>

	<form id="status30form" name="status30form" action="<?=$this_page;?>" method="post">
		<input type="hidden" id="status30_comp_idx" name="comp_idx" value="<?=$code_comp;?>" />
		<input type="hidden" id="status30_part_idx" name="part_idx" value="<?=$code_part;?>" />
		<input type="hidden" id="status30_wi_idx"   name="wi_idx"   value="<?=$wi_idx;?>" />
		<input type="hidden" id="status30_sub_type" name="sub_type" value="status30" />

		<div class="pstatus">
			<div class="pstatus_wrap">
				<div class="pstatus_data">
					<div class="user_edit">
						업무 요청완료를 하시면 등록된 보고내용을 수정할 수 없습니다.<br />
						요청완료하는 즉시, 등록자에게 요청완료 사실을 알려드립니다.<br />
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

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: '<?=$local_dir;?>/bizstory/include/select_work_status_ok.php',
				data: $('#status30form').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						popup_work_close();
						list_data();
						view_open($('#status30_wi_idx').val());
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