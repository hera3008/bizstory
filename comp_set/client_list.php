<?
/*
	수정 : 2013.03.37
	위치 : 설정폴더 > 거래처관리 > 거래처등록/수정 - 목록
*/
	//require_once "../common/setting.php";
	//require_once "../common/no_direct.php";
	//require_once "../common/member_chk.php";

	$code_comp    = $_SESSION[$sess_str . '_comp_idx'];
	//$code_part    = search_company_part($code_part);
	$code_part    = '';
	$set_client_cnt = $comp_set_data['client_cnt'];
	$set_agent_yn   = $comp_set_data['agent_yn'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and ci.comp_idx = '" . $code_comp . "' and ci.part_idx = '" . $code_part . "'";
	if ($shgroup != '' && $shgroup != 'all') // 거래처분류
	{
		$where .= " and (concat(ccg.up_ccg_idx, ',') like '%" . $shgroup . ",%' or ci.ccg_idx = '" . $shgroup . "')";
	}
	if ($stext != '' && $swhere != '')
	{
		$where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ci.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = client_info_data('list', $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

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

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$page_where = " and ci.comp_idx = '" . $code_comp . "'";
		$page_data = client_info_data('page', $page_where);
		if ($page_data['total_num'] >= $set_client_cnt) // 거래처수확인
		{
			$btn_write = '<a href="javascript:void(0);" onclick="alert(\'더이상 등록할 수 없습니다.\')" class="btn_big_green"><span>등록</span></a>';
		}
		else
		{
			$btn_write = '<a href="javascript:void(0);" onclick="open_data_form(\'\')" class="btn_big_green"><span>등록</span></a>';
		}
	}

// 월유지보수 합계
	$con_where = " and con.comp_idx = '" . $code_comp . "' and con.part_idx = '" . $code_part . "'";
	$con_list = contract_info_data('list', $con_where, '', '', '');
	
?>


										<form id="searchform" name="searchform" method="post" action="#" class="form p-4 border bg-gray-100 rounded-2 mb-6 mb-lg-8">
                                            <div class="row gx-2">
                                                <div class="col-4 col-sm-6 col-lg-3 mb-2 mb-lg-0">
                                                    <select class="form-select form-select-sm" name="shgroup" id="search_shgroup" data-control="select2"  data-hide-search="true" aria-label="전체거래처분류">
                                                        <option value="all">전체거래처분류</option>
                                                        <option value="1">관공서</option>
                                                        <option value="8">&nbsp;&nbsp;&nbsp;매월청구(업체)</option>
                                                        <option value="5">&nbsp;&nbsp;&nbsp;매월청구(조달)</option>
                                                        <option value="9">&nbsp;&nbsp;&nbsp;분기청구(업체)</option>
                                                        <option value="20">&nbsp;&nbsp;&nbsp;신규프로젝트</option>
                                                        <option value="7">&nbsp;&nbsp;&nbsp;무상기간</option>
                                                        <option value="6">&nbsp;&nbsp;&nbsp;미계약</option>
                                                        <option value="10">&nbsp;&nbsp;&nbsp;신규영업중</option>
                                                        <option value="12">&nbsp;&nbsp;&nbsp;계약중</option>
                                                        <option value="11">&nbsp;&nbsp;&nbsp;완불</option>
                                                        <option value="2">교회</option>
                                                        <option value="3">학교</option>
                                                        <option value="4">기업</option>
                                                        <option value="330">&nbsp;&nbsp;&nbsp;매입</option>
                                                        <option value="331">&nbsp;&nbsp;&nbsp;매출</option>
                                                        <option value="26">&nbsp;&nbsp;&nbsp;신규프로젝트</option>
                                                        <option value="361">&nbsp;&nbsp;&nbsp;협력업체</option>
                                                        <option value="21">비즈스토리</option>
                                                        <option value="22">&nbsp;&nbsp;&nbsp;학내망</option>
                                                        <option value="23">&nbsp;&nbsp;&nbsp;재조사</option>
                                                        <option value="207">&nbsp;&nbsp;&nbsp;일반기업</option>
                                                        <option value="329">보류</option>
                                                    </select>
                                                </div>
                                                <div class="col-8 col-sm-6 col-lg-2">
                                                    <select class="form-select form-select-sm" name="swhere"
                                                        id="search_swhere" data-control="select2"
                                                        data-hide-search="true" aria-label="칼럼선택">
                                                        <option value="ci.client_name">거래처명</option>
                                                        <option value="ci.charge_info">담당자정보</option>
                                                        <option value="ci.address">주소</option>
                                                    </select>
                                                </div>
                                                <div class="col-9 col-sm-10 col-lg-5">
                                                    <div class="position-relative d-flex align-items-center">
                                                        <input type="text" class="form-control form-control-sm"
                                                            placeholder="키워드를 입력하세요" />
                                                        <button type="submit"
                                                            class="btn btn-sm btn-icon btn-dark position-absolute end-0 px-6 rounded-start-0"
                                                            aria-label="검색"><i
                                                                class="ki-outline ki-magnifier fs-3"></i></button>
                                                    </div>
                                                </div>
                                                <div class="col-3 col-sm-2 col-lg-2">
                                                    <button onclick="viewExcelDownload();" type="button"
                                                        class="w-100 btn btn-sm btn-success px-6"
                                                        aria-label="Excel 다운로드">Excel<span
                                                            class="d-none d-xl-inline-block"> 다운로드</span></button>
                                                </div>
                                            </div>
                                        </form>

                                        <div
                                            class="alert alert-warning d-flex align-items-center border-0 fs-7 text-warning">
                                            <ul class="mb-0">
                                                <li>거래처 등록은 최대 500개까지 가능합니다.</li>
                                                <li>현재 거래처는 230개 등록되었습니다.</li>
                                            </ul>
                                        </div>

                                        <div class="d-flex flex-stack flex-wrap mb-4">
                                            <div class="d-flex align-items-center py-1"></div>
                                            <div class="d-flex align-items-center py-1">
												 <a href="javascript:void(0);" class="btn btn-sm btn-warning" onclick="data_form_open(' ')"><i class="ki-outline ki-pencil fs-6"></i> 등록</a>
                                            </div>
                                        </div>

                                        <table class="table align-middle table-striped table-row-bordered ls-n2 fs-6 fs-sm-7 text-gray-700 gy-3 gx-0" id="kt_datatable">
                                            <thead>
                                                <tr class="text-gray-900 fw-semibold text-uppercase bg-gray-100 border-top-2 border-secondary">
                                                    <th class="min-w-45px mw-50px text-center">
                                                        <label class="form-check form-check-custom form-check-sm ms-5">
                                                            <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="[data-bbs-list='check']"  value="0" />
                                                        </label>
                                                    </th>
                                                    <th class="min-w-45px mw-55px text-center d-none d-xl-table-cell"> 번호 </th>
                                                    <th class="min-w-70px mw-75px text-center d-none d-xl-table-cell"> 거래처코드 </th>
                                                    <th class="min-w-175px text-center" data-priority="1"> 거래처명</th>
                                                    <?/*<th class="min-w-75px min-w-md-85px mw-90px text-center d-none d-sm-table-cell"> 담당자 </th>*/?>
                                                    <th class="min-w-90px mw-100px text-center d-none d-xl-table-cell"> 그룹 </th>
                                                    <th class="min-w-70px min-w-md-80px text-center d-none d-xl-table-cell"> 연락처 </th>
                                                    <th class="min-w-40px min-w-md-45px mw-60px text-center d-none d-md-table-cell"> 사용 </th>
                                                    <th class="min-w-40px min-w-md-45px mw-60px text-center d-none d-md-table-cell"> IP차단 </th>
                                                    <!--th class="min-w-40px min-w-md-45px mw-60px text-center d-none d-md-table-cell"> SMS </th-->
                                                    <th class="min-w-40px min-w-md-45px mw-60px text-center d-none d-md-table-cell"> email </th>
                                                    <th class="min-w-40px min-w-md-45px mw-60px text-center d-none d-md-table-cell"> PUSH </th>
                                                    <th class="min-w-40px min-w-md-45px mw-50px text-center d-none d-xl-table-cell"> 홈 </th>
                                                    <th class="min-w-90px w-125px text-center">관리</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
											<?
												$i = 0;
												if ($list["total_num"] == 0) {
											?>
													<tr>
														<td colspan="14">등록된 데이타가 없습니다.</td>
													</tr>
											<?
												}
												else
												{
													$i = 1;
													$num = $list["total_num"] - ($page_num - 1) * $page_size;
													foreach($list as $k => $data)
													{
														if (is_array($data))
														{
															$ci_idx      = $data['ci_idx'];
															$user_link   = $local_dir . '/bizstory/comp_set/client_user.php';
															$report_link = $local_dir . '/bizstory/comp_set/client_report.php';

															if ($auth_menu['mod'] == "Y")
															{
																$btn_view   = "check_code_data('check_yn', 'view_yn', '" . $ci_idx . "', '" . $data["view_yn"] . "')";
																$btn_ip     = "check_code_data('check_yn', 'ip_yn', '" . $ci_idx . "', '" . $data["ip_yn"] . "')";
																$btn_sms    = "check_code_data('check_yn', 'receipt_sms_yn', '" . $ci_idx . "', '" . $data["receipt_sms_yn"] . "')";
																$btn_email  = "check_code_data('check_yn', 'receipt_email_yn', '" . $ci_idx . "', '" . $data["receipt_email_yn"] . "')";
																$btn_push   = "check_code_data('check_yn', 'receipt_push_yn', '" . $ci_idx . "', '" . $data["receipt_push_yn"] . "')";
																$btn_modify = "data_form_open('" . $ci_idx . "')";
															}
															else
															{
																$btn_view   = "check_auth_popup('modify')";
																$btn_ip     = "check_auth_popup('modify')";
																$btn_sms    = "check_auth_popup('modify')";
																$btn_email  = "check_auth_popup('modify')";
																$btn_push   = "check_auth_popup('modify')";
																$btn_modify = "check_auth_popup('modify')";
															}
															if ($auth_menu['down'] == "Y") $btn_report = "popupsub_open('" . $ci_idx . "', '" . $report_link . "')";
															else $btn_report = "check_auth_popup('modify')";

															if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $ci_idx . "')";
															else $btn_delete = "check_auth_popup('delete');";

														// 연락처
															$charge_info = $data['charge_info'];
															$charge_info_arr = explode('||', $charge_info);
															$info_str = explode('/', $charge_info_arr[0]);

															if ($data['tel_num'] != '--' && $data['tel_num'] != '-' && $data['tel_num'] != '') $tel_num_str = '<span class="eng">(' . $data['tel_num'] . ')</span>';
															else $tel_num_str = '';

															if ($data['client_email'] != '@' && $data['client_email'] != '') $client_email_str = '<span class="eng">' . $data['client_email'] . '</span>';
															else $client_email_str = '';

														// 링크주소
															$link_url = $data['link_url'];
															$link_url_arr = explode(',', $link_url);
															if ($link_url_arr[0] != '')
															{
																$link_string = str_replace('http://', '', $link_url_arr[0]);
																$link_html = '<a href="http://' . $link_string . '" target="_blank" alt="홈페이지로 이동합니다."><i class="ki-outline ki-home fs-4"></i></a>';
															}
															else
															{
																$link_html = '';
															}

														// 거래처그룹 2단계까지만
															$group_view = client_group_view($data['ccg_idx']);
															$group_name = $group_view['group_level1'];
															if ($group_view['group_level2'] != '') $group_name .= '<br />' . $group_view['group_level2'];

														// 사용자수
															$user_where = " and cu.ci_idx = '" . $data['ci_idx'] . "'";
															$user_page = client_user_data('page', $user_where);
															$total_user = $user_page['total_num'];

														// 계약수
															$con_where = " and con.ci_idx = '" . $data['ci_idx'] . "'";
															$con_page = contract_info_data('page', $con_where);
															$total_con = $con_page['total_num'];

														// 메모수
															$sub_where = " and cim.ci_idx='" . $data['ci_idx'] . "'";
															$sub_data = client_memo_data('page', $sub_where);
															$data['total_memo'] = $sub_data['total_num'];

															$charge_str = staff_layer_form($data['mem_idx'], '', 'N', $set_color_list2, 'clientlist', $data['ci_idx'], '');
											?>
                                                <tr>
                                                    <td>
                                                        <label class="form-check form-check-custom form-check-sm ms-5">
                                                            <input class="form-check-input" type="checkbox" id="ciidx_<?=$i;?>" name="chk_ci_idx[]" value="<?=$data["ci_idx"];?>" data-bbs-list="check" value="1" />
                                                        </label>
                                                    </td>
                                                    <td class="d-none d-xl-table-cell"><?=$num;?></td>
                                                    <td class="d-none d-xl-table-cell"><?=$data['client_code'];?></td>
													
                                                    <td class="text-start ps-4">
                                                       <a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="text-gray-800 text-hover-primary ellipsis-1">
                                                            <?=$data['client_name'];?>
											<?
												if ($total_user > 0)
												{
											?>

                                                            <!-- 사용자 여부 --><span class="ms-2 text-warning fw-semibold"><i class="ki-outline ki-file-down fs-5 fs-md-4 me-1 align-middle text-warning"></i><?=number_format($total_user)?></span><!--// 사용자 여부 -->
											<?
												}
											?>

											<?
												if ($data['total_memo'] > 0)
												{
											?>
                                                            
                                                            <!-- 메모 여부 --><span class="fw-semibold text-danger ms-1"><i class="ki-outline ki-message-text-2 fs-5 fs-md-4 me-1 align-middle text-danger"></i><?=number_format($data['total_memo'])?></span> <!--// 메모 여부 -->
											<?
											}
											?>
                                                            <!-- 계약 여부 --><!--span class="fw-semibold text-info ms-1"><i class="ki-outline ki-briefcase fs-5 fs-md-4 me-1 align-middle text-info"></i>2</span--><!--// 계약 여부 -->
                                                        </a>
                                                    </td>
													<?/*
                                                    <td class="d-none d-sm-table-cell">
                                                       
														<?=$charge_str;?>
                                                    </td>
													*/?>
                                                    <td class="fw-semibold d-none d-xl-table-cell">
														<?=$group_name;?>
														<!--관공서 <div class="text-muted fw-normal">무상기간</div> -->
                                                    </td>
                                                    <td class="d-none d-xl-table-cell">
                                                        <strong class="fw-semibold cursor-pointer" data-bs-toggle="popover" data-bs-placement="top" data-bs-content=""><?=$client_email_str;?>
														<span class="d-block fw-normal fs-8"><?=$tel_num_str;?></span>
														</strong>
                                                    </td>
                                                    <td class="d-none d-md-table-cell">
                                                        <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
                                                            <input class="form-check-input w-30px h-20px" type="checkbox" name="view_yn" id="check_view_yn" <?=$data['view_yn'] =='Y' ? 'checked="checked"' :''?> onclick="<?=$btn_view;?>"/>
                                                        </div>
                                                    </td>
                                                    <td class="d-none d-md-table-cell">
                                                        <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
                                                            <input class="form-check-input w-30px h-20px" type="checkbox" name="ip_yn" id="check_ip_yn" <?=$data['ip_yn'] =='Y' ? 'checked="checked"' :''?> onclick="<?=$btn_ip;?>"/>
                                                        </div>
                                                    </td>
                                                    <!--td class="d-none d-md-table-cell">
                                                        <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
                                                            <input class="form-check-input w-30px h-20px" type="checkbox" name="receipt_sms_yn" id="check_receipt_sms_yn" <?=$data['receipt_sms_yn'] =='Y' ? 'checked="checked"' :''?> onclick="<?=$btn_sms;?>"/>
                                                        </div>
                                                    </td-->
                                                    <td class="d-none d-md-table-cell">
                                                        <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
                                                            <input class="form-check-input w-30px h-20px" type="checkbox" name="receipt_email_yn" id="check_receipt_email_yn" <?=$data['receipt_email_yn'] =='Y' ? 'checked="checked"' :''?> onclick="<?=$btn_email;?>"/>
                                                        </div>
                                                    </td>
                                                    <td class="d-none d-md-table-cell">
                                                        <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
                                                            <input class="form-check-input w-30px h-20px" type="checkbox" name="receipt_push_yn" id="check_receipt_push_yn" <?=$data['receipt_push_yn'] =='Y' ? 'checked="checked"' :''?> onclick="<?=$btn_push;?>"/>
                                                        </div>
                                                    </td>
                                                    <td class="d-none d-xl-table-cell">
														<?=$link_html?>
                                                    </td>
                                                    <td class="px-4">
                                                        <div class="row">
                                                            <div class="col-6 pe-1">
                                                                <a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="w-100 btn btn-sm btn-primary px-2 py-1 fs-8">
                                                                    수정
                                                                </a>
                                                            </div>
                                                            <div class="col-6 ps-1">
                                                                <button type="button" onclick="<?=$btn_delete;?>" data-bs-toggle="modal" data-bs-target="#kt_modal_leave" class="w-100 btn btn-sm btn-danger px-2 py-1 fs-8">
                                                                    삭제
                                                                </button>
                                                            </div>
                                                            <!--div class="col-12 pt-2">
                                                                <a href="#"
                                                                    class="w-100 btn btn-sm btn-info px-2 py-1 fs-8">
                                                                    에이전트
                                                                </a-->
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
											<?

															$num--;
															$i++;
														}
													}
												}
											?>

                                            </tbody>
                                        </table>
                                        <!-- //거래처등록/수정 -->

<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>

<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />
<hr />

<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>
<hr />

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 거래처사용자목록
	function user_list(ci_idx)
	{
		$('#list_ci_idx').val(ci_idx);
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/comp_set/client_user.php',
			data: $('#listform').serialize(),
			success: function(msg) {
				var maskHeight = $(document).height() + 500;
				var maskWidth  = $(window).width();
				$("#data_form").slideDown("slow");
				$("#loading").fadeIn('slow').fadeOut('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':maskWidth,'height':maskHeight}).fadeIn("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 거래처사용자등록
	function user_form(ci_idx, idx)
	{
		$('#list_ci_idx').val(ci_idx);
		$('#list_idx').val(idx);
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/comp_set/client_user_form.php',
			data: $('#listform').serialize(),
			success: function(msg) {
				var maskHeight = $(document).height() + 500;
				var maskWidth  = $(window).width();
				$("#data_form").slideDown("slow");
				$("#loading").fadeIn('slow').fadeOut('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':maskWidth,'height':maskHeight}).fadeIn("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 삭제하기
	function check_form_delete(ci_idx, idx)
	{
		if (confirm("선택하신 데이타를 삭제하시겠습니까?"))
		{
			$('#other_sub_type').val('delete');
			$('#other_idx').val(idx);
			$.ajax({
				type: "post", dataType: 'json', url: link_ok,
				data: $('#otherform').serialize(),
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						user_list(ci_idx);
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
	}

//------------------------------------ 목록 처리
	function check_form_code(sub_type, sub_action, ci_idx, idx, post_value)
	{
		$('#list_sub_type').val(sub_type)
		$('#list_sub_action').val(sub_action);
		$('#list_idx').val(idx);
		$('#list_post_value').val(post_value);
		$('#list_ci_idx').val(ci_idx);

		$.ajax({
			type: "post", dataType: 'json', url: link_ok,
			data: $('#listform').serialize(),
			success: function(msg) {
				if (msg.success_chk == "Y") user_list(ci_idx);
				else check_auth_popup(msg.error_string);
			}
		});
	}
//]]>
</script>