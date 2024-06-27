<?
/*
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 설정관리 > 메뉴관리
*/
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
	$link_list = $local_dir . "/bizstory/maintain/menu_list.php"; // 목록
	$link_form = $local_dir . "/bizstory/maintain/menu_form.php"; // 등록
	$link_ok   = $local_dir . "/bizstory/maintain/menu_ok.php";   // 저장
	$link_up   = $local_dir . "/bizstory/maintain/menu_up.php";   // 상위메뉴
?>
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
	var link_up   = '<?=$link_up;?>';

//------------------------------------ 메뉴단계변경
	function up_menu_change(menu_depth, idx)
	{
		if (menu_depth == "") menu_depth = $('#post_menu_depth').val();

		$.ajax({
			type    : 'post', dataType: 'html', url: link_up,
			data : {
				"menu_depth" : menu_depth,
				"mi_idx" : idx
			},
			success : function(msg) {
				$('#up_menu_list').html(msg);
			}
		});
	}

//------------------------------------ 선택된 메뉴 하위메뉴
	function down_menu_change(menu_depth, sel_depth, idx)
	{
		if (menu_depth == "")
		{
			menu_depth = document.postform.menu_depth.value;
		}
		$.ajax({
			type    : 'post', dataType: 'html', url: link_up,
			data     : {
				"menu_depth" : menu_depth,
				"sel_depth" : sel_depth,
				"mi_idx" : idx
			},
			success : function(msg) {
				$('#up_menu_list').html(msg);
			}
		});
	}

//------------------------------------ 메뉴단계
	function check_menu_depth()
	{
		var chk_msg = ''; var chk_total = '';
		var chk_value = $('#post_menu_depth').val();
		var chk_title = $('#post_menu_depth').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 메뉴명
	function check_menu_name()
	{
		var chk_msg = ''; var chk_total = '';
		var chk_value = $('#post_menu_name').val();
		var chk_title = $('#post_menu_name').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '', chk_msg = '';

		chk_msg = check_menu_depth(); // 메뉴단계
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		chk_msg = check_menu_name(); // 메뉴명
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		var post_num = $('#post_menu_depth').val(); // 상위메뉴
		if(post_num != "" && post_num > 1)
		{
			for(i = 1; i < post_num; i++)
			{
				if ($('#post_menu' + i).val() == "")
				{
					chk_total = chk_total + i + '차 메뉴를 선택해 주세요.<br />';
					action_num++;
				}
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
						popupform_close();
						list_data();
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
	list_data();
//]]>
</script>