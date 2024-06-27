<?
/*
	위치 : 설정폴더 > 거래처관리 > 거래처등록/수정
	수정 : 2013.03.30
	수정 : 2024.03.18
*/

	//require_once "../common/setting.php";
	//require_once "../common/no_direct.php";
	//require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	//$code_part = search_company_part($code_part);
	$code_part = "";
	$set_client_cnt = $comp_set_data['client_cnt'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ci.reg_date';
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
	$link_list         = $local_dir . "/bizstory/comp_set/client_request_list.php";      // 목록
	$link_form         = $local_dir . "/bizstory/comp_set/client_request_form.php";      // 등록폼
	$link_view         = $local_dir . "/bizstory/comp_set/client_request_view.php";      // 보기
	$link_ok           = $local_dir . "/bizstory/comp_set/client_request_ok.php";        // 저장
	$link_excel        = $local_dir . "/bizstory/comp_set/client_request_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/comp_set/client_request_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/comp_set/client_request_print_sel.php"; // 상세인쇄

	if ($auth_menu['down'] == "Y") // 다운로드버튼
	{
		$btn_down = '<a href="javascript:void(0);" class="btn_sml" onclick="list_excel()"><span>엑셀</span></a>';
	}
	if ($auth_menu['print'] == "Y") // 인쇄버튼
	{
		//$btn_print     = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print()"><span><em class="print"></em>인쇄</span></a>';
		//$btn_print_sel = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print_detail()"><span><em class="print"></em>상세인쇄</span></a>';
	}

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';

	$where = " and comp.comp_idx = '" . $code_comp . "'";
	$comp_data = company_info_data("view", $where);

	if($comp_data['comp_class'] == "1")  //학교
	{
		$where =" cr.comp_client_idx = '{$code_comp}'";
	}
	else
	{
		$where =" cr.comp_idx = '{$code_comp}'";
	}
	$query_string ="
		SELECT
			cr.*
			, ci.comp_name AS comp_name, ci.tel_num AS tel_num, ci.comp_code AS comp_code
			, cci.comp_name AS client_comp_name, cci.tel_num AS client_comp_tel_num, cci.comp_code AS client_comp_code
		from
			client_request_data cr
			left join company_info AS ci ON cr.comp_idx = ci.comp_idx
			left join company_info AS cci ON cr.comp_client_idx = cci.comp_idx
		WHERE 
			{$where}
	";
	$sql_data['query_string'] = $query_string;
	$list = query_list($sql_data);
?>


										<form id="searchform" name="searchform" method="post" action="#" class="form p-4 border bg-gray-100 rounded-2 mb-6 mb-lg-8">
                                            <div class="row gx-2">
                                                <div class="col-4 col-sm-6 col-lg-3 mb-2 mb-lg-0">
                                                    <select class="form-select form-select-sm" name="shgroup" id="search_shgroup" data-control="select2"  data-hide-search="true" aria-label="전체거래처분류">
                                                        <option value="all">전체거래처분류</option>
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
                                            <!--ul class="mb-0">
                                                <li>거래처 등록은 최대 500개까지 가능합니다.</li>
                                                <li>현재 거래처는 230개 등록되었습니다.</li>
                                            </ul-->
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
                                                    <th class="min-w-175px text-center" data-priority="1">거래처명</th>
                                                    <th class="min-w-90px w-125px text-center"> 신청일 </th>
                                                    <th class="min-w-40px min-w-md-45px mw-50px text-center d-none d-xl-table-cell"> 승인여부 </th>
                                                    <th class="min-w-90px w-125px text-center">완료일</th>

													<?if($comp_data['comp_class'] == "1"){?>
													<th class="min-w-90px w-125px text-center">
														거래처 등록
													</th>	
													<?}?>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
											<?
												$i = 0;
												if ($list["total_num"] == 0) {
											?>
													<tr>
														<td colspan="7">등록된 데이타가 없습니다.</td>
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
															$cr_idx      = $data['cr_idx'];
															$user_link   = $local_dir . '/bizstory/comp_set/client_user.php';
															$report_link = $local_dir . '/bizstory/comp_set/client_report.php';

															if ($auth_menu['mod'] == "Y")
															{
																$btn_view   = "check_code_data('check_yn', 'view_yn', '" . $cr_idx . "', '" . $data["view_yn"] . "')";
																$btn_modify = "data_view_open('" . $cr_idx . "')";
															}
															else
															{
																$btn_view   = "check_auth_popup('modify')";																
																$btn_modify = "data_view_open('modify')";
															}
															
															if($comp_data['comp_class'] == "1")  //학교
															{
																$comp_name = $data['comp_name'];
																$tel_num = $data['tel_num'];
																$comp_code = $data['comp_code'];
															}
															else //기업
															{
																$comp_name = $data['client_comp_name'];
																$tel_num = $data['client_comp_tel_num'];
																$comp_code = $data['client_comp_code'];
																
															}
											?>
                                                <tr>
                                                    <td>
                                                        <label class="form-check form-check-custom form-check-sm ms-5">
                                                            <input class="form-check-input" type="checkbox" id="cr_idx_<?=$i;?>" name="chk_cr_idx[]" value="<?=$data["cr_idx"];?>" data-bbs-list="check" value="1" />
                                                        </label>
                                                    </td>
                                                    <td class="d-none d-xl-table-cell"><?=$num;?></td>
                                                    <td class="d-none d-xl-table-cell"><?=$comp_code;?></td>
                                                    <td class="text-start ps-4">
                                                       <a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="text-gray-800 text-hover-primary ellipsis-1">
                                                            <?=$comp_name;?>
                                                        </a>
                                                    </td>
													
                                                    <td class="fw-semibold d-none d-xl-table-cell"><?=$data['reg_date'];?></td>                                                   
                                                    <td class="d-none d-md-table-cell">
                                                        <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
                                                            <input class="form-check-input w-30px h-20px" type="checkbox" name="comfirm_yn" id="check_comfirm_yn" <?=$data['comfirm_yn'] =='Y' ? 'checked="checked"' :''?> />
                                                        </div>
                                                    </td>                                                   
                                                    <td class="px-4"><?=$data['confirm_date']?></td>
													<?if($comp_data['comp_class'] == "1"){?>
													<td class="px-4">
														<?if($data['comfirm_yn'] == "N"){?>
														<button type="button"  class="btn btn-sm btn-primary px-3 py-1 fs-8" onclick="location.href='/index.php?fmode=comp_set&smode=client&comp_code=<?=$data['comp_code']?>'">등록</button>
														<?}?>
													</td>
													<?}?>

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


<script type="text/javascript">
//<![CDATA[

//]]>
</script>