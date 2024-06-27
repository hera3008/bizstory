<?
/*
	생성 : 2012.12.27
	수정 : 2012.12.27
	위치 : 설정폴더(총관리자용) > 푸쉬관리 > 푸쉬공지 - 등록/수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$sn_idx = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if (($auth_menu['int'] == 'Y' && $sn_idx == '') || ($auth_menu['mod'] == 'Y' && $sn_idx != '')) // 등록, 수정권한
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
		$where = " and sn.sn_idx = '" . $sn_idx . "'";
		$data = sms_notice_data("view", $where);

		if ($data['comp_all'] == "") $data['comp_all'] = "Y";

		$comp_where = " and comp.auth_yn = 'Y' and comp.view_yn = 'Y'";
		$comp_order = "comp.comp_name asc";
		$comp_list = company_info_data('list', $comp_where, $comp_order, '', '');
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_default;?>
			<input type="hidden" name="param[comp_idx]" id="post_comp_idx" value="" />

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
					<th><label for="post_sn_type">어플선택</label></th>
					<td>
						<div class="left">
							<select name="" id="" title="">
				<?
					foreach ($set_sn_type as $k => $v)
					{
				?>
								<option value="<?=$k;?>"><?=$v;?></option>
				<?
					}
				?>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_contents">내용</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[contents]" id="post_contents" class="type_text" title="내용 입력하세요." size="60" value="<?=$data["contents"];?>" />
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
							</select>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<span class="btn_big_green"><input type="submit" value="등록" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>

					<input type="hidden" name="sub_type" value="post" />
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
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_contents').val(); // 내용
		chk_title = $('#post_contents').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

	// 업체선택
		var comp_all = $("input:checkbox[name='param[comp_all]']:checked").length;
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
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				beforeSubmit: function(){$("#loading").fadeIn('slow').fadeOut('slow');},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
				<?
					$f_default1 = str_replace('&amp;', '&', $f_default);;
				?>
						location.href = '?<?=$f_default1;?>';
					}
					else check_auth_popup(msg.error_string);
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