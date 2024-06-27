<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 거래처관리 > 거래처분류 - 목록
*/
	//require_once "../common/setting.php";
	//require_once "../common/no_direct.php";
	//require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];

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

	$where = " and ccg.comp_idx = '" . $code_comp . "'";
	$list = company_client_group_data('list', $where, '', '', '');


	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="popupform_open(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>

                                        <form id="form-position" name="form-position" method="post" action="#">
                                            <table
                                                class="table align-middle table-row-bordered ls-n2 fs-7 text-gray-700 gy-3 gx-0" id="kt_table">
                                                <thead>
                                                    <tr class="text-gray-900 fw-semibold text-uppercase bg-gray-100 border-top-2 border-secondary">
                                                        <th class="min-w-50px w-60px text-center">순서</th>
                                                        <th class="min-w-150px mw-250px text-center">분류명</th>
                                                        <th class="min-w-50px w-md-60px text-center">보기</th>
                                                        <th class="min-w-50px w-md-60px d-none d-sm-table-cell text-center">기본</th>
                                                        <th class="min-w-90px w-md-125px text-center">관리</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center">
                                                <?
                                                    $i = 0;
                                                    if ($list["total_num"] == 0) {
                                                ?>
                                                        <tr>
                                                            <td colspan="7">등록된 데이타가 없습니다.</td>
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
                                                                $ccg_idx = $data['ccg_idx'];
                                                                $sort_data = query_view("select min(sort) as min_sort, max(sort) as max_sort from company_client_group where del_yn = 'N' and comp_idx ='" . $code_comp . "' and part_idx ='" . $code_part . "' and up_ccg_idx = '" . $data["up_ccg_idx"] . "'");

                                                                if ($auth_menu['mod'] == "Y")
                                                                {
                                                                    $btn_up      = "check_code_data('sort_up', '', '" . $ccg_idx . "', '')";
                                                                    $btn_down    = "check_code_data('sort_down', '', '" . $ccg_idx . "', '')";
                                                                    $btn_bold    = "check_code_data('check_yn', 'code_bold', '" . $ccg_idx . "', '" . $data["code_bold"] . "')";
                                                                    $btn_view    = "check_code_data('check_yn', 'view_yn', '" . $ccg_idx . "', '" . $data["view_yn"] . "')";
                                                                    $btn_default = "check_code_data('check_yn', 'default_yn', '" . $ccg_idx . "', '" . $data["default_yn"] . "')";
                                                                    $btn_modify  = "popupform_open('" . $ccg_idx . "')";
                                                                }
                                                                else
                                                                {
                                                                    $btn_up      = "check_auth_popup('modify')";
                                                                    $btn_down    = "check_auth_popup('modify')";
                                                                    $btn_bold    = "check_auth_popup('modify')";
                                                                    $btn_view    = "check_auth_popup('modify')";
                                                                    $btn_default = "check_auth_popup('modify')";
                                                                    $btn_modify  = "check_auth_popup('modify')";
                                                                }

                                                                if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $ccg_idx . "')";
																else $btn_delete = "check_auth_popup('delete');";

																if($data["menu_depth"] == 1)
																{
																	$menu_depth = "<span class=\"text-gray-900 fw-bold\"><span class=\"fs-8 badge badge-outline badge-primary me-1 text-center\">{$data['menu_depth']}</span>{$data['group_name']}</span>";
																}
																else if($data["menu_depth"] == 2)
																{
																	$menu_depth = "<span class=\"text-gray-800\"><span class=\"fs-8 badge badge-outline badge-success me-1 ms-3 text-center\">{$data['menu_depth']}</span>{$data['group_name']}</span>";
																}
																else if($data["menu_depth"] == 3)
																{
																	$menu_depth = "<span class=\"text-gray-800\"><span class=\"fs-8 badge badge-outline badge-warning me-1 ms-6 text-center\">{$data['menu_depth']}</span>{$data['group_name']}</span>";
																}
																else if($data["menu_depth"] == 4)
																{
																	$menu_depth = "<span class=\"text-gray-800\"><span class=\"fs-8 badge badge-outline badge-warning me-1 ms-8 text-center\">{$data['menu_depth']}</span>{$data['group_name']}</span>";
																}
																else
																{
																	$menu_depth = "<span class=\"text-gray-900 fw-bold\"><span class=\"fs-8 badge badge-outline badge-primary me-1 text-center\">{$data['menu_depth']}</span>{$data['group_name']}</span>";
																}
                                                ?>
                                                            <tr>
																<td class="sorter"></td>
																<td class="text-start ps-2"><?=$menu_depth?></td>
																<td>
																	<div
																		class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
																		<input
																			class="form-check-input w-25px h-15px w-xl-30px h-xl-20px" type="checkbox" id="view_Switch_<?=$ccg_idx?>" <?=$data["view_yn"]=='Y'?'checked="checked"':""?> onclick="<?=$btn_view;?>" />
																	</div>
																</td>
																<td class="d-none d-sm-table-cell">
																	<div
																		class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
																		<input
																			class="form-check-input w-25px h-15px w-xl-30px h-xl-20px" type="checkbox" id="default_Switch_<?=$ccg_idx?>" <?=$data["default_yn"]=='Y'?'checked="checked"':""?> onclick="<?=$btn_default;?>"/>
																	</div>
																</td>
																<td>
																	<button type="button" data-bs-idx="<?=$ccg_idx?>" data-bs-toggle="modal" data-bs-target="#kt_modal_position" class="btn btn-sm btn-primary px-3 py-1 fs-8">수정</button>
																	<?if($data['menu_num'] == 0){?>
																	<button type="button" class="btn btn-sm btn-danger px-3 py-1 fs-8" onclick="<?=$btn_delete;?>">삭제</button>
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
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </form>

