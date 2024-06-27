<?
/*	
	위치 : 설정관리 > 에이전트관리 > 아이콘관리
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
	$link_ok    = $local_dir . "/bizstory/carecon/carecon_icon_ok.php";   // 저장
	$link_icon = $local_dir . "/bizstory/carecon/carecon_icon.php"; // 아이콘

// 1.아이콘 버튼 데이타
	$where = " and cbu.comp_idx = '" . $code_comp . "' and cbu.carecon_type = '" . $carecon_type . "'";
	$list = carecon_button_data('list', $where, '', '', '');
	
// 2. 알림게시판
	$sub_where1 = "
		and cbu.comp_idx = '" . $code_comp . "' and cbu.part_idx = '" . $code_part . "'
		and cbu.carecon_type = '" . $code_agent . "' and cbu.btn_type = '2'";
	$sub_data1 = carecon_button_data('page', $sub_where1);
	$sub_total1 = $sub_data1['total_num'];

// 3. 상담게시판
	$sub_where2 = "
		and cbu.comp_idx = '" . $code_comp . "' and cbu.part_idx = '" . $code_part . "'
		and cbu.carecon_type = '" . $code_agent . "' and cbu.btn_type = '3'";
	$sub_data2 = carecon_button_data('page', $sub_where2);
	$sub_total2 = $sub_data2['total_num'];
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
                                            <h4 class="fs-1">Care Con 아이콘관리</h4>
                                        </div>
                                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                            <ol
                                                class="breadcrumb breadcrumb-dot text-muted fs-8 fs-md-7 d-none d-md-inline-flex">
                                                <li class="breadcrumb-item">홈</li>
                                                <li class="breadcrumb-item">설정관리</li>
												<li class="breadcrumb-item">Care Con 관리</li>
                                                <li class="breadcrumb-item text-gray-700">아이콘 관리</li>
                                            </ol>
                                        </div>
                                    </div>
                                    <div class="card-body px-6 px-lg-9 py-2 py-lg-3">
										
										 <?php
											// 카테고리
											echo part_cate_tab($code_comp);
										?>
										<!-- 안내 -->
                                        <div
                                            class="alert alert-warning d-flex align-items-center border-0 fs-7 text-warning">
                                            <ul class="mb-0">
                                                <li>링크주소 입력시 "http://", "ftp://"등 같이 입력하세요.</li>
                                            </ul>
                                        </div>
                                        <!--// 안내  -->

                                        <div class="d-flex flex-stack flex-wrap mb-4">
                                            <div class="d-flex align-items-center py-1">
                                                <select name="sh_carecon_type" id="sh_carecon_type" data-control="select2" data-hide-search="true"
                                                    class="form-select form-select-sm bg-gray-100 border-gray-300 w-200px">
                                                    <option value="에이전트 타입선택" selected="">에이전트 타입선택</option>
                                                    <option value="A" <?=$carecon_type=='A'? 'selected' : ''?>>A 타입</option>
                                                    <!--option value="B 타입">B 타입</option>
                                                    <option value="C 타입">C 타입</option-->
                                                </select>
                                            </div>
                                        </div>

                                        <form id="postform" name="postform" method="post" action="<?=$this_page;?>" target="print" onsubmit="return check_form()">
											<input type="hidden" name="carecon_type" id="post_carecon_type" value="<?=$carecon_type?>">
                                            <table class="table align-middle table-striped table-row-bordered ls-n2 fs-6 fs-sm-7 text-gray-700 gy-3 gx-0" id="kt_datatable">
                                                <thead>
                                                    <tr class="text-gray-900 fw-semibold text-uppercase bg-gray-100 border-top-2 border-secondary">
                                                        <th class="w-60px d-none d-xl-table-cell text-center">번호</th>
                                                        <th class="text-center" data-priority="1"> 아이콘명 / 링크주소</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center">
												<?
													if ($list['total_num'] == 0)
													{
														$sub_type = 'post';
														foreach ($set_agent_button as $k => $v)
														{
												?>
                                                    <tr>
                                                        <td class="d-none d-xl-table-cell"><?=$k;?></td>                                                       
                                                        <td class="text-start px-3">
                                                            <div class="d-xl-flex flex-xl-wrap">
															<?if ($k == 1){?>
                                                                <div class="d-xl-flex align-items-center mb-2 mb-xl-0 me-xl-3">
                                                                    <input type="text"  name="btn_name_<?=$k;?>" id="btn_name_<?=$k;?>" value="<?=$v;?>" class="form-control form-control-sm w-100 w-xl-150px" placeholder="아이콘명을 입력하세요."  />
                                                                </div>
															<?}else{?>
																 <div class="d-xl-flex align-items-center mb-2 mb-xl-0 me-xl-3">
                                                                    <input type="text"  name="btn_name_<?=$k;?>" id="btn_name_<?=$k;?>" value="<?=$v;?>" class="form-control form-control-sm w-100 w-xl-150px" placeholder="아이콘명을 입력하세요."  />
                                                                </div>
                                                                <div class="d-xl-flex align-items-center mb-2 mb-xl-0 me-xl-3">
                                                                    <select name="btn_type_<?=$k;?>" id="btn_type_<?=$k;?>" onchange="type_change(this.value, '<?=$k;?>');" 
                                                                        data-control="select2" data-hide-search="true" class="form-select form-select-sm border-gray-300 w-100 w-xl-200px">
                                                                        <option value="아이콘 타입선택" selected="">아이콘 타입선택</option>
																		<?
																			foreach ($set_agent_button_type as $type_k => $type_v)
																			{
																		?>
																				<option value="<?=$type_k;?>"><?=$type_v;?></option>
																		<?
																			}
																		?>
                                                                    </select>
                                                                </div>
                                                                <div class="d-xl-flex align-items-center">
                                                                    <input type="text" name="link_url_<?=$k;?>" id="link_url_<?=$k;?>" value="" class="form-control form-control-sm w-100 w-xl-350px" placeholder="" disabled="disabled" />
                                                                </div>
															<?}?>
                                                            </div>
                                                        </td>
												
                                                    </tr>
												<?
														}
													}
													else
													{
														$sub_type = 'modify';

														foreach ($list as $k => $data)
														{
															if (is_array($data))
															{
																$sort = $data['sort'];
																if ($data['btn_type'] == '5')
																{
																	$class_str    = '';
																	$disabled_str = '';
																}
																else
																{
																	$disabled_str = 'disabled="disabled"';
																}
												?>

                                                    <tr>
														
														<td class="d-none d-xl-table-cell"><?=$sort;?></td>                                                                                             
                                                        <td class="text-start px-3">
															<div class="d-xl-flex flex-xl-wrap">
															<?if ($sort == 1){?>
                                                                <div class="d-xl-flex align-items-center mb-2 mb-xl-0 me-xl-3">																	
                                                                    <input type="text" name="btn_name_<?=$sort;?>" id="btn_name_<?=$sort;?>" value="<?=$data['btn_name'];?>"
                                                                        class="form-control form-control-sm w-100 w-xl-150px" placeholder="아이콘명을 입력하세요." />
                                                                </div>
															<?}else{?>
																 <div class="d-xl-flex align-items-center mb-2 mb-xl-0 me-xl-3">																	
                                                                    <input type="text" name="btn_name_<?=$sort;?>" id="btn_name_<?=$sort;?>" value="<?=$data['btn_name'];?>"
                                                                        class="form-control form-control-sm w-100 w-xl-150px" placeholder="아이콘명을 입력하세요." />
                                                                </div>
                                                                <div class="d-xl-flex align-items-center mb-2 mb-xl-0 me-xl-3">
                                                                    <select name="btn_type_<?=$sort;?>" id="btn_type_<?=$sort;?>" onchange="type_change(this.value, '<?=$sort;?>');"
																		data-control="select2" data-hide-search="true" class="form-select form-select-sm border-gray-300 w-100 w-xl-200px">
                                                                        <option value="">아이콘 타입선택 </option>
																		<?
																			foreach ($set_agent_button_type as $type_k => $type_v)
																			{
																				if ($type_k != $data['btn_type'])
																				{
																					if ($type_k == '2' && $sub_total1 > 0 || $type_k == '3' && $sub_total2 > 0)
																					{

																					}
																					else
																					{

																		?>
																		<option value="<?=$type_k;?>"><?=$type_v;?></option>
																		<?
																					}
																				}
																			}
																		?>
                                                                    </select>
                                                                </div>
                                                                <div class="d-xl-flex align-items-center">
                                                                    <input type="text" type="text" name="link_url_<?=$sort;?>" id="link_url_<?=$sort;?>" value="<?=$data['link_url'];?>"
                                                                        class="form-control form-control-sm w-100 w-xl-350px" placeholder="" <?=$disabled_str;?> />
                                                                </div>
																<?}?>
                                                            </div>
                                                        </td>
                                                    </tr>
												<?
															}
														}
													}
												?>
                                                </tbody>
                                            </table>

                                            <div class="row mb-8 mb-lg-10">
                                                <div class="col-6"></div>
                                                <div class="col-6 text-end">
													<input type="hidden" name="sub_type" value="<?=$sub_type;?>" />
                                                    <button type="button" class="btn btn-sm btn-secondary d-print-none" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'">
                                                        <i class="ki-outline ki-arrows-circle fs-6"></i> 취소
                                                    </button>
                                                    <button type="submit" class="btn btn-sm btn-warning d-print-none">
                                                        <i class="ki-outline ki-pencil fs-6"></i> 수정
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- //글목록 -->
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
		<input type="hidden" id="list_carecon_type" name="carecon_type" value="<?=$carecon_type;?>" />
		<?=$form_page;?>
	</form>

<script type="text/javascript">
//<![CDATA[
	var link_ok    = '<?=$link_ok;?>';
//]]>
</script>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
					<?
						$f_default1 = str_replace('&amp;', '&', $f_default);
					?>
						location.href = '?<?=$f_default1;?>';
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 타입변경
	function type_change(str, idx)
	{
		if (str == '5') // 링크아이콘일 경우만
		{
			$("#link_url_" + idx).attr('disabled',false);
			$("#link_url_" + idx).css('background','');
		}
		else
		{
			$("#link_url_" + idx).attr('disabled',true);
			$("#link_url_" + idx).css('background','#CCCCCC');
		}
	}
//]]>
</script>



	