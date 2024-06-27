<?
/*
	수정 : 2012.09.04
	위치 : 고객관리 > 접수목록
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	//  업체정보
	$where = " and comp.comp_idx = ".$code_comp;
	$comp_info = company_info_data('view', $where);

	if ($shclass == '')
	{
		$shclass      = 'all';
		$send_shclass = 'all';
		$recv_shclass = 'all';
		$send_shsgroup = 'all';
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ri.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shclass=' . $send_shclass . '&amp;shstatus=' . $send_shstatus . '&amp;shstaff=' . $send_shstaff;
	$f_search  = $f_search . '&amp;shsgroup=' . $send_shsgroup;
	$f_search  = $f_search . '&amp;sdate1=' . $send_sdate1 . '&amp;sdate2=' . $send_sdate2;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"   value="' . $send_swhere . '" />
		<input type="hidden" name="stext"    value="' . $send_stext . '" />
		<input type="hidden" name="shclass"  value="' . $send_shclass . '" />
		<input type="hidden" name="shstatus" value="' . $send_shstatus . '" />
		<input type="hidden" name="shsgroup"  value="' . $send_shsgroup . '" />
		<input type="hidden" name="shstaff"  value="' . $send_shstaff . '" />
		<input type="hidden" name="sdate1"   value="' . $send_sdate1 . '" />
		<input type="hidden" name="sdate2"   value="' . $send_sdate2 . '" />
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
	$link_list         = $local_dir . "/bizstory/receipt/receipt_list.php";      // 목록
	$link_form         = $local_dir . "/bizstory/receipt/receipt_form.php";      // 등록
	$link_view         = $local_dir . "/bizstory/receipt/receipt_view.php";      // 보기
	$link_ok           = $local_dir . "/bizstory/receipt/receipt_ok.php";        // 저장
	$link_excel        = $local_dir . "/bizstory/receipt/receipt_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/receipt/receipt_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/receipt/receipt_print_sel.php"; // 상세인쇄

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';

	$set_receipt_status_css = array('RS01' => 'danger', 'RS02' => 'warning', 'RS03' => 'success', 'RS90' => 'primary', 'RS80' => 'info', 'RS60' => 'gray-600');
?>

					<!-- Content wrapper -->
					<div class="d-flex flex-column flex-column-fluid">
						<!-- Content -->
						<div id="kt_app_content" class="app-content app-content-fit-mobile flex-column-fluid">
							<!-- Content container -->
							<div id="kt_content_container" class="app-container app-container-fit-mobile container-fluid">
								<div class="card card-flush">
									<div class="card-header align-items-center min-h-50px mt-4 mt-lg-5 ls-n2 py-0 px-6 px-lg-8 gap-2 gap-md-4">
										<div class="card-title">
											<h4 class="fs-1">접수<?=!$sub_type? '목록' : ($sub_type == 'postform' || $sub_type == 'modifyform' ? '등록' : '내용')?></h4>
										</div>
										<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
											<ol class="breadcrumb breadcrumb-dot text-muted fs-8 fs-md-7 d-none d-md-inline-flex">
												<li class="breadcrumb-item">홈</li>
												<li class="breadcrumb-item">접수관리</li>
												<li class="breadcrumb-item text-gray-700">접수목록</li>
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
											include $local_path . "/bizstory/receipt/receipt_form.php";
											echo '</div>';
										}
										else if( $sub_type == 'viewform')
										{
											echo '<div id="work_form_view">';
											include $local_path . "/bizstory/receipt/receipt_view.php";
											echo '</div>';
										}
										else
										{
											if($comp_info['comp_class'] == '1')
											{
												echo "<!-- 글목록 -->";
												echo '<div id="data_list">';
												include $local_path."/bizstory/receipt/receipt_list_1.php";
												echo '</div>';
												echo '<!-- // 글목록 -->';
											}
											else
											{
												echo "<!-- 글목록 -->";
												echo '<div id="data_list">';
												include $local_path."/bizstory/receipt/receipt_list_2.php";
												echo '</div>';
												echo '<!-- // 글목록 -->';
											}
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
		<input type="hidden" id="list_list_type"  name="list_type"  value="" />
		<?=$form_page;?>
	</form>

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
	function check_search(list_type)
	{
		var stext       = $('#search_stext').val();
		var stext_title = $('#search_stext').attr('title');
		if (stext == stext_title) stext = '';

		document.listform.swhere.value = $('#search_swhere').val();
		document.listform.stext.value  = stext;

		document.listform.list_type.value = list_type;
		document.listform.shclass.value   = $('#search_shclass').val(); // 분류
		document.listform.shstatus.value  = $('#search_shstatus').val(); // 상태
		document.listform.shsgroup.value  = $('#search_shsgroup').val(); // 직원그룹
		document.listform.shstaff.value   = $('#search_shstaff').val(); // 직원
		document.listform.sdate1.value    = $('#search_sdate1').val();
		document.listform.sdate2.value    = $('#search_sdate2').val();

		view_close();
		list_data();
		return false;
	}

//------------------------------------ 검색 - 미처리건
	function list_search(str)
	{
		var stext       = $('#search_stext').val();
		var stext_title = $('#search_stext').attr('title');
		if (stext == stext_title) stext = '';

		document.listform.list_type.value = str; // 구분
		document.listform.shclass.value   = ''; // 분류
		document.listform.shstatus.value  = ''; // 상태
		document.listform.shsgroup.value  = ''; // 직원그룹
		document.listform.shstaff.value   = ''; // 직원
		document.listform.sdate1.value    = '';
		document.listform.sdate2.value    = '';
		document.listform.swhere.value    = '';
		document.listform.stext.value     = '';

		view_close();
		list_data();
		return false;
	}

//------------------------------------ 거래처검색
	function search_client(str1, str2)
	{
		$('#search_swhere').val(str1);
		$('#search_stext').val(str2);

		var list_type = $('#list_list_type').val();
		check_search(list_type);
	}

<?
	if ($sub_type == '')
	{
		if ($list_type == 'all_no')
		{
			echo 'list_search("all_no");';
		}
		else if ($list_type == 'my_no')
		{
			echo 'list_search("my_no");';
		}
		else
		{	
			//echo 'list_data();';
		}
?>


	//part_information('<?=$code_part;?>', 'receipt_status', 'search_shstatus', '<?=$shstatus;?>', 'select');
	//part_information('<?=$code_part;?>', 'receipt_class', 'search_shclass', '<?=$shclass;?>', 'select');
	//part_information('<?=$code_part;?>', 'staff_group', 'search_shsgroup', '<?=$shsgroup;?>', 'select');
	//part_information('<?=$code_part;?>', 'staff_info', 'search_shstaff', '<?=$shstaff;?>', 'select');
<?
	}
?>
//]]>
</script>