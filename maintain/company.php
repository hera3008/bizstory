<?
/*
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 업체관리 > 업체목록
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
	$link_list         = $local_dir . "/bizstory/maintain/company_list.php";      // 목록
	$link_form         = $local_dir . "/bizstory/maintain/company_form.php";      // 등록
	$link_ok           = $local_dir . "/bizstory/maintain/company_ok.php";        // 저장
	$link_excel        = $local_dir . "/bizstory/maintain/company_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/maintain/company_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/maintain/company_print_sel.php"; // 상세인쇄

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
                                            <h4 class="fs-1"><?=$sub_type == ''?'업체목록':'업체 등록/수정'?></h4>
                                        </div>
                                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                            <ol
                                                class="breadcrumb breadcrumb-dot text-muted fs-8 fs-md-7 d-none d-md-inline-flex">
                                                <li class="breadcrumb-item">홈</li>
                                                <li class="breadcrumb-item">설정관리</li>
                                                <li class="breadcrumb-item">거래처관리</li>
                                                <li class="breadcrumb-item text-gray-700">거래처등록/수정</li>
                                            </ol>
                                        </div>
                                    </div>
									<div class="card-body px-6 px-lg-9 py-2 py-lg-3">

										<!-- 거래처등록/수정 -->
										<?php
											// 카테고리
											//include_once("../common/tabs.php");
										?>
										<?
										if ($sub_type == 'postform' || $sub_type == 'modifyform')
										{
											echo '<div id="work_form_view">';
											include $local_path . "/bizstory/maintain/company_form.php";
											echo '</div>';
										}
										else
										{
											echo "<!-- 글목록 -->";
											echo '<div id="data_list">';
											include $local_path."/bizstory/maintain/company_list.php";
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
		<?=$form_page;?>
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

		var comp_class = $('#post_comp_class').val();
		var comp_category = $('#comp_category').val();

		//chk_msg = check_comp_class('On'); // 분류        
        chk_msg = check_input_value($.trim(comp_category));        
		if (chk_msg == 'No')
		{
			action_num++;
            check_auth_popup($('#comp_category').attr('title'));
			return false;
		}
        
		//교육기관
		if(comp_class == '1')
		{	
			//시도교육청
			chk_msg = check_input_value($.trim($('#post_sc_name').val()));
			if( chk_msg == 'No')
			{
				action_num++;
				check_auth_popup($('#post_sc_name').attr('title'));
				return false;
			}

			chk_msg = check_input_value($.trim($('#post_sc_code').val()));
			if( chk_msg == 'No')
			{
				action_num++;
				check_auth_popup($('#post_sc_name').attr('title'));
				return false;
			}
			
			//교육지원청
			if(comp_category != '3')
			{
				chk_msg = check_input_value($.trim($('#post_org_name').val()));
				if( chk_msg == 'No')
				{
					action_num++;
					check_auth_popup($('#post_org_name').attr('title'));
					return false;
				}

				chk_msg = check_input_value($.trim($('#post_org_code').val()));
				if( chk_msg == 'No')
				{
					action_num++;
					check_auth_popup($('#post_org_code').attr('title'));
					return false;
				}
			}

			//교육지원청
			if(comp_category != '3' && comp_category != '4')
			{
				chk_msg = check_input_value($.trim($('#post_schul_name').val()));
				if( chk_msg == 'No')
				{
					action_num++;
					check_auth_popup($('#post_schul_name').attr('title'));
					return false;
				}

				chk_msg = check_input_value($.trim($('#post_schul_code').val()));
				if( chk_msg == 'No')
				{
					action_num++;
					check_auth_popup($('#post_schul_code').attr('title'));
					return false;
				}
			}


		}
		else
		{
			chk_msg = check_comp_name('On'); // 상호명
			if (chk_msg == 'No')
			{
				action_num++;
				return false;
			}

			chk_msg = check_boss_name('On'); // 대표자명
			if (chk_msg == 'No')
			{
				action_num++;
				return false;
			}

			chk_msg = check_comp_num('On'); // 사업자등록번호
			if (chk_msg == 'No')
			{
				action_num++;
				return false;
			}
			
			chk_val = $.trim($('#post_comp_class_sub').val());
			chk_msg = check_input_value(chk_val); //업체분류
			if( chk_msg == 'No')
			{
				action_num++;
				chk_msg = $('#post_comp_class_sub').attr('title');
				check_auth_popup(chk_msg);
				return false;
			}
		}
		
		chk_msg = check_comp_email('On'); // 이메일
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		chk_msg = check_tel_num('On'); // 전화번호
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}
		
		if($('#post_auth_yn').val() == 'Y'){
			chk_msg = check_input_value($.trim($('#post_menu_code').val())); //메뉴 설정
			if (chk_msg == 'No')
			{
				action_num++;
				check_auth_popup($('#post_menu_code').attr('title'));
				return false;
			}

			chk_msg = check_input_value($.trim($('#post_clent_group_code').val())); //거래처분류설정
			if (chk_msg == 'No')
			{
				action_num++;
				check_auth_popup($('#post_clent_group_code').attr('title'));
				return false;
			}
		}
		else
		{
			$('##post_menu_code').val('');
			$('#post_clent_group_code').val('');
		}

		if (action_num == 0)
		{
			$.ajax({
				type : 'post', dataType: 'json', url: link_ok,
				data : $('#postform').serialize(),
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
                        /*
						popupform_close();
						list_data();
						if (msg.file_out == 'Y')
						{
							filecenter_company_folder(msg.comp_idx);
						}
                        */
                       document.location.reload();
					}
					else
					{
						//$("#loading").fadeOut('slow');
						//$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					//$("#loading").fadeOut('slow');
					//$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

	//list_data();
//]]>
</script>
<? include $local_path . "/bizstory/js/filecenter_js.php"; ?>