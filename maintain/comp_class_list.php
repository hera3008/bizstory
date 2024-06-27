<?
/*
	생성 : 2012.11.02
	생성 : 2013.05.22
	위치 : 설정폴더 > 업체관리 > 업체분류 - 목록
*/
	/*
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";
	*/
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

	$where = "";
	$list = company_class_data('list', $where, '', '', '');

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="popupform_open(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>
										<form id="form-position" name="form-position" method="post" action="#">
                                            <table
                                                class="table align-middle table-row-bordered ls-n2 fs-7 text-gray-700 gy-3 gx-0"
                                                id="kt_table">
                                                <thead>
                                                    <tr class="text-gray-900 fw-semibold text-uppercase bg-gray-100 border-top-2 border-secondary">
                                                        <th class="min-w-50px w-60px text-center">순서</th>
                                                        <th class="min-w-150px mw-250px text-center">분류명</th>
														<th class="min-w-50px w-60px text-center">메인화면</th>
														<th class="min-w-50px w-60px text-center">지사수</th>
														<th class="min-w-50px w-60px text-center">거래처수</th>
														<th class="min-w-50px w-60px text-center">직원수</th>
														<th class="min-w-50px w-60px text-center">배너수</th>
														<th class="min-w-50px w-60px text-center">저장공간</th>
														<th class="min-w-50px w-60px text-center">뷰어</th>
														<th class="min-w-50px w-60px text-center">기본가격</th>
                                                        <th class="min-w-50px w-md-60px text-center">보기</th>
                                                        <th class="min-w-50px w-md-60px d-none d-sm-table-cell text-center">기본</th>
														<!--th class="min-w-50px w-60px text-center">메뉴설정</th-->
                                                        <th class="min-w-90px w-md-125px text-center">관리</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center">
												<?
													$i = 0;
													if ($list["total_num"] == 0) {
												?>
													<tr>
                                                        <td colspan="13">등록된 데이타가 없습니다.</td>
                                                    </tr>
												<?
													}
													else
													{
														$i = 1;
														$num = $list["total_num"];
														foreach($list as $k => $data)
														{
															if (is_array($data))
															{
																$sort_data = query_view("select min(sort) as min_sort, max(sort) as max_sort from company_class where del_yn = 'N' and up_code_idx = '" . $data["up_code_idx"] . "'");

																$code_idx = $data['code_idx'];
																$url_menu = $local_dir . '/bizstory/maintain/comp_class_menu.php';

																if ($auth_menu['mod'] == "Y")
																{
																	$btn_up      = "check_code_data('sort_up', '', '" . $code_idx . "', '')";
																	$btn_down    = "check_code_data('sort_down', '', '" . $code_idx . "', '')";
																	$btn_view    = "check_code_data('check_yn', 'view_yn', '" . $code_idx . "', '" . $data["view_yn"] . "')";
																	$btn_viewer  = "check_code_data('check_yn', 'viewer_yn', '" . $code_idx . "', '" . $data["viewer_yn"] . "')";
																	$btn_default = "check_code_data('check_yn', 'default_yn', '" . $code_idx . "', '" . $data["default_yn"] . "')";
																	$btn_modify  = "popupform_open('" . $code_idx . "')";
																	$btn_menu    = "other_page_open('" . $code_idx . "', '" . $url_menu . "')";
																}
																else	
																{
																	$btn_up      = "check_auth_popup('modify')";
																	$btn_down    = "check_auth_popup('modify')";
																	$btn_view    = "check_auth_popup('modify')";
																	$btn_viewer  = "check_auth_popup('modify')";
																	$btn_default = "check_auth_popup('modify')";
																	$btn_modify  = "check_auth_popup('modify')";
																	$btn_menu    = "check_auth_popup('modify')";
																}

																if ($auth_menu['del'] == "Y" && $data["import_yn"] == 'N') $btn_delete = "check_delete('" . $code_idx . "')";
																else $btn_delete = "check_auth_popup('delete');";
																
																if($data["menu_depth"] == 1)
																{
																	$menu_depth = "<span class=\"text-gray-900 fw-bold\"><span class=\"fs-8 badge badge-outline badge-primary me-1 text-center\">{$data['menu_depth']}</span>{$data['code_name']}</span>";
																}
																else if($data["menu_depth"] == 2)
																{
																	$menu_depth = "<span class=\"text-gray-800\"><span class=\"fs-8 badge badge-outline badge-success me-1 ms-3 text-center\">{$data['menu_depth']}</span>{$data['code_name']}</span>";
																}
																else if($data["menu_depth"] == 3)
																{
																	$menu_depth = "<span class=\"text-gray-800\"><span class=\"fs-8 badge badge-outline badge-warning me-1 ms-6 text-center\">{$data['menu_depth']}</span>{$data['code_name']}</span>";
																}
																else
																{
																	$menu_dmenu_depthept = "<span class=\"text-gray-900 fw-bold\"><span class=\"fs-8 badge badge-outline badge-primary me-1 text-center\">{$data['menu_depth']}</span>{$data['code_name']}</span>";
																}
												?>

 

													 <tr>
                                                        <td class="sorter"></td>
                                                        <td class="text-start ps-2"><?=$menu_depth?></td>
												<?
																if ($data['menu_num'] == 0) 
																{
												?>
														<td><span class="num"><?=$data['main_type'];?></span></td>
														<td><span class="num"><?=$data['part_num'];?></span></td>
														<td><span class="num"><?=$data['client_num'];?></span></td>
														<td><span class="num"><?=$data['staff_num'];?></span></td>
														<td><span class="num"><?=$data['banner_num'];?></span></td>
														<td><span class="num"><?=$data['volume_num'];?></span></td>
														<td>
															<div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
                                                                <input class="form-check-input w-25px h-15px w-xl-30px h-xl-20px" type="checkbox" id="viewer_Switch_<?=$code_idx?>" <?=$data["viewer_yn"]=='Y'?'checked="checked"':""?> onclick="<?=$btn_viewer;?>"/>
                                                            </div>
														</td>
														<td><span class="eng right"><?=number_format($data['default_price']);?></span></td>
														<td>
															<div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
                                                                <input class="form-check-input w-25px h-15px w-xl-30px h-xl-20px" type="checkbox" id="view_Switch_<?=$code_idx?>" <?=$data["view_yn"]=='Y'?'checked="checked"':""?> onclick="<?=$btn_view;?>"/>
                                                            </div>
														</td>
														<td>
															<div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
                                                                <input class="form-check-input w-25px h-15px w-xl-30px h-xl-20px" type="checkbox" id="default_Switch_<?=$code_idx?>" <?=$data["default_yn"]=='Y'?'checked="checked"':""?> onclick="<?=$btn_default;?>" />
                                                            </div>
														</td>
														<!--td><a href="javascript:void(0);" onclick="<?=$btn_menu;?>" class="btn_con_violet"><span>메뉴설정</span></a></td-->
														<td>
															<button type="button" data-bs-idx="<?=$code_idx?>" data-bs-toggle="modal" data-bs-target="#kt_modal_position" class="btn btn-sm btn-primary px-3 py-1 fs-8">수정</button>
															<?if($data["import_yn"] == 'N'){?>
															<button type="button" class="btn btn-sm btn-danger px-3 py-1 fs-8" onclick="<?=$btn_delete;?>">삭제</button>
															<?}?>
														</td>
												<?				}
																else
																{
												?>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<!--td>&nbsp;</td-->
														<td>
															<button type="button" data-bs-toggle="modal" data-bs-target="#kt_modal_position" class="btn btn-sm btn-primary px-3 py-1 fs-8">수정</button>
														</td>
												<?
																}
												?>
													</tr>
												<?
														$num--;
														$i++;
												?>
                                                        
												<?
														}
													}

												}
												?>
                                                   

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </form>



