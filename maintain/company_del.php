<?
/*
	생성 : 2013.05.20
	수정 : 2013.05.20
	위치 : 설정폴더(관리자) > 업체관리 > 삭제업체
*/
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'comp.del_date';
	if ($sorder2 == '') $sorder2 = 'desc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;sclass=' . $send_sclass;
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
		<input type="hidden" name="sclass" value="' . $send_sclass . '" />
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
	$link_list = $local_dir . "/bizstory/maintain/company_del_list.php"; // 목록
	$link_view = $local_dir . "/bizstory/maintain/company_del_view.php"; // 보기
	$link_ok   = $local_dir . "/bizstory/maintain/company_del_ok.php";   // 저장

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';
?>
<div class="tablewrapper">
	<div id="tableheader">
		<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
			<?=$form_default;?>
			<div class="search">
				<select name="sclass" id="search_sclass" title="전체분류">
					<option value="all">전체분류</option>
				<?
					$class_where = " and code.view_yn = 'Y'";
					$class_list = company_class_data('list', $class_where, '', '', '');
					foreach ($class_list as $class_k => $class_data)
					{
						if (is_array($class_data))
						{
							$emp_str = str_repeat('&nbsp;', 4 * ($class_data['menu_depth'] - 1));
				?>
					<option value="<?=$class_data['code_idx'];?>" <?=selected($class_data['code_idx'], $sclass);?>><?=$emp_str;?><?=$class_data['code_name'];?></option>
				<?
						}
					}
				?>
				</select>
				<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
					<option value="comp.comp_name"  <?=selected($swhere, 'comp.comp_name');?>>업체명</option>
					<option value="comp.boss_name"  <?=selected($swhere, 'comp.boss_name');?>>대표자명</option>
					<option value="comp.charge_name"<?=selected($swhere, 'comp.charge_name');?>>담당자명</option>
					<option value="comp.tel_num"    <?=selected($swhere, 'comp.tel_num');?>>전화번호</option>
					<option value="comp.address"    <?=selected($swhere, 'comp.address');?>>주소</option>
					<option value="comp.comp_email" <?=selected($swhere, 'comp.comp_email');?>>메일주소</option>
				</select>
				<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
				<a href="javascript:void(0);" onclick="check_search()" class="btn_sml"><span>검색</span></a>
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

<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/script_member.js" charset="utf-8"></script>
<script type="text/javascript">
//<![CDATA[
	var link_list = '<?=$link_list;?>';
	var link_view = '<?=$link_view;?>';
	var link_ok   = '<?=$link_ok;?>';

//------------------------------------ 검색
	function check_search()
	{
		var stext       = $('#search_stext').val();
		var stext_title = $('#search_stext').attr('title');
		if (stext == stext_title) stext = '';

		document.listform.sclass.value = $('#search_sclass').val();
		document.listform.swhere.value = $('#search_swhere').val();
		document.listform.stext.value  = stext;

		view_close();
		list_data();
		return false;
	}

//------------------------------------ 복구
	function check_return(idx)
	{
		if (confirm("선택하신 업체를 복구하시겠습니까?"))
		{
			$('#list_sub_type').val('return_comp');
			$('#list_idx').val(idx);

			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#listform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						check_auth_popup('복구가 완료되었습니다.');
						view_close();
						list_data();
					}
					else
					{
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					$("#loading").fadeOut('slow');
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		return false;
	}

	list_data();
//]]>
</script>