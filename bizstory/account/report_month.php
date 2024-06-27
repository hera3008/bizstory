<?
/*
	수정 : 2012.11.22
	위치 : 회계업무 > 운영비월별
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	if ($asyear == '')
	{
		$asyear      = date('Y');
		$send_asyear = $asyear;
		$recv_asyear = $asyear;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;asyear=' . $send_asyear;
	$f_search  = $f_search . '&amp;astype=' . $send_astype . '&amp;asgubun=' . $send_asgubun . '&amp;asclass=' . $send_asclass . '&amp;asbank=' . $send_asbank . '&amp;ascard=' . $send_ascards . '&amp;asclient=' . $send_asclient;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="asyear"   value="' . $send_asyear . '" />
		<input type="hidden" name="astype"   value="' . $send_astype . '" />
		<input type="hidden" name="asgubun"  value="' . $send_asgubun . '" />
		<input type="hidden" name="asclass"  value="' . $send_asclass . '" />
		<input type="hidden" name="asbank"   value="' . $send_asbank . '" />
		<input type="hidden" name="ascard"   value="' . $send_ascard . '" />
		<input type="hidden" name="asclient" value="' . $send_asclient . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list  = $local_dir . "/bizstory/account/report_month_list.php";  // 목록
	$link_excel = $local_dir . "/bizstory/account/report_month_excel.php"; // 액셀
	$link_print = $local_dir . "/bizstory/account/report_month_print.php"; // 인쇄

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

				<select id="search_asyear" name="asyear" title="년도">
		<?
			for ($i = 2011; $i <= date('Y') + 2; $i++)
			{
		?>
					<option value="<?=$i;?>"<?=selected($i, $asyear);?>><?=$i;?></option>
		<?
			}
		?>
				</select>
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
		<input type="hidden" id="list_class_code" name="class_code" value="" />
		<input type="hidden" id="list_acc_month"  name="acc_month"  value="" />
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

	list_data();

//------------------------------------ 검색
	function check_search()
	{
		document.listform.asyear.value   = $('#search_asyear').val();   // 년도
		document.listform.astype.value   = $('#search_astype').val();   // 종류
		document.listform.asgubun.value  = $('#search_asgubun').val();  // 구분
		document.listform.asclass.value  = $('#search_asclass').val();  // 계정과목
		document.listform.asbank.value   = $('#search_asbank').val();   // 통장
		document.listform.ascard.value   = $('#search_ascard').val();   // 카드
		document.listform.asclient.value = $('#search_asclient').val(); // 거래처

		view_close();
		list_data();
		return false;
	}
//]]>
</script>