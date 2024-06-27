<?
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	if ($sdate == '')
	{
		$sdate = date('Ymd');
		$send_sdate = $sdate;
		$recv_sdate = $sdate;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'mem.reg_date';
	if ($sorder2 == '') $sorder2 = 'asc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list         = $local_dir . "/bizstory/work/diligence_list.php";      // 목록
	$link_form         = $local_dir . "/bizstory/work/diligence_form.php";      // 등록
	$link_form2        = $local_dir . "/bizstory/work/diligence_form2.php";     // 등록
	$link_view         = $local_dir . "/bizstory/work/diligence_view.php";      // 보기
	$link_ok           = $local_dir . "/bizstory/work/diligence_ok.php";        // 저장
	$link_excel        = $local_dir . "/bizstory/work/diligence_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/work/diligence_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/work/diligence_print_sel.php"; // 상세인쇄

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
				<?=$btn_down;?>
				<?=$btn_print;?>
				<?=$btn_print_sel;?>
			</div>
		</form>
	</div>

	<div id="data_view" title="상세보기"></div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"    name="sub_type"    value="" />
		<input type="hidden" id="list_sub_action"  name="sub_action"  value="" />
		<input type="hidden" id="list_idx"         name="idx"         value="" />
		<input type="hidden" id="list_post_value"  name="post_value"  value="" />
		<input type="hidden" id="list_code_part"   name="code_part"   value="<?=$code_part;?>" />
		<input type="hidden" id="list_sdate"       name="sdate"       value="" />
		<input type="hidden" id="list_dili_status" name="dili_status" value="" />
		<input type="hidden" id="list_mem_idx"     name="mem_idx"     value="" />

		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
</div>
<!--
		<input type="hidden" id="list_stime"      name="stime"      value="" />
//-->
<script type="text/javascript">
//<![CDATA[
	var link_list         = '<?=$link_list;?>';
	var link_form         = '<?=$link_form;?>';
	var link_form2        = '<?=$link_form2;?>';
	var link_view         = '<?=$link_view;?>';
	var link_ok           = '<?=$link_ok;?>';
	var link_excel        = '<?=$link_excel;?>';
	var link_print        = '<?=$link_print;?>';
	var link_print_detail = '<?=$link_print_detail;?>';

	list_data();
//]]>
</script>