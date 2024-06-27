<?
/*
	생성 : 2012.10.12
	수정 : 2012.10.12
	위치 : 상담게시판 - 목록
*/
	include "../bizstory/common/setting.php";
	include $local_path . "/agent/include/agent_chk.php";
	include $local_path . "/agent/include/header.php";
?>
<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/agent/css/agent2.css" media="all" />
<script type="text/javascript" src="<?=$local_dir;?>/agent/js/jquery.ready.js" charset="utf-8"></script>
<title>BizStory Agent - <?=$client_name;?></title>
<script>
//<![CDATA[
	if (window.navigator.userAgent.match(/MSIE|Internet Explorer|Trident/i)) {
		
		window.location = "microsoft-edge:" + window.location.href + "?client_code=<?=$client_code;?>";
		
		setTimeout(function(){
			//window.location = "https://go.microsoft.com/fwlink/?linkid=2135547";
			top.window.open('about:blank','_self').close();
			top.window.opener=self;
			top.self.close();
		}, 1);
	}
//]]>
</script>
</head>

<body id="agent" class="consult">

<div id="loading">로딩중입니다...</div>
<div id="loading2">문서 미리보기 로딩중입니다...</div>
<div id="popup_file_preview" title="파일 미리보기"></div>
<div id="preview_file_result" title="파일변환결과"></div>

<div id="agent_form_area">
	<div id="agent_form">
		<div class="popupform" title="팝업등록폼">
			<div id="data_form" title="등록폼"></div>
		</div>

		<? include $local_path . "/agent/include/top.php"; ?>
<?
	if ($error_string != '')
	{
?>
	<div class="error_view">
		<?=$error_string;?>
	</div>
<?
	}
	else
	{
		if ($consult_idx != '') $cons_idx = $consult_idx;

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 변수
		$f_default = '';
		$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
		$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
		$f_all     = $f_page . '&amp;page_num=' . $page_num;
		$f_script  = str_replace('&amp;', '&', $f_all);
		$field_str = str_replace('&amp;', '|', $f_all);

		$form_default  = '
			<input type="hidden" name="comp_idx"    value="' . $client_comp . '" />
			<input type="hidden" name="part_idx"    value="' . $client_part . '" />
			<input type="hidden" name="client_idx"  value="' . $client_idx . '" />
			<input type="hidden" name="client_code" value="' . $client_code . '" />
		';
		$form_search = $form_default . '
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
		$link_list         = $local_dir . "/agent/consult_list.php";      // 목록
		$link_form         = $local_dir . "/agent/consult_form.php";      // 등록
		$link_view         = $local_dir . "/agent/consult_view.php";      // 보기
		$link_ok           = $local_dir . "/agent/consult_ok.php";        // 저장
		$link_excel        = $local_dir . "/agent/consult_excel.php";     // 액셀
		$link_print        = $local_dir . "/agent/consult_print.php";     // 인쇄
		$link_print_detail = $local_dir . "/agent/consult_print_sel.php"; // 상세인쇄

		//$btn_down      = '<a href="javascript:void(0);" class="btn_sml" onclick="list_excel()"><span>엑셀</span></a>';
		//$btn_print     = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print()"><span><em class="print"></em>인쇄</span></a>';
		//$btn_print_sel = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print_detail()"><span><em class="print"></em>상세인쇄</span></a>';

		$search_column  = '칼럼 선택';
		$search_keyword = '검색할 단어 입력';
?>
		<div class="tablewrapper">
			<div id="tableheader">
				<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
					<?=$form_default;?>
					<div class="search">
						<p>검&nbsp;&nbsp;&nbsp;색</p>
						<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
							<option value="ri.subject"<?=selected($swhere, 'ri.subject');?>>제목</option>
							<option value="ri.remark"<?=selected($swhere, 'ri.remark');?>>내용</option>
						</select>
						<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
						<a href="javascript:void(0);" class="btn_sml" onclick="check_search()"><span>검색</span></a>
					</div>
				</form>
			</div>
			<div id="data_view" title="상세보기 / 등록, 수정폼"></div>

			<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
				<input type="hidden" id="list_idx"        name="idx"        value="" />
				<input type="hidden" id="list_org_idx"    name="org_idx"    value="" />
				<input type="hidden" id="list_macaddress" name="macaddress" value="<?=$macaddress;?>" />
				<?=$form_page;?>

				<div id="data_list"></div>
			</form>
	<?
		if ($sub_type == '')
		{ }
		else if ($sub_type == 'postform' || $sub_type == 'modifyform')
		{
			echo '<div id="work_form_view">';
			include $local_path . "/agent/consult_form.php";
			echo '</div>';
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
	if ($cons_idx > 0 && $sub_type == '')
	{
		echo "view_open('" . $cons_idx . "');";
	}
?>
//]]>
</script>

<?
	}
	include $local_path . "/agent/include/tail.php";
?>