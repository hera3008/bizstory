<?
/*
	생성 : 2013.01.16
	수정 : 2013.01.16
	위치 : 전문가코너 > 거래처검색관리
*/

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ecs.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;scomp=' . $send_scomp;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="scomp"  value="' . $send_scomp . '" />
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
	$link_list  = $local_dir . "/bizstory/expert/client_search_list.php";  // 목록
	$link_form  = $local_dir . "/bizstory/expert/client_search_form.php";  // 등록
	$link_ok    = $local_dir . "/bizstory/expert/client_search_ok.php";    // 저장

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';
?>
<div class="tablewrapper">

	<div id="tableheader">

		<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
			<?=$form_default;?>
			<div class="search">
				<p>검&nbsp;&nbsp;&nbsp;색</p>
				<select id="search_scomp" name="scomp" title="전체업체">
					<option value="">전체업체</option>
			<?
				$comp_where = " and comp.view_yn = 'Y'";
				$comp_order = "comp.comp_name asc";
				$comp_list = company_info_data('list', $comp_where, $comp_order, '', '');
				foreach ($comp_list as $comp_k => $comp_data)
				{
					if (is_array($comp_data))
					{
			?>
					<option value="<?=$comp_data['comp_idx'];?>"<?=selected($comp_data['comp_name'], $scomp);?>><?=$comp_data['comp_name'];?></option>
			<?
					}
				}
			?>
				</select>
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
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>

</div>

<script type="text/javascript">
//<![CDATA[
	var link_list  = '<?=$link_list;?>';
	var link_form  = '<?=$link_form;?>';
	var link_ok    = '<?=$link_ok;?>';

	list_data();

//------------------------------------ 검색
	function check_search()
	{
		document.listform.scomp.value = $('#search_scomp').val(); // 업체

		view_close();
		list_data();
		return false;
	}
//]]>
</script>