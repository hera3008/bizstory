<?
/*
	생성 : 2012.06.07
	수정 : 2012.09.10
	위치 : 게시판폴더
*/
	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
	$code_level = $_SESSION[$sess_str . '_ubstory_level'];

	$set_part_yn    = $company_set_data['part_yn'];
	$set_table_name = 'board_biz_' . $company_id;

// 게시판 설정
	$set_where = " and bs.bs_idx = '" . $bs_idx . "'";
	$set_bbs = board_set_data("view", $set_where);
	if ($set_bbs["total_num"] > 0)
	{
	// 관리자일 경우
		$set_bbs["auth_yn"] = "N";
		If ($code_level >= 1 && $code_level <= 11 && $code_mem != "")
		{
			$set_bbs["auth_yn"] = "Y";
		}

	// 게시판설정값
		foreach($set_bbs as $key => $value)
		{
			$key  = "set_" . $key;
			$$key = $value;
		}
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode . '&amp;bs_idx=' . $send_bs_idx;
	$f_search  = $f_default . '&amp;scate=' . $send_scate . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode"  value="' . $send_fmode . '" />
		<input type="hidden" name="smode"  value="' . $send_smode . '" />
		<input type="hidden" name="bs_idx" value="' . $send_bs_idx . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="scate"  value="' . $send_scate . '" />
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list         = $local_dir . "/bizstory/board/board_list.php";      // 목록
	$link_form         = $local_dir . "/bizstory/board/board_form.php";      // 등록
	$link_view         = $local_dir . "/bizstory/board/board_view.php";      // 보기
	$link_ok           = $local_dir . "/bizstory/board/board_ok.php";        // 저장
	$link_excel        = $local_dir . "/bizstory/board/board_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/board/board_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/board/board_print_sel.php"; // 상세인쇄

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';

// 말머리 목록
	$cate_where = " and bc.bs_idx = '" . $bs_idx . "' and bc.view_yn = 'Y'";
	$cate_list = board_category_data('list', $cate_where, '', '', '');

// 문구
	if ($set_remark_top != "" && $set_remark_top != "<br>")
	{
		echo '
<div class="info_text">
	' . $set_remark_top . '
</div>
		';
	}
?>
<div class="tablewrapper">
	<div id="tableheader">
		<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
			<?=$form_default;?>
			<div class="search">
				<p>검&nbsp;&nbsp;&nbsp;색</p>
		<?
			if ($set_category_yn == 'Y')
			{
		?>
				<select id="search_scate" name="scate" title="전체말머리">
					<option value="all">전체말머리</option>
			<?
				foreach ($cate_list as $cate_k => $cate_data)
				{
					if (is_array($cate_data))
					{
						echo '<option value="' . $cate_data['bc_idx'] . '" ' . selected($cate_data['bc_idx'], $scate) . '>' . $cate_data['menu_name'] . '</option>';
					}
				}
			?>
				</select>
		<?
			}
		?>
				<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
					<option value="b.subject"<?=selected($swhere, 'b.subject');?>>제목</option>
					<option value="b.remark"<?=selected($swhere, 'b.remark');?>>내용</option>
					<option value="b.writer"<?=selected($swhere, 'b.writer');?>>작성자</option>
				</select>
				<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
				<a href="javascript:void(0);" class="btn_sml" onclick="check_search()"><span>검색</span></a>
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
		<input type="hidden" id="list_org_idx"    name="org_idx"    value="" />
		<input type="hidden" id="set_table_name"  name="set_table_name" value="<?=$set_table_name;?>" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
<?
	if ($sub_type == '')
	{ }
// 등록, 수정, 답변
	else if ($sub_type == 'postform' || $sub_type == 'modifyform' || $sub_type == 'replyform')
	{
		echo '<div id="work_form_view">';
		include $local_path . "/bizstory/board/board_form.php";
		echo '</div>';
	}
// 복사, 이동
	else if ($sub_type == 'chk_copy' || $sub_type == 'chk_move')
	{
		include $local_path . "/bizstory/board/board_move.php";
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
	function check_search()
	{
		var stext       = $('#search_stext').val();
		var stext_title = $('#search_stext').attr('title');
		if (stext == stext_title) stext = '';

		document.listform.scate.value  = $('#search_scate').val(); // 말머리
		document.listform.swhere.value = $('#search_swhere').val();
		document.listform.stext.value  = stext;

		view_close();
		list_data();
		return false;
	}
<?
	if ($sub_type == '')
	{
		echo 'list_data();';
	}
	if ($wi_idx > 0 && $sub_type == '')
	{
		//echo "view_open('" . $wi_idx . "');";
	}
?>

//]]>
</script>