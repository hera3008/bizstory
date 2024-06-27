<?
/*
	생성 : 2013.08.20
	위치 : 버튼css 작업중 ㅋㅋㅋㅋ 추후 삭제
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'wi.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;swtype=' . $send_swtype . '&amp;shwstatus=' . $send_shwstatus . '&amp;smember=' . $send_smember;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"    value="' . $send_swhere . '" />
		<input type="hidden" name="stext"     value="' . $send_stext . '" />
		<input type="hidden" name="swtype"    value="' . $send_swtype . '" />
		<input type="hidden" name="shwstatus" value="' . $send_shwstatus . '" />
		<input type="hidden" name="smember"   value="' . $send_smember . '" />
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
	$link_list         = $local_dir . "/bizstory/work/work_list.php";      // 목록
	$link_form         = $local_dir . "/bizstory/work/work_form.php";      // 등록
	$link_view         = $local_dir . "/bizstory/work/work_view.php";      // 보기
	$link_ok           = $local_dir . "/bizstory/work/work_ok.php";        // 저장
	$link_excel        = $local_dir . "/bizstory/work/work_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/work/work_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/work/work_print_sel.php"; // 상세인쇄
	$link_print_view   = $local_dir . "/bizstory/work/work_view_print.php"; // 보기인쇄

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
				<select id="search_swtype" name="swtype" title="전체종류">
					<option value="all">전체종류</option>
				<?
					foreach ($set_work_type as $set_k => $set_v)
					{
				?>
					<option value="<?=$set_k;?>"<?=selected($swtype, $set_k);?>><?=$set_v;?></option>
				<?
					}
				?>
				</select>
				<select id="search_shwstatus" name="shwstatus" title="전체상태">
					<option value="all">전체상태</option>
				</select>
				<select id="search_smember" name="smember" title="담당자선택">
					<option value="">담당자선택</option>
				</select>
				<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
					<option value="wi.subject"<?=selected($swhere, 'wi.subject');?>>업무제목</option>
					<option value="wi.remark"<?=selected($swhere, 'wi.remark');?>>내용</option>
				</select>
				<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
				<a href="javascript:void(0);" class="btn_sml" onclick="check_search()"><span>검색</span></a>
			</div>
		</form>
	</div>

	<span class="btn_big_green"><input type="submit" value="등록" /></span>
	<span class="btn_big_gray"><input type="button" value="취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

	
</div>

<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/script_work.js" charset="utf-8"></script>
<script type="text/javascript">
//<![CDATA[
	var link_list         = '<?=$link_list;?>';
	var link_form         = '<?=$link_form;?>';
	var link_view         = '<?=$link_view;?>';
	var link_ok           = '<?=$link_ok;?>';
	var link_excel        = '<?=$link_excel;?>';
	var link_print        = '<?=$link_print;?>';
	var link_print_detail = '<?=$link_print_detail;?>';
	var link_print_view   = '<?=$link_print_view;?>';

//------------------------------------ 검색
	function check_search()
	{
		var stext       = $('#search_stext').val();
		var stext_title = $('#search_stext').attr('title');
		if (stext == stext_title) stext = '';

		document.listform.swhere.value = $('#search_swhere').val();
		document.listform.stext.value  = stext;

		document.listform.swtype.value    = $('#search_swtype').val(); // 분류
		document.listform.shwstatus.value = $('#search_shwstatus').val(); // 상태
		document.listform.smember.value   = $('#search_smember').val(); // 직원

		view_close();
		list_data();
		return false;
	}

//------------------------------------ 전체업무
	function work_list_all(link_chk)
	{
		document.listform.swhere.value    = ''; // 칼럼
		document.listform.stext.value     = ''; // 검색어
		document.listform.swtype.value    = ''; // 종류
		document.listform.shwstatus.value = ''; // 상태

		if (link_chk == 'all')
		{
			document.listform.smember.value = 'all'; // 직원
		}
		else
		{
			document.listform.smember.value = ''; // 직원
		}

		view_close();
		list_data();
		return false;
	}

<?
	if ($sub_type == '')
	{
?>
	list_data();
	part_information('<?=$code_part;?>', 'work_status', 'search_shwstatus', '<?=$shwstatus;?>', 'select');
	part_information('<?=$code_part;?>', 'staff_info', 'search_smember', '<?=$smember;?>', 'select_allno');
<?
	}
	if ($wi_idx > 0 && $sub_type == '')
	{
		echo "view_open('" . $wi_idx . "');";
	}
?>
//]]>
</script>