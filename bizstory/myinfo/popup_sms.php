<?
/*
	생성 : 2012.12.27
	수정 : 2012.12.27
	위치 : 직원정보 - SMS보내기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$send_idx  = $_SESSION[$sess_str . '_mem_idx'];

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

	$mem_where = " and mem.mem_idx = '" . $receive_idx . "'";
	$mem_data = member_info_data('view', $mem_where);
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>

		<fieldset>
			<legend class="blind">SMS작성 폼</legend>
			<table class="tinytable write" summary="SMS작성을 등록합니다.">
			<caption>SMS작성</caption>
			<colgroup>
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_receive_idx">받는자</label></th>
					<td>
						<div class="left">
							[<span style="color:<?=$set_color_list2[$mem_data['part_sort']];?>"><?=$mem_data['part_name'];?></span>:<?=$mem_data['group_name'];?>] <strong style="color:#ff6c00"><?=$mem_data['mem_name'];?></strong>
							<input type="hidden" name="receive_idx" id="post_receive_idx" value="<?=$receive_idx;?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_contents">문구</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[contents]" id="post_contents" class="type_text" title="문구를 입력하세요." size="50" value="<?=$data['contents'];?>" />
							<br />* 30자까지 가능합니다.
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<span class="btn_big_green"><input type="submit" value="등록" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close();" /></span>

					<input type="hidden" name="sub_type" value="post" />
				</div>
			</div>

		</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 등록
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#post_contents').val(); // 문구
		chk_title = $('#post_contents').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: '<?=$local_dir;?>/bizstory/myinfo/popup_sms_ok.php',
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$("#loading").fadeOut('slow');
						popupform_close();
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
//]]>
</script>
