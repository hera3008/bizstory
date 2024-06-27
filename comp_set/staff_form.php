<?
/*
	수정 : 2013.04.18
	위치 : 설정폴더 > 직원관리 > 직원등록/수정 - 등록, 수정
*/
	//require_once "../common/setting.php";
	//require_once "../common/no_direct.php";
	//require_once "../common/member_chk.php";

	$code_comp      = $_SESSION[$sess_str . '_comp_idx'];
	//$code_part      = search_company_part($code_part);
	$code_part      = "";
	$set_staff_num  = $comp_set_data['staff_cnt'];
	$set_file_class = $comp_set_data['file_class'];
	$mem_idx        = $idx;

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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$page_where = " and mem.comp_idx = '" . $code_comp . "'";
	$page_data = member_info_data('page', $page_where);

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' && $mem_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['int'] == 'Y' && $mem_idx != '') // 수정권한
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
			</script>';
		exit;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($form_chk == 'Y')
	{
		if($mem_idx != ""){
			$where = " and mem.mem_idx = '" . $mem_idx . "'";
			$data = member_info_data("view", $where);

			if ($data['part_idx'] == '' || $data['part_idx'] == '0') $data['part_idx'] = $code_part;
			if ($data['login_yn'] == '') $data['login_yn'] = 'Y';
			if ($data['ubstory_yn'] == '') $data['ubstory_yn'] = 'N';

			$mem_email = $data['mem_email'];
			$mem_email_arr = explode('@', $mem_email);
			$data['mem_email1'] = $mem_email_arr[0];
			$data['mem_email2'] = $mem_email_arr[1];

			$address = $data['address'];
			$address_arr = explode('||', $address);
			$data['address1'] = $address_arr[0];
			$data['address2'] = $address_arr[1];

			$tel_num = $data['tel_num'];
			$tel_num_arr = explode('-', $tel_num);
			$data['tel_num1'] = $tel_num_arr[0];
			$data['tel_num2'] = $tel_num_arr[1];
			$data['tel_num3'] = $tel_num_arr[2];

			$hp_num = $data['hp_num'];
			$hp_num_arr = explode('-', $hp_num);
			$data['hp_num1'] = $hp_num_arr[0];
			$data['hp_num2'] = $hp_num_arr[1];
			$data['hp_num3'] = $hp_num_arr[2];

			$code_part = $data['part_idx'];

			$mf_where = " and mf.mem_idx = '" . $mem_idx. "' and mf.sort = 1";
			$mf_data  = member_file_data('view', $mf_where);
			
			$staff_img_blank = $local_dir . '/bizstory/assets/media/svg/avatars/blank.svg';
			$staff_img = '';
			if ($data['mem_img'] != '')  $staff_img = $data['mem_img']; 
			else if ($mf_data['img_sname'] != '') $staff_img =  $mem_dir . "/" . $mf_data['mem_idx'] . "/" . $mf_data['img_sname'];
			else $staff_img = $staff_img_blank;

			
		}
		else
		{
			$staff_img_blank = $staff_img  = $local_dir . '/bizstory/assets/media/svg/avatars/blank.svg';
		}
?>
										<form id="postform" name="postform" method="post" class="form" action="<?=$this_page;?>" onsubmit="return check_form()">
											<?=$form_all;?>
											<input type="hidden" id="ubstory_level" name="ubstory_level" value="<?=$data['ubstory_level'];?>" />
											<?/*
                                            <div class="row py-2 py-xl-4">
                                                <!-- 지사 -->
                                                <div class="col-xl-2">
                                                    <div for="post_part_idx" class="fs-6 fw-semibold my-2">
                                                        <span class="required">지사</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <select name="param[part_idx]" id="post_part_idx"
                                                        data-control="select2" data-hide-search="true"
                                                        data-placeholder="지사를 선택해 주세요" aria-label="지사를 선택해 주세요"
                                                        class="form-select form-select-sm">
                                                        <option>지사를 선택해 주세요.</option>
                                                        <option value="11">유지보수사업부</option>
                                                        <option value="117">협력업체</option>
                                                        <option value="1">(주)유비스토리</option>
                                                        <option value="29">외부협업</option>
                                                    </select>
                                                </div-->
                                                <!--// 지사 -->

                                                <!-- 권한부여여부 -->
                                                <div class="col-xl-2 pt-4 pt-xl-0">
                                                    <label for="post_empowerment_yn"
                                                        class="fs-6 fw-semibold mt-3 mt-lg-6 mb-2 my-xl-2">
                                                        <span class="required">권한부여여부</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container mt-2">
                                                    <div
                                                        class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
                                                        <input class="form-check-input w-35px h-20px" type="checkbox"
                                                            id="post_empowerment_yn" checked="checked" />
                                                    </div>
                                                </div>
                                                <!--// 권한부여여부 -->
                                            </div>
											*/?>

                                           
                                            <!-- 프로필 사진 -->
                                            <div class="row py-2 py-xl-4">
                                                <div class="col-xl-2">
                                                    <div for="mem_img" class="fs-6 fw-semibold my-2">
                                                        프로필 사진
                                                    </div>
                                                </div>
                                                <div class="col-xl-10 fv-row fv-plugins-icon-container">	
                                                    <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url(<?=$staff_img_blank?>)">														
                                                        <div id="mem_img" class="image-input-wrapper w-90px h-90px" style="background-image: url('<?=$staff_img?>')"></div>
														<textarea id="post_mem_img" name="param[mem_img]" style="display:none;"><?=$data['mem_img'];?></textarea>
                                                        <label 
                                                            class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                            data-bs-dismiss="click" aria-label="이미지 변경">
                                                            <i class="ki-outline ki-pencil fs-6"></i>
                                                            <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                                                            <input type="hidden" name="avatar_remove" />
                                                        </label>
														
                                                        <button type="button"
                                                            class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                            data-bs-dismiss="click" aria-label="이미지 취소">
                                                            <i class="ki-outline ki-cross fs-3"></i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                            data-bs-dismiss="click" aria-label="이미지 삭제">
                                                            <i class="ki-outline ki-cross fs-3"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--// 프로필 사진 -->											

                                            <div class="row py-2 py-xl-4">
                                                <!-- 이름 -->
                                                <div class="col-xl-2">
                                                    <div for="post_mem_name" class="fs-6 fw-semibold my-2">
                                                        <span class="required">이름</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <input type="text" name="param[mem_name]" id="post_mem_name" title="이름을 입력하세요." value="<?=$data['mem_name'];?>"  maxlength="20"
                                                        class="form-control form-control-sm" placeholder="이름을 입력하세요.">
                                                </div>
                                                <!--// 이름 -->


                                                <!-- 비밀번호 -->
                                                <div class="col-xl-2">
                                                    <label for="post_mem_pwd"
                                                        class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                        <span>비밀번호</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <input type="password" name="param[mem_pwd]" id="post_mem_pwd" placeholder="비밀번호를 입력하세요."
                                                        class="form-control form-control-sm" placeholder="비밀번호를 입력하세요."
                                                        maxlength="20">
														<?
															if ($mem_idx == '') { echo '* 입력하지 않으면 핸드폰번호 뒷자리가 됩니다.'; }
															else { echo '* 수정시만 입력하세요.'; }
														?>
                                                </div>
                                                <!--// 비밀번호 -->
                                            </div>

											<div class="row py-2 py-xl-4">
                                                <!-- 직책 -->
                                                <div class="col-xl-2">
                                                    <div for="post_csd_idx" class="fs-6 fw-semibold my-2">
                                                        <span >직책</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
												<?
													$query_string = "select csd_idx, duty_name from company_staff_duty where del_yn='N' and view_yn='Y' and comp_idx = '" . $code_comp ."' order by sort ";
													$data_sql['query_string'] = $query_string;
													$staff_duty_data = query_list($data_sql);
														
												?>
													<select name="param[csd_idx]" id="post_csd_idx" title="직책을 선택하세요" data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="직책 선택하세요" class="form-select form-select-sm">
														<option value="">없음</option>
												<?
															foreach($staff_duty_data as $skey => $slist){
																if(is_array($slist)){
												?>
															<option value="<?=$slist['csd_idx']?>" <?=$slist['csd_idx'] == $data['csd_idx']?"selected":""?>><?=$slist['duty_name']?></option>
												<?			}
														}
												?>
													</select>
                                                </div>
                                                <!--// 직책 -->

                                                <!-- 부서 -->
                                                <div class="col-xl-2">
                                                    <div for="post_csg_idx" class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                        <span class="required">부서</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
												<?
													$query_string = "select csg_idx, group_name from company_staff_group where del_yn='N' and view_yn='Y' and comp_idx = '" . $code_comp ."' order by sort ";
													$data_sql['query_string'] = $query_string;
													$staff_group_data = query_list($data_sql);
														
												?>
													<select name="param[csg_idx]" id="post_csg_idx" title="부서를 선택하세요" data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="부서를 선택하세요" class="form-select form-select-sm">
														<option value="">없음</option>
												<?
															foreach($staff_group_data as $skey => $slist){
																if(is_array($slist)){
												?>
															<option value="<?=$slist['csg_idx']?>" <?=$slist['csg_idx'] == $data['csg_idx']?"selected":""?>><?=$slist['group_name']?></option>
												<?			}
														}
												?>
												</select>
                                                <!--// 부서 -->
                                            </div>



                                            <div class="row py-2 py-xl-4">
                                                <!-- 전화번호 -->
                                                <div class="col-xl-2">
                                                    <label for="post_tel_num1" class="fs-6 fw-semibold my-2">
                                                        <span class="required">전화번호</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <div class="d-flex flex-row flex-column-fluid">
                                                        <div class="d-flex flex-row-fluid flex-center">
                                                            <select name="param[tel_num1]" id="post_tel_num1" data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="전화번호 앞자리" class="form-select form-select-sm">
																<option value="">없음</option>
																<?foreach($set_telephone as $key => $val){?>
																	<option value="<?=$key?>" <?=$key==$data['tel_num1']?"selected":""?>><?=$val?></option>
																<?}?>
                                                            </select>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-row-auto w-20px flex-center text-gray-400">
                                                            -
                                                        </div>
                                                        <div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
															<input type="text" name="param[tel_num2]" id="post_tel_num2" value="<?=$data['tel_num2'];?>" title="전화번호를 모두 입력하세요." class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="전화번호 중간번호"/>
                                                        </div>
                                                        <div class="d-flex flex-row-auto w-20px flex-center text-gray-400"> - </div>
                                                        <div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
															<input type="text" name="param[tel_num3]" id="post_tel_num3" value="<?=$data['tel_num3'];?>" title="전화번호를 모두 입력하세요." class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="전화번호 끝번호"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--// 전화번호 -->

                                                <!-- 휴대전화번호 -->
                                                <div class="col-xl-2">
                                                    <label for="post_hp_num1" class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                        <span class="required">휴대전화번호</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <div class="d-flex flex-row flex-column-fluid">
                                                        <div class="d-flex flex-row-fluid flex-center">
															<select name="param[hp_num1]" id="post_hp_num1" title="핸드폰 번호 앞자리를 선택하세요" data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="휴대전화번호 앞자리" class="form-select form-select-sm">
																<option value="">없음</option>
																<?foreach($set_cellular as $key => $val){?>
																	<option value="<?=$key?>" <?=$key==$data['hp_num1']?"selected":""?>><?=$val?></option>
																<?}?>
															</select>
                                                        </div>
                                                        <div class="d-flex flex-row-auto w-20px flex-center text-gray-400"> - </div>
                                                        <div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
															<input type="text" name="param[hp_num2]" id="post_hp_num2" itle="휴대전화번호 모두 입력하세요." value="<?=$data['hp_num2'];?>" class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="휴대전화번호 중간번호"/>
                                                        </div>
                                                        <div class="d-flex flex-row-auto w-20px flex-center text-gray-400"> - </div>
                                                        <div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
															<input type="text" name="param[hp_num3]" id="post_hp_num3" title="휴대전화번호 모두 입력하세요." value="<?=$data['hp_num3'];?>" class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="휴대전화번호 끝번호"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--// 휴대전화번호 -->
                                            </div>

                                            <!-- 이메일 -->
                                            <div class="row py-2 py-xl-4">
                                                <div class="col-xl-2">
                                                    <div for="post_comp_email1" class="fs-6 fw-semibold my-2">
                                                        <span class="required">이메일</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-10 fv-row fv-plugins-icon-container">
                                                    <div class="row gx-2">
                                                        <div class="col-6 col-md-4">
                                                            <input type="text" name="param[mem_email1]" id="post_comp_email1" title="이메일 아이디를 입력하세요." value="<?=$data['mem_email1'];?>" maxlength="30"
                                                                class="form-control form-control-sm" aria-label="이메일 아이디를 입력하세요." placeholder="이메일 아이디를 입력하세요." >
                                                        </div>
                                                        <div class="col-6 col-md-3">
														<input type="text" name="param[mem_email2]" id="post_comp_email2" title="이메일 주소를 입력하세요." value="<?=$data['mem_email2'];?>" class="form-control form-control-sm common_email2" aria-label="이메일 주소를 입력하세요." placeholder="이메일 주소를 입력하세요." maxlength="40">
                                                        </div>
                                                        <div class="col-md-5 mt-2 mt-md-0">
                                                            <div class="position-relative d-flex align-items-center pe-24">
                                                                <select name="user_email3" data-control="select2" data-hide-search="true" data-placeholder="이메일 선택" aria-label="이메일 선택하세요" class="form-select form-select-sm common_email3">
																	<option value="">이메일 선택하세요</option>
																	<?foreach($set_email_domain as $key => $val){?>
																	<option value="<?=$key?>"><?=$val?></option>
																	<?}?>
																	<option value="직접입력">직접입력</option>
																</select>
																<?
																	if ($mem_idx == '')
																	{
																?>
																<input type="hidden" name="post_mem_email_chk" id="post_mem_email_chk" value="N" />
																<button type="button" onclick="double_email_chk();" class="btn btn-sm btn-dark border-0 position-absolute end-0 px-6 btn_email_chk" aria-label="검색">중복확인</button>
																<?
																	} else {
																?>
																<input type="hidden" name="post_mem_email_chk" id="post_mem_email_chk" value="Y" />
																<?
																	}
																?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--// 이메일 -->

                                            <!-- 주소 -->
                                            <div class="row py-2 py-xl-4">
                                                <div class="col-xl-2">
                                                    <label for="post_zip_code" class="fs-6 fw-semibold my-2">
                                                        <span class="required">주소</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-10 fv-row fv-plugins-icon-container">
                                                    <div class="row gx-2">
                                                        <div class="col-md-4 mb-2">
                                                            <div class="position-relative d-flex align-items-center">
                                                                <input type="text" name="param[zip_code]" id="post_zip_code" title="우편번호 앞자리를 입력하세요." value="<?=$data['zip_code'];?>"
                                                                    class="form-control form-control-sm common_zip_code" aria-label="우편번호 입력하세요." placeholder="우편번호" maxlength="5" readonly>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-dark position-absolute end-0 px-6 rounded-start-0"
                                                                    data-bs-toggle="modal" data-bs-target="#pop-postcode" data-form-id="form-modify">우편번호찾기</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-8 d-none d-md-block"></div>
                                                        <div class="col-md-6 mb-2 mb-md-0">
                                                            <input type="text" name="param[address1]" id="post_address1" title="주소 입력하세요." value="<?=$data['address1'];?>" maxlength="80"
                                                                class="form-control form-control-sm common_address1" aria-label="주소를 입력하세요." placeholder="주소를 입력하세요.">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" name="param[address2]" id="post_address2" title="상세주소 입력하세요." value="<?=$data['address2'];?>"  maxlength="80"
                                                                class="form-control form-control-sm common_address2" aria-label="상세주소를 입력하세요." placeholder="상세주소를 입력하세요.">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--// 주소 -->

											<div class="row py-2 py-xl-4">
                                                <!-- 입사일 -->
                                                <div class="col-xl-2">
                                                    <label for="post_login_yn" class="fs-6 fw-semibold my-2">
                                                        <span class="required">재직여부</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
													<div
                                                        class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-2">
                                                        <input type="checkbox" name="param[login_yn]" id="post_login_yn" value='Y'
															class="form-check-input w-35px h-20px" <?=$data['login_yn']=='Y' ? 'checked="checked"' : ""?> />
                                                    </div>
                                                </div>
                                                <!--// 입사일 -->

                                                <!-- 삭제일 -->
                                                <div class="col-xl-2 pt-4 pt-xl-0">
                                                    <label for="post_end_date" class="fs-6 fw-semibold my-2">
                                                        <span>삭제일</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container"> <?=$data['end_date']?>
                                                    <input type="text" name="param[end_date]" id="post_end_date" title="삭제일을 입력하세요." value=""
                                                        class="form-control form-control-sm flatpickr-input-single" placeholder="0000-00-00" size="24">
                                                </div>
                                                <!--// 삭제일 -->
                                            </div>

                                            <div class="row py-2 py-xl-4">
                                               <!-- 관리자여부 -->
											   <div class="col-xl-2">
                                                    <div for="post_empowerment_yn" class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                        <span>권한부여여부</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-2"> 
                                                        <input type="checkbox" name="param[empowerment_yn]" id="post_empowerment_yn" value="Y"
															class="form-check-input w-35px h-20px" <?=$data['empowerment_yn']=='Y' ? 'checked="checked"' : ""?>/>
                                                    </div>
                                                </div>
                                                <!--// 관리자여부 -->

                                                <!-- 권한부여여부 -->
                                                <div class="col-xl-2 pt-4 pt-xl-0">
                                                    <label for="post_ubstory_yn" class="fs-6 fw-semibold mt-3 mt-lg-6 mb-2 my-xl-2">
                                                        <span>관리자여부</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container mt-2">
                                                    <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
														<input type="checkbox" name="param[ubstory_yn]" id="post_ubstory_yn" value="Y"
															class="form-check-input w-35px h-20px" id="post_ubstory_yn" <?=$data['ubstory_yn']=='Y' ? 'checked="checked"' : ""?> />
                                                        
                                                    </div>
                                                </div>
                                                <!--// 권한부여여부 -->
                                            </div>

                                           

                                            <!-- 메모 -->
                                            <div class="row py-2 py-xl-4">
                                                <div class="col-xl-2">
                                                    <label for="post_remark" class="fs-6 fw-semibold my-2">
                                                        <span>메모</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-10 fv-row fv-plugins-icon-container">
                                                    <textarea id="post_remark" name="param[remark]" title="메모을 입력하세요." class="form-control form-control-sm" rows="5" placeholder="메모를 입력하세요"><?=$data['remark'];?></textarea>
                                                </div>
                                            </div>
                                            <!--// 메모 -->

                                            <!-- 이력사항 -->
                                            <div class="row py-2 py-xl-4">
                                                <div class="col-xl-2">
                                                    <label for="post_remark2" class="fs-6 fw-semibold my-2">
                                                        <span>이력사항</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-10 fv-row fv-plugins-icon-container">
                                                    <textarea id="post_remark2" name="param[remark2]" title="이력사항 입력하세요." class="form-control form-control-sm" rows="10" placeholder="이력사항을 입력하세요"><?=$data['remark2'];?></textarea>
                                                </div>
                                            </div>
                                            <!--// 이력사항 -->

                                            <div class="separator separator-dashed mt-4 mt-xl-0 mb-6 mb-lg-8"></div>
                                            <div class="mb-8 mb-lg-10 text-end">
                                                <button type="button" class="btn btn-sm btn-secondary">
                                                    <i class="ki-outline ki-arrows-circle fs-6"></i> 취소
                                                </button>
												
												<?	if ($mem_idx == '') { ?>
												<button type="button" class="btn btn-sm btn-warning" onclick="check_form()">
                                                    <i class="ki-outline ki-pencil fs-6"></i> 수정
                                                </button>
												<input type="hidden" name="sub_type" value="post" />
												<?	} else { ?>
                                                <button type="button" class="btn btn-sm btn-warning" onclick="check_form()">
                                                    <i class="ki-outline ki-pencil fs-6"></i> 수정
                                                </button>
												<input type="hidden" name="sub_type" value="modify" />
												<input type="hidden" name="mem_idx"  value="<?=$mem_idx;?>" />
												<?	} ?>
                                            </div>
                                        </form>
                                        <!-- //직원등록/수정 -->

<script type="text/javascript" src="<?=$local_dir?>/bizstory/js/base64.js" charset="utf-8"></script>
<script type="text/javascript">
//<![CDATA[

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#post_part_idx').val(); // 지사
		chk_title = $('#post_part_idx').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_mem_name').val(); // 이름
		chk_title = $('#post_mem_name').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_msg = check_mem_email(); // 이메일
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		chk_msg = check_hp_num(); // 핸드폰번호
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		var staff_img_blank = '<?=$staff_img_blank?>';
		var image_url = $('#mem_img').css("background-image").replace(/^url\(['"](.+)['"]\)/, '$1');
        if(image_url == staff_img_blank)
            $('#post_mem_img').val();
        else
		    $('#post_mem_img').val(image_url);
        
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
						document.location.reload();
				<?
					if ($set_file_class == 'OUT')
					{
				?>
						filecenter_member_folder(msg.mem_idx);
				<?
					}
				?>
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
<?
	}
?>