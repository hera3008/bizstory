<?
/*
	생성 : 2013.01.02
	수정 : 2013.01.02
	위치 : 업무폴더 > 나의업무 > 일정
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

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
	$link_list         = $local_dir . "/bizstory/work/schedule_month.php"; // 목록
	$link_view         = $local_dir . "/bizstory/work/schedule_view.php";  // 보기
	$link_form         = $local_dir . "/bizstory/work/schedule_form.php";  // 등록
	$link_ok           = $local_dir . "/bizstory/work/schedule_ok.php";    // 저장
	$link_excel        = $local_dir . "/bizstory/work/schedule_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/work/schedule_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/work/schedule_print_sel.php"; // 상세인쇄

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="open_data_form(\'\')" class="btn_big_green fr"><span>등록</span></a>';
	}
?>
<div class="tablewrapper">
	<div id="tableheader">
		<? include $local_path . "/bizstory/comp_set/part_menu_inc.php"; ?>

		<div class="agentarea" id="sche_menu">
			<p>일정 타입</p>
			<div class="agent_type">
				<a href="javascript:void(0)" id="sche_type_1" onclick="sche_view('<?=$sdate;?>', '1')"<?=$class_str;?>>월간일정</a>
				<span>|</span>
				<a href="javascript:void(0)" id="sche_type_2" onclick="sche_view('<?=$sdate;?>', '2')"<?=$class_str;?>>주간일정</a>
				<span>|</span>
				<a href="javascript:void(0)" id="sche_type_3" onclick="sche_view('<?=$sdate;?>', '3')"<?=$class_str;?>>일정목록</a>
			</div>
		</div>
		<div class="etc_bottom"><?=$btn_write;?></div>
	</div>
	<div id="data_view" title="상세보기 / 등록, 수정폼"></div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<input type="hidden" id="list_sdate"      name="sdate"      value="<?=$sdate;?>" />
		<input type="hidden" id="list_sch_type"   name="sch_type"   value="1" />
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

	function sche_view(today, sche_type)
	{
		if (sche_type == '3') link_list = '<?=$local_dir;?>/bizstory/work/schedule_day.php';
		else if (sche_type == '2') link_list = '<?=$local_dir;?>/bizstory/work/schedule_week.php';
		else link_list = '<?=$local_dir;?>/bizstory/work/schedule_month.php';

		$("#list_sdate").val(today);
		$('#list_sche_type').val(sche_type);
		$('#sche_menu a').removeClass('select');
		$('#sche_type_' + sche_type).addClass('select');

		//list_data();
		view_close();
		close_data_form();
	}

	sche_view('<?=$sdate;?>', '1');
//]]>
</script>


