<?
/*
	수정 : 2013.04.09
	위치 : 업무관리 > 나의 업무 > 쪽지 > 쪽지목록
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list = $local_dir . "/bizstory/msg/msg_list.php"; // 목록
	$link_form = $local_dir . "/bizstory/msg/msg_form.php"; // 등록
	$link_view = $local_dir . "/bizstory/msg/msg_view.php"; // 보기
	$link_ok   = $local_dir . "/bizstory/msg/msg_ok.php";   // 저장

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';
?>
<div class="tablewrapper">

	<div id="data_view" title="상세보기 / 등록, 수정폼"></div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<?=$form_page;?>
		<input type="hidden" id="list_idx2"       name="idx2"         value="" />
		<input type="hidden" id="list_mem_idx"    name="list_mem_idx" value="" />

		<div id="data_list"></div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
	var link_list = '<?=$link_list;?>';
	var link_form = '<?=$link_form;?>';
	var link_view = '<?=$link_view;?>';
	var link_ok   = '<?=$link_ok;?>';

//------------------------------------ 목록
	function msg_list_data(idx)
	{
		$('#list_idx').val(idx);
		$('#list_idx2').val(idx);

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/msg/msg_list_list.php',
			data: $('#listform').serialize(),
			success: function(msg) {
				$('#msg_data_list').html(msg);
			},
			complete: function(){
				$("#loading").fadeOut('slow');
				$("#backgroundPopup").fadeOut("slow");
			}
		});
	}

//------------------------------------ 선택삭제
	function select_delete()
	{
		var chk_num1 = chk_checkbox_num('mridx');
		var chk_num2 = chk_checkbox_num('msidx');
		var chk_num = chk_num1 + chk_num2;
		if (chk_num == 0)
		{
			check_auth_popup('삭제할 데이타를 선택하세요.');
		}
		else
		{
			if (confirm("선택하신 데이타를 삭제하시겠습니까?"))
			{
				$('#list_sub_type').val('delete_select');

				$("#loading").fadeIn('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$.ajax({
					type: 'post', dataType: 'json', url: link_ok,
					data: $('#listform').serialize(),
					success: function(msg) {
						if (msg.success_chk == "Y")
						{
							view_close();
							list_data();
							msg_list_data($('#list_idx').val());
						}
						else
						{
							$("#loading").fadeOut('slow');
							$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
							check_auth_popup(msg.error_string);
						}
					}
				});
			}
		}
	}

//------------------------------------ 쪽지삭제
	function check_msg_delete(str, idx, idx2)
	{
		if (confirm("선택하신 쪽지를 삭제하시겠습니까?"))
		{
			if (str == 'send')
			{
				$('#list_sub_type').val('delete_send');
			}
			else
			{
				$('#list_sub_type').val('delete_receive');
			}
			$('#list_idx').val(idx);

			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: "post", dataType: 'json', url: link_ok,
				data: $('#listform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						if (msg.error_string != '')
						{
							check_auth_popup(msg.error_string);
						}
						list_data();
						msg_list_data(idx2);
					}
					else
					{
						check_auth_popup(msg.error_string);
					}
				}
			});
		}
	}

//------------------------------------ 팝업보기 열기
	function popupview_open(idx2, chk_idx, idx, msg_type)
	{
		$('#list_mem_idx').val(chk_idx);
		$('#list_idx2').val(idx2);
		$('#list_idx').val(idx);
		$('#list_post_value').val(msg_type);
		$.ajax({
			type: "get", dataType: 'html', url: link_view,
			data: $('#listform').serialize(),
			success : function(msg) {
				$("#msg_view_remark_" + idx2).html(msg);
				$("#msg_view_remark_" + idx2).css({"display":"block"});
			}
		});
	}

//------------------------------------ 팝업보기 열기
	function popupview_close(idx)
	{
		$("#msg_view_remark_" + idx).css({"display":"none"});
	}

	list_data();
	msg_list_data('');
//]]>
</script>
<? include $local_path . "/bizstory/js/filecenter_js.php"; ?>