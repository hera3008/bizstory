<?
/*
	수정 : 2013.03.26
	위치 : 설정관리 > 회사관리 > 회사정보
*/
	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);

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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_ok = $local_dir . "/bizstory/comp_set/company_ok.php"; // 저장

	$where = " and comp.comp_idx = '" . $code_comp . "'";
	$data = company_info_data('view', $where);
?>
<div class="ajax_write">
	<div class="ajax_frame">
		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
			<input type="hidden" name="sub_type" value="modify" />

			<fieldset>
				<legend class="blind">회사정보</legend>
				<table class="tinytable write" summary="회사정보를 수정합니다.">
				<caption>회사정보</caption>
				<colgroup>
					<col width="100px" />
					<col />
					<col width="100px" />
					<col />
				</colgroup>
				<tbody>
				<?
					include $local_path . "/bizstory/maintain/company_form_inc.php";
				?>
				</tbody>
				</table>

				<div class="section">
					<div class="fr">
						<span class="btn_big_blue"><input type="submit" value="수정" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="location.href='<?=$this_page;?>?<?=$f_all;?>'"/></span>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<?
	include $local_path . "/bizstory/include/find_address_daum.php";
?>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/script_member.js" charset="utf-8"></script>
<script type="text/javascript">
//<![CDATA[
	var link_ok = '<?=$link_ok;?>';

//------------------------------------ Save
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

	// 상호명
		chk_msg = check_comp_name();
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

	// 대표자명
		chk_msg = check_boss_name();
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

	// 사업자등록번호
		chk_msg = check_comp_num();
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

	// 이메일
		chk_msg = check_comp_email();
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
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
						check_auth_popup('정상적으로 처리되었습니다.');
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
		return false;
	}
//]]>
</script>