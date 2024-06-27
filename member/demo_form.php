<?PHP
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";

	$demo_type = !string_input($_POST['demo_type']) ? 'school' : $_POST['demo_type'];

	// 서비스약관
	$page_where = " and pi.menu_code = 'agree_{$demo_type}'";
	$page_data = page_info_data('view', $page_where);
	$use_rule = $page_data['remark'];
?>
						<!-- Tab 1: 학교 -->
						<div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
							<form action="<?=$this_page;?>" id="popup_joinform" name="popup_joinform" class="form" method="post" onsubmit="return check_regist()">
								<input type="hidden" name="sub_type" id="post_sub_type" value="reg_post" />
								<input type="hidden" name="param[demo_type]" id="post_demo_type" value="<?=$demo_type?>" />
								<input type="hidden" name="post_mem_email_chk" id="post_mem_email_chk" value="N" />
								<input type="hidden" name="post_comp_num_chk" id="post_comp_num_chk" value="N" />
								<div class="scroll-y h-175px border rounded-1 p-6 fs-8 text-gray-600 ls-n3 bg-gray-100">
									<?php
										echo $use_rule;
									?>
								</div>

								<div class="form-check form-check-custom form-check-sm my-4">
									<input class="form-check-input" type="checkbox" value="1" id="agree_check" title="약관에 동의해 주셔야만 데모신청을 하실 수 있습니다."/>
									<label class="form-check-label" for="agree_check">
										약관에 동의합니다.
									</label>
								</div>

								<?if($demo_type=='school'){?>
								<div class="row pb-2">
									
									<div class="col-xl-3">
										<div class="fs-7 fw-semibold my-3">
											<span class="required">학교선택</span>
										</div>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<div class="d-flex flex-row flex-column-fluid">
											<div class="d-flex flex-row-fluid flex-center">
											<input type="hidden" id="post_sc_name" name="param[sc_name]" value="" />
												<select id="post_sc_code" name="param[sc_code]" title="시도교육청 선택하세요."  class="form-select form-select-sm" onchange="school_info_data($(this).val())">
													<option value="">도교육성선택</option>
													<option value="B10">서울특별시교육청</option>
													<option value="C10">부산광역시교육청</option>
													<option value="D10">대구광역시교육청</option>
													<option value="E10">인천광역시교육청</option>
													<option value="F10">광주광역시교육청</option>
													<option value="G10">대전광역시교육청</option>
													<option value="H10">울산광역시교육청</option>
													<option value="I10">세종특별자치시교육청</option>
													<option value="J10">경기도교육청</option>
													<option value="K10">강원특별자치도교육청</option>
													<option value="M10">충청북도교육청</option>
													<option value="N10">충청남도교육청</option>
													<option value="P10">전라북도교육청</option>
													<option value="Q10">전라남도교육청</option>
													<option value="R10">경상북도교육청</option>
													<option value="S10">경상남도교육청</option>
													<option value="T10">제주특별자치도교육청</option>
												</select>
											</div>
											<div class="d-flex flex-row-fluid flex-center">
												<input type="hidden" id="post_schul_name" name="param[schul_name]" value="" />
												<select id="post_schul_code" name="param[schul_code]" title="학교 선택하세요." class="form-select form-select-sm">
													<option value="">학교선택</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								
								<div class="row py-2">
									<div class="col-xl-3">
										<label for="post_charge_name" class="fs-7 fw-semibold my-3">
											<span class="required">신청자명</span>
										</label>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<input type="text" name="param[charge_name]" id="post_charge_name" class="form-control form-control-sm maxlength" title="신청자명을 입력하세요." placeholder="신청자명을 입력하세요."  maxlength="20" value="">
									</div>
								</div>
								<?}?>

								<?if($demo_type=='company'){?>
								<div class="row pb-2">
									<div class="col-xl-3">
										<label for="post_comp_name" class="fs-7 fw-semibold my-3">
											<span class="required">상호명</span>
										</label>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<input type="text" name="param[comp_name]" id="post_comp_name" class="form-control form-control-sm maxlength" title="상호명을 입력하세요." placeholder="상호명을 입력하세요."  maxlength="50" value="">
									</div>
								</div>

								<div class="row py-2">
									<div class="col-xl-3">
										<label for="post_boss_name" class="fs-7 fw-semibold my-3">
											<span class="required">대표자명</span>
										</label>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<input type="text" name="param[boss_name]" id="post_boss_name" class="form-control form-control-sm maxlength" title="대표자명을 입력하세요." placeholder="대표자명을 입력하세요."  maxlength="10" value="">
									</div>
								</div>
								
								<div class="row py-2">
									<div class="col-xl-3">
										<label for="post_comp_num1" class="fs-7 fw-semibold my-3">
											<span class="required">사업자등록번호</span>
										</label>
									</div>
									<div class="d-flex flex-row flex-column-fluid">
											
												<input type="text" name="param[comp_num1]" id="post_comp_num1" title="업자 등록번호를 입력하세요." />
											
												<input type="text" name="param[comp_num2]" id="post_comp_num2" title="업자 등록번호를 입력하세요."/>
											
												<input type="text" name="param[comp_num3]" id="post_comp_num3" title="업자 등록번호1를 입력하세요." />
											
									</div>
									<div><button type="button" onclick="double_comp_num_chk('On');">중복확인</button></div>
								</div>

								<div class="row py-2">
									<div class="col-xl-3">
										<label for="post_upjong" class="fs-7 fw-semibold my-3">
											<span class="required">업종</span>
										</label>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<input type="text" name="param[upjong]" id="post_upjong" class="form-control form-control-sm maxlength" title="업종을 입력하세요." placeholder="업종을 입력하세요."  maxlength="30" value="">
									</div>
								</div>

								<div class="row py-2">
									<div class="col-xl-3">
										<label for="post_uptae" class="fs-7 fw-semibold my-3">
											<span class="required">업태</span>
										</label>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<input type="text" name="param[uptae]" id="post_uptae" class="form-control form-control-sm maxlength" title="업태를 입력하세요."  placeholder="업태를 입력하세요."  maxlength="30" value="">
									</div>
								</div>
								
								<?}?>

								<div class="row py-2">
									<div class="col-xl-3">
										<label for="post_tel_num1" class="fs-7 fw-semibold my-3">
											<span class="required">전화번호</span>
										</label>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<div class="d-flex flex-row flex-column-fluid">
											<div class="d-flex flex-row-fluid flex-center">												
												<select name="param[tel_num1]" id="post_tel_num1" title="전화번호 앞자리를 선택하세요." data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="전화번호 앞자리" class="form-select form-select-sm">
													<option value="">선택</option>
													<?foreach($set_telephone as $key => $val){?>
														<option value="<?=$key?>"><?=$val?></option>
													<?}?>
												</select>
											</div>
											<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
												-
											</div>
											<div class="d-flex flex-row-auto w-75px flex-center">
												<input type="text" name="param[tel_num2]" id="post_tel_num2" title="전화번호 중간번호를 입력하세요." class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="전화번호 중간번호"/>
											</div>
											<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
												-
											</div>
											<div class="d-flex flex-row-auto w-75px flex-center">
												<input type="text" name="param[tel_num3]" id="post_tel_num3" title="전화번호 끝번호를 입력하세요." class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="전화번호 끝번호"/>
											</div>
										</div>
									</div>
								</div>

								<div class="row py-2">
									<div class="col-xl-3">
										<label for="post_hp_num1" class="fs-7 fw-semibold my-3">
											<span class="required">휴대전화번호</span>
										</label>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<div class="d-flex flex-row flex-column-fluid">
											<div class="d-flex flex-row-fluid flex-center">
												<select name="param[hp_num1]" id="post_hp_num1" title="휴대전화번호 앞자리를 선택하세요." data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="휴대전화번호 앞자리" class="form-select form-select-sm">													
													<?foreach($set_cellular as $key => $val){?>
														<option value="<?=$key?>"><?=$val?></option>
													<?}?>
												</select>
											</div>
											<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
												-
											</div>
											<div class="d-flex flex-row-auto w-75px flex-center">
												<input type="text" name="param[hp_num2]" id="post_hp_num2" title="휴대전화번호 중간번호를 입력하세요." class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="휴대전화번호 중간번호"/>
											</div>
											<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
												-
											</div>
											<div class="d-flex flex-row-auto w-75px flex-center">
												<input type="text" name="param[hp_num3]" id="post_hp_num3" title="휴대전화번호 끝번호를 입력하세요." class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="휴대전화번호 끝번호"/>
											</div>
										</div>
									</div>
								</div>

								<div class="row py-2">
									<div class="col-xl-3">
										<label for="post_mem_email1" class="fs-7 fw-semibold my-3">
											<span class="required">이메일</span>
										</label>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<div class="d-flex flex-row flex-column-fluid">
											<div class="d-flex flex-row-fluid flex-center">
												<input type="text" name="param[mem_email1]" id="post_mem_email1" title="이메일 아이디를 입력하세요." class="form-control form-control-sm" aria-label="이메일 아이디를 입력하세요." placeholder="" maxlength="30">
											</div>
											<div class="d-flex flex-row-auto w-25px flex-center text-gray-400">
												@
											</div>
											<div class="d-flex flex-row-fluid flex-center">
												<input type="text" name="param[mem_email2]" id="post_mem_email2" title="이메일 주소를 입력하세요." class="form-control form-control-sm common_email2" aria-label="이메일 주소를 입력하세요." placeholder="" maxlength="40">
											</div>
										</div>
										<div class="position-relative d-flex align-items-center pe-24 mt-2">
											<select name="user_email3" data-control="select2" data-hide-search="true" data-placeholder="이메일 선택" aria-label="이메일 선택하세요" class="form-select form-select-sm common_email3">
												<option value="이메일 선택하세요" selected="selected">이메일 선택하세요</option>
												<option value="naver.com">naver.com</option>
												<option value="google.com">google.com</option>
												<option value="hanmail.net">hanmail.net</option>
												<option value="nate.com">nate.com</option>
												<option value="kakao.com">kakao.com</option>
												<option value="직접입력">직접입력</option>
											</select>
											<button type="button" class="btn btn-sm btn-dark border-0 position-absolute end-0 px-6 btn_email_chk" aria-label="검색" onclick="double_email_chk('On');">중복확인</button>
										</div>
									</div>
								</div>

								<div class="row py-3">
									<div class="col-xl-3">
										<label for="post_zip_code" class="fs-7 fw-semibold my-3">
											<span class="required">주소</span>
										</label>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<div class="position-relative d-flex align-items-center">
											<input type="text" name="param[zip_code]" id="post_zip_code" title="우편번호 입력하세요." class="form-control form-control-sm common_zip_code" aria-label="우편번호 입력하세요." placeholder="우편번호" maxlength="4" readonly>
											<button type="button" class="btn btn-sm btn-dark position-absolute end-0 px-6 rounded-start-0" data-bs-toggle="modal" data-bs-target="#pop-postcode" data-form-id="popup_joinform">우편번호찾기</button>
										</div>
										<div class="d-flex flex-row flex-column-fluid mt-2">
											<div class="d-flex flex-row-fluid flex-center">
												<input type="text" name="param[address1]" id="post_address1" title="주소를 입력하세요." class="form-control form-control-sm common_address1" aria-label="주소를 입력하세요." placeholder="주소를 입력하세요." maxlength="80" readonly>
											</div>
										</div>
										<div class="d-flex flex-row flex-column-fluid mt-2">
											<div class="d-flex flex-row-fluid flex-center">
												<input type="text" name="param[address2]" id="post_address2" title="상세주소를 입력하세요." class="form-control form-control-sm common_address2" aria-label="상세주소를 입력하세요." placeholder="상세주소를 입력하세요." maxlength="80">
											</div>
										</div>
									</div>
								</div>

								<div class="separator separator-dashed mt-2 mt-lg-4 mb-6 mb-lg-8"></div>
								<div class="row mb-8 mb-lg-10">
									<div class="col-6">
										<button type="button" class="btn btn-sm btn-flex btn-secondary" id="kt_demo_cancel">
											<i class="ki-outline ki-burger-menu fs-6"></i> 취소
										</button>
									</div>
									<div class="col-6 text-end">
										<button type="button" onclick="check_regist();" class="btn btn-sm btn-flex btn-warning">
											<i class="ki-outline ki-pencil fs-6"></i> 신청
										</button>
									</div>
								</div>
							</form>
						</div>