<?
/*	
	위치 : 설정관리 > 접수관리 > 접수분류
	생성 : 2013.03.27
	수정 : 2024.03.14 김소령
	내용 : 교육기관 교육청 등록시 접수 분류 자동 생성을 위한 기초 값 설정 
*/

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
	$link_list = $local_dir . "/bizstory/maintain/receipt_class_list.php"; // 목록
	$link_form = $local_dir . "/bizstory/maintain/receipt_class_form.php"; // 등록
	$link_ok   = $local_dir . "/bizstory/maintain/receipt_class_ok.php";   // 저장
	$link_up   = $local_dir . "/bizstory/maintain/receipt_class_up.php";   // 상위
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
                                            <h4 class="fs-1">접수분류관리</h4>
                                        </div>
                                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                            <ol
                                                class="breadcrumb breadcrumb-dot text-muted fs-8 fs-md-7 d-none d-md-inline-flex">
                                                <li class="breadcrumb-item">홈</li>
                                                <li class="breadcrumb-item">설정관리</li>
                                                <li class="breadcrumb-item text-gray-700">접수분류관리</li>
                                            </ol>
                                        </div>
                                    </div>
                                    <div class="card-body px-6 px-lg-9 py-2 py-lg-3">


                                        <div class="d-flex flex-stack flex-wrap mb-4">
                                            <div class="d-flex align-items-center py-1">
                                                <!--h6 class="fs-6 mt-2 text-primary">(주)유비스토리</h6-->
                                            </div>
                                            <div class="d-flex align-items-center py-1">
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-idx="" data-bs-toggle="modal" data-bs-target="#kt_modal_position">
													<i class="ki-outline ki-pencil fs-6"></i> 등록
												</button>
                                            </div>
                                        </div>	

										<!-- 글목록 -->
                                        <div id="data_list">
                                            <? include_once("{$local_path}/bizstory/maintain/receipt_class_list.php"); ?>
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


<div class="tablewrapper">
	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<?=$form_page;?>
	</form>
</div>

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

		chk_value = $('#post_menu_depth').val();
		chk_title = $('#post_menu_depth').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if(parseInt(chk_value) > 1)
		{
			let chk_menu = '';
			
			for(let i=1; i<chk_value; i++)
			{
				chk_menu = $('#chk_menu'+i).val();
				chk_menu_title = $('#chk_menu'+i).attr('title');
				if(chk_menu == "")
				{
					chk_total = chk_total + chk_menu_title + '<br />';
					action_num++;
				}
			}
			
			$('#post_receipt_code').val($('#chk_menu'+(chk_value-1)+" :selected").attr('data-receipt-code'));
		}

		chk_value = $('#post_code_name').val();
		chk_title = $('#post_code_name').attr('title');
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
						document.location.reload();
					}
					else
					{
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					$(".modal-backdrop").fadeOut("fade");
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
			type: "post", dataType: 'html', url : link_up,
			data : {"menu_depth" : menu_depth, "code_idx" : idx, "comp_idx" : comp_idx, "code_part" : $('#post_part_idx').val()},
			success : function(msg) {
				$('#up_menu_list').html(msg);
			}
		});
	}

//------------------------------------ 선택된 하위메뉴
	function down_menu_change(menu_depth, sel_depth, idx)
	{
		if (menu_depth == "") menu_depth = document.postform.menu_depth.value;
		$.ajax({
			type: "post", dataType : 'html', url : link_up,
			data : {"menu_depth" : menu_depth, "sel_depth" : sel_depth, "code_idx" : idx},
			success : function(msg) {
				$('#up_menu_list').html(msg);
			}
		});
	}

	list_data();
//]]>
</script>