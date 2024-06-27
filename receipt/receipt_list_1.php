<?
/*
	수정 : 2013.05.02
	위치 : 고객관리 > 접수목록 - 목록
*/
	//require_once "../common/setting.php";
	//require_once "../common/no_direct.php";
	$page_chk = 'html';
	//require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	//$code_part = search_company_part($code_part);
	$code_part = 0;
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$set_part_yn = $comp_set_data['part_yn'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = "1";
	$where .= " and ri.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $where .= " and ri.part_idx = '" . $code_part . "'";

	if ($shclass != '' && $shclass != 'all') // 접수분류
	{
		$where .= " and (concat(code.up_code_idx, ',') like '%" . $shclass . ",%' or ri.receipt_class = '" . $shclass . "')";
	}
	if ($shstatus != '' && $shstatus != 'all') // 접수상태
	{
		if ($shstatus == 'end_no')
		{
			$where .= " and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')";
		}
		else
		{
			$where .= " and ri.receipt_status = '" . $shstatus . "'";
		}
	}
	if ($shsgroup != '' && $shsgroup != 'all') // 직원그룹
	{
		$where .= " and mem.csg_idx = '" . $shsgroup . "'";
	}
	if ($shstaff != '' && $shstaff != 'all') // 직원 - 거래처담당자
	{
		$where .= " and ri.charge_mem_idx = '" . $shstaff . "'";
	}
	if ($list_type == 'all_no') // 전체 미처리
	{
		$where .= " and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')";
	}
	if ($sdate1 != "") $where .= " and date_format(ri.reg_date, '%Y-%m-%d') >= '" . $sdate1 . "'";
	if ($sdate2 != "") $where .= " and date_format(ri.reg_date, '%Y-%m-%d') <= '" . $sdate2 . "'";
	if ($stext != '' && $swhere != '')
	{
		if ($swhere == 'ri.tel_num')
		{
			$stext = str_replace('-', '', $stext);
			$stext = str_replace('.', '', $stext);
			$where .= " and (
				replace(ri.tel_num, '-', '') like '%" . $stext . "%' or
				replace(ri.tel_num, '.', '') like '%" . $stext . "%'
			)";
		}
		else $where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ri.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	if ($list_type == 'my_no') // 나의 미처리
	{
		$where .= "
			and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')
			and (
				if (ifnull(rid.mem_idx, '') = ''
					, if (ifnull(ri.charge_mem_idx, '') = ''
						, ci.mem_idx
						, ri.charge_mem_idx)
					, rid.mem_idx) = '" . $code_mem . "')
		";
		$query_page = "
			select
				count(ri.ri_idx)
			from
				receipt_info ri
				left join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
				left join (select ri_idx, mem_idx from receipt_info_detail where del_yn = 'N' group by ri_idx) rid on rid.ri_idx = ri.ri_idx

				left join member_info mem on mem.comp_idx = ci.comp_idx and mem.mem_idx = ci.mem_idx
				left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.part_idx = ri.part_idx and code.code_idx = ri.receipt_class
				left join code_receipt_status code2 on code2.comp_idx = ri.comp_idx and code2.part_idx = ri.part_idx and code2.code_value = ri.receipt_status
			where
				ri.del_yn = 'N'
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ri.*
				, ci.client_name, ci.del_yn as client_del_yn, ci.link_url
				, mem.mem_name, mem.del_yn as member_del_yn, mem.mem_idx
				, code.del_yn as class_del_yn
				, code2.code_name as receipt_status_str, code2.code_bold as receipt_status_bold, code2.code_color as receipt_status_color, code2.code_value as status_value
			from
				receipt_info ri
				left join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
				left join (select ri_idx, mem_idx from receipt_info_detail where del_yn = 'N' group by ri_idx) rid on rid.ri_idx = ri.ri_idx

				left join member_info mem on mem.comp_idx = ci.comp_idx and mem.mem_idx = ci.mem_idx
				left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.part_idx = ri.part_idx and code.code_idx = ri.receipt_class
				left join code_receipt_status code2 on code2.comp_idx = ri.comp_idx and code2.part_idx = ri.part_idx and code2.code_value = ri.receipt_status
			where
				ri.del_yn = 'N'
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";
		$data_sql['query_page']   = $query_page;
		$data_sql['query_string'] = $query_string;
		$data_sql['page_size']    = $page_size;
		$data_sql['page_num']     = $page_num;

		$list = query_list($data_sql);
	}
	else
	{
		
		//$list = receipt_info_data('list', $where, $orderby, $page_num, $page_size);


		$where = "ri.comp_idx = '" . $code_comp . "' and ri.part_idx = '" . $code_part . "'";

		$query_string ="
			select
				ri.*
				, ci.comp_name
				, code.del_yn as class_del_yn
			from
				receipt_info ri
				join company_info ci on ci.del_yn = 'N' and ci.comp_idx = ri.comp_idx
				left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.code_idx = ri.receipt_class 	
			where
			" . $where . "
			order by
			" . $orderby . "
		";
		
		$data_sql['query_page']   = $query_page;
		$data_sql['query_string'] = $query_string;
		$data_sql['page_size']    = $page_size;
		$data_sql['page_num']     = $page_num;

		$list = query_list($data_sql);
	}
	
	//print_r($list);

	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shclass=' . $send_shclass . '&amp;shstatus=' . $send_shstatus . '&amp;shstaff=' . $send_shstaff;
	$f_search  = $f_search . '&amp;shsgroup=' . $send_shsgroup;
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
		<input type="hidden" name="shsgroup"  value="' . $send_shsgroup . '" />
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

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="data_form_open(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
	if ($auth_menu['down'] == "Y") // 다운로드버튼
	{
		//$btn_down = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_excel()"><span>엑셀</span></a>';
	}
	if ($auth_menu['print'] == "Y") // 인쇄버튼
	{
		//$btn_print     = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_print()"><span><em class="print"></em>인쇄</span></a>';
		$btn_print_sel = '<a href="javascript:void(0);" onclick="list_print_detail()" class="btn_big_violet"><span>상세인쇄</span></a>';
	}

?>


										<form id="searchform" name="searchform" method="post" action="#" class="form p-4 border bg-gray-100 rounded-2 mb-6 mb-lg-8">
											<div class="row gx-2">
												<div class="col-6 col-sm-4 col-md-3 mb-2">
													<select  name="shclass" id="search_shclass" class="form-select form-select-sm" data-control="select2" data-hide-search="true" aria-label="전체분류" >
														<option value="all">전체분류</option>
														<option value="1">웹사이트관련</option>
														<option value="2">&nbsp;&nbsp;&nbsp;컨텐츠 수정/추가</option>
														<option value="3">&nbsp;&nbsp;&nbsp;내용수정</option>
														<option value="4">&nbsp;&nbsp;&nbsp;메뉴수정</option>
														<option value="5">&nbsp;&nbsp;&nbsp;배너추가</option>
														<option value="6">&nbsp;&nbsp;&nbsp;팝업추가</option>
														<option value="11">&nbsp;&nbsp;&nbsp;오류수정요청</option>
														<option value="12">&nbsp;&nbsp;&nbsp;사이트오류</option>
														<option value="13">&nbsp;&nbsp;&nbsp;시스템오류</option>
														<option value="445">&nbsp;&nbsp;&nbsp;웹취약점</option>
														<option value="14">&nbsp;&nbsp;&nbsp;기타오류</option>
														<option value="46">하드웨어관련</option>
														<option value="47">문의사항관련</option>
														<option value="9">&nbsp;&nbsp;&nbsp;사이트문의</option>
														<option value="8">&nbsp;&nbsp;&nbsp;관공서공문</option>
														<option value="10">&nbsp;&nbsp;&nbsp;기타문의</option>
														<option value="435">평생학습센터</option>
														<option value="436">&nbsp;&nbsp;&nbsp;평생학습센터</option>
														<option value="437">&nbsp;&nbsp;&nbsp;회계프로그램</option>
													</select>
												</div>
												<div class="col-6 col-sm-3 col-md-3 mb-2">
													<select class="form-select form-select-sm" name="" id="" data-control="select2" data-hide-search="true" aria-label="전체상태">
														<option value="all">전체상태</option>
														<option value="end_no">미처리</option>
														<option value="RS01">접수등록</option>
														<option value="RS02">접수승인</option>
														<option value="RS03">작업진행</option>
														<option value="RS90">완료처리</option>
														<option value="RS80">보류처리</option>
														<option value="RS60">취소처리</option>
													</select>
												</div>
												<div class="col-6 col-sm-5 col-md-3 mb-2">
													<select class="form-select form-select-sm" name="" id="" data-control="select2" data-hide-search="true" aria-label="전체직원그룹">
														<option value="all">전체직원그룹</option>
														<option value="2">웹퍼블리셔</option>
														<option value="1">부설연구소</option>
														<option value="3">경영지원</option>
													</select>
												</div>
												<div class="col-6 col-sm-4 col-md-3 mb-2">
													<select class="form-select form-select-sm" name="aaa" id="aaa" data-control="select2" data-hide-search="true" aria-label="전체직원">
														<option value="all">전체직원</option>
														<option value="195">고등어</option>
														<option value="907">권진용</option>
														<option value="1144">김소정</option>
														<option value="960">문지호</option>
														<option value="521">박시후</option>
														<option value="2">서경원</option>
														<option value="1064">안지영</option>
														<option value="528">웹유지보수총괄</option>
														<option value="985">유지보수총괄</option>
														<option value="1018">이호철</option>
														<option value="1004">최순혁</option>
													</select>
												</div>
												<div class="col-4 col-sm-3 col-md-2 col-lg-3 mb-2 mb-sm-0">
													<select class="form-select form-select-sm" name="swhere" id="search_swhere" data-control="select2" data-hide-search="true" aria-label="칼럼선택">
														<option value="ci.client_name">거래처명</option>
														<option value="ri.subject">제목</option>
														<option value="ri.remark">내용</option>
														<option value="ri.writer">작성자</option>
														<option value="ri.tel_num">연락처</option>
													</select>
												</div>
												<div class="col-8 col-sm-5 col-md-4 col-lg-3">
													<div class="position-relative d-flex align-items-center">
														<i class="ki-outline ki-calendar-8 position-absolute ms-4 fs-2"></i>
														<input class="form-control form-control-sm ps-12 flatpickr-input" name="date" placeholder="검색기간을 설정하세요" id="kt_daterangepicker" type="text" readonly="readonly">
													</div>
												</div>
												<div class="col-md-6">
													<div class="position-relative d-flex align-items-center">
														<input type="text" class="form-control form-control-sm" placeholder="키워드를 입력하세요" />
														<button type="submit" class="btn btn-sm btn-icon btn-dark position-absolute end-0 px-6 rounded-start-0" aria-label="검색"><i class="ki-outline ki-magnifier fs-3"></i></button>
													</div>
												</div>
											</div>
										</form>

										<div class="d-flex flex-stack flex-wrap mb-4">
											<div class="d-flex align-items-center py-1">
												<select name="" data-control="select2" data-hide-search="true" class="form-select form-select-sm bg-gray-100 border-gray-300 w-125px">
													<option value="전체목록" selected="">전체목록</option>
													<option value="나의 미처리">나의 미처리</option>
													<option value="전체 미처리">전체 미처리</option>
												</select>
											</div>
											<div class="d-flex align-items-center py-1">
												<button type="button" class="btn btn-sm btn-secondary me-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click" title="접수를 선택하고 인쇄하세요."><i class="ki-outline ki-printer fs-6"></i> 상세인쇄</button>
												<a href="javascript:void(0);" class="btn btn-sm btn-warning" onclick="data_form_open(' ')"><i class="ki-outline ki-pencil fs-6"></i> 등록</a>
											</div>
										</div>

										<table class="table align-middle table-striped table-row-bordered ls-n2 fs-6 fs-sm-7 text-gray-700 gy-3 gx-0" id="kt_datatable">
											<thead>
												<tr class="text-gray-900 fw-semibold text-uppercase bg-gray-100 border-top-2 border-secondary">
													<th class="min-w-45px mw-50px d-none d-md-table-cell text-center">
														<label class="form-check form-check-custom form-check-sm ms-5">
															<input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="[data-bbs-list='check']" value="0" />
														</label>
													</th>
													<th class="min-w-50px mw-65px d-none d-xl-table-cell text-center">번호</th>
													<th class="min-w-125px mw-125px d-none d-sm-table-cell text-center">거래처명</th>
													<th class="min-w-90px mw-100px d-none d-md-table-cell text-center">분류</th>
													<th class="min-w-200px min-w-md-225px text-center" data-priority="1">제목</th>
													<th class="min-w-85px mw-100px d-none d-xl-table-cell text-center">등록자</th>
													<th class="min-w-65px min-w-md-75px mw-65px text-center">상태</th>
													<th class="min-w-80px min-w-md-90px mw-100px d-none d-xl-table-cell text-center">담당직원</th>
													<th class="min-w-70px min-w-md-80px mw-85px d-none d-xl-table-cell text-center">등록일</th>
													<th class="min-w-40px min-w-md-45px mw-50px text-center">링크</th>
												</tr>
											</thead>
											<tbody class="text-center">
											<?
												$i = 0;
												if ($list["total_num"] == 0) {
											?>
													<tr>
														<td colspan="10">등록된 데이타가 없습니다.</td>
													</tr>
											<?
												}
												else
												{
													$i = 1;
													$num = $list["total_num"] - ($page_num - 1) * $page_size;
													foreach($list as $k => $data)
													{
														if (is_array($data))
														{
															if ($auth_menu['view'] == "Y") $btn_view = "data_view_open('" . $data["ri_idx"] . "')";
															else $btn_view = "check_auth_popup('view')";
															
															$list_data = receipt_list_data($data['ri_idx'], $data);
											?>

												<tr>
													<td class="d-none d-md-table-cell">
														<label class="form-check form-check-custom form-check-sm ms-5">
															<input type="checkbox" id="riidx_<?=$i;?>" name="chk_ri_idx[]" value="<?=$list_data["ri_idx"];?>" class="form-check-input"  title="선택" data-bbs-list="check"/>
														</label>
													</td>
													<td class="d-none d-xl-table-cell"><?=$num;?></td>
													<td class="d-none d-sm-table-cell">
														<a href="javascript:void(0);" class="text-gray-800 text-hover-primary"><?=$data['comp_name'];?></a>
													</td>
													<td class="d-none d-md-table-cell"><?=$list_data['receipt_class_str']['first_class'];?></td>
													<td class="text-start ps-4">
														<a href="javascript:void(0);" onclick="<?=$btn_view;?>" class="text-gray-800 text-hover-primary ellipsis-1">
															<?=$list_data['subject'];?>
															<?//=$list_data['important_str'];?>
											<?
												if ($list_data['total_file'] > 0)
												{
											?>
															<!-- file 첨부 여부 --><span class="ms-2 text-warning fw-semibold"><i class="ki-outline ki-file-down fs-5 fs-md-4 me-1 align-middle text-warning"></i><?=number_format($list_data['total_file'])?></span><!--// file 첨부 여부 -->
											<?
												}
											?>
											<?
												if ($list_data['total_comment'] > 0)
												{
											?>
															<!-- 댓글 여부 --><span class="fw-semibold text-danger ms-1"><i class="ki-outline ki-message-text-2 fs-5 fs-md-4 me-1 align-middle text-danger"></i><?=number_format($list_data['total_comment'])?></span><!--// 댓글 여부 -->
											<?
												}
											?>
														</a>
													</td>
													<td class="d-none d-xl-table-cell">
														<span class="cursor-pointer" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="010-8628-8513"><?=$list_data['receipt_name'];?></span>
													</td>
													<td class="text-success"><?=$list_data['receipt_status_name'];?></td>
													<td class="d-none d-xl-table-cell">
														<?=$list_data['member_str'];?> <!--[경영지원] <a href="javascript:void(0);" class="d-block text-primary kt_user_button">이호철 부장</a>-->
													</td>
													<td class="d-none d-xl-table-cell fs-8">
														<?=date_replace($list_data['reg_date'], 'Y.m.d');?>
														<div class="text-info"><?=$list_data['end_date_str'];?></div>
													</td>
													<td>
														<a href="https://lib.masan.ac.kr" target="_blank" aria-label="새창으로 이동" class="btn btn-icon btn-active-light-primary w-35px h-30px">
															<i class="ki-outline ki-home fs-4"></i>
														</a>
													</td>
												</tr>
											<?
														$num--;
														$i++;
													}
												}
											}
											?>

											</tbody>
										</table>
										<!-- //글목록 -->


<div class="details">
	<div>Records <span class="total_num"><?=$list['total_num'];?></span> / Total Pages <?=$list['total_page'];?></div>
</div>

<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />
<hr />

