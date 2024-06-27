<?
/*
	생성 : 2012.04.09
	수정 : 2012.05.08
	위치 : 업무폴더 > 점검보고서
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'rr.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;sdate1=' . $send_sdate1 . '&amp;sdate2=' . $send_sdate2;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"   value="' . $send_swhere . '" />
		<input type="hidden" name="stext"    value="' . $send_stext . '" />
		<input type="hidden" name="sdate1"   value="' . $send_sdate1 . '" />
		<input type="hidden" name="sdate2"   value="' . $send_sdate2 . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1"   value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2"   value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list         = $local_dir . "/bizstory/work/client_report_list.php";      // 목록
	$link_form         = $local_dir . "/bizstory/work/client_report_form.php";      // 등록
	$link_view         = $local_dir . "/bizstory/work/client_report_view.php";      // 보기
	$link_ok           = $local_dir . "/bizstory/work/client_report_ok.php";        // 저장
	$link_excel        = $local_dir . "/bizstory/work/client_report_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/work/client_report_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/work/client_report_print_sel.php"; // 상세인쇄

	if ($auth_menu['down'] == "Y") // 다운로드버튼
	{
		$btn_down = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_excel()"><span>엑셀</span></a>';
	}
	if ($auth_menu['print'] == "Y") // 인쇄버튼
	{
		$btn_print     = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_print()"><span><em class="print"></em>인쇄</span></a>';
		$btn_print_sel = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_print_detail()"><span><em class="print"></em>상세인쇄</span></a>';
	}

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';
?>
<div class="tablewrapper">
	<div id="tableheader">
		<? include $local_path . "/bizstory/comp_set/part_menu_inc.php"; ?>

		<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
			<?=$form_default;?>
			<div class="search">
				<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
					<option value="ci.client_name"<?=selected($swhere, 'ci.client_name');?>>거래처명</option>
				</select>
				<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
				<a href="javascript:void(0);" class="btn_sml fl" onclick="check_search()"><span>검색</span></a>
				<?=$btn_down;?>
				<?=$btn_print;?>
				<?=$btn_print_sel;?>
			</div>
		</form>
	</div>
	<div id="data_view" title="상세보기 / 등록, 수정폼"></div>
<?
	if ($sub_type == '')
	{
?>
	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
<?
	}
	else if ($sub_type == 'postform' || $sub_type == 'modifyform')
	{
		include $local_path . "/bizstory/work/client_report_form.php";
	}
?>
</div>

<script type="text/javascript" src="<?=$local_dir;?>/bizstory/editor/tinymce/jquery.tinymce.js"></script>
<script type="text/javascript">
//<![CDATA[
	var link_list         = '<?=$link_list;?>';
	var link_form         = '<?=$link_form;?>';
	var link_view         = '<?=$link_view;?>';
	var link_ok           = '<?=$link_ok;?>';
	var link_excel        = '<?=$link_excel;?>';
	var link_print        = '<?=$link_print;?>';
	var link_print_detail = '<?=$link_print_detail;?>';

//------------------------------------ 검색
	function check_search()
	{
		var stext       = $('#search_stext').val();
		var stext_title = $('#search_stext').attr('title');
		if (stext == stext_title) stext = '';

		document.listform.swhere.value = $('#search_swhere').val();
		document.listform.stext.value  = stext;

		view_close();
		list_data();
		return false;
	}

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_part_idx').val(); // 지사
		chk_title = $('#post_part_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_ci_idx').val(); // 거래처
		chk_title = $('#post_ci_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type:'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						popupform_close();
						list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 거래처를 선택하면 해당데이타보여주기
	function check_client_info(ci_idx, rr_idx)
	{
		if (ci_idx != '')
		{
			$('#post_client').val(ci_idx);
			$('#post_rr_idx').val(rr_idx);

			$.ajax({
				type: 'post', dataType: 'html', url: '<?=$local_dir;?>/bizstory/work/client_report_check.php',
				data   : $('#postform').serialize(),
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success: function(msg) {
					$('#sub_receipt_report').html(msg);
				}
			});

			$.ajax({
				type: 'post', dataType: 'html', url: '<?=$local_dir;?>/bizstory/work/client_report_receipt.php',
				data   : $('#postform').serialize(),
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success: function(msg) {
					$('#sub_receipt_list').html(msg);
				}
			});
		}
	}

	list_data();
//]]>
</script>