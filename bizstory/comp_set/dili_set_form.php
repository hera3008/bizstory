<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
	$ds_idx    = $idx;

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

	$where = " and ds.comp_idx = '" . $code_comp . "'and ds.part_idx = '" . $code_part . "'";
	$data = diligence_set_data('view', $where);
	$ds_idx = $data['ds_idx'];

	$form_chk = 'N';
	if (($auth_menu['int'] == 'Y' && $ds_idx == '') || ($auth_menu['mod'] == 'Y' && $ds_idx != '')) // 등록, 수정권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth('');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		if ($data['part_idx'] == '' || $data['part_idx'] == '0') $data['part_idx'] = $code_part;
		if ($data['start_yn'] == '') $data['start_yn'] = 'Y';
		if ($data['end_yn'] == '') $data['end_yn'] = 'Y';
		if ($data['night_yn'] == '') $data['night_yn'] = 'Y';
		if ($data['open_yn'] == '') $data['open_yn'] = 'Y';

		$start_time = explode(':', $data['start_time']);
		$data['start_hour']   = $start_time[0];
		$data['start_minute'] = $start_time[1];

		$end_time = explode(':', $data['end_time']);
		$data['end_hour']   = $end_time[0];
		$data['end_minute'] = $end_time[1];

		$night_time = explode(':', $data['night_time']);
		$data['night_hour']   = $night_time[0];
		$data['night_minute'] = $night_time[1];
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
			<input type="hidden" name="param[part_idx]" id="post_part_idx" value="<?=$data['part_idx'];?>" />

		<fieldset>
			<legend class="blind">출근부설정</legend>
			<table class="tinytable write" summary="출근부설정을 수정합니다.">
			<caption>출근부설정</caption>
			<colgroup>
				<col width="100px" />
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th>근무요일</th>
					<td colspan="2">
						<div class="left">
					<?
						echo code_checkbox($set_week, 'work_week[]', 'post_work_week', $data['work_week'], '', '', '');
					?>
						</div>
					</td>
				</tr>
				<tr>
					<th>출근시간</th>
					<td colspan="2">
						<div class="left">
							<label for="post_start_yn"><input type="checkbox" name="param[start_yn]" id="post_start_yn" value="N" <?=checked($data['start_yn'], 'N');?> class="type_checkbox" onclick="start_check()" /> 출근시간 체크하지 않기</label> <br />
							* 출근시간 설정없이 언제든지 출근체크를 할 수 있습니다.
						</div>
						<div class="left" id="start_form_view">
							<br />
							오후 1시는 13시로 입력하시면됩니다. <br />
							<input type="text" name="param[start_hour]" id="post_start_hour" class="type_text" title="출근시간의 시를 입력하세요." size="5" value="<?=$data['start_hour'];?>" /> 시
							<input type="text" name="param[start_minute]" id="post_start_minute" class="type_text" title="출근시간의 분을 입력하세요." size="5" value="<?=$data['start_minute'];?>" /> 분
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label for="post_end_yn"><input type="checkbox" name="param[end_yn]" id="post_end_yn" value="Y" <?=checked($data['end_yn'], 'Y');?> class="type_checkbox" onclick="end_check()" /> 퇴근시간도 체크하기</label>
							<br />
							출근 시간이후에 출근을 하면 지각으로 처리됩니다.<br />
						</div>
					</td>
				</tr>
				<tr>
					<th>퇴근시간</th>
					<td colspan="2">
						<div class="left" id="end_form_view1">
							<input type="text" name="param[end_hour]" id="post_end_hour" class="type_text" title="퇴근시간의 시를 입력하세요." size="5" value="<?=$data['end_hour'];?>" /> 시
							<input type="text" name="param[end_minute]" id="post_end_minute" class="type_text" title="퇴근시간의 분을 입력하세요." size="5" value="<?=$data['end_minute'];?>" /> 분
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label for="post_night_yn"><input type="checkbox" name="param[night_yn]" id="post_night_yn" value="Y" <?=checked($data['night_yn'], 'Y');?> class="type_checkbox" onclick="night_check()" /> 야근시간도 체크하기</label>
						</div>
						<div class="left" id="end_form_view2">퇴근시간을 현재 사용하지 않습니다.</div>
					</td>
				</tr>
				<tr>
					<th>야근시간</th>
					<td colspan="2">
						<div class="left" id="night_form_view1">
							시간설정을 하지 않으면 자동으로 퇴근시간 2시간 후부터 야근시간으로 적용됩니다.<br />
							<input type="text" name="param[night_hour]" id="post_night_hour" class="type_text" title="야근시간의 시를 입력하세요." size="5" value="<?=$data['night_hour'];?>" /> 시
							<input type="text" name="param[night_minute]" id="post_night_minute" class="type_text" title="야근시간의 분을 입력하세요." size="5" value="<?=$data['night_minute'];?>" /> 분
						</div>
						<div class="left" id="night_form_view2">야근시간을 현재 사용하지 않습니다.</div>
					</td>
				</tr>
				<tr>
					<th>출근자 제외</th>
					<td colspan="2">
						<div class="left">
			<?
				$sub_where = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $data['part_idx'] . "'";
				$mem_list = member_info_data('list', $sub_where, 'mem.mem_name', '', '');
				if ($mem_list['total_num'] > 0)
				{
			?>
							<ul>
			<?
					foreach ($mem_list as $mem_k => $mem_data)
					{
						if (is_array($mem_data))
						{
							$checkbox_str = 'except_mem_idx_' . $mem_data['mem_idx'];
			?>
								<li>
									<label for="<?=$checkbox_str;?>">
										<input type="checkbox" name="except_mem_idx[]" id="<?=$checkbox_str;?>" value="<?=$mem_data['mem_idx'];?>" class="type_checkbox" title="<?=$mem_data['mem_name'];?>" />
										<span><?=$mem_data['mem_name'];?></span>
									</label>
								</li>
			<?
						}
					}
			?>
							</ul>
			<?
				}
			?>
						</div>
					</td>
				</tr>
				<tr>
					<th>보기설정</th>
					<td colspan="2">
						<div class="left">
							출근내역 비공개를 선택하시면 직원들은 다른 직원의 출근현황을 볼 수 없습니다.<br />
							<label for="post_open_yn_1"><input type="radio" name="param[open_yn]" id="post_open_yn_1" value="Y" class="type_radio" <?=checked($data['open_yn'], 'Y');?> /> 직원 출근내역 공개</label>
							<label for="post_open_yn_2"><input type="radio" name="param[open_yn]" id="post_open_yn_2" value="N" class="type_radio" <?=checked($data['open_yn'], 'N');?> /> 직원 출근내역 비공개</label>
						</div>
					</td>
				</tr>
				<tr>
					<th rowspan="4">메세지</th>
					<td colspan="2">
						<div class="left">
							출근자들에게 보이는 간단한 메세지입니다.
						</div>
					</td>
				</tr>
				<tr>
					<td><label for="post_start_message">정상출근시</label></td>
					<td>
						<div class="left">
							<input type="text" name="param[start_message]" id="post_start_message" class="type_text" title="정상출근시 메세지를 입력하세요." size="50" value="<?=$data['start_message'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<td><label for="post_late_message">지각시</label></td>
					<td>
						<div class="left">
							<input type="text" name="param[late_message]" id="post_late_message" class="type_text" title="지각시 메세지를 입력하세요." size="50" value="<?=$data['late_message'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<td><label for="post_end_message">퇴근시</label></td>
					<td>
						<div class="left">
							<input type="text" name="param[end_message]" id="post_end_message" class="type_text" title="퇴근시 메세지를 입력하세요." size="50" value="<?=$data['end_message'];?>" />
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
			<?
				if ($ds_idx == '') {
			?>
					<span class="btn_big_green"><input type="submit" value="등록" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close()" /></span>

					<input type="hidden" name="sub_type" value="post" />
			<?
				} else {
			?>
					<span class="btn_big_blue"><input type="submit" value="수정" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close()" /></span>

					<input type="hidden" name="sub_type" value="modify" />
					<input type="hidden" name="ds_idx"   value="<?=$ds_idx;?>" />
			<?
				}
			?>
				</div>
			</div>
		</fieldset>
		</form>
	</div>
</div>
<?
	}
?>

<script type="text/javascript">
//<![CDATA[
	function start_check()
	{
		if ($('#post_start_yn').attr('checked') == 'checked' || $('#post_start_yn').attr('checked') == true)
		{
			$('#start_form_view').css({"display":"none"});
		}
		else $('#start_form_view').css({"display":"block"});
	}

	function end_check()
	{
		if ($('#post_end_yn').attr('checked') == 'checked' || $('#post_end_yn').attr('checked') == true)
		{
			$('#end_form_view1').css({"display":"block"});
			$('#end_form_view2').css({"display":"none"});
		}
		else
		{
			$('#end_form_view1').css({"display":"none"});
			$('#end_form_view2').css({"display":"block"});
		}
	}

	function night_check()
	{
		if ($('#post_night_yn').attr('checked') == 'checked' || $('#post_night_yn').attr('checked') == true)
		{
			$('#night_form_view1').css({"display":"block"});
			$('#night_form_view2').css({"display":"none"});
		}
		else
		{
			$('#night_form_view1').css({"display":"none"});
			$('#night_form_view2').css({"display":"block"});
		}
	}

	$('#start_form_view').css({"display":"block"});
	$('#end_form_view1').css({"display":"none"});
	$('#end_form_view2').css({"display":"none"});
	$('#night_form_view1').css({"display":"none"});
	$('#night_form_view2').css({"display":"none"});

	start_check();
	end_check();
	night_check();
//]]>
</script>