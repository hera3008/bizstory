<?
/*
	생성 : 2012.05.16
	위치 : 게시판
*/
	include "../../bizstory/common/setting.php";
	include $local_path . "/cms/include/client_chk.php";
	include $local_path . "/cms/include/top.php";

	if ($bs_idx == "" || $bs_idx == "0") // 값이 없을 경우
	{
		// 잘못된 접속입니다.
		include $local_path . "/bizstory/include/error_view.php";
		exit;
	}

// 게시판 설정
	$set_where = " and bs.bs_idx = '" . $bs_idx . "' and bs.view_yn = 'Y'";
	$set_board = pro_board_set_data("view", $set_where);
	if ($set_board['total_num'] == 0)
	{
		// 설정된 프로젝트게시판이 없습니다.
		include $local_path . "/bizstory/include/error_view.php";
		exit;
	}
	else
	{
		if ($set_board['ci_idx'] != $_SESSION[$sess_str . '_client_idx'] || $_SESSION[$sess_str . '_client_idx'] == '')
		{
			// 해당거래처 프로젝트가 아닙니다.
			include $local_path . "/bizstory/include/error_view.php";
			exit;
		}
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'b.order_idx';
	if ($sorder2 == '') $sorder2 = 'desc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'bs_idx=' . $send_bs_idx;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;scate=' . $send_scate;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="bs_idx" value="' . $send_bs_idx . '" />';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"    value="' . $send_swhere . '" />
		<input type="hidden" name="stext"     value="' . $send_stext . '" />
		<input type="hidden" name="scate"  value="' . $send_scate . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list = $local_dir . "/cms/board_project/board_list.php"; // 목록
	$link_form = $local_dir . "/cms/board_project/board_form.php"; // 등록
	$link_view = $local_dir . "/cms/board_project/board_view.php"; // 보기
	$link_ok   = $local_dir . "/cms/board_project/board_ok.php";   // 저장

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';
?>

<div class="home_pagenavi">
	<h2>프로젝트게시판 <small><?=$client_data['client_name'];?></small></h2>
	<ul>
		<li><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/cms/board_project/board.php?bs_idx=<?=$bs_idx;?>'">프로젝트게시판</a></li>
	</ul>
</div>
<hr />
<?
	if ($set_board['remark_top'] != "") // 상단문구
	{
		echo '<div class="remark_top">' . $set_board['remark_top'] . '</div>';
	}
?>

<div class="tablewrapper">
<?
	if ($sub_type == '')
	{
?>
	<div id="tableheader">
		<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
			<?=$form_default;?>
			<div class="search">
				<div class="search_area">
					<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
						<option value="b.subject"<?=selected($swhere, 'b.subject');?>>제목</option>
						<option value="b.remark"<?=selected($swhere, 'b.remark');?>>내용</option>
						<option value="b.writer"<?=selected($swhere, 'b.writer');?>>작성자</option>
					</select>
					<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
					<a href="javascript:void(0);" class="btn_sml fl" onclick="check_search()"><span>검색</span></a>
				</div>
			</div>
		</form>
	</div>

	<div id="data_view" title="상세보기 / 등록, 수정폼"></div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type" name="sub_type" value="" />
		<input type="hidden" id="list_bs_idx"   name="bs_idx"   value="<?=$bs_idx;?>" />
		<input type="hidden" id="list_idx"      name="idx"      value="" />

		<input type="hidden" id="list_comp_idx"    name="comp_idx"    value="<?=$client_comp;?>" />
		<input type="hidden" id="list_part_idx"    name="part_idx"    value="<?=$client_part;?>" />
		<input type="hidden" id="list_ci_idx"      name="ci_idx"      value="<?=$client_idx;?>" />
		<input type="hidden" id="list_client_code" name="client_code" value="<?=$client_code;?>" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>

	<div class="boardnavigation">
		<div class="buttonleft">
			<span class="button"><a href="<?=$this_page;?>?<?=$field_default;?>">목록보기</a></span>
		</div>
		<div class="buttonright">
			<?=$set_btn_write;?>
		</div>
	</div>
<?
	}
	else if ($sub_type == 'postform' || $sub_type == 'modifyform')
	{
		include $local_path . "/cms/board_project/board_form.php";
	}
?>
</div>

<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/script_file.js" charset="utf-8"></script>
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

		view_close();
		list_data();
		return false;
	}

//------------------------------------ 등록, 수정
	function project_form(idx)
	{
		$("#popup_notice_view").hide();
		$('#list_idx').val(idx);
		if (idx == '') $('#list_sub_type').val('postform');
		else $('#list_sub_type').val('modifyform');
		location.href = local_dir + '/cms/board_project/board.php?' + $('#listform').serialize();
	}

	list_data();
//]]>
</script>

<?
	include $local_path . "/cms/include/tail.php";
?>