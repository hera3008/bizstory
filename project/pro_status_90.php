<?
/*
	생성 : 2013.04.04
	위치 : 업무관리 > 프로젝트관리 > 보기 - 완료
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$where = " and pro.pro_idx = '" .  $pro_idx . "'";
	$data = project_info_data('view', $where);
	$data = project_list_data($data, $pro_idx);
?>
<div class="pstatus_box">
	<div class="pstatus_top">
		<p class="count">프로젝트를 <?if ($force_yn == 'Y') {?>강제<?}?>완료하시겠습니까?</p>
	</div>

	<form id="status90form" name="status90form" action="<?=$this_page;?>" method="post">
		<input type="hidden" id="status90_comp_idx" name="comp_idx" value="<?=$code_comp;?>" />
		<input type="hidden" id="status90_part_idx" name="part_idx" value="<?=$code_part;?>" />
		<input type="hidden" id="status90_pro_idx"  name="pro_idx"  value="<?=$pro_idx;?>" />
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
                        프로젝트를 강제완료하시려면 강제완료 사유를 남겨주십시오.<br />
                        사유를 입력하지 않으시면 "<span style="color:gray">프로젝트가 강제 종료 되었습니다.</span>"로<br />자동 입력 됩니다.
                    </div>
                </div>
            </div>
		    <? } else { ?>
			<div class="pstatus_wrap">
				<div class="pstatus_data">
					<div class="user_edit">
						프로젝트가 완료되면 업무분류 등록, 수정, 삭제가 가능하지 않습니다.<br />
					</div>
				</div>
			</div>
			<?
            }
            ?>
			<div class="popup_button">
				<a href="javascript:void(0);" onclick="check_status();" class="btn_big_violet"><span>확인</span></a>
				<a href="javascript:void(0);" onclick="close_pro_status();" class="btn_big_violet"><span>닫기</span></a>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
// 저장
	function check_status()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: '<?=$local_dir;?>/bizstory/project/pro_status_ok.php',
				data: $('#status90form').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						close_pro_status();
						list_data();
						view_open($('#status90_pro_idx').val());
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