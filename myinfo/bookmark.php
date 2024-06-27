<?
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
	$link_list = $local_dir . "/bizstory/myinfo/bookmark_list.php"; // 목록
	$link_ok   = $local_dir . "/bizstory/myinfo/bookmark_ok.php";   // 저장
?>
<div class="info_text">
	<ul>
		<li>자주 쓰는 메뉴를 설정합니다.</li>
	</ul>
</div>

<div class="tablewrapper">
	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<input type="hidden" id="list_code_comp"  name="code_comp"  value="<?=$code_comp;?>" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
</div>
<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/bizstory/css/fsidebar.css" media="all" />
<script type="text/javascript">
//<![CDATA[
	var link_list = '<?=$link_list;?>';
	var link_ok   = '<?=$link_ok;?>';

	list_data();
//]]>
</script>