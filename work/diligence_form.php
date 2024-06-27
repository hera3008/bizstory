<?
// 출근, 퇴근클릭시 화면
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y') // 등록권한
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
		$where = " and ds.comp_idx = '" . $code_comp . "'and ds.part_idx = '" . $code_part . "'";
		$set_dili = diligence_set_data('view', $where);

		$now_hour   = date('H');
		$now_minute = date('i');
		$now_second = date('s');

		$data_date = query_view("select date_format(date_sub('" . $sdate . "',interval 1 day),'%Y%m%d') as prev_date");
		$pdate = $data_date["prev_date"];
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
			<input type="hidden" name="prev_date" id="post_prev_date" value="<?=$pdate;?>" />
			<input type="hidden" name="param[part_idx]" id="post_part_idx" value="<?=$code_part;?>" />
			<input type="hidden" name="param[start_date]" id="post_start_date" value="<?=$sdate;?>" />
			<input type="hidden" name="param[start_time]" id="post_start_time" value="<?=$now_hour;?>:<?=$now_minute;?>" />
<?
	if ($dili_status == 'start') // 출근
	{
		$set_start_time = $set_dili['start_time'];
		$set_start_time = str_replace(':', '', $set_start_time);

		$set_start_message = $set_dili['start_message'];
		$set_late_message  = $set_dili['late_message'];

		$now_time = $now_hour . $now_minute;

		if ($set_start_time < $now_time)
		{
			$dili_title   = '<h3>지각!</h3> <span>' . $now_hour . '시 ' . $now_minute . '분 ' . $now_second . '초</span>';
			$dili_message = $set_late_message;
			$dili_status  = '31'; // 지각
			$late_yn      = 'Y';
		}
		else
		{
			$dili_title   = '<h3>출근확인</h3> <span>' . $now_hour . '시 ' . $now_minute . '분 ' . $now_second . '초</span>';
			$dili_message = $set_start_message;
			$dili_status  = '11'; // 출근
			$late_yn      = 'N';
		}
?>
			<div><?=$dili_title;?></div>
			<div>
				<?=$dili_message;?>
<?
	// 지각일 경우
		if ($late_yn == 'Y') {
?>
				<div>
					<span>지각사유</span>
					<span>
						<label for="post_open_yn1"><input type="radio" name="param[open_yn]" id="post_open_yn1" value="Y" checked="checked" /> 전체공개</label>
						<label for="post_open_yn2"><input type="radio" name="param[open_yn]" id="post_open_yn2" value="N" /> 마스터에게만 공개</label>
					</span>
					<textarea name="param[remark]" id="post_remark" title="지각사유를 입력하세요."></textarea>
				</div>
<?
		}
?>
			</div>
<?
	}
	else if ($dili_status == 'end') // 퇴근
	{
		$set_end_time    = $set_dili['end_time'];
		$set_end_time    = str_replace(':', '', $set_end_time);
		$set_end_message = $set_dili['end_message'];
		$dili_title      = '<h3>퇴근확인!</h3> <span>' . $now_hour . '시 ' . $now_minute . '분 ' . $now_second . '초</span>';
		$dili_status     = '21';
?>
			<div><?=$dili_title;?></div>
			<div>
				<?=$set_end_message;?>
			</div>
<?
	}
?>
			<div class="section">
				<div class="fr">
					<span class="btn_big_violet"><input type="submit" value="확인" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close()" /></span>

					<input type="hidden" name="sub_type" value="dili_post" />
					<input type="hidden" name="param[dili_status]" id="post_dili_status" value="<?=$dili_status;?>" />
				</div>
			</div>

		</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript" src="<?=$local_dir;?>/bizstory/editor/tinymce/jquery.tinymce.js"></script>
<script type="text/javascript">
//<![CDATA[
	part_information('<?=$code_part;?>', 'dili_status', 'post_dili_status', '<?=$data['dili_status'];?>', '');

	$('#post_remark').tinymce({
		script_url : '<?=$local_dir;?>/bizstory/editor/tinymce/tiny_mce.js',
		theme : "advanced", skin : "default",
		width : "100%", height : "60",
		plugins : "autolink,emotions,inlinepopups",

		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,sub,sup,|,forecolor,backcolor,|,charmap,emotions",
		theme_advanced_buttons2 : "", theme_advanced_buttons3 : "", theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top", theme_advanced_toolbar_align : "left",
		theme_advanced_resizing : true
	});

//------------------------------------ 출근, 퇴근확인
	function check_form()
	{
		$("#popup_notice_view").hide();

		$.ajax({
			type    : "post", dataType : "json", url : link_ok,
			data    : $('#postform').serialize(),
			success : function(msg) {
				if (msg.success_chk == "Y")
				{
					popupform_close();
					list_data();
				}
				else check_auth_popup(msg.error_string);
			}
		});

		return false;
	}
//]]>
</script>
<?
	}
?>
