<?
/*
	생성 : 2013.01.02
	수정 : 2013.01.02
	위치 : 업무폴더 > 나의업무 > 일정 - 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
	$sche_idx  = $idx;

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
	if (($auth_menu['int'] == 'Y' && $sche_idx == '') || ($auth_menu['mod'] == 'Y' && $sche_idx != '')) // 등록, 수정권한
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

		if ($data['sche_type'] == '') $data['sche_type'] = 'personal';
		if ($data['start_date'] == '') $data['start_date'] = date('Y-m-d');
		if ($data['end_date'] == '') $data['end_date'] = date('Y-m-d');
		if ($data['open_type'] == '') $data['open_type'] = 'all';
		if ($data['notify_type'] == '') $data['notify_type'] = 'N';
		if ($data['repeat_class'] == '') $data['repeat_class'] = 'day';
		if ($data['repeat_num'] == '') $data['repeat_num'] = '1';

		$charge_idx_arr = explode(',', $data['charge_idx']);

	// 지사정보
		$sub_where = " and part.comp_idx = '" . $code_comp . "' and part.view_yn = 'Y'";
		if ($set_part_yn == 'N') $sub_where .= " and part.part_idx = '" . $code_part . "'";
		$part_list = company_part_data('list', $sub_where, '', '', '');

		$sdate  = date("Y-m-d");
		$syear  = substr($sdate, 0, 4);
		$smonth = substr($sdate, 5, 2);
		$sday   = substr($sdate, 8, 2);
		$month_first = $syear . '-' . $smonth . '-01';
		$stime   = date("Y-m-d H:i:s");
		$stime_hour   = substr($stime, 11, 2);
		$stime_minute = substr($stime, 14, 2);

		$data_date = query_view("
			select
				date_format(date_add('" . $sdate . "',interval 1 day),'%Y-%m-%d') as d_date,
				date_format(date_add('" . $sdate . "',interval 1 month),'%Y-%m-%d') as m_date,
				date_format(date_add('" . $stime . "',interval 30 minute),'%H:%i') as s_time,
				date_format(date_add('" . $stime . "',interval 90 minute),'%H:%i') as e_time
		");

		$repeat_sdate = $data_date['d_date'];
		$repeat_edate = $data_date['m_date'];
		$repeat_stime = $data_date['s_time'];
		$repeat_etime = $data_date['e_time'];

		$shour   = substr($repeat_stime, 0, 2);
		$sminute = substr($repeat_stime, 3, 2);
		$ehour   = substr($repeat_etime, 0, 2);
		if ($stime_minute >= 30)
		{
			$repeat_stime = $shour . ':00';
			$repeat_etime = $ehour . ':00';
		}
		else
		{
			$repeat_stime = $stime_hour . ':30';
			$repeat_etime = $ehour . ':30';
		}

		if ($data['start_time'] == '') $data['start_time'] = $repeat_stime;
		if ($data['end_time'] == '') $data['end_time'] = $repeat_etime;
		if ($data['notify_time'] == '') $data['notify_time'] = '10';
?>
<div class="info_text">
	<ul>
		<li>개인일정은 나만 볼 수 있습니다.</li>
	</ul>
</div>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>

		<fieldset>
			<legend class="blind">일정 폼</legend>
			<table class="tinytable write" summary="일정을 등록/수정합니다.">
			<caption>일정</caption>
			<colgroup>
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_sche_type">일정구분</label></th>
					<td>
						<div class="left">
					<?
						echo code_radio($set_sche_type, 'param[sche_type]', 'post_sche_type', $data['sche_type'], '', '', 'onchange="charge_view(this.value)"');
					?>
						</div>

						<div class="charge_view_box left none" id="charge_idx_view">
							<input type="hidden" name="param[charge_idx]" id="post_charge_idx" value="<?=$data['charge_idx'];?>" title="참가자를 선택하세요." />
							<ul>
								<li class="part_name">ㆍ참가자선택</li>
							</ul>
					<?
						foreach ($part_list as $part_k => $part_data)
						{
							if (is_array($part_data))
							{
								$chk_str = 'partidx' . $part_data['part_idx'];
					?>
							<ul>
								<li class="first">
									<label for="<?=$chk_str;?>">
										<input type="checkbox" class="type_checkbox" title="<?=$part_data['part_name'];?>" name="<?=$chk_str;?>" id="<?=$chk_str;?>" onclick="check_all2('<?=$chk_str;?>', this, '1'); select_member();" />
										<span style="color:<?=$set_color_list2[$part_data['sort']];?>"><?=$part_data['part_name'];?></span>
									</label>
								</li>
							</ul>
					<?
							// 그룹별
								$group_where = " and csg.comp_idx = '" . $code_comp . "' and csg.part_idx = '" . $part_data['part_idx'] . "'";
								$group_list = company_staff_group_data('list', $group_where, '', '', '');
								foreach ($group_list as $group_k => $group_data)
								{
									if (is_array($group_data))
									{
										$chk_strg = $chk_str . '-' . $group_data['csg_idx'];

									// 지사별 직원
										$sub_where2 = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $part_data['part_idx'] . "' and mem.csg_idx = '" . $group_data['csg_idx'] . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
										$sub_order2 = "csg.sort asc, cpd.sort asc, mem.mem_name asc";
										$mem_list = member_info_data('list', $sub_where2, $sub_order2, '', '');
										if ($mem_list['total_num'] > 0)
										{
					?>
							<ul>
								<li class="second">
									<label for="<?=$chk_strg;?>">
										<input type="checkbox" class="type_checkbox" title="<?=$group_data['group_name'];?>" name="<?=$chk_strg;?>" id="<?=$chk_strg;?>" onclick="check_all2('<?=$chk_strg;?>', this, '0'); select_member();" />
										<span><?=$group_data['group_name'];?></span>
									</label>
									<ul>
					<?
											foreach ($mem_list as $mem_k => $mem_data)
											{
												if (is_array($mem_data))
												{
													$checkbox_str = $chk_strg . '_' . $mem_data['mem_idx'];
													$checked = '';
													if (is_array($charge_idx_arr))
													{
														foreach ($charge_idx_arr as $charge_k => $charge_v)
														{
															if ($mem_data['mem_idx'] == $charge_v)
															{
																$checked = 'checked="checked"';
																break;
															}
														}
													}
													$total_member++;
					?>
										<li class="mem_name">
											<label for="<?=$checkbox_str;?>">
												<input type="checkbox" name="check_member_idx[]" id="<?=$checkbox_str;?>" value="<?=$mem_data['mem_idx'];?>" class="type_checkbox" <?=$checked;?><?=$disabled;?> title="<?=$mem_data['mem_name'];?>" onclick="select_member()" />
												<?=$mem_data['mem_name'];?>
											</label>
										</li>
					<?
												}
											}
					?>
									</ul>
								</li>
							</ul>
					<?
										}
									}
								}
							}
						}
					?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_sche_class">일정종류</label></th>
					<td>
						<div class="left">
							<select name="param[sche_class]" id="post_sche_class" title="일정종류를 선택하세요">
								<option value="">일정종류를 선택하세요</option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_subject">제목</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[subject]" id="post_subject" class="type_text" title="제목을 입력하세요." size="50" value="<?=$data['subject'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_start_date">일시</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[start_date]" id="post_start_date" class="type_text datepicker" title="시작일을 입력하세요." size="10" value="<?=$data['start_date'];?>" />
						<?
							echo code_select($set_sche_time, 'param[start_time]', 'post_start_time', $data['start_time'], '시작시분을 선택하세요.', '', '', '', '');
						?>
							~
							<input type="text" name="param[end_date]" id="post_end_date" class="type_text datepicker" title="종료일을 입력하세요." size="10" value="<?=$data['end_date'];?>" />
						<?
							echo code_select($set_sche_time, 'param[end_time]', 'post_end_time', $data['end_time'], '종료시분을 선택하세요.', '', '', '', '');
						?>
							<a href="javascript:void(0);" onclick="repeat_setting()" class="btn_con"><span>반복</span></a>
							<input type="hidden" name="repeat_class_chk" id="repeat_class_chk" value="N" />
							<label for="post_all_day"><input type="checkbox" name="all_day" id="post_all_day" value="Y" onclick="period_view()" /> 종일</label>
						</div>
						<div id="repeat_setting" class="none">
							<table class="tinytable" summary="반복설정">
								<caption>반복설정</caption>
								<colgroup>
									<col width="60px" />
									<col />
								</colgroup>
								<tbody>
									<tr>
										<th><label for="repeat_start_date">범위</label></th>
										<td>
											<div class="left">
												<input type="text" name="repeat_start_date" id="repeat_start_date" class="type_text datepicker" title="시작일을 입력하세요." size="10" value="<?=$repeat_sdate;?>" />
												~
												<input type="text" name="repeat_end_date" id="repeat_end_date" class="type_text datepicker" title="종료일을 입력하세요." size="10" value="<?=$repeat_edate;?>" />
												<label for="repeat_all_day"><input type="checkbox" name="repeat_unlimit" id="repeat_unlimit" value="Y" onclick="period_setting()" /> 무한반복</label>
											</div>
										</td>
									</tr>
									<tr>
										<th><label for="post_subject">시간</label></th>
										<td>
											<div class="left">
										<?
											echo code_select($set_sche_time, 'repeat_start_time', 'repeat_start_time', $repeat_stime, '시작시분을 선택하세요.', '', '', '', '');
											echo ' ~ ';
											echo code_select($set_sche_repeat, 'repeat_for', 'repeat_for', $data['repeat_for'], '종류를 선택하세요.', '', '', '', '');
											echo code_select($set_sche_time, 'repeat_end_time', 'repeat_end_time', $repeat_etime, '종료시분을 선택하세요.', '', '', '', '');
										?>
												<label for="repeat_all_day"><input type="checkbox" name="repeat_all_day" id="repeat_all_day" value="Y" /> 종일</label>
											</div>
										</td>
									</tr>
									<tr>
										<th><label for="repeat_class">빈도</label></th>
										<td>
											<div class="left">
												<ul>
													<li>
												<?
													echo code_select($set_repeat_set, 'repeat_class', 'repeat_class', $data['repeat_class'], '반복설정을 선택하세요.', '', '', '', 'onchange="repeat_set_view(this.value)"');
												?>
													</li>
													<li>
														<span id="repeat_view_day" class="none">
															<input type="text" name="day_repeat_num" id="day_repeat_num" class="type_text" title="일을 입력하세요." size="3" value="<?=$data['repeat_num'];?>" /> 일마다
														</span>
														<span id="repeat_view_week" class="none">
															<input type="text" name="week_repeat_num" id="week_repeat_num" class="type_text" title="주를 입력하세요." size="3" value="<?=$data['repeat_num'];?>" /> 주마다<br />
														<?
															echo code_checkbox($set_week, 'repeat_week[]', 'repeat_week', $data['repeat_week'], '', '', '');
														?>
														</span>
														<span id="repeat_view_month" class="none">
															<input type="text" name="month_repeat_num" id="month_repeat_num" class="type_text" title="개월을 입력하세요." size="3" value="<?=$data['repeat_num'];?>" /> 개월마다
														</span>
														<div id="repeat_view_year" class="none">
														</div>
													</li>
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<div class="left">
												내용
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_place">장소</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[place]" id="post_place" class="type_text" title="장소를 입력하세요." size="50" value="<?=$data['place'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_remark">내용</label></th>
					<td>
						<div class="left textarea_span">
							<textarea name="param[remark]" id="post_remark" title="내용을 입력하세요." rows="5" cols="50" class="none"><?=$data['remark'];?></textarea>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_open_type">공개여부</label></th>
					<td>
						<div class="left">
					<?
						echo code_radio($set_open_type, 'param[open_type]', 'post_open_type', $data['open_type'], '', '', '');
					?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_notify_type">미리알림</label></th>
					<td>
						<div class="left">
					<?
						echo code_select($set_sche_notify, 'notify_time', 'post_notify_time', $data['notify_time'], '', '', '', '', '');
						echo code_radio($set_notify_type, 'param[notify_type]', 'post_notify_type', $data['notify_type'], '', '', '');
					?>
					<!--//
							<div id="notify_type_view"></div>
							<a href="javascript:void(0);" onclick="notify_add()" class="btn_con"><span>알림추가</span></a>//-->
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
			<?
				if ($sche_idx == '') {
			?>
					<span class="btn_big_green"><input type="submit" value="등록" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>

					<input type="hidden" name="sub_type" value="post" />
			<?
				} else {
			?>
					<span class="btn_big_blue"><input type="submit" value="수정" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>

					<input type="hidden" name="sub_type" value="modify" />
					<input type="hidden" name="sche_idx" value="<?=$sche_idx;?>" />
			<?
				}
			?>
				</div>
			</div>

		</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	$(".datepicker").datepicker();

// 에디터관련
	var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "post_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){ }
		},
		fCreator: "createSEditor2"
	});

	part_information('<?=$code_part;?>', 'sche_class', 'post_sche_class', '<?=$data['sche_class'];?>', '');

//------------------------------------ 참가자
	function charge_view(str)
	{
		$('#charge_idx_view').css({"display": "none"});
		if (str == 'team')
		{
			$('#charge_idx_view').css({"display": "block"});

		}
	}

//------------------------------------ 반복설정
	function repeat_setting()
	{
		var repeat_chk = $('#repeat_class_chk').val();
		if (repeat_chk == 'N')
		{
			$('#repeat_setting').css({"display": "block"});
			$('#repeat_class_chk').val('Y');
		}
		else
		{
			$('#repeat_setting').css({"display": "none"});
			$('#repeat_class_chk').val('N');
		}
	}

//------------------------------------ 반복설정
	function repeat_set_view(str)
	{
		$('#repeat_view_day').css({"display": "none"});
		$('#repeat_view_week').css({"display": "none"});
		$('#repeat_view_month').css({"display": "none"});
		$('#repeat_view_year').css({"display": "none"});
		if (str == 'day')
		{
			$('#repeat_view_day').css({"display": "block"});
			$('#repeat_view_week').css({"display": "none"});
			$('#repeat_view_month').css({"display": "none"});
			$('#repeat_view_year').css({"display": "none"});
		}
		else if (str == 'week')
		{
			$('#repeat_view_day').css({"display": "none"});
			$('#repeat_view_week').css({"display": "block"});
			$('#repeat_view_month').css({"display": "none"});
			$('#repeat_view_year').css({"display": "none"});
		}
		else if (str == 'month')
		{
			$('#repeat_view_day').css({"display": "none"});
			$('#repeat_view_week').css({"display": "none"});
			$('#repeat_view_month').css({"display": "block"});
			$('#repeat_view_year').css({"display": "none"});
		}
		else if (str == 'year')
		{
			$('#repeat_view_day').css({"display": "none"});
			$('#repeat_view_week').css({"display": "none"});
			$('#repeat_view_month').css({"display": "none"});
			$('#repeat_view_year').css({"display": "block"});
		}
	}

//------------------------------------ 기간
	function period_setting(str)
	{
		if ($('#repeat_all_day').attr('checked') == 'checked' || $('#repeat_all_day').attr('checked') == true)
		{
			document.repeat_end_date.disabled = true;
		}
		else
		{
			document.repeat_end_date.disabled = false;
		}
	}

//------------------------------------ 참가자 - 선택
	function select_member()
	{
		var mem_idx  = document.getElementsByName('check_member_idx[]');
		var i = 0, j = 0;
		var total_member_idx = ''

		while(mem_idx[i])
		{
			if (mem_idx[i].type == 'checkbox' && mem_idx[i].disabled == false && mem_idx[i].checked == true)
			{
				if (j == 0)
				{
					total_member_idx  = mem_idx[i].value;
				}
				else
				{
					total_member_idx  += ',' + mem_idx[i].value;
				}
				j++;
			}
			i++;
		}

		$('#post_charge_idx').val(total_member_idx);
	}

//------------------------------------ 알림추가
	function notify_add()
	{
	}

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_subject').val(); // 제목
		chk_title = $('#post_subject').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		select_member();

		oEditors.getById["post_remark"].exec("UPDATE_CONTENTS_FIELD", []);

		if (action_num == 0)
		{
			//$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						close_data_form();
						list_data();
					}
					else
					{
						$("#loading").fadeOut('slow');
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

	//period_view();
	repeat_set_view('<?=$data['repeat_class'];?>');
	charge_view('<?=$data['sche_type'];?>');
//]]>
</script>
<?
	}
?>
