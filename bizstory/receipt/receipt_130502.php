<?
/*
	수정 : 2012.09.04
	위치 : 고객관리 > 접수목록
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	if ($shclass == '')
	{
		$shclass      = 'all';
		$send_shclass = 'all';
		$recv_shclass = 'all';
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ri.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shclass=' . $send_shclass . '&amp;shstatus=' . $send_shstatus . '&amp;shstaff=' . $send_shstaff;
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
		<input type="hidden" name="shclass"  value="' . $send_shclass . '" />
		<input type="hidden" name="shstatus" value="' . $send_shstatus . '" />
		<input type="hidden" name="shstaff"  value="' . $send_shstaff . '" />
		<input type="hidden" name="sdate1"   value="' . $send_sdate1 . '" />
		<input type="hidden" name="sdate2"   value="' . $send_sdate2 . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list         = $local_dir . "/bizstory/receipt/receipt_list.php";      // 목록
	$link_form         = $local_dir . "/bizstory/receipt/receipt_form.php";      // 등록
	$link_view         = $local_dir . "/bizstory/receipt/receipt_view.php";      // 보기
	$link_ok           = $local_dir . "/bizstory/receipt/receipt_ok.php";        // 저장
	$link_excel        = $local_dir . "/bizstory/receipt/receipt_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/receipt/receipt_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/receipt/receipt_print_sel.php"; // 상세인쇄

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
				<select id="search_shclass" name="shclass" title="전체분류">
					<option value="">전체분류</option>
				</select>
				<select id="search_shstatus" name="shstatus" title="전체상태">
					<option value="all">전체상태</option>
					<option value="end_no">미처리</option>
				</select>
				<select id="search_shsgroup" name="shsgroup" title="전체직원그룹">
					<option value="all">전체직원그룹</option>
				</select>
				<select id="search_shstaff" name="shstaff" title="전체직원">
					<option value="all">전체직원</option>
				</select>
				<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
					<option value="ci.client_name"<?=selected($swhere, 'ci.client_name');?>>거래처명</option>
					<option value="ri.subject"<?=selected($swhere, 'ri.subject');?>>제목</option>
					<option value="ri.remark"<?=selected($swhere, 'ri.remark');?>>내용</option>
					<option value="ri.writer"<?=selected($swhere, 'ri.writer');?>>작성자</option>
					<option value="ri.tel_num"<?=selected($swhere, 'ri.tel_num');?>>연락처</option>
				</select>
				<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
				<input type="text" id="search_sdate1" name="sdate1" class="type_text datepicker" title="기간을 입력하세요." size="10" style="width:80px;" value="<?=$sdate1;?>" />
				~
				<input type="text" id="search_sdate2" name="sdate2" class="type_text datepicker" title="기간을 입력하세요." size="10" style="width:80px;" value="<?=$sdate2;?>" />
				<a href="javascript:void(0);" class="btn_sml" onclick="check_search('')"><span>검색</span></a>
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
		<input type="hidden" id="list_list_type"  name="list_type"  value="" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
<?
	if ($sub_type == '')
	{ }
	else if ($sub_type == 'postform' || $sub_type == 'modifyform')
	{
		echo '<div id="work_form_view">';
		include $local_path . "/bizstory/receipt/receipt_form.php";
		echo '</div>';
	}
?>
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
	function check_search(list_type)
	{
		var stext       = $('#search_stext').val();
		var stext_title = $('#search_stext').attr('title');
		if (stext == stext_title) stext = '';

		document.listform.swhere.value = $('#search_swhere').val();
		document.listform.stext.value  = stext;

		document.listform.list_type.value = list_type;
		document.listform.shclass.value   = $('#search_shclass').val(); // 분류
		document.listform.shstatus.value  = $('#search_shstatus').val(); // 상태
		document.listform.shstaff.value   = $('#search_shstaff').val(); // 직원
		document.listform.sdate1.value    = $('#search_sdate1').val();
		document.listform.sdate2.value    = $('#search_sdate2').val();

		view_close();
		list_data();
		return false;
	}

//------------------------------------ 검색 - 미처리건
	function list_search(str)
	{
		var stext       = $('#search_stext').val();
		var stext_title = $('#search_stext').attr('title');
		if (stext == stext_title) stext = '';

		document.listform.list_type.value = str; // 구분
		document.listform.shclass.value   = ''; // 분류
		document.listform.shstatus.value  = ''; // 상태
		document.listform.shstaff.value   = ''; // 직원
		document.listform.sdate1.value    = '';
		document.listform.sdate2.value    = '';
		document.listform.swhere.value    = '';
		document.listform.stext.value     = '';

		view_close();
		list_data();
		return false;
	}

//------------------------------------ 거래처검색
	function search_client(str1, str2)
	{
		$('#search_swhere').val(str1);
		$('#search_stext').val(str2);

		var list_type = $('#list_list_type').val();
		check_search(list_type);
	}

<?
	if ($sub_type == '')
	{
		if ($list_type == 'all_no')
		{
			echo 'list_search("all_no");';
		}
		else if ($list_type == 'my_no')
		{
			echo 'list_search("my_no");';
		}
		else
		{
			echo 'list_data();';
		}
?>

	$(".datepicker").datepicker({
		numberOfMonths: 2
	});

	part_information('<?=$code_part;?>', 'receipt_status', 'search_shstatus', '<?=$shstatus;?>', 'select');
	part_information('<?=$code_part;?>', 'receipt_class', 'search_shclass', '<?=$shclass;?>', 'select');
	part_information('<?=$code_part;?>', 'staff_group', 'search_shsgroup', '<?=$shsgroup;?>', 'select');
	part_information('<?=$code_part;?>', 'staff_info', 'search_shstaff', '<?=$shstaff;?>', 'select');
<?
	}

	if ($ri_idx > 0 && $sub_type == '')
	{
		echo "view_open('" . $ri_idx . "');";
	}
?>
//]]>
</script>