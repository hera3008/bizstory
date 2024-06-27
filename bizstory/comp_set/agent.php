<?
/*
	수정 : 2012.10.31
	위치 : 설정관리 > 에이전트관리 > 타입관리
*/
	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_agent = search_agent_type($code_part, $code_agent);

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
	$link_list  = $local_dir . "/bizstory/comp_set/agent_list.php"; // 목록
	$link_form  = $local_dir . "/bizstory/comp_set/agent_form.php"; // 등록
	$link_ok    = $local_dir . "/bizstory/comp_set/agent_ok.php";   // 저장
	$link_agent = $local_dir . "/bizstory/comp_set/agent_type.php"; // 에이전트
?>
<div class="tablewrapper">
	<div id="tableheader">
		<? include $local_path . "/bizstory/comp_set/part_menu_inc.php"; ?>

		<div class="agentarea" id="agent_menu"></div>
	</div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<input type="hidden" id="list_code_agent" name="code_agent" value="<?=$code_agent;?>" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
	var link_list  = '<?=$link_list;?>';
	var link_form  = '<?=$link_form;?>';
	var link_ok    = '<?=$link_ok;?>';
	var link_agent = '<?=$link_agent;?>';

	agent_type('<?=$code_part;?>', '<?=$code_agent;?>');
//]]>
</script>