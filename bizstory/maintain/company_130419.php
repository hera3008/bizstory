<?
/*
	수정 : 2012.11.09
	위치 : 설정폴더(관리자) > 업체관리 > 업체목록
*/
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'comp.reg_date';
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
	$link_list         = $local_dir . "/bizstory/maintain/company_list.php";      // 목록
	$link_form         = $local_dir . "/bizstory/maintain/company_form.php";      // 등록
	$link_ok           = $local_dir . "/bizstory/maintain/company_ok.php";        // 저장
	$link_excel        = $local_dir . "/bizstory/maintain/company_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/maintain/company_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/maintain/company_print_sel.php"; // 상세인쇄

	if ($auth_menu['down'] == "Y") // 다운로드버튼
	{
		$btn_down = '<a href="javascript:void(0);" class="btn_sml" onclick="list_excel()"><span>엑셀</span></a>';
	}
	if ($auth_menu['print'] == "Y") // 인쇄버튼
	{
		$btn_print     = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print()"><span><em class="print"></em>인쇄</span></a>';
		$btn_print_sel = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print_detail()"><span><em class="print"></em>상세인쇄</span></a>';
	}

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';
?>
<div class="tablewrapper">
	<div class="info_frame">
		<span>* 승인은 한번만 가능합니다. 업체 사용가능은 사용유무를 선택하거나 종료일을 설정하면 됩니다.</span>
		<span>* 업체수정을 통해 파일센터, 외부서버에 대한 정보가 설정이 됩니다.</span>
	</div>
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
				<?=$btn_down;?>
				<?=$btn_print;?>
				<?=$btn_print_sel;?>
			</div>
		</form>
	</div>

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
	var link_list         = '<?=$link_list;?>';
	var link_form         = '<?=$link_form;?>';
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

		document.listform.sclass.value = $('#search_sclass').val();
		document.listform.swhere.value = $('#search_swhere').val();
		document.listform.stext.value  = stext;

		view_close();
		list_data();
		return false;
	}

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_msg = check_comp_email(); // 이메일
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		chk_msg = check_tel_num(); // 전화번호
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		chk_msg = check_comp_name(); // 상호명
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		chk_msg = check_boss_name(); // 대표자명
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		chk_msg = check_comp_num(); // 사업자등록번호
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		if (action_num == 0)
		{
			$.ajax({
				type : 'post', dataType: 'json', url: link_ok,
				data : $('#postform').serialize(),
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						popupform_close();
						list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

	list_data();
//]]>
</script>