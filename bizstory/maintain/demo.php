<?
/*
	생성 : 2012.12.17
	수정 : 2012.12.17
	위치 : 설정폴더(관리자) > 설정관리 > 데모신청
*/
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'demo.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';

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
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
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
	$link_list         = $local_dir . "/bizstory/maintain/demo_list.php"; // 목록
	$link_form         = $local_dir . "/bizstory/maintain/demo_form.php"; // 등록
	$link_view         = $local_dir . "/bizstory/maintain/demo_view.php"; // 보기
	$link_ok           = $local_dir . "/bizstory/maintain/demo_ok.php";   // 저장
	$link_excel        = $local_dir . "/bizstory/maintain/demo_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/maintain/demo_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/maintain/demo_print_sel.php"; // 상세인쇄

	if ($auth_menu['down'] == "Y") // 다운로드버튼
	{
		$btn_down = '<a href="javascript:void(0);" class="btn_sml" onclick="list_excel()"><span>엑셀</span></a>';
	}
	if ($auth_menu['print'] == "Y") // 인쇄버튼
	{
		$btn_print     = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print()"><span><em class="print"></em>인쇄</span></a>';
		$btn_print_sel = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print_detail()"><span><em class="print"></em>상세인쇄</span></a>';
	}

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';
?>
<div class="tablewrapper">
	<div id="tableheader">
		<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
			<?=$form_default;?>
			<div class="search">
				<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
					<option value="demo.comp_name" <?=selected($swhere, 'demo.comp_name');?>>업체명</option>
					<option value="demo.boss_name" <?=selected($swhere, 'demo.boss_name');?>>대표자명</option>
					<option value="demo.tel_num"   <?=selected($swhere, 'demo.tel_num');?>>전화번호</option>
					<option value="demo.mem_email" <?=selected($swhere, 'demo.mem_email');?>>메일주소</option>
				</select>
				<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
				<a href="javascript:void(0);" onclick="check_search()" class="btn_sml"><span>검색</span></a>
				<?=$btn_down;?>
				<?=$btn_print;?>
				<?=$btn_print_sel;?>
			</div>
		</form>
	</div>

	<div id="data_view" title="상세보기 / 등록, 수정폼"></div>

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

	list_data();
//]]>
</script>