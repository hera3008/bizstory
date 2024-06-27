<?
/*
	생성 : 2012.07.03
	수정 : 2012.10.31
	위치 : 설정관리 > 에이전트관리 > 알림관리 > 알림게시판
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'abn.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;sbclass=' . $send_sbclass;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
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
		<input type="hidden" name="sbclass" value="' . $send_sbclass . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list         = $local_dir . "/bizstory/comp_set/agent_bnotice_list.php";      // 목록
	$link_form         = $local_dir . "/bizstory/comp_set/agent_bnotice_form.php";      // 등록
	$link_view         = $local_dir . "/bizstory/comp_set/agent_bnotice_view.php";      // 보기
	$link_ok           = $local_dir . "/bizstory/comp_set/agent_bnotice_ok.php";        // 저장
	$link_excel        = $local_dir . "/bizstory/comp_set/agent_bnotice_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/comp_set/agent_bnotice_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/comp_set/agent_bnotice_print_sel.php"; // 상세인쇄

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';
?>
<div class="tablewrapper">
	<div id="tableheader">
		<? include $local_path . "/bizstory/comp_set/part_menu_inc.php"; ?>

		<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
			<?=$form_default;?>
			<div class="search">
				<p>검&nbsp;&nbsp;&nbsp;색</p>
				<select id="search_sbclass" name="sbclass" title="전체분류">
					<option value="">전체분류</option>
				</select>
				<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
					<option value="abn.subject"<?=selected($swhere, 'abn.subject');?>>제목</option>
					<option value="abn.remark"<?=selected($swhere, 'abn.remark');?>>내용</option>
				</select>
				<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
				<a href="javascript:void(0);" class="btn_sml" onclick="check_search()"><span>검색</span></a>
			</div>
		</form>
	</div>

	<div id="data_view" title="상세보기 / 등록, 수정폼"></div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
</div>

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

		document.listform.swhere.value  = $('#search_swhere').val();
		document.listform.stext.value   = stext;
		document.listform.sbclass.value = $('#search_sbclass').val(); // 분류

		view_close();
		list_data();
		return false;
	}

	part_information('<?=$code_part;?>', 'bnotice_class', 'search_sbclass', '<?=$sbclass;?>', 'select');
	list_data();
//]]>
</script>