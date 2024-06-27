<?
/*
	수정 : 2012.05.02
	위치 : 업무폴더 > 거래처목록
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ci.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shgroup=' . $send_shgroup;
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
		<input type="hidden" name="shgroup" value="' . $send_shgroup . '" />
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
	$link_list         = $local_dir . "/bizstory/work/client_list.php";      // 목록
	$link_view         = $local_dir . "/bizstory/work/client_view.php";      // 보기
	$link_excel        = $local_dir . "/bizstory/work/client_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/work/client_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/work/client_print_sel.php"; // 상세인쇄

	if ($auth_menu['down'] == "Y") // 다운로드버튼
	{
		//$btn_down = '<a href="javascript:void(0);" class="btn_sml" onclick="list_excel()"><span>엑셀</span></a>';
	}
	if ($auth_menu['print'] == "Y") // 인쇄버튼
	{
		//$btn_print     = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print()"><span><em class="print"></em>인쇄</span></a>';
		//$btn_print_sel = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print_detail()"><span><em class="print"></em>상세인쇄</span></a>';
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
				<p>검&nbsp;&nbsp;&nbsp;색</p>
				<select name="shgroup" id="search_shgroup" title="전체거래처분류">
					<option value="">전체거래처분류</option>
				</select>
				<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
					<option value="ci.client_name"<?=selected($swhere, 'ci.client_name');?>>거래처명</option>
					<option value="ci.charge_info"<?=selected($swhere, 'ci.charge_info');?>>담당자명</option>
					<option value="ci.client_email"<?=selected($swhere, 'ci.client_email');?>>이메일</option>
					<option value="ci.tel_num"<?=selected($swhere, 'ci.tel_num');?>>연락처</option>
					<option value="ci.address"<?=selected($swhere, 'ci.address');?>>주소</option>
				</select>
				<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
				<a href="javascript:void(0);" class="btn_sml" onclick="check_search()"><span>검색</span></a>
				<?=$btn_down;?>
				<?=$btn_print;?>
				<?=$btn_print_sel;?>
			</div>
		</form>
	</div>

	<div id="data_view" title="상세보기"></div>

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

<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/client_memo.js" charset="utf-8"></script>
<script type="text/javascript">
//<![CDATA[
	var link_list         = '<?=$link_list;?>';
	var link_view         = '<?=$link_view;?>';
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
		document.listform.shgroup.value = $('#search_shgroup').val(); // 거래처분류

		view_close();
		list_data();
		return false;
	}

	part_information('<?=$code_part;?>', 'client_group', 'search_shgroup', '<?=$shgroup;?>', 'select');
	list_data();

//------------------------------------ 메모 관련
	var memo_list = '<?=$local_dir;?>/bizstory/work/client_view_memo_list.php';
	var memo_form = '<?=$local_dir;?>/bizstory/work/client_view_memo_form.php';
	var memo_ok   = '<?=$local_dir;?>/bizstory/work/client_view_memo_ok.php';
	var memo_chk_val = 'close';
	var file_chk_num = 0;
	var oEditors_memo = [];

<?
	if ($ci_idx != '')
	{
		echo 'view_open("' . $ci_idx . '");';
		echo 'memo_view();';
	}
?>
//]]>
</script>