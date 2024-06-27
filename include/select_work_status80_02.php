<?
/*
	생성 : 2012.04.25
	수정 : 2012.06.18
	위치 : 업무폴더 > 나의업무 > 업무 - 상태 - 보류에서 업무진행
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

	$data        = $work_info->work_info_view();
	$file_list   = $work_info->work_file_list();
	$file_images = $work_info->work_file_images();
?>
<div class="pstatus_box">
	<div class="pstatus_top">
		<p class="count">업무를 다시 진행하시겠습니까?</p>
	</div>

	<form id="status80_02form" name="status80_02form" action="<?=$this_page;?>" method="post">
		<input type="hidden" id="status80_02_comp_idx" name="comp_idx" value="<?=$code_comp;?>" />
		<input type="hidden" id="status80_02_part_idx" name="part_idx" value="<?=$code_part;?>" />
		<input type="hidden" id="status80_02_wi_idx"   name="wi_idx"   value="<?=$wi_idx;?>" />
		<input type="hidden" id="status80_02_sub_type" name="sub_type" value="status80_02" />

		<input type="hidden" name="param[charge_idx]" id="post_charge_idx" value="<?=$data['charge_idx'];?>" />
		<input type="hidden" name="post_apply_idx" id="post_apply_idx" value="<?=$data['apply_idx'];?>" />

		<div class="pstatus">

			<table class="tinytable view">
			<colgroup>
				<col width="60px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="status_charge_idx">담당자</label></th>
					<td>
						<div class="left">
							<div id="charge_view"></div>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="status_deadline_date1">기한</label></th>
					<td>
						<div class="left">
							<ul>
				<?
					if ($data['deadline_date'] == '')
					{
				?>
								<li>
									<select name="deadline_date1" id="post_deadline_date1" onchange="deadline_date_view(this.value, 'deadline_date_view')">
					<?
						foreach ($deadline_list['date'] as $date_k => $date_v)
						{
							echo '
								<option value="' . $date_v . '">' . $date_v . ' ' . $deadline_list['week'][$date_k] . '</option>';
						}
					?>
										<option value="-">---------------</option>
										<option value="select">직접선택하기</option>
									</select>
								</li>
								<li>
									<span id="deadline_date_view" class="none">
										<input type="text" name="deadline_date2" id="post_deadline_date2" class="type_text datepicker" title="기한을 입력하세요." size="10" value="<?=date('Y-m-d');?>" />
									</span>
								</li>
								<li>
									<?=code_select($set_work_deadline_txt, 'deadline_str1', 'post_deadline_str1', '', '덧붙이기(선택사항)', '덧붙이기(선택사항)', '', '', 'onchange="deadline_str_view(this.value, \'deadline_str_view\')"');?>
								</li>
								<li>
									<span id="deadline_str_view" class="none">
										<input type="text" name="deadline_str2" id="post_deadline_str2" class="type_text" title="직접입력하세요." size="20" />
									</span>
								</li>
				<?
					}
					else
					{
				?>
								<li>
									<input type="text" name="deadline_date1" id="post_deadline_date1" class="type_text datepicker" title="기한을 입력하세요." size="10" value="<?=date_replace($data['deadline_date'], 'Y-m-d');?>" />
								</li>
								<li>
									<input type="text" name="deadline_str1" id="post_deadline_str1" class="type_text" title="직접입력하세요." size="20" value="<?=$data['deadline_str'];?>" />
								</li>
				<?
					}
				?>
							</ul>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="pstatus_wrap">
				<div class="pstatus_data">
					<div class="user_edit">
						보류된 업무는 다시 업무를 진행 시킬 수 있습니다.
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
	$(".datepicker").datepicker( {
		showOn:"button",
		buttonImage:"<?=$local_dir;?>/bizstory/images/btn/calendar.jpg",
		dateFormat:"yy-mm-dd",
		buttonImageOnly:true,
	});

	charge_member_list('<?=$data['work_type'];?>', '<?=$data['wi_idx'];?>');

// 담당자목록
	function charge_member_list(work_type, wi_idx)
	{
		var apply_idx     = $("#post_apply_idx").val();
		var charge_idx    = $("#post_charge_idx").val();
		var old_work_type = '<?=$data['work_type'];?>';

		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/include/select_charge_member.php',
			data: {'old_work_type':old_work_type, 'work_type':work_type, 'charge_idx':charge_idx, 'apply_idx':apply_idx, 'wi_idx':wi_idx},
			success: function(msg) {
				$("#charge_view").html(msg);
			}
		});
	}

// 담당자, 기한 저장
	function form_workstatus()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: '<?=$local_dir;?>/bizstory/include/select_work_status_ok.php',
				data: $('#status80_02form').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						popup_work_close();
						list_data();
						view_open($('#status80_02_wi_idx').val());
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