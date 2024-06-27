<?
/*
	수정 : 2012.11.27
	위치 : 회계업무 > 합계잔액시산표
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
	$f_page    = $f_search;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="asyear"   value="' . $send_asyear . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list  = $local_dir . "/bizstory/account/report_01_list.php";  // 목록
	$link_excel = $local_dir . "/bizstory/account/report_01_excel.php"; // 액셀
	$link_print = $local_dir . "/bizstory/account/report_01_print.php"; // 인쇄

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

	list_data();

//------------------------------------ 검색
	function check_search()
	{
		document.listform.asyear.value = $('#search_asyear').val(); // 년도

		view_close();
		list_data();
		return false;
	}
//]]>
</script>