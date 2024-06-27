<?
/*
	생성 : 2012.04.25
	위치 : 업무폴더 > 나의업무 > 업무 - 상태 - 업무완료
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
		<p class="count">업무를 완료하시겠습니까?</p>
	</div>

	<form id="status90form" name="status90form" action="<?=$this_page;?>" method="post">
		<input type="hidden" id="status90_comp_idx" name="comp_idx" value="<?=$code_comp;?>" />
		<input type="hidden" id="status90_part_idx" name="part_idx" value="<?=$code_part;?>" />
		<input type="hidden" id="status90_wi_idx"   name="wi_idx"   value="<?=$wi_idx;?>" />
		<input type="hidden" id="status90_sub_type" name="sub_type" value="status90" />
        <input type="hidden" id="status_forec_yn" name="force_yn" value="<?=$force_yn?>" />

		<div class="pstatus">
            <?
            if ($force_yn == 'Y') {
            ?>
            <div class="pstatus_info">
                <span class="user">강제완료 사유</span>
                <span class="date">
                    <input type="text" name="status_contents" id="status_contents" class="type_text" title="강제완료 사유를 입력하세요." size="40" />
                </span>
            </div>
            
            <div class="pstatus_wrap">
                <div class="pstatus_data">
                    <div class="user_edit">
                        업무를 강제완료하시려면 강제완료 사유를 남겨주십시오.<br />
                        사유를 입력하지 않으시면 "<span style="color:#A0A1A4">업무가 강제 종료 되었습니다.</span>"로<br />자동 입력 됩니다.
                    </div>
                </div>
            </div>
            <? } else { ?>
			<div class="pstatus_wrap">
				<div class="pstatus_data">
					<div class="user_edit">
						업무가 완료되면 등록된 보고내용은 수정할 수 없습니다.<br />
						업무가 완료되는 즉시, 마스터와 업무 등록자에게 업무완료 사실을 알력드립니다.
					</div>
				</div>
			</div>
			<?
            }
            ?>
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
				data: $('#status90form').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						popup_work_close();
						list_data();
						view_open($('#status90_wi_idx').val());
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