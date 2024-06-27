<?PHP
// 서비스약관
$page_where = " and pi.menu_code = 'agree_sch'";
$page_data = page_info_data('view', $page_where);
$use_rule = $page_data['remark'];
?>
						<!-- Tab 1: 학교 -->
						<div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
							<form action="<?=$this_page;?>" id="popup_joinform" name="popup_joinform" class="form" method="post">
								<input type="hidden" name="sub_type" id="post_sub_type" value="reg_post" />
								<input type="hidden" name="param[demo_class]" id="post_demo_class" value="school" />
								<input type="hidden" name="common_email_chk" value="N" />
								<div class="scroll-y h-175px border rounded-1 p-6 fs-8 text-gray-600 ls-n3 bg-gray-100">
									<?php
										echo $use_rule;
									?>
								</div>

								<div class="form-check form-check-custom form-check-sm my-4">
									<input class="form-check-input" type="checkbox" value="1" id="priority1"/>
									<label class="form-check-label" for="priority1">
										약관에 동의합니다.
									</label>
								</div>

								<div class="row pb-2">
									<div class="col-xl-3">
										<div class="fs-7 fw-semibold my-3">
											<span class="required">학교선택</span>
										</div>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<div class="d-flex flex-row flex-column-fluid">
											<div class="d-flex flex-row-fluid flex-center">
											<input type="hidden" id="post_sc_name" name="param[sc_name]" value="N" />
												<select id="sc_code" name="param[sc_code]" title="시도교육청 선택"  class="form-select form-select-sm" onchange="school_info_data($(this).val())">
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
												<input type="hidden" id="post_schul_name" name="param[schul_name]" value="N" />
												<select id="schul_code" name="post_param[schul_code]" title="학교 선택" class="form-select form-select-sm">
													<option value="">학교선택</option>
												</select>
											</div>
										</div>
									</div>
								</div>

								<div class="row py-2">
									<div class="col-xl-3">
										<label for="school_manager" class="fs-7 fw-semibold my-3">
											<span class="required">신청자명</span>
										</label>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<input type="text" name="param[charge_name]" id="post_charge_name" class="form-control form-control-sm maxlength" placeholder="신청자명을 입력하세요."  maxlength="20" value="">
									</div>
								</div>

								<div class="row py-2">
									<div class="col-xl-3">
										<label for="school_tel_num1" class="fs-7 fw-semibold my-3">
											<span class="required">전화번호</span>
										</label>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<div class="d-flex flex-row flex-column-fluid">
											<div class="d-flex flex-row-fluid flex-center">												
												<select name="param[tel_num1]" id="post_tel_num1" data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="전화번호 앞자리" class="form-select form-select-sm">
													<option value="">없음</option>
													<?foreach($set_telephone as $key => $val){?>
														<option value="<?=$key?>"><?=$val?></option>
													<?}?>
												</select>
											</div>
											<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
												-
											</div>
											<div class="d-flex flex-row-auto w-75px flex-center">
												<input type="text" name="school_tel_num2" id="school_tel_num2" class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="전화번호 중간번호"/>
											</div>
											<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
												-
											</div>
											<div class="d-flex flex-row-auto w-75px flex-center">
												<input type="text" name="school_tel_num3" id="school_tel_num3" class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="전화번호 끝번호"/>
											</div>
										</div>
									</div>
								</div>

								<div class="row py-2">
									<div class="col-xl-3">
										<label for="school_hp_num1" class="fs-7 fw-semibold my-3">
											<span class="required">휴대전화번호</span>
										</label>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<div class="d-flex flex-row flex-column-fluid">
											<div class="d-flex flex-row-fluid flex-center">
												<select name="param[hp_num1]" id="post_hp_num1" data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="휴대전화번호 앞자리" class="form-select form-select-sm">
													<option value="">없음</option>
													<?foreach($set_cellular as $key => $val){?>
														<option value="<?=$key?>"><?=$val?></option>
													<?}?>
												</select>
											</div>
											<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
												-
											</div>
											<div class="d-flex flex-row-auto w-75px flex-center">
												<input type="text" name="param[hp_num2]" id="post_hp_num2" class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="휴대전화번호 중간번호"/>
											</div>
											<div class="d-flex flex-row-auto w-20px flex-center text-gray-400">
												-
											</div>
											<div class="d-flex flex-row-auto w-75px flex-center">
												<input type="text" name="param[hp_num3]" id="post_hp_num3" class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="휴대전화번호 끝번호"/>
											</div>
										</div>
									</div>
								</div>

								<div class="row py-2">
									<div class="col-xl-3">
										<label for="school_email1" class="fs-7 fw-semibold my-3">
											<span class="required">이메일</span>
										</label>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<div class="d-flex flex-row flex-column-fluid">
											<div class="d-flex flex-row-fluid flex-center">
												<input type="text" name="param[mem_email1]" id="post_mem_email1" class="form-control form-control-sm" aria-label="이메일 아이디를 입력하세요." placeholder="" maxlength="30">
											</div>
											<div class="d-flex flex-row-auto w-25px flex-center text-gray-400">
												@
											</div>
											<div class="d-flex flex-row-fluid flex-center">
												<input type="text" name="param[mem_email2]" id="post_mem_email2" class="form-control form-control-sm common_email2" aria-label="이메일 주소를 입력하세요." placeholder="" maxlength="40">
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
											<button type="button" class="btn btn-sm btn-dark border-0 position-absolute end-0 px-6 btn_email_chk" aria-label="검색" onclick="double_email_chk();">중복확인</button>
										</div>
									</div>
								</div>

								<div class="row py-3">
									<div class="col-xl-3">
										<label for="school_zip_code" class="fs-7 fw-semibold my-3">
											<span class="required">주소</span>
										</label>
									</div>
									<div class="col-xl-9 fv-row fv-plugins-icon-container">
										<div class="position-relative d-flex align-items-center">
											<input type="text" name="param[zip_code]" id="post_zip_code" class="form-control form-control-sm common_zip_code" aria-label="우편번호 앞자리를 입력하세요." placeholder="우편번호" maxlength="4" disabled="disabled">
											<button type="button" class="btn btn-sm btn-dark position-absolute end-0 px-6 rounded-start-0" data-bs-toggle="modal" data-bs-target="#pop-postcode" data-form-id="form-school">우편번호찾기</button>
										</div>
										<div class="d-flex flex-row flex-column-fluid mt-2">
											<div class="d-flex flex-row-fluid flex-center">
												<input type="text" name="param[address1]" id="post_address1" class="form-control form-control-sm common_address1" aria-label="주소를 입력하세요." placeholder="주소를 입력하세요." maxlength="80" disabled="disabled">
											</div>
										</div>
										<div class="d-flex flex-row flex-column-fluid mt-2">
											<div class="d-flex flex-row-fluid flex-center">
												<input type="text" name="param[address2]" id="post_address2" class="form-control form-control-sm common_address2" aria-label="상세주소를 입력하세요." placeholder="상세주소를 입력하세요." maxlength="80">
											</div>
										</div>
									</div>
								</div>

								<div class="separator separator-dashed mt-2 mt-lg-4 mb-6 mb-lg-8"></div>
								<div class="row mb-8 mb-lg-10">
									<div class="col-6">
										<button type="button" class="btn btn-sm btn-flex btn-secondary">
											<i class="ki-outline ki-burger-menu fs-6"></i> 취소
										</button>
									</div>
									<div class="col-6 text-end">
										<button type="button" onclick="handleFormSubmission('#popup_joinform', '#priority1')" class="btn btn-sm btn-flex btn-warning">
											<i class="ki-outline ki-pencil fs-6"></i> 신청
										</button>
									</div>
								</div>
							</form>
						</div>
