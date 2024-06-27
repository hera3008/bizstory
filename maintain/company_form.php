<?
/*
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 업체관리 > 업체목록 - 등록, 수정
*/

	$comp_idx = $idx;

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

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' && $comp_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $comp_idx != '') // 수정권한
	{
		$form_chk   = 'Y';
		$form_title = '수정';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
				$(".modal-backdrop").fadeOut("fade");
			//]]>
			</script>
		';
	}

	if ($form_chk == 'Y')
	{
		$where = " and comp.comp_idx = '" . $comp_idx . "'";
		$data = company_info_data("view", $where);

		if ($data["start_date"] == "") $data["start_date"] = $data["auth_date"];
		if ($data["start_date"] == "") $data["start_date"] = date('Y-m-d');
		$data["start_date"] = date_replace($data["start_date"], 'Y-m-d');
		$data["end_date"]   = date_replace($data["end_date"], 'Y-m-d');

		if ($data["view_yn"] == "") $data["view_yn"] = "Y";
		if ($data["auth_yn"] == "") $data["auth_yn"] = "N";

		$comp_num = $data['comp_num'];
		$comp_num_arr = explode('-', $comp_num);
		$data['comp_num1'] = $comp_num_arr[0];
		$data['comp_num2'] = $comp_num_arr[1];
		$data['comp_num3'] = $comp_num_arr[2];

		$distinct_num = $data['distinct_num'];
		$distinct_num_arr = explode('-', $distinct_num);
		$data['distinct_num1'] = $distinct_num_arr[0];
		$data['distinct_num2'] = $distinct_num_arr[1];

		$address = $data['address'];
		$address_arr = explode('||', $address);
		$data['address1'] = $address_arr[0];
		$data['address2'] = $address_arr[1];

		$tel_num = $data['tel_num'];
		$tel_num_arr = explode('-', $tel_num);
		$data['tel_num1'] = $tel_num_arr[0];
		$data['tel_num2'] = $tel_num_arr[1];
		$data['tel_num3'] = $tel_num_arr[2];

		$fax_num = $data['fax_num'];
		$fax_num_arr = explode('-', $fax_num);
		$data['fax_num1'] = $fax_num_arr[0];
		$data['fax_num2'] = $fax_num_arr[1];
		$data['fax_num3'] = $fax_num_arr[2];

		$comp_email = $data['comp_email'];		
		$comp_email_arr = explode('@', $comp_email);
		$data['comp_email1'] = $comp_email_arr[0];
		$data['comp_email2'] = $comp_email_arr[1];

		$hp_num = $data['hp_num'];
		$hp_num_arr = explode('-', $hp_num);
		$data['hp_num1'] = $hp_num_arr[0];
		$data['hp_num2'] = $hp_num_arr[1];
		$data['hp_num3'] = $hp_num_arr[2];
?>
                                                    
											<!-- 업체정보 -->
											<form id="postform" name="postform" method="post" class="form" action="<?=$this_page;?>" onsubmit="return check_form()">
											<?=$form_all;?>	

												<div class="row py-2 py-xl-4">
													<!-- 대분류 -->
													<div class="col-xl-2">
														<label for="comp_category" class="fs-6 fw-semibold my-2">
															<span class="required">분류</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<input type="hidden" name="param[comp_class]" id="post_comp_class" value="<?=$data['comp_class']?>" title="분류를 선택하세요">
														<select name="comp_category" id="comp_category" title="분류를 선택하세요"
															data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="분류를 선택하세요" 
															class="form-select form-select-sm"
														>
														<?
															$class_where = " and code.view_yn = 'Y' and code.import_yn = 'Y'";
															$class_list = company_class_data('list', $class_where, '', '', '');
															foreach ($class_list as $class_k => $class_data)
															{
																if (is_array($class_data))
																{
																	$emp_str = str_repeat('&nbsp;', 4 * ($class_data['menu_depth'] - 1));
														?>
															<option value="<?=$class_data['code_idx'];?>" date-class-code="<?=$class_data['class_code']?>" <?=selected($class_data['code_idx'], $data['comp_class']);?>><?=$emp_str?><?=$class_data['code_name'];?></option>
														<?
																}
															}
														?>
														</select>
													</div>
													<!--// 대분류 -->													
												</div>

												<? if($data['comp_class'] == 1){?>
												<div class="row py-2 py-xl-4 comp_class" id="comp_class_3">
													<!-- 시도교육청 이름 -->
													<div class="col-xl-2">
														<label for="post_sc_name" class="fs-6 fw-semibold my-2">
															<span class="required">시도교육청 이름</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<input type="text" name="param[sc_name]" id="post_sc_name" value="<?=$data['sc_name'];?>" title="시도교육청 이름 입력하세요." class="form-control form-control-sm" placeholder="시도교육청 이름 입력하세요." size="20">
													</div>
													<!--// 시도교육청 이름 -->

													<!-- 시도교육청코드 -->
													<div class="col-xl-2">
														<label for="post_sc_code" class="fs-6 fw-semibold my-2">
															<span class="required">시도교육청코드</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<input type="text" name="param[sc_code]" id="post_sc_code" value="<?=$data['sc_code'];?>" title="시도교육청 코드 입력하세요." class="form-control form-control-sm" placeholder="시도교육청 코드 입력하세요." size="20">
													</div>
													<!--// 시도교육청코드 -->
												</div>

												<div class="row py-2 py-xl-4 comp_class" id="comp_class_4">
													<!-- 지역교육지원청 이름 -->
													<div class="col-xl-2">
														<label for="post_org_name" class="fs-6 fw-semibold my-2">
															<span class="required">지역교육지원청 이름</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<input type="text" name="param[org_name]" id="post_org_name" value="<?=$data['org_name'];?>" title="지역교육지원청 이름 입력하세요." class="form-control form-control-sm" placeholder="지역교육지원청 이름 입력하세요." size="20">
													</div>
													<!--// 지역교육지원청 이름 -->

													<!-- 지역교육지원청 코드 -->
													<div class="col-xl-2">
														<label for="post_org_code" class="fs-6 fw-semibold my-2">
															<span class="required">지역교육지원청 코드</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<input type="text" name="param[org_code]" id="post_org_code" value="<?=$data['org_code'];?>" title="시도교육청 코드 입력하세요." class="form-control form-control-sm" placeholder="시도교육청 코드 입력하세요." size="20">
													</div>
													<!--// 지역교육지원청 코드 -->
												</div>

												<div class="row py-2 py-xl-4 comp_class" id="comp_class_5">
													<!-- 학교이름 -->
													<div class="col-xl-2">
														<label for="post_schul_name" class="fs-6 fw-semibold my-2">
															<span class="required">학교이름</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<input type="text" name="param[schul_name]" id="post_schul_name" value="<?=$data['schul_name'];?>" title="학교이름 입력하세요." class="form-control form-control-sm" placeholder="학교이름 입력하세요." size="20">
													</div>
													<!--// 학교이름 -->

													<!-- 학교코드 -->
													<div class="col-xl-2">
														<label for="post_schul_code" class="fs-6 fw-semibold my-2">
															<span class="required">학교코드</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<input type="text" name="param[schul_code]" id="post_schul_code" value="<?=$data['schul_code'];?>" title="학교코드 입력하세요." class="form-control form-control-sm" placeholder="학교코드 입력하세요." size="20">
													</div>
													<!--// 학교코드 -->
												</div>
												<?} ?>
												
												<? if($data['comp_class'] == 2){?>
												<div class="row py-2 py-xl-4 comp_class comp_class2">
													<!-- 업체명 -->
													<div class="col-xl-2">
														<label for="post_comp_name" class="fs-6 fw-semibold my-2">
															<span class="required">업체명</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<input type="text" name="param[comp_name]" id="post_comp_name" value="<?=$data['comp_name'];?>" title="업체명을 입력하세요." class="form-control form-control-sm" placeholder="업체명을 입력하세요." size="20">
													</div>
													<!--// 업체명 -->

													<!-- 업체명 -->
													<div class="col-xl-2">
														<label for="post_comp_class_sub" class="fs-6 fw-semibold my-2">
															<span class="required">업체분류</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<select name="param[comp_class_sub]" id="post_comp_class_sub" title="업체분류를 선택하세요"
															data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="업체분류를 선택하세요" 
															class="form-select form-select-sm"
														>
															<option value="">업체분류를 선택하세요</option>
														<?
															$class_where = " and code.view_yn = 'Y' and left(code.class_code, 2) != '01'";
															$class_list = company_class_data('list', $class_where, 'code.sort', '', '');
															
															foreach ($class_list as $class_k => $class_data)
															{
																if (is_array($class_data))
																{
																	$emp_str = str_repeat('&nbsp;', 4 * ($class_data['menu_depth'] - 1));
														?>
															<option value="<?=$class_data['code_idx'];?>" <?=selected($class_data['code_idx'], $data['comp_class_sub']);?>><?=$emp_str;?><?=$class_data['code_name'];?></option>
														<?
																}
															}
														?>
														</select>
													</div>
													
												</div>
												<?}?>

												<div class="row py-2 py-xl-4">
													<!-- 사업자등록번호 -->
													<div class="col-xl-2 pt-4 pt-xl-0">
														<label for="post_comp_num1" class="fs-6 fw-semibold my-2" >
															<span class="required">사업자등록번호(고유번호)</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<div class="d-flex flex-row flex-column-fluid w-md-50 w-xl-75">
															<div class="d-flex flex-row-fluid flex-center">
																<input type="text" name="param[comp_num1]" id="post_comp_num1" value="<?=$data['comp_num1'];?>" title="사업자등록번호(고유번호)를 모두 입력하세요." class="form-control form-control-sm" placeholder="" maxlength="6" aria-label="사업자등록번호 앞번호"/>
															</div>
															<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
																-
															</div>
															<div class="d-flex flex-row-fluid flex-center">
																<input type="text" name="param[comp_num2]" id="post_comp_num2" value="<?=$data['comp_num2'];?>" title="사업자등록번호(고유번호)를 모두 입력하세요." class="form-control form-control-sm" placeholder="" maxlength="6" aria-label="사업자등록번호 중간번호"/>
															</div>
															<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
																-
															</div>
															<div class="d-flex flex-row-fluid flex-center">
																<input type="text" name="param[comp_num3]" id="post_comp_num3" value="<?=$data['comp_num3'];?>" title="사업자등록번호(고유번호)를 모두 입력하세요." class="form-control form-control-sm" placeholder="" maxlength="6" aria-label="사업자등록번호 뒷번호"/>
															</div>
														</div>
													</div>
													<!--// 사업자등록번호 -->
												</div>

												

												<div class="row py-2 py-xl-4 comp_class comp_class2" style="display: none;">
													<!-- 대표자명 -->
													<div class="col-xl-2">
														<label for="post_boss_name" class="fs-6 fw-semibold my-2">
															<span class="required">대표자명</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<input type="text" name="param[boss_name]" id="post_boss_name" value="<?=$data['boss_name'];?>" title="대표자명을 입력하세요." class="form-control form-control-sm" placeholder="대표자명을 입력하세요." size="24">
													</div>
													<!--// 대표자명 -->

													<!-- 법인번호 -->
													<div class="col-xl-2 pt-4 pt-xl-0">
														<label for="post_distinct_num1" class="fs-6 fw-semibold my-2">
															<span class="">법인번호</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<div class="d-flex flex-row flex-column-fluid w-md-50 w-xl-75">
															<div class="d-flex flex-row-fluid flex-center">
																<input type="text" name="param[distinct_num1]" id="post_distinct_num1" value="<?=$data['distinct_num1'];?>" title="법인번호를 모두 입력하세요." class="form-control form-control-sm" placeholder="" maxlength="6" aria-label="법인번호 앞번호"/>
															</div>
															<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
																-
															</div>
															<div class="d-flex flex-row-fluid flex-center">
																<input type="text" name="param[distinct_num2]" id="post_distinct_num2" value="<?=$data['distinct_num2'];?>" title="법인번호를 모두 입력하세요." class="form-control form-control-sm" placeholder="" maxlength="6" aria-label="법인번호 뒷번호"/>
															</div>
														</div>
													</div>
													<!--// 법인번호 -->
												</div>

												<div class="row py-2 py-xl-4 comp_class comp_class2" style="display: none;">
													<!-- 업종 -->
													<div class="col-xl-2">
														<label for="post_upjong" class="fs-6 fw-semibold my-2">
															<span class="">업종</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<input type="text" name="param[upjong]" id="post_upjong" title="업종을 입력하세요." value="<?=$data['upjong'];?>" class="form-control form-control-sm" placeholder="업종을 입력하세요." size="24">
													</div>
													<!--// 업종 -->

													<!-- 업태 -->
													<div class="col-xl-2">
														<label for="post_uptae" class="fs-6 fw-semibold my-2">
															<span class="">업태</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<input type="text" name="param[uptae]" id="post_uptae" title="업태를 입력하세요." value="<?=$data['uptae'];?>" class="form-control form-control-sm" placeholder="업태 입력하세요." size="24">
													</div>
													<!--// 업태 -->
												</div>

												<!-- 사업장주소 -->
												<div class="row py-2 py-xl-4">
													<div class="col-xl-2">
														<label for="post_zip_code" class="fs-6 fw-semibold my-2">
															<span class="">사업장주소</span>
														</label>
													</div>
													<div class="col-xl-10 fv-row fv-plugins-icon-container">
														<div class="row gx-2">
															<div class="col-md-4 mb-2">
																<div class="position-relative d-flex align-items-center">
																	<input type="text"  name="param[zip_code]" id="post_zip_code" title="우편번호를 입력하세요." value="<?=$data['zip_code'];?>" class="form-control form-control-sm common_zip_code" aria-label="우편번호를 입력하세요." placeholder="우편번호" maxlength="4">
																	<button type="button" class="btn btn-sm btn-dark position-absolute end-0 px-6 rounded-start-0" data-bs-toggle="modal" data-bs-target="#pop-postcode" data-form-id="form-schoolinfo">우편번호찾기</button>
																</div>
															</div>
															<div class="col-8 d-none d-md-block"></div>
															<div class="col-md-6 mb-2 mb-md-0">
																<input type="text"  name="param[address1]" id="post_address1" value="<?=$data['address1'];?>" title="사업장 주소 입력하세요." class="form-control form-control-sm common_address1" aria-label="주소를 입력하세요." placeholder="주소를 입력하세요." maxlength="80">
															</div>
															<div class="col-md-6">
																<input type="text" name="param[address2]" id="post_address2" value="<?=$data['address2'];?>" title="사업장 상세주소 입력하세요." class="form-control form-control-sm common_address2" aria-label="상세주소를 입력하세요." placeholder="상세주소를 입력하세요." maxlength="80">
															</div>
														</div>
													</div>
												</div>
												<!--// 사업장주소 -->

												<!-- 홈페이지 주소 -->
												<div class="row py-2 py-xl-4">
													<div class="col-xl-2">
														<label for="post_home_page" class="fs-6 fw-semibold my-2">
															<span class="">홈페이지 주소</span>
														</label>
													</div>
													<div class="col-xl-10 fv-row fv-plugins-icon-container">
														<input type="text" name="param[home_page]" id="post_home_page" value="<?=$data['home_page'];?>" title="홈페이지 주소를 입력하세요." class="form-control form-control-sm common_address2" aria-label="홈페이지 주소를 입력하세요." placeholder="홈페이지 주소를 입력하세요." maxlength="80">
													</div>
												</div>
												<!--// 사업장주소 -->

												<div class="row py-2 py-xl-4">
													<!-- 전화번호 -->
													<div class="col-xl-2">
														<label for="post_tel_num1" class="fs-6 fw-semibold my-2">
															<span class="required">전화번호</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<div class="d-flex flex-row flex-column-fluid w-md-50 w-xl-100">
															<div class="d-flex flex-row-fluid flex-center">
																<select name="param[tel_num1]" id="post_tel_num1" data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="전화번호 앞자리" class="form-select form-select-sm">
																	<option value="">선택</option>
																	<?foreach($set_telephone as $key => $val){?>
																		<option value="<?=$key?>" <?=$key==$data['tel_num1']?"selected":""?>><?=$val?></option>
																	<?}?>
																</select>
															</div>
															<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
																-
															</div>
															<div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
																<input type="text" name="param[tel_num2]" id="post_tel_num2" value="<?=$data['tel_num2'];?>" title="전화번호를 모두 입력하세요." class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="전화번호 중간번호"/>
															</div>
															<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
																-
															</div>
															<div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
																<input type="text" name="param[tel_num3]" id="post_tel_num3" value="<?=$data['tel_num3'];?>" title="전화번호를 모두 입력하세요." class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="전화번호 끝번호"/>
															</div>
														</div>
													</div>
													<!--// 전화번호 -->


													<!-- 팩스번호 -->
													<div class="col-xl-2 pt-4 pt-xl-0">
														<label for="post_fax_num1" class="fs-6 fw-semibold my-2">
															<span class="">팩스번호</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<div class="d-flex flex-row flex-column-fluid w-md-50 w-xl-100">
															<div class="d-flex flex-row-fluid flex-center">
																<select name="param[fax_num1]" id="post_fax_num1" title="팩스번호 앞자리를 선택하세요." data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="팩스번호 앞자리" class="form-select form-select-sm">
																	<option value="">선택</option>
																	<?foreach($set_telephone as $key => $val){?>
																		<option value="<?=$key?>" <?=$key==$data['fax_num1']?"selected":""?>><?=$val?></option>
																	<?}?>
																</select>
															</div>
															<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
																-
															</div>
															<div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
																<input type="text" name="param[fax_num2]" id="post_fax_num2" title="팩스번호를 모두 입력하세요." value="<?=$data['fax_num2'];?>" class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="팩스번호 중간번호"/>
															</div>
															<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
																-
															</div>
															<div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
																<input type="text" name="param[fax_num3]" id="post_fax_num3" title="팩스번호를 모두 입력하세요." value="<?=$data['fax_num3'];?>" class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="팩스번호 끝번호"/>
															</div>
														</div>
													</div>
													<!--// 팩스번호 -->
												</div>

												<!-- 이메일 -->
												<div class="row py-2 py-xl-4">
													<div class="col-xl-2">
														<div for="post_comp_email1" class="fs-6 fw-semibold my-2">
															<span class="required">이메일</span>
														</div>
													</div>
													<div class="col-xl-10 fv-row fv-plugins-icon-container">
														<div class="row gx-2 w-xl-75">
															<div class="col-6 col-md-4">
																<input type="text" name="param[comp_email1]" id="post_comp_email1" title="이메일 아이디를 입력하세요." value="<?=$data['comp_email1'];?>" class="form-control form-control-sm" aria-label="이메일 아이디를 입력하세요." placeholder="이메일 아이디를 입력하세요." maxlength="30">
															</div>
															<div class="col-6 col-md-3">
																<input type="text" name="param[comp_email2]" id="post_comp_email2" title="이메일 주소를 입력하세요." value="<?=$data['comp_email2'];?>" class="form-control form-control-sm common_email2" aria-label="이메일 주소를 입력하세요." placeholder="이메일 주소를 입력하세요." maxlength="40">
															</div>
															<div class="col-md-5 mt-2 mt-md-0">
																<div class="position-relative d-flex align-items-center">
																	<select name="user_email3" data-control="select2" data-hide-search="true" data-placeholder="이메일 선택" aria-label="이메일 선택하세요" class="form-select form-select-sm common_email3">
																		<option value="">이메일 선택하세요</option>
																		<?foreach($set_email_domain as $key => $val){?>
																		<option value="<?=$key?>"><?=$val?></option>
																		<?}?>
																	</select>
																</div>
															</div>
														</div>
													</div>
												</div>
												<!--// 이메일 -->

												<div class="row py-2 py-xl-4">
													<!-- 담당자 -->
													<div class="col-xl-2">
														<label for="post_charge_name" class="fs-6 fw-semibold my-2">
															<span class="">담당자</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<input type="text" name="param[charge_name]" id="post_charge_name" title="담당자를 입력하세요." value="<?=$data['charge_name'];?>" class="form-control form-control-sm" placeholder="담당자를 입력하세요." size="20">
													</div>
													<!--// 담당자 -->

													<!-- 휴대전화번호 -->
													<div class="col-xl-2">
														<label for="post_hp_num1" class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
															<span class="">휴대전화번호</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<div class="d-flex flex-row flex-column-fluid w-md-50 w-xl-100">
															<div class="d-flex flex-row-fluid flex-center">
																<select name="param[hp_num1]" id="post_hp_num1" title="핸드폰 번호 앞자리를 선택하세요" data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="휴대전화번호 앞자리" class="form-select form-select-sm">
																	<option value="">없음</option>
																	<?foreach($set_cellular as $key => $val){?>
																		<option value="<?=$key?>" <?=$key==$data['hp_num1']?"selected":""?>><?=$val?></option>
																	<?}?>
																</select>
															</div>
															<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
																-
															</div>
															<div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
																<input type="text" name="param[hp_num2]" id="post_hp_num2" itle="전화번호를 모두 입력하세요." value="<?=$data['hp_num2'];?>" class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="휴대전화번호 중간번호"/>
															</div>
															<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
																-
															</div>
															<div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
																<input type="text" name="param[hp_num3]" id="post_hp_num3" title="전화번호를 모두 입력하세요." value="<?=$data['hp_num3'];?>" class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="휴대전화번호 끝번호"/>
															</div>
														</div>
													</div>
													<!--// 휴대전화번호 -->
												</div>

												<div class="row py-2 py-xl-4">
													<!-- 메뉴 설정 -->
													<div class="col-xl-2">
														<label for="post_start_date" class="fs-6 fw-semibold my-2">
															<span class="required">메뉴설정</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
													<?														
														if($data['menu_code'])
														{
															$where = "";
															$where = " and menu_code='" . $data['menu_code'] . "'";
															$query_string = "select menu_name, menu_code from menu_info where menu_depth = 1 " . $where . " order by menu_code";
															$menu_data = query_view($query_string);
													?>
														<input type="text" name="param[menu_code]" id="post_menu_code" title="메뉴설정 입력하세요." value="<?=$menu_data['menu_name'];?>" class="form-control form-control-sm"  aria-label="메뉴분류" disabled/>
													<?
														}
														else
														{
															$query_string = "select menu_name, menu_code from menu_info where menu_depth = 1 order by menu_code";
															$data_sql['query_string'] = $query_string;
															$menu_data = query_list($data_sql);
														
													?>
														<select name="param[menu_code]" id="post_menu_code" title="메뉴설정 선택하세요" data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="메뉴설정 선택하세요" class="form-select form-select-sm">
															<option value="">없음</option>
													<?
															foreach($menu_data as $mkey => $mlist){
																if(is_array($mlist)){
													?>
																<option value="<?=$mlist['menu_code']?>" <?=$mlist['menu_code'] == $data['menu_code']?"selected":""?>><?=$mlist['menu_name']?></option>
													<?			}
															}
													?>
														</select>
													<? } ?>
													</div>
													<!--// 메뉴 설정 -->

													<!-- 거래처 분류 설정 -->
													<div class="col-xl-2">
														<label for="post_clent_group_code" class="fs-6 fw-semibold my-2">
															<span class="required">거래처분류설정</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
													<?														
														if($data['clent_group_code'])
														{
															$where = "";
															$where = " and import_yn='Y' and group_code='" . $data['clent_group_code'] . "'";
															$query_string = "select group_name, group_code from company_client_group where menu_depth = 1 " . $where . " order by group_code";
															$clent_group_data = query_view($query_string);
													?>
														<input type="text" name="param[clent_group_code]" id="post_clent_group_code" title="거래처 분류 설정 선택하세요." value="<?=$clent_group_data['group_name'];?>" class="form-control form-control-sm"  aria-label="거래처분류" disabled/>
													<?
														}
														else
														{
															$query_string = "select group_name, group_code from company_client_group where menu_depth = 1 and import_yn='Y' order by group_code ";
															$data_sql['query_string'] = $query_string;
															$clent_group_data = query_list($data_sql);
														
													?>
														<select name="param[clent_group_code]" id="post_clent_group_code" title="거래처 분류 설정 선택하세요" data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="거래처 분류 설정 선택하세요" class="form-select form-select-sm">
															<option value="">없음</option>
													<?
															foreach($clent_group_data as $ckey => $clist){
																if(is_array($clist)){
													?>
																<option value="<?=$clist['group_code']?>" <?=$clist['group_code'] == $data['clent_group_code']?"selected":""?>><?=$clist['group_name']?></option>
													<?			}
															}
													?>
														</select>
													<? } ?>
													</div>
													<!--// 메뉴 설정 -->

													
												</div>


												<div class="row py-2 py-xl-4">
													<!-- 시작일 -->
													<div class="col-xl-2">
														<label for="post_start_date" class="fs-6 fw-semibold my-2">
															<span class="required">시작일</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<input type="text" name="param[start_date]" id="post_start_date" title="시작일을 입력하세요." value="<?=date_replace($data['start_date'], 'Y-m-d');?>" readonly="readonly" class="form-control form-control-sm flatpickr-input-single" placeholder="시작일을 입력하세요." size="20">
													</div>
													<!--// 시작일 -->

													<!-- 종료일 -->
													<div class="col-xl-2">
														<label for="post_end_date" class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
															<span class="required">종료일</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<input type="text" name="param[end_date]" id="post_end_date" title="종료일을 입력하세요." value="<?=date_replace($data['end_date'], 'Y-m-d');?>" readonly="readonly" class="form-control form-control-sm flatpickr-input-single" placeholder="종료일을 입력하세요." size="20">
													</div>
													<!--// 종료일 -->
												</div>

												<div class="row py-2 py-xl-4">
													<!-- 보기여부 -->
													<div class="col-xl-2">
														<label for="post_view_yn" class="fs-6 fw-semibold my-2">
															<span class="">보기여부</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
															<input class="form-check-input w-25px h-15px w-xl-30px h-xl-20px" type="checkbox" name="param[view_yn]" id="post_view_yn" value="Y" <?=$data["view_yn"]=='Y'?'checked="checked"':""?> />
														</div>
													</div>
													<!--// 보기여부 -->	
													
													<!-- 보기여부 -->
													<div class="col-xl-2">
														<label for="post_auth_yn" class="fs-6 fw-semibold my-2">
															<span class="">승인여부</span>
														</label>
													</div>
													<div class="col-xl-4 fv-row fv-plugins-icon-container">
														<div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
															<input class="form-check-input w-25px h-15px w-xl-30px h-xl-20px" type="checkbox" name="param[auth_yn]" id="post_auth_yn" value="Y" <?=$data["auth_yn"]=='Y'?'checked="checked" disabled':""?> />
														</div>
													</div>
													<!--// 보기여부 -->		
												</div>

												<div class="separator separator-dashed mt-4 mt-xl-0 mb-6 mb-lg-8"></div>
												<div class="mb-8 mb-lg-10 text-end">
													<button type="button" class="btn btn-sm btn-secondary" onclick="data_list_open()">
														<i class="ki-outline ki-arrows-circle fs-6"></i> 취소
													</button>
													<?
														if ($comp_idx == '') {
													?>
													<input type="hidden" name="sub_type" value="post" />
													<button type="button" class="btn btn-sm btn-warning" onclick="return check_form()" >
														<i class="ki-outline ki-pencil fs-6"></i> 등록
													</button>
													<?
														}
														else
														{
													?>
													<input type="hidden" name="sub_type" value="modify" />
													<input type="hidden" name="comp_idx" value="<?=$comp_idx;?>" />
													<button type="button" class="btn btn-sm btn-warning" onclick="return check_form()" >
														<i class="ki-outline ki-pencil fs-6"></i> 수정
													</button>
													<?

														}
													?>
												</div>
											</form>
											<!-- //업체정보 -->

<script type="text/javascript">
//<![CDATA[
	

	$('#comp_category').change( function(){
		let comp_class = $("#comp_category option:checked").val();		
		let class_code = $("#comp_category option:checked").attr('date-class-code');
		let code = 0;
		
		if(class_code != "")
		{		
			code = parseInt(class_code.substr(0, 2));		
		}

		if(comp_class != "") 
		{
			$(".comp_class").hide();
			if(comp_class == "2")
			{
				$(".comp_class2").show();
			}
			else if(comp_class == "3")
			{	
				
				$("#comp_class_3").show();
				$("#comp_class_4").hide();
				$("#comp_class_5").hide();
				$(".comp_class2").hide();
			} 
			else if(comp_class == "4")
			{
				$(".comp_class").hide();
				$("#comp_class_3").show();
				$("#comp_class_4").show();
				$("#comp_class_5").hide();
				$(".comp_class2").hide();
			} 
			else
			{	
				$(".comp_class").hide();
				$("#comp_class_3").show();
				$("#comp_class_4").show();
				$("#comp_class_5").show();
				$(".comp_class2").hide();
			}

		}

		$('#post_comp_class').val(code);
		
	});
//]]>
</script>
<?
	}
?>