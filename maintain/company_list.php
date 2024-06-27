<?
/*
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 업체관리 > 업체목록 - 목록
*/
//	require_once "../common/setting.php";
//	require_once "../common/no_direct.php";
//	require_once "../common/member_chk.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = "";
	if ($sclass != '' && $sclass != 'all') // 업체분류
	{
		$where .= " and (concat(code.up_code_idx, ',') like '%" . $sclass . ",%' or comp.comp_class = '" . $sclass . "')";
	}
	if ($stext != '' && $swhere != '')
	{
		if ($swhere == 'comp.tel_num')
		{
			$stext = str_replace('-', '', $stext);
			$where .= " and (
				replace(comp.tel_num, '-', '') like '%" . $stext . "%' or
				replace(comp.fax_num, '-', '') like '%" . $stext . "%' or
				replace(comp.hp_num, '-', '') like '%" . $stext . "%'
			)";
		}
		else $where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'comp.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = company_info_data('list', $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;sclass=' . $send_sclass;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
		<input type="hidden" name="sclass" value="' . $send_sclass . '" />
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
		$btn_write = '<a href="javascript:void(0);" onclick="popupform_open(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>
				 						<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();"
                                            class="form p-4 border bg-gray-100 rounded-2 mb-6 mb-lg-8">
                                            <div class="row gx-2">
                                                <div class="col-4 col-sm-6 col-lg-3 mb-2 mb-lg-0">
                                                    <select name="sclass" id="search_sclass" title="전체분류"
														class="form-select form-select-sm" data-control="select2"
                                                        data-hide-search="true" aria-label="거래처분류">
                                                        <option value="all">전체거래처분류</option>
														<?
															$class_where = " and code.view_yn = 'Y' and code.menu_depth > 0";
															$class_list = company_class_data('list', $class_where, '', '', '');
															foreach ($class_list as $class_k => $class_data)
															{
																if (is_array($class_data))
																{
																	$emp_str = str_repeat('&nbsp;', 4 * ($class_data['menu_depth'] - 1));
														?>
															<option value="<?=$class_data['code_idx'];?>" <?=selected($class_data['code_idx'], $sclass);?>><?=$emp_str;?><?=$class_data['code_name'];?></option>
														<?
																}
															}
														?>
                                                    </select>
                                                </div>
                                                <div class="col-8 col-sm-6 col-lg-2">
                                                    <select id="search_swhere" name="swhere" title="<?=$search_column;?>" 
														class="form-select form-select-sm" name="swhere"
                                                        data-control="select2" data-hide-search="true" aria-label="<?=$search_column;?>"
													>
													<option value="comp.comp_name"  <?=selected($swhere, 'comp.comp_name');?>>업체명</option>
													<option value="comp.boss_name"  <?=selected($swhere, 'comp.boss_name');?>>대표자명</option>
													<option value="comp.charge_name"<?=selected($swhere, 'comp.charge_name');?>>담당자명</option>
													<option value="comp.tel_num"    <?=selected($swhere, 'comp.tel_num');?>>전화번호</option>
													<option value="comp.address"    <?=selected($swhere, 'comp.address');?>>주소</option>
													<option value="comp.comp_email" <?=selected($swhere, 'comp.comp_email');?>>메일주소</option>
                                                    </select>
                                                </div>
                                                <div class="col-9 col-sm-10 col-lg-5">
                                                    <div class="position-relative d-flex align-items-center">
                                                        <input id="search_stext" name="stext" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" 
															onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}"
															ype="text" class="form-control form-control-sm"
                                                            placeholder="<?=$search_keyword;?>" />
                                                        <button type="submit" onclick="check_search()"
                                                            class="btn btn-sm btn-icon btn-dark position-absolute end-0 px-6 rounded-start-0"
                                                            aria-label="검색"><i class="ki-outline ki-magnifier fs-3"></i></button>
                                                    </div>
                                                </div>
                                                <!--div class="col-3 col-sm-2 col-lg-2">
                                                    <button onclick="viewExcelDownload();" type="button"
                                                        class="w-100 btn btn-sm btn-success px-6"
                                                        aria-label="Excel 다운로드">Excel<span
                                                            class="d-none d-xl-inline-block"> 다운로드</span></button>
                                                </div-->
                                            </div>
                                        </form>

                                        <div
                                            class="alert alert-warning d-flex align-items-center border-0 fs-7 text-warning">
                                            <ul class="mb-0">
                                                <li>업체등록을 하고 난뒤 승인을 한후 메뉴설정을 해주세요..</li>
                                                <li>승인은 한번만 가능합니다. 업체 사용가능은 사용유무를 선택하거나 종료일을 설정하면 됩니다.</li>
                                            </ul>
                                        </div>

                                        <div class="d-flex flex-stack flex-wrap mb-4">
                                            <div class="d-flex align-items-center py-1"></div>
                                            <div class="d-flex align-items-center py-1">
                                                <a href="javascript:void(0);" class="btn btn-sm btn-warning" onclick="data_form_open(' ')"><i class="ki-outline ki-pencil fs-6"></i> 등록</a>
                                            </div>
                                        </div>

                                        <table
                                            class="table align-middle table-striped table-row-bordered ls-n2 fs-6 fs-sm-7 text-gray-700 gy-3 gx-0"
                                            id="kt_datatable">
                                            <thead>
                                                <tr class="text-gray-900 fw-semibold text-uppercase bg-gray-100 border-top-2 border-secondary">
                                                    <th class="min-w-45px mw-50px text-center">
                                                        <label class="form-check form-check-custom form-check-sm ms-5">
                                                            <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="[data-bbs-list='check']" value="0" />
                                                        </label>
                                                    </th>
                                                    <th class="min-w-45px mw-55px text-center d-none d-xl-table-cell">번호</th>
													<th class="min-w-70px mw-75px text-center d-none d-xl-table-cell">분류</th>
                                                    <th class="min-w-70px mw-75px text-center d-none d-xl-table-cell">업체코드</th>
													<th class="min-w-70px mw-75px text-center d-none d-xl-table-cell">소속기간</th>
                                                    <th class="min-w-175px text-center" data-priority="1">업체명</th>
													<th class="min-w-70px min-w-md-80px text-center d-none d-xl-table-cell">연락처</th>
                                                    <th class="min-w-75px min-w-md-85px mw-90px text-center d-none d-sm-table-cell"> 대표자</th>
													<th class="min-w-75px min-w-md-85px mw-90px text-center d-none d-sm-table-cell"> 담당자</th>
													<th class="min-w-75px min-w-md-85px mw-90px text-center d-none d-sm-table-cell"> 만료일</th>
													<th class="min-w-75px min-w-md-85px mw-90px text-center d-none d-sm-table-cell"> 지사</th>
													<th class="min-w-75px min-w-md-85px mw-90px text-center d-none d-sm-table-cell"> 직원</th>
													<th class="min-w-75px min-w-md-85px mw-90px text-center d-none d-sm-table-cell"> 가격</th>
													<th class="min-w-75px min-w-md-85px mw-90px text-center d-none d-sm-table-cell"> 승인</th>													
                                                    <th class="min-w-90px w-125px text-center">관리</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
											<?
												$i = 0;
												if ($list["total_num"] == 0) {
											?>
													<tr>
														<td colspan="15">등록된 데이타가 없습니다.</td>
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
															if ($auth_menu['mod'] == "Y")
															{
																$btn_auth   = "check_code_data('check_yn', 'auth_yn', '" . $data["comp_idx"] . "', '" . $data["auth_yn"] . "')";
																$btn_move   = "window.open('" . $local_dir . "/bizstory/maintain/company_chk.php?comp_idx=" . $data['comp_idx'] . "', '_blank')";
																$btn_modify = "popupform_open('" . $data["comp_idx"] . "')";

																$menu_url = $local_dir . '/bizstory/maintain/company_menu.php';
																$btn_menu = "other_page_open('" . $data["comp_idx"] . "', '" . $menu_url . "')";
															}
															else
															{
																$btn_auth   = "check_auth_popup('modify')";
																$btn_move   = "check_auth_popup('modify')";
																$btn_menu   = "check_auth_popup('modify')";
																$btn_modify = "check_auth_popup('modify')";
															}
															if ($data["auth_yn"] == 'Y') $btn_auth = "check_auth_popup('승인했습니다.')";

															if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $data["comp_idx"] . "')";
															else $btn_delete = "check_auth_popup('delete');";

														// 남은일
															if ($data['end_date'] == '') $data['end_date'] = '0000-00-00';
															$data_date = query_view("select datediff('" . $data['end_date'] . "', '" . date("Y-m-d") . "') as remain_days");
															$remain_days = $data_date['remain_days'];
															if ($remain_days == '') $remain_days = 0;

															$tel_num = $data["tel_num"];
															$tel_num_str = substr($tel_num, 0, 1);
															if ($tel_num == '-' || $tel_num == '--')
															{
																$tel_num = '';
															}
															else if ($tel_num_str == '-')
															{
																$tel_num = substr($tel_num, 1, strlen($tel_num));
															}

														// 설정값
															$chk_where = " and cs.comp_idx = '" . $data['comp_idx'] . "'";
															$chk_data = company_setting_data('view', $chk_where);

														// 지사총수
															$part_cnt = number_format($chk_data['part_cnt']); // 등록가능 지사
															$part_where = " and part.comp_idx = '" . $data["comp_idx"] . "'";
															$part_page = company_part_data('page', $part_where);
															$total_part = number_format($part_page['total_num']);

														// 거래처총수
															$client_cnt = number_format($chk_data['client_cnt']); // 등록가능 거래처
															$client_where = " and ci.comp_idx = '" . $data["comp_idx"] . "'";
															$client_page = client_info_data('page', $client_where);
															$total_client = number_format($client_page['total_num']);

														// 직원수
															$staff_cnt = number_format($chk_data['staff_cnt']); // 등록가능 직원수
															$mem_where = " and mem.comp_idx = '" . $data["comp_idx"] . "'";
															$mem_page = member_info_data('page', $mem_where);
															$total_staff = number_format($mem_page['total_num']);

														// 에이전트수
															$agent_where = " and ad.comp_idx = '" . $data["comp_idx"] . "' and ci.del_yn = 'N'";
															$agent_page = agent_data_data('page', $agent_where);
															$total_agent = number_format($agent_page['total_num']);

														//사용데이터 - /data/company/comp_idx/* 값구해서
															$volume_num = number_format($chk_data['volume_num']); // 등록가능 용량
															$volume_path = $comp_path . '/' . $data["comp_idx"];
															$volume_data = server_volume($volume_path);

															if ($remain_days <= 0)
															{
																$end_class = ' style="color:#0000FF;"';
															}
															else if ($remain_days <= 15)
															{
																$end_class = ' style="color:#FF0000;"';
															}
															else
															{
																$end_class = "";
															}

														// 총값들 - 지사, 거래처, 직원, 저장공간, 에이전트, 가격
															$all_set_part   += $chk_data['part_cnt'];
															$all_set_client += $chk_data['client_cnt'];
															$all_set_mem    += $chk_data['staff_cnt'];
															$all_set_volume += $chk_data['volume_num'];

															$all_use_part   += $part_page['total_num'];
															$all_use_client += $client_page['total_num'];
															$all_use_mem    += $mem_page['total_num'];
															$all_use_agent  += $agent_page['total_num'];
															$all_use_volume += $volume_data;

															$total_price += $chk_data['use_price'];
															$use_price = number_format($chk_data['use_price']);
											?>

                                                <tr>
                                                    <td>
                                                        <label class="form-check form-check-custom form-check-sm ms-5">
                                                            <input  id="compidx_<?=$i;?>" name="chk_comp_idx[]" value="<?=$data["comp_idx"];?>"
																class="form-check-input" type="checkbox" data-bbs-list="check" />
                                                        </label>
                                                    </td>
                                                    <td class="d-none d-xl-table-cell"><?=$num?></td>
                                                    <td class="d-none d-xl-table-cell"><?=$data["comp_class_str"];?></td>
													<td class="d-none d-xl-table-cell"><?=$data["comp_code"];?></td>
													<td class="d-none d-xl-table-cell"><?=$data["org_name"];?></td>													
                                                    <td class="text-start ps-4">
                                                        <a href="javascript:void(0);" onclick="data_form_open('<?=$data['comp_idx'];?>')" class="text-gray-800 text-hover-primary ellipsis-1">
															<?=$data["comp_name"];?>(<?=$data["comp_idx"];?>)
															<?/*
                                                            <!-- 사용자 여부 --><span
                                                                class="ms-2 text-warning fw-semibold"><i
                                                                    class="ki-outline ki-file-down fs-5 fs-md-4 me-1 align-middle text-warning"></i>4</span>
                                                            <!--// 사용자 여부 -->
                                                            <!-- 메모 여부 --><span class="fw-semibold text-danger ms-1"><i
                                                                    class="ki-outline ki-message-text-2 fs-5 fs-md-4 me-1 align-middle text-danger"></i>2</span>
                                                            <!--// 메모 여부 -->
                                                            <!-- 계약 여부 --><span class="fw-semibold text-info ms-1"><i
                                                                    class="ki-outline ki-briefcase fs-5 fs-md-4 me-1 align-middle text-info"></i>2</span>
                                                            <!--// 계약 여부 -->
															*/?>
                                                        </a>
                                                    </td>
                                                    <td class="d-none d-sm-table-cell">
                                                       <a href="javascript:void(0);" class="d-block text-primary kt_user_button"><?=$data["boss_name"];?></a>
                                                    </td>
													<td class="d-none d-sm-table-cell">
                                                       <a href="javascript:void(0);" class="d-block text-primary kt_user_button"><?=$data["charge_name"];?></a>
                                                    </td>
													<td class="d-none d-sm-table-cell">
                                                       <a href="javascript:void(0);" class="d-block text-primary kt_user_button"><?=$data["charge_name"];?></a>
                                                    </td> 
													<td class="d-none d-sm-table-cell"><?=$data["end_date"];?><br /><?=number_format($remain_days);?></td>
													<td class="d-none d-sm-table-cell">
														<strong style="color:#ff6c00;"><?=$total_part;?></strong> / <strong style="color:#0075c8;"><?=$part_cnt;?></strong>
													</td>
													<td class="d-none d-sm-table-cell">
														<strong style="color:#ff6c00;"><?=$total_staff;?></strong> / <strong style="color:#0075c8;"><?=$staff_cnt;?></strong>
													</td>
													<td class="d-none d-sm-table-cell">
														<?=$use_price;?>
													</td>													
                                                    <td class="d-none d-md-table-cell">
                                                        <div
                                                            class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
                                                            <input class="form-check-input w-30px h-20px" type="checkbox" 
																onclick="<?=$btn_auth;?>" <?=$data['auth_yn']=='Y' ? 'checked="checked"' : ""?>
															/>
                                                        </div>
                                                    </td>
                                                    <td class="px-4">
                                                        <div class="row">
															<!--div class="col-6 pe-1">
                                                                <a href="javascript:void(0);" onclick="<?=$btn_menu;?>"
                                                                    class="w-100 btn btn-sm btn-primary px-2 py-1 fs-8">
                                                                    메뉴설정
                                                                </a>
                                                            </div-->

                                                            <div class="col-6 pe-1">
                                                                <a href="javascript:void();" onclick="data_form_open(<?=$data['comp_idx']?>)"
                                                                    class="w-100 btn btn-sm btn-primary px-2 py-1 fs-8">
                                                                    수정
                                                                </a>
                                                            </div>
                                                            <div class="col-6 ps-1">
                                                                <button type="button" onclick="<?=$btn_delete;?>"
																	data-bs-toggle="modal" data-bs-target="#kt_modal_leave"
                                                                    class="w-100 btn btn-sm btn-danger px-2 py-1 fs-8">
                                                                    삭제
                                                                </button>
                                                            </div>
                                                            <!--div class="col-12 pt-2">
                                                                <a href="#"
                                                                    class="w-100 btn btn-sm btn-info px-2 py-1 fs-8">
                                                                    에이전트
                                                                </a>
                                                            </div-->
                                                        </div>
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
                                        <!-- //거래처등록/수정 -->



                                    </div>
                                </div>
                            </div>
                            <!--// Content container -->
                        </div>
                        <!--// Content -->
                    </div>
                    <!--// Content wrapper -->



<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>
