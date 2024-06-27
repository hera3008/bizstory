<?
/*
	생성 : 2012.07.04
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 컨텐츠관리 > 배너관리 > 사이트배너
*/
	$banner_type = '2';
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
	$link_list = $local_dir . "/bizstory/maintain/banner_site_list.php"; // 목록
	$link_form = $local_dir . "/bizstory/maintain/banner_site_form.php"; // 등록
	$link_ok   = $local_dir . "/bizstory/maintain/banner_site_ok.php";   // 저장
?>
<div class="tablewrapper">

	<div id="data_view" title="상세보기 / 등록, 수정폼"></div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"    name="sub_type"    value="" />
		<input type="hidden" id="list_sub_action"  name="sub_action"  value="" />
		<input type="hidden" id="list_idx"         name="idx"         value="" />
		<input type="hidden" id="list_post_value"  name="post_value"  value="" />
		<input type="hidden" id="list_banner_type" name="banner_type" value="<?=$banner_type;?>" />
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