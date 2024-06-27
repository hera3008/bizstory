<?
/*	
	위치 : 설정관리 > 에이전트관리 > 배너관리
	생성 : 2012.07.03
	수정 : 2012.10.31
		  2024.04.02 김소령 care con 명칭 변경 및 디자인 변경
	
*/
	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	//$code_part  = search_company_part($code_part);
	$code_part  = "";
	
	$where = " and comp.comp_idx = '" . $code_comp . "'";
	$comp_info = company_info_data("view", $where);
	$carecon_type = $comp_info['carecon_type'];

    
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
        $link_list         =  "/bizstory/carecon/carecon_banner_list.php";      // 목록
        $link_form         =  "/bizstory/carecon/carecon_banner_form.php";      // 등록
        $link_ok           =  "/bizstory/carecon/carecon_banner_ok.php";        // 저장
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
                                            <h4 class="fs-1">Care Con 배너관리</h4>
                                        </div>
                                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                            <ol
                                                class="breadcrumb breadcrumb-dot text-muted fs-8 fs-md-7 d-none d-md-inline-flex">
                                                <li class="breadcrumb-item">홈</li>
                                                <li class="breadcrumb-item">설정관리</li>
												<li class="breadcrumb-item">Care Con 관리</li>
                                                <li class="breadcrumb-item text-gray-700">배너관리</li>
                                            </ol>
                                        </div>
                                    </div>
                                    <div class="card-body px-6 px-lg-9 py-2 py-lg-3">
										
										 <?php
											// 카테고리
											echo part_cate_tab($code_comp);
										?>

                                        <!-- 글목록 -->
                                        <div id="data_list">
                                            <? include_once $local_path.$link_list; ?>
                                        </div>
										<!-- //글목록 -->

										<!-- 글쓰기-->
										<div class="modal fade" tabindex="-1" id="kt_modal_position">
                                            <div class="modal-dialog modal-dialog-centered">
											</div>											
                                        </div>
                                        <!-- //글쓰기 -->

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
        <input type="hidden" id="list_carecon_type"  name="list_carecon_type"  value="<?=$list_carecon_type;?>" />
        <?=$form_page;?>
    </form>

    <script src="../assets/plugins/custom/dropzone/dropzone.js"></script>
    <script src="/assets/plugins/custom/rowSorter/rowSorter.js"></script>
    <script type="text/javascript">
//<![CDATA[
	var link_list         = '<?=$local_dir.$link_list;?>';
	var link_form         = '<?=$local_dir.$link_form;?>';
	var link_ok           = '<?=$local_dir.$link_ok;?>';

    document.addEventListener('DOMContentLoaded', function() {
        function getElementById(id) {
            return document.getElementById(id);
        }

        function onDropHandler(tbody, row, new_index, old_index) {
            var table = tbody.nodeName === 'TBODY' ? tbody.parentNode : tbody;
            table.querySelectorAll('tfoot td')[0].innerHTML = (old_index + 1) + '행이 ' + (new_index + 1) +
                '행으로 이동되었습니다.';
        }

        RowSorter(getElementById('kt_table'), {
            handler: 'td.sorter',
            onDrop: onDropHandler
        });
    });

//------------------------------------ 등록
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#post_content').val();
		chk_title = $('#post_content').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					console.log(msg);
					if (msg.success_chk == "Y")
					{
						popupform_close();
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
		else check_auth_popup(chk_total);

		return false;
	}

//]]>
</script>

