<?
/*
	수정 : 2013.03.37
	위치 : 설정폴더 > 거래처관리 > 거래처등록/수정 - 등록, 수정
*/
	//require_once "../common/setting.php";
	//require_once "../common/no_direct.php";
	//require_once "../common/member_chk.php";

	$code_comp      = $_SESSION[$sess_str . '_comp_idx'];
	//$code_part      = search_company_part($code_part);
    $code_part      = "";
	$set_client_cnt = $comp_set_data['client_cnt'];
	$set_tax_yn     = $comp_set_data['tax_yn'];
	$ci_idx         = $idx;

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

	$form_chk = 'N';
	if($auth_menu['int'] == 'Y' && $ci_idx == '')
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && ($ci_idx != '' || $comp_client_idx != '')) // 수정권한
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
		$where = " and comp.comp_idx = '" . $code_comp . "'";
		$comp_info = company_info_data("view", $where);
		$comp_class = $comp_info['comp_class'];
	
	////////// 학교 ////////////////////////////////////////////
		//if($comp_class == '1')
		//{
			
			
			$client_where = $comp_client_idx ? " and ci.comp_client_idx = '" . $comp_client_idx . "'" : " and ci.ci_idx = '" . $ci_idx . "'";
			$client_data = client_info_data("view", $client_where);
			//print_r($client_data);

			if($comp_client_idx == '') $comp_client_idx = $client_data['comp_client_idx'];
			if($ci_idx == '') $ci_idx = $client_data['ci_idx'];
			

			$where = " and comp.comp_idx = '" . $comp_client_idx . "'";
			$data = company_info_data("view", $where);

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

			$client_email = $data['comp_email'];
			$client_email_arr = explode('@', $client_email);
			$data['client_email1'] = $client_email_arr[0];
			$data['client_email2'] = $client_email_arr[1];

			$data['remark'] = $client_data['remark'];
			$data['memo1'] = $client_data['memo1'];

			$link_url = $data['link_url'];
			$link_url_arr = explode(',', $link_url);

			$ip_info = $data['ip_info'];
			$ip_info_arr = explode(',', $ip_info);

			$charge_info = $data['charge_info'];
			$charge_info_arr = explode('||', $charge_info);
			
			$data['ccg_idx'] = $client_data['ccg_idx'];

			$data['view_yn'] = $client_data['view_yn'];
			if ($data['view_yn'] == '') $data['view_yn'] = 'Y';
			
			$data['ip_yn'] = $client_data['ip_yn'];
			if ($data['ip_yn'] == '') $data['ip_yn'] = 'N';

			$data['part_idx'] = $client_data['part_idx'];
			if ($data['part_idx'] == '' || $client_data['part_idx'] == '0') $data['part_idx'] = $code_part;

			$data['receipt_yn'] = $client_data['receipt_yn'];
			if ($data['receipt_yn'] == '') $data['receipt_yn'] = 'Y';

			$data['receipt_email_yn'] = $client_data['receipt_email_yn'];
			if ($data['receipt_email_yn'] == '') $data['receipt_email_yn'] = 'Y';

			$data['receipt_push_yn'] = $client_data['receipt_push_yn'];
			if ($data['receipt_push_yn'] == '') $data['receipt_push_yn'] = 'Y';

			
			$where = " and receipt_code != '' and import_yn='N' ";
			if($comp_info['sc_code'] && !$comp_info['org_code'] && !$comp_info['schul_code']) 
				$where .= " and (code.sc_code = '" . $comp_info['sc_code'] . "' and  code.org_code = '' and code.schul_code = '')";

			else if($comp_info['org_code'] && $comp_info['org_code'] && !$comp_info['schul_code']) 
				$where .= " and (code.sc_code = '" . $comp_info['sc_code'] . "' or  code.org_code = '" . $comp_info['org_code'] . "') and code.schul_code = ''";

			else if($comp_info['schul_code'] && $comp_info['org_code'] && $comp_info['schul_code']) 
				$where .= " and ( code.sc_code = '" . $comp_info['sc_code'] . "' or  code.org_code = '" . $comp_info['org_code'] . "' or code.schul_code = '" . $comp_info['schul_code'] . "' )";
			
			$receipt_class_data = code_receipt_class_data('list', $where, '', '', '');

			$staff_where = " and mem.comp_idx = '" . $comp_client_idx . "'";
			$staff_data = member_info_data('list', $staff_where);
		//}
		//else
		//{

		//}

		
?>
										<!-- 거래처등록/수정 -->
                                        <!-- 안내 -->
                                        <div
                                            class="alert alert-warning d-flex align-items-center border-0 fs-7 text-warning">
                                            <ul class="mb-0">
                                                <li>사용자, 계약정보는 거래처를 먼저 등록한 후 사용하세요.</li>
                                            </ul>
                                        </div>
                                        <!--// 안내  -->

                                        <!-- 기본정보 입력 -->
                                        <form id="postform" name="postform" method="post" class="form mb-6"  action="<?=$this_page;?>" onsubmit="return check_form()">
											<input type='hidden' name="param[comp_client_idx]" id="post_comp_client_idx" value="<?=$comp_client_idx?>">
											<?=$form_all;?>
                                            
                                            <div class="row py-2 py-xl-4">
                                                <!-- 거래처명 -->
                                                <div class="col-xl-2">
                                                    <div for="post_client_name" class="fs-6 fw-semibold my-2">
                                                        <span class="required">거래처명</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <input type="text" name="param[client_name]" id="post_client_name" value="<?=$data['comp_name']?>" placeholder="거래처명을 입력하세요." class="form-control form-control-sm" maxlength="20" readonly>
                                                </div>
                                                <!--// 거래처명 -->
                                            </div>

                                            

                                            <div class="row py-2 py-xl-4">
                                                <!-- 연락처 -->
                                                <div class="col-xl-2">
                                                    <label for="post_tel_num1" class="fs-6 fw-semibold my-2">
                                                        <span class="required">연락처</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <div class="d-flex flex-row flex-column-fluid">
                                                        <div class="d-flex flex-row-fluid flex-center">
                                                            <select name="param[tel_num1]" id="post_tel_num1" data-control="select2" data-hide-search="true" data-placeholder="선택" aria-label="전화번호 앞자리" class="form-select form-select-sm">
																<option value="">선택</option>
																<?foreach($set_telephone as $key => $val){?>
																	<option value="<?=$key?>" <?=$key==$data['tel_num1']?"selected":""?>><?=$val?></option>
																<?}?>
															</select>
                                                        </div>
                                                        <div class="d-flex flex-row-auto w-20px flex-center text-gray-400"> - </div>
                                                        <div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
                                                            <input type="text" name="param[tel_num2]" id="post_tel_num2" value="<?=$data['tel_num2']?>" maxlength="4" 
                                                                class="form-control form-control-sm" placeholder="0000" maxlength="4" aria-label="연락처 중간번호" />
                                                        </div>
                                                        <div class="d-flex flex-row-auto w-20px flex-center text-gray-400"> - </div>
                                                        <div class="d-flex flex-row-auto w-60px w-lg-80px flex-center">
                                                            <input type="text" name="param[tel_num3]" id="post_tel_num3" value="<?=$data['tel_num3']?>" maxlength="4"
                                                                class="form-control form-control-sm" placeholder="0000" aria-label="연락처 끝번호" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--// 연락처 -->

                                                <!-- 팩스번호 -->
                                                <div class="col-xl-2">
                                                    <label for="post_fax_num1" class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                        <span>팩스번호</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <div class="d-flex flex-row flex-column-fluid">
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
                                                    <div for="post_client_email1" class="fs-6 fw-semibold my-2">
                                                        <span>이메일</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-10 fv-row fv-plugins-icon-container">
                                                    <div class="row gx-2">
                                                        <div class="col-6 col-md-4">
															<input type="text" name="param[client_email1]" id="post_client_email1" title="이메일 아이디를 입력하세요." value="<?=$data['client_email1'];?>" class="form-control form-control-sm" aria-label="이메일 아이디를 입력하세요." placeholder="이메일 아이디를 입력하세요." maxlength="30">
														</div>
														<div class="col-6 col-md-3">
															<input type="text" name="param[client_email2]" id="post_client_email2" title="이메일 주소를 입력하세요." value="<?=$data['client_email2'];?>" class="form-control form-control-sm common_email2" aria-label="이메일 주소를 입력하세요." placeholder="이메일 주소를 입력하세요." maxlength="40">
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

                                            <!-- 주소 -->
                                            <div class="row py-2 py-xl-4">
                                                <div class="col-xl-2">
                                                    <label for="post_zip_code" class="fs-6 fw-semibold my-2">
                                                        <span>주소</span>
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
                                            <!--// 주소 -->

                                            <!-- 링크주소 -->
                                            <div class="row py-2 py-xl-4">
                                                <div class="col-xl-2">
                                                    <div for="post_link_url" class="fs-6 fw-semibold my-2">
                                                        <span>링크주소</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-10 fv-row fv-plugins-icon-container">
                                                    <div id="kt_link">
                                                        <div data-repeater-list="kt_link">
                                                            <div data-repeater-item>
                                                                <div class="form-group row">
                                                                    <div class="col-10 col-xl-11 mb-4 pe-md-1">
                                                                        <input type="text" name="post_link_url" id="post_link_url" value="<?=$data['home_page']?>"
                                                                            class="form-control form-control-sm" placeholder="링크주소 입력" maxlength="200">
                                                                    </div>
                                                                    <div class="col-2 col-xl-1 mb-4">
                                                                        <a href="javascript:;" data-repeater-delete class="w-100 btn btn-sm btn-light-danger" title="삭제">
                                                                            <i class="ki-outline ki-trash ms-1 fs-5"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a href="javascript:;" data-repeater-create class="w-100 w-md-auto btn btn-sm btn-light-primary">
                                                            <i class="ki-duotone ki-plus fs-3"></i>추가
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--// 링크주소 -->

											<div class="row py-2 py-xl-4">
                                                <!-- 거래처 분류 -->
                                                <div class="col-xl-2">
                                                    <label for="post_ccg_idx" class="fs-6 fw-semibold my-2">
                                                        <span class="required">거래처분류</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-2">
														<select name="param[ccg_idx]" id="post_ccg_idx" title="거래처분류를 선택해 주세요" data-control="select2" data-hide-search="true" data-placeholder="거래처분류를 선택해 주세요" aria-label="거래처분류를 선택해 주세요"  class="form-select form-select-sm">
															<option value="">거래처분류를 선택하세요</option>
															<?
															$where = " and ccg.comp_idx = '" . $code_comp . "'";
															$where .= $code_part ? " and ccg.part_idx = '" . $code_part . "'" : "";
															$client_grep_data = company_client_group_data('list', $where, '', '', '');
															//print_r($client_grep_data);
															foreach($client_grep_data as $val => $client_grep_list){
																if(is_array($client_grep_list)){
																	$emp_str = str_repeat('&nbsp;', 4 * ($client_grep_list['menu_depth'] - 1));
															?>
															<option value="<?=$client_grep_list['ccg_idx']?>" data-group-code="<?=$client_grep_list['group_code']?>" <?=$client_grep_list['ccg_idx'] == $data['ccg_idx']? 'selected':''?> /> 
																<?=$emp_str?><?=$client_grep_list['group_name']?>
															</option>
															<?
															}  
																}
															?>
														   
														</select>
                                                    </div>
                                                </div>
                                                <!--// 거래처 분류 -->


                                            <div class="row py-2 py-xl-4">
                                                <!-- 담당자 -->
                                                <div class="col-xl-2">
                                                    <div for="post_code_idx1" class="fs-6 fw-semibold my-2">
                                                        <span>접수 분류 알림 설정</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-10 fv-row fv-plugins-icon-container">
                                                    <div id="kt_manager">
                                                        <div data-repeater-list="kt_manager">
														<?
														if($ci_idx != ''){
															$query_string = "select * from clent_receipt_alarm where ci_idx='" . $ci_idx . "' and del_yn='N' order by cra_idx";
															$data_sql['query_string'] = $query_string;
															$clent_receipt_alarm_data = query_list($data_sql);
															
															$i = 1;
															foreach($clent_receipt_alarm_data as $key => $alarm_list){
																if(is_array($alarm_list)){
														?>
                                                            <div data-repeater-item class="mb-xl-2" style="border-bottom: 1px solid #ddd;">
                                                                <div class="form-group row">
                                                                    <div class="col-md-5 mb-6 pe-md-4">
																		<input type="hidden" name="cra_idx[<?=$i?>]" id="cra_idx<?=$i?>" value="<?=$alarm_list['cra_idx']?>" />
																		<select name="receipt_class[<?=$i?>]" id="post_receipt_class<?=$i?>" title="접수분류를 선택해 주세요" data-control="select2" data-hide-search="true" data-placeholder="접수분류를 선택해 주세요" aria-label="접수분류를 선택해 주세요"  class="form-select form-select-sm">
																			<option value="">접수분류를 선택하세요</option>
																			<?																			
																			foreach($receipt_class_data as $val => $receipt_class_list){
																				if(is_array($receipt_class_list)){
																					$emp_str = str_repeat('&nbsp;', 4 * ($receipt_class_list['menu_depth'] - 1));
																			?>
																			<option value="<?=$receipt_class_list['code_idx']?>" data-receipt-code="<?=$receipt_class_list['receipt-code']?>" <?=$receipt_class_list['code_idx'] == $alarm_list['receipt_class']? 'selected':''?> /> 
																				<?=$emp_str?><?=$receipt_class_list['code_name']?>
																			</option>
																			<?
																			}  
																				}
																			?>
																		</select>
																	</div>
																</div>
																<div class="form-group row">
																	<div class="col-md-2 mb-4 pe-md-1">
                                                                        <label for="post_car_charge_mem_idx<?=$i?>" class="fs-7 form-label">담당자명</label>
																		<select name="charge_mem_idx[<?=$i?>]" id="post_charge_mem_idx<?=$i?>" title="담당자 선택해 주세요" data-control="select2" data-hide-search="true" data-placeholder="거래처분류를 선택해 주세요" aria-label="거래처분류를 선택해 주세요"  class="form-select form-select-sm">
																			<option value="">당당자를 선택하세요</option>
																			<?																			
																			foreach($staff_data as $val => $staff_list){
																				if(is_array($staff_list)){
																			?>
																			<option value="<?=$staff_list['mem_idx']?>" data-mem-info="<?=$staff_list['hp_num']?>/<?=$staff_list['tel_num']?>/<?=$staff_list['mem_email']?>" <?=$staff_list['mem_idx'] == $alarm_list['charge_mem_idx']? 'selected':''?> /> 
																				<?=$emp_str?><?=$staff_list['mem_name']?>
																			</option>
																			<?
																			}  
																				}
																			?>
																		</select>

                                                                        <input type="hidden" name="charge_mem_name[<?=$i?>]" id="post_charge_mem_name<?=$i?>" value="<?=$alarm_list['charge_mem_name']?>" class="form-control form-control-sm" placeholder="담당자명을 입력" maxlength="15">
                                                                    </div>
                                                                    <div class="col-md-3 mb-4 pe-md-1">
                                                                        <label for="post_charge_hp_num" class="fs-7 form-label">연락처1</label>
                                                                        <input type="text" name="charge_hp_num[<?=$i?>]" id="post_charge_hp_num<?=$i?>" value="<?=$alarm_list['charge_hp_num']?>" class="form-control form-control-sm" placeholder="연락처1 입력" maxlength="15">
                                                                    </div>
                                                                    <div class="col-md-3 mb-4 pe-md-1">
                                                                        <label for="post_charge_tel_num<?=$i?>" class="fs-7 form-label">연락처2</label>
                                                                        <input type="text" name="charge_tel_num[<?=$i?>]" id="post_charge_tel_num<?=$i?>" value="<?=$alarm_list['charge_tel_num']?>" class="form-control form-control-sm" placeholder="연락처2 입력" maxlength="15">
                                                                    </div>
                                                                    <div class="col-md-4 mb-4 pe-md-1">
                                                                        <label for="post_charge_mem_email<?=$i?>" class="fs-7 form-label">메일주소</label>
                                                                        <div class="input-group input-group-sm p-0">
                                                                            <input type="text" name="charge_email[<?=$i?>]" id="post_charge_email<?=$i?>" value="<?=$alarm_list['charge_email']?>" class="form-control form-control-sm" placeholder="이메일을 입력" maxlength="40">
                                                                            <span class="input-group-text border-0 p-0 ms-2">
                                                                                <a href="javascript:;" data-repeater-delete class="w-100 btn btn-sm btn-light-danger" title="삭제">
                                                                                    <i class="ki-outline ki-trash ms-1 fs-5"></i>
                                                                                </a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
														<?
																	$i++;
																}
															}
														}
														?>
														<?
														if($clent_receipt_alarm_data == 0){
															$i = 1;
														?>
														<div data-repeater-item>
                                                                <div class="form-group row">
                                                                    <div class="col-md-5 mb-6 pe-md-4">
																		<input type="hidden" name="cra_idx[<?=$i?>]" id="cra_idx<?=$i?>" value="<?=$alarm_list['cra_idx']?>" />
																		<select name="code_idx[<?=$i?>]" id="post_code_idx<?=$i?>" title="접수분류를 선택해 주세요" onchange="charge_mem_info(<?=$i?>)"
																			data-control="select2" data-hide-search="true" data-placeholder="거래처분류를 선택해 주세요" aria-label="거래처분류를 선택해 주세요" class="form-select form-select-sm">
																			<option value="">거래처분류를 선택하세요</option>
																			<?																			
																			foreach($receipt_class_data as $val => $receipt_class_list){
																				if(is_array($receipt_class_list)){
																					$emp_str = str_repeat('&nbsp;', 4 * ($receipt_class_list['menu_depth'] - 1));
																			?>
																			<option value="<?=$receipt_class_list['code_idx']?>" data-receipt-code="<?=$receipt_class_list['receipt_code']?>" <?=$receipt_class_list['code_idx'] == $alarm_list['ccg_idx']? 'selected':''?> /> 
																				<?=$emp_str?><?=$receipt_class_list['mem_name']?>
																			</option>
																			<?
																			}  
																				}
																			?>
																		</select>
																	</div>
																</div>
																<div class="form-group row">
																	<div class="col-md-2 mb-4 pe-md-1">
                                                                        <label for="post_car_charge_mem_idx<?=$i?>" class="fs-7 form-label">담당자명</label>
																		<select name="charge_mem_idx[<?=$i?>]" id="post_charge_mem_idx<?=$i?>" title="담당자 선택" onchange="charge_mem_info(<?=$i?>)"
																			data-control="select2" data-hide-search="true" data-placeholder="담당자 선택" aria-label="담당자 선택"  class="form-select form-select-sm">
																			<option value="">당당자를 선택하세요</option>
																			<?																			
																			foreach($staff_data as $val => $staff_list){
																				if(is_array($staff_list)){
																			?>
																			<option value="<?=$staff_list['mem_idx']?>" data-mem-info="<?=$staff_list['hp_num']?>/<?=$staff_list['tel_num']?>/<?=$staff_list['mem_email']?>" <?=$staff_list['mem_idx'] == $alarm_list['mem_idx']? 'selected':''?> /> 
																				<?=$staff_list['mem_name']?>
																			</option>
																			<?
																			}  
																				}
																			?>
																		</select>

                                                                        <input type="hidden" name="charge_mem_name[<?=$i?>]" id="post_charge_mem_name<?=$i?>" value="<?=$alarm_list['charge_mem_name']?>" class="form-control form-control-sm" placeholder="담당자명을 입력" maxlength="15">
                                                                    </div>
                                                                    <div class="col-md-3 mb-4 pe-md-1">
                                                                        <label for="post_charge_hp_num" class="fs-7 form-label">연락처1</label>
                                                                        <input type="text" name="charge_hp_num[<?=$i?>]" id="post_charge_hp_num<?=$i?>" value="<?=$alarm_list['charge_hp_num']?>" class="form-control form-control-sm" placeholder="연락처1 입력" maxlength="15">
                                                                    </div>
                                                                    <div class="col-md-3 mb-4 pe-md-1">
                                                                        <label for="post_charge_tel_num<?=$i?>" class="fs-7 form-label">연락처2</label>
                                                                        <input type="text" name="charge_tel_num[<?=$i?>]" id="post_charge_tel_num<?=$i?>" value="<?=$alarm_list['charge_tel_num']?>" class="form-control form-control-sm" placeholder="연락처2 입력" maxlength="15">
                                                                    </div>
                                                                    <div class="col-md-4 mb-4 pe-md-1">
                                                                        <label for="post_charge_mem_email<?=$i?>" class="fs-7 form-label">메일주소</label>
                                                                        <div class="input-group input-group-sm p-0">
                                                                            <input type="text" name="charge_email[<?=$i?>]" id="post_charge_email<?=$i?>" value="<?=$alarm_list['charge_mem_email']?>" class="form-control form-control-sm" placeholder="이메일을 입력" maxlength="40">
                                                                            <span class="input-group-text border-0 p-0 ms-2">
                                                                                <a href="javascript:;" data-repeater-delete class="w-100 btn btn-sm btn-light-danger" title="삭제">
                                                                                    <i class="ki-outline ki-trash ms-1 fs-5"></i>
                                                                                </a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
														<?}?>
                                                        </div>
                                                        <a href="javascript:;" data-repeater-create class="w-100 w-md-auto btn btn-sm btn-light-primary">
                                                            <i class="ki-duotone ki-plus fs-3"></i>
                                                            추가
                                                        </a>
                                                    </div>


                                                </div>
                                                <!--// 담당자 -->
                                            </div>

                                            <div class="row py-2 py-xl-4">
                                                <!-- IP차단여부 -->
                                                <div class="col-xl-2">
                                                    <div for="post_cpd_idx" class="fs-6 fw-semibold my-2">
                                                        <span>IP차단여부</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-2">
                                                        <input class="form-check-input w-35px h-20px" type="checkbox" name="param[ip_yn]" id="post_ip_yn" value='Y' <?=$data['ip_yn'] == 'Y' ? 'checked="checked"' :''?> />
                                                    </div>
                                                </div>
                                                <!--// IP차단여부 -->

                                                <!-- 허용IP -->
                                                <div class="col-xl-2">
                                                    <div for="post_ip_info1" class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                        <span>허용IP</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <div id="kt_ipAllowed">
                                                        <div data-repeater-list="post_ip_info">
                                                            <div data-repeater-item>
                                                                <div class="form-group row">
                                                                    <div class="col-10 col-md-9 mb-4 pe-md-1">
																		<?for($i=0; $i<count($ip_info_arr); $i++){?>
                                                                        <input type="type" name="post_ip_info" value="<?=$ip_info_arr[$i]?>" id="post_ip_info<?=$i+1?>" class="form-control form-control-sm" placeholder="허용IP 입력" maxlength="20">
																		<?}?>
																		<?if(count($ip_info_arr) == 0){?>
																		<input type="type" name="post_ip_info" value="" id="post_ip_info1" class="form-control form-control-sm" placeholder="허용IP 입력" maxlength="20">
																		<?}?>
                                                                    </div>
                                                                    <div class="col-2 col-md-3 mb-4">
                                                                        <a href="javascript:;" data-repeater-delete class="w-100 btn btn-sm btn-light-danger" title="삭제">
                                                                            <i class="ki-outline ki-trash ms-1 fs-5"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a href="javascript:;" data-repeater-create class="w-100 w-md-auto btn btn-sm btn-light-primary">
                                                            <i class="ki-duotone ki-plus fs-3"></i>
                                                            추가
                                                        </a>
                                                    </div>
                                                </div>
                                                <!--// 허용IP -->
                                            </div>

                                            

                                            <div class="row py-2 py-xl-4">
                                                <!-- 접수 SMS -->
                                                <div class="col-xl-2">
                                                    <div for="post_receipt_email_yn" class="fs-6 fw-semibold my-2">
                                                        <span class="required">접수 Email</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-2">
                                                        <input class="form-check-input w-35px h-20px" type="checkbox" name="param[receipt_email_yn]" id="post_receipt_email_yn" value='Y' <?=$data['receipt_email_yn'] == 'Y' ? 'checked="checked"' :''?> />
                                                    </div>
                                                </div>
                                                <!--// 접수 SMS -->

                                                <!-- 접수 Email -->
                                                <div class="col-xl-2">
                                                    <div for="post_receipt_push_yn" class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                        <span class="required">접수 Push</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-2">
                                                        <input class="form-check-input w-35px h-20px" type="checkbox" name="param[receipt_push_yn]" id="post_receipt_push_yn" value='Y' <?=$data['receipt_push_yn'] == 'Y' ? 'checked="checked"' :''?> />
                                                    </div>
                                                </div>
                                                <!--// 접수 Email -->
                                            </div>

                                            <div class="row py-2 py-xl-4">
												<?/*
                                                <!-- 점검보고서타입 -->
                                                <div class="col-xl-2">
                                                    <div for="post_report_type" class="fs-6 fw-semibold my-2">
                                                        <span class="required">점검보고서타입</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <select name="param[report_type]" id="post_report_type"
                                                        data-control="select2" data-hide-search="true"
                                                        data-placeholder="점검보고서타입을 선택해 주세요"
                                                        aria-label="점검보고서타입을 선택해 주세요"
                                                        class="form-select form-select-sm">
                                                        <option value="">점검보고서타입을 선택하세요</option>
                                                        <option value="1">홈페이지유지보수</option>
                                                        <option value="12">시스템유지보수</option>
                                                    </select>
                                                </div>
                                                <!--// 점검보고서타입 -->
												*/?>

                                                <!-- 사용여부 -->
                                                <div class="col-xl-2">
                                                    <div for="post_view_yn"
                                                        class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                        <span class="required">사용여부</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                    <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-2">
                                                        <input class="form-check-input w-35px h-20px" type="checkbox" name="param[view_yn]" id="post_post_view_yn" value='Y' <?=$data['post_view_yn'] == 'Y' ? 'checked="checked"' :''?> />
                                                    </div>
                                                </div>
                                                <!--// 사용여부 -->

												<!-- 접속정보 -->
                                            <div class="row py-2 py-xl-4">
                                                <div class="col-xl-2">
                                                    <label for="post_memo1" class="fs-6 fw-semibold my-2">
                                                        <span>접속정보</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-10 fv-row fv-plugins-icon-container">
                                                    <textarea id="post_memo1" name="param[memo1]" class="form-control form-control-sm" rows="4" placeholder="접속정보를 입력하세요"><?=$data['memo1']?></textarea>
                                                </div>
                                            </div>
                                            <!--// 접속정보 -->

                                            <!-- 간단메모 -->
                                            <div class="row py-2 py-xl-4">
                                                <div class="col-xl-2">
                                                    <label for="post_remark" class="fs-6 fw-semibold my-2">
                                                        <span>간단메모</span>
                                                    </label>
                                                </div>
                                                <div class="col-xl-10 fv-row fv-plugins-icon-container">
                                                    <textarea id="post_remark" name="param[remark]" class="form-control form-control-sm" rows="4" placeholder="간단메모를 입력하세요"><?=$data['remark']?></textarea>
                                                </div>
                                            </div>
                                            <!--// 간단메모 -->

                                            </div>

                                            <!-- 세금계산서관련 정보입력 -->
											<?/*
                                            <div class="card card-dashed my-8 my-lg-10">
                                                <div class="card-header">
                                                    <h3 class="card-title fs-4">세금계산서관련 정보입력</h3>
                                                    <div class="card-toolbar">
                                                        <div
                                                            class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-2">
                                                            <input class="form-check-input w-35px h-20px"
                                                                type="checkbox" id="same_info" name="same_info"
                                                                value="Y" />
                                                            <label for="same_info" class="ms-2">기본정보가 동일합니다.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body py-2">
                                                    <div class="row py-2 py-xl-4">
                                                        <!-- 상호명 -->
                                                        <div class="col-xl-2">
                                                            <div for="post_tax_comp_name" class="fs-6 fw-semibold my-2">
                                                                <span class="required">상호명</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                            <input type="text" name="param[tax_comp_name]"
                                                                id="post_tax_comp_name"
                                                                class="form-control form-control-sm"
                                                                placeholder="상호명을 입력하세요." maxlength="20">
                                                        </div>
                                                        <!--// 상호명 -->

                                                        <!-- 대표자명 -->
                                                        <div class="col-xl-2">
                                                            <div for="post_tax_boss_name"
                                                                class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                                <span class="required">대표자명</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                            <input type="text" name="param[tax_boss_name]"
                                                                id="post_tax_boss_name"
                                                                class="form-control form-control-sm"
                                                                placeholder="대표자명을 입력하세요." maxlength="10">
                                                        </div>
                                                        <!--// 대표자명 -->
                                                    </div>

                                                    <div class="row py-2 py-xl-4">
                                                        <!-- 사업자등록번호 -->
                                                        <div class="col-xl-2">
                                                            <div for="post_tax_comp_num1" class="fs-6 fw-semibold my-2">
                                                                <span class="required">사업자등록번호</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                            <div class="d-flex flex-row flex-column-fluid">
                                                                <div class="d-flex flex-row-fluid flex-center">
                                                                    <input type="text" name="param[tax_comp_num1]"
                                                                        id="post_tax_comp_num1"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="000" maxlength="3"
                                                                        aria-label="사업자등록번호 처음번호" />
                                                                </div>
                                                                <div
                                                                    class="d-flex flex-row-auto w-20px flex-center text-gray-400">
                                                                    -
                                                                </div>
                                                                <div class="d-flex flex-row-auto w-75px flex-center">
                                                                    <input type="text" name="param[tax_comp_num2]"
                                                                        id="post_tax_comp_num2"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="00" maxlength="2"
                                                                        aria-label="사업자등록번호 중간번호" />
                                                                </div>
                                                                <div
                                                                    class="d-flex flex-row-auto w-20px flex-center text-gray-400">
                                                                    -
                                                                </div>
                                                                <div class="d-flex flex-row-auto w-75px flex-center">
                                                                    <input type="text" name="param[tax_comp_num3]"
                                                                        id="post_tax_comp_num3"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="00000" maxlength="5"
                                                                        aria-label="사업자등록번호 끝번호" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--// 사업자등록번호 -->
                                                    </div>

                                                    <div class="row py-2 py-xl-4">
                                                        <!-- 업종 -->
                                                        <div class="col-xl-2">
                                                            <div for="post_tax_upjong" class="fs-6 fw-semibold my-2">
                                                                <span class="required">업종</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                            <input type="text" name="param[tax_upjong]"
                                                                id="post_tax_upjong"
                                                                class="form-control form-control-sm"
                                                                placeholder="업종을 입력하세요." maxlength="50">
                                                        </div>
                                                        <!--// 업종 -->

                                                        <!-- 업태 -->
                                                        <div class="col-xl-2">
                                                            <div for="post_tax_uptae"
                                                                class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                                <span class="required">업태</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                            <input type="text" name="param[tax_uptae]"
                                                                id="post_tax_uptae" class="form-control form-control-sm"
                                                                placeholder="업태를 입력하세요." maxlength="50">
                                                        </div>
                                                        <!--// 업태 -->
                                                    </div>

                                                    <!-- 사업장주소 -->
                                                    <div class="row py-2 py-xl-4">
                                                        <div class="col-xl-2">
                                                            <label for="zip_code" class="fs-6 fw-semibold my-2">
                                                                <span class="required">사업장주소</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-10 fv-row fv-plugins-icon-container">
                                                            <div class="row gx-2">
                                                                <div class="col-md-4 mb-2">
                                                                    <div
                                                                        class="position-relative d-flex align-items-center">
                                                                        <input type="text" id="zip_code"
                                                                            class="form-control form-control-sm common_zip_code"
                                                                            aria-label="우편번호 앞자리를 입력하세요."
                                                                            placeholder="우편번호" maxlength="4"
                                                                            disabled="disabled">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-dark position-absolute end-0 px-6 rounded-start-0"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#pop-postcode"
                                                                            data-form-id="form-partner">우편번호찾기</button>
                                                                    </div>
                                                                </div>
                                                                <div class="col-8 d-none d-md-block"></div>
                                                                <div class="col-md-6 mb-2 mb-md-0">
                                                                    <input type="text"
                                                                        class="form-control form-control-sm common_address1"
                                                                        aria-label="주소를 입력하세요." placeholder="주소를 입력하세요."
                                                                        maxlength="80" disabled="disabled">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text"
                                                                        class="form-control form-control-sm common_address2"
                                                                        aria-label="상세주소를 입력하세요."
                                                                        placeholder="상세주소를 입력하세요." maxlength="80">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--// 사업장주소 -->

                                                    <!-- 전자계산서 담당자메일 -->
                                                    <div class="row py-2 py-xl-4">
                                                        <div class="col-xl-2">
                                                            <label for="zip_code" class="fs-6 fw-semibold my-2">
                                                                <span class="required">전자계산서<br /> 담당자메일</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-10 fv-row fv-plugins-icon-container">
                                                            <div class="row gx-2 mt-3">
                                                                <div class="col-6 col-md-4">
                                                                    <input type="text" name="user_email1"
                                                                        id="user_email1"
                                                                        class="form-control form-control-sm"
                                                                        aria-label="이메일 아이디를 입력하세요."
                                                                        placeholder="이메일 아이디를 입력하세요." maxlength="30"
                                                                        value="arachi76">
                                                                </div>
                                                                <div class="col-6 col-md-3">
                                                                    <input type="text" name="user_email2"
                                                                        class="form-control form-control-sm common_email2"
                                                                        aria-label="이메일 주소를 입력하세요."
                                                                        placeholder="이메일 주소를 입력하세요." maxlength="40"
                                                                        value="naver.com">
                                                                </div>
                                                                <div class="col-md-5 mt-2 mt-md-0">
                                                                    <div
                                                                        class="position-relative d-flex align-items-center">
                                                                        <select name="user_email3"
                                                                            data-control="select2"
                                                                            data-hide-search="true"
                                                                            data-placeholder="이메일 선택"
                                                                            aria-label="이메일 선택하세요"
                                                                            class="form-select form-select-sm common_email3">
                                                                            <option>이메일 선택하세요</option>
                                                                            <option value="naver.com">naver.com</option>
                                                                            <option value="google.com"
                                                                                selected="selected">
                                                                                google.com</option>
                                                                            <option value="hanmail.net">hanmail.net
                                                                            </option>
                                                                            <option value="nate.com">nate.com</option>
                                                                            <option value="kakao.com">kakao.com</option>
                                                                            <option value="직접입력">직접입력</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--// 전자계산서 담당자메일 -->
                                                </div>
                                            </div>
                                            <!-- 세금계산서관련 정보입력 -->
											*/?>

                                            <div class="separator separator-dashed mt-4 mt-xl-0 mb-6 mb-lg-8"></div>
                                            <div class="mb-8 mb-lg-10 text-end">
												<button type="button" class="btn btn-sm btn-secondary" onclick="data_list_open()">
													<i class="ki-outline ki-arrows-circle fs-6"></i> 취소
												</button>

                                                <?
												if ($ci_idx == '') {
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
												<input type="hidden" name="ci_idx" value="<?=$ci_idx;?>" />
												<button type="button" class="btn btn-sm btn-warning" onclick="return check_form()" >
													<i class="ki-outline ki-pencil fs-6"></i> 수정
												</button>
												<?

													}
												?>
                                            </div>

											

                                        </form>
                                        <!-- //기본정보 입력 -->
										
										
                                        <!-- 사용자정보 -->
                                        <form id="form-partner-user" name="postform" method="post" class="form mb-6" action="#">
                                            <div class="card card-dashed mb-8 mb-lg-10">
                                                <div class="card-header bg-gray-100i min-h-45px py-2 px-6 px-lg-8">
                                                    <h3 class="card-title fs-5 fw-semibold collapsible cursor-pointer rotate"
                                                        data-bs-toggle="collapse" data-bs-target="#kt_user_collapsible">
                                                        <span class="rotate-180 me-2">
                                                            <i class="ki-duotone ki-down fs-1"></i>
                                                        </span>사용자정보 <span
                                                            class="text-warning fs-6 ms-1 mt-1">[<?=$staff_data['total_num']?>]</span>
                                                    </h3>

                                                    <!--div class="card-toolbar">
                                                        <button type="button" class="btn btn-sm btn-danger">
                                                            <i class="ki-outline ki-user fs-6"></i> 사용자등록
                                                        </button>
                                                    </div-->
                                                </div>
												<?/*
                                                <div id="kt_user_collapsible" class="collapse">
                                                    <div class="card-body">
                                                        <!-- 안내 -->
                                                        <div
                                                            class="alert alert-warning d-flex align-items-center border-0 fs-7 text-warning">
                                                            수정할 경우만 비밀번호를 입력하세요.
                                                        </div>
                                                        <!--// 안내  -->

                                                        <div class="row py-2 py-xl-4">
                                                            <!-- 아이디 -->
                                                            <div class="col-xl-2">
                                                                <div for="cuser_mem_id" class="fs-6 fw-semibold my-2">
                                                                    <span class="required">아이디</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                                <input type="text" name="param[mem_id]"
                                                                    id="cuser_mem_id"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="아이디를 입력하세요." maxlength="30">
                                                            </div>
                                                            <!--// 아이디 -->

                                                            <!-- 비밀번호 -->
                                                            <div class="col-xl-2">
                                                                <div for="cuser_mem_pwd"
                                                                    class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                                    <span class="required">비밀번호</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                                <input type="password" name="param[mem_pwd]"
                                                                    id="cuser_mem_pwd"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="비밀번호를 입력하세요." maxlength="30">
                                                            </div>
                                                            <!--// 비밀번호 -->
                                                        </div>

                                                        <div class="row py-2 py-xl-4">
                                                            <!-- 이름 -->
                                                            <div class="col-xl-2">
                                                                <div for="cuser_mem_name" class="fs-6 fw-semibold my-2">
                                                                    <span class="required">이름</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                                <input type="text" name="param[mem_name]"
                                                                    id="cuser_mem_name"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="이름을 입력하세요." maxlength="20">
                                                            </div>
                                                            <!--// 이름 -->

                                                            <!-- 연락처 -->
                                                            <div class="col-xl-2">
                                                                <div for="cuser_tel_num"
                                                                    class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                                    <span class="required">연락처</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                                <input type="text" name="param[tel_num]"
                                                                    id="cuser_tel_num"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="연락처를 입력하세요." maxlength="20">
                                                            </div>
                                                            <!--// 연락처 -->
                                                        </div>

                                                        <!-- 사용자 이메일 -->
                                                        <div class="row py-2 py-xl-4">
                                                            <div class="col-xl-2">
                                                                <label for="zip_code" class="fs-6 fw-semibold my-2">
                                                                    <span class="required">이메일</span>
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-10 fv-row fv-plugins-icon-container">
                                                                <div class="row gx-2">
                                                                    <div class="col-6 col-md-4">
                                                                        <input type="text" name="param[mem_email1]"
                                                                            id="cuser_mem_email1"
                                                                            class="form-control form-control-sm"
                                                                            aria-label="이메일 아이디를 입력하세요."
                                                                            placeholder="이메일 아이디를 입력하세요." maxlength="30"
                                                                            value="arachi76">
                                                                    </div>
                                                                    <div class="col-6 col-md-3">
                                                                        <input type="text" name="param[mem_email2]"
                                                                            id="cuser_mem_email2"
                                                                            class="form-control form-control-sm common_email2"
                                                                            aria-label="이메일 주소를 입력하세요."
                                                                            placeholder="이메일 주소를 입력하세요." maxlength="40"
                                                                            value="naver.com">
                                                                    </div>
                                                                    <div class="col-md-5 mt-2 mt-md-0">
                                                                        <div
                                                                            class="position-relative d-flex align-items-center">
                                                                            <select name="cuser_mem_email3"
                                                                                id="cuser_mem_email3"
                                                                                data-control="select2"
                                                                                data-hide-search="true"
                                                                                data-placeholder="이메일 선택"
                                                                                aria-label="이메일 선택하세요"
                                                                                class="form-select form-select-sm common_email3">
                                                                                <option>이메일 선택하세요</option>
                                                                                <option value="naver.com">naver.com
                                                                                </option>
                                                                                <option value="google.com"
                                                                                    selected="selected">
                                                                                    google.com</option>
                                                                                <option value="hanmail.net">hanmail.net
                                                                                </option>
                                                                                <option value="nate.com">nate.com
                                                                                </option>
                                                                                <option value="kakao.com">kakao.com
                                                                                </option>
                                                                                <option value="직접입력">직접입력</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--// 사용자 이메일 -->

                                                        <!-- 로그인여부 -->
                                                        <div class="row py-2 py-xl-4">
                                                            <div class="col-xl-2">
                                                                <div for="post_view_yn_1" class="fs-6 fw-semibold my-2">
                                                                    <span class="required">로그인여부</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                                <div
                                                                    class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm mt-2">
                                                                    <input class="form-check-input w-35px h-20px"
                                                                        type="checkbox" id="post_view_yn_1"
                                                                        checked="checked" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--// 로그인여부 -->

                                                        <div
                                                            class="separator separator-dashed mt-4 mt-xl-0 mb-6 mb-lg-8">
                                                        </div>
                                                        <div class="mb-8 mb-lg-10 text-end">
                                                            <button type="button" class="btn btn-sm btn-secondary">
                                                                <i class="ki-outline ki-arrows-circle fs-6"></i> 취소
                                                            </button>
                                                            <button type="submit" class="btn btn-sm btn-warning">
                                                                <i class="ki-outline ki-pencil fs-6"></i> 등록
                                                            </button>
                                                        </div>
                                                    </div>
													*/?>
                                                    <div class="card-footer">
														<?if($staff_data['total_num'] > 0){?>
                                                        <table class="table align-middle table-striped table-row-bordered ls-n2 fs-6 fs-sm-7 text-gray-700 gy-3 gx-0">
                                                            <thead>
                                                                <tr class="text-gray-900 fw-semibold text-uppercase bg-gray-100 border-top-2 border-secondary">
                                                                    <th class="min-w-45px mw-50px d-none d-md-table-cell text-center">
                                                                        <label class="form-check form-check-custom form-check-sm ms-5">
                                                                            <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="[data-bbs-list='check']" value="0" />
                                                                        </label>
                                                                    </th>
                                                                    <th class="min-w-70px mw-80px d-none d-xl-table-cell text-center">번호</th>
                                                                    <th class="min-w-90px mw-100px d-none d-sm-table-cell text-center" data-priority="1"> 아이디</th>
                                                                    <th class="min-w-70px mw-80px text-center">이름</th>
                                                                    <th class="min-w-150px mw-200px text-center">연락처</th>
																	<!--
                                                                    <th class="min-w-60px mw-70px text-center">로그인</th>
                                                                    <th class="min-w-80px min-w-md-100px mw-125px text-center">관리</th>
																	-->
                                                                </tr>
                                                            </thead>
															
                                                            <tbody class="text-center">
															<? 
																$i = 1;
																foreach($staff_data as $skey => $slist){
																	if(is_array($slist)){
															?>
                                                                <tr>
                                                                    <td class="d-none d-md-table-cell">
                                                                        <label class="form-check form-check-custom form-check-sm ms-5">
                                                                            <input class="form-check-input" type="checkbox" data-bbs-list="check" value="1" />
                                                                        </label>
                                                                    </td>
                                                                    <td class="d-none d-xl-table-cell"><?=$i?></td>
                                                                    <td class="d-none d-sm-table-cell"><?=$slist['mem_id']?> </td>
                                                                    <td><?=$slist['mem_name']?></td>
                                                                    <td class="text-start ps-4"><?=$slist['hp_num']?></td>
                                                                    <!--td>
                                                                        <div
                                                                            class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
                                                                            <input
                                                                                class="form-check-input w-35px h-20px"
                                                                                type="checkbox"
                                                                                id="login_Switch_2349" />
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" data-bs-toggle="modal"
                                                                            data-bs-target="#kt_modal_contract"
                                                                            class="btn btn-sm btn-primary px-3 py-1 fs-8">
                                                                            수정
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger px-3 py-1 fs-8">
                                                                            삭제
                                                                        </button>
                                                                    </td-->
                                                                </tr>
															<?	
																	$i++;
																	}
																}	
															?>

                                                            </tbody>
                                                        </table>
														<?}?>
														<? if($staff_data['total_num'] == '0'){?>
                                                        <div class="text-center py-10 text-danger">등록된 데이타가 없습니다.</div>
														<?}?>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- //사용자정보 -->

                                        <!-- 계약정보 -->
										<?/*
                                        <form id="form-partner-contract" name="postform" method="post" class="form mb-6"
                                            action="#">
                                            <div class="card card-dashed mb-8 mb-lg-10">
                                                <div class="card-header bg-gray-100i min-h-45px py-2 px-6 px-lg-8">
                                                    <h3 class="card-title fs-5 fw-semibold collapsible cursor-pointer rotate"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#kt_contract_collapsible">
                                                        <span class="rotate-180 me-2">
                                                            <i class="ki-duotone ki-down fs-1"></i>
                                                        </span>계약정보 <span class="text-warning fs-6 ms-1 mt-1">[2]</span>
                                                    </h3>

                                                    <div class="card-toolbar">
                                                        <button type="button" class="btn btn-sm btn-danger">
                                                            <i class="ki-outline ki-user fs-6"></i> 계약등록
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="kt_contract_collapsible" class="collapse">
                                                    <div class="card-body">
                                                        <div class="row py-2 py-xl-4">
                                                            <!-- 구분 -->
                                                            <div class="col-xl-2">
                                                                <div for="cuser_mem_id" class="fs-6 fw-semibold my-2">
                                                                    <span class="required">구분</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                                <div
                                                                    class="form-check form-check-custom form-check-inline mt-1">
                                                                    <input class="form-check-input" type="radio"
                                                                        value="" id="contract_contract_type_1"
                                                                        name="param[contract_type]" value="maintenance"
                                                                        checked="checked" />
                                                                    <label class="form-check-label"
                                                                        for="contract_contract_type_1">
                                                                        유지보수
                                                                    </label>
                                                                </div>
                                                                <div
                                                                    class="form-check form-check-custom form-check-inline mt-1">
                                                                    <input class="form-check-input" type="radio"
                                                                        value="" id="contract_contract_type_2"
                                                                        name="param[contract_type]" checked="checked" />
                                                                    <label class="form-check-label"
                                                                        for="contract_contract_type_2">
                                                                        기타계약
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!--// 구분 -->
                                                        </div>

                                                        <div class="row py-2 py-xl-4">
                                                            <!-- 계약명 -->
                                                            <div class="col-xl-2">
                                                                <div for="contract_subject"
                                                                    class="fs-6 fw-semibold my-2">
                                                                    <span class="required">계약명</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                                <input type="text" name="param[subject]"
                                                                    id="contract_subject"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="계약명을 입력하세요." maxlength="50">
                                                            </div>
                                                            <!--// 계약명 -->

                                                            <!-- 계약일 -->
                                                            <div class="col-xl-2">
                                                                <div for="contract_contract_date"
                                                                    class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                                    <span class="required">계약일</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                                <input type="text" name="param[contract_date]"
                                                                    id="contract_contract_date"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="계약일을 입력하세요." maxlength="10">
                                                            </div>
                                                            <!--// 계약일 -->
                                                        </div>

                                                        <div class="row py-2 py-xl-4">
                                                            <!-- 계약번호 -->
                                                            <div class="col-xl-2">
                                                                <div for="contract_contract_number"
                                                                    class="fs-6 fw-semibold my-2">
                                                                    <span class="required">계약번호</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                                <input type="text" name="param[contract_number]"
                                                                    id="contract_contract_number"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="계약번호를 입력하세요." maxlength="30">
                                                            </div>
                                                            <!--// 계약번호 -->

                                                            <!-- 착수일 -->
                                                            <div class="col-xl-2">
                                                                <div for="contract_begin_date"
                                                                    class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                                    <span class="required">착수일</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                                <input type="text" name="param[begin_date]"
                                                                    id="contract_begin_date"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="착수일을 입력하세요." maxlength="10">
                                                            </div>
                                                            <!--// 착수일 -->
                                                        </div>

                                                        <div class="row py-2 py-xl-4">
                                                            <!-- 담당자 -->
                                                            <div class="col-xl-2">
                                                                <div for="contract_charge_name"
                                                                    class="fs-6 fw-semibold my-2">
                                                                    <span class="required">담당자</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                                <input type="text" name="param[charge_name]"
                                                                    id="contract_charge_name"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="담당자를 입력하세요." maxlength="20">
                                                            </div>
                                                            <!--// 담당자 -->

                                                            <!-- 완료일 -->
                                                            <div class="col-xl-2">
                                                                <div for="contract_contract_date"
                                                                    class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                                    <span class="required">완료일</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                                <input type="text" name="param[contract_date]"
                                                                    id="contract_contract_date"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="완료일을 입력하세요." maxlength="10">
                                                            </div>
                                                            <!--// 완료일 -->
                                                        </div>

                                                        <div class="row py-2 py-xl-4">
                                                            <!-- 계약금액 -->
                                                            <div class="col-xl-2">
                                                                <div for="contract_con_price"
                                                                    class="fs-6 fw-semibold my-2">
                                                                    <span class="required">계약금액</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                                <div class="input-group input-group-sm p-0">
                                                                    <input type="text" name="param[con_price]"
                                                                        id="contract_con_price"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="계약금액을 입력하세요." maxlength="20">
                                                                    <span
                                                                        class="input-group-text bg-white border-0 p-0 ms-2">
                                                                        <div
                                                                            class="form-check form-check-custom form-check-inline mt-1">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" value="Y"
                                                                                id="contract_con_price_chk"
                                                                                name="param[con_price_chk]"
                                                                                checked="checked" />
                                                                            <label class="form-check-label"
                                                                                for="contract_con_price_chk">
                                                                                계약정산
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <!-- 월유지보수 -->
                                                            <div class="col-xl-2">
                                                                <div for="contract_month_price"
                                                                    class="fs-6 fw-semibold mt-6 mb-2 my-xl-2">
                                                                    <span class="required">월유지보수</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 fv-row fv-plugins-icon-container">
                                                                <div class="input-group input-group-sm p-0">
                                                                    <input type="text" name="param[month_price]"
                                                                        id="contract_month_price"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="월유지보수금액을 입력하세요." maxlength="20">
                                                                    <span
                                                                        class="input-group-text bg-white border-0 p-0 ms-2">
                                                                        <div
                                                                            class="form-check form-check-custom form-check-inline mt-1">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" value="Y"
                                                                                id="contract_month_price_chk"
                                                                                name="param[month_price_chk]"
                                                                                checked="checked" />
                                                                            <label class="form-check-label"
                                                                                for="contract_month_price_chk">
                                                                                유지보수정산
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <!--// 월유지보수 -->
                                                        </div>

                                                        <!-- 내용 -->
                                                        <div class="row py-2 py-xl-4">
                                                            <div class="col-xl-2">
                                                                <div for="contract_con_price"
                                                                    class="fs-6 fw-semibold my-2">
                                                                    <span class="required">내용</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-10 fv-row fv-plugins-icon-container">
                                                                <textarea id="post_contents"
                                                                    class="form-control form-control-sm" rows="10"
                                                                    placeholder="내용을 입력하세요"></textarea>
                                                            </div>
                                                        </div>
                                                        <!-- 내용 -->

                                                        <div
                                                            class="separator separator-dashed mt-4 mt-xl-0 mb-6 mb-lg-8">
                                                        </div>
                                                        <div class="mb-8 mb-lg-10 text-end">
                                                            <button type="button" class="btn btn-sm btn-secondary">
                                                                <i class="ki-outline ki-arrows-circle fs-6"></i> 취소
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-warning">
                                                                <i class="ki-outline ki-pencil fs-6"></i> 등록
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <table
                                                            class="table align-middle table-striped table-row-bordered ls-n2 fs-6 fs-sm-7 text-gray-700 gy-3 gx-0">
                                                            <thead>
                                                                <tr
                                                                    class="text-gray-900 fw-semibold text-uppercase bg-gray-100 border-top-2 border-secondary">
                                                                    <th
                                                                        class="min-w-60px mw-70px d-none d-xl-table-cell text-center">
                                                                        번호</th>
                                                                    <th class="min-w-90px mw-100px text-center"
                                                                        data-priority="1">
                                                                        계약명</th>
                                                                    <th
                                                                        class="min-w-70px mw-80px text-center d-none d-xl-table-cell">
                                                                        계약번호</th>
                                                                    <th class="min-w-80px mw-90px text-center">
                                                                        계약일</th>
                                                                    <th
                                                                        class="min-w-60px mw-70px text-center d-none d-sm-table-cell">
                                                                        착수일</th>
                                                                    <th class="min-w-60px mw-70px text-center">
                                                                        완료일</th>
                                                                    <th
                                                                        class="min-w-60px mw-70px text-center d-none d-md-table-cell">
                                                                        계약금액</th>
                                                                    <th
                                                                        class="min-w-60px mw-70px text-center d-none d-md-table-cell">
                                                                        유지보수</th>
                                                                    <th
                                                                        class="min-w-60px mw-70px text-center d-none d-xl-table-cell">
                                                                        구분</th>
                                                                    <th
                                                                        class="min-w-60px mw-70px text-center d-none d-xl-table-cell">
                                                                        담당자</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="text-center">
                                                                <tr>
                                                                    <td class="d-none d-xl-table-cell">354</td>
                                                                    <td class="text-start ps-4"><a
                                                                            href="javascript:void(0);"
                                                                            class="text-gray-800 ellipsis-1"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#kt_modal_contract">2015년
                                                                            홈페이지 유지보수</a></td>
                                                                    <td class="d-none d-xl-table-cell">20121105148-00
                                                                    </td>
                                                                    <td>12.11.05</td>
                                                                    <td class="d-none d-sm-table-cell">12.11.05</td>
                                                                    <td>12.11.12</td>
                                                                    <td class="d-none d-md-table-cell">1,244,000</td>
                                                                    <td class="d-none d-md-table-cell">110,000</td>
                                                                    <td class="d-none d-xl-table-cell">유지보수</td>
                                                                    <td class="d-none d-xl-table-cell">신미정 주무관</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="text-center py-10 text-danger">등록된 데이타가 없습니다.</div>
                                                        <div class="text-center py-10 text-danger">거래처를 먼저 등록하세요.</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="modal fade" tabindex="-1" id="kt_modal_contract">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">2015년 홈페이지 유지보수</h3>
                                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="ki-outline ki-cross fs-1"></i>
                                                        </div>
                                                    </div>
                                                    <form action="#" id="form-school" class="form" method="post">
                                                        <div class="modal-body">
                                                            <table
                                                                class="table align-middle table-block table-bordered table-striped-columns ls-n2 fs-6 text-gray-700 gy-3 gx-0 gx-sm-4">
                                                                <tbody class="text-center">

                                                                    <tr>
                                                                        <th>계약구분</th>
                                                                        <td class="text-start" colspan="3">유지보수</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th>계약명</th>
                                                                        <td class="text-start">2015년 홈페이지 유지보수</td>
                                                                        <th>계약일</th>
                                                                        <td class="text-start">2014-12-26</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th>계약번호</th>
                                                                        <td class="text-start">20121105148-00</td>
                                                                        <th>착수일</th>
                                                                        <td class="text-start">2015-01-01</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th>담당자</th>
                                                                        <td class="text-start">신미정 주무관</td>
                                                                        <th>완료일</th>
                                                                        <td class="text-start">2015-12-31</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th>계약금액</th>
                                                                        <td class="text-start">
                                                                            1,320,000 원 (계약정산 : Y)</td>
                                                                        <th>월유지보수</th>
                                                                        <td class="text-start">110,000 원 (유지보수정산 : Y)
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th>내용</th>
                                                                        <td class="text-start" colspan="3">
                                                                            2015년 군포의왕교육지원청 영재교육원 홈페이지 유지보수와 동시 계약
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger d-print-none"
                                                                data-bs-dismiss="modal"><i
                                                                    class="ki-outline ki-trash fs-6"></i>
                                                                삭제
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-warning d-print-none"><i
                                                                    class="ki-outline ki-pencil fs-6"></i> 수정
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        */?>
                                        <!-- //계약정보 -->


                                        <?php
											// 메모
											//include_once("../common/memo.php");
										?>
                                        <!-- //거래처등록/수정 -->



<script>
	function charge_mem_info(num){
		const mem_name = $.trim($('#post_charge_mem_idx'+num+" :selected").text());
		const mem_info = $.trim($('#post_charge_mem_idx'+num+" :selected").attr('data-mem-info')).split('/');
		$('#post_charge_mem_name'+num).val(mem_name);
		$('#post_charge_hp_num'+num).val(mem_info[0]);
		$('#post_charge_tel_num'+num).val(mem_info[1]);
		$('#post_charge_email'+num).val(mem_info[2]);
	}
</script>

<?
	}
?>