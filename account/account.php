<?
/*
	수정 : 2012.11.22
	위치 : 회계업무 > 운영비관리
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ai.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;assdate=' . $send_assdate . '&amp;asedate=' . $send_asedate;
	$f_search  = $f_search . '&amp;astype=' . $send_astype . '&amp;asgubun=' . $send_asgubun . '&amp;asclass=' . $send_asclass . '&amp;asbank=' . $send_asbank . '&amp;ascard=' . $send_ascards . '&amp;asclient=' . $send_asclient;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="assdate"  value="' . $send_assdate . '" />
		<input type="hidden" name="asedate"  value="' . $send_asedate . '" />
		<input type="hidden" name="astype"   value="' . $send_astype . '" />
		<input type="hidden" name="asgubun"  value="' . $send_asgubun . '" />
		<input type="hidden" name="asclass"  value="' . $send_asclass . '" />
		<input type="hidden" name="asbank"   value="' . $send_asbank . '" />
		<input type="hidden" name="ascard"   value="' . $send_ascard . '" />
		<input type="hidden" name="asclient" value="' . $send_asclient . '" />
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
	$link_list  = $local_dir . "/bizstory/account/account_list.php";  // 목록
	$link_form  = $local_dir . "/bizstory/account/account_form.php";  // 등록
	$link_ok    = $local_dir . "/bizstory/account/account_ok.php";    // 저장
	$link_excel = $local_dir . "/bizstory/account/account_excel.php"; // 액셀
	$link_print = $local_dir . "/bizstory/account/account_print.php"; // 인쇄

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';

	if ($auth_menu['down'] == "Y") // 다운로드버튼
	{
		$btn_down = '<a href="javascript:void(0);" class="btn_sml" onclick="list_excel()"><span>엑셀</span></a>';
	}
	if ($auth_menu['print'] == "Y") // 인쇄버튼
	{
		$btn_print = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print()"><span><em class="print"></em>인쇄</span></a>';
	}
?>
<div class="tablewrapper">

	<div id="tableheader">
		<? include $local_path . "/bizstory/comp_set/part_menu_inc.php"; ?>

		<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
			<?=$form_default;?>
			<div class="search">
				<p>검&nbsp;&nbsp;&nbsp;색</p>

				<?=code_select($set_account_type, "astype", "search_astype", $astype, '전체종류', '전체종류');?>
				<select id="search_asgubun" name="asgubun" title="전체구분">
					<option value="">전체구분</option>
				</select>
				<select id="search_asclass" name="asclass" title="전체계정과목">
					<option value="all">전체계정과목</option>
				</select>
				<select id="search_asbank" name="asbank" title="전체통장">
					<option value="all">전체통장</option>
				</select>
				<select id="search_ascard" name="ascard" title="전체카드">
					<option value="all">전체카드</option>
				</select>
				<select id="search_asclient" name="asclient" title="전체거래처">
					<option value="all">전체거래처</option>
				</select>
				<input type="text" id="search_assdate" name="assdate" class="type_text datepicker" value="<?=$assdate;?>" title="시작일을 입력하세요." size="10" />
				~
				<input type="text" id="search_asedate" name="asedate" class="type_text datepicker" value="<?=$asedate;?>" title="종료일을 입력하세요." size="10" />
				<a href="javascript:void(0);" class="btn_sml" onclick="check_search()"><span>검색</span></a>
				<?=$btn_down;?>
				<?=$btn_print;?>
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
	var link_list  = '<?=$link_list;?>';
	var link_form  = '<?=$link_form;?>';
	var link_ok    = '<?=$link_ok;?>';
	var link_excel = '<?=$link_excel;?>';
	var link_print = '<?=$link_print;?>';

	part_information('<?=$code_part;?>', 'account_gubun', 'search_asgubun', '<?=$asgubun;?>', 'select');
	part_information('<?=$code_part;?>', 'account_class', 'search_asclass', '<?=$asclass;?>', 'select');
	part_information('<?=$code_part;?>', 'account_bank', 'search_asbank', '<?=$asbank;?>', 'select');
	part_information('<?=$code_part;?>', 'account_card', 'search_ascard', '<?=$ascard;?>', 'select');
	part_information('<?=$code_part;?>', 'client_info', 'search_asclient', '<?=$asclient;?>', 'select');

	$(".datepicker").datepicker();

	list_data();

//------------------------------------ 검색
	function check_search()
	{
		document.listform.astype.value   = $('#search_astype').val();   // 종류
		document.listform.asgubun.value  = $('#search_asgubun').val();  // 구분
		document.listform.asclass.value  = $('#search_asclass').val();  // 계정과목
		document.listform.asbank.value   = $('#search_asbank').val();   // 통장
		document.listform.ascard.value   = $('#search_ascard').val();   // 카드
		document.listform.asclient.value = $('#search_asclient').val(); // 거래처
		document.listform.assdate.value  = $('#search_assdate').val();  // 시작일
		document.listform.asedate.value  = $('#search_asedate').val();  // 종료일

		view_close();
		list_data();
		return false;
	}
//]]>
</script>