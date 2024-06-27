<?
/*
	수정 : 2013.05.02
	위치 : 고객관리 > 접수목록 - 등록, 수정
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem = $_SESSION[$sess_str . '_mem_idx'];
	$ri_idx    = $idx;
	
	$where = " and comp.comp_idx = '" . $code_comp . "'";
	$comp_info = company_info_data("view", $where);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shclass=' . $send_shclass . '&amp;shstatus=' . $send_shstatus . '&amp;shstaff=' . $send_shstaff;
	$f_search  = $f_search . '&amp;sdate1=' . $send_sdate1 . '&amp;sdate2=' . $send_sdate2;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"   value="' . $send_swhere . '" />
		<input type="hidden" name="stext"    value="' . $send_stext . '" />
		<input type="hidden" name="shclass"  value="' . $send_shclass . '" />
		<input type="hidden" name="shstatus" value="' . $send_shstatus . '" />
		<input type="hidden" name="shstaff"  value="' . $send_shstaff . '" />
		<input type="hidden" name="sdate1"   value="' . $send_sdate1 . '" />
		<input type="hidden" name="sdate2"   value="' . $send_sdate2 . '" />
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
	if ($auth_menu['int'] == 'Y' && $ri_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $ri_idx != '') // 수정권한
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
		
		$receipt_info = new receipt_info();
		$receipt_info->ri_idx = $ri_idx;
		$receipt_info->data_path = $comp_receipt_path;
		$receipt_info->data_dir = $comp_receipt_dir;

		$data = $receipt_info->receipt_info_view();
		$file_list = $receipt_info->receipt_file();

		if ($data['part_idx'] == '' || $data['part_idx'] == '0') $data['part_idx'] = $code_part;
		if ($data['part_name'] == '')
		{
			$sub_where = " and part.part_idx = '" . $code_part . "'";
			$sub_data = company_part_data('view', $sub_where);

			$data['part_name'] = $sub_data['part_name'];
		}

	// 직원정보
		$mem_where = " and mem.mem_idx = '" . $code_mem . "'";
		$mem_data = member_info_data('view', $mem_where);

		if ($data['writer'] == '') $data['writer'] = $mem_data['mem_name'];
		if ($data['tel_num'] == '') $data['tel_num'] = $mem_data['hp_num'];
		if ($data['important'] == '') $data['important'] = 'RI01';

	// 접수파일
		$file_query = "select max(sort) as sort from receipt_file where ri_idx = '" . $ri_idx . "'";
		$file_chk = query_view($file_query);
		$file_sort = ($file_chk['sort'] == '') ? '0' : $file_chk['sort'];

		$file_upload_num = $file_sort;
		$file_chk_num    = $file_upload_num + 1;	

	// 접수 분류
		$where = " and receipt_code != '' and import_yn='N' ";
		if($comp_info['sc_code'] && !$comp_info['org_code'] && !$comp_info['schul_code']) 
			$where .= " and (code.sc_code = '" . $comp_info['sc_code'] . "' and  code.org_code = '' and code.schul_code = '')";

		else if($comp_info['org_code'] && $comp_info['org_code'] && !$comp_info['schul_code']) 
			$where .= " and (code.sc_code = '" . $comp_info['sc_code'] . "' or  code.org_code = '" . $comp_info['org_code'] . "') and code.schul_code = ''";

		else if($comp_info['schul_code'] && $comp_info['org_code'] && $comp_info['schul_code']) 
			$where .= " and ( code.sc_code = '" . $comp_info['sc_code'] . "' or  code.org_code = '" . $comp_info['org_code'] . "' or code.schul_code = '" . $comp_info['schul_code'] . "' )";
		
		$receipt_class_data = code_receipt_class_data('list', $where, '', '', '');

?>




										<!-- 글쓰기 -->
										<form id="postform" name="postform" method="post" class="form" action="<?=$this_page;?>" onsubmit="return check_form()">
											<input type="hidden" name="param[comp_idx]" id="post_comp_idx" value="<?=$code_comp;?>" />
											<input type="hidden" name="param[part_idx]" id="post_part_idx" value="" />
											<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />
											<?=$form_all;?>

											<!-- 안내 -->
											<div class="alert bg-transparent d-flex align-items-center p-0 fs-6 text-warning">
												<strong class="text-danger fs-2 align-middle">*</strong> 는 필수 사항입니다.
											</div>
											<!--// 안내  -->

											<div class="row py-2 py-xl-4">
												<?/*
												<!-- 지사 -->
												<div class="col-xl-2">
													<label for="post_part_idx" class="fs-6 fw-semibold my-2">
														<span class="required">지사</span>
													</label>
												</div>
												<div class="col-xl-4 fv-row fv-plugins-icon-container">
												<?
												// 거래처, 접수분류
													$str_script = "part_information(this.value, 'client_info', 'post_ci_idx', '" . $data['ci_idx'] . "', ''); part_information(this.value, 'receipt_class', 'post_receipt_class', '" . $data['receipt_class'] . "', '');";
												?>
													<?=company_part_select($data['part_idx'], ' onchange="' . $str_script . '"');?>
												</div>
												<!--// 지사 -->
												

												<!-- 거래처명 -->
												<div class="col-xl-2 pt-4 pt-xl-0">
													<label for="post_ci_idx" class="fs-6 fw-semibold my-2">
														<span class="required">거래처명</span>
													</label>
												</div>
												<div class="col-xl-4 fv-row fv-plugins-icon-container">
													<select name="param[ci_idx]" id="post_ci_idx" title="거래처를 선택하세요" onchange="check_client_info(this.value)" data-control="select2" data-hide-search="true" data-placeholder="거래처를 선택하세요" class="form-select form-select-sm">
														<option value="">거래처를 선택하세요</option>
													</select>
												</div>
												<!--// 거래처명 -->
											</div>
											*/?>
											<div class="row py-2 py-xl-4">
												<!-- 작성자 -->
												<div class="col-xl-2">
													<label for="post_writer" class="fs-6 fw-semibold my-2">
														<span class="required">작성자</span>
													</label>
												</div>
												<div class="col-xl-4 fv-row fv-plugins-icon-container">
													<input type="text" name="param[writer]" id="post_writer" title="작성자를 입력하세요." value="<?=$data['writer'];?>" class="form-control form-control-sm" placeholder="작성자를 입력하세요." size="30">
												</div>
												<!--// 작성자 -->

												<!-- 연락처(위치) -->
												<div class="col-xl-2 pt-4 pt-xl-0">
													<label for="post_tel_num" class="fs-6 fw-semibold my-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click" title="휴대폰 번호 대신 사무실번호 또는 위치정보(예:00시청 00과) 입력 부탁드립니다.">
														<span class="required">연락처(위치)</span>
													</label>
												</div>
												<div class="col-xl-4 fv-row fv-plugins-icon-container">
													<input type="text" name="param[tel_num]" id="post_tel_num" title="전화번호를 입력하세요." value="<?=$data['tel_num'];?>" class="form-control form-control-sm" placeholder="연락처(위치)를 입력하세요." size="20">
													&nbsp;<span style="color:#ee6666">개인정보 유출방지를 위해 개인 휴대폰 번호는 가급적 삼가해주시고 사무실번호 또는 위치정보(예:00시청 00과)형태로 입력해 주시기바랍니다.</span>
												</div>
												<!--// 거래처명 -->
											</div>

											<div class="row py-2 py-xl-4">
												<!-- 분류 -->
												<div class="col-xl-2">
													<label for="post_receipt_class" class="fs-6 fw-semibold my-2">
														<span class="required">분류</span>
													</label>
												</div>
												<div class="col-xl-4 fv-row fv-plugins-icon-container">
													<input type="hidden" name="param[receipt_code]" id="post_receipt_code" value="<?=$code_comp;?>" />
													<select id="post_receipt_class" name="param[receipt_class]" title="분류를 선택하세요" onchange="check_receipt_code()" data-control="select2" data-hide-search="true" data-placeholder="분류를 선택하세요" class="form-select form-select-sm">
														<option value="">분류를 선택하세요</option>
														<?
														$depth = 1;
														$last_str='';
														foreach($receipt_class_data as $k => $clist)
														{
																if (is_array($clist))
																{
																	if($depth < $clist['menu_depth']) $last_str = 'last';
																	else if($depth > $clist['menu_depth']) $last_str = '';

																	$depth = $clist['menu_depth'];
																	$emp_str = str_repeat('&nbsp;', 4 * ($clist['menu_depth'] - 1));
														?>
														<option value="<?=$clist['code_idx']?>" data-receipt-code='<?=$clist['receipt_code']?>' <?=$class == $clist['receipt_code'] ? 'selected ': ''?>><?=$emp_str?><?=$clist['code_name']?></option>
														<?		}
														}
														?>
													</select>
												</div>
												<!--// 분류 -->

												<!-- 중요도 -->
												<div class="col-xl-2 pt-4 pt-xl-0">
													<label for="post_ci_idx" class="fs-6 fw-semibold my-2">
														<span class="required">중요도</span>
													</label>
												</div>
												<div class="col-xl-4 fv-row fv-plugins-icon-container mt-2">
													<div class="form-check form-check-custom form-check-sm form-check-inline">
														<input class="form-check-input" type="radio" name="param[important]" id="post_important_1" value="RI01" <?=$data['important'] == 'RI01' ? 'checked="checked"' :''?>/>
														<label class="form-check-label" for="post_important_1">
															해당없음
														</label>
													</div>
													<div class="form-check form-check-custom form-check-sm form-check-inline">
														<input class="form-check-input" type="radio" name="param[important]" id="post_important_2" value="RI02" <?=$data['important'] == 'RI02' ? 'checked="checked"' :''?>/>
														<label class="form-check-label" for="post_important_2">
															상
														</label>
													</div>
													<div class="form-check form-check-custom form-check-sm form-check-inline">
														<input class="form-check-input" type="radio" name="param[important]" id="post_important_3" value="RI03" <?=$data['important'] == 'RI03' ? 'checked="checked"' :''?>/>
														<label class="form-check-label" for="post_important_3">
															중
														</label>
													</div>
													<div class="form-check form-check-custom form-check-sm form-check-inline">
														<input class="form-check-input" type="radio" name="param[important]" id="post_important_4" value="RI04" <?=$data['important'] == 'RI04' ? 'checked="checked"' :''?>/>
														<label class="form-check-label" for="post_important_4">
															하
														</label>
													</div>
												</div>
												<!--// 중요도 -->
											</div>

											<!-- 제목 -->
											<div class="row py-2 py-xl-4">
												<div class="col-xl-2">
													<label for="post_subject" class="fs-6 fw-semibold my-2">
														<span class="required">제목</span>
													</label>
												</div>
												<div class="col-xl-10 fv-row fv-plugins-icon-container">
													<input type="text" name="param[subject]" id="post_subject" itle="제목을 입력하세요." value="<?=$data['subject'];?>" class="form-control form-control-sm" placeholder="제목을 입력하세요." size="50">
												</div>
											</div>
											<!--// 제목 -->

											<!-- 내용 -->
											<div class="row py-2 py-xl-4">
												<div class="col-xl-2">
													<label for="post_contents" class="fs-6 fw-semibold my-2">
														<span class="required">내용</span>
													</label>
												</div>
												<div class="col-xl-10 fv-row fv-plugins-icon-container h-350px h-lg-450px">
													<textarea name="param[remark]" id="post_remark" class="form-control form-control-sm" rows="14" placeholder="내용을 입력하세요" style="display:none;"></textarea>

													<div id="post_contents">
														
													</div>
												</div>
											</div>
											<!--// 내용 -->


											<!-- 첨부파일 -->
											<div class="row py-2 py-xl-4 mt-xl-6">
												<div class="col-xl-2">
													<div class="fs-6 fw-semibold my-2 pt-14 pt-xl-6">
														파일
													</div>
												</div>
												<div class="col-xl-10 fv-row fv-plugins-icon-container">
													<?php
														include_once($local_path."/bizstory/include/attachmentUpload.php");
													?>
												</div>
											</div>
											<!--// 첨부파일 -->

											<div class="separator separator-dashed mb-6 mb-lg-8"></div>
											<div class="row mb-8 mb-lg-10">
												<div class="col-6">
													<a href="javascript:void(0);" class="btn btn-sm btn-secondary d-print-none" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'">
														<i class="ki-outline ki-burger-menu fs-6"></i> 목록
													</a>
												</div>
												<div class="col-6 text-end">
													<button type="button" class="btn btn-sm btn-secondary d-print-none" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'">
														<i class="ki-outline ki-arrows-circle fs-6"></i> 취소
													</button>
												<?
													if ($ri_idx == '') {
												?>
													<input type="hidden" name="sub_type" value="post" />
													<button type="button" class="btn btn-sm btn-warning d-print-none" onclick="check_form()">
														<i class="ki-outline ki-pencil fs-6"></i> 글작성
													</button>
												<?
													} else {
												?>
													<input type="hidden" name="sub_type" value="modify" />
													<input type="hidden" name="ri_idx"   value="<?=$ri_idx;?>" />
													<button type="submit" class="btn btn-sm btn-warning d-print-none" onclick="check_form()">
														<i class="ki-outline ki-pencil fs-6"></i> 글수정
													</button>
												<?
													}
												?>
												</div>
											</div>
										</form>
										<!-- //글쓰기 -->

	<script src="<?=$local_url?>/bizstory/assets/plugins/custom/dropzone/dropzone.js"></script>
	<script>
		// 에디터
		var quill = new Quill('#post_contents', {
			 modules: {
				  toolbar: [
						[{
							 header: [1, 2, false]
						}],
						['bold', 'italic', 'underline'],
						['image', 'code-block']
				  ]
			 },
			 placeholder: '내용을 입력하세요.',
			 theme: 'snow' // or 'bubble'
		});
	</script>

<script type="text/javascript">


	//part_information('<?=$data['part_idx'];?>', 'client_info', 'post_ci_idx', '<?=$data['ci_idx'];?>', '');
	//part_information('<?=$data['part_idx'];?>', 'receipt_class', 'post_receipt_class', '<?=$data['receipt_class'];?>', '');
	
//---- 분류 코드
	function check_receipt_code()
	{
		const receipt_class = $('#post_receipt_class option:selected').attr('data-receipt-code');
		$('#post_receipt_code').val(receipt_class);
	}

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';
		/*
		var file_chk = $('#upload_select_num').val();
		if (file_chk > 0)
		{
			chk_total = chk_total + '먼저 선택한 파일을 올리세요.<br />';
			action_num++;
		}
		

		chk_value = $('#post_part_idx').val(); // 지사
		chk_title = $('#post_part_idx').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}
		
		chk_value = $('#post_ci_idx').val(); // 거래처
		chk_title = $('#post_ci_idx').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}
		*/

		chk_value = $('#post_receipt_class').val(); // 접수분류
		chk_title = $('#post_receipt_class').attr('title');
		if (chk_value == '' || chk_value == '0')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_subject').val(); // 제목
		chk_title = $('#post_subject').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}
		
		$('#post_remark').val($('.ql-editor').html());
		chk_value = $('#post_remark').val(); // 내용
		chk_title = $('#post_remark').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						
						//location.reload();
					}
					else check_auth_popup(msg.error_string);
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 거래처를 선택하면 기본데이타보여주기
	function check_client_info(idx)
	{
		$.ajax({
			type: 'post', dataType: 'json', url : '<?=$local_dir;?>/bizstory/comp_set/client_information.php',
			data : {"idx": idx},
			success : function(msg) {
				if (msg.success_chk == "Y")
				{
					$('#post_client_code').val(msg.client_code);
				}
			}
		});
	}
//]]>
</script>

<?
	}
?>
