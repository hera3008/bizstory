<?
/*
	수정 : 2013.03.27
	위치 : 설정관리 > 접수관리 > 접수분류 - 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_idx  = $idx;

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
	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' && $code_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $code_idx != '') // 수정권한
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
		$where = " and code.code_idx = '" . $code_idx . "'";
		$data = code_receipt_class_data("view", $where);

		if ($data["view_yn"] == '') $data["view_yn"] = 'Y';
		if ($data["default_yn"] == '') $data["default_yn"] = 'N';
        if ($data["import_yn"] == '') $data["import_yn"] = 'Y';
		if ($data["part_idx"] == '' || $data["part_idx"] == '0') $data["part_idx"] = $code_part;
		if ($menu_depth == "") $menu_depth = $data["menu_depth"];

        $where = "  del_yn = 'N' " . (!$comp_idx && !$part_idx ? " and import_yn = 'Y'":"");
        $where = $where .($comp_idx ? " and comp_idx = '" . $code_comp . "'" : "");
        $where = $where .($part_idx ? " and part_idx = '" . $code_part . "'" : "");
		$depth_data = query_view("select max(menu_depth) as max_depth from code_receipt_class where " . $where ." limit 1");
		if($depth_data["max_depth"] == "") $max_depth = 1;
		else $max_depth = $depth_data["max_depth"] + 1;

        if($max_depth >3) $max_depth = 3;

		if ($menu_depth == "") $menu_depth = 1;
?>

                                           <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">접수분류 등록</h3>
                                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="ki-outline ki-cross fs-1"></i>
                                                        </div>
                                                    </div>
                                                    <form id="postform" name="postform" method="post" class="form" action="<?=$this_page;?>" onsubmit="return check_form()">
                                                        <div class="modal-body">
                                                           
                                                            <div class="row py-2">
                                                                <div class="col-3">
                                                                    <label for="post_menu_depth"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">분류단계</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 fv-row fv-plugins-icon-container">
                                                                    <select name="param[menu_depth]" id="post_menu_depth" 
																		data-control="select2" data-hide-search="true" 
																		data-placeholder="분류단계를 선택해 주세요" 
																		aria-label="분류단계를 선택해 주세요" 
                                                                        title="분류단계를 선택해 주세요"
																		class="form-select form-select-sm" onchange="up_menu_change(this.value, '<?=$code_idx;?>', '');">

                                                                        <option>분류단계를 선택해 주세요.</option>
																		<?
																			for($i = 1; $i <= $max_depth; $i++) {
																		?>
																				<option value="<?=$i;?>" <?=selected($menu_depth, $i);?>><?=$i;?>차 분류</option>
																		<?
																			}
																		?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="row py-2">
                                                                <div class="col-3">
                                                                    <label for="chk_menu1"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">상위분류</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 fv-row fv-plugins-icon-container">
                                                                    <input type="hidden" name="param[receipt_code]" id="post_receipt_code" value="<?=$data['receipt_code'];?>">
																	<div class="left" id="up_menu_list">
																		선택한 상위분류가 없습니다.
																	</div>

                                                                    <!--select name="param[menu1]" id="chk_menu1"
                                                                        data-control="select2" data-hide-search="true"
                                                                        data-placeholder="1차 분류를 선택해 주세요"
                                                                        aria-label="1차 분류를 선택해 주세요"
                                                                        class="form-select form-select-sm mb-2">
                                                                        <option>1차 분류를 선택해 주세요.</option>
                                                                        <option value="1">웹사이트관련</option>
                                                                        <option value="46">하드웨어관련</option>
                                                                        <option value="47" selected="selected">문의사항관련
                                                                        </option>
                                                                        <option value="435">평생학습센터</option>
                                                                    </select>

                                                                    <select name="param[menu2]" id="chk_menu2"
                                                                        data-control="select2" data-hide-search="true"
                                                                        data-placeholder="2차 분류를 선택해 주세요"
                                                                        aria-label="2차 분류를 선택해 주세요"
                                                                        class="form-select form-select-sm mb-2">
                                                                        <option>2차 분류를 선택해 주세요.</option>
                                                                        <option value="2" selected="selected">컨텐츠 수정/추가
                                                                        </option>
                                                                        <option value="11">오류수정요청</option>
                                                                    </select>

                                                                    <select name="param[menu3]" id="chk_menu3"
                                                                        data-control="select2" data-hide-search="true"
                                                                        data-placeholder="3차 분류를 선택해 주세요"
                                                                        aria-label="3차 분류를 선택해 주세요"
                                                                        class="form-select form-select-sm mb-2">
                                                                        <option>3차 분류를 선택해 주세요.</option>
                                                                        <option value="3" selected="selected">내용수정
                                                                        </option>
                                                                        <option value="4">메뉴수정</option>
                                                                        <option value="5">배너추가
                                                                        </option>
                                                                        <option value="6">팝업추가</option>
                                                                    </select-->
                                                                </div>
                                                            </div>

                                                            <div class="row py-2">
                                                                <div class="col-3">
                                                                    <label for="post_code_name"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">분류명</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 fv-row fv-plugins-icon-container">
                                                                    <input type="text" name="param[code_name]" id="post_code_name" title="분류명을 입력하세요."
                                                                        class="form-control form-control-sm maxlength" placeholder="분류명을 입력하세요." maxlength="25"
                                                                        value="<?=$data['code_name'];?>">
                                                                </div>
                                                            </div>

                                                            <div class="row py-2">
                                                                <div class="col-3">
                                                                    <label for="post_view_yn"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">보기여부</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-3 fv-row fv-plugins-icon-container">
                                                                    <div
                                                                        class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-3">
                                                                        <input name="param[view_yn]" id="post_view_yn" type="checkbox" value="Y"
                                                                            class="form-check-input w-25px h-15px w-xl-30px h-xl-20px"
																			<?=$data["view_yn"]=="Y"?"checked=\"checked\"":""?> />
                                                                    </div>
                                                                </div>

                                                                <div class="col-3">
                                                                    <label for="post_default_yn"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">기본여부</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-3 fv-row fv-plugins-icon-container">
                                                                    <div
                                                                        class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-3">
                                                                        <input name="param[default_yn]" id="post_default_yn" type="checkbox" value="Y"
                                                                            class="form-check-input w-25px h-15px w-xl-30px h-xl-20px"
                                                                            <?=$data["default_yn"]=="Y"?"checked=\"checked\"":""?> />
                                                                    </div>
                                                                </div>

																<div class="col-3">
                                                                    <label for="post_import_yn"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">기본설정여부</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-3 fv-row fv-plugins-icon-container">
                                                                    <div
                                                                        class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-3">
                                                                        <input name="param[import_yn]" id="post_import_yn" type="checkbox"  value="Y"
                                                                            class="form-check-input w-25px h-15px w-xl-30px h-xl-20px"
                                                                            <?=$data["import_yn"]=="Y"?"checked=\"checked\"":""?> />
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
															<button type="button" class="btn btn-sm btn-secondary d-print-none" data-bs-dismiss="modal">
																<i class="ki-outline ki-arrows-circle fs-6"></i> 취소
                                                            </button>

														<?
														if ($code_idx == "") {
														?>
                                                            <button type="button" class="btn btn-sm btn-warning d-print-none"  onclick="check_form()">
																<i class="ki-outline ki-pencil fs-6"></i> 등록
                                                            </button>
															<input type="hidden" name="sub_type" value="post" />
														<?}else{?>	
															 <button type="button" class="btn btn-sm btn-warning d-print-none"  onclick="check_form()">
																<i class="ki-outline ki-pencil fs-6"></i> 수정
                                                            </button>
															<input type="hidden" name="sub_type" value="modify" />
															<input type="hidden" name="code_idx" value="<?=$code_idx;?>" />
														<?}?>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
<script type="text/javascript">
//<![CDATA[
	up_menu_change('<?=$menu_depth;?>', '<?=$code_idx;?>', '');
//]]>
</script>
<?
	}
?>