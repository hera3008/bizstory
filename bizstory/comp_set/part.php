<?
/*
	수정 : 2013.03.22
	위치 : 설정관리 > 코드관리 > 지사관리
*/
	$code_comp      = $_SESSION[$sess_str . '_comp_idx'];
	$set_part_num   = $comp_set_data['part_cnt'];
	$set_file_class = $comp_set_data['file_class'];

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
	$link_list = $local_dir . "/bizstory/comp_set/part_list.php"; // 목록
	$link_form = $local_dir . "/bizstory/comp_set/part_form.php"; // 등록
	$link_ok   = $local_dir . "/bizstory/comp_set/part_ok.php";   // 저장
?>
<div class="info_text">
	<ul>
		<li>등록은 <?=$set_part_num;?>까지 가능합니다.</li>
	</ul>
</div>
<hr />

<div class="tablewrapper">
	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
	var link_list = '<?=$link_list;?>';
	var link_form = '<?=$link_form;?>';
	var link_ok   = '<?=$link_ok;?>';

//------------------------------------ 등록
	function check_form()
	{
		var action_num = 0, chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_part_name').val();
		chk_title = $('#post_part_name').attr('title');
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
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						popupform_close();
						list_data();
				<?
					if ($set_file_class == 'OUT') {
				?>
						filecenter_part_folder(msg.part_idx);
				<?
					}
				?>
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

//------------------------------------ 삭제하기
	function check_delete_part(idx)
	{
		if (confirm("선택하신 지사를 삭제하시겠습니까?\n\n지사를 삭제하시면 관련 업무, 직원, 거래처정보 등 모두 삭제됩니다."))
		{
			check_code_data('delete', '', idx, '');
			view_close();
		}
	}

	list_data();
//]]>
</script>
<? include $local_path . "/bizstory/js/filecenter_js.php"; ?>