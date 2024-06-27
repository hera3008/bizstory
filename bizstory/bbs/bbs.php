<?
/*
	생성 : 2012.06.07
	수정 : 2012.12.19
	위치 : 게시판
*/
	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
	$code_level = $_SESSION[$sess_str . '_ubstory_level'];

// 게시판 설정
	$set_where = " and bs.bs_idx = '" . $bs_idx . "'";
	$set_bbs = bbs_setting_data("view", $set_where);
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
// 정렬
	if ($sorder1 == '') $sorder1 = 'b.order_idx';
	if ($sorder2 == '') $sorder2 = 'desc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode . '&amp;bs_idx=' . $send_bs_idx;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;scate=' . $send_scate;
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
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
		<input type="hidden" name="scate"  value="' . $send_scate . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list = $local_dir . "/bizstory/bbs/bbs_list.php"; // 목록
	$link_form = $local_dir . "/bizstory/bbs/bbs_form.php"; // 등록
	$link_view = $local_dir . "/bizstory/bbs/bbs_view.php"; // 보기
	$link_ok   = $local_dir . "/bizstory/bbs/bbs_ok.php";   // 저장

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';

// 말머리 목록
	$cate_where = " and bc.bs_idx = '" . $bs_idx . "' and bc.view_yn = 'Y'";
	$cate_list = bbs_category_data('list', $cate_where, '', '', '');

// 문구
	if ($set_remark_top != "" && $set_remark_top != "<br>")
	{
		echo '
<div class="info_text">
	<ul>
		<li>' . $set_remark_top . '</li>
	</ul>
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
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
	var link_list = '<?=$link_list;?>';
	var link_form = '<?=$link_form;?>';
	var link_view = '<?=$link_view;?>';
	var link_ok   = '<?=$link_ok;?>';

//------------------------------------ 검색
	function check_search()
	{
		var stext       = $('#search_stext').val();
		var stext_title = $('#search_stext').attr('title');
		if (stext == stext_title) stext = '';

		document.listform.swhere.value = $('#search_swhere').val();
		document.listform.stext.value  = stext;
		document.listform.scate.value  = $('#search_scate').val(); // 말머리

		view_close();
		list_data();
		return false;
	}
	list_data();
//]]>
</script>