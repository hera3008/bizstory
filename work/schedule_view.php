<?
/*
	생성 : 2013.01.03
	수정 : 2013.01.03
	위치 : 업무폴더 > 나의업무 > 일정 - 보기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
	$code_level = $_SESSION[$sess_str . '_ubstory_level'];
	$sche_idx   = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if ($auth_menu['view'] == 'Y') // 보기권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth_popup('');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		$where = " and sche.sche_idx = '" . $sche_idx . "'";
		$data = schedule_info_data("view", $where);

	// 기간
		$start_date = $data['start_date'];
		$start_time = $data['start_time'];
		$end_date   = $data['end_date'];
		$end_time   = $data['end_time'];
		if ($start_date == $end_date)
		{
			$sche_date = $start_date . ' ' . $start_time;
			if ($end_time != '')
			{
				if ($start_time != $end_time)
				{
					$sche_date .= ' ~ ' . $end_time;
				}
			}
		}
		else
		{
			$sche_date = $start_date . ' ' . $start_time . ' ~ ' . $end_date . ' ' . $end_time;
		}

	// 총담당자구하기
		$charge_idx = $data['charge_idx'];
		$charge_arr = explode(',', $charge_idx);
		foreach ($charge_arr as $charge_k => $charge_v)
		{
			if ($charge_v != '')
			{
				$mem_where = " and mem.mem_idx = '" . $charge_v . "'";
				$mem_data = member_info_data('view', $mem_where, '', '', '');

				if ($mem_data['total_num'] > 0)
				{
					$name_idx = $mem_data['mem_idx'] . '_' . $sche_idx;
					if ($part_ok == 'Y')
					{
						$charge_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . ']
							<span class="relative">
								<a href="javascript:void(0)" onclick="staff_layer_open(\'' . $mem_data['mem_idx'] . '\');"><strong style="color:#ff6c00">' . $mem_data['mem_name'] . '</strong></a>
								<div id="obj_' . $name_idx . '" class="none"></div>
							</span>';
					}
					else
					{
						$charge_name = $mem_data['mem_name'];
					}
					$total_charge_str .= ', ' . $charge_name;
				}
				unset($mem_data);
			}
		}
		$total_charge_str = substr($total_charge_str, 2, strlen($total_charge_str));
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" name="sub_type" id="view_sub_type" />
			<input type="hidden" name="sche_idx" id="view_sche_idx" value="<?=$sche_idx;?>" />

		<fieldset>
			<legend class="blind">일정정보</legend>
			<table class="tinytable view" summary="등록한 일정에 대한 상세정보입니다.">
				<caption>일정정보</caption>
				<colgroup>
					<col width="100px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th>제목</th>
						<td colspan="3">
							<div class="left">
								<strong>
									[<?=$set_sche_type[$data['sche_type']];?>]
									[<?=$data['sche_class_str'];?>]
									<?=$data['subject'];?>
								</strong>
							</div>
						</td>
					</tr>
			<?
				if ($data['sche_type'] == 'team')
				{
			?>
					<tr>
						<th>참가자</th>
						<td>
							<div class="left"><?=$total_charge_str;?></div>
						</td>
					</tr>
			<?
				}
			?>
					<tr>
						<th>기간</th>
						<td colspan="3">
							<div class="left"><?=$sche_date;?></div>
						</td>
					</tr>
					<tr>
						<th>장소</th>
						<td colspan="3">
							<div class="left"><?=$data['place'];?></div>
						</td>
					</tr>
					<tr>
						<th>내용</th>
						<td colspan="3">
							<div class="left">
								<p class="memo">
									<?=$data['remark'];?>
								</p>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
<?
// 등록자만 가능하다.
	if ($data['reg_id'] == $code_mem || $code_level <= 11)
	{
		$btn_modify = '<span class="btn_big_blue"><input type="button" value="수정" onclick="data_form_open(\'' . $sche_idx . '\')" /></span>';
		$btn_delete = '<span class="btn_big_red"><input type="button" value="삭제" onclick="check_delete(\'' . $sche_idx . '\')" /></span>';
	}
?>
			<div class="section">
				<div class="fr">
					<?=$btn_modify;?>
					<?=$btn_delete;?>
				</div>
			</div>
		</fieldset>
		</form>

		<div class="section">
			<span class="btn_big_gray"><input type="button" value="닫기" onclick="view_close()" /></span>
		</div>
	</div>
</div>

<?
	}
?>