<?
/*
	생성 : 2012.12.27
	수정 : 2012.12.27
	위치 : 설정폴더(총관리자용) > 푸쉬관리 > 푸쉬공지
*/

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list = $local_dir . "/bizstory/maintain/push_notice_list.php"; // 목록
	$link_form = $local_dir . "/bizstory/maintain/push_notice_form.php"; // 등록
	$link_ok   = $local_dir . "/bizstory/maintain/push_notice_ok.php";   // 저장
?>
<div class="tablewrapper">
	<div id="data_view" title="상세보기 / 등록, 수정폼"></div>
	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type" name="sub_type" value="" />
		<input type="hidden" id="list_idx"      name="idx"      value="" />
		<?=$form_page;?>
		<div id="data_list"></div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
	var link_list = '<?=$link_list;?>';
	var link_form = '<?=$link_form;?>';
	var link_ok   = '<?=$link_ok;?>';

	list_data();
//]]>
</script>
