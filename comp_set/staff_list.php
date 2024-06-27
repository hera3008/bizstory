<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 직원관리 > 직원등록/수정 - 목록
*/
	//require_once "../common/setting.php";
	//require_once "../common/no_direct.php";
	//require_once "../common/member_chk.php";

	$code_comp     = $_SESSION[$sess_str . '_comp_idx'];
	//$code_part     = search_company_part($code_part);
	$code_part     = "";
	$set_staff_num = $comp_set_data['staff_cnt'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = "";
	$where .= $code_comp ? " and mem.comp_idx = '" . $code_comp . "'" : "";
	$where .= $code_part ? " and mem.part_idx = '" . $code_part . "'" : "";
	if ($shgroup != '' && $shgroup != 'all') $where .= " and mem.csg_idx = '" . $shgroup . "'"; // 부서
	if ($stext != '' && $swhere != '')
	{
		if ($swhere == 'mem.tel_num')
		{
			$stext = str_replace('-', '', $stext);
			$stext = str_replace('.', '', $stext);
			$where .= " and (
				replace(mem.tel_num, '-', '') like '%" . $stext . "%' or
				replace(mem.tel_num, '.', '') like '%" . $stext . "%' or
				replace(mem.hp_num, '-', '') like '%" . $stext . "%' or
				replace(mem.hp_num, '.', '') like '%" . $stext . "%'
			)";
		}
		else $where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'mem.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = 'mem.login_yn asc, ' . $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = member_info_data('list', $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

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
	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$page_where = " and mem.comp_idx = '" . $code_comp . "'";
		$page_data = member_info_data('page', $page_where);
		$btn_write = '<a href="javascript:void(0);" onclick="open_data_form(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>
 										<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();" class="form p-4 border bg-gray-100 rounded-2 mb-6 mb-lg-8">
										<?=$form_default;?>

                                            <div class="row gx-2">
                                                <div class="col-7 col-md-3 mb-2 mb-md-0">
                                                    <select class="form-select form-select-sm" name="shgroup"
                                                        id="search_shgroup" data-control="select2"
                                                        data-hide-search="true" aria-label="부서선택">
                                                        <option value="all">전체부서</option>
                                                        <option value="2">웹퍼블리셔</option>
                                                        <option value="1">부설연구소</option>
                                                        <option value="3">경영지원</option>
                                                    </select>
                                                </div>
                                                <div class="col-5 col-md-3 mb-2 mb-md-0">
                                                    <select class="form-select form-select-sm" name="swhere"
                                                        id="search_swhere" data-control="select2"
                                                        data-hide-search="true" aria-label="칼럼선택">
                                                        <option value="mem.mem_name">직원명</option>
                                                        <option value="mem.mem_id">아이디</option>
                                                        <option value="mem.mem_email">이메일</option>
                                                        <option value="mem.tel_num">연락처</option>
                                                        <option value="mem.address">주소</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative d-flex align-items-center">
                                                        <input type="text" class="form-control form-control-sm"
                                                            placeholder="키워드를 입력하세요" />
                                                        <button type="submit"
                                                            class="btn btn-sm btn-icon btn-dark position-absolute end-0 px-6 rounded-start-0"
                                                            aria-label="검색"><i
                                                                class="ki-outline ki-magnifier fs-3"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="d-flex flex-stack flex-wrap mb-4">
                                            <div class="d-flex align-items-center py-1">
                                                <select name="status" data-control="select2" data-hide-search="true"
                                                    class="form-select form-select-sm bg-gray-100 border-gray-300 w-125px">
                                                    <option value="부서선택" selected="">부서선택</option>
                                                    <option value="전체목록">전체목록</option>
                                                    <option value="부설연구소">부설연구소</option>
                                                    <option value="경영지원">경영지원</option>
                                                </select>
                                            </div>
                                            <div class="d-flex align-items-center py-1">
                                                <a href="javascript:void(0);" onclick="data_form_open('');" class="btn btn-sm btn-warning">
													<i class="ki-outline ki-pencil fs-6"></i> 등록
												</a> 
                                            </div>
                                        </div>

                                        <table class="table align-middle table-striped table-row-bordered ls-n2 fs-7 text-gray-700 gy-3 gx-0" id="kt_datatable">
                                            <thead>
                                                <tr class="text-gray-900 fw-semibold text-uppercase bg-gray-100 border-top-2 border-secondary">
                                                    <th class="min-w-50px mw-65px d-none d-xl-table-cell text-center">번호</th>
                                                    <th class="min-w-150px min-w-xl-200px text-center" data-priority="1">아이디</th>
                                                    <th class="min-w-55px mw-65px text-center">이름</th>
                                                    <th class="min-w-60px mw-70px d-none d-sm-table-cell text-center">직책</th>
                                                    <th class="min-w-90px mw-100px d-none d-xl-table-cell text-center">직원그룹</th>
                                                    <th class="min-w-100px mw-125px d-none d-md-table-cell text-center">연락처</th>
                                                    <th class="min-w-60px mw-65px d-none d-sm-table-cell text-center">재직</th>
                                                    <th class="min-w-50px min-w-md-70px mw-80px text-center"><span class="d-none d-sm-inline-block">메뉴</span>권한</th>
                                                    <th class="min-w-90px min-w-md-95px mw-100px mw-xl-125px text-center">관리</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
											<?
												$i = 0;
												if ($list["total_num"] == 0) {
											?>
													<tr>
														<td colspan="9">등록된 데이타가 없습니다.</td>
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
															$mem_idx  = $data['mem_idx'];
															$url_menu = $local_dir . '/bizstory/comp_set/staff_menu.php';

															if ($auth_menu['mod'] == "Y")
															{
																$btn_login  = "check_code_data('check_yn', 'login_yn', '" . $mem_idx . "', '" . $data["login_yn"] . "')";
																$btn_modify = "data_form_open('" . $mem_idx . "')";
																$btn_menu   = "other_page_open('" . $mem_idx . "', '" . $url_menu . "')";
																$btn_move   = "window.open('" . $local_dir . "/bizstory/comp_set/staff_chk.php?idx=" . $mem_idx . "', '_blank')";
															}
															else
															{
																$btn_login  = "check_auth_popup('modify')";
																$btn_modify = "check_auth_popup('modify')";
																$btn_menu   = "check_auth_popup('modify')";
																$btn_move   = "";
															}

															if ($auth_menu['del'] == "Y") $btn_delete = "check_staff_out('" . $mem_idx . "')";
															else $btn_delete = "check_auth_popup('delete');";

															$charge_str = staff_layer_form($mem_idx, '', 'N', $set_color_list2, 'stafflist', $data['mem_idx'], '');
															$mem_img = member_img_view($mem_idx, $comp_member_dir);
											?>
														<tr>
															<td class="d-none d-xl-table-cell"><?=$num;?></td>
															<td class="text-start ps-2">
																<a href="javascript:void(0);" class="text-gray-800 text-hover-primary ellipsis-1" onclick="data_form_open(<?=$mem_idx?>);">
																	<div class="d-none d-sm-inline-block symbol symbol-25px symbol-lg-35px symbol-circle me-2">
                                                                		<?=$mem_img['img_26'];?>
                                                            		</div>
																	<?=$data["mem_id"];?>
																</a>
															</td>
															<td><a href="javascript:void(0);" class="text-primary kt_user_button" data-bs-idx="<?=$mem_idx?>"><?=$charge_str;?></a></td>
															<td class="d-none d-sm-table-cell"><?=$data["duty_name"];?></td>
															<td class="d-none d-xl-table-cell"><?=$data["group_name"];?></td>
															<td class="d-none d-md-table-cell">
																<a href="tel:<?=$data["hp_num"];?>" class="text-gray-700 text-hover-primary phoneLink"><?=$data["hp_num"];?></a>
                                                    		</td>
															<td class="d-none d-sm-table-cell">
																<div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
																	<input class="form-check-input w-35px h-20px" type="checkbox" id="employment_Switch_<?=$mem_idx?>" 
																		value='Y' <?=$data["login_yn"] == 'Y' ? 'checked="checked"' : ''?> onclick="<?=$btn_login;?>" />
																</div>
															</td>
															<td>
																<button type="button" onclick="<?=$btn_menu;?>" data-bs-toggle="modal"
																	data-bs-target="#kt_modal_menuPermission" class="btn btn-sm btn-info px-3 py-1 fs-8">
																	<span class="d-none d-sm-inline-block">메뉴</span>권한
																</button>
															</td>
															<td>
																<a href="javascript:void(0);" onclick="<?=$btn_modify;?>"
																	class="btn btn-sm btn-primary px-3 py-1 fs-8">
																	수정
																</a>
																<button type="button" onclick="<?=$btn_delete;?>" data-bs-toggle="modal" data-bs-target="#kt_modal_leave" class="btn btn-sm btn-danger px-3 py-1 fs-8">
																	퇴사
																</button>
																<?if ($btn_move != '') {?>
																	<a href="javascript:void(0);" onclick="<?=$btn_move;?>" class="btn btn-sm btn-primary px-3 py-1 fs-8"> 페이지보기 </a>
																<?}?>
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
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<div class="etc_bottom"><?=$btn_write;?></div>


<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>