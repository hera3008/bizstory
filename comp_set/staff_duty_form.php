<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 직원관리 > 직책관리 - 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	//$code_part = search_company_part($code_part);
	$code_part = "";
	$csd_idx   = $idx;

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

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' && $csd_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $csd_idx != '') // 수정권한
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

	if ($form_chk == 'Y')
	{
		$where = " and csd.csd_idx = '" . $csd_idx . "'";
		$data = company_staff_duty_data("view", $where); 

		if ($data["view_yn"] == '') $data["view_yn"] = 'Y';
		if ($data["default_yn"] == '') $data["default_yn"] = 'N';
		if ($data["part_idx"] == '' || $data["part_idx"] == '0') $data["part_idx"] = $code_part;
?>
											<div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">직책관리 등록</h3>
                                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="ki-outline ki-cross fs-1"></i>
                                                        </div>
                                                    </div>
                                                     <form id="postform" name="postform" method="post" class="form" action="<?=$this_page;?>" onsubmit="return check_form()">
                                                        <div class="modal-body">

                                                            <!--div class="row py-2">
                                                                <div class="col-3">
                                                                    <label for="post_part_idx"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">지사</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9">
                                                                    <select name="param[part_idx]" id="post_part_idx"
                                                                        data-control="select2" data-hide-search="true"
                                                                        data-placeholder="지사를 선택해 주세요"
                                                                        aria-label="지사를 선택해 주세요"
                                                                        class="form-select form-select-sm">
                                                                        <option>지사를 선택해 주세요.</option>
                                                                        <option value="11">유지보수사업부</option>
                                                                        <option value="117">협력업체</option>
                                                                        <option value="1" selected="selected">(주)유비스토리
                                                                        </option>
                                                                        <option value="29">외부협업</option>
                                                                    </select>
                                                                </div>
                                                            </div-->

                                                            <div class="row py-2">
                                                                <div class="col-3">
                                                                    <label for="post_duty_name" class="fs-7 fw-semibold my-3">
                                                                        <span class="required">직책명</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9">
                                                                    <input type="text" name="param[duty_name]" id="post_duty_name" value="<?=$data['duty_name'];?>" maxlength="25" title="직책명을 입력하세요."
                                                                        class="form-control form-control-sm maxlength" placeholder="직책명을 입력하세요." >
                                                                </div>
                                                            </div>

                                                            <div class="row py-2">
                                                                <div class="col-3">
                                                                    <label for="post_view_yn" class="fs-7 fw-semibold my-3">
                                                                        <span class="required">보기여부</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-3">
                                                                    <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-2 mt-md-3">
                                                                        <input type="checkbox" name="param[view_yn]" id="post_view_yn" value="Y" <?=$data['view_yn'] == 'Y' ? 'checked' : ''?>
                                                                            class="form-check-input w-25px h-15px w-xl-30px h-xl-20px"/>
                                                                    </div>
                                                                </div>

                                                                <div class="col-3">
                                                                    <label for="post_default_yn" class="fs-7 fw-semibold my-3">
                                                                        <span class="required">기본여부</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-3">
                                                                    <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-2 mt-md-3">
                                                                        <input type="checkbox" name="param[default_yn]" id="post_default_yn" value="Y" <?=$data['default_yn'] == 'Y' ? 'checked' : ''?>
                                                                            class="form-check-input w-25px h-15px w-xl-30px h-xl-20px"/>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-secondary d-print-none" data-bs-dismiss="modal">
																<i class="ki-outline ki-arrows-circle fs-6"></i>취소
															</button>

														<?if ($csd_idx == "") {?>
                                                            <button type="button" class="btn btn-sm btn-warning d-print-none"  onclick="check_form()">
																<i class="ki-outline ki-pencil fs-6"></i> 등록
                                                            </button>
															<input type="hidden" name="sub_type" value="post" />
														<?}else{?>	
															 <button type="button" class="btn btn-sm btn-warning d-print-none"  onclick="check_form()">
																<i class="ki-outline ki-pencil fs-6"></i> 수정
                                                            </button>
															<input type="hidden" name="sub_type" value="modify" />
															<input type="hidden" name="csd_idx" value="<?=$csd_idx;?>" />
														<?}?>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

<?
	}
?>