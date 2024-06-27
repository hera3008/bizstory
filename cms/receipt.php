<?
	include "../bizstory/common/setting.php";
	include $local_path . "/cms/include/client_chk.php";
	include $local_path . "/cms/include/top.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ri.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = '';
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shclass=' . $send_shclass . '&amp;shstatus=' . $send_shstatus;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"    value="' . $send_swhere . '" />
		<input type="hidden" name="stext"     value="' . $send_stext . '" />
		<input type="hidden" name="shclass"   value="' . $send_shclass . '" />
		<input type="hidden" name="shstatus"  value="' . $send_shstatus . '" />
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
	$link_list         = $local_dir . "/cms/receipt_list.php";      // 목록
	$link_form         = $local_dir . "/cms/receipt_form.php";      // 등록
	$link_view         = $local_dir . "/cms/receipt_view.php";      // 보기
	$link_ok           = $local_dir . "/cms/receipt_ok.php";        // 저장
	$link_excel        = $local_dir . "/cms/receipt_excel.php";     // 액셀
	$link_print        = $local_dir . "/cms/receipt_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/cms/receipt_print_sel.php"; // 상세인쇄

	//$btn_down      = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_excel()"><span>엑셀</span></a>';
	//$btn_print     = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_print()"><span><em class="print"></em>인쇄</span></a>';
	//$btn_print_sel = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_print_detail()"><span><em class="print"></em>상세인쇄</span></a>';

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';
?>
<div class="home_pagenavi">
	<h2>접수 <small><?=$client_data['client_name'];?></small></h2>
	<ul>
		<li><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/cms/receipt.php'">접수</a></li>
	</ul>
</div>
<hr />

<div class="tablewrapper">
	<div id="tableheader">
		<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
			<?=$form_default;?>
			<div class="search">
				<div class="search_area">
					<select id="search_shstatus" name="shstatus" title="전체상태">

						<option value="all">전체상태</option>
					</select>
					<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
						<option value="ri.subject"<?=selected($swhere, 'ri.subject');?>>제목</option>
						<option value="ri.remark"<?=selected($swhere, 'ri.remark');?>>내용</option>
					</select>
					<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
					<a href="javascript:void(0);" class="btn_sml" onclick="check_search()"><span>검색</span></a>
					<a href="javascript:void(0);" class="btn_sml btn_b1" onclick="list_no_search()"><span>미처리건</span></a>
					<?=$btn_down;?>
					<?=$btn_print;?>
					<?=$btn_print_sel;?>
				</div>
			</div>
		</form>
	</div>

	<div id="data_view" title="상세보기 / 등록, 수정폼"></div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_org_idx"    name="org_idx"    value="" />

		<input type="hidden" id="list_comp_idx"    name="comp_idx"    value="<?=$client_comp;?>" />
		<input type="hidden" id="list_part_idx"    name="part_idx"    value="<?=$client_part;?>" />
		<input type="hidden" id="list_ci_idx"      name="ci_idx"      value="<?=$client_idx;?>" />
		<input type="hidden" id="list_client_code" name="client_code" value="<?=$client_code;?>" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
<?
	if ($sub_type == '')
	{
	}
	else if ($sub_type == 'postform' || $sub_type == 'modifyform')
	{
		echo '<div id="work_form_view">';
		include $local_path . "/cms/receipt_form.php";
		echo '</div>';
	}
?>
</div>

<div class="ui-widget" id="popup_notice_view" style="display:none">
	<div class="ui-state-highlight ui-corner-all">
		<p><span class="ui-icon ui-icon-info"></span>
		<span id="popup_notice_memo">
			<strong>주의</strong> 주의사항 입력
		</span>
		</p>
	</div>
</div>

<div id="popup_result_msg" title="처리결과"></div>

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

		document.listform.swhere.value   = $('#search_swhere').val();
		document.listform.stext.value    = stext;
		document.listform.shstatus.value = $('#search_shstatus').val(); // 상태

		view_close();
		list_data();
		return false;
	}

//------------------------------------ 검색 - 미처리건
	function list_no_search()
	{
		var stext       = $('#search_stext').val();
		var stext_title = $('#search_stext').attr('title');
		if (stext == stext_title) stext = '';

		document.listform.swhere.value   = $('#search_swhere').val();
		document.listform.stext.value    = stext;
		document.listform.shstatus.value = 'no_list'; // 상태

		view_close();
		list_data();
		return false;
	}
<?
	if ($sub_type == '')
	{
		echo 'list_data();';
	}
	if ($ri_idx > 0 && $sub_type == '')
	{
		echo "view_open('" . $ri_idx . "');";
	}
?>
	part_information('<?=$client_part;?>', 'receipt_status', 'search_shstatus', '<?=$shstatus;?>', 'select');

	$("#popup_result_msg").dialog({
		autoOpen: false, width: 350, modal: true,
		buttons: {
			"확인": function() {$(this).dialog("close");}
		}
	});
//]]>
</script>

<?
	include $local_path . "/cms/include/tail.php";
?>