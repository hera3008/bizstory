<?
/*
	생성 : 2012.07.04
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 컨텐츠관리 > 공지관리 > 사이트공지 - 등록/수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$notice_type = '2';
	$ni_idx      = $idx;

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
	if ($auth_menu['int'] == 'Y' && $ni_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $ni_idx != '') // 수정권한
	{
		$form_chk   = 'Y';
		$form_title = '수정';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>';
		exit;
	}

	if ($form_chk == 'Y')
	{
		$where = " and ni.ni_idx = '" . $ni_idx . "'";
		$data = notice_info_data("view", $where);

		if ($data['comp_all'] == "") $data['comp_all'] = "Y";
		if ($data['import_type'] == "") $data['import_type'] = "0";
		if ($data['view_yn'] == "") $data['view_yn'] = "Y";

		$comp_add_where = '';
		$comp_idx_arr = explode(',', $data['comp_idx']);
		foreach($comp_idx_arr as $comp_k => $comp_v)
		{
			if ($comp_k > 0)
			{
				$comp_add_where .= " and comp.comp_idx != '" . $comp_v . "'";
			}
		}

		$comp_where = " and comp.auth_yn = 'Y' and comp.view_yn = 'Y'" . $comp_add_where;
		$comp_order = "comp.comp_name asc";
		$comp_list = company_info_data('list', $comp_where, $comp_order, '', '');
?>
<div class="info_text">
	<ul>
		<li>링크주소 입력시 "http://" 를 입력하지 마세요.</li>
	</ul>
</div>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_default;?>
			<input type="hidden" name="param[comp_idx]" id="post_comp_idx" value="" />
			<input type="hidden" name="param[notice_type]" id="post_notice_type" value="<?=$notice_type;?>" />

			<fieldset>
				<legend class="blind">공지 폼</legend>
				<table class="tinytable write" summary="공지를 등록/수정합니다.">
					<caption>공지</caption>
					<colgroup>
						<col width="100px" />
						<col />
					</colgroup>
					<tbody>
						<tr>
							<th>출력여부</th>
							<td>
								<div class="left">
									<?=code_radio($set_view, "param[view_yn]", "post_view_yn", $data["view_yn"]);?>
								</div>
							</td>
						</tr>
						<tr>
							<th>중요도</th>
							<td>
								<div class="left">
									<?=code_radio($set_agent_important, 'param[import_type]', 'post_import_type', $data['import_type']);?>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_content">내용</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[content]" id="post_content" class="type_text" title="내용 입력하세요." size="60" value="<?=$data["content"];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_link_url">링크주소</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[link_url]" id="post_link_url" class="type_text" title="링크주소를 입력하세요." size="60" value="<?=$data["link_url"];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th rowspan="2">업체설정</th>
							<td>
								<div class="left">
									<label for="post_comp_all"><input type="checkbox" name="param[comp_all]" id="post_comp_all" value="Y" <?=checked($data['comp_all'], 'Y');?> /> 업체전체</label>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="left">
									<select name="left_comp_idx" id="post_left_comp_idx" style="width:210px;" size="12" multiple="multiple" title="업체를 선택하세요.">
						<?
							foreach($comp_list as $k => $comp_data)
							{
								if (is_array($comp_data))
								{
						?>
										<option value="<?=$comp_data['comp_idx'];?>"><?=$comp_data['comp_name'];?></option>
						<?
								}
							}
						?>
									</select>

									<a href="javascript:void(0);" onclick="right_move()" class="btn_con"><span> + </span></a>
									<a href="javascript:void(0);" onclick="left_move()" class="btn_con"><span> - </span></a>

									<select name="right_comp_idx" id="post_right_comp_idx" style="width:210px;" size="12" multiple="multiple" title="업체를 선택하세요.">
						<?
							foreach($comp_idx_arr as $comp_k => $comp_v)
							{
								if ($comp_k > 0)
								{
									$sub_where = " and comp.comp_idx = '" . $comp_v . "'";
									$sub_data = company_info_data('view', $sub_where);
						?>
										<option value="<?=$sub_data['comp_idx'];?>"><?=$sub_data['comp_name'];?></option>
						<?
								}
							}
						?>
									</select>
								</div>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="section">
					<div class="fr">
				<?
					if ($ni_idx == '') {
				?>
						<span class="btn_big_green"><input type="submit" value="등록" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form();" /></span>

						<input type="hidden" name="sub_type" value="post" />
				<?
					} else {
				?>
						<span class="btn_big_blue"><input type="submit" value="수정" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form();" /></span>

						<input type="hidden" name="sub_type" value="modify" />
						<input type="hidden" name="bi_idx"   value="<?=$bi_idx;?>" />
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
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#post_content').val(); // 내용
		chk_title = $('#post_content').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

	// 업체선택
		var comp_all = $("input:checkbox[name='param[comp_all]']:checked").length; // 승인요청
		if (comp_all == 0) // 업체전체가 아닐 경우
		{
			var total_num = 0;
			var total_comp = '';
			var comp_len = $('#post_right_comp_idx option').size();
			var comp_val;
			chk_title = $('#post_right_comp_idx').attr('title');

			for (var i = 0; i < comp_len; i++)
			{
				comp_val = $("#post_right_comp_idx option:eq(" + i + ")").val();

				total_comp = total_comp + ',' + comp_val;
				total_num = total_num + 1;
			}
			if (total_num == 0)
			{
				chk_total = chk_total + chk_title + '<br />';
				action_num++;
			}
			else
			{
				$('#post_comp_idx').val(total_comp);
			}
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						close_data_form();
					}
					else
					{
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
						check_auth_popup(msg.error_string);
					}
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

//------------------------------------ 왼쪽 -> 오른쪽
	function right_move()
	{
		var total_num = 0;
		var comp_len = $('#post_left_comp_idx option').size();
		var comp_text, comp_val;

		for (var i = 0; i < comp_len; i++)
		{
			if($("#post_left_comp_idx option:eq(" + i + ")").attr("selected") == 'selected')
			{
				comp_text = $("#post_left_comp_idx option:eq(" + i + ")").text();
				comp_val  = $("#post_left_comp_idx option:eq(" + i + ")").val();

				$('#post_right_comp_idx').append('<option value="' + comp_val + '">' + comp_text + '</option>');
				$("#post_left_comp_idx option:eq(" + i + ")").remove();
				total_num = total_num + 1;
			}
		}
		if (total_num == 0)
		{
			check_auth_popup($('#post_left_comp_idx').attr('title'));
		}
	}

//------------------------------------ 오른쪽 -> 왼쪽
	function left_move()
	{
		var total_num = 0;
		var comp_len = $('#post_right_comp_idx option').size();
		var comp_text, comp_val;

		for (var i = 0; i < comp_len; i++)
		{
			if($("#post_right_comp_idx option:eq(" + i + ")").attr("selected") == 'selected')
			{
				comp_text = $("#post_right_comp_idx option:eq(" + i + ")").text();
				comp_val  = $("#post_right_comp_idx option:eq(" + i + ")").val();

				$('#post_leftt_comp_idx').append('<option value="' + comp_val + '">' + comp_text + '</option>');
				$("#post_right_comp_idx option:eq(" + i + ")").remove();
				total_num = total_num + 1;
			}
		}
		if (total_num == 0)
		{
			check_auth_popup($('#post_right_comp_idx').attr('title'));
		}
	}
//]]>
</script>
<?
	}
?>