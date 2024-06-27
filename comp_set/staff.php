<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 직원관리 > 직원등록/수정
*/
	$code_comp     = $_SESSION[$sess_str . '_comp_idx'];
	//$code_part     = search_company_part($code_part);
	$code_part     = "";
	$code_mem      = $_SESSION[$sess_str . '_mem_idx'];
	$set_staff_num = $comp_set_data['staff_cnt'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'mem.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shgroup=' . $send_shgroup;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
		<input type="hidden" name="shgroup" value="' . $send_shgroup . '" />
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
	$link_list         = $local_dir . "/bizstory/comp_set/staff_list.php";      // 목록
	$link_form         = $local_dir . "/bizstory/comp_set/staff_form.php";      // 등록
	$link_ok           = $local_dir . "/bizstory/comp_set/staff_ok.php";        // 저장
	$link_excel        = $local_dir . "/bizstory/comp_set/staff_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/comp_set/staff_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/comp_set/staff_print_sel.php"; // 상세인쇄

	if ($auth_menu['down'] == "Y") // 다운로드버튼
	{
		//$btn_down = '<a href="javascript:void(0);" class="btn_sml" onclick="list_excel()"><span>엑셀</span></a>';
	}
	if ($auth_menu['print'] == "Y") // 인쇄버튼
	{
		//$btn_print     = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print()"><span><em class="print"></em>인쇄</span></a>';
		//$btn_print_sel = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print_detail()"><span><em class="print"></em>상세인쇄</span></a>';
	}

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';

	$page_where = " and mem.comp_idx = '" . $code_comp . "'";
	$page_data = member_info_data('page', $page_where);
?>
                    <!-- Content wrapper -->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!-- Content -->
                        <div id="kt_app_content" class="app-content app-content-fit-mobile flex-column-fluid">
                            <!-- Content container -->
                            <div id="kt_content_container"
                                class="app-container app-container-fit-mobile container-fluid">
                                <div class="card card-flush">
                                    <div
                                        class="card-header align-items-center min-h-50px mt-4 mt-lg-5 ls-n2 py-0 px-6 px-lg-8 gap-2 gap-md-4">
                                        <div class="card-title">
                                            <h4 class="fs-1"><?=$sub_type == ''?'직원목록':'직원 등록/수정'?></h4>
                                        </div>
                                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                            <ol
                                                class="breadcrumb breadcrumb-dot text-muted fs-8 fs-md-7 d-none d-md-inline-flex">
                                                <li class="breadcrumb-item">홈</li>
                                                <li class="breadcrumb-item">설정관리</li>
                                                <li class="breadcrumb-item">직원관리</li>
                                                <li class="breadcrumb-item text-gray-700">직원등록/수정</li>
                                            </ol>
                                        </div>
                                    </div>
									<div class="card-body px-6 px-lg-9 py-2 py-lg-3">
										<!-- 글목록 -->
										<?php
											// 카테고리
											echo part_cate_tab($code_comp);
										?>
										<?
										if ($sub_type == 'postform' || $sub_type == 'modifyform')
										{
											echo '<div id="work_form_view">';
											include $local_path . "/bizstory/comp_set/staff_form.php";
											echo '</div>';
										}
										else
										{
											echo "<!-- 글목록 -->";
											echo '<div id="data_list">';
											include $local_path."/bizstory/comp_set/staff_list.php";
											echo '</div>';
											echo '<!-- // 글목록 -->';
										}
										?>
									</div>
                                </div>
                            </div>
                            <!--// Content container -->
                        </div>
                        <!--// Content -->
                    </div>
                    <!--// Content wrapper -->

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<input type="hidden" id="list_org_idx"    name="org_idx"    value="" />
		<?=$form_page;?>
	</form>

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

		document.listform.swhere.value = $('#search_swhere').val();
		document.listform.stext.value  = stext;

		document.listform.shgroup.value = $('#search_shgroup').val(); // 부서

		
		return false;
	}

//------------------------------------ 퇴사처리하기
	function check_staff_out(idx)
	{
		if (confirm("선택하신 직원을 퇴사하시겠습니까?"))
		{
			check_code_data('delete', '', idx, '');
		}
	}

	//part_information('<?=$code_part;?>', 'staff_group', 'search_shgroup', '<?=$shgroup;?>', 'select');
	//list_data();
//]]>
</script>
<? include $local_path . "/bizstory/js/filecenter_js.php"; ?>