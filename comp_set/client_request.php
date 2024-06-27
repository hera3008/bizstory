<?
/*
	위치 : 총관리자 > 자동등록설정 > 거래처분류
	생성 : 2013.03.25
	수정 : 2024.03.18 김소령
	내용 : 교육기관 등록시 거래처 분류 자동 생성을 위한 기초 값 설정 

*/

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	
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
	$link_list         = $local_dir . "/bizstory/comp_set/client_request_list.php";
	$link_form 			= $local_dir . "/bizstory/comp_set/client_request_form.php"; // 등록
	$link_ok           = $local_dir . "/bizstory/comp_set/client_request_ok.php";        // 저장
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
                                            <h4 class="fs-1">승인요청</h4>
                                        </div>
                                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                            <ol
                                                class="breadcrumb breadcrumb-dot text-muted fs-8 fs-md-7 d-none d-md-inline-flex">
                                                <li class="breadcrumb-item">홈</li>
                                                <li class="breadcrumb-item">설정관리</li>
                                                <li class="breadcrumb-item text-gray-700">승인요청</li>
                                            </ol>
                                        </div>
                                    </div>
                                    <div class="card-body px-6 px-lg-9 py-2 py-lg-3">


                                        <div class="d-flex flex-stack flex-wrap mb-4">
                                            <div class="d-flex align-items-center py-1">
                                                <!--h6 class="fs-6 mt-2 text-primary">(주)유비스토리</h6-->
                                            </div>
                                            <div class="d-flex align-items-center py-1">
												<a href="javascript:void(0);" class="btn btn-sm btn-warning" onclick="data_form_open('')"><i class="ki-outline ki-pencil fs-6"></i> 등록</a>
                                            </div>
                                        </div>	

										<?
										if ($sub_type == 'postform' || $sub_type == 'modifyform')
										{
											echo '<div id="work_form_view">';
											include $local_path . "/bizstory/comp_set/client_request_form.php";
											echo '</div>';
										}
										else if( $sub_type == 'viewform')
										{
											echo '<div id="work_form_view">';
											include $local_path . "/bizstory/comp_set/client_request_view.php";
											echo '</div>';
										}
										else
										{
											echo "<!-- 글목록 -->";
											echo '<div id="data_list">';
											include $local_path."/bizstory/comp_set/client_request_list.php";
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
		<?=$form_page;?>
	</form>

<script type="text/javascript">
//<![CDATA[
	var link_list = '<?=$link_list;?>';
	var link_form = '<?=$link_form;?>';
	var link_ok   = '<?=$link_ok;?>';
	var link_up   = '<?=$link_up;?>';

//------------------------------------ 등록
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#post_schul_name').val();
		chk_title = $('#post_schul_name').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						location.href = "<?=$this_page;?>?<?=$f_all;?>";
					}
					else
					{
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

//------------------------------------ 단계변경
	function up_menu_change(menu_depth, idx, comp_idx)
	{
		if (menu_depth == "") menu_depth = $('#post_menu_depth').val();

		$.ajax({
			type:'post', dataType: 'html', url: link_up,
			data : {"menu_depth" : menu_depth, "code_idx" : idx, "comp_idx" : comp_idx, "code_part" : $('#post_part_idx').val()},
			success: function(msg) {
				$('#up_menu_list').html(msg);
			}
		});
	}

//------------------------------------ 선택된 하위메뉴
	function down_menu_change(menu_depth, sel_depth, idx)
	{
		if (menu_depth == "") menu_depth = document.postform.menu_depth.value;
		$.ajax({
			type:'post', dataType : 'html', url: link_up,
			data: {"menu_depth" : menu_depth, "sel_depth" : sel_depth, "ccg_idx" : idx, "code_part" : $('#post_part_idx').val()},
			success : function(msg) {
				$('#up_menu_list').html(msg);
			}
		});
	}
//]]>
</script>

