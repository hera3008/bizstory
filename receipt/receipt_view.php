<?
/*
	수정 : 2013.04.12
	위치 : 고객관리 > 접수목록 - 보기
*/
	//require_once "../common/setting.php";
	//require_once "../common/no_direct.php";
	$page_chk = 'html';
	//require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	//$code_part = search_company_part($code_part);
	$code_part = "";
	$ri_idx    = $idx;

	$mem_idx = $_SESSION[$sess_str . '_mem_idx'];

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
	if ($auth_menu['view'] == 'Y') // 보기권한
	{
		$form_chk = 'Y';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>';
		exit;
	}

	if ($form_chk == 'Y')
	{
		/*
		$receipt_info = new receipt_info();
		$receipt_info->ri_idx = $ri_idx;
		$receipt_info->data_path = $comp_receipt_path;
		$receipt_info->data_dir = $comp_receipt_dir;

		$data      = $receipt_info->receipt_info_view();
		$file_list = $receipt_info->receipt_file();
		*/

		$where = " and ri.ri_idx = '" . $ri_idx . "'";
		$data = receipt_info_data('view', $where);
		$charge_idx = $data['charge_mem_idx']; 
		//print_r($data);
		$receipt_status_css =$set_receipt_status_css[$data['status_value']];
		
		$rc_where = " and rc.ri_idx = '" . $ri_idx . "'";
		$total_comment_data = receipt_comment_data('page', $rc_where);
		$total_comment = $total_comment_data['total_num'];
		//print_r($total_comment);

		
		$charge_query = "
			SELECT 
				ri.ci_idx, ri.charge_mem_idx, ci.mem_idx as client_mem_idx
				, mem.mem_name as charge_name, mem.del_yn as charge_del_yn
				, csg.group_name
			FROM
				receipt_info ri
				join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
				JOIN clent_receipt_alarm cra ON ri.comp_idx = cra.comp_idx AND ri.receipt_class = cra.receipt_class	
				join member_info mem on mem.mem_idx = cra.charge_mem_idx	
				join company_staff_group csg on csg.csg_idx = mem.csg_idx
			WHERE 
				ri.del_yn = 'N' ".$where
		;
		
		$charge_data = query_view($charge_query);
		
	// 접수 분류
		$where = " and comp.comp_idx = '" . $data['comp_idx'] . "'";
		$comp_info = company_info_data("view", $where);
		$where = " and receipt_code != '' and import_yn='N' ";
		if($comp_info['sc_code'] && !$comp_info['org_code'] && !$comp_info['schul_code']) 
			$where .= " and (code.sc_code = '" . $comp_info['sc_code'] . "' and  code.org_code = '' and code.schul_code = '')";

		else if($comp_info['org_code'] && $comp_info['org_code'] && !$comp_info['schul_code']) 
			$where .= " and (code.sc_code = '" . $comp_info['sc_code'] . "' or  code.org_code = '" . $comp_info['org_code'] . "') and code.schul_code = ''";

		else if($comp_info['schul_code'] && $comp_info['org_code'] && $comp_info['schul_code']) 
			$where .= " and ( code.sc_code = '" . $comp_info['sc_code'] . "' or  code.org_code = '" . $comp_info['org_code'] . "' or code.schul_code = '" . $comp_info['schul_code'] . "' )";
		
		$receipt_class_data = code_receipt_class_data('list', $where, '', '', '');

	// 직원
		$staff_where = " and mem.comp_idx = '" . $data['comp_client_idx'] . "'";
		$staff_data = member_info_data('list', $staff_where);

?>
										
									<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
										<?=$form_all;?>
										<input type="hidden" name="sub_type" id="view_sub_type" />
										<input type="hidden" name="part_idx" id="view_part_idx" value="<?=$data['part_idx'];?>" />
										<input type="hidden" name="ri_idx"   id="view_ri_idx"   value="<?=$ri_idx;?>" />
										<input type="hidden" name="rid_idx"  id="view_rid_idx"  value="" />
										<input type="hidden" name="rid_type" id="view_rid_type" value="" />
										
										<!-- 글보기 -->
										<div class="separator separator-dashed mb-6 mb-lg-8"></div>
										<div class="mb-2 mb-lg-4">
											<!-- Info -->
											<div class="d-sm-flex flex-sm-stack mb-6 fs-7">
												<div class="d-flex flex-wrap">
													<div class="me-6 me-lg-9 my-1">
														<i class="ki-outline ki-user-edit text-gray-700 fs-2 fs-lg-1 me-1 align-middle"></i>
														<span class="text-gray-800"><?=$data['writer'];?></span>
														<a href="tel:037-760-0191" class="text-primary phoneLink">(<a href="tel:<?=$data['tel_num'];?>" class="tel"><?=$data['tel_num'];?></a>)</a>
													</div>
													<div class="me-6 me-lg-9 my-1">
														<i class="ki-outline ki-calendar-tick text-gray-700 fs-2 fs-lg-1 me-1 align-middle"></i>
														<span class="text-gray-500"><?=$data['reg_date'];?></span>
													</div>
													
													<?if($total_comment > 0){?>
														<!-- 댓글 없을 경우 출력하지 않음 -->
													<div class="me-6 me-lg-9 my-1">
														<i class="ki-outline ki-message-text text-gray-700 fs-2 fs-lg-1 me-1 align-middle"></i>
															<strong class="text-danger"><?=number_format($total_comment)?></strong>
													</div>
													<!-- //댓글 없을 경우 출력하지 않음 -->
													<?}?>
													
												</div>
												<div class="text-end">
													<button type="button" class="btn btn-sm btn-light btn-active-light-primary d-print-none btn-print px-4 mt-2 mt-sm-0" aria-label="Print" data-bs-original-title="Print" data-kt-initialized="1">
														<i class="ki-outline ki-printer fs-2 mt-1"></i> 인쇄
													</button>
												</div>
											</div>
											<!-- //Info -->

											<!-- Title -->
											<h2 class="text-gray-900 fs-3 fw-semibold lh-base ls-n3">
												<?=$data['subject'];?> <?=$data['important_img'];?>
											</h2>
											<!-- //Title -->
										</div>

										<table class="table align-middle table-block table-bordered table-striped-columns ls-n2 fs-6 text-gray-700 gy-3 gx-0 gx-sm-4">
											<tbody class="text-center">
												<tr>
													<th>거래처명</th>
													<td class="text-start">
														<a href="http://www.tw.ac.kr/main.do" target="_blank" aria-label="홈페이지로 이동" class="btn btn-sm text-primary btn-link py-1">
															<i class="ki-outline ki-home fs-4 text-primary me-1 align-middle"></i>
														</a>
														<?=$data['comp_name'];?>
														<!--a href="javascript:void(0);" class="badge badge-light-info fs-6 py-2 px-3" title="거래처 정보"
															data-bs-toggle="popover" data-bs-dismiss="true" data-bs-html="true" data-bs-custom-class="popover-inverse" data-bs-placement="right"  
															data-bs-content="- 신규 서버정보<br />- 홈페이지<br />USER : administrator/tongwon1@@9<br />IP : 210.106.72.1<br />OS : WindowsServer2012 R2 Standard<br />DNS : www.tw.ac.kr(메인), ipsi.tw.ac.kr(입시)<br />- DB<br />USER : hpuser/twhp_1oo9<br />IP : 210.106.72.24<br />OS : Solaris 10<br />DBMS : Oracle 10g<br />SERVICE_NAME : upep<br />PORT : 1521<br />CharacterSet : 'KO16KSC5601<br /><br />- 홈페이지 DB서버<br />IP : 210.106.72.104 (administrator / ehddnjs1@#)<br /><br />- 대학홈페이지, 입시홈페이지<br />IP : 210.106.72.12 (administrator / wkfkwkfk5)<br />위치 : C:SERVERwww (대학:twmain폴더, 입학:twipsi폴더)<br /><br />- 기숙사, 사회봉사, 신문방송국 등<br />IP : 210.106.72.78 (administrator / wkfkwkfk5)<br />위치 : C:SERVERwww">
															<?=$data['comp_name'];?> <i class="ki-outline ki-information-3 fs-4 text-info ms-2 align-middle"></i-->
														</a>
													</td>
													<th>담당자</th>
													<td class="text-start">
														<a href="javascript:void(0);" class="text-gray-700 text-hover-primary kt_user_button">
															<span class="text-info"><?=$data['client_name']?></span>
															<?=$charge_data['group_name'] ? ":". $charge_data['group_name'] : ""?>  
															<span class="text-primary ms-2"><?=$charge_data['charge_name']?></span>
														</a>
													</td>
												</tr>

												<tr>
													<th>접수분류</th>
													<td class="text-start">
														<ol class="breadcrumb">
														<?
															$receipt_class = receipt_class_view($data['receipt_class']);
															foreach ($receipt_class as $k => $v)
															{
																$i=1;
																if(is_array($v))
																{
																	if ($i == $data['class_depth']) echo '<li class="breadcrumb-item text-dark">'. $v[$i] .'</li>';
																	else echo '<li class="breadcrumb-item">'. $v[$i] .'</li>';
																	$i++;
																}
															}
														?>
														</ol>
													</td>
													<th>상태</th>
													<td class="text-start">
														<span class="text-<?=$receipt_status_css?>">
															<i class="ki-outline ki-tablet-book fs-4 text-<?=$receipt_status_css?> align-middle me-1" aria-label="<?=$data['receipt_status_str']?>"></i>
															<?=$data['receipt_status_str']?>
														</span>
														<!--span class="text-warning"><i class="ki-outline ki-tablet-book fs-4 text-warning align-middle me-1" aria-label="접수승인"></i>접수승인</span-->
														<!-- <span class="text-success"><i class="ki-outline ki-folder-added fs-4 text-success align-middle me-1" aria-label="완료처리"></i>완료처리</span> -->
														<!-- <span class="text-warning"><i class="ki-outline ki-tablet-book fs-4 text-warning align-middle me-1" aria-label="접수승인"></i>접수승인</span> -->
														<!-- <span class="text-danger"><i class="ki-outline ki-notepad-edit fs-4 text-danger align-middle me-1" aria-label="접수등록"></i>접수등록</span> -->
														<!-- <span class="text-info"><i class="ki-outline ki-tablet-delete fs-4 text-info align-middle me-1" aria-label="보류처리"></i>보류처리</span> -->
														<!-- <span class="text-gray-600"><i class="ki-outline ki-delete-folder fs-4 text-gray-600 align-middle me-1" aria-label="취소처리"></i>취소처리</span> -->
													</td>
												</tr>

												<tr>
													<th>내용</th>
													<td class="text-start lh-xl" colspan="3">
														<?=$data['remark'];?>

														<?php
															// 첨부 이미지 출력
															//include("../common/imageView.php");
														?>
													</td>
												</tr>

												<tr>
													<th>첨부파일</th>
													<td class="text-start" colspan="3">
														<?php
															//include("../common/attachmentList.php");
														?>
													</td>
												</tr>

											</tbody>
										</table>
										<? //echo $charge_idx."===".$mem_idx ."=========".$data['status_code_value']; ?>
										<?
										
										// 등록된 하위값
										$where = " and rid.ri_idx = '" . $ri_idx . "'";
										$detail_data = receipt_info_detail_data('page', $where);
										//print_r($detail_data);
										
										$sub_type = '';
										if($detail_data['total_num'] > 0){
											$plural_where = " and rid.ri_idx = '" . $ri_idx . "' and rid.receipt_type = '2'";
											$plural_list = receipt_info_detail_data('page', $plural_where);
											if ($plural_list['total_num'] == 0)
											{
												$sub_type = 'singular_view';
											}
											else
											{
												$sub_type = 'plural_view';
											}
										}

										?>

										<?if(!$sub_type || $sub_type == 'singular_view'){?>
										<!-- 타임라인(수정권한 없을때) -->
										<!-- 상태값에 따라 해당 클래스도 같이 ↓ 변경됨 border-success(완료처리), border-warning(접수승인), border-danger(접수등록), border-info(보류처리), border-gray-600(취소처리) -->
										<div id='singular_view' class="card card-bordered border-<?=$receipt_status_css?> bg-white p-0 my-8 my-lg-10" style="">
											<div class="card-header card-header-stretch ribbon ribbon-start ribbon-clip px-0 mt-2 position-relative">
												<div class="ribbon-label">
													<i class="ki-outline ki-folder-added fs-2 text-white mt-1" aria-label="완료처리"></i><span class="ribbon-inner bg-<?=$receipt_status_css?>"></span>
													<!-- <i class="ki-outline ki-tablet-book fs-2 text-white mt-1" aria-label="접수승인"></i><span class="ribbon-inner bg-warning"></span> -->
													<!-- <i class="ki-outline ki-notepad-edit fs-2 text-white mt-1" aria-label="접수등록"></i><span class="ribbon-inner bg-danger"></span> -->
													<!-- <i class="ki-outline ki-tablet-delete fs-2 text-white mt-1" aria-label="보류처리"></i><span class="ribbon-inner bg-info"></span> -->
													<!-- <i class="ki-outline ki-delete-folder fs-2 text-white mt-1" aria-label="취소처리"></i><span class="ribbon-inner bg-gray-600"></span> -->
												</div>
								
												<?	if( ($charge_idx != $mem_idx) ||  ($data['status_code_value'] != 'RS01') ){?>
												<div class="card-title">
													<div class="d-sm-flex flex-sm-wrap fs-7 ls-n3 ms-18 my-1">
														<div class="me-6 me-lg-9 my-1">
															<span class="text-danger fw-semibold">접수분류</span> : <span class="text-gray-800"><?=$data['class_name']?></span>
														</div>
														<div class="me-6 me-lg-9 my-1">
															<span class="text-danger fw-semibold">담당자</span> : <span class="text-gray-800"><?=$charge_data['charge_name']?></span>
														</div>
														<div class="me-6 me-lg-9 my-1">
															<?if($data['status_code_value'] == 'RS90'){?>
															<span class="text-danger fw-semibold">완료일</span> : <span class="text-gray-800"><?=date("Y-m-d", strtotime($data['end_date']))?></span>
															<?}else{?>
															<span class="text-danger fw-semibold">완료예정일</span> : <span class="text-gray-800"><?=strtotime($data['end_sche_date']) > 0 ? date("Y-m-d", strtotime($data['end_sche_date'])) : "" ?></span>
															<?}?>
														</div>
													</div>
															<?
															if($data['status_code_value'] != 'RS01' && $data['status_code_value'] != 'RS90'){

																$singular_where = " and rid.ri_idx = '" . $ri_idx . "' and rid.receipt_type = '1'";
																$singular_data = receipt_info_detail_data('view', $singular_where);

																$rid_idx = $singular_data['rid_idx'];
															?>
													<div class="d-flex flex-stack me-sm-2 my-1 mb-2 mb-sm-0">
														<div class="position-relative d-flex align-items-center w-75 w-sm-auto">
															<select id="detail_receipt_status_<?=$rid_idx;?>" name="detail_receipt_status_<?=$rid_idx;?>" title="접수상태" data-control="select2" 
																onchange="receipt_status_end(this.value, '<?=$rid_idx;?>')" class="form-select form-select-sm w-xl-100px">
																<option value="">접수상태</option>
																<?
																	foreach ($set_receipt_status as $k => $v)
																	{
																		$view_ok = 'Y';
																		if ($k == 'RS01' || $k == 'RS02')
																		{
																			$view_ok = 'N';
																		}
																		if ($detail_data['receipt_status'] == 'RS03')
																		{
																			if ($k == 'RS03')
																			{
																				$view_ok = 'N';
																			}
																		}
																		if ($view_ok == 'Y')
																		{
																?>
																		<option value="<?=$k;?>" <?=selected($k, $detail_data['receipt_status']);?>><?=$v;?></option>
																<?
																		}
																	}
																?>
															</select>																
															<button type="button" class="btn btn-sm btn-secondary px-4" onclick="receipt_status_change('<?=$rid_idx;?>');">
																적용
															</button>
														</div>
													</div>
														<?
															}
														?>
													
												</div>
												
											<?		}
													else
													{
											?>
												<div class="card-title w-100 w-sm-75 mw-800px">
													<div class="d-sm-flex flex-sm-wrap fs-7 ls-n3 ms-18 box-sizing-content w-100 pe-6">
														<div class="d-flex flex-stack me-sm-6 me-xl-9 my-1 mb-2 mb-sm-0">
															<div class="text-danger fw-semibold mt-1 me-4 w-45px w-md-auto">접수분류</div>
															<div class="w-75 w-sm-auto">
																<input type="hidden" name="detail_receipt_code" id="detail_receipt_code" value="<?=$code_comp;?>" />
																<select id="detail_receipt_class" name="detail_receipt_class" title="분류를 선택하세요" onchange="check_receipt_code()" data-control="select2" data-hide-search="true" data-placeholder="분류를 선택하세요" class="form-select form-select-sm">
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
																	<option value="<?=$clist['code_idx']?>" data-receipt-code='<?=$clist['receipt_code']?>' <?=$data['receipt_class'] == $clist['code_idx'] ? 'selected ': ''?>><?=$emp_str?><?=$clist['code_name']?></option>
																	<?		}
																	}
																	?>
																</select>
															</div>
														</div>
														<div class="d-flex flex-stack me-sm-6 me-xl-9 my-1 mb-2 mb-sm-0">
															<div class="text-danger fw-semibold mt-1 me-4 w-45px w-md-auto">담당자</div>
															<div class="w-75 w-sm-auto">
																<select name="detail_mem_idx" id="detail_mem_idx" title="담당자 선택해 주세요" data-control="select2" class="form-select form-select-sm w-xl-100px">
																	<option value="">당당자선택</option>
																	<?																			
																	foreach($staff_data as $val => $staff_list){
																		if(is_array($staff_list)){
																	?>
																	<option value="<?=$staff_list['mem_idx']?>" <?=$staff_list['mem_idx'] == $charge_data['charge_mem_idx']? 'selected':''?> /> 
																		<?=$staff_list['mem_name']?>
																	</option>
																	<?
																	}  
																		}
																	?>
																</select>

															</div>
														</div>
														<div class="d-flex flex-stack me-sm-2 my-1 mb-2 mb-sm-0">
															<div class="text-danger fw-semibold mt-1 me-4 w-45px w-md-auto">완료일</div>
															<div class="position-relative d-flex align-items-center w-75 w-sm-auto">
																<i class="ki-outline ki-calendar-8 position-absolute ms-3 fs-2"></i>
																<input name="detail_end_pre_date" id="detail_end_pre_date" class="form-control form-control-sm ps-10 flatpickr-input-single mw-125px me-2" placeholder=" 완료예정일을 설정하세요" id="kt_datepicker" type="text" readonly="readonly">
																<button type="button" class="btn btn-sm btn-secondary px-4" onclick="check_singular('');">
																	접수승인
																</button>
															</div>

															<div class="d-flex flex-stack me-sm-2 my-1 mb-2 mb-sm-2">
																<button type="button" class="btn btn-sm btn-secondary px-4" onclick="section_view();">
																	상세업무등록
																</button>
															</div>

														</div>
													</div>
												</div>
											<?
												}
											
											?>

												<div class="card-toolbar me-8 mt-2 position-absolute position-xl-relative z-index-3 top--50 top-xl-0 end--25 end-xl-0">
													<a href="write.php" class="btn btn-sm btn-icon btn-light-facebook d-print-none me-1" aria-label="수정">
														<i class="ki-outline ki-plus fs-2 mt-1"></i>
													</a>
													<button type="button" class="btn btn-sm btn-icon btn-light-facebook d-print-none" aria-label="삭제">
														<i class="ki-outline ki-cross fs-2 mt-1"></i>
													</button>
												</div>
											</div>

											<!--완료처리 코멘트 -->
											<div id="end_view_<?=$rid_idx;?>" class="card-header card-header-stretch ribbon ribbon-start ribbon-clip px-0 mt-2 position-relative" style="display:none;">
												<div class="" id="end_view_<?=$rid_idx;?>" style="display:none">
													담당자의 [완료처리] 내역은 [보고서] 완료내역에 출력됩니다.
													<span id="status_end_text_<?=$rid_idx;?>" style="display:none" class="status_end_text">
														완료, 취소처리시 수정, 삭제 불가
													</span>
												</div>
												<textarea rows="5" name="detail_remark_end_<?=$rid_idx;?>" id="detail_remark_end_<?=$rid_idx;?>" title="완료문구를 입력하세요." style="width:95%"></textarea>
											</div>
											<!--//완료처리 코멘트 -->

											<?php
												include $local_path . "/bizstory/receipt/receipt_stauts_history.php";
											?>
										</div>
										<? } ?>
										<!-- //타임라인(수정권한 없을때) -->

										



										<?if(!$sub_type || $sub_type == 'plural_view'){?>
										<!-- 타임라인(상세없무등록) -->
										<!-- 상태값에 따라 해당 클래스도 같이 ↓ 변경됨 border-success(완료처리), border-warning(접수승인), border-danger(접수등록), border-info(보류처리), border-gray-600(취소처리) -->
										<div id="plural_view" class="card card-bordered border-<?=$receipt_status_css?> bg-white p-0 my-8 my-lg-10" style="display:<?=!$sub_type?'none':'block'?>;">
											<div class="card-header card-header-stretch ribbon ribbon-start ribbon-clip px-0 mt-2 position-relative">
												<div class="ribbon-label">
													<i class="ki-outline ki-notepad-edit fs-2 text-white mt-1" aria-label="접수등록"></i><span class="ribbon-inner bg-<?=$receipt_status_css?>"></span>
													<!-- <i class="ki-outline ki-folder-added fs-2 text-white mt-1" aria-label="완료처리"></i><span class="ribbon-inner bg-success"></span> -->
													<!-- <i class="ki-outline ki-tablet-book fs-2 text-white mt-1" aria-label="접수승인"></i><span class="ribbon-inner bg-warning"></span> -->
													
													<!-- <i class="ki-outline ki-tablet-delete fs-2 text-white mt-1" aria-label="보류처리"></i><span class="ribbon-inner bg-info"></span> -->
													<!-- <i class="ki-outline ki-delete-folder fs-2 text-white mt-1" aria-label="취소처리"></i><span class="ribbon-inner bg-gray-600"></span> -->
												</div>

												<div class="card-title">
													<div class="ms-18 fs-7 ls-n3">다수 접수업무가 등록되었습니다.</div>
												</div>

												<div class="card-toolbar me-8 mt-3 position-absolute position-xl-relative z-index-3 top--50 top-xl-0 end--25 end-xl-0">
													<a href="write.php" class="btn btn-sm btn-icon btn-light-facebook d-print-none me-1" aria-label="수정">
														<i class="ki-outline ki-plus fs-2 mt-1"></i>
													</a>
													<button type="button" class="btn btn-sm btn-icon btn-light-facebook d-print-none" aria-label="삭제">
														<i class="ki-outline ki-cross fs-2 mt-1"></i>
													</button>
												</div>
											</div>

											<!-- 안내 -->
											<div class="alert bg-transparent d-flex align-items-center p-2 mx-6 mt-2">
												<i class="ki-outline ki-shield-tick fs-2 text-warning me-2 mt-1"></i>
												<div class="d-flex flex-column fs-7 text-warning mt-1">
													접수된 업무진행시 담당자를 여러명 지정할 때 사용합니다.
												</div>
											</div>
											<!--// 안내  -->
											<div id="plural_detail">
											
											</div>
											
											<!-- //상세없무등록 -->

											<?php
												// 접수 타임라인
												include $local_path . "/bizstory/receipt/receipt_stauts_history.php";
											?>
										</div>
										<!-- //타임라인(상세없무등록) -->
										<?}?>

										
										

										<?php
											// 댓글
											//include_once("../common/comment.php");
										?>
										<div class="row mb-8 mb-lg-10">
											<div class="col-12 text-end">
												<a href="javascript:void();" class="btn btn-flex btn-secondary" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'">
													<i class="ki-outline ki-burger-menu fs-6"></i> 목록
												</a>
											</div>
										</div>
										<!-- //글보기 -->
									</form>




	<?
	// 관리자만
		if ($_SESSION[$sess_str . '_ubstory_level'] <= '11') {
	?>
					<!--div class="receipt_area">
						<span class="btn_big_blue"><input type="button" value="수정" onclick="data_form_open('<?=$ri_idx;?>')" /></span>
						<span class="btn_big_red"><input type="button" value="삭제" onclick="check_delete('<?=$ri_idx;?>')" /></span>
					</div-->
	<?
		}
	?>

<script type="text/javascript">
//<![CDATA[

//---- 분류 코드
	function check_receipt_code()
	{
		const receipt_class = $('#detail_receipt_class option:selected').attr('data-receipt-code');
		$('#detail_receipt_code').val(receipt_class);
	}

//------------------------------------ 접수구분
	function section_view()
	{
		$('#singular_view').hide();
		$('#plural_view').show();
		plural_list();
		
	}
plural_view();
//------------------------------------ 접수상태내역
	function status_history_info()
	{
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/receipt/receipt_stauts_history.php',
			data: $('#viewform').serialize(),
			success: function(msg) {
				$('#status_history_info').html(msg);
			}
		});
	}

//------------------------------------ 접수상태변경
	function receipt_status_change(idx)
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		$('#view_sub_type').val('status_modify');
		$("#view_rid_idx").val(idx);

		var status_val = $('#detail_receipt_status_' + idx).val();
		if (status_val == '')
		{
			chk_total = chk_total + '접수상태를 선택하세요.<br />';
			action_num++;
		}
		if (status_val == 'RS90') // 완료일 경우
		{
			chk_value = $('#detail_remark_end_' + idx).val(); // 완료문구
			chk_title = $('#detail_remark_end_' + idx).attr('title');
			chk_msg = check_input_value(chk_value);
			if (chk_msg == 'No')
			{
				chk_total = chk_total + chk_title + '<br />';
				action_num++;
			}
		}

		if (action_num == 0)
		{
			//$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#viewform').serialize(),
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#view_sub_type').val('');
						$("#view_rid_idx").val('');
						$("#view_rid_type").val('');
						//section_view();
						document.location.reload();
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

//------------------------------------ 상태완료 문구
	function receipt_status_end(str, idx)
	{
		alert(str);
		if (str == 'RS90') // 완료일 경우
		{
			$('#end_view_' + idx).css({display:'block'});
			$('#status_end_text_' + idx).css({display:'block'});
		}
		else
		{
			$('#end_view_' + idx).css({display:'none'});
		}
	}


//------------------------------------ 단일 등록/수정
	function check_singular(idx)
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		$('#view_sub_type').val('singular_post');
		$("#view_rid_idx").val(idx);
		$("#view_rid_type").val('');

		chk_value = $('#detail_receipt_class').val(); // 접수분류
		chk_title = $('#detail_receipt_class').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}
		const receipt_class = $('#detail_receipt_class option:selected').attr('data-receipt-code');
		$('#detail_receipt_code').val(receipt_class);

		chk_value = $('#detail_mem_idx').val(); // 담당자
		chk_title = $('#detail_mem_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#detail_end_pre_date').val(); // 완료예정일
		chk_title = $('#detail_end_pre_date').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#viewform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						//$("#view_sub_type").val('');
						//$("#view_rid_idx").val('');
						//section_view()
						document.location.reload();
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

//------------------------------------ 단일 수정폼
	function singular_modify(idx)
	{
		$("#view_rid_idx").val(idx);
		$("#view_rid_type").val(1);
		$("#view_sub_type").val('singular_form');
		section_view();
	}

//------------------------------------ 다수 리스트 보기
	function plural_view()
	{
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/receipt/receipt_view_section_plural.php',
			data: $('#viewform').serialize(),
			success: function(msg) {
				$('#plural_detail').html(msg);
			}
		});
	}


//------------------------------------ 다수 리스트
	function plural_list()
	{
		$('#view_sub_type').val('plural_list');
		$("#view_rid_idx").val('');
		$("#view_rid_type").val('');
		plural_view();
		
	}

//------------------------------------ 다수 등록/수정폼
	function plural_form(idx)
	{
		alert(1);
		$("#view_rid_type").val(2);
		$("#view_sub_type").val('plural_form');
		$("#view_rid_idx").val(idx);
		plural_view();
	}

//------------------------------------ 다수 접수등록/수정
	function check_plural()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		$('#view_sub_type').val('plural_post');

		chk_value = $('#detail_receipt_class').val(); // 접수분류
		chk_title = $('#detail_receipt_class').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}
		const receipt_class = $('#detail_receipt_class option:selected').attr('data-receipt-code');
		$('#detail_receipt_code').val(receipt_class);

		chk_value = $('#detail_mem_idx').val(); // 담당자
		chk_title = $('#detail_mem_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#detail_end_pre_date').val(); // 완료예정일
		chk_title = $('#detail_end_pre_date').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#detail_remark').val(); // 내용
		chk_title = $('#detail_remark').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#viewform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						
						plural_list();
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

//------------------------------------ 접수상태변경
	function status_change(str)
	{
		if (str == 'RS90')
		{
			$("#receipt_status_remark").removeClass('blind');
		}
		else
		{
			$("#receipt_status_remark").addClass('blind');
		}
	}

//------------------------------------ 다수접수 삭제
	function plural_delete(idx)
	{
		if (confirm("선택하신 데이타를 삭제하시겠습니까?"))
		{
			$("#view_rid_type").val(2);
			$("#view_sub_type").val('plural_delete');
			$("#view_rid_idx").val(idx);

			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#viewform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$("#view_sub_type").val('plural_list');
						$("#view_rid_idx").val('');
						section_view();
					}
				}
			});
		}
	}

//------------------------------------ 분리폼
	function plural_remark(str)
	{
		$('#plural_remark_' + str).css({"display":"block"});
	}

//------------------------------------ 댓글 관련
	var comment_list = '<?=$local_dir;?>/bizstory/receipt/receipt_view_comment_list.php';
	var comment_form = '<?=$local_dir;?>/bizstory/receipt/receipt_view_comment_form.php';
	var comment_ok   = '<?=$local_dir;?>/bizstory/receipt/receipt_view_comment_ok.php';

//------------------------------------ 댓글 등록
	function comment_insert_form(form_type)
	{
		if (form_type == 'close')
		{
			$("#new_comment").slideUp("slow");
			$("#new_comment").html('');
			$('#comment_new_btn').css({'display':'block'});
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: comment_form,
				data: $('#viewform').serialize(),
				success: function(msg) {
					$('#comment_new_btn').css({'display':'none'});
					$("#new_comment").slideUp("slow");
					$("#new_comment").slideDown("slow");
					$("#new_comment").html(msg);
				}
			});
		}
	}

//------------------------------------ 댓글 목록
	function comment_list_data()
	{
		$.ajax({
			type: "get", dataType: 'html', url: comment_list,
			data: $('#commentlistform').serialize(),
			success: function(msg) {
				$('#comment_list_data').html(msg);
			}
		});
	}

//------------------------------------ 댓글 열기/닫기
	var comment_chk_val = 'close';
	function comment_view()
	{
		if (comment_chk_val == 'close')
		{
			comment_chk_val = 'open';
			$('#comment_list_data').html('');
			$("#comment_gate").removeClass('btn_i_minus');
			$("#comment_gate").addClass('btn_i_plus');
		}
		else
		{
			comment_chk_val = 'close';
			comment_list_data();
			$("#comment_gate").removeClass('btn_i_plus');
			$("#comment_gate").addClass('btn_i_minus');
		}
	}
	//comment_view();

//]]>
</script>
<?
	}
?>
