<?
/*
	생성 : 2012.07.03
	위치 : 설정관리 > 접수관리 > 에이전트관리 > 배너관리 - 등록/수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp      = $_SESSION[$sess_str . '_comp_idx'];
	$code_part      = search_company_part($code_part);
	$set_banner_cnt = $comp_set_data['banner_cnt'];
	$ab_idx    = $idx;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	if ($auth_menu['int'] == 'Y' && $ab_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $ab_idx != '') // 수정권한
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
    $where = " and ab.comp_idx = '" . $code_comp . "' and ab.part_idx = '" . $code_part . "'";
	$list = agent_banner_data('list', $where, '', '', '');
	if ($set_banner_cnt <= $list['total_num'])
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth('더이상 배너를 등록할 수 없습니다.<br />최대 <?=$set_banner_cnt;?>개까지 가능합니다.');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		if($ab_idx !=""){
			$where = " and ab.ab_idx = '" . $ab_idx . "'";
			$data = agent_banner_data("view", $where);

			if ($data["view_yn"] == '') $data["view_yn"] = 'Y';
			if ($data["part_idx"] == '' || $data["part_idx"] == '0') $data["part_idx"] = $code_part;
		}else{
			$file_upload_num = 1;
		}
?>
	
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">배너 등록</h3>
                                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="ki-outline ki-cross fs-1"></i>
                                                        </div>
                                                    </div>
                                                    <form action="#" id="form-school" class="form" method="post">
                                                        <div class="modal-body">

                                                            <div class="row py-2">
                                                                <div class="col-3 border-bottom pb-3">
                                                                    <label for="post_part_idx"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">지사</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 border-bottom pb-3">
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
                                                            </div>

                                                            <div class="row py-2">
                                                                <div class="col-3 border-bottom pb-3">
                                                                    <label for="post_content"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">배너명</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 border-bottom pb-3">
                                                                    <input type="text" name="param[content]"
                                                                        id="post_content"
                                                                        class="form-control form-control-sm maxlength"
                                                                        placeholder="배너명을 입력하세요." maxlength="20"
                                                                        value="홈스토리 솔루션">
                                                                </div>
                                                            </div>

                                                            <div class="row py-2">
                                                                <div class="col-3 border-bottom pb-3">
                                                                    <label for="post_link_url"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">배너 링크주소</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 border-bottom pb-3">
                                                                    <input type="text" name="param[link_url]"
                                                                        id="post_link_url"
                                                                        class="form-control form-control-sm maxlength"
                                                                        placeholder="배너 링크주소 입력하세요." maxlength="25"
                                                                        value="http://www.ubstory.net">
                                                                </div>
                                                            </div>

                                                            <div class="row py-2">
                                                                <div class="col-3 border-bottom pb-3">
                                                                    <label for="post_view_yn"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">보기여부</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 border-bottom pb-3">
                                                                    <div
                                                                        class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-3">
                                                                        <input
                                                                            class="form-check-input w-25px h-15px w-xl-30px h-xl-20px"
                                                                            type="checkbox" id="post_view_yn"
                                                                            checked="checked" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row py-2">
                                                                <div class="col-12">
                                                                    <label for="post_view_yn"
                                                                        class="fs-7 fw-semibold mt-3">
                                                                        <span class="required">파일 (374px * 100px)</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12 border-bottom pb-3">
                                                                    <div
                                                                        class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-3">
                                                                        <?php
                                                                            include_once("../common/attachmentUpload.php");
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button"
                                                                class="btn btn-sm btn-secondary d-print-none"
                                                                data-bs-dismiss="modal"><i
                                                                    class="ki-outline ki-arrows-circle fs-6"></i>
                                                                취소
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-warning d-print-none"><i
                                                                    class="ki-outline ki-pencil fs-6"></i> 등록
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

    <script type="text/javascript">
    //<![CDATA[
        up_menu_change('<?=$menu_depth;?>', '<?=$ccg_idx;?>');
    //]]>
    </script>
<?
	}
?>