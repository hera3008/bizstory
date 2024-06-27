<?
/*
	수정 : 2013.03.26
	위치 : 설정관리 > 회사관리 > 회사정보
*/
	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	//$code_part  = search_company_part($code_part);
	$code_part  = '';

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
	$link_ok = $local_dir . "/bizstory/comp_set/company_ok.php"; // 저장


//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 기관 정보 조회

	$where = " and comp.comp_idx = '" . $code_comp . "'";
	$data = company_info_data('view', $where);

	$comp_num = $data['comp_num'];
	$comp_num_arr = explode('-', $comp_num);
	$data['comp_num1'] = $comp_num_arr[0];
	$data['comp_num2'] = $comp_num_arr[1];
	$data['comp_num3'] = $comp_num_arr[2];

	$distinct_num = $data['distinct_num'];
	$distinct_num_arr = explode('-', $distinct_num);
	$data['distinct_num1'] = $distinct_num_arr[0];
	$data['distinct_num2'] = $distinct_num_arr[1];

	$zip_code = $data['zip_code'];
	$zip_code_arr = explode('-', $zip_code);
	$data['zip_code1'] = $zip_code_arr[0];
	$data['zip_code2'] = $zip_code_arr[1];

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
                                            <h4 class="fs-1">학교정보</h4>
                                        </div>
                                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                            <ol
                                                class="breadcrumb breadcrumb-dot text-muted fs-8 fs-md-7 d-none d-md-inline-flex">
                                                <li class="breadcrumb-item">홈</li>
                                                <li class="breadcrumb-item">설정관리</li>
                                                <li class="breadcrumb-item text-gray-700">학교정보</li>
                                            </ol>
                                        </div>
                                    </div>
                                    <div class="card-body px-6 px-lg-9 py-2 py-lg-3">

										<!-- 기관정보 -->
                                        <form id="postform" name="postform" method="post" class="form" action="<?=$this_page;?>" onsubmit="return check_form()">
											<?=$form_all;?>
											<input type="hidden" name="sub_type" value="modify" />
                                            <div class="row py-2 py-xl-3">
                                                <!-- 상호명 -->
                                                <div class="col-xl-2 border-md-bottom pb-xl-6">
                                                    <label for="post_comp_name" class="fs-6 fw-semibold my-2">
                                                        <span class="required">기관명</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 border-bottom pb-6">
                                                    <input type="text" name="param[comp_name]" id="post_comp_name"
                                                        class="form-control form-control-sm" placeholder="기관명을 입력하세요."
                                                        size="30" value="<?=$data['comp_name']?>">
                                                </div>
                                                <!--// 상호명 -->

                                                <!-- 사업자등록번호 -->
                                                <div class="col-xl-2 pt-4 pt-xl-0 border-md-bottom pb-xl-6">
                                                    <label for="post_comp_num1" class="fs-6 fw-semibold my-2">
                                                        <span class="required">사업자등록번호</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 border-bottom pb-6">
													<div class="d-flex flex-row flex-column-fluid w-md-50 w-xl-100">
                                                         <div class="d-flex flex-row-auto w-100px w-lg-120px flex-center">
                                                            <input type="text" name="param[comp_num1]" id="post_comp_num1" title="사업자등록번호를 모두 입력하세요." value="<?=$data['comp_num1'];?>"
                                                                class="form-control form-control-sm" placeholder="" maxlength="3" aria-label="사업자등록변호 앞번호" />
                                                        </div>
                                                        <div class="d-flex flex-row-auto w-20px flex-center text-gray-400"> - </div>
														<div class="d-flex flex-row-auto w-100px w-lg-120px flex-center">
                                                            <input type="text" name="param[comp_num2]" id="post_comp_num2" title="사업자등록번호를 모두 입력하세요." value="<?=$data['comp_num2'];?>"
                                                                class="form-control form-control-sm" placeholder="" maxlength="2" aria-label="사업자등록번호 중간번호" />
                                                        </div>
														<div class="d-flex flex-row-auto w-20px flex-center text-gray-400"> - </div>
                                                        <div class="d-flex flex-row-fluid flex-center">
                                                            <input type="text" name="param[comp_num3]" id="post_comp_num3" title="사업자등록번호를 모두 입력하세요." value="<?=$data['comp_num3'];?>"
                                                                class="form-control form-control-sm" placeholder="" maxlength="5" aria-label="사업자등록번호 끝번호" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--// 사업자등록번호 -->
                                            </div>

                                            <div class="row py-2 py-xl-3">
                                                <!-- 대표자명 -->
                                                <div class="col-xl-2 border-md-bottom pb-xl-6">
                                                    <label for="post_boss_name" class="fs-6 fw-semibold my-2">
                                                        <span class="required">대표자명</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 border-bottom pb-6">
                                                    <input type="text" name="param[boss_name]" id="post_boss_name"  title="대표자명을 입력하세요." size="20" value="<?=$data['boss_name'];?>"
                                                        class="form-control form-control-sm" placeholder="대표자명을 입력하세요.">
                                                </div>
                                                <!--// 대표자명 -->

                                                <!-- 고유번호 -->
                                                <div class="col-xl-2 pt-4 pt-xl-0 border-md-bottom pb-xl-6">
                                                    <label for="post_distinct_num1" class="fs-6 fw-semibold my-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click">
                                                        <span class="required">고유번호</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 border-bottom pb-6">
                                                    <div class="d-flex flex-row flex-column-fluid w-md-50 w-xl-75">
                                                        <div class="d-flex flex-row-fluid flex-center">
                                                            <input type="text" name="param[distinct_num1]" id="post_distinct_num1" title="고유번호를 모두 입력하세요." value="<?=$data['distinct_num1'];?>"
                                                                class="form-control form-control-sm" placeholder="" maxlength="6" aria-label="고유번호 앞번호" />
                                                        </div>
                                                        <div class="d-flex flex-row-auto w-20px flex-center text-gray-400"> - </div>
                                                        <div class="d-flex flex-row-fluid flex-center">
                                                            <input type="text" name="param[distinct_num2]" id="post_distinct_num2" title="고유번호를 모두 입력하세요." value="<?=$data['distinct_num2'];?>"
                                                                class="form-control form-control-sm" placeholder="" maxlength="6" aria-label="고유번호 끝번호" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--// 고유번호 -->
                                            </div>

                                            <div class="row py-2 py-xl-3">
                                                <!-- 업종 -->
                                                <div class="col-xl-2 border-md-bottom pb-xl-6">
                                                    <label for="post_upjong" class="fs-6 fw-semibold my-2">
                                                        <span class="required">업종</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 border-bottom pb-6">
                                                    <input type="text" name="param[upjong]" id="post_upjong" title="업종을 입력하세요." 
                                                        class="form-control form-control-sm" placeholder="업종을 입력하세요." size="24" value="<?=$data['upjong'];?>">
                                                </div>
                                                <!--// 업종 -->

                                                <!-- 업태 -->
                                                <div class="col-xl-2 pt-4 pt-xl-0 border-md-bottom pb-xl-6">
                                                    <label for="post_uptae" class="fs-6 fw-semibold my-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click">
                                                        <span class="required">업태</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 border-bottom pb-6">
                                                    <input type="text" name="param[uptae]" id="post_uptae" title="업태를 입력하세요." size="24" value="<?=$data['uptae'];?>"
                                                        class="form-control form-control-sm" placeholder="업태를 입력하세요.">
                                                </div>
                                                <!--// 업태 -->
                                            </div>

                                            <!-- 사업장주소 -->
                                            <div class="row py-2 pt-xl-0 pb-xl-2">
                                                <div class="col-xl-2 border-md-bottom pb-xl-6">
                                                    <label for="post_address1" class="fs-6 fw-semibold my-2">
                                                        <span class="required">사업장주소</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-10 border-bottom pb-6 mt-2">
                                                    <div class="row gx-2">
                                                        <div class="col-md-4 mb-2">
                                                            <div class="position-relative d-flex align-items-center">
                                                                <input type="text" name="param[zip_code]" id="zip_code" title="사업장주소 우편번호를 입력하세요." maxlength="4" value="<?=$data['zip_code1'];?>"
                                                                    class="form-control form-control-sm common_zip_code" aria-label="우편번호 앞자리를 입력하세요." placeholder="우편번호">
                                                                <button type="button" class="btn btn-sm btn-dark position-absolute end-0 px-6 rounded-start-0"
                                                                    data-bs-toggle="modal" data-bs-target="#pop-postcode"  data-form-id="form-schoolinfo">우편번호찾기</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-8 d-none d-md-block"></div>
                                                        <div class="col-md-6 mb-2 mb-md-0">
                                                            <input type="text" name="param[address1]" id="post_address1" title="사업장 주소 입력하세요."   maxlength="80" value="<?=$data['address1'];?>"
                                                                class="form-control form-control-sm common_address1" aria-label="주소를 입력하세요." placeholder="주소를 입력하세요." >
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" name="param[address2]" id="post_address2" title="사업장 상세주소 입력하세요."  maxlength="80" value="<?=$data['address2'];?>"
                                                                class="form-control form-control-sm common_address2" aria-label="상세주소를 입력하세요." placeholder="상세주소를 입력하세요.">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--// 사업장주소 -->

                                            <div class="row py-2 py-xl-3">
                                                <!-- 전화번호 -->
                                                <div class="col-xl-2 border-md-bottom pb-xl-6">
                                                    <label for="post_tel_num1" class="fs-6 fw-semibold my-2">
                                                        <span class="required">전화번호</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 border-bottom pb-6">
                                                    <div class="d-flex flex-row flex-column-fluid w-md-50 w-xl-100">
                                                        <div class="d-flex flex-row-fluid flex-center">
															<select name="param[tel_num1]" id="post_tel_num1" title="전화번호 앞자리를 선택하세요." data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="전화번호 앞자리" class="form-select form-select-sm">
																<option value="">선택</option>
																<?foreach($set_telephone as $key => $val){?>
																	<option value="<?=$key?>" <?=$val == $data['tel_num1'] ? 'selected' :''?>><?=$val?></option>
																<?}?>
															</select>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-row-auto w-20px flex-center text-gray-400">
                                                            -
                                                        </div>
                                                        <div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
                                                            <input type="text" name="param[tel_num2]" id="post_tel_num2" title="전화번호를 모두 입력하세요." value="<?=$data['tel_num2'];?>"
                                                                class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="전화번호 중간번호" />
                                                        </div>
                                                        <div class="d-flex flex-row-auto w-20px flex-center text-gray-400"> - </div>
                                                        <div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
                                                            <input type="text" name="param[tel_num3]" id="post_tel_num3" title="전화번호를 모두 입력하세요."  value="<?=$data['tel_num3'];?>"
                                                                class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="전화번호 끝번호" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--// 전화번호 -->


                                                <!-- 팩스번호 -->
                                                <div class="col-xl-2 pt-4 pt-xl-0 border-md-bottom pb-xl-6">
                                                    <label for="post_fax_num1" class="fs-6 fw-semibold my-2">
                                                        <span class="required">팩스번호</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 border-bottom pb-6">
                                                    <div class="d-flex flex-row flex-column-fluid w-md-50 w-xl-100">
                                                        <div class="d-flex flex-row-fluid flex-center">
															<select name="param[fax_num1]" id="post_fax_num1" title="팩스번호 앞자리를 선택하세요." data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="팩스번호 앞자리" class="form-select form-select-sm">
																<option value="">선택</option>
																<?foreach($set_telephone as $key => $val){?>
																	<option value="<?=$key?>" <?=$val == $data['fax_num1'] ? 'selected' :''?>><?=$val?></option>
																<?}?>
															</select>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-row-auto w-20px flex-center text-gray-400">
                                                            -
                                                        </div>
                                                        <div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
                                                            <input type="text" name="param[fax_num2]" id="post_fax_num2" title="팩스번호를 모두 입력하세요." value="<?=$data['fax_num2'];?>"
                                                                class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="팩스번호 중간번호" />
                                                        </div>
                                                        <div class="d-flex flex-row-auto w-20px flex-center text-gray-400"> -  </div>
                                                        <div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
                                                            <input type="text" name="param[fax_num3]" id="post_fax_num3"  title="팩스번호를 모두 입력하세요."  value="<?=$data['fax_num3'];?>"
                                                                class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="팩스번호 끝번호" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--// 팩스번호 -->
                                            </div>

                                            <!-- 이메일 -->
                                            <div class="row py-2 pt-xl-0 pb-xl-2">
                                                <div class="col-xl-2 border-md-bottom pb-xl-6">
                                                    <div for="post_comp_email1" class="fs-6 fw-semibold my-2">
                                                        <span class="required">이메일</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-10 border-bottom pb-6 mt-2">
                                                    <div class="row gx-2 w-xl-75">
                                                        <div class="col-6 col-md-4"> 
                                                            <input type="text" name="param[comp_email1]" id="post_comp_email1" title="이메일 아이디를 입력하세요."  value="<?=$data['comp_email1'];?>"
                                                                class="form-control form-control-sm"  aria-label="이메일 아이디를 입력하세요." placeholder="이메일 아이디를 입력하세요." maxlength="30">
                                                        </div>
                                                        <div class="col-6 col-md-3">
                                                            <input type="text" name="param[comp_email2]" id="post_comp_email2" title="이메일 아이디를 입력하세요." value="<?=$data['comp_email2'];?>"
                                                                class="form-control form-control-sm common_email2" aria-label="이메일 주소를 입력하세요." placeholder="이메일 주소를 입력하세요." maxlength="40">
                                                        </div>
                                                        <div class="col-md-5 mt-2 mt-md-0">
                                                            <div class="position-relative d-flex align-items-center">
                                                                <select name="post_comp_email3" id="post_comp_email3" data-control="select2" title="이메일 선택하세요"
                                                                    data-hide-search="true" data-placeholder="이메일 선택" aria-label="이메일 선택하세요" class="form-select form-select-sm common_email3">
                                                                    <option value="">이메일 선택하세요</option>
																	<?foreach($set_email_domain as $ekey => $eval){?>
                                                                    <option value="<?=$eval?>" <?=$eval == $data['comp_email2'] ? 'selected' :''?>><?=$eval?></option>
																	<?}?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--// 이메일 -->

                                            <div class="row py-2 py-xl-3">
                                                <!-- 담당자 -->
                                                <div class="col-xl-2 border-md-bottom pb-xl-6">
                                                    <label for="post_charge_name" class="fs-6 fw-semibold my-2">
                                                        <span class="required">담당자</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 border-bottom pb-6">
                                                    <input type="text" name="param[charge_name]" id="post_charge_name" title="담당자를 입력하세요." size="20" value="<?=$data['charge_name'];?>"
                                                        class="form-control form-control-sm" placeholder="담당자를 입력하세요.">
                                                </div>
                                                <!--// 담당자 -->

                                                <!-- 휴대전화번호 -->
                                                <div class="col-xl-2 border-md-bottom pb-xl-6">
                                                    <label for="post_hp_num1"
                                                        class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                        <span class="required">휴대전화번호</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 border-bottom pb-6">
                                                    <div class="d-flex flex-row flex-column-fluid w-md-50 w-xl-100">
                                                        <div class="d-flex flex-row-fluid flex-center">
                                                            <select name="param[hp_num1]" id="post_hp_num1" title="핸드폰 번호 앞자리를 선택하세요."
                                                                data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="휴대전화번호 앞자리" class="form-select form-select-sm">
                                                                <option value="">없음</option>
																<?foreach($set_cellular as $key => $val){?>
																	<option value="<?=$key?>" <?=$val == $data['hp_num1'] ? 'selected' :''?>><?=$val?></option>
																<?}?>
                                                            </select>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-row-auto w-20px flex-center text-gray-400">
                                                            -
                                                        </div>
                                                        <div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
                                                            <input type="text" name="param[hp_num2]" id="post_hp_num2" title="전화번호를 모두 입력하세요." value="<?=$data['hp_num2'];?>"
                                                                class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="휴대전화번호 중간번호" />
                                                        </div>
                                                        <div class="d-flex flex-row-auto w-20px flex-center text-gray-400"> - </div>
                                                        <div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
                                                            <input type="text" name="param[hp_num3]" id="post_hp_num3" title="전화번호를 모두 입력하세요." value="<?=$data['hp_num3'];?>"
                                                                class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="휴대전화번호 끝번호" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--// 휴대전화번호 -->
                                            </div>

                                            <div class="mb-8 mb-lg-10 text-end">
                                                <button type="button" class="btn btn-sm btn-secondary" onclick="location.href='<?=$this_page;?>?<?=$f_all;?>'">
                                                    <i class="ki-outline ki-arrows-circle fs-6"></i> 취소
                                                </button>
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="ki-outline ki-pencil fs-6"></i> 수정
                                                </button>
                                            </div>
                                        </form>
                                        <!-- //기관정보 -->

									 </div>
                                </div>
                            </div>
                            <!--// Content container -->
                        </div>
                        <!--// Content -->
                    </div>
                    <!--// Content wrapper -->

<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/script_member.js" charset="utf-8"></script>
<script type="text/javascript">
//<![CDATA[
	var link_ok = '<?=$link_ok;?>';

//------------------------------------ Save
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

	// 상호명
		chk_msg = check_comp_name('On');
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

	// 대표자명
		chk_msg = check_boss_name('On');
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

	// 사업자등록번호
		chk_msg = check_comp_num('On');
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

	// 이메일
		chk_msg = check_comp_email('On');
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						check_auth_popup('정상적으로 처리되었습니다.');
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
		return false;
	}
//]]>
</script>